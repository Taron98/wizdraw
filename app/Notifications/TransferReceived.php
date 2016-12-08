<?php

namespace Wizdraw\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Wizdraw\Models\Client;
use Wizdraw\Models\Transfer;
use Wizdraw\Models\TransferType;
use Wizdraw\Notifications\Channels\SmsChannel;
use Wizdraw\Notifications\Messages\SmsMessage;

/**
 * Class TransferReceived
 * @package Wizdraw\Notifications
 */
class TransferReceived extends Notification implements ShouldQueue
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

        if ($this->transfer->type->getType() === TransferType::TYPE_PICKUP_CASH) {
            $text = trans('sms.transfer_receiver_pick_up', $attributes);
        } else {
            $text = trans('sms.transfer_receiver_deposit', $attributes);
        }

        return SmsMessage::create()
            ->setText($text);
    }

}