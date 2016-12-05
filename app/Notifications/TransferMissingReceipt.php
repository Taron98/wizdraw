<?php

namespace Wizdraw\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Wizdraw\Models\Transfer;
use Wizdraw\Models\User;
use Wizdraw\Notifications\Channels\PushwooshChannel;
use Wizdraw\Notifications\Messages\PushwooshMessage;

/**
 * Class TransferMissingReceipt
 * @package Wizdraw\Notifications
 */
class TransferMissingReceipt extends Notification implements ShouldQueue
{
    use Queueable;

    const REMIND_EVERY_HOURS = 5;

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
        return [PushwooshChannel::class];
    }

    /**
     * @param $notifiable
     *
     * @return PushwooshMessage|null
     */
    public function toPushwoosh(User $notifiable)
    {
        $content = trans('notification.transfer_missing_receipt', [
            'transactionNumber' => $this->transfer->getTransactionNumber(),
            'receiverFirstName' => $this->transfer->receiverClient->getFirstName(),
        ]);

        // A receipt was added
        if (!is_null($this->transfer->receipt)) {
            return;
        }

        $this->addReminder($notifiable);

        return PushwooshMessage::create()
            ->setContent($content);
    }

    /**
     * @param User $notifiable
     */
    private function addReminder(User $notifiable)
    {
        // Add a reminder
        $fiveHoursForward = Carbon::now()->addHours(self::REMIND_EVERY_HOURS);
        $notifiable->notify(
            (new TransferMissingReceipt($this->transfer))
                ->delay($fiveHoursForward)
        );
    }

}