<?php

namespace App\Console;
use App\Console\Commands\CheckPlanExpiry;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Schedule your command here
        $schedule->command('plans:check-expiry')->daily();
//        $schedule->command('plans:check-expiry')->everyMinute();
//        $schedule->command('plans:check-expiry')->cron('* * * * * *');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        $this->commands([
            \App\Console\Commands\CheckPlanExpiry::class,
        ]);

        require base_path('routes/console.php');
    }
}
