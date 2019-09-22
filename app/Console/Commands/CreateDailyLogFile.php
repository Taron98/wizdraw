<?php
/**
 * Created by PhpStorm.
 * User: Shahar
 * Date: 22/09/2019
 * Time: 16:02
 */

namespace Wizdraw\Console\Commands;

use App\Services\logs\LogsService;
use Illuminate\Console\Command;

class CreateDailyLogFile extends Command
{
    private $logService;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates daily log file with 777 permissions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(LogsService $logService)
    {
        $this->logService = $logService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->logService->createLogFilesWithPermission();
    }
}