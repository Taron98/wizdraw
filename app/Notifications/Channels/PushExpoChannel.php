<?php

namespace Wizdraw\Notifications\Channel;


use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;

class PushExpoChannel
{

    const EXPO_NOTIFICATION_URL = 'https://exp.host/--/api/v2/push/send';

    /** @var Client */
    private $http;

    /**
     * SmsChannel constructor.
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
        $params = $notification->toExpoPush($notifiable)->toArray();

        $headers = [
            'Content-type' => 'application/json; charset=utf-8',
            'Accept' => 'application/json',
        ];

        $this->http->post(self::EXPO_NOTIFICATION_URL, ['headers' => $headers, 'json' => $params]);
    }
}