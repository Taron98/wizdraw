<?php

namespace Wizdraw\Jobs;

use Wizdraw\Cache\Services\BankCacheService;

/**
 * Class BankQueueJob
 * @package Wizdraw\Jobs
 */
class BankQueueJob extends AbstractQueueJob
{

    /**
     * Execute the job
     *
     * @param BankCacheService $bankCacheService
     */
    public function handle(BankCacheService $bankCacheService)
    {
        $bankCacheService->saveFromQueue($this->data);
    }

}
