<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ListModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserList extends ListModel
{
    use HasFactory;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $sight = Sight::find($request->input('sight')) ?? null;
        if($sight) $list->sight = $sight;

    }

    public function index()
    {
        $users = DB::table('visits as v')
            ->join('activities as a','a.id','=','v.act_id')
            ->join('sights as s','s.id','=','v.sight_id')
            ->join('districts as d','d.id','=','s.district_id')
            ->selectRaw('a.user_id as id')
            ->groupBy('a.user_id')
            ->orderByRaw('2 desc');

        if(empty($this->sight)) {
            $users = $users->selectRaw('count(distinct(v.sight_id)) as count');
        } else {
            $users = $users->where('v.sight_id',$this->sight->id);
            $users = $users->selectRaw('count(v.sight_id) as count');
        }

        if(!empty($this->district)) {
            $users = $users->where('d.id',$this->district->id);
        }
        if(!empty($this->area)) {
            $users = $users->where('d.area_id',$this->area->id);
        }

        if(empty($this->limit)) {
            $users = $users->paginate(24);
        } else {
            $users = $users->limit($this->limit)->get();
            $users = new \Illuminate\Pagination\LengthAwarePaginator($users,$this->limit,$this->limit);
        }
        $collection = $users->getCollection()->all();
        foreach($collection as &$entry) {
            $u = User::find($entry->id);
            $u->count = $entry->count;
            $entry = $u;
        }
        $users->setCollection(collect($collection));

        return $users;
    }

    public function title()
    {
        return 'Топ мандрiвникiв';
    }
}
