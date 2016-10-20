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
        $string = 'https://188.138.96.222/VSServices/SendSms.ashx?login=1258965269888&pass=Test$WF@01!&text=check%20this%20code-'. $verifyCode . '&from=+972537916395&to=' . $phone;
        curl_setopt($ch, CURLOPT_URL,$string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if($response === false)
        {
            $response =  false;
        }
        else
        {
            $response =  true;
        }
        curl_close ($ch);
        return $response;
    }

}