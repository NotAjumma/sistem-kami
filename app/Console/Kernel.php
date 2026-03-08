<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('reminders:send')->everyMinute(); // for testing
        $schedule->command('reminders:send')->hourly();
        $schedule->command('sitemap:generate')->daily();

        // Health check — daily at 8 AM MYT (UTC+8 → stored as UTC 00:00)
        // Emails only when failures found; use --force to always email
        $schedule->command('health:report')->dailyAt('00:00');
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
