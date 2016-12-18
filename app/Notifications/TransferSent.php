<?php

namespace Wizdraw\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Wizdraw\Models\Client;
use Wizdraw\Models\Transfer;
use Wizdraw\Notifications\Channels\SmsChannel;
use Wizdraw\Notifications\Messages\SmsMessage;

/**
 * Class TransferSent
 * @package Wizdraw\Notifications
 */
class TransferSent extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var  Transfer */
    protected $transfer;

    /**
     * TransferMissingReceipt constructor.
     *
     * @param Transfer $transfer
     */
    public function __construct(Transfer $transfer)
    {
        $this->transfer = $transfer;
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
     * @param Client $notifiable
     *
     * @return SmsMessage
     */
    public function toSms(Client $notifiable)
    {
        $attributes = [
            'senderName'        => $this->transfer->client->getFullName(),
            'transactionNumber' => $this->transfer->getTransactionNumber(),
        ];

        $text = trans('sms.transfer_sender', $attributes);

        return (new SmsMessage)
            ->setText($text);
    }

}