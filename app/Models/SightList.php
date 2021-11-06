<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ListModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\SightCategory;
use App\Models\SightSubCategory;

class SightList extends ListModel
{
    use HasFactory;

    public SightCategory $category;
    public SightSubCategory $subcategory;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        if($request->input('user')) {
            $user = User::find($request->input('user')) ?? null;
            if($user) $this->user = $user;
        }

        $category = null;
        if($request->input('category')) {
            $category = Category::find($request->input('category')) ?? null;
            if($category) $this->category = $category;
        }

        $subcategory = null;
        if($request->input('subcategory')) {
            $subcategory = SubCategory::find($request->input('subcategory')) ?? null;
            if($subcategory) $this->subcategory = $subcategory;
        }

    }

    public function index()
    {
        $sights = DB::table('sights as s')
            ->join('visits as v','v.sight_id','=','s.id')
            ->join('activities as a','a.id','=','v.act_id')
            ->join('users as u','u.id','=','a.user_id')
            ->join('districts as d','d.id','=','s.district_id')
            ->selectRaw('s.id,count(v.id) as count')
            ->groupBy('s.id')
            ->having('count','>',0)
            ->orderByRaw('count desc,s.name');

        if(!empty($this->user))
            $sights = $sights->where('a.user_id',$this->user->id);

        if(!empty($this->district)) {
            $sights = $sights->where('d.id',$this->district->id);
        }
        if(!empty($this->area)) {
            $sights = $sights->where('d.area_id',$this->area->id);
        }
        if(!empty($this->category)) {
            $sights = $sights->where('s.category_id',$this->category->id);
        }
        if(!empty($this->subcategory)) {
            $sights = $sights->where('s.sub_category_id',$this->subcategory->id);
        }

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
