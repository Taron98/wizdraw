<?php

namespace Wizdraw\Services;

use Wizdraw\Models\Campaign;

/**
 * Class CampaignService
 * @package Wizdraw\Services
 */
class CampaignService extends AbstractService
{

    protected $campaign;

    /**
     * CampaignService constructor.
     *
     * @param Campaign $campaign
     */
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * @param $id
     * @return $this
     */
    public function getCampaign($id)
    {
        $campaign = $this->campaign->getCampaignById($id);

        return $campaign;
    }

    public function setNewCommission($commissionsArray, $commissionToSet)
    {
        foreach($commissionsArray as $commissionObj){
            $const = $commissionObj->getConst();
        }

        return $commissionsArray;
    }


}