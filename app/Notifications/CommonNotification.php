<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommonNotification extends Notification
{
    use Queueable;
    private string $text;
    private ?string $image;
    private string $class;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($text,$image=null,$class=null)
    {
        $this->text = $text;
        $this->image = $image;
        $this->class = $class;
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
            'text' => $this->text,
            'image' => $this->image,
            'class' => $this->class
        ];
    }

    public static function render($notification)
    {
        return view('notifications.common',[
            'notification'=>$notification,
            'text' => $notification->data['text'],
            'image' => $notification->data['image'] ?? asset('/images/attention.png'),
            'class' => $notification->data['class'] ?? ''
        ]);
    }
}
