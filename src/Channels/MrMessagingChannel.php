<?php

namespace Illuminate\Notifications\Channels;

use Carbon\Carbon;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Messages\MrMessagingMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notification;

class MrMessagingChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param Notification $notification
     * @return array|null
     */
    public function send($notifiable, Notification $notification): ?array
    {
        if (!method_exists($notification, 'toMrMessaging')) {
            Log::error('Notification does not have a toMrMessaging method', ['notification' => $notification]);
            return null;
        }
        if (config('mrmessaging.delivery_enabled') != true) {
            return null;
        }

        // We will look for toMrmessaging method first, then fallback to toSms
        if (!$to = $notifiable->routeNotificationFor('mrmessaging', $notification)) {
            if (!$to = $notifiable->routeNotificationFor('sms', $notification)) {
                return null;
            }
        }

        // Remove any spaces or + from the number
        $to = str_replace(['+', ' '], '', $to);

        $message = $notification->toMrMessaging($notifiable);

        if (is_string($message)) {
            $message = resolve(MrMessagingMessage::class, ['message' => $message]);
        }


        $data = [
            'username' => config('mrmessaging.username'),
            'password' => config('mrmessaging.password'),
            'sender' => Str::random(8) . '|' . $notifiable->getKey(),
            'receiver' => $to,
            'message' => $message->getContent(),
        ];
        if (strlen($message->getContent()) > 160) {
            $data['type'] = 'longsms';
        }
        $response = Http::get(config('mrmessaging.host') . 'sendsms', $data);

        if (!$response->successful()) {
            Log::error('MrMessaging Sending SMS failed!', [
                'response_code' => $response->status(),
                'response_body' => $response->body(),
                'message' => $data['message'],
                'to' => $data['receiver'],
                'sender' => $data['sender']
            ]);
            // ToDo - Throw Exception?
            return null;
        }

        $mrMessageEventIds = explode(',', $response->body());


        // Store the eventId in the notification or cache

        if (config('mrmessaging.store_event_id.saved_notification.enabled')) {
            if ($notification->savedNotification && $notification->savedNotification instanceof DatabaseNotification) {
                $primaryEventId = $mrMessageEventIds[0];
                $notification->savedNotification->update([
                    'event_id' => $primaryEventId,
                    'sent_at' => Carbon::now(),
                ]);
            }
        }
        if (config('mrmessaging.store_event_id.cache.enabled')) {
            foreach ($mrMessageEventIds as $mrMessageEventId) {
                Cache::tags('mr_messaging')->put($mrMessageEventId, [
                    'notifiable_model' => get_class($notifiable),
                    'notifiable_model_key' => $notifiable->getKey(),
                    'notification_model' => get_class($notification),
                    'sent_at' => Carbon::now()
                ], config('mrmessaging.store_event_id.cache.ttl'));
            }
        }

        return $mrMessageEventIds;
    }
}
