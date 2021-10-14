<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\User;
use App\Models\Sight;

class ActivityController extends Controller
{
    public function list(Request $request)
    {
        $sight_id = $request->input('sight');
        $user_id = $request->input('user');

        $acts = Activity::join('visits','visits.act_id','=','activities.id')
            ->when($sight_id, function ($query, $sight_id) {
                return $query->where('visits.sight_id', $sight_id);
            })
            ->when($user_id, function ($query, $user_id) {
                return $query->where('activities.user_id', $user_id);
            })
            ->orderBy('activities.start_date','desc')
            ->paginate(40)
            ->appends(request()->query());

        return view('activities.list',[
            'activities'=>$acts,
            'user'=>User::find($user_id),
            'sight'=>Sight::find($sight_id)
        ]);

    }
}
