<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ListModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SightList extends ListModel
{
    use HasFactory;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $user = User::find($request->input('user')) ?? null;
        if($user) $this->user = $user;

    }

    public function index()
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

    public function title()
    {
        return 'Список пам\'яток';
    }
}
