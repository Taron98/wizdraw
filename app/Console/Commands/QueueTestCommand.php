<?php

namespace Wizdraw\Console\Commands;

use Illuminate\Console\Command;
use Wizdraw\Cache\Jobs\BankQueueJob;
use Wizdraw\Cache\Jobs\BrancheQueueJob;
use Wizdraw\Cache\Jobs\CommissionQueueJob;
use Wizdraw\Cache\Jobs\CountryQueueJob;
use Wizdraw\Cache\Jobs\RateQueueJob;
use Wizdraw\Models\Transfer;
use Wizdraw\Models\User;
use Wizdraw\Notifications\TransferMissingReceipt;

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

       // $this->UpdateAppNotification();
        $this->writeCountries();
        $this->writeBanks();
        $this->writeRates();
//        $this->writeCommissions();
//        $this->writeIfsc();
        $this->writeOriginToDestinationCommissions();

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

    private function addOrigin()
    {
        $data = file_get_contents(database_path('cache/commissions.json'));
        $country = json_decode($data);
        foreach ($country as $c){
            $c->{'origin'}= 13;
        }
        $json_data = json_encode($country);
        file_put_contents(database_path('cache/commissionsOriginIsrael.json'), $json_data);
    }

    private function writeOriginToDestinationCommissions()
    {
        $data = file_get_contents(database_path('cache/commissionsOriginIsrael.json'));
        dispatch(new CommissionQueueJob($data));

        $data = file_get_contents(database_path('cache/commissionsOriginHONGKONG.json'));
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

    /**
     * this function send SMS to all the clients in the application to inform them there's new version of the application.
     * #### IMPORTANT ####
     * Instructions: change the 'strict' configuration under \config\database.php to false! (instead of true value), the groupBy query won't
     * work since the server is using 'ONLY_FULL_GROUP_BY' mode and we need to shut it down.
     * my suggestion is to run this on the pre-prod server, just export the production DB to the pre-prod server and run it over there.
     * Don't forget to change the strict value back to true again after you finished.
     */
    private function UpdateAppNotification()
    {

        $Clients = DB::table('clients')
                    ->join('users','clients.id','=','users.client_id')
                    ->select('clients.id','first_name','last_name','phone')
                    ->whereNotNull('phone')
                    ->groupBy('users.client_id')
                    ->get();

        //$clients = Client::hydrate($Clients->toArray());
        $url = 'https://play.google.com/store/apps/details?id=com.ionicframework.wicapp652054';
        $tinyUrl = createTinyUrl($url);
        $i=0;
        $ids = array();
        $phones = array();
              foreach ($Clients as $client){
                  $myClient = new Client;
                  $myClient->id = $client->id;
                  $myClient->phone = $client->phone;
                  $myClient->firstName = $client->first_name;
                  $myClient->lastName = $client->last_name;
                  $myClient->notify(new UpdateApplication($myClient,$tinyUrl));
                  $i++;
                  array_push($ids,$client->id);
                  array_push($phones,$client->phone);
              }

        Log::info(json_encode(['UpdateAppNotification' => 'finish sending notification to '.$i.' clients', 'Phones' => $phones, 'DB IDs' => $ids]));
    }

    function createTinyUrl($strURL) {
        $tinyurl = file_get_contents("http://tinyurl.com/api-create.php?url=" . $strURL);
        return $tinyurl;
    }

    private function TestQueue()
    {

    }


}
