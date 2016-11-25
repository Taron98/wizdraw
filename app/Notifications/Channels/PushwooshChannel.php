<?php

namespace Wizdraw\Notifications\Channels;

use Gomoob\Pushwoosh\Client\Pushwoosh;
use Gomoob\Pushwoosh\Model\Request\CreateMessageRequest;
use Illuminate\Notifications\Notification;

/**
 * Class PushwooshChannel
 * @package Wizdraw\Notifications\Channels
 */
class PushwooshChannel
{

    /** @var  Pushwoosh */
    private $pushwoosh;

    /**
     * PushwooshChannel constructor.
     *
     * @param Pushwoosh $pushwoosh
     */
    public function __construct(Pushwoosh $pushwoosh)
    {
        $this->pushwoosh = $pushwoosh;
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
        $pushwooshNotification = $notification
            ->toPushwoosh($notifiable)
            ->setDevices([ $notifiable->routeNotificationForPushwoosh() ])
            ->toNotification();
        $request = CreateMessageRequest::create()
            ->addNotification($pushwooshNotification);
        $response = $this->pushwoosh->createMessage($request);

        // todo: what todo?
//        if (!$response->isOk()) {
//
//        }
    }

}