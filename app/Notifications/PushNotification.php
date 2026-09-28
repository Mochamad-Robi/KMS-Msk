<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class PushNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $title;
    protected $body;
    protected $link;

    public function __construct(string $title, string $body, ?string $link = null)
    {
        $this->title = $title;
        $this->body  = $body;
        $this->link  = $link ?? '/';
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title($this->title)
            ->body($this->body)
            ->icon('/assets/logo.png')
            ->badge('/assets/logo.png')
            ->data(['url' => $this->link])
            ->options(['TTL' => 86400]);
    }
}