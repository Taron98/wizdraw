<?php

namespace Wizdraw\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Wizdraw\Models\User;
use Wizdraw\Notifications\Channels\PushwooshChannel;
use Wizdraw\Notifications\Messages\PushwooshMessage;

/**
 * Class ClientMissingPassport
 * @package Wizdraw\Notifications
 */
class ClientMissingPassport extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification channels.
     *
     * @param  mixed $notifiable
     *
     * @return array|string
     */
    public function via($notifiable)
    {
        return [PushwooshChannel::class];
    }

    /**
     * @param $notifiable
     *
     * @return PushwooshMessage
     */
    public function toPushwoosh(User $notifiable) : PushwooshMessage
    {
        $content = trans('notification.missing_passport');
        $header = trans('notification.title_missing_passport');

        return PushwooshMessage::create()
            ->setContent($content)
            ->setAndroidHeader($header);
    }

}