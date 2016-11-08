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

    /** @var  GuzzleClient */
    private $guzzleClient;

    /**
     * SmsService constructor.
     * @param Client $guzzleClient
     * @param string $phone
     */
    public function __construct(Client $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }


    /**
 * @param $phone
 * @param $verifyCode
 * @return bool|CashCardTransactions|SimpleXMLElement
 */
    public function sendSmsNewClient($phone,$verifyCode)
    {

        $text = 'You have successfully granted access to wizdraw!Simply return to wizdraw and enter PIN to complete the process. activation code:' . $verifyCode;
        $text = urlencode($text);
        $response = $this->sendSms($phone,$text);
        return $response;
    }

    /**
     * @param $phone
     * @param $amount
     * @param $currency
     * @return bool|CashCardTransactions|SimpleXMLElement|CashCardTransactions|SimpleXMLElement
     */
    public function sendSmsNewTransfer($phone,$amount,$currency)
    {

        $text = $amount . ' ' . $currency . ' from Yuval Tal waiting for you to withdrawal.';
        $text = urlencode($text);
        $response = $this->sendSms($phone,$text);
        return $response;
    }


    /**
     * @param $phone
     * @param $text
     * @return bool|CashCardTransactions|SimpleXMLElement
     */
    private function sendSms($phone,$text){
        $phone = '+' . preg_replace('/[^0-9]/', '', $phone);
        $url = self::API_URL . '&text='.  $text . '&from=Wizdraw&to=' . $phone;
        $response = json_decode($this->guzzleClient->get($url)->getBody(), true);
        \Log::error('Got an error: ' . print_r($response, true));
        if ($response['sms_response_code'] !== '200') {
            \Log::error('Got an error: ' . print_r($response, true));
            $response = false;
        } else {
            $response = true;
        }
        return $response;

    }

}