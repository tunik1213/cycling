<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\UserList;
use App\Models\SightList;
use App\Models\AreaList;

class AreaController extends Controller
{
    private $validation_rules = [
            'name' => 'required',
            'area_image' => 'required|max:1024',
        ];

    public function __construct()
    {
        $this->middleware('moderator')->except(['list', 'show', 'getImage']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $areas = Area::orderBy('name')->get();
        return view('areas.index', compact('areas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('areas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->validation_rules);

        $imagePath = $request->area_image->path();

        Area::create([
            'name' => $request->name,
            'image' => Image::make($imagePath)
                ->fit(200)
                ->encode('jpg', 75)
        ]);

        return redirect()->route('areas.index')->with('success', 'Область успiшно створена.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Area $area)
    {
        $topUsers = new UserList($request);
        $topUsers->limit = 4;
        $topUsers->area = $area;

        $topSights = new SightList($request);
        $topSights->limit = 4;
        $topSights->area = $area;

        return view('areas.show', [
            'area' => $area,
            'topUsers' => $topUsers,
            'topSights' => $topSights,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Area $area)
    {
        return view('areas.edit', compact('area'));
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
        $area = Area::find($id);
        $area->name = $request->name;
        if ($request->area_image) {
            $area->image = Image::make($request->area_image->path())
                ->fit(200)
                ->encode('png', 75);
        }
        $area->license = $request->license;
        $area->save();

        return redirect()->route('areas.index')->with('success', 'Область успiшно змiнено');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        Area::find($id)->delete();

        return redirect()->route('areas.index')
                      ->with('success', 'Область успiшно видалено');
    }

    public function getImage(int $id)
    {
        $a = Area::find($id);
        if($a == null) {
            return;
        }

        $img = $a->image;

        header("Content-Type: image/jpg");
        header("Content-Length: " . strlen($img));

        echo($img);
    }

    public function list(Request $request)
    {
        $list = new AreaList($request);

        return view('areas.list', [
            'areaList' => $list
        ]);
    }
}
