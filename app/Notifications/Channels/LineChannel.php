<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class LineChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toLine')) {
            return;
        }

        $message = $notification->toLine($notifiable);
        $lineId = $notifiable->routeNotificationFor('line', $notification);

        if (!$lineId) return;

        Http::withToken(env('LINE_BOT_CHANNEL_TOKEN'))
            ->post('https://api.line.me/v2/bot/message/push', [
                'to' => $lineId,
                'messages' => [['type' => 'text', 'text' => $message]]
            ]);
    }
}
