<?php

namespace Wizdraw\Jobs;

use Wizdraw\Cache\Services\CountryCacheService;

/**
 * Class CountryQueueJob
 * @package Wizdraw\Jobs
 */
class CountryQueueJob extends AbstractQueueJob
{

    /**
     * Execute the job
     *
     * @param CountryCacheService $countryCacheService
     */
    public function handle(CountryCacheService $countryCacheService)
    {
        $countryCacheService->saveFromQueue($this->data);
    }

}
