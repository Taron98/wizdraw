<?php

namespace Wizdraw\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Wizdraw\Models\Client;
use Wizdraw\Notifications\Channels\SmsChannel;
use Wizdraw\Notifications\Messages\SmsMessage;

/**
 * Class ClientVerify
 * @package Wizdraw\Notifications
 */
class ClientVerify extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var  bool */
    protected $isFirstTime;

    /** @var  int */
    protected $expire;

    /**
     * ClientVerify constructor.
     *
     * @param bool $isFirstTime
     */
    public function __construct($isFirstTime = false)
    {
        $this->isFirstTime = $isFirstTime;
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
     * @return SmsMessage
     */
    public function toSms(Client $notifiable)
    {
        $attributes = [
            'verifyCode' => $notifiable->user->getVerifyCode(),
            'expire'     => $this->expire,
        ];

        if ($this->isFirstTime) {
            $text = trans('sms.verification_first_time', $attributes);
        } else {
            $text = trans('sms.verification', $attributes);
        }

        return (new SmsMessage)
            ->setText($text);
    }

    /**
     * @param Client $notifiable
     *
     * @return MailMessage
     */
    public function toMail(Client $notifiable)
    {
        $subject = trans('mail.title_client_verify');
        $attributes = [
            'firstName'  => $notifiable->getFirstName(),
            'verifyCode' => $notifiable->user->getVerifyCode(),
            'expire'     => $this->expire,
        ];

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.verification', $attributes);
    }

}