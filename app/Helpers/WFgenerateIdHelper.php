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

        /* local host */
        curl_setopt($ch, CURLOPT_URL, env('WIC_WF_GENERATOR_URL'));

        /* wic test env */
       // curl_setopt($ch, CURLOPT_URL,"52.21.225.207/transfers/new_wic_files/Server/initTransactionID.php");

        /* wic production env */
        //curl_setopt($ch, CURLOPT_URL,"54.86.248.41/transfers/new_wic_files/Server/initTransactionID.php");

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);
        $response = json_decode($server_output);
        curl_close ($ch);
        return $response->{'response'};
    }

}
