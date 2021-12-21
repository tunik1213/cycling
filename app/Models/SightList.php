<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ListModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\SightCategory as Category;
use App\Models\SightSubCategory as SubCategory;
use App\Models\User;
use App\Models\Activity;

class SightList extends ListModel
{
    use HasFactory;

    public ?SightCategory $category;
    public ?SightSubCategory $subcategory;
    public ?User $author;
    public ?Activity $activity;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        if($request->input('user')) {
            $user = User::find($request->input('user')) ?? null;
            if($user) $this->user = $user;
        }

        $this->category = null;
        if($request->input('category')) {
            $category = Category::find($request->input('category')) ?? null;
            if($category) $this->category = $category;
        }

        $this->subcategory = null;
        if($request->input('subcategory')) {
            $subcategory = SubCategory::find($request->input('subcategory')) ?? null;
            if($subcategory) $this->subcategory = $subcategory;
        }

        $this->author = null;
        if($request->input('author')) {
            $author = User::find($request->input('author')) ?? null;
            if($author) $this->author = $author;
        }

        $this->activity = null;
        if($request->input('activity')) {
            $activity = Activity::find($request->input('activity')) ?? null;
            if($activity) $this->activity = $activity;
        }


    }

    private function query()
    {
        $sights = DB::table('sights as s')
            ->leftjoin('visits as v','v.sight_id','=','s.id')
            ->leftjoin('activities as a','a.id','=','v.act_id')
            ->leftjoin('users as u','u.id','=','a.user_id')
            ->leftjoin('districts as d','d.id','=','s.district_id')
            ->selectRaw('s.id,count(v.id) as count')
            ->groupBy('s.id');
            
        if(empty($this->activity)) {
            $sights = $sights->orderByRaw('count desc,s.name');
        } else {
            $sights = $sights->orderByRaw('v.id');
        }

            
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
        if(!empty($this->author)) {
            $sights = $sights->where('s.user_id',$this->author->id);
        }

        if(!empty($this->activity)) {
            $sights = $sights->where('a.id',$this->activity->id);
        }
        
        return $sights;
    }

    public function isNotEmpty()
    {
        return !$this->isEmpty();
    }
    public function isEmpty()
    {
        $result = $this->query()->limit(1)->first();
        return empty($result);
    }

    public function index()
    {
        $sights = $this->query();

        if(empty($this->limit)) {
            $sights = $sights->paginate(24)->appends($this->request->query());
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

    public function title(bool $links=true) : string
    {
        if(!empty($this->user)) {
            return 'Список пам\'яток, якi вiдвiда'
                .$this->user->gender('в','ла').' '
                .(($links) ? $this->user->link : $this->user->fullname);
        }

        if(!empty($this->author)) {
            return 'Список пам\'яток, якi дода'
                .$this->author->gender('в','ла').' '
                .(($links) ? $this->author->link : $this->author->fullname);
        }

        if(!empty($this->activity)) {
            return 'Вiдвiданi пам\'ятки';
        }

        return 'Список пам\'яток';
    }

    public function filters($arr=[])
    {
        $result = parent::filters($arr);

        if (!empty($this->category)) $result['category'] = $this->category->id;
        if (!empty($this->subcategory)) $result['subcategory'] = $this->subcategory->id;
        if (!empty($this->author)) $result['author'] = $this->author->id;
        if (!empty($this->activity)) $result['activity'] = $this->activity->id;

        return $result;
    }
}
