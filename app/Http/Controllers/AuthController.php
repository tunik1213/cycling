<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Strava;
use App\Models\User;
use Intervention\Image\ImageManagerStatic as Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Jobs\CheckInvites;

class AuthController extends Controller
{
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
        try {
            $user->importActivities();
        } catch (\Throwable $e) {
            report($e);
        }

        Auth::login($user, $remember = env('APP_DEBUG'));

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

    public function logout(request $request) 
    {

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
