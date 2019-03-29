<?php

namespace Wizdraw\Notifications;

use App\Notifications\Channel\PushExpoChannel;
use App\Notifications\Messages\PushExpoMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Wizdraw\Models\ExpoToken;
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
        return [PushExpoChannel::class];
    }


    /**
     * @param $notifiable
     *
     * @return PushExpoMessage|null
     */
    public function toExpoPush($notifiable)
    {
        $countryStores = $this->stores($this->transfer->senderCountryId);

        $content = trans('notification.transfer_aborted', [
            'transactionNumber' => $this->transfer->getTransactionNumber(),
            'csPhoneNumber' => $countryStores[0]->cs_number,
        ]);
        $device_id = $this->transfer->client->user->device_id;

        $expoToken = ExpoToken::where('device_id', $device_id)->first()->expo_token;

        return (new PushExpoMessage())->setTo($expoToken)->setTitle('Transfer Aborted')->setBody($content)->enableSound();
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