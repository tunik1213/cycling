<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sight;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Aws\S3\S3Client;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'act_id',
        'sight_id'
    ];
    
    public static function checkInvite(Activity $act, Sight $sight) : bool
    {
        $point['lat'] = $sight->lat;
        $point['lng'] = $sight->lng;

        $poly = \Polyline::decode($act->summary_polyline);
        $pairedPoly = [];
        for ($i=0;$i<(count($poly));$i=$i+2) {
            array_push($pairedPoly,['lat'=>$poly[$i],'lng'=>$poly[$i+1]]);
        }

        return \GeometryLibrary\PolyUtil::isLocationOnPath($point,$pairedPoly,$tolerance = $sight->radius,$geodesic = false);

    }

    // проверяет расположены ли координаты $sight на маршруте $act
    // и если да - то проставляет им соответствие 
    public static function searchInvites(Activity $act, Sight $sight) : void 
    {
        $v = Visit::where('act_id',$act->id)
            ->where('sight_id',$sight->id)
            ->first();
        if($v != null) return;

        $match = Self::checkInvite($act, $sight);

        if ($match) {
            Visit::create([
                'act_id'=> $act->id,
                'sight_id'=> $sight->id
            ]);
        }
    }

    public static function removeDuplicates()
    {
        $res = DB::select('select act_id,sight_id,count(*) as count
from visits
group by act_id,sight_id
having count(*) > 1');
        
        foreach($res as $r) {
            $q = 'delete from visits where act_id = '.$r->act_id.' and sight_id = '.$r->sight_id.' limit '.($r->count-1).';';
            echo $q.'<br/>';
            DB::statement($q);
        }

    }

    // забирает из aws s3 все рассчитанные посещения
    public static function retrievеAllVisits()
    {
        $bucketName = 'visits12345';

        $sdk = new \Aws\Sdk([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION')
        ]);

        $s3Client = $sdk->createS3();
        
        $result = $s3Client->listObjects([
            'Bucket' => $bucketName,
        ]);

        $objList = $result->get('Contents');
	if(empty($objList)) return;

        foreach($objList as $objEntry) {
            $key = $objEntry['Key'];
            $result = $s3Client->getObject([
                'Bucket' => $bucketName,
                'Key'    => $key
            ]);
            $fileContent = $result['Body'];

            Self::_importVisits($fileContent);

            $result = $s3Client->deleteObject([
                'Bucket' => $bucketName,
                'Key'    => $key
            ]);

        }
    }

    private static function _importVisits($rawData)
    {
        $data = json_decode($rawData);
        
        foreach($data->visits as $v) {
            $found = Visit::where('act_id',$v->activity)
                ->where('sight_id',$v->sight)
                ->first();
            if($found != null) continue;

            Visit::create([
                'act_id'=> $v->activity,
                'sight_id'=> $v->sight
            ]);
        }

    }


// finding visits via aws
    // by user
    public static function findVisitsUser(User $user)
    {
        $data = [
            'user' => $user->id,
            'activities' => [],
            'sights' => [],
        ];

        foreach ($user->activities as $a) {
            array_push($data['activities'],
            [
                'id' => $a->id,
                'polyline' => $a->summary_polyline
            ]);
        }

        $sights = Sight::orderBy('id')->get();
        foreach($sights as $s) {
            array_push($data['sights'],
            [
                'id' => $s->id,
                'lat' => $s->lat,
                'lng' => $s->lng,
                'radius' => $s->radius
            ]);
        }

        $json = collect($data)->toJson();

        $response = Http::withBody(
            $json,'application/json'
        )->put('https://a2afp1u4hg.execute-api.eu-central-1.amazonaws.com/v1/checkinvites/user'.$user->id);

    }
    // by sight
    public static function findVisitsSight(Sight $sight)
    {
        
    }
}
