<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\District;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\UserList;
use App\Models\SightList;

class DistrictController extends Controller
{
    public function __construct()
    {
        $this->middleware('moderator')->except(['index', 'show', 'getImage', 'export']);
    }

    private function areas() {
        return Area::orderBy('name')->get();
    }

    public function index()
    {
        $districts = District::orderBy('name')->get();
        return view('districts.index', compact('districts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('districts.create',['areas'=>$this->areas()]);
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
            'district_image' => 'required|max:1024',
            'area_id' => 'required'
        ]);

        $imagePath = $request->district_image->path();

        District::create([
            'name' => $request->name,
            'image'=>Image::make($imagePath)
                ->fit(200)
                ->encode('jpg', 75),
            'area_id'=> (int)$request->area_id
        ]);

        return redirect()->route('districts.index')->with('success','Район успiшно створений.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, District $district)
    {
        $topUsers = new UserList($request);
        $topUsers->limit = 4;
        $topUsers->district = $district;

        $topSights = new SightList($request);
        $topSights->limit = 4;
        $topSights->district = $district;

        return view('districts.show',[
            'district'=>$district,
            'topUsers'=>$topUsers,
            'topSights'=>$topSights,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(District $district)
    {
        return view('districts.edit',['district'=>$district,'areas'=>$this->areas()]);
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
        $district = District::find($id);
        $district->name = $request->name;
        if ($request->district_image) {
            $district->image = Image::make($request->district_image->path())
                ->fit(200)
                ->encode('png', 75);
        }
        $district->area_id = $request->area_id;
        $district->save();

        return redirect()->route('districts.index')->with('success','Район успiшно змiнено');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
         District::find($id)->delete();

         return redirect()->route('districts.index')
                       ->with('success','Район успiшно видалено');
    }

    public function getImage(int $id)
    {
        $d=District::find($id);
        if($d==null) return;

        $img = $d->image;

        header("Content-Type: image/jpg");
        header("Content-Length: " . strlen($img));

        echo($img);
    }

    public function export(int $area_id)
    {
        $result = District::where('area_id',$area_id)
            ->select(['id','name as label'])
            ->orderBy('name')
            ->get();

        return response()->json($result);

    }
}
