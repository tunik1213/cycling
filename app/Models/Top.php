<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Sight;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;

class Top
{
    use HasFactory;

    public User $user;
    public Sight $sight;
    public int $limit;

    public function test()
    {
        dd($this->limit);

    }   


    public function users()
    {
        $users = DB::table('visits as v')
            ->join('activities as a','a.id','=','v.act_id')
            ->selectRaw('a.user_id as id,count(distinct(v.sight_id)) as count')
            ->groupBy('a.user_id')
            ->orderByRaw('2 desc');
        if(!empty($this->sight))
            $users = $users->where('a.user_id',$this->user->id);

        if(empty($this->limit)) {
            $users = $users->paginate(24);
        } else {
            $users = $users->limit($this->limit)->get();
            $users = new \Illuminate\Pagination\LengthAwarePaginator($users,$this->limit,$this->limit);
        }
        $collection = $users->getCollection()->all();
        foreach($collection as &$entry) {
            $u = User::find($entry->id);
            $u->dopInformation = view('visits.count_link',['user'=>$u,'count'=>$entry->count]);
            $entry = $u;
        }
        $users->setCollection(collect($collection));

        return $users;

    }

    public function sights()
    {
        $sights = DB::table('sights as s')
            ->join('visits as v','v.sight_id','=','s.id')
            ->join('activities as a','a.id','=','v.act_id')
            ->selectRaw('s.id,count(v.id) as count')
            ->groupBy('s.id')
            ->having('count','>',0)
            ->orderByRaw('count desc,s.name');
        if(!empty($this->user))
            $sights = $sights->where('a.user_id',$this->user->id);

        if(empty($this->limit)) {
            $sights = $sights->paginate(12);
        } else {
            $sights = $sights->limit($this->limit)->get();
            $sights = new \Illuminate\Pagination\LengthAwarePaginator($sights,$this->limit,$this->limit);
        }

        $collection = $sights->getCollection()->all();
        foreach($collection as &$entry) {
            $s = Sight::find($entry->id);
            $s->count = $entry->count;
            $entry = $s;
        }
        $sights->setCollection(collect($collection));

        return $sights;
    }

    private function params()
    {
        $result = [];
        if (!empty($this->user)) $result['user_id'] = $this->user->id;
        if (!empty($this->sight)) $result['sight_id'] = $this->sight->id;

        return $result;
    }

}
