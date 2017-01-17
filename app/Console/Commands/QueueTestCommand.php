<?php

namespace Wizdraw\Console\Commands;

use Illuminate\Console\Command;
use Wizdraw\Cache\Jobs\BankQueueJob;
use Wizdraw\Cache\Jobs\BrancheQueueJob;
use Wizdraw\Cache\Jobs\CommissionQueueJob;
use Wizdraw\Cache\Jobs\CountryQueueJob;
use Wizdraw\Cache\Jobs\RateQueueJob;
use Wizdraw\Services\FileService;

/**
 * Class QueueTestCommand
 * @package Wizdraw\Console\Commands
 * todo: this is a temporary command, reading from json files
 */
class QueueTestCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wiz:cache:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache tables queue tester';

    /**
     * Execute the console command
     *
     * @return mixed
     */
    public function handle()
    {
        $this->writeCountries();
        $this->writeBanks();
        $this->writeRates();
        $this->writeCommissions();
        $this->writeIfsc();
        $this->writeIfsc2();
    }

    private function writeCountries()
    {
        $data = file_get_contents(database_path('cache/countries.json'));
        dispatch(new CountryQueueJob($data));
    }

    private function writeBanks()
    {
        $data = file_get_contents(database_path('cache/banks/global.json'));
        dispatch(new BankQueueJob($data));

//        $data = file_get_contents(database_path('cache/banks/ime_banks.json'));
//        dispatch(new BankQueueJob($data));

//        $data = file_get_contents(database_path('cache/banks/metro.json'));
//        dispatch(new BankQueueJob($data));

//        $data = file_get_contents(database_path('cache/banks/bdo.json'));
//        dispatch(new BankQueueJob($data));
    }

    private function writeRates()
    {
        $data = file_get_contents(database_path('cache/rates.json'));
        dispatch(new RateQueueJob($data));
    }

    private function writeCommissions()
    {
        $data = file_get_contents(database_path('cache/commissions.json'));
        dispatch(new CommissionQueueJob($data));
    }

    private function writeIfsc()
    {
        $data = file_get_contents(database_path('cache/ifscFirst.json'));
        dispatch(new BrancheQueueJob($data));
    }

    private function writeIfsc2()
    {
        $data = file_get_contents(database_path('cache/ifscSecond.json'));
        dispatch(new BrancheQueueJob($data));
    }

    function stripslashes_deep($value)
    {
        $value = is_array($value) ?
            array_map('stripslashes_deep', $value) :
            stripslashes($value);

        return str_replace("\\", '', $value);
    }

}
