<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Route;
use App\Models\Sight;

class RouteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show','getImage','list']);
    }

    public function edit(Request $request, ?int $id=null)
    {
        if(empty($id)) {
            $route = Route::current_editing();
        } else {
            $route = Route::find($id);
        }
        if(empty($route)) abort(404);

        return view('routes.edit',['route'=>$route]);
    }

    public function addSight(Request $request)
    {
        $sight = Sight::find($request->input('sight'));
        if(empty($sight)) abort(404);

        $route = Route::find($request->input('route')) ?? Route::current_editing();
        if(empty($route)) return abort(404);

        $success = true; $message='Пам\'ятку успiшно додано в маршрут';

        $found = $route->sights()->find($sight);
        if(!empty($found)){
            $success=false;
            $message='Дана пам\'ятка вже є у маршрутi';
        } else {
            $rowCount = $route->sights()->count();
            $route->sights()->attach($sight, ['row_number' => $rowCount+1]);
            $route->save();
        }

        return response()->json(['success'=>$success,'message'=>$message]);
    }
}
