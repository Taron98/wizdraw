<?php

namespace Wizdraw\Notifications;

use Wizdraw\Notifications\Channels\PushExpoChannel;
use Wizdraw\Notifications\Channels\FirebaseChannel;
use Wizdraw\Notifications\Messages\PushExpoMessage;
use Wizdraw\Notifications\Messages\PushFirebaseMessage;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Wizdraw\Models\FirebaseToken;
use Wizdraw\Models\ExpoToken;
use Wizdraw\Models\Transfer;
use Wizdraw\Models\User;



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
        return [FirebaseChannel::class];
    }

    /**
     * @param $notifiable
     *
     * @return PushExpoMessage|null
     */
    public function toFirebasePush(User $notifiable)
    {
        $content = trans('notification.transfer_missing_receipt', [
            'transactionNumber' => $this->transfer->getTransactionNumber(),
            'receiverFirstName' => $this->transfer->receiverClient->getFirstName(),
        ]);

        if (!is_null($this->transfer->receipt) || $this->transfer->statusId != 3) {
           return null;
        }

        $this->addReminder($notifiable);
        $device_id = $this->transfer->client->user->device_id;
        $client_id = $notifiable->client->user->client_id;

        $fcmToken = FirebaseToken::where(['device_id'=> $device_id, 'client_id'=> $client_id])->first()->fcm_token;

        return (new PushFirebaseMessage())->setTo($fcmToken)->setTitle('Transfer Missing Receipt')->setBody($content)->enableSound();
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