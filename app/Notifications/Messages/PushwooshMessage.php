<?php

namespace Wizdraw\Notifications\Messages;

use Carbon\Carbon;
use Gomoob\Pushwoosh\Model\Notification\Android;
use Gomoob\Pushwoosh\Model\Notification\Notification as PushwooshNotification;

/**
 * Class PushwooshMessage
 * @package Wizdraw\Notifications\Messages
 */
class PushwooshMessage
{

    /** @var  string */
    protected $content;

    /** @var  array */
    protected $data;

    /** @var  Carbon */
    protected $sendDate;

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
    public function setContent(string $content): PushwooshMessage
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return PushwooshMessage
     */
    public function setData(array $data): PushwooshMessage
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getSendDate()
    {
        return $this->sendDate;
    }

    /**
     * @param Carbon $sendDate
     *
     * @return PushwooshMessage
     */
    public function setSendDate(Carbon $sendDate): PushwooshMessage
    {
        $this->sendDate = $sendDate;

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

    /**
     * @return PushwooshNotification
     */
    public function toNotification()
    {
        $notification = (new PushwooshNotification)
            ->setAndroid($this->android)
            ->setContent($this->content)
            ->setDevices($this->devices);

        if ($this->sendDate instanceof Carbon) {
            $notification->setSendDate($this->sendDate);
        }

        if (!empty($this->data)) {
            $notification->setData($this->data);
        }

        return $notification;
    }

}