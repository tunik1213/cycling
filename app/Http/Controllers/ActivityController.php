<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\User;
use App\Models\Sight;
use App\Models\ActivityList;
use App\Models\SightList;

class ActivityController extends Controller
{
    public function list(Request $request)
    {
        $list = new ActivityList($request);
        

        return view('activities.list',[
            'actList' => $list
        ]);

    }

    public function show(Request $request, int $act_id)
    {
        $act = Activity::find($act_id);
        if (empty($act)) return abort(404);

        $sights = new SightList($request);
        $sights->activity = $act;

        return view('activities.show',[
                'activity' => $act,
                'sights' => $sights
        ]);
    }
}
