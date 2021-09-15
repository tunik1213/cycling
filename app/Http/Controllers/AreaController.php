<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use Intervention\Image\ImageManagerStatic as Image;

class AreaController extends Controller
{
    private $validation_rules = [
            'name' => 'required',
            'area_image' => 'required|max:1024',
        ];
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
            'image'=>Image::make($imagePath)
                ->fit(200)
                ->encode('jpg', 75)
        ]);

        return redirect()->route('areas.index')->with('success','Область успiшно створена.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Area $area)
    {
        return view('areas.show',compact('area'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Area $area)
    {
        return view('areas.edit',compact('area'));
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
                ->encode('jpg', 75);
        }
        $area->save();

        return redirect()->route('areas.index')->with('success','Область успiшно змiнено');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Area $a)
    {
         $a->first()->delete();

         return redirect()->route('areas.index')
                       ->with('success','Область успiшно видалено');
    }

    public function getImage(int $id)
    {
        $img = Area::find($id)->image;

        header("Content-Type: image/jpg");
        header("Content-Length: " . strlen($img));

        echo($img);
    }
}