<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/8/2017
 * Time: 12:37
 */

namespace Wizdraw\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Wizdraw\Models\Client;
use Wizdraw\Notifications\Channels\SmsChannel;
use Wizdraw\Notifications\Messages\SmsMessage;

class UpdateApplication extends Notification implements ShouldQueue
{
    use Queueable;

    protected $client;

    /**
     * UpdateApplication constructor.
     *
     */

    public function __construct($client)
    {
        $this->client = $client;
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
            'senderName'        => $this->client->getFirstName(),
            ];

        $text = trans('sms.application_update', $attributes);

        return (new SmsMessage)
            ->setText($text);
    }

}