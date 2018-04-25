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


    public function getCampaign($id)
    {
        $campaign = $this->campaign->getCampaignById($id);

        return $campaign;
    }


}