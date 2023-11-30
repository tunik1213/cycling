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
use App\Models\Route;
use Carbon\Carbon;

class SightList extends ListModel
{
    use HasFactory;

    public ?SightCategory $category;
    public ?SightSubCategory $subcategory;
    public ?User $author;
    public ?Activity $activity;
    public ?string $search;
    public ?Route $routeAdd;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        if($request->input('user')) {
            $user = User::find($request->input('user')) ?? null;
            if($user) {
                $this->user = $user;
            }
        }

        $this->category = null;
        if($request->input('category')) {
            $category = Category::find($request->input('category')) ?? null;
            if($category) {
                $this->category = $category;
            }
        }

        $this->subcategory = null;
        if($request->input('subcategory')) {
            $subcategory = SubCategory::find($request->input('subcategory')) ?? null;
            if($subcategory) {
                $this->subcategory = $subcategory;
            }
        }

        $this->author = null;
        if($request->input('author')) {
            $author = User::find($request->input('author')) ?? null;
            if($author) {
                $this->author = $author;
            }
        }

        $this->activity = null;
        if($request->input('activity')) {
            $activity = Activity::find($request->input('activity')) ?? null;
            if($activity) {
                $this->activity = $activity;
            }
        }

        $this->search = $request->input('search') ?? null;

