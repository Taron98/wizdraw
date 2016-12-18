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
        // todo: revert the comment to enable pushwoosh
        /*$pushwooshNotification = $notification
            ->toPushwoosh($notifiable);

        if (is_null($pushwooshNotification)) {
            return;
        }

        $pushwooshNotification = $pushwooshNotification
            ->setDevices([$notifiable->routeNotificationForPushwoosh()])
            ->toNotification();

        $request = (new CreateMessageRequest)
            ->addNotification($pushwooshNotification);

        $response = $this->pushwoosh->createMessage($request);

        if (!$response->isOk()) {
            \Log::error('Could not creating a notification in Pushwoosh');
        }*/
    }

}