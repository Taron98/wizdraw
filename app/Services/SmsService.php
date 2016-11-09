<?php

namespace Wizdraw\Services;

use GuzzleHttp\Client;


/**
 * Class SmsService
 * @package Wizdraw\Services
 */
class SmsService extends AbstractService
{

    const API_URL = 'https://188.138.96.222/VSServices/SendSms.ashx?login=1258965269888&pass=Test$WF@01!';

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
     *
     * @return bool|CashCardTransactions|SimpleXMLElement
     */
    public function sendSmsNewClient($phone, $verifyCode)
    {
        $text = 'You have successfully granted access to wizdraw! Simply return to wizdraw and enter PIN to complete the process. activation code: ' . $verifyCode;
        $text = urlencode($text);
        $response = $this->sendSms($phone, $text);

        return $response;
    }

    /**
     * @param $phone
     * @param $amount
     * @param $currency
     * @param $receiverName
     *
     * @return bool|CashCardTransactions|SimpleXMLElement|CashCardTransactions|SimpleXMLElement
     */
    public function sendSmsNewTransfer($phone, $amount, $currency, $receiverName)
    {
        $text = $amount . ' ' . $currency . ' from ' . $receiverName . ' waiting for you to withdrawal.';
        $text = urlencode($text);
        $response = $this->sendSms($phone, $text);

        return $response;
    }


    /**
     * @param $phone
     * @param $text
     *
     * @return bool|CashCardTransactions|SimpleXMLElement
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