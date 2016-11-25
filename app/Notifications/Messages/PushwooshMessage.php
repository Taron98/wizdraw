<?php

namespace Wizdraw\Notifications\Messages;

use Gomoob\Pushwoosh\Model\Notification\Android;
use Gomoob\Pushwoosh\Model\Notification\Notification;

/**
 * Class PushwooshMessage
 * @package Wizdraw\Notifications\Messages
 */
class PushwooshMessage
{

    /** @var  string */
    protected $content;

    /** @var  string[] */
    protected $devices;

    /** @var  Android */
    protected $android;

    /**
     * PushwooshMessage constructor.
     *
     * @param string $content
     */
    public function __construct(string $content = '')
    {
        $this->content = $content;
        $this->android = new Android();
    }

    /**
     * @param string $content
     *
     * @return static
     */
    public static function create(string $content = '')
    {
        return new static($content);
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return PushwooshMessage
     */
    public function setContent(string $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getDevices(): array
    {
        return $this->devices;
    }

    /**
     * @param string[] $devices
     *
     * @return PushwooshMessage
     */
    public function setDevices(array $devices): PushwooshMessage
    {
        $this->devices = $devices;

        return $this;
    }

    /**
     * @return string
     */
    public function getAndroidHeader(): string
    {
        return $this->android->getHeader();
    }

    /**
     * @param string $header
     *
     * @return PushwooshMessage
     */
    public function setAndroidHeader(string $header): PushwooshMessage
    {
        $this->android->setHeader($header);

        return $this;
    }

    public function toNotification()
    {
        return Notification::create()
            ->setAndroid($this->android)
            ->setContent($this->content)
            ->setDevices($this->devices);
    }

}