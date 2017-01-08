<?php

namespace Wizdraw\Services;

use Wizdraw\Models\Client;
use Wizdraw\Models\Vip;
use Wizdraw\Repositories\VipRepository;

/**
 * Class VipService
 * @package Wizdraw\Services
 */
class VipService extends AbstractService
{
    const INVALID_NUMBERS = [9606153, 9665643, 9710371];

    /** @var  FileService */
    private $fileService;

    /**
     * VipService constructor.
     *
     * @param VipRepository $vipRepository
     * @param FileService $fileService
     */
    public function __construct(VipRepository $vipRepository, FileService $fileService)
    {
        $this->repository = $vipRepository;
        $this->fileService = $fileService;
    }

    /**
     * @param Client $client
     *
     * @return Vip
     */
    public function createVip(Client $client)
    {
        /** @var Vip $vip */
        $vip = $this->repository->createWithRelation($client);

        $this->fileService->uploadQrVip($client->getId(), $vip->getNumber());

        return $vip;
    }

    /**
     * @return int
     */
    public function generateNumber()
    {
        $number = 9500000 + mt_rand(1, 499999);

        // The number is unique and is not in the invalid list
        if (!$this->repository->exists(['number' => $number]) && !in_array($number, self::INVALID_NUMBERS)) {
            return $number;
        }

        return $this->generateNumber();
    }

}