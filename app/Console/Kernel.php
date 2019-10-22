<?php

namespace Wizdraw\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     * todo: create commands to generate ide-helper stuff
     *
     * @var array
     */
    protected $commands = [
        Commands\QueueTestCommand::class,
        Commands\TransactionLimitCommand::class,
        Commands\UpdateAppNotificationCommand::class,
        Commands\CommissionAndActiveCommand::class,
        Commands\UpdateVipUsers::class,
        Commands\RefreshCommand::class,
        Commands\CreateDailyLogFile::class,
        Commands\StorageSize::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('storage:size')
            ->twiceDaily(4, 16)
            ->timezone('Asia/Jerusalem');
        // withoutOverlapping

        /**Creates every day at 23:55 logs files with 777 permissions**/
        $schedule->command('create:logs')->dailyAt('21:55');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
