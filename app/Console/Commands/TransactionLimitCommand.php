<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 8/30/2017
 * Time: 17:20
 */

namespace Wizdraw\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class TransactionLimitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wiz:cache:transaction:limit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache transactions limit by client country';

    /**
     * Execute the console command
     *
     */
    public function handle()
    {
        $this->setTransActionLimit();
    }

    /**
     * set sending amount limit on cache
     * for ex.
     * sender from israel can send 20000 ILS per transaction
     * sender from hong kong can send 8000 HKD per transaction
     */
    public function setTransactionLimit()
    {

        $limits = [13 => 20000, 90 => [ 'circkle-k' => 2300, 'seven-eleven' => 4500 ] , 119 => 30000];
        $redis = Redis::connection();
        foreach ($limits as $k => $v){
            $redis->lpush(redis_key('origin', $k, 'amountLimits'), $v);
        }
    }
}