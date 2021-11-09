<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sight;
use App\Models\Activity;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'act_id',
        'sight_id'
    ];
    

    // проверяет расположены ли координаты $sight на маршруте $act
    // и если да - то проставляет им соответствие 
    public static function searchInvites(Activity $act, Sight $sight) : void 
    {
        $v = Visit::where('act_id',$act->id)
            ->where('sight_id',$sight->id)
            ->first();
        if($v != null) return;

        $point['lat'] = $sight->lat;
        $point['lng'] = $sight->lng;

        $poly = \Polyline::decode($act->summary_polyline);
        $pairedPoly = [];
        for ($i=0;$i<(count($poly));$i=$i+2) {
            array_push($pairedPoly,['lat'=>$poly[$i],'lng'=>$poly[$i+1]]);
        }

        $match = \GeometryLibrary\PolyUtil::isLocationOnPath($point,$pairedPoly,$tolerance = 25);

        if ($match) {
            Visit::create([
                'act_id'=> $act->id,
                'sight_id'=> $sight->id
            ]);
        }
    }
}
