<?php

namespace Wizdraw\Services;

use Wizdraw\Repositories\TransferRepository;

/**
 * Class TransferService
 * @package Wizdraw\Services
 */
class TransferService extends AbstractService
{

    /**
     * TransferService constructor.
     *
     * @param TransferRepository $transferRepository
     */
    public function __construct(
        TransferRepository $transferRepository
    ) {
        $this->repository = $transferRepository;
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function find(int $id)
    {
        return $this->repository->with(['client', 'receiverClient', 'natures', 'status'])->find($id);
    }

}