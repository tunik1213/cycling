<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ListModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\SightCategory;
use App\Models\SightSubCategory;
use App\Models\User;

class SightList extends ListModel
{
    use HasFactory;

    public ?SightCategory $category;
    public ?SightSubCategory $subcategory;
    public ?User $author;

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
        if(!empty($this->author)) {
            $sights = $sights->where('s.user_id',$this->author->id);
        }

        if(empty($this->limit)) {
            $sights = $sights->paginate(12)->appends($this->request->query());
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
            return 'Список пам\'яток, якi створи'
                .$this->author->gender('в','ла').' '
                .(($links) ? $this->author->link : $this->author->fullname);
        }

        return 'Список пам\'яток';
    }

    public function filters($arr=[])
    {
        $result = parent::filters($arr);

        if (!empty($this->category)) $result['category'] = $this->category->id;
        if (!empty($this->subcategory)) $result['subcategory'] = $this->subcategory->id;
        if (!empty($this->author)) $result['author'] = $this->author->id;

        return $result;
    }
}
