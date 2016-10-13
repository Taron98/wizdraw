<?php

namespace Wizdraw\Jobs;

use Wizdraw\Cache\Services\CommissionCacheService;

/**
 * Class CommissionQueueJob
 * @package Wizdraw\Jobs
 */
class CommissionQueueJob extends AbstractQueueJob
{

    /**
     * Execute the job
     *
     * @param CommissionCacheService $commissionCacheService
     */
    public function handle(CommissionCacheService $commissionCacheService)
    {
        $commissionCacheService->saveFromQueue($this->data);
    }

}
