<?php

namespace App\Http\Controllers;

use App\Models\SightSubCategory;
use Illuminate\Http\Request;
use App\Models\SightSubCategory as Subcat;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subcats = Subcat::orderBy('name')->paginate(50);
    
        return view('subcats.index',['subcats'=>$subcats]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('subcats.create');
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
            'category_id' => 'required',
        ]);
    
        Subcat::create($request->all());
     
        return redirect()->route('subcategories.index')
                        ->with('success','Пiдкатегорiю успiшно додано.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SightSubCategory  $sightSubCategory
     * @return \Illuminate\Http\Response
     */
    public function show(Subcat $sightSubCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SightSubCategory  $sightSubCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        return view('subcats.edit',['subcat'=>Subcat::find($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SightSubCategory  $sightSubCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
        ]);
        $sightSubCategory = Subcat::find($id);

        $sightSubCategory->update($request->all());
    
        return redirect()->route('subcategories.index')
                        ->with('success','Пiдкатегорiю успiшно змiнено.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SightSubCategory  $sightSubCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {$sightSubCategory = Subcat::find($id);
        $sightSubCategory->delete();
    
        return redirect()->route('subcategories.index')
                        ->with('success','Пiдкатегорiю успiшно видалено');
    }
}
