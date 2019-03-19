<?php

namespace Wizdraw\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RefreshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ran all commands with one command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        shell_exec('sudo service supervisor restart');
        shell_exec('sudo chmod -R 777 ../../../bootstrap/cache/');
        shell_exec('sudo chmod -R 777 ../../../storage/');
        shell_exec('sudo chmod -R 777 ../../../public/');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('wiz:cache:test');
        Artisan::call('wiz:cache:commission');
        Artisan::call('wiz:cache:transaction:limit');
    }
}
