<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LineChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toLine')) {
            return;
        }

        $messagePayload = $notification->toLine($notifiable);
        $lineId = $notifiable->routeNotificationFor('line', $notification) ?? $notifiable->line_id;

        if (!$lineId) return;

        $formattedMessage = is_array($messagePayload) 
            ? $messagePayload 
            : ['type' => 'text', 'text' => $messagePayload];

        $response = Http::withToken(env('LINE_BOT_CHANNEL_TOKEN'))
            ->post('https://api.line.me/v2/bot/message/push', [
                'to' => $lineId,
                'messages' => [$formattedMessage]
            ]);

        if ($response->failed()) {
            if ($response->status() === 429) {
                Log::critical('LINE API Quota Exceeded! Messages are blocked until next month.', [
                    'user_id' => $notifiable->id ?? 'unknown',
                    'line_id' => $lineId
                ]);
            } else {
                Log::error('LINE API Error: Failed to send message.', [
                    'status' => $response->status(),
                    'error' => $response->json()
                ]);
            }
        }
    }
}