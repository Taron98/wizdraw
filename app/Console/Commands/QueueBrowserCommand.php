<?php

namespace Wizdraw\Console\Commands;

use Illuminate\Console\Command;
use Wizdraw\Cache\Services\BankCacheService;
use Wizdraw\Cache\Services\CommissionCacheService;
use Wizdraw\Cache\Services\CountryCacheService;
use Wizdraw\Cache\Services\RateCacheService;

/**
 * Class QueueBrowserCommand
 * @package Wizdraw\Console\Commands
 * todo: this is a temporary command, reading from json files
 */
class QueueBrowserCommand extends Command
{

    /** @var  BankCacheService */
    protected $bankCacheService;

    /** @var  CountryCacheService */
    protected $countryCacheService;

    /** @var  RateCacheService */
    protected $rateCacheService;

    /** @var CommissionCacheService */
    protected $commissionCacheService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wiz:queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache tables queue browser';

    /**
     * QueueBrowserCommand constructor.
     *
     * @param BankCacheService $bankCacheService
     * @param CountryCacheService $countryCacheService
     * @param RateCacheService $rateCacheService
     * @param CommissionCacheService $commissionCacheService
     */
    public function __construct(
        BankCacheService $bankCacheService,
        CountryCacheService $countryCacheService,
        RateCacheService $rateCacheService,
        CommissionCacheService $commissionCacheService
    ) {
        parent::__construct();

        $this->bankCacheService = $bankCacheService;
        $this->countryCacheService = $countryCacheService;
        $this->rateCacheService = $rateCacheService;
        $this->commissionCacheService = $commissionCacheService;
    }

    /**
     * Execute the console command
     *
     * @return mixed
     */
    public function handle()
    {
        $this->loadCountries();
        $this->loadBanks();
        $this->loadRates();
        $this->loadCommissions();
    }

    private function loadCountries()
    {
        $data = file_get_contents(database_path('cache/countries.json'));
        $data = json_decode($data);
        $this->countryCacheService->saveFromQueue($data);
    }

    private function loadBanks()
    {
        $data = file_get_contents(database_path('cache/banks.json'));
        $data = json_decode($data);
        $this->bankCacheService->saveFromQueue($data);
    }

    private function loadRates()
    {
        $data = file_get_contents(database_path('cache/rates.json'));
        $data = json_decode($data);
        $this->rateCacheService->saveFromQueue($data);
    }

    private function loadCommissions()
    {
        $data = file_get_contents(database_path('cache/commissions.json'));
        $data = json_decode($data);
        $this->commissionCacheService->saveFromQueue($data);
    }

}
