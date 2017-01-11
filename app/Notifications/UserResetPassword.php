<?php

namespace Wizdraw\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Wizdraw\Models\Client;
use Wizdraw\Models\User;

/**
 * Class UserResetPassword
 * @package Wizdraw\Notifications
 */
class UserResetPassword extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var  bool */
    protected $email;

    /** @var  int */
    protected $expire;

    /**
     * UserResetPassword constructor.
     *
     * @param $email
     */
    public function __construct($email)
    {
        $this->email = $email;
        $this->expire = config('auth.verification.expire') / 60;
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
     * @param User $notifiable
     *
     * @return MailMessage
     */
    public function toMail(User $notifiable)
    {
        $subject = trans('passwords.reset');
        $attributes = [
            'firstName'  => $notifiable->client->getFirstName(),
            'verifyCode' => $notifiable->getVerifyCode(),
            'expire'     => $this->expire,
        ];

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.reset', $attributes);
    }

}