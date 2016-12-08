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

    const API_URL = 'https://sw.vauxtalk.com/VSServices/SendSms.ashx?login=1258965269888&pass=Test$WF@01!';

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
            $text = "Your Wizdraw verification code is {$verifyCode}.\nSimply open the app and enter the code to complete the process.\nThis code is valid for {$expireInMinutes} hours.";
        } else {
            $text = "Your verification code is {$verifyCode}.";
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
        if ($transfer->type->getType() === TransferType::TYPE_PICKUP_CASH) {
            $text = "{$receiverName}\nsent you funds that are waiting for you to pick up. " .
                "Your transaction number is {$transfer->getTransactionNumber()}. " .
                "Open the Wizdraw app for more details.";
        } else {
            $text = "{$receiverName}\ndeposited funds for you in your bank account. " .
                "Your transaction number is {$transfer->getTransactionNumber()}. " .
                "Open the Wizdraw app for more details.";
        }

//        $text = $transfer->getAmount() . ' ' . $currency . ' from ' . $receiverName . ' waiting for you to withdrawal.';
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
        $text = "{$senderName}\nThe transaction has been successfully completed.\nThe transaction ID is: {$transactionNumber}\nThe Wizdraw team";
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