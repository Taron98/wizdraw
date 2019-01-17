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

if (!function_exists('generate_qr_code_7_eleven')) {

    /**
     * @param string $amount
     * @param string $account
     *
     * @return string
     */
    function generate_qr_code_7_eleven(string $amount, string $account)
    {
        $merchantCode = '624';
        $billType = '00';
        $dealExpiry = date('Ymd', strtotime('+2 days'));

        $account = $account . getCheckSum($account);
        $account = zeroGenerator($account, 20);
        $amount = zeroGenerator($amount, 10, true, true);

        $qr = $merchantCode . $billType . $account . $amount;

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
        $qr = 'Amount to charge:' . $amount . ' Transaction ID:' . $wf . ' Transaction Date:' . $date . ' Sender full name:' . $senderFullName . ' Affiliate code:' . $affiliateCode;

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
    function getCheckSum($data, $mod = 37)
    {
        $digits = strlen($data);

        // Sets the data (string*) lenght to be exact 9 characters
        if ($digits < 9) {
            getCheckSum('0' . $data);
        }

        $weights = [];

        // Sums current digit plus the previous digit to a valid weight
        for ($idx = 0; $idx < $digits; $idx++) {

            $prev = $idx - 1;

            $weights[] = $idx !== 0
                ? (int)$data[$prev] + (int)$data[$idx]
                : (int)$data[$idx];
        }

        if ($digits === count($weights)) {

            $sum = 0;

            for ($i = 0; $i < $digits; $i++) {
                $sum += (int)$data[$i] + $weights[$i];
            }

            $remainder = $sum % $mod;

            $checksum = $remainder
                ? $mod - $remainder
                : $remainder;
            return (string)$checksum;
        }
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

if (!function_exists('zeroGenerator')) {

    /**
     * @param string $str
     * @param int $totalTabsLength
     * @param bool $rtl
     * @param bool $float
     *
     * @return mixed|string
     */
    function zeroGenerator(string $str, int $totalTabsLength = 0, bool $rtl = true, bool $float = false)
    {
        $str = $float ? number_format((float)$str, 2, '', '') : $str;

        if (strlen($str) < $totalTabsLength) {
            $rtl
                ? zeroGenerator('0' . $str, $totalTabsLength, $rtl)
                : zeroGenerator($str . '0', $totalTabsLength, $rtl);
        }

        return $str;
    }
}