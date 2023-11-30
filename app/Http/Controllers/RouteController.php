<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Route;
use App\Models\Sight;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Auth;
use App\Models\UserList;
use App\Models\Activity;

class RouteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show','getImage','list']);
        $this->middleware('moderator')->only('new');
    }

    public function edit(Request $request, ?int $id = null)
    {
        if(empty($id)) {
            $route = Route::find_or_create();
        } else {
            $route = Route::find($id);
        }
        if(empty($route)) {
            abort(404);
        }

        return view('routes.edit', ['route' => $route]);
    }

    public function addSight(Request $request)
    {
        $sight = Sight::find($request->input('sight'));
        if(empty($sight)) {
            abort(404);
        }

        $route = Route::find($request->input('route')) ?? Route::find_or_create();
        if(empty($route)) {
            return abort(404);
        }

        $success = true;
        $message = 'Локацiю успiшно додано в маршрут';

        $found = $route->sights()->find($sight);
        if(!empty($found)) {
            $success = false;
            $message = 'Дана локацiя вже є у маршрутi';
        } else {
            $rowCount = $route->sights()->count();
            $route->sights()->attach($sight, ['row_number' => $rowCount + 1]);
            $route->save();
        }

        return response()->json(['success' => $success,'message' => $message]);
    }

    public function update(Request $request, int $id)
    {
        $route = Route::find($id);
        if(!$route->canEdit()) {
            return abort(403);
        }

        $route->name = $request->name;

        if ($request->logo_image) {
            $route->logo_image = Image::make($request->logo_image->path())
                ->fit(200)
                ->encode('jpg', 75);
        }
        if ($request->map_image) {
            $route->map_image = Image::make($request->map_image->path())
                ->encode('jpg', 75);
        }

        $route->description = $request->description;
        if(!$route->finished) {
            $route->finished = $request->finished ?? false;
        }

        $user = Auth::user();
        if($user->moderator) {
            if(empty($route->moderator)) {
                $route->moderator = $user->id;
            }
        }
        if(empty($route->user_id)) {
            $route->user_id = $user->id;
        }
        $route->license = $request->license;

        $sights = [];
        foreach(explode(',', $request->sights) as $index => $s_id) {
            if(empty($s_id)) {
                continue;
            }
            $sights[$s_id] = ['row_number' => ++$index];
        }

        $route->sights()->sync($sights);

        $route->distance = $request->distance;
        $route->grunt_percent = $request->grunt_percent;


        $route->save();


        if(empty($request->redirect)) {
            return redirect(route('routes.show', $id))->with('success', 'Змiни успiшно збережено');
        } else {
            return redirect($request->redirect);
        }

    }

    public function show(Request $request, int $id)
    {
        $route = Route::find($id);
        if(empty($route)) {
            abort(404);
        }

        $topUsers = new UserList($request);
        $topUsers->limit = 4;
        $topUsers->route = $route;

        return view('routes.show', [
            'route' => $route,
            'topUsers' => $topUsers
        ]);
    }

    public function getImage(int $id, string $type)
    {
        $img = Route::find($id)[$type.'_image'] ?? null;

        header("Content-Type: image/jpg");
        header("Content-Length: " . strlen($img));

        echo($img);
    }

    public function list(Request $request)
    {
        $result = Route::where('finished', 1)
            ->whereNotNull('moderator')
            ->paginate(100)
            ->appends(request()->query())
        ;

        return view('routes.list', ['routes' => $result]);
    }
    public function new(Request $request)
    {
        $result = Route::where('finished', 1)
            ->whereNull('moderator')
            ->paginate(100)
            ->appends(request()->query())
        ;

        return view('routes.list', ['routes' => $result]);
    }

    public function mergeActivity(int $activityId)
    {
        $act = Activity::find($activityId);
        if(empty($act)) {
            return abort(404);
        }

        $route = Route::find_or_create();
        if (empty($route->name)) {
            $route->name = $act->name;
        }
        $rowNumber = $route->sights()->count();
        foreach($act->visits as $v) {
            $sight = Sight::find($v->sight_id);
            $route->sights()->attach($sight, ['row_number' => ++$rowNumber]);
        }
        $route->save();

        return redirect(route('routes.edit', ['id' => $route->id]));
    }
}
