<?php

namespace Wizdraw\Services;

use Wizdraw\Models\Campaign;
use Wizdraw\Models\CampaignWithTransfer;
use Wizdraw\Models\Transfer;

/**
 * Class CampaignService
 * @package Wizdraw\Services
 */
class CampaignService extends AbstractService
{

    protected $campaign;
    protected $campaignWithTransfer;

    /**
     * CampaignService constructor.
     * @param Campaign $campaign
     * @param CampaignWithTransfer $campaignWithTransfer
     */
    public function __construct(Campaign $campaign, CampaignWithTransfer $campaignWithTransfer)
    {
        $this->campaign = $campaign;
        $this->campaignWithTransfer = $campaignWithTransfer;
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

    /**
     * @param $commissionsArray
     * @param $isConst
     * @param $commissionToSet
     * @return mixed
     */
    public function setNewCommission($commissionsArray, $isConst, $commissionToSet)
    {
        foreach($commissionsArray as $commissionObj){
            $isConst ? $commissionObj->setConst($commissionToSet) : $commissionObj->setPercentage($commissionToSet);
        }

        return $commissionsArray;
    }

    /**
     * @param $campaign
     * @param Transfer $transfer
     * @return bool
     */
    public function createInCampaignsWithTransfers($campaign, Transfer $transfer)
    {
        return $this->campaignWithTransfer->insertToCampaignsWithTransfers($campaign[0]->id, $transfer->id, $transfer->transactionNumber);
    }


}