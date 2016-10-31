<?php

namespace Wizdraw\Services;

use Wizdraw\Repositories\TransferStatusRepository;

/**
 * Class TransferStatusService
 * @package Wizdraw\Services
 */
class TransferStatusService extends AbstractService
{

    /**
     * TransferStatusService constructor.
     *
     * @param TransferStatusRepository $transferStatusRepository
     */
    public function __construct(TransferStatusRepository $transferStatusRepository)
    {
        $this->repository = $transferStatusRepository;
    }

    /**
     * @param string $status
     *
     * @return mixed
     */
    public function findByStatus(string $status)
    {
        return $this->repository->findByField('status', $status)->first();
    }

}