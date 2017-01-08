<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\Affiliate;

/**
 * Class AffiliateRepository
 * @package Wizdraw\Repositories
 */
class AffiliateRepository extends AbstractRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Affiliate::class;
    }

    /**
     * @param $affiliateCode
     *
     * @return mixed
     */
    public function findByCode($affiliateCode)
    {
        return $this->findByField('code', $affiliateCode)->first();
    }

}