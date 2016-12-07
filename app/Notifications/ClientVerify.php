<?php

namespace Wizdraw\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Wizdraw\Models\User;
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

    /**
     * ClientVerify constructor.
     *
     * @param bool $isFirstTime
     */
    public function __construct($isFirstTime = false)
    {
        $this->isFirstTime = $isFirstTime;
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
        return [SmsChannel::class];
    }

    /**
     * @param User $notifiable
     *
     * @return SmsMessage
     */
    public function toSms(User $notifiable)
    {
        $attributes = [
            'verifyCode' => $notifiable->getVerifyCode(),
            'expire'     => config('auth.verification.expire') / 60,
        ];

        if ($this->isFirstTime) {
            $text = trans('sms.verification_first_time', $attributes);
        } else {
            $text = trans('sms.verification', $attributes);
        }

        return SmsMessage::create()
            ->setText($text);
    }

}