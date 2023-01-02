<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ListModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Sight;
use App\Models\User;
use Carbon\Carbon;

class ActivityList extends ListModel
{
    use HasFactory;

    public ?Activity $activity;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        if($request->input('sight')){
            $sight = Sight::find($request->input('sight')) ?? null;
            if($sight) $this->sight = $sight;
        }
        if($request->input('user')){
            $user = User::find($request->input('user')) ?? null;
            if($user) $this->user = $user;
        }
        if($request->input('activity')){
            $activity = Activity::find($request->input('activity')) ?? null;
            if($activity) $this->activity = $activity;
        }
        if($request->input('route')){
            $route = Route::find($request->input('route')) ?? null;
            if($route) $this->route = $route;
        }
    }

    public function index()
    {
        $query = $this->activities_query()
            ->paginate(40)
            ->appends($this->request->query());

        $collection = $query->getCollection()->all();
        foreach($collection as &$entry) {
            $a = Activity::find($entry->id);
            $a->count = $entry->count;
            $a->sight_count = $entry->sight_count;
            $entry = $a;
        }
        $query->setCollection(collect($collection));

        return $query;
    }

    public function count() : int
    {
        return $this->activities_query()->count();
    }

    private function activities_query()
    {
        $query = DB::table('activities as a')
            ->leftjoin('visits as v','v.act_id','=','a.id')
            ->selectRaw('a.id as id,count(v.id) as count')
            ->selectRaw('(select count(*) from visits where act_id=a.id) as sight_count')
            ->groupBy('a.id')
            ->orderBy('a.start_date','desc');

        if(!empty($this->sight)) {
            $query = $query->where('v.sight_id', $this->sight->id);
        }
        if(!empty($this->user)) {
            $query = $query->where('a.user_id', $this->user->id);
        }
        if(!empty($this->activity)) {
            $query = $query->where('a.id', $this->activity->id);
        }
        if(!empty($this->route)) {
            $query = $query->join('route_passes as rs','rs.act_id','=','a.id')
            ->where('rs.route_id',$this->route->id);
        }

        if(!empty($this->timestamp)) {
            $from = Carbon::createFromTimestamp($this->timestamp)->subHours(5);
            $to = Carbon::createFromTimestamp($this->timestamp)->addHours(5);
            $query = $query->whereBetween('v.created_at',[$from,$to]);
        }

        return $query;
    }

    public function url($add=[])
    {
        return route('activities',$this->filters($add));
    }
}
