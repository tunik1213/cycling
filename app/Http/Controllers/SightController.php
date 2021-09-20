<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sight;
use App\Models\District;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;

class SightController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show','index','getImage']);
        $this->middleware('moderator')->only(['destroy','edit','update']);
    }

    public function import(Request $request, string $loc) {

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
        Sight::import_google_maps($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sights = Sight::orderBy('name')->paginate(10);
        return view('sights.index', compact('sights'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sights.create',['districts'=>District::orderBy('name')->get()]);
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
            'lng' => 'required'
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

        Sight::create([
            'district_id' => $request->district_id,
            'name' => $request->name,
            'image'=> $image,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'approx_location' => $approx,
            'description' => $request->description,
            'user_id' => Auth::user()->id
        ]);

        return redirect()->route('sights.index')->with('success','Пам\'ятка успiшно створена.');
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
        return view('sights.edit',['sight'=>Sight::find($id),'districts'=>District::orderBy('name')->get()]);
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
            'lng' => 'required'
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
        $sight->description = $request->description;
        $sight->district_id = $request->district_id;
        $sight->save();

        return redirect()->route('sights.index')->with('success','Пам\'ятку успiшно змiнено');
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
        $img = Sight::find($id)->image;

        header("Content-Type: image/jpg");
        header("Content-Length: " . strlen($img));

        echo($img);
    }
}
