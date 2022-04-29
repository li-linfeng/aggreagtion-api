<?php

namespace App\Console;

use App\Console\Commands\FindUploadResources;
use App\Console\Commands\UpdateArticles;
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
        UpdateArticles::class,
        FindUploadResources::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $date_format = date('Ymd');
        $schedule->command('command:update_articles')->daily();
        // $schedule->command('resource:findNewResource')->everyMinute();
        $schedule->call(function () {
            app('log')->info('执行计划任务');
        })->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
