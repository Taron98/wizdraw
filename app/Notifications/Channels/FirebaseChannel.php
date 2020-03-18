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
        $request_body = $notification->toFirebasePush($notifiable)->toArray();

        $YOUR_API_KEY = env('FCM_LEGACY_KEY');
        $fields = json_encode($request_body);
        $request_headers = array(
                'Content-Type: application/json',
                'Authorization: key=' . $YOUR_API_KEY,
            );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::FIREBASE_NOTIFICATION_URL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);
        curl_close($ch);
        echo $response;

    }

}