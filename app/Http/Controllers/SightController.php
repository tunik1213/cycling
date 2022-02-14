<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Sight;
use App\Models\District;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use StepanDalecky\KmlParser\Parser;
use App\Models\SightList;
use App\Models\UserList;

class SightController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show','getImage','list', 'geoJSON']);
        $this->middleware('moderator')->only(['destroy','edit','update','index','massUpdate']);
    }

    public function import(Request $request, string $loc, ?int $district_id) {

        //var_dump($loc);return;

        //$loc = '48.46635129586719, 35.05081279557519';

        $result = \GoogleMaps::load('textsearch')
         ->setParam([
            //'query' =>'достопримечательность',
            'location'=>$loc,
            'type'=>'tourist_attraction',
            'radius'=>20000,
            'region'=>'ua',
            'language'=> 'uk',
        ])->get();

        $data =  json_decode($result);
        Sight::import_google_maps($data,$district_id);
    }

    public function importAll(Request $request)
    {
        $districts = District::has('sights','=',0)->get();
        foreach($districts as $d) {

            $result = \GoogleMaps::load('textsearch')
             ->setParam([
                'query' =>$d->name.' район',
                'type'=>'tourist_attraction',
                'radius'=>20000,
                'region'=>'ua',
                'language'=> 'uk',
            ])->get();

            $data =  json_decode($result);
            Sight::import_google_maps($data,$d->id);
        }
    }

    public function importKML()
    {
        // TODO area_id

        $parser = Parser::fromFile('/var/www/html/cycling/tmp/sights.kml');
        
        $kml = $parser->getKml();
        $document = $kml->getDocument();
        
        $folders = $document->getFolders();
        foreach($folders as $folder) {
            $sights = $folder->getPlacemarks();
            foreach($sights as $s) {

                try {

                $name = $s->getName();
                echo '<hr/><br/>importing '.$name.'<br/><br/>';
                try {
                    $descr = $s->getDescription();    
                } catch(\Throwable $e) {
                    $descr = '';
                }
                

                preg_match('/<img.*?>/im', $descr, $matches);
                $img=$matches[0] ?? '';
                $descr = str_replace($img, '', $descr);
                preg_match('/src="(.*?)"/im',$img,$matches);
                $img_path = $matches[1] ?? '';

                if (empty($img_path)) {
                    echo 'no image, skipping';
                    continue;
                } 

                
                $p = $s->getPoint();
                
                $raw_coord = $p->getCoordinates();

                preg_match('/(\d+\.\d+,\d+\.\d+)/im',$raw_coord,$matches);
                $coordinates = explode(',',$matches[0]);
                $lat = max($coordinates);
                $lng = min($coordinates);

                $approx = Sight::getApprox($lat,$lng);
                $found = Sight::where('approx_location',$approx)->first();
                if ($found != null) {
                    echo('found '.$found->id);
                    continue;
                }

$district_id = null;
$area_id = 42;
//$locality = 'Черкаси';
                $url = 'https://maps.google.com/maps/api/geocode/json?latlng='
                    .$lat.','.$lng
                    .'&sensor=false&language=uk&key='
                    .env('GOOGLE_MAPS_UNRESTRICTED_KEY');
                $google_data = json_decode(file_get_contents($url));

               //dd($google_data->results);


                $area='';$district='';$locality='';
                foreach($google_data->results as $r) {
                    foreach($r->address_components as $a) {
                        foreach($a->types as $t) {
                            if($t == 'locality') $locality = $a->short_name;
                            if($t == 'administrative_area_level_2') $district = $a->short_name;
                            if($t == 'administrative_area_level_1') $area = $a->short_name;
                        }
                    }
                }

                if(empty($area_id)) {
                    $district = trim(str_replace('район','',$district));
                    if (empty($district)) {
                        echo 'empty district<br/>';
                        continue;
                    }
                    $district_id = District::where('name',$district)->first()->id ?? null;
                    if($district_id==null) {
                        $area = trim(str_replace('область','',$area));
                        $area_id = Area::where('name',$area)->first()->id ?? null;
                        if($area_id == null) {
                            if($area=='Київська обл.' || $area == 'місто Київ') {
                                $area_id = 33;
                                $district_id = 126;
                            } else {
                                echo'area does not exist: '.$area.'<br />';
                                continue;
                            }
                        }

                        if (empty($district_id)) {
                            echo 'creating district: '.$district.'<br />';
                            $new_district = District::create([
                                'area_id'=>$area_id,
                                'name'=>$district
                            ]);
                            $district_id = $new_district->id;
                        }
                    }
                }

                $image = Image::make($img_path)
                    ->fit(300)
                    ->encode('jpg', 75);

                $s = Sight::create([
                            'area_id' => $area_id ?? District::find($district_id)->area_id,
                            'district_id' => $district_id,
                            'name' => $name,
                            'image'=> $image,
                            'lat' => $lat,
                            'lng' => $lng,
                            'approx_location' => $approx,
                            'description' => $descr,
                            'user_id' => 27,
                            'category_id' => 3,
                            'sub_category_id' => 0,
                            'locality' => $locality,
                            'moderator' => 1
                        ]);

                echo 'created '.$s->id;

                if(env('APP_DEBUG')) exit;
                
                } catch(\Throwable $e) {
                    echo 'error importing '.$name.'<br/>';
                    echo    $e->getMessage().'<br/>';
                    if(env('APP_DEBUG')) throw $e;
                    
                    continue;
                }

            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // TODO точки без района

        $area_id = $request->input('area') ?? null;
        $area = Area::find($area_id);

        $sights = Sight::join('districts','districts.id','=','sights.district_id')
            ->select(['sights.*'])
            ->when($area, function ($query, $area) {
                return $query->where('districts.area_id', $area->id);
            })
            ->paginate(100)
            ->appends(request()->query());

        return view('sights.index', ['sights'=>$sights, 'area'=>$area]);
    }

    public function massUpdate(Request $request) {
        if (!$request->input('sights')) return;

        foreach ($request->input('sights') as $s_id) {
            $s = Sight::find($s_id);
            if(!$s) continue;
            
            if ($request->input('category')) $s->category_id = $request->input('category');
            if ($request->input('subcategory')) $s->sub_category_id = $request->input('subcategory');
            $s->save();
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sights.create');
    }

    public function messages()
    {
        return [
            'name.required' => 'Уведiть назву пам\'ятки!',
            'area_id.required' => 'Вкажiть область!',
            'lng.required' => 'Не вказано довготу!',
            'lat.required' => 'Не вказано широту!',
            'category.*' => 'Виберiть категорiю!'
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'area_id' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'category' => 'required|integer|min:1'
        ], $this->messages());

        $found = $this->_find($request->lat,$request->lng);
        if($found != null) {
            $error_text = view('sights.exists',['sight'=>$found])->render();
            return redirect()
                ->route('sights.create')
                ->with('error',$error_text)
                ->withInput();
        }

        if ($request->sight_image) {
            $imagePath = $request->sight_image->path();
            $image = Image::make($imagePath)
                ->fit(500)
                ->encode('jpg', 75);
        } else {
            $image = null;
        }

        $descr = prepare_external_links($request->description);
        $approx = Sight::getApprox($request->lat,$request->lng);

        $s = Sight::create([
            'area_id' => (int)$request->area_id,
            'district_id' => (int)$request->district_id,
            'name' => $request->name,
            'image'=> $image,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'approx_location' => $approx,
            'description' => $descr,
            'user_id' => Auth::user()->id,
            'category_id' => (int)$request->category,
            'sub_category_id' => $request->subcategory ?? null,
            'radius' => $request->radius ?? 25,
            'locality' => $request->locality ?? null,
            'license' => $request->license ?? null
        ]);

        $success_message = 'Пам\'ятка успiшно створена! '
        .'<a href="'.route('sights.create').'"><i class="fas fa-plus"></i> Додати ще</a>';
        return redirect()->route('sights.show',['sight'=>$s])->with('success',$success_message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,Sight $sight)
    {
        $topUsers = new UserList($request);
        $topUsers->limit = 4;
        $topUsers->sight = $sight;

        return view('sights.show',[
            'sight'=>$sight,
            'topUsers'=>$topUsers
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,int $id)
    {
        $params = [
            'sight'=>Sight::find($id),
            'moderation_uri' => $request->input('moderation_uri')
        ];
        
        return view('sights.edit',$params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required',
            'area_id' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'category' => 'required|integer|min:1'
        ], $this->messages());

        $found = $this->_find($request->lat,$request->lng,$id);
        if($found != null) {
            $error_text = view('sights.exists',['sight'=>$found])->render();
            return redirect()
                ->route('sights.edit',$id)
                ->with('error',$error_text)
                ->withInput();
        }

        $sight = Sight::find($id);
        $sight->name = $request->name;
        if ($request->sight_image) {
            $sight->image = Image::make($request->sight_image->path())
                ->fit(500)
                ->encode('jpg', 75);
        }
        $sight->approx_location = Sight::getApprox($request->lat,$request->lng);
        $sight->lat = $request->lat;
        $sight->lng = $request->lng;
        $sight->description = prepare_external_links($request->description);
        $sight->area_id = $request->area_id;
        $sight->district_id = $request->district_id;
        $sight->category_id = $request->category;
        $sight->sub_category_id = $request->subcategory ?? null;
        $sight->radius = $request->radius;
        $sight->locality = $request->locality ?? null;
        $sight->license = $request->license ?? null;
        $sight->save();

        $url = $request->input('moderation_uri', route('sights.show',['sight'=>$sight]));

        return redirect($url)->with('success','Пам\'ятку успiшно змiнено');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        DB::beginTransaction();

        DB::statement('delete from visits where sight_id = ?',[$id]);
        Sight::find($id)->delete();
        
        DB::commit();

        return redirect()->route('sights.index')
           ->with('success','Пам\'ятку успiшно видалено');
    }

    public function getImage(int $id)
    {
        $img = Sight::find($id)->image ?? null;

        header("Content-Type: image/jpg");
        header("Content-Length: " . strlen($img));

        echo($img);
    }

    public function list(Request $request)
    {
        $list = new SightList($request);

        return view('sights.list',[
            'sightList'=>$list
        ]);
    }

    public function find(Request $request, $lat, $lng) 
    {
        $found = $this->_find($lat,$lng,$request->sight ?? null);
        if($found!=null) return view('sights.exists',['sight'=>$found]);
        return null;
    }

    private function _find($lat,$lng,$sight_id=null) : ?Sight
    {
        $approx = Sight::getApprox($lat,$lng);
        $found = Sight::where('approx_location',$approx);
        if(!empty($sight_id)) $found = $found->where('id','<>',$sight_id);
            
        return $found->first();
    }

    public function moderation(Request $request)
    {
        $area_id = $request->input('area') ?? null;
        $area = Area::find($area_id);

        $sights = Sight::leftJoin('districts','districts.id','=','sights.district_id')
            ->whereNull('sights.moderator')
            ->select(['sights.*'])
            ->when($area, function ($query, $area) {
                return $query->where('districts.area_id', $area->id);
            })
            ->paginate(20)
            ->appends(request()->query());

        return view('sights.index', [
            'sights'=>$sights, 
            'area'=>$area, 
            'moderation_uri'=>$request->getRequestUri()
        ]);

    }

    public function geoJSON(Request $request)
    {
        $list = new SightList($request);
        return response()->json($list->geoJsonData(), 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }
}
