<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\CrontabController;
use App\Http\Controllers\SightController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/strava_auth',[AuthController::class, 'strava'])->name('strava_login');
Route::get('/strava_auth_callback',[AuthController::class, 'StravaCallBack'])->name('strava_callback');


Route::get('/test',[TestController::class, 'index'])->middleware('moderator');
Route::get('/sight/import/{location}',[SightController::class, 'import'])->middleware('moderator');

Route::get('/crontab/checkInvites',[CrontabController::class,'checkInvites']);


Route::get('/home', [UserController::class, 'home'])->name('home');
Route::get('/user/getAvatarImage/{id?}', [UserController::class, 'getAvatarImage'])->name('userAvatar');


Route::resource('areas', AreaController::class);
Route::get('/areas/{id}/image', [AreaController::class, 'getImage'])->name('areas.image');

Route::resource('districts', DistrictController::class);
Route::get('/districts/{id}/image', [DistrictController::class, 'getImage'])->name('districts.image');

Route::get('/admin', [AdminController::class, 'index'])->name('admin');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
