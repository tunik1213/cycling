<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sight;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;

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
}
