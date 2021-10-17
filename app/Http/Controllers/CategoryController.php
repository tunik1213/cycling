<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SightCategory as Category;

class CategoryController extends Controller
{
    public function show(int $id)
    {
        $cat = Category::find($id);
        if ($cat == null) abort(404);

        return view('sight_categories.show',['category'=>$cat]);
    }
}
