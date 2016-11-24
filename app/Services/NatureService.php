<?php

namespace Wizdraw\Services;

use Wizdraw\Repositories\NatureRepository;

/**
 * Class NatureService
 * @package Wizdraw\Services
 */
class NatureService extends AbstractService
{

    /**
     * NatureService constructor.
     *
     * @param NatureRepository $natureRepository
     */
    public function __construct(NatureRepository $natureRepository)
    {
        $this->repository = $natureRepository;
    }

    /**
     * @param string $nature
     *
     * @return mixed
     */
    public function findByNature(string $nature)
    {
        return $this->repository->findByField('nature', $nature)->first();
    }

}