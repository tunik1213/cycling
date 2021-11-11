<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\User;
use App\Models\Sight;
use App\Models\ActivityList;

class ActivityController extends Controller
{
    public function list(Request $request)
    {
        $list = new ActivityList($request);
        

        return view('activities.list',[
            'actList' => $list
        ]);

    }
}
