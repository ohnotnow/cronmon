<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CheckJobs::class,
        Commands\CreateAdmin::class,
        Commands\AutoCreateAdmin::class,
        Commands\TruncatePings::class,
        Commands\SilenceAlerts::class,
        Commands\UnsilenceAlerts::class,
        Commands\CronmonDiscover::class,
        Commands\ReformatRoutes::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cronmon:checkjobs')->everyFiveMinutes()->withoutOverlapping();
        $schedule->command('cronmon:truncatepings')->weekly();
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
