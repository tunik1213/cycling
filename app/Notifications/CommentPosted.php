<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Comment;
use App\Models\User;

class CommentPosted extends Notification
{
    use Queueable;
    private Comment $comment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'author_id' => $this->comment->author_id,
            'commentable_id' => $this->comment->commentable_id,
            'commentable_type' => $this->comment->commentable_type,
            'parent_id' => $this->comment->parent_id,
            'comment_id' => $this->comment->id
        ];
    }

    public static function render($notification)
    {
        $author = User::find($notification->data['author_id']);
        $commented_object = $notification->data['commentable_type']::find($notification->data['commentable_id']);
        $comment = Comment::find($notification->data['comment_id']);
        $parent_comment = Comment::find($notification->data['parent_id']);

        return view('notifications.new_comment', [
            'notification' => $notification,
            'author' => $author,
            'commented_object' => $commented_object,
            'comment' => $comment,
            'parent_comment' => $parent_comment
        ]);
    }
}
