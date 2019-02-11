<?php

namespace Wizdraw\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\ExpoPushNotifications\ExpoChannel;
use NotificationChannels\ExpoPushNotifications\ExpoMessage;
use Illuminate\Notifications\Notification;
use Wizdraw\Models\Transfer;
use Wizdraw\Models\User;


/**
 * Class TransferAborted
 * @package Wizdraw\Notifications
 */
class TransferAborted extends Notification implements ShouldQueue
{
    use Queueable;

    const APPLICATION_STATE = 'money-transfer.home.intro';

    /** @var  Transfer */
    protected $transfer;

    /**
     * TransferAborted constructor.
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
        return [ExpoChannel::class];
    }


    /**
     * @param $notifiable
     *
     * @return ExpoMessage|null
     */
    public function toExpoPush(User $notifiable)
    {
        $countryStores = $this->stores($this->transfer->senderCountryId);

        $content = trans('notification.transfer_aborted', [
            'transactionNumber' => $this->transfer->getTransactionNumber(),
            'csPhoneNumber' => $countryStores[0]->cs_number,
        ]);
        return ExpoMessage::create()
            ->badge(1)
            ->enableSound()
            ->body($content);
    }

    /**
     * @param int $countryId
     *
     * @return array
     */
    public function stores(int $countryId)
    {
        return get_country_stores($countryId);
    }

}