<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ListModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DistrictList extends ListModel
{
    use HasFactory;

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index()
    {

        $query = DB::table('districts as d')
            ->leftjoin('sights as s','d.id','s.district_id')
            ->leftjoin('visits as v','v.sight_id','=','s.id')
            ->leftjoin('activities as act','act.id','=','v.act_id')
            ->selectRaw('d.id as id,count(distinct s.id) as sight_count')
            ->orderBy('sight_count','desc')
            ->groupBy('d.id');

        if(!empty($this->sight)) {
            $query = $query->where('v.sight_id', $this->sight->id);
        }
        if(!empty($this->user)) {
            $query = $query->where('act.user_id', $this->user->id);
        }
        if(!empty($this->activity)) {
            $query = $query->where('act.id', $this->activity->id);
        }
        if(!empty($this->route)) {
            $query = $query->join('route_passes as rs','rs.act_id','=','act.id')
            ->where('rs.route_id',$this->route->id);
        }

        $query = $query
            ->paginate(40)
            ->appends($this->request->query());

        $collection = $query->getCollection()->all();
        foreach($collection as &$entry) {
            $d = District::find($entry->id);
            $d->sight_count = $entry->sight_count ?? 0;
            $entry = $d;
        }
        $query->setCollection(collect($collection));

        return $query;
    }

    public function title() : string
    {
        $result = 'Список районiв';

        if(!empty($this->user))
            $result .= ', в яких катає '.$this->user->fullname;

        return $result;
    }

    public function h1() : string
    {
        $result = 'Список районiв';

        if(!empty($this->user))
            $result .= ', в яких катає '.$this->user->link;

        return $result;
    }

}
