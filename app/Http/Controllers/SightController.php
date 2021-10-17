<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Sight;
use App\Models\District;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;
use App\Jobs\CheckInvites;
use Illuminate\Support\Facades\DB;

class SightController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show','index','getImage']);
        $this->middleware('moderator')->only(['destroy','edit','update']);
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $area_id = $request->input('area') ?? null;
        $area = Area::find($area_id);

        $sights = Sight::join('districts','districts.id','=','sights.district_id')
            ->select(['sights.*'])
            ->when($area, function ($query, $area) {
                return $query->where('districts.area_id', $area->id);
            })
            ->paginate(20)
            ->appends(request()->query());

        return view('sights.index', ['sights'=>$sights, 'area'=>$area]);
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
            'district_id' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'category' => 'required|integer|min:1'
        ]);

        $approx = Sight::getApprox($request->lat,$request->lng);
        $found = Sight::where('approx_location',$approx)->first();

        if ($found != null) {
            $error_text = 'Пам\'ятка з такими координамати iснує<br/>
            <a href="'.route('sights.show',$found->id).'">'.$found->name.'</a>';
            return redirect()->route('sights.create')->with('error',$error_text);
        }

        if ($request->sight_image) {
            $imagePath = $request->sight_image->path();
            $image = Image::make($imagePath)
                ->fit(300)
                ->encode('jpg', 75);
        } else {
            $image = null;
        }

        $descr = prepare_external_links($request->description);

        $s = Sight::create([
            'district_id' => (int)$request->district_id,
            'name' => $request->name,
            'image'=> $image,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'approx_location' => $approx,
            'description' => $descr,
            'user_id' => Auth::user()->id,
            'category_id' => $request->category
        ]);

        //CheckInvites::dispatchAfterResponse();

        return redirect()->route('sights.show',['sight'=>$s])->with('success','Пам\'ятка успiшно створена.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Sight $sight)
    {
        return view('sights.show',['sight'=>$sight]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        return view('sights.edit',['sight'=>Sight::find($id)]);
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
            'district_id' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'category' => 'required|integer|min:1'
        ]);

        $approx = Sight::getApprox($request->lat,$request->lng);
        $found = Sight::where('approx_location',$approx)
            ->where('id','<>',$id)
            ->first();

        if ($found != null) {
            $error_text = 'Пам\'ятка з такими координамати iснує<br/>
            <a href="'.route('sights.show',$found->id).'">'.$found->name.'</a>';
            return redirect()->route('sights.edit',$id)->with('error',$error_text);
        }

        $sight = Sight::find($id);
        $sight->name = $request->name;
        if ($request->sight_image) {
            $sight->image = Image::make($request->sight_image->path())
                ->fit(300)
                ->encode('jpg', 75);
        }
        $sight->lat = $request->lat;
        $sight->lng = $request->lng;
        $sight->description = prepare_external_links($request->description);
        $sight->district_id = $request->district_id;
        $sight->category_id = $request->category;
        $sight->save();

        return redirect()->route('sights.show',['sight'=>$sight])->with('success','Пам\'ятку успiшно змiнено');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
         Sight::find($id)->delete();

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
}
