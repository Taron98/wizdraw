<?php

namespace Wizdraw\Notifications\Messages;

class SmsMessage
{

    /** @var  string */
    private $text;

    /** @var  string */
    private $to;

    /**
     * SmsMessage constructor.
     *
     * @param string $text
     * @param string $to
     */
    public function __construct($text = '', $to = '')
    {
        $this->text = $text;
        $this->to = $to;
    }

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return SmsMessage
     */
    public function setText(string $text): SmsMessage
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string $to
     *
     * @return SmsMessage
     */
    public function setTo(string $to): SmsMessage
    {
        $this->to = $to;

        return $this;
    }

}