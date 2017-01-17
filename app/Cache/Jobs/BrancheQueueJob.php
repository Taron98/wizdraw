<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 1/15/2017
 * Time: 10:46
 */

namespace Wizdraw\Cache\Jobs;

use Wizdraw\Cache\Services\BranchCacheService;

/**
 * Class BrancheQueueJob
 * @package Wizdraw\Cache\Jobs
 */
class BrancheQueueJob extends AbstractQueueJob
{

    /**
     * @return string
     */
    protected function cacheService(): string
    {
        return BranchCacheService::class;
    }

}