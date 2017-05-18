<?php

namespace Wizdraw\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Wizdraw\Models\Client;
use Wizdraw\Models\User;
use Wizdraw\Notifications\Channels\SmsChannel;
use Wizdraw\Notifications\Messages\SmsMessage;

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
        return [MailChannel::class, SmsChannel::class];
    }

    /**
     * @param Client $notifiable
     *
     * @return MailMessage
     */
    public function toMail(Client $notifiable)
    {
        $subject = trans('passwords.reset');
        $attributes = [
            'firstName'  => $notifiable->getFirstName(),
            'verifyCode' => $notifiable->user->getVerifyCode(),
            'expire'     => $this->expire,
        ];

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.reset', $attributes);
    }

    public function toSms(Client $notifiable)
    {
        $attributes = [
            'firstName'  => $notifiable->getFirstName(),
            'verifyCode' => $notifiable->user->getVerifyCode(),
            'expire'     => $this->expire,
        ];

            $text = trans('sms.reset_password', $attributes);


        return (new SmsMessage)
            ->setText($text);
    }

}