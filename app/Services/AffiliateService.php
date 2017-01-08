<?php

namespace Wizdraw\Services;

use Wizdraw\Repositories\AffiliateRepository;

/**
 * Class ClientService
 * @package Wizdraw\Services
 */
class AffiliateService extends AbstractService
{

    /**
     * ClientService constructor.
     *
     * @param AffiliateRepository $affiliateRepository
     */
    public function __construct(AffiliateRepository $affiliateRepository)
    {
        $this->repository = $affiliateRepository;
    }

    /**
     * @param $code
     *
     * @return mixed
     */
    public function findByCode($code)
    {
        return $this->repository->findByCode($code);
    }

}