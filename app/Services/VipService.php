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
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function createVip(Client $client)
    {
        return $this->repository->createWithRelation($client);
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

    /**
     * @param $clientId
     *
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function findByClientId($clientId)
    {
        return $this->repository->findByField('client_id', $clientId)->first();
    }

}