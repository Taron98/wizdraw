<?php

namespace Wizdraw\Observers;

use Wizdraw\Models\Vip;
use Wizdraw\Services\VipService;

/**
 * Class VipObserver
 * @package Wizdraw\Models
 */
class VipObserver
{

    /** @var  VipService */
    private $vipService;

    /**
     * VipObserver constructor.
     *
     * @param VipService $vipService
     */
    public function __construct(VipService $vipService)
    {
        $this->vipService = $vipService;
    }

    /**
     * @param Vip $vip
     */
    public function creating(Vip $vip)
    {
        $newNumber = $this->vipService->generateNumber();

        $vip->setNumber($newNumber);
    }

}