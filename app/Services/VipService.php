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

        $this->fileService->uploadQrVip($client->getId(), $vip->getVipNumber());

        return $vip;
    }

}