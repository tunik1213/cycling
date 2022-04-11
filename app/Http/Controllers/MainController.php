<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserList;
use App\Models\SightList;
use App\Models\AuthorList;
use Illuminate\Support\Facades\Auth;
use App\Models\StaticPage;

class MainController extends Controller
{
    public function main(Request $request)
    {
        $topSights = new SightList($request);
        $topSights->user = Auth::user();
        $topSights->limit = 4;

        $topUsers = new UserList($request);
        $topUsers->limit = 4;

        $topAuthors = new AuthorList($request);
        $topAuthors->limit = 4;        

        return view('welcome',[
            'topUsers'=>$topUsers,
            'topSights'=>$topSights,
            'topAuthors'=>$topAuthors
        ]);
    }

    public function staticPage(string $page_name)
    {
        $p = StaticPage::where('name',$page_name)->first();
        if(empty($p)) abort(404);

        return view('static_page',['page'=>$p]);

    }
}
