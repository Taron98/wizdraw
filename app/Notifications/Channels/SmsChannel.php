<?php

namespace Wizdraw\Notifications\Channels;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;

/**
 * Class SmsChannel
 * @package Wizdraw\Notifications\Channels
 */
class SmsChannel
{

    const API_URL = 'https://%s/VSServices/SendSms.ashx?login=%s&pass=%s&from=%s&to=%s&text=%s';

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
     * @param string $text
     * @param string $to
     *
     * @return string
     */
    private function url(string $to, string $text): string
    {
        return sprintf(
            self::API_URL,
            config('services.wicsms.host'),
            config('services.wicsms.login'),
            config('services.wicsms.pass'),
            config('services.wicsms.from'),
            $to,
            urlencode($text)
        );
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
        $to = $notifiable->routeNotificationFor('sms');
        $text = $notification->toSms($notifiable)->getText();

        if (!$to) {
            return;
        }

        // Send the sms using wic's private api
        $response = $this->http
            ->get($this->url($to, $text), ['verify' => false])
            ->getBody();

        $response = parse_xml($response);

        if (empty($response->sms_response_code) || (int)$response->sms_response_code !== 200) {
            \Log::error('Could not send sms to ' . phone($to) . ': ' . print_r($response, true));
        }
    }

}
