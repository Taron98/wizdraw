<?php

namespace Wizdraw\Console\Commands;

use Illuminate\Console\Command;
use Wizdraw\Cache\Jobs\CommissionQueueJob;
use Illuminate\Support\Facades\Redis;

class CommissionAndActiveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wiz:cache:commission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache commissions';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->writeOriginToDestinationCommissions();
        $this->manageActiveCountries();
    }

    private function writeOriginToDestinationCommissions()
    {
        $data = file_get_contents(database_path('cache/commissionsOriginIsrael.json'));
        dispatch(new CommissionQueueJob($data));

        $data = file_get_contents(database_path('cache/commissionsOriginHONGKONG.json'));
        dispatch(new CommissionQueueJob($data));

        $data = file_get_contents(database_path('cache/commissionsOriginSingapore.json'));
        dispatch(new CommissionQueueJob($data));

        $data = file_get_contents(database_path('cache/commissionsOriginTaiwan.json'));
        dispatch(new CommissionQueueJob($data));
    }


    private function manageActiveCountries()
    {
        $json[] = ['119' => ['PHILIPPINES'], '90' => ['NEPAL', 'THAILAND', 'PHILIPPINES', 'INDIA', 'SRI LANKA'], '91' => ['PHILIPPINES'], '13' => ['NEPAL', 'THAILAND', 'PHILIPPINES', 'INDIA', 'SRI LANKA', 'GEORGIA']];

        $redis = Redis::connection();
        $origins = [13, 90, 119, 91];
        foreach ($origins as $o) {
            if (isset($json[0][$o])) {
                $redis->lpush(redis_key('origin', $o, 'activeCountries'), $json[0][$o]);
                //$values = Redis::command('hset', ['origin:', 5, 10]);
            }
        }
    }
}
