<?php

namespace App\Notifications\Messages;

class PushExpoMessage
{
    /**
     * The notification title
     *
     * @var string
     */
    private $to;

    /**
     * The notification title
     *
     * @var string
     */
    private $title;

    /**
     * The notification body
     *
     * @var string
     */
    private $body;

    /**
     * The notification sound for recipient
     *
     * @var string
     */
    private $sound = 'default';

    /**
     * Get token for the device
     *
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * Set token for the push notification
     *
     * @param string $to
     * @return PushExpoMessage
     */
    public function setTo(string $to): PushExpoMessage
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get title of the notification
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set title of the notification
     *
     * @param string $title
     * @return PushExpoMessage
     */
    public function setTitle(string $title): PushExpoMessage
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get body of the notification
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Set body of the notification
     *
     * @param string $body
     * @return PushExpoMessage
     */
    public function setBody(string $body): PushExpoMessage
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Enable the message sound.
     *
     * @return $this
     */
    public function enableSound(): PushExpoMessage
    {
        $this->sound = 'default';

        return $this;
    }

    /**
     * Disable the message sound.
     *
     * @return $this
     */
    public function disableSound()
    {
        $this->sound = null;

        return $this;
    }

    /**
     * Get an array representation of the message.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'to'    => $this->getTo(),
            'title' => $this->getTitle(),
            'body'  => $this->getBody(),
            'sound' => $this->sound,
        ];
    }
}