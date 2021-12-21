<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ListModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AuthorList extends ListModel
{
    use HasFactory;

    public function __construct(Request $request)
    {
        parent::__construct($request);

    }

    public function index()
    {
        $users = DB::table('sights as s')
            //->leftJoin('visits as v','v.sight_id','=','s.id')
            ->join('districts as d','d.id','=','s.district_id')
            ->join('users as u','u.id','=','s.user_id')
            ->whereNotNull('u.avatar')
            ->selectRaw('u.id as id')
            ->selectRaw('count(distinct(s.id)) as count')
            ->groupBy('u.id')
            ->orderByRaw('count(distinct(s.id))  desc');


        if(!empty($this->district)) {
            $users = $users->where('d.id',$this->district->id);
        }
        if(!empty($this->area)) {
            $users = $users->where('d.area_id',$this->area->id);
        }

        if(empty($this->limit)) {
            $users = $users->paginate(24)->appends($this->request->query());
        } else {
            $users = $users->limit($this->limit)->get();
            $users = new \Illuminate\Pagination\LengthAwarePaginator($users,$this->limit,$this->limit);
        }
        $collection = $users->getCollection()->all();
        foreach($collection as &$entry) {
            $u = User::find($entry->id);
            $u->count_link = view('user.count_link',[
                'userList' => $this,
                'count' => $entry->count,
                'user' => $u,
                'getParams' => $this->filters(['author'=>$u->id])
            ]);
            $entry = $u;
        }
        $users->setCollection(collect($collection));

        return $users;
    }

    public function title()
    {
        return 'Найкращi автори';
    }

    public function count_link_text()
    {
        return 'пам\'яток додано';
    }
    public function listRoute()
    {
        return route('authors.list',$this->filters());
    }
}
