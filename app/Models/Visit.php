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
        $point['lng'] = $sight->lat;
        $point['lat'] = $sight->lng;

        $poly = \Polyline::decode($act->summary_polyline);
        $pairedPoly = [];
        for ($i=0;$i<(count($poly));$i=$i+2) {
            // внимание! здесь намерено перепутаны местами широта и долгота, т.к. только в этом случае либа работает правильно
            array_push($pairedPoly,['lat'=>$poly[$i+1],'lng'=>$poly[$i]]);
        }

        $match = \GeometryLibrary\PolyUtil::isLocationOnPath($point,$pairedPoly,$tolerance = 10);

        if ($match) {
            Visit::create([
                'act_id'=> $act->id,
                'sight_id'=> $sight->id
            ]);
        }
    }
}
