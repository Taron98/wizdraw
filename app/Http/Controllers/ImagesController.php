<?php


namespace Wizdraw\Http\Controllers;


use Wizdraw\Services\FileService;

class ImagesController extends FileService
{
    public function getImage($type,$transactionId)
    {
        return $this->getImageBase64($type,$transactionId);
    }
}
