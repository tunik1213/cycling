<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function form(Request $request)
    {
        return view('feedback.form');
    }

    public function create(Request $request)
    {
        $f = new Feedback([
            'author_id' => Auth::user()->id,
            'text' => $request->text,
            'contacts' => $request->contacts
        ]);
        $f->save();

        return view('message',['class'=>'success','text'=>'Спасибi за вiдгук!']);

    }

    public function new(Request $request)
    {
        $fs = Feedback::whereNull('moderator')->get();
        return view('feedback.index',['fs'=>$fs]);

    }

}
