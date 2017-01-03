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

if (!function_exists('convert_base64_to_jpeg')) {
    /**
     * @param string $data
     *
     * @return mixed
     */
    function convert_base64_to_jpeg(string $data)
    {
        ob_start();
        imagejpeg(imagecreatefromstring($data));
        $jpegImage = ob_get_contents();
        ob_end_clean();

        $filePath = 'temp/' . time() . '.jpg';
        Storage::disk()->put($filePath, $jpegImage);

        return $filePath;
    }
}