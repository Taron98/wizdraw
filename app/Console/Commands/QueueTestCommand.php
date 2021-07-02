<?php

namespace Wizdraw\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Wizdraw\Cache\Jobs\BankQueueJob;
use Wizdraw\Cache\Jobs\BrancheQueueJob;
use Wizdraw\Cache\Jobs\CommissionQueueJob;
use Wizdraw\Cache\Jobs\CountryQueueJob;
use Wizdraw\Cache\Jobs\RateQueueJob;
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
//        $user = User::find(11);
//        $transfer = Transfer::find(58);
//        $user->notify(new TransferMissingReceipt($transfer));
//        die;
//        $client = Client::find(1);
//        $targetTIme = $client->getTargetTime(8);
//        die;
//
//        $now = Carbon::now();
//        $tomorrow = Carbon::tomorrow();
//
//        $bla = $now > $tomorrow;
//        $bla2 = $now < $tomorrow;
//
//        // $next = $now + 5
//        // if $next > 8pm
//        // $next = 8am
//        // else
//        // $next = $next

        $this->writeCountries();
        $this->writeBanks();
//        $this->writeRates();
        $this->writeProvinces();
//        $this->writeCommissions();
//        $this->writeIfsc();


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

    private function writeProvinces()
    {
        $data = file_get_contents(database_path('cache/provinces.json'));
        $provinces        = json_decode($data);
        $redis            = Redis::connection();
        $groupedProvinces = [];
        foreach ($provinces as $province) {
            $groupedProvinces[$province->country_id][] = $province->name;
        }

        foreach ($groupedProvinces as $key => $groupedProvince) {
	 $redis->lpush(redis_key('provinces', $key), $groupedProvince);
        }
    }

    private function writeCommissions()
    {
        $data = file_get_contents(database_path('cache/commissions.json'));
        dispatch(new CommissionQueueJob($data));
    }

    private function addOrigin()
    {
        $data = file_get_contents(database_path('cache/commissions.json'));
        $country = json_decode($data);
        foreach ($country as $c) {
            $c->{'origin'} = 13;
        }
        $json_data = json_encode($country);
        file_put_contents(database_path('cache/commissionsOriginIsrael.json'), $json_data);
    }



    private function writeIfsc()
    {
        $data = file_get_contents(database_path('cache/ifsc1.json'));
        dispatch(new BrancheQueueJob($data));

        $data = file_get_contents(database_path('cache/ifsc2.json'));
        dispatch(new BrancheQueueJob($data));

        $data = file_get_contents(database_path('cache/ifsc3.json'));
        dispatch(new BrancheQueueJob($data));

        $data = file_get_contents(database_path('cache/ifsc4.json'));
        dispatch(new BrancheQueueJob($data));
    }

}
