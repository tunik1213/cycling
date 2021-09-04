<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Strava;
use App\Models\User;
use Intervention\Image\ImageManagerStatic as Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function Strava(request $request) {
        return Strava::authenticate($scope='read,profile:read_all,activity:read');
    }

    public function StravaCallBack(request $request) {
        $token = Strava::token($request->code);
        $user = $this->findOrCreateUser($token->athlete);

        $user->access_token = $token->access_token;
        $user->refresh_token = $token->refresh_token;
        $user->save();

        Auth::login($user, $remember = true);

        return redirect('/');
    }

    private function findOrCreateUser($athlete) : User 
    {
        $existingUser = User::where('athlete_id',$athlete->id)->first();
        if ($existingUser != null) return $existingUser;

        $newUser = User::create([
            'athlete_id' => $athlete->id,
            'firstname' => $athlete->firstname,
            'lastname' => $athlete->lastname,
            'sex' => $athlete->sex,
            'premium' => $athlete->premium,
            'strava_created_at' => Carbon::parse($athlete->created_at),
             'avatar' => Image::make($athlete->profile)
                 ->encode('jpg', 75)
        ]);
        return $newUser;
    }
}
