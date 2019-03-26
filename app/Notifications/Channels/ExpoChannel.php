<?php
/**
 * Created by PhpStorm.
 * User: rubina.shakhkyan
 * Date: 11.03.2019
 * Time: 15:00
 */

namespace Wizdraw\Notifications\Channels;


use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;

class ExpoChannel
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
        $params = $notification->toExpoPush()->toArray();

        $this->http->request('POST', self::EXPO_NOTIFICATION_URL, [
            $params
        ]);
    }

}