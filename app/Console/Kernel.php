<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\CheckSingleVideoForm;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CheckSingleVideoForm::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        /* $schedule->command('queue:work')->everyTenMinutes();
        $schedule->command('queue:restart')->everyFifteenMinutes(); */
        $schedule->command('singlevideo:forms')->daily()->appendOutputTo(storage_path('logs/singlevideocommand.log'));
        $schedule->command('queue:work')->everyMinute()->appendOutputTo(storage_path('logs/jobs.log'));
        $schedule->command('queue:restart')->everyTwoMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
