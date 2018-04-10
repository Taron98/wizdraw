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
        return [PushwooshChannel::class];
    }

    /**
     * @param $notifiable
     *
     * @return PushwooshMessage|null
     */
    public function toPushwoosh(User $notifiable)
    {
        $countryStores = $this->stores($this->transfer->senderCountryId);

        $content = trans('notification.transfer_aborted', [
            'transactionNumber' => $this->transfer->getTransactionNumber(),
            'csPhoneNumber' => $countryStores[0]->cs_number,
        ]);

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
     * @param int $countryId
     *
     * @return array
     */
    public function stores(int $countryId)
    {
        return get_country_stores($countryId);
    }

}