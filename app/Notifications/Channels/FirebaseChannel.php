<?php

namespace Wizdraw\Notifications\Channels;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;

/**
 * Class PushwooshChannel
 * @package Wizdraw\Notifications\Channels
 */
class FirebaseChannel
{
    const FIREBASE_NOTIFICATION_URL = 'https://fcm.googleapis.com/fcm/send';

    /** @var Client */
    private $http;

    /**
     * FirebaseChannel constructor.
     *
     * @param Client $http
     */
    public function __construct(Client $http)
    {
        $this->http = $http;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     *
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $params = $notification->toFirebasePush($notifiable)->toArray();

        $headers = [
            'Content-type' => 'application/json; charset=utf-8',
            'Authorization' => 'key=' . env('FCM_LEGACY_KEY'),
            'Accept' => 'application/json',
        ];

        $this->http->post(self::FIREBASE_NOTIFICATION_URL, ['headers' => $headers, 'json' => json_encode($params)]);
    }

}