<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sight;
use App\Models\Activity;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;

class CrontabController extends Controller
{
    public $gm;

    public function __construct() 
    {
        $this->gm = \GoogleMaps::load('directions');
    }

    public function checkInvites() : void 
    {

        $maxSigEver = DB::select('select max(id) as id from sights')[0]->id;
        $maxActivityEver = DB::select('select max(id) as id from activities')[0]->id;

        $uncheckedActivities = Activity::where('max_sight_verified','<',$maxSigEver)->get();

        foreach($uncheckedActivities as $act) {
            echo('checking activity '.$act->id.'<br/>');

            $uncheckedSights = Sight::where('max_activity_verified','<',$maxActivityEver)->get();

            foreach($uncheckedSights as $sight) {
                Visit::searchInvites($act,$sight);
            }
        }
    }

   
}

