<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SightCategory as Category;
use App\Models\SightSubCategory as SubCategory;
use App\Models\SightList;

class CategoryController extends Controller
{
    public function show(Request $request, int $id)
    {
        $cat = Category::find($id);
        if ($cat == null) abort(404);

        $sights = new SightList($request);
        $sights->category = $cat;

        return view('sight_categories.show',[
            'category' => $cat,
            'sightList'=> $sights
        ]);
    }
    public function exportSubCategories(Request $request)
    {
        $result = SubCategory::where('category',$request->input('id'))
            ->select(['id','name'])
            ->orderBy('name')
            ->get();
        return response()->json($result);
    }
}
