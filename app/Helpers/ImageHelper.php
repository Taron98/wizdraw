<?php

use SimpleSoftwareIO\QrCode\Facades\QrCode;

if (!function_exists('generate_qr_code')) {
    /**
     * @param $string
     *
     * @return mixed
     */
    function generate_qr_code($string)
    {
        $type = 'png';

        $qrCodeBinary = QrCode::format($type)
            ->size(500)
            ->errorCorrection('H')
            ->merge('/resources/assets/images/qr_icon.png')
            ->generate($string);

        $qrCode = 'data:image/' . $type . ';base64,' . base64_encode($qrCodeBinary);

        return $qrCode;
    }
}