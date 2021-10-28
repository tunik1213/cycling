<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sight;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function profile(?int $id=null)
    {
        $user = ($id==null) ? Auth::user() : User::find($id);
        return view('user.profile',['user'=>$user]);

    }

    public function sightsVisited(int $id)
    {
        $user = User::find($id);
        $sights = DB::table('sights as s')
            ->join('visits as v','v.sight_id','=','s.id')
            ->join('activities as a','a.id','=','v.act_id')
            ->where('a.user_id',$id)
            ->selectRaw('s.id,count(v.id) as count')
            ->groupBy('s.id')
            ->having('count','>',0)
            ->orderByRaw('count desc,s.name')
            ->paginate(12);

        $collection = $sights->getCollection()->all();
        foreach($collection as &$entry) {
            $s = Sight::find($entry->id);
            $s->count = $entry->count;
            $entry = $s;
        }
        $sights->setCollection(collect($collection));
        
        return view('sights.list',['sights'=>$sights,'user'=>$user]);
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

    public function top(Request $request)
    {
        $users = User::topTravelersAll();
        return view('user.index',[
            'users'=>$users,
            'list_title'=>'Топ мандрiвникiв'
        ]);
    }

}
