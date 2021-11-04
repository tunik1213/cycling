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
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MainController;

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

Route::get('/', [MainController::class, 'main'])->name('main');

Route::get('/login',function(){
    return view('user.login');
})->name('login');
Route::get('/strava_auth',[AuthController::class, 'strava'])->name('strava_login');
Route::get('/strava_auth_callback',[AuthController::class, 'StravaCallBack'])->name('strava_callback');

Route::middleware('moderator')->group(function () {
    Route::get('/test',[TestController::class, 'index']);
    Route::get('/sight/import/{location}/{district_id?}',[SightController::class, 'import']);
    Route::get('/sight/importAll',[SightController::class,'importAll']);
    Route::get('/user/index',[UserController::class,'index'])->name('users.index');
    Route::get('/sight/importKML',[SightController::class,'importKML']);
});

Route::get('/crontab/checkInvites',[CrontabController::class,'checkInvites'])->name('checkInvites')->middleware('localhost');
Route::get('/crontab/parseActivityNames',[CrontabController::class,'parseActivityNames'])->middleware('localhost');


Route::get('/home', [UserController::class, 'home'])->name('home');
Route::get('/user/getAvatarImage/{id?}', [UserController::class, 'getAvatarImage'])->name('userAvatar');
Route::get('/user/list', [UserController::class, 'list'])->name('users.list');
Route::get('/user/{id?}', [UserController::class, 'profile'])->name('userProfile');
Route::get('/user/{id}/sights', [UserController::class, 'sightsVisited'])->name('userSights');
Route::get('/sights/list', [SightController::class, 'list'])->name('sights.list');

Route::get('/activities', [ActivityController::class, 'list'])->name('activities');


Route::resource('areas', AreaController::class);
Route::get('/areas/{id}/image', [AreaController::class, 'getImage'])->name('areas.image');

Route::resource('districts', DistrictController::class);
Route::get('/districts/{id}/image', [DistrictController::class, 'getImage'])->name('districts.image');

Route::resource('sights', SightController::class);
Route::get('/sights/{id}/image', [SightController::class, 'getImage'])->name('sights.image');

Route::get('/category/{id}', [CategoryController::class, 'show'])->name('sightCategory');

Route::get('/admin', [AdminController::class, 'index'])->name('admin');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');




Route::get('/export/subcategories',[CategoryController::class, 'exportSubCategories']);
