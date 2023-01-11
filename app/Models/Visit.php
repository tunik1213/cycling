<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sight;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Log;
use App\Notifications\VisitsFound;
use App\Models\User;

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

    // удаляет все посещения, начиная с указанной даты, и пересчитывает заезды заново
    public static function recalculate(string $dateFrom)
    {
        db::statement('delete from visits where created_at > ?',[$dateFrom]);

        $acts = Activity::where('created_at','>',$dateFrom)->get();
        
        foreach (Sight::all() as $s) {
            foreach ($acts as $a) {
                Self::searchInvites($a,$s);
            }
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

            User::update_visits_verify($key);
        }
    }

    private static function _importVisits($rawData)
    {
        $data = json_decode($rawData);
        $created_visits = [];
        
        foreach($data->visits as $v) {
            $found = Visit::where('act_id',$v->activity)
                ->where('sight_id',$v->sight)
                ->first();
            if($found != null) continue;

            $v = Visit::create([
                'act_id'=> $v->activity,
                'sight_id'=> $v->sight
            ]);
            array_push($created_visits,$v->id);
        }

    	if(count($created_visits) > 0) {

            // отправим уведомления юзерам о том, что найдены новые посещения
            $result = DB::select('
            select 
                a.user_id,
                count(distinct v.sight_id) count
            from visits v
            join activities a on a.id = v.act_id
            where v.id in ('.implode(',',$created_visits).')
            group by a.user_id
            having count(distinct v.sight_id) > 0');

            foreach ($result as $r) {
                $user = User::find($r->user_id);
                if($user) $user->notify(new VisitsFound($r->count));
            }

            // проверим прохождения маршрутов по найденным посещениям локаций
    	    DB::Statement('
                insert route_passes (route_id,act_id)
        
    	    with rs_counts as (
    	        select rs.route_id,count(*) as cnt 
        	        from route_sight rs 
        	        group by rs.route_id 
                ) 
    	    select rs.route_id, v.act_id
                from route_sight rs 
    	    cross join visits v on v.sight_id = rs.sight_id 
                left join rs_counts on rs_counts.route_id = rs.route_id 
    	    where v.id in ('.implode(',',$created_visits).')
                group by rs.route_id, v.act_id
    	    having count(*) = avg(rs_counts.cnt);
                ');
    	}
    }


// finding visits via aws
    // by user
    public static function findVisitsUser(User $user)
    {
        if(empty($user->activities->count())) return;

        $data = [
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

        Self::sendToAWS($json,$filename='user'.$user->id);

    }

    // by sight
    public static function findVisitsSight(Sight $sight)
    {
        $data = [
            'activities' => [],
            'sights' => [],
        ];

        $acts = Activity::orderBy('id')->get();
        foreach ($acts as $a) {
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

        Self::sendToAWS($json,$filename='sight'.$sight->id);

        
    }

    // by activities
    public static function findVisitsActivities(Array $acts, ?User $user=null)
    {
        $data = [
            'activities' => [],
            'sights' => [],
        ];

        foreach ($acts as $a) {
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

        if($user) {
            $filename = 'user'.$user->id;
        } else {
            $filename='activities'.MD5(microtime());
        }

        Self::sendToAWS($json,$filename);
        
    }


    private static function sendToAWS($json, $filename)
    {
        if (env('APP_DEBUG')) {
            file_put_contents('/var/www/html/cycling/tmp/'.$filename, $json);
        } else {
            $response = Http::withBody(
                $json,'application/json'
            )->put('https://a2afp1u4hg.execute-api.eu-central-1.amazonaws.com/v1/checkinvites/'.$filename);
        }
    }
}
