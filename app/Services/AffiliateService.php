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

    public function getAffilaiteCodeId($affiliateCode)
    {
        return $this->repository->getAffilaiteCodeId($affiliateCode);
    }
    
    
    

}