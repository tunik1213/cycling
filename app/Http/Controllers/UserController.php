<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
}
