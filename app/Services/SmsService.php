<?php

namespace Wizdraw\Services;


/**
 * Class SmsService
 * @package Wizdraw\Services
 */
class SmsService extends AbstractService
{


    public function __construct( )
    {
    }

    public function sendSms(string $phone,string $verifyCode){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,'https://188.138.96.222/VSServices/SendSms.ashx?login=1258965269888&pass=Test$WF@01!&text=check this code '. $verifyCode . '&from=+972537916395&to=' . $phone);
        $string = 'https://188.138.96.222/VSServices/SendSms.ashx?login=1258965269888&pass=Test$WF@01!&text=check this code '. $verifyCode . '&from=+972537916395&to=' . $phone;
        print_r($string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        if(curl_exec($ch) === false)
        {
            echo 'Curl error: ' . curl_error($ch);
        }
        else
        {
            echo 'Operation completed without any errors';
        }
        curl_close ($ch);

    }

}