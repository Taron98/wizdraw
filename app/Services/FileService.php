<?php

namespace Wizdraw\Services;

use Approached\LaravelImageOptimizer\ImageOptimizer;
use Illuminate\Filesystem\FilesystemManager;
use League\Flysystem\Filesystem;
use Storage;

/**
 * Class FileService
 * @package Wizdraw\Services
 */
class FileService extends AbstractService
{
    const TYPE_PROFILE = 'profile';
    const TYPE_IDENTITY = 'identity';
    const TYPE_RECEIPT = 'receipt';
    const TYPE_ADDRESS = 'address';
    const TYPE_QR_VIP = 'vip';
    const TYPE_QR_CIRCLE_K = 'circle-k';
    const TYPE_QR_7_ELEVEN = '7-eleven';
    const TYPE_QR_PAY_TO_AGENT = 'pay-to-agent';

    const DEFAULT_QR_EXT = 'png';
    const DEFAULT_FILE_EXT = 'jpg';

    const HK_ACCOUNT_NUMBER = '085730-60000537';

    /** @var  Filesystem */
    private $fileSystem;

    /** @var  Filesystem */
    private $localFileSystem;

    /** @var ImageOptimizer */
    private $imageOptimizer;

    /**
     * FileService constructor.
     *
     * @param FilesystemManager $fileSystem
     * @param ImageOptimizer $imageOptimizer
     */
    public function __construct(FilesystemManager $fileSystem, ImageOptimizer $imageOptimizer)
    {
        $this->localFileSystem = $fileSystem->disk();
        $this->fileSystem = $fileSystem->cloud();
        $this->imageOptimizer = $imageOptimizer;
    }

    /**
     * @param string $type
     * @param string $name
     * @param string $data
     *
     * @return bool
     */
    public function upload(string $type, string $name, string $data): bool
    {
        $file = $this->extractFile($data);

        if (is_null($file)) {
            return false;
        }

        $filePath = $this->getFilePath($type, $name);

        return $this->fileSystem->put($filePath, $file);
    }

    /**
     * @param string $name
     * @param string $data
     *
     * @return bool
     */
    public function uploadProfile(string $name, string $data): bool
    {
        return $this->upload(self::TYPE_PROFILE, $name, $data);
    }

    /**
     * @param string $name
     * @param string $data
     *
     * @return bool
     */
    public function uploadIdentity(string $name, string $data): bool
    {
        return $this->upload(self::TYPE_IDENTITY, $name, $data);
    }

    /**
     * @param string $name
     * @param string $data
     *
     * @return bool
     */
    public function uploadReceipt(string $name, string $data): bool
    {
        return $this->upload(self::TYPE_RECEIPT, $name, $data);
    }

    /**
     * @param string $name
     * @param string $data
     *
     * @return bool
     */
    public function uploadAddress(string $name, string $data): bool
    {
        return $this->upload(self::TYPE_ADDRESS, $name, $data);
    }

    /**
     * @param string $name
     * @param string $vipNumber
     *
     * @return bool
     */
    public function uploadQrVip(string $name, string $vipNumber)
    {
        $qrCode = generate_qr_code(substr($vipNumber,1));

        return $this->upload(self::TYPE_QR_VIP, $name, $qrCode);
    }

    /**
     * @param string $name
     * @param string $amount
     *
     * @return array
     */
    public function uploadQr7Eleven(string $name, string $amount)
    {
        $account = str_replace('-', '', self::HK_ACCOUNT_NUMBER);
        $qrCode = generate_qr_code_7_eleven($amount, $account);

        $res = $this->upload(self::TYPE_QR_7_ELEVEN, $name, $qrCode);

        return ['result' => $res, 'qr' => $qrCode];
    }


    /**
     * @param $wf
     * @param $amount
     *
     * @return array
     */
    public function uploadQrCircleK($wf, $amount)
    {
        $qrCode = generate_qr_code_circle_k($wf, $amount);

        $res = $this->upload(self::TYPE_QR_CIRCLE_K, $wf, $qrCode);

        return ['result' => $res, 'qr' => $qrCode];
    }

    /**
     * @param $wf
     * @param $amount
     * @return array
     */
    public function uploadQrPayToAgent($wf, $amount, $date, $senderFullName, $affiliateCode)
    {
        $qrCode = generate_qr_code_pay_to_agent($wf, $amount, $date, $senderFullName, $affiliateCode);

        $res = $this->upload(self::TYPE_QR_PAY_TO_AGENT, $wf, $qrCode);

        return ['result' => $res, 'qr' => $qrCode];
    }

    /**
     * @param string $data
     *
     * @return bool|false|null|string
     */
    public function extractFile(string $data)
    {
        preg_match('#^data:image/([^,;]+);base64,([\s\S]+)#i', $data, $fileMeta);

        if (empty($fileMeta[ 1 ]) || empty($fileMeta[ 2 ])) {
            return null;
        }

        $file = $this->convertAndOptimize(base64_decode($fileMeta[ 2 ]));

        return $file;
    }

    /**
     * @param string $type
     * @param string $name
     *
     * @return bool
     */
    public static function exists(string $type, string $name): bool
    {
        $filePath = $type . '/' . $name . '.' . self::DEFAULT_FILE_EXT;

        return Storage::cloud()->exists($filePath);
    }

    /**
     * @param string $type
     * @param string $name
     *
     * @return null
     */
    public function getUrlIfExists(string $type, string $name)
    {
        $filePath = $this->getFilePath($type, $name);

        if (!$this->fileSystem->exists($filePath)) {
            return null;
        }

        return $this->fileSystem->url($filePath);
    }

    /**
     * @param string $type
     * @param string $name
     *
     * @return string
     */
    private function getFilePath(string $type, string $name)
    {
        return $type . '/' . $name . '.' . self::DEFAULT_FILE_EXT;
    }

    /**
     * @param string $file
     *
     * @return bool|false|string
     */
    private function convertAndOptimize(string $file)
    {
        $filePath = convert_base64_to_jpeg($file);
        $fullFilePath = config('filesystems.disks.local.root') . '/' . $filePath;
        $this->imageOptimizer->optimizeImage($fullFilePath);

        return $this->localFileSystem->readAndDelete($filePath);
    }

}
