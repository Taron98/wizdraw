<?php

namespace Wizdraw\Services;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;

/**
 * Class FileService
 * @package Wizdraw\Services
 */
class FileService extends AbstractService
{
    const TYPE_PROFILE = 'profile/';
    const TYPE_IDENTITY = 'identity/';
    const TYPE_RECEIPT = 'receipts/';

    const DEFAULT_FILE_EXT = 'jpg';

    /** @var  Filesystem */
    private $fileSystem;

    /**
     * FileService constructor.
     *
     * @param FilesystemManager $fileSystem
     */
    public function __construct(FilesystemManager $fileSystem)
    {
        $this->fileSystem = $fileSystem->disk('s3');
    }

    /**
     * @param string $type
     * @param string $name
     * @param string $data
     *
     * @return bool|void
     */
    public function upload(string $type, string $name, string $data) : bool
    {
        // todo: reduce image size and con
        $file = $this->extractFile($data);

        if (is_null($file)) {
            return false;
        }

        $filePath = $this->getFilePath($type, $name);

        return $this->fileSystem->put($filePath, $file[ 'content' ]);
    }

    /**
     * @param string $name
     * @param string $data
     *
     * @return bool
     */
    public function uploadProfile(string $name, string $data) : bool
    {
        return $this->upload(self::TYPE_PROFILE, $name, $data);
    }

    /**
     * @param string $name
     * @param string $data
     *
     * @return bool
     */
    public function uploadIdentity(string $name, string $data) : bool
    {
        return $this->upload(self::TYPE_IDENTITY, $name, $data);
    }

    /**
     * @param string $name
     * @param string $data
     *
     * @return bool
     */
    public function uploadReceipt(string $name, string $data) : bool
    {
        return $this->upload(self::TYPE_RECEIPT, $name, $data);
    }

    /**
     * @param string $data
     *
     * @return array|null
     */
    public function extractFile(string $data)
    {
        preg_match('#^data:image/([^,;]+);base64,([\s\S]+)#i', $data, $fileMeta);

        if (empty($fileMeta[ 1 ]) || empty($fileMeta[ 2 ])) {
            return null;
        }

        return [
            'type'    => $fileMeta[ 1 ],
            'content' => base64_decode($fileMeta[ 2 ]),
        ];
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
        return $type . $name . "." . self::DEFAULT_FILE_EXT;
    }

}
