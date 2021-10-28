<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SightCategory as Category;
use App\Models\SightSubCategory as SubCategory;

class CategoryController extends Controller
{
    public function show(int $id)
    {
        $cat = Category::find($id);
        if ($cat == null) abort(404);

        return view('sight_categories.show',['category'=>$cat]);
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
