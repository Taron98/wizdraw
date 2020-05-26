<?php

namespace Wizdraw\Notifications;

use Wizdraw\Notifications\Channels\PushExpoChannel;
use Wizdraw\Notifications\Channels\FirebaseChannel;
use Wizdraw\Notifications\Messages\PushExpoMessage;
use Wizdraw\Notifications\Messages\PushFirebaseMessage;
use Illuminate\Notifications\Notification;
use Wizdraw\Models\ExpoToken;
use Wizdraw\Models\FirebaseToken;
use Wizdraw\Models\Transfer;


/**
 * Class TransferAborted
 * @package Wizdraw\Notifications
 */
class TransferAborted extends Notification
{

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
        return [FirebaseChannel::class];
    }


    /**
     * @param $notifiable
     *
     * @return PushExpoMessage|null
     */
    public function toFirebasePush($notifiable)
    {
        $countryStores = $this->stores($this->transfer->senderCountryId);

        $content = trans('notification.transfer_aborted', [
            'transactionNumber' => $this->transfer->getTransactionNumber(),
            'csPhoneNumber' => $countryStores[0]->cs_number,
        ]);
        $device_id = $this->transfer->client->user->device_id;
        $client_id =  $this->transfer->client->user->client_id;

        $fcmToken = FirebaseToken::where(['device_id'=> $device_id, 'client_id'=> $client_id])->first()->fcm_token;

        return (new PushFirebaseMessage())->setTo($fcmToken)->setTitle('Transfer Aborted')->setBody($content)->enableSound();
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