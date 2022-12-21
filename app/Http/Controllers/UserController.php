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
use App\Models\AuthorList;
use App\Models\SightList;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['getAvatarImage','list','authors']);
    }

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
        if(!$user) return redirect(route('login'));
        

        $topSightsVisited = new SightList($request);
        $topSightsVisited->user = $user;
        $topSightsVisited->limit = 4;

        $topSightsAuthor = new SightList($request);
        $topSightsAuthor->author = $user;
        $topSightsAuthor->limit = 4;

        return view('user.profile',[
            'user'=>$user,
            'topSightsVisited'=>$topSightsVisited,
            'topSightsAuthor'=>$topSightsAuthor,
            'stats' => $user->stats()
        ]);

    }

    public function index(Request $request)
    {
        $users = User::orderBy('created_at','desc')
            ->paginate(24);

        return view('user.adminIndex',['users'=>$users]);

    }

    public function list(Request $request)
    {
        $list = new UserList($request);

        return view('user.index',[
            'userList'=>$list
        ]);
    }

    public function authors(Request $request)
    {
        $list = new AuthorList($request);

        return view('user.index',[
            'userList'=>$list
        ]);
    }

    public function notifications(Request $request)
    {
        $user = Auth::user();
        $notifications = $user->notifications
            ->take(20);

        return view('notifications.list', ['notifications'=> $notifications]);
    }
}
