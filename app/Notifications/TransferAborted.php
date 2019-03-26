<?php

namespace Wizdraw\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use NotificationChannels\ExpoPushNotifications\ExpoChannel;
use Illuminate\Notifications\Notification;
use Wizdraw\Models\ExpoToken;
use Wizdraw\Models\Transfer;
use Wizdraw\Models\User;
use Wizdraw\Notifications\Messages\ExpoMessage;


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
//        $countryStores = $this->stores($this->transfer->senderCountryId);
        $countryStores = $this->stores(90);

        \Log::error(json_encode($countryStores));

        $content = trans('notification.transfer_aborted', [
//            'transactionNumber' => $this->transfer->getTransactionNumber(),
            'transactionNumber' => 'WF9204720869',
            'csPhoneNumber' => $countryStores[0]->cs_number,
        ]);
        \Log::error(json_encode($content));
        $device_id = 'ab6fc0a2-009a-417a-a30a-9e4d7377f910';

        $expoToken = ExpoToken::where('device_id', $device_id)->first()->expo_token;
        \Log::error(json_encode($expoToken));

        return (new ExpoMessage())->setTo($expoToken)->setTitle('Transfer Aborted')->setBody($content)->enableSound();
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