<?php


namespace Wizdraw\Repositories;

use Wizdraw\Models\Affiliate;

/**
 * Created by PhpStorm.
 * User: user
 * Date: 1/5/2017
 * Time: 13:42
 */
class AffiliateRepository extends AbstractRepository
{


    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model() : string
    {
        return Affiliate::class;
    }
    
    public function getAffilaiteCodeId($affiliateCode){
        
        return $this->findByField('code', $affiliateCode)->first();

    }

}