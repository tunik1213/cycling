<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Route;
use App\Models\Sight;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Auth;

class RouteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show','getImage','list']);
    }

    public function edit(Request $request, ?int $id=null)
    {
        if(empty($id)) {
            $route = Route::find_or_create();
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

        $route = Route::find($request->input('route')) ?? Route::find_or_create();
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

    public function update(Request $request, int $id) {
        $route = Route::find($id);
        if(!$route->canEdit()) return abort(403);

        $route->name = $request->name;
        if ($request->image) {
            $route->image = Image::make($request->image->path())
                ->encode('jpg', 75);
        }
        $route->description = $request->description;
        $route->finished = $request->finished ?? false;

        $user= Auth::user();
        if($user->moderator) {
            if(empty($route->moderator)) $route->moderator = $user->id;
        } 
        if(empty($route->user_id)) $route->user_id = $user->id;
        $route->license = $request->license;

        if(!empty($request->sights)) {
            foreach(explode(',',$request->sights) as $index=>$s_id) {
                if(empty($s_id)) continue;
                $sights[$s_id] = ['row_number' => ++$index];
            }

            $route->sights()->sync($sights);
        }


        $route->save();


        if(empty($request->redirect)) {
            return redirect(route('routes.show',$id))->with('success','Змiни успiшно збережено');
        } else {
            return redirect($request->redirect);
        }
        
    }

    public function show(Request $request, int $id)
    {
        $route = Route::find($id);
        if(empty($route)) abort(404);

        return view('routes.show',['route'=>$route]);

    }

    public function getImage(int $id)
    {
        $img = Route::find($id)->image ?? null;

        header("Content-Type: image/jpg");
        header("Content-Length: " . strlen($img));

        echo($img);
    }
}
