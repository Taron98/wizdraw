<?php


const PASSWORD = 'WiZ32#WIC';
const USER = 'WFMobileWiz';

if (!function_exists('getWfId')) {
    function getWfId()
    {

        // set post fields
        $post = [
            'username' => USER,
            'password' => PASSWORD,
            'method'   => 'getIdForWizdraw',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"52.21.225.207/wic/html/transfers/new_wic_files/Server/initTransactionID.php?XDEBUG_SESSION_START=PHPSTORM");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);
        $response = json_decode($server_output);
        curl_close ($ch);
    }

}

getWfId();