        $this->routeAdd = null;
        if($request->input('routeAdd')) {
            $route = Route::find($request->input('routeAdd')) ?? null;
            if($route) {
                $this->routeAdd = $route;
            }
        }

    }

    private function sights_query()
    {
        $sights = $this->query_all_filters();
        if(!empty($this->user) || !empty($this->author)) {
            $sights = $sights
             ->selectRaw('s.id,count(v.id) as count')
             ->groupBy('s.id');
        } else {
            $sights = $sights
            ->selectRaw('s.id,0 as count');
        }

        if(empty($this->activity)) {
            $sights = $sights->orderByRaw('count desc,isnull(s.classiness)*999');
        } else {
            $sights = $sights->orderByRaw('v.id');
        };

        return $sights;
    }

    public function locations()
    {
        $result = $this->query_user_filters()
            ->join('areas', 'areas.id', '=', 'd.area_id')
            ->select([
                'areas.id as area_id',
                'areas.name as area_name',
                'd.id as district_id',
                'd.name as district_name'
            ])
            ->orderBy('areas.name', 'asc')
            ->orderBy('d.name', 'asc')
            ->distinct()
            ->get();

        $areas = [];
        foreach ($result as $r) {
            $a = $areas[$r->area_id] ?? ['name' => $r->area_name,'districts' => []];
            $a['districts'][$r->district_id] = $r->district_name;
            $areas[$r->area_id] = $a;
        }

        return $areas;

    }

    public function categories()
    {
        $result = $this->query_user_filters()
            ->join('sight_categories as cats', 'cats.id', '=', 's.category_id')
            ->join('sight_sub_categories as subcats', 'subcats.id', '=', 's.sub_category_id')
            ->select([
                'cats.id as category_id',
                'cats.name as category_name',
                'subcats.id as subcategory_id',
                'subcats.name as subcategory_name'
            ])
            ->orderBy('cats.name', 'asc')
            ->orderBy('subcats.name', 'asc')
            ->distinct()
            ->get();

        $cats = [];
        foreach ($result as $r) {
            $cat = $cats[$r->category_id] ?? ['name' => $r->category_name,'subcats' => []];
            $cat['subcats'][$r->subcategory_id] = $r->subcategory_name;
            $cats[$r->category_id] = $cat;
        }

        return $cats;

    }

    public function isNotEmpty()
    {
        return !$this->isEmpty();
    }
    public function isEmpty()
    {
        $result = $this->query_all_filters()->limit(1)->first();
        return empty($result);
    }

    public function index()
    {
        $sights = $this->sights_query();

        if(empty($this->limit)) {
            $sights = $sights->paginate(24)->appends($this->request->query());
        } else {
            $sights = $sights->limit($this->limit)->get();
            $sights = new \Illuminate\Pagination\LengthAwarePaginator($sights, $this->limit, $this->limit);
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

    public function title(bool $links = true): string
    {
        if(!empty($this->routeAdd)) {
            return 'Додати локацію до веломаршруту '
                . $this->routeAdd->name;

        }

        if(!empty($this->user)) {
            return 'Локації, якi вiдвiда'
                .$this->user->gender('в', 'ла').' '
                .(($links) ? $this->user->link : $this->user->fullname);
        }

        if(!empty($this->author)) {
            return 'Локації, які дода'
                .$this->author->gender('в', 'ла').' '
                .(($links) ? $this->author->link : $this->author->fullname);
        }

        if(!empty($this->activity)) {
            return 'Вiдвiданi локації';
        }

        return 'Список локацій';
    }

    public function filters($add = [], $remove = [])
    {
        $result = parent::filters($add, $remove);

        if (!empty($this->author)) {
            $result['author'] = $this->author->id;
        }
        if (!empty($this->activity)) {
            $result['activity'] = $this->activity->id;
        }
        if (!empty($this->search) && !isset($remove['search'])) {
            $result['search'] = $this->search;
        }
        if (!empty($this->routeAdd) && !isset($remove['routeAdd'])) {
            $result['routeAdd'] = $this->routeAdd->id;
        }
        if (!empty($this->timestamp)) {
            $result['notification'] = $this->timestamp;
        }

        foreach($remove as $r) {
            if(isset($result[$r])) {
                unset($result[$r]);
            }
        }

        return $result;
    }

    public function geoJsonData()
    {
        $result = [
            'type' => 'FeatureCollection',
            'features' => []
        ];
        //if (empty($this->filters())) return $result;

        $data = $this
        ->query_all_filters()
        ->select([
            's.id',
            's.name',
            's.lat',
            's.lng'
        ])
        ->distinct()
        ->reorder()
        ->get();

        foreach($data as $d) {
            $feature = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [$d->lng,$d->lat]
                ],
                'properties' => [
                    'id' => $d->id
                ]
            ];
            array_push($result['features'], $feature);
        }

        return $result;
    }

    public function geoJsonUrl()
    {
        return route('sightsGeoJSON', $this->filters());
    }


    private function base_query()
    {
        $result = DB::table('sights as s')
            ->leftjoin('districts as d', 'd.id', '=', 's.district_id');
        if(!empty($this->user) || !empty($this->activity) || !empty($this->author)) {
            $result = $result
            ->leftjoin('visits as v', 'v.sight_id', '=', 's.id')
            ->leftjoin('activities as a', 'a.id', '=', 'v.act_id')
            ->leftjoin('users as u', 'u.id', '=', 'a.user_id');
        }
        return $result;
    }

    private function query_user_filters()
    {
        $sights = $this->base_query();

        if(!empty($this->user)) {
            $sights = $sights->where('a.user_id', $this->user->id);
        }

        if(!empty($this->author)) {
            $sights = $sights->where('s.user_id', $this->author->id);
        }

        if(!empty($this->activity)) {
            $sights = $sights->where('a.id', $this->activity->id);
        }

        if(!empty($this->timestamp)) {
            $from = Carbon::createFromTimestamp($this->timestamp)->subHours(5);
            $to = Carbon::createFromTimestamp($this->timestamp)->addHours(5);
            $sights = $sights->whereBetween('v.created_at', [$from,$to]);
        }

        return $sights;
    }

    private function query_all_filters()
    {
        $sights = $this->query_user_filters();

        if(!empty($this->district)) {
            $sights = $sights->where('d.id', $this->district->id);
        }
        if(!empty($this->area)) {
            $sights = $sights->where('d.area_id', $this->area->id);
        }

        if(!empty($this->category)) {
            $sights = $sights->where('s.category_id', $this->category->id);
        }
        if(!empty($this->subcategory)) {
            $sights = $sights->where('s.sub_category_id', $this->subcategory->id);
        }

        if(!empty($this->search)) {
            $search_array = explode(' ', $this->search);
            foreach ($search_array as &$q) {
                $q = '+'.trim($q).'*';
            }
            $search_query = implode(' ', $search_array);
            $sights = $sights->whereRaw(
                "MATCH(s.name) AGAINST('$search_query' IN BOOLEAN MODE)"
            );
        }

        //dd($sights->toSql());

        return $sights;
    }

}
