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
        $checksum = getCheckSum("00" . $string);
        $qr = "098030" . "000" . $string . $checksum;

        $type = 'png';

        $qrCodeBinary = QrCode::format($type)
            ->size(500)
            ->errorCorrection('H')
            ->merge('/resources/assets/images/qr_icon.png')
            ->generate($qr);

        $qrCode = 'data:image/' . $type . ';base64,' . base64_encode($qrCodeBinary);

        return $qrCode;
    }
}

if (!function_exists('generate_qr_code_circle_k')) {
    /**
     * @param $wf
     *
     * @param $amount
     *
     * @return mixed
     */
    function generate_qr_code_circle_k($wf,$amount)
    {
        $billType = "00";
        $invoice = substr($wf,3,12);
        $amountDigit = substr($amount,0,1);
        $amountFormatted = handleFloatAmount($amount);
        $qr = "9091111001" . $billType . $invoice . "000000009479769" . $amountFormatted . $amountDigit;

        $type = 'png';

        $qrCodeBinary = QrCode::format($type)
            ->size(500)
            ->errorCorrection('H')
            ->merge('/resources/assets/images/qr_icon.png')
            ->generate($qr);

        $qrCode = 'data:image/' . $type . ';base64,' . base64_encode($qrCodeBinary);

        return $qrCode;
    }
}

if (!function_exists('generate_qr_code_pay_to_agent')) {
    /**
     * @param $wf
     *
     * @param $amount
     *
     * @return mixed
     */
    function generate_qr_code_pay_to_agent($wf,$amount,$date,$senderFullName,$affiliateCode)
    {
        $affiliateCode = $affiliateCode ? $affiliateCode : 'No Affiliator';
        $qr = 'Amount to charge:' . $amount . 'Transaction ID:' . $wf . 'Transaction Date:' . $date . 'Sender full name:' . $senderFullName . 'Affiliate code:' . $affiliateCode;

        $type = 'png';

        $qrCodeBinary = QrCode::format($type)
            ->size(500)
            ->errorCorrection('H')
            ->merge('/resources/assets/images/qr_icon.png')
            ->generate($qr);

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

if (!function_exists('getCheckSum')) {

    /**
     * @param $data
     *
     * @return string
     */
    function getCheckSum($data)
    {

        $sum = 0;
        $mod = 9;
        $weight = 12;
        $checksum = -1;

        for ($i = strlen($data) - 1; $i >= 0; $i--) {
            $value = (int)$data[ $i ];
            $sum = $sum + $value * $weight;
            $weight--;
        }

        $remainder = $sum % $mod;
        if ($remainder == 0) {
            $checksum = 0;
        } else {
            $checksum = $mod - $remainder;
        }

        return (string)$checksum;
    }

}

if (!function_exists('handleFloatAmount')) {

    function handleFloatAmount($amount)
    {
        $amountFormatted = number_format($amount,1,'.','');
        $amountFormatted = str_replace('.','',$amountFormatted);
        $amountLength = strlen($amountFormatted);
        $amountPrefix = "";
        for($i=0;$i<7-$amountLength;$i++){
            $amountPrefix.="0";
        }

        return $amountPrefix . $amountFormatted;



    }
}