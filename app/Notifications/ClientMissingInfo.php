<?php

namespace Wizdraw\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Wizdraw\Models\Client;
use Wizdraw\Models\User;
use Wizdraw\Notifications\Channels\PushwooshChannel;
use Wizdraw\Notifications\Messages\PushwooshMessage;
use Wizdraw\Services\FileService;

/**
 * Class ClientMissingInfo
 * @package Wizdraw\Notifications
 */
class ClientMissingInfo extends Notification implements ShouldQueue
{
    use Queueable;

    const REMIND_EVERY_HOURS = 24;
    const REMIND_TIME = 21;
    const APPLICATION_STATE = 'setup.tutorial-setup';
    const FILE_TYPES = [
        FileService::TYPE_ADDRESS,
        FileService::TYPE_IDENTITY,
        FileService::TYPE_PROFILE,
    ];

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
     * @return PushwooshMessage
     */
    public function toPushwoosh(User $notifiable)
    {
        $missing = $this->checkMissing($notifiable->client);

        $content = trans('notification.missing_multiple');
        if (count($missing[ 0 ]) === 1) {
            $content = trans('notification.missing_' . $missing[ 0 ][ 0 ]);
        }

        $this->addReminder($notifiable);

        // Most of the time it means that the user registered after 9pm
        if (is_null($this->delay) || $this->delay->diffInMinutes(null, false) > 5) {
            return null;
        }

        return (new PushwooshMessage)
            ->setContent($content)
            ->setData([
                'state' => self::APPLICATION_STATE,
                'data'  => $missing[ 1 ],
            ]);
    }

    /**
     * @param Client $client
     *
     * @return array
     */
    private function checkMissing(Client $client): array
    {
        $missing = $data = [];

        foreach (self::FILE_TYPES as $type) {
            if (!FileService::exists($type, $client->getId())) {
                $missing[] = $type;
                $data[] = "{$type}Image";
            }
        }

        return [$missing, $data];
    }

    /**
     * @param User $notifiable
     */
    private function addReminder(User $notifiable)
    {
        $twentyFourHoursForward = $notifiable
            ->client
            ->getTargetTime(self::REMIND_TIME)
            ->addHours(self::REMIND_EVERY_HOURS);

        $notifiable->notify(
            (new ClientMissingInfo())
                ->delay($twentyFourHoursForward)
        );
    }

}