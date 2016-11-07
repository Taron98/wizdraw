<?php

namespace Wizdraw\Services;


/**
 * Class SmsService
 * @package Wizdraw\Services
 */
class SmsService extends AbstractService
{


    public function __construct()
    {
    }

    public function sendSms($phone, $verifyCode)
    {
        $phone = '+' . preg_replace('/[^0-9]/', '', $phone);
        $text = 'You have successfully granted access to wizdraw! 
                 Simply return to wizdraw and enter PIN to complete the process.
                 activation code:' . $verifyCode;
        $ch = curl_init();
        $string = 'https://188.138.96.222/VSServices/SendSms.ashx?login=1258965269888&pass=Test$WF@01!&text=' . $text . '&from=Wizdraw5&to=' . $phone;
        curl_setopt($ch, CURLOPT_URL, $string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = simplexml_load_string(str_replace('utf-16', 'utf-8', curl_exec($ch)));
        $response = json_decode(json_encode((array)$response), TRUE);

        if ($response['sms_response_code'] !== 200) {
            \Log::error('Got an error: ' . print_r($response, true));

            $response = false;
        } else {
            $response = true;
        }

        curl_close($ch);

        return $response;
    }

}