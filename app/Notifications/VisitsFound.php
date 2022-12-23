<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VisitsFound extends Notification
{
    use Queueable;
    private int $sightsCount;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($count)
    {
        $this->sightsCount = $count;
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
            'count' => $this->sightsCount
        ];
    }

    public static function render($notification)
    {
        return view('notifications.new_visits',[
            'notification'=>$notification,
            'count' => $notification->data['count']
        ]);
    }

    
}
