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
    const APPLICATION_STATE = 'money-transfer.finish-transaction';

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
        if (!is_null($this->transfer->receipt) || $this->transfer->statusId!=3) {
            return;
        }

        $this->addReminder($notifiable);

        return (new PushwooshMessage)
            ->setContent($content)
            ->setData([
                'state' => self::APPLICATION_STATE,
                'data'  => [
                    'transferId' => $this->transfer->getId(),
                ],
            ]);
    }

    /**
     * @param User $notifiable
     */
    private function addReminder(User $notifiable)
    {
        $target = Carbon::now()->addHours(self::REMIND_EVERY_HOURS);
        $target5pm = $notifiable->client->getTargetTime(17);

        if ($target > $target5pm) {
            $target = $notifiable->client->getTargetTime(8)->addDay();
        }

        // Add a reminder
        $notifiable->notify(
            (new TransferMissingReceipt($this->transfer))
                ->delay($target)
                ->onConnection('redis')
        );
    }
}