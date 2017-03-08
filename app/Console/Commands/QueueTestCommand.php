<?php

namespace Wizdraw\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Wizdraw\Cache\Jobs\BankQueueJob;
use Wizdraw\Cache\Jobs\BrancheQueueJob;
use Wizdraw\Cache\Jobs\CommissionQueueJob;
use Wizdraw\Cache\Jobs\CountryQueueJob;
use Wizdraw\Cache\Jobs\RateQueueJob;
use Wizdraw\Models\Client;
use Wizdraw\Models\Transfer;
use Wizdraw\Models\User;
use Wizdraw\Notifications\TransferMissingReceipt;
use Wizdraw\Notifications\UpdateApplication;

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
       // $user = User::find(11);
       // $transfer = Transfer::find(58);
       // $user->notify(new TransferMissingReceipt($transfer));
       // die;
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

        $this->UpdateAppNotification();
//        $this->writeCountries();
//        $this->writeBanks();
//        $this->writeRates();
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

    private function writeCommissions()
    {
        $data = file_get_contents(database_path('cache/commissions.json'));
        dispatch(new CommissionQueueJob($data));
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

    private function UpdateAppNotification()
    {

        $Clients = DB::table('clients')
                    ->join('users','clients.id','=','users.client_id')
                    ->select('first_name','last_name','phone')
                    ->whereNotNull('phone')
                    ->groupBy('users.client_id')
                    ->get();

        $i=0;
              foreach ($Clients as $client){
                  $client->notify(new UpdateApplication($client));
                  $i++;
              }

        Log::info(json_encode(['UpdateAppNotification' => 'finish sending notification to '.$i.' clients']));

    }
}
