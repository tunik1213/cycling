<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sight;
use App\Models\Comment;
use App\Models\Route;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CommentPosted;

class CommentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([]);
    }

    public function addComment()
    {
        $commentText = trim(htmlspecialchars($_POST['comment']));
        if (empty($commentText)) return;

        $objId = (int)$_POST['commentable_id'];
        if(empty($objId)) return;

        switch($_POST['commentable_type']) {
            case 'sight':
                $modelClass = Sight::class;
                break;
            case 'route':
                $modelClass = Route::class;
                break;
            default:
                return;
        }

        $obj = $modelClass::find($objId);
        if(empty($obj)) return;

        $parent_id = (int)$_POST['parent_id'];

        $comment = new Comment([
            'author_id'=>Auth::id(),
            'parent_id'=>$parent_id,
            'text'=>$commentText
        ]);
        
        $obj->comments()->save($comment);

        if(empty($parent_id)) {
            $userToNotify = $obj->user;
        } else {
            $parentComment = Comment::find($parent_id);
            $userToNotify = $parentComment->author;
        }

        if(!empty($userToNotify))
            $userToNotify->notify(new CommentPosted($comment));

        return view('comments.show',['comment' => $comment]);
    }
}
