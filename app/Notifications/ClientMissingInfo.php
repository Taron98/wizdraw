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

        if (!count($missing)) {
            return null;
        }

        $content = trans('notification.missing_multiple');
        if (count($missing) == 1) {
            $content = $missing[ 0 ];
        }

        $this->addReminder($notifiable);

        // Most of the time it means that the user registered after 9pm
        if ($this->delay->diffInMinutes(null, false) > 5) {
            return null;
        }

        return (new PushwooshMessage)
            ->setContent($content);
    }

    /**
     * @param Client $client
     *
     * @return array
     */
    public function checkMissing(Client $client): array
    {
        $types = [
            FileService::TYPE_ADDRESS  => trans("notification.missing_address"),
            FileService::TYPE_IDENTITY => trans("notification.missing_id"),
            FileService::TYPE_PROFILE  => trans("notification.missing_profile"),
        ];
        $missing = [];

        foreach ($types as $type) {
            if (!FileService::exists($type, $client->getId())) {
                $missing[] = $type;
            }
        }

        return $missing;
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