<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 8/31/2017
 * Time: 09:26
 */

namespace Wizdraw\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Wizdraw\Models\Client;
use Wizdraw\Notifications\UpdateApplication;

class UpdateAppNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wiz:cache:update:app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to application client, prompt them to update the app';

    /**
     * Execute the console command
     *
     */
    public function handle()
    {
        $this->UpdateAppNotification();
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
            ->join('users', 'clients.id', '=', 'users.client_id')
            ->select('clients.id', 'first_name', 'last_name', 'phone')
            ->whereNotNull('phone')
            ->groupBy('users.client_id')
            ->get();

        //$clients = Client::hydrate($Clients->toArray());
        $url = 'https://play.google.com/store/apps/details?id=com.ionicframework.wicapp652054';
        $tinyUrl = $this->createTinyUrl($url);
        $i = 0;
        $ids = [];
        $phones = [];
        foreach ($Clients as $client) {
            $myClient = new Client;
            $myClient->id = $client->id;
            $myClient->phone = $client->phone;
            $myClient->firstName = $client->first_name;
            $myClient->lastName = $client->last_name;
            $myClient->notify(new UpdateApplication($myClient, $tinyUrl));
            $i++;
            array_push($ids, $client->id);
            array_push($phones, $client->phone);
        }

        Log::info(json_encode([
            'UpdateAppNotification' => 'finish sending notification to ' . $i . ' clients',
            'Phones'                => $phones,
            'DB IDs'                => $ids,
        ]));
    }

    function createTinyUrl($strURL)
    {
        $tinyurl = file_get_contents("http://tinyurl.com/api-create.php?url=" . $strURL);

        return $tinyurl;
    }
}