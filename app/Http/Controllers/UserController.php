<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sight;
use App\Models\Top;
use App\Models\District;
use App\Models\Area;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\UserList;
use App\Models\SightList;

class UserController extends Controller
{
    public function home (Request $request)
    {
        return view('user.cabinet',['user'=>Auth::user()]);
    }

    public function getAvatarImage(int $userId = null)
    {
        $user = ($userId == null) ? auth()->user() : User::find($userId);
        if ($user==null) return;

        $avatar = $user->avatar;
        if ($avatar == null) {
            $avatar = file_get_contents(public_path().'/no_foto.jpeg');
        }

        header("Content-Type: image/jpg");
        header("Content-Length: " . strlen($avatar));

        echo($avatar);
        exit;
    }

    public function profile(Request $request, ?int $id=null)
    {
        $user = ($id==null) ? Auth::user() : User::find($id);

        $topSights = new SightList($request);
        $topSights->user = $user;
        $topSights->limit = 4;

        return view('user.profile',[
            'user'=>$user,
            'topSights'=>$topSights
        ]);

    }

    public function index(Request $request)
    {
        $users = User::orderBy('created_at')
            ->paginate(24);

        $collection = $users->getCollection();
        foreach($collection as &$u) {
            $u->dopInformation = $u->registeredAt;
        }
        $users->setCollection($collection);

        return view('user.index',['users'=>$users]);

    }

    public function list(Request $request)
    {
        $list = new UserList($request);

        return view('user.index',[
            'userList'=>$list
        ]);
    }

}
