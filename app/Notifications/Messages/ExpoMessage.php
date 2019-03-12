<?php
/**
 * Created by PhpStorm.
 * User: rubina.shakhkyan
 * Date: 11.03.2019
 * Time: 15:08
 */

namespace Wizdraw\Notifications\Messages;


use Carbon\Carbon;


class ExpoMessage
{
    /** @var  string */
    private $to;

    /** @var  string */
    private $data;

    /** @var  string */
    private $title;

    /** @var  string */
    private $body;

    /** @var  Carbon */
    private $ttl;

    /** @var  int */
    private $expiration;

    /** @var  'default' | 'normal' | 'high' */
    private $priority;

    /** @var  string */
    private $subtitle;

    /** @var  'default' | null */
    private $sound;

    /** @var  int */
    private $badge;

    /** @var  string */
    private $_category;

    /** @var  string */
    private $channelId;

    /**
     * ExpoMessage constructor.
     *
     * @param string $to
     * @param string $data
     * @param string $title
     * @param string $body
     *
     */
    public function __construct(string $to= '', string $data = '', string $title = '', string $body = '')
    {
        $this->to = $to;
        $this->data = $data;
        $this->title= $title;
        $this->body = $body;
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
     * @return ExpoMessage
     */
    public function setTo(string $to): ExpoMessage
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @param string $data
     *
     * @return ExpoMessage
     */
    public function setData(string $data): ExpoMessage
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return ExpoMessage
     */
    public function setTitle(string $title): ExpoMessage
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return ExpoMessage
     */
    public function setBody(string $body): ExpoMessage
    {
        $this->body = $body;

        return $this;
    }


    public function toNotification()
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', 'https://exp.host/--/api/v2/push/send', [
            'form_params' => [
                'to' => $this->to,
                'title' => $this->title,
                'body' => $this->body
            ]
        ]);
        return $response;
    }



}