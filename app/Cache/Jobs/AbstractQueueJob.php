<?php

namespace Wizdraw\Cache\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Wizdraw\Cache\Services\AbstractCacheService;

/**
 * Class AbstractQueueJob
 * @package Wizdraw\Jobs
 */
abstract class AbstractQueueJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /** @var  string */
    protected $data;

    /**
     * AbstractQueueJob constructor.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * Execute the job
     */
    public function handle()
    {
        /** @var AbstractCacheService $cacheService */
        $cacheService = resolve($this->cacheService());

        $cacheService->saveFromQueue($this->data);
    }

    /**
     * @return string
     */
    abstract protected function cacheService() : string;

}
