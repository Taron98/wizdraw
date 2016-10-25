<?php

namespace Wizdraw\Services;

use Wizdraw\Repositories\StatusRepository;

/**
 * Class StatusService
 * @package Wizdraw\Services
 */
class StatusService extends AbstractService
{

    /**
     * StatusService constructor.
     *
     * @param StatusRepository $statusRepository
     */
    public function __construct(StatusRepository $statusRepository)
    {
        $this->repository = $statusRepository;
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