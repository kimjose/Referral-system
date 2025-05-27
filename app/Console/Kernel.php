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
        Commands\TestMFLIntegrationCommand::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Sync MFL facilities daily at 1 AM
        $schedule->command('integrations:sync --type=mfl')
                ->dailyAt('01:00')
                ->withoutOverlapping()
                ->runInBackground();

        // Sync eCHIS referrals every 15 minutes
        $schedule->command('integrations:sync --type=echis')
                ->everyFifteenMinutes()
                ->withoutOverlapping()
                ->runInBackground();

        // Sync SHR health records every 30 minutes
        $schedule->command('integrations:sync --type=shr')
                ->everyThirtyMinutes()
                ->withoutOverlapping()
                ->runInBackground();

        // Sync HIE records every hour
        $schedule->command('integrations:sync --type=hie')
                ->hourly()
                ->withoutOverlapping()
                ->runInBackground();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
} 