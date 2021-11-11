<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ListModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Sight;

class ActivityList extends ListModel
{
    use HasFactory;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        if($request->input('sight')){
            $sight = Sight::find($request->input('sight')) ?? null;
            if($sight) $this->sight = $sight;
        }

    }

    public function index()
    {

        $query = DB::table('activities as a')
            ->leftjoin('visits as v','v.act_id','=','a.id')
            ->selectRaw('a.id as id,count(v.id) as count')
            ->groupBy('a.id')
            ->orderBy('a.start_date','desc');

        if(!empty($this->sight)) {
            $query = $query->where('v.sight_id', $this->sight->id);
        }
        if(!empty($this->user)) {
            $query = $query->where('a.user_id', $this->user->id);
        }
        $query = $query
            ->paginate(40)
            ->appends($this->request->query());

        $collection = $query->getCollection()->all();
        foreach($collection as &$entry) {
            $a = Activity::find($entry->id);
            $a->count = $entry->count;
            $entry = $a;
        }
        $query->setCollection(collect($collection));

        return $query;
    }
}
