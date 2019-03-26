<?php
/**
 * Created by PhpStorm.
 * User: rubina.shakhkyan
 * Date: 11.03.2019
 * Time: 15:08
 */

namespace Wizdraw\Notifications\Messages;

class ExpoMessage
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
     * @return ExpoMessage
     */
    public function setTo(string $to)
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
     * @return ExpoMessage
     */
    public function setTitle(string $title): ExpoMessage
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
     * @return ExpoMessage
     */
    public function setBody(string $body): ExpoMessage
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Enable the message sound.
     *
     * @return $this
     */
    public function enableSound(): ExpoMessage
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