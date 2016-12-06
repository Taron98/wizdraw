<?php

namespace Wizdraw\Services;

use GuzzleHttp\Client;
use Wizdraw\Models\Transfer;
use Wizdraw\Models\TransferType;


/**
 * Class SmsService
 * @package Wizdraw\Services
 */
class SmsService extends AbstractService
{

    const API_URL = 'https://154.48.192.2/VSServices/SendSms.ashx?login=1258965269888&pass=Test$WF@01!';

    /** @var Client */
    private $guzzleClient;

    /**
     * SmsService constructor.
     *
     * @param Client $guzzleClient
     */
    public function __construct(Client $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * @param $phone
     * @param $verifyCode
     * @param $isFirstTime
     *
     * @return bool
     */
    public function sendSmsNewClient($phone, $verifyCode, $isFirstTime = false)
    {
        $expireInMinutes = config('auth.verification.expire') / 60;

        if ($isFirstTime) {
            $text = trans('sms.verification_first_time', [
                'verifyCode'      => $verifyCode,
                'expireInMinutes' => $expireInMinutes,
            ]);
        } else {
            $text = trans('sms.verification', [
                'verifyCode' => $verifyCode,
            ]);
        }

        $text = urlencode($text);
        $response = $this->sendSms($phone, $text);

        return $response;
    }

    /**
     * @param Transfer $transfer
     * @param $phone
     * @param $receiverName
     *
     * @return bool
     */
    public function sendSmsTransferWaiting(Transfer $transfer, $phone, $receiverName)
    {
        $attributes = [
            'receiverName'      => $receiverName,
            'transactionNumber' => $transfer->getTransactionNumber(),
        ];

        if ($transfer->type->getType() === TransferType::TYPE_PICKUP_CASH) {
            $text = trans('sms.transfer_receiver_pick_up', $attributes);
        } else {
            $text = trans('sms.transfer_receiver_deposit', $attributes);
        }

        $text = urlencode($text);
        $response = $this->sendSms($phone, $text);

        return $response;
    }

    /**
     * @param $phone
     * @param $senderName
     * @param string $transactionNumber
     *
     * @return bool
     */
    public function sendSmsTransferCompleted($phone, $senderName, string $transactionNumber = '')
    {
        $text = trans('sms.transfer_sender', [
            'senderName'        => $senderName,
            'transactionNumber' => $transactionNumber,
        ]);
        $text = urlencode($text);
        $response = $this->sendSms($phone, $text);

        return $response;
    }

    /**
     * @param $phone
     * @param $text
     *
     * @return bool
     */
    private function sendSms($phone, $text)
    {
        $phone = '+' . preg_replace('/[^0-9]/', '', $phone);

        $url = self::API_URL . '&text=' . $text . '&from=Wizdraw&to=' . $phone;
        $response = $this->guzzleClient->get($url, ['verify' => false])->getBody()->getContents();

        $response = simplexml_load_string(str_replace('utf-16', 'utf-8', $response));
        $response = json_decode(json_encode((array)$response), true);
        \Log::error('Got an error: ' . print_r($response, true));

        if ($response[ 'sms_response_code' ] !== '200') {
            \Log::error('Got an error: ' . print_r($response, true));
            $response = false;
        } else {
            $response = true;
        }

        return $response;
    }

}