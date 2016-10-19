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

    public function sendSms(string $phone){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,'https://188.138.96.222/VSServices/SendSms.ashx?login=1258965269888&pass=Test$WF@01!&text=TEST%20TEST%20ETSS!&from=+972537916395&to=+972542223839');
        $server_output = curl_exec ($ch);
        curl_close ($ch);

    }

}