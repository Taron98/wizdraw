<?php

namespace Wizdraw\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Wizdraw\Models\Client;


/**
 * Class ClientVerify
 * @package Wizdraw\Notifications
 */
class ClientWelcome extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * ClientVerify constructor.
     *
     *
     */
    public function __construct()
    {
    }

    /**
     * Get the notification channels.
     *
     * @param  mixed $notifiable
     *
     * @return array|string
     */
    public function via($notifiable)
    {
        return [MailChannel::class];
    }


    /**
     * @param Client $notifiable
     *
     * @return MailMessage
     */
    public function toMail(Client $notifiable)
    {
        $subject = trans('mail.title_client_greeting');
        $attributes = [
            'firstName'  => $notifiable->getFirstName(),
        ];

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.greeting', $attributes);
    }

}