<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Strava;
use App\Models\User;
use Intervention\Image\ImageManagerStatic as Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ImportActivities;

class AuthController extends Controller
{
    public function login(Request $request){
        return view('user.login');
    }

    public function Strava(Request $request) 
    {
        return Strava::authenticate($scope='read,profile:read_all,activity:read');
    }

    public function StravaCallBack(Request $request) 
    {
        $token = Strava::token($request->code);
        $user = $this->findOrCreateUser($token->athlete);

        $user->access_token = $token->access_token;
        $user->refresh_token = $token->refresh_token;
        $user->save();
        // try {
        //     $user->importActivities();
        // } catch (\Throwable $e) {
        //     report($e);
        // }
        ImportActivities::dispatchAfterResponse($user);

        Auth::login($user, $remember = env('APP_DEBUG'));

        $url = $request->session()->pull('redirect_uri', '/');
        return redirect($url);
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

    public function logout(request $request) 
    {

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
