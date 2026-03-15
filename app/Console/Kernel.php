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
        $schedule->command('reminders:send')->everyFifteenMinutes()->onOneServer();
        $schedule->command('sitemap:generate')->daily()->onOneServer();

        // Health check — daily at 8 AM MYT (server is UTC+8)
        $schedule->command('health:report')->dailyAt('08:00')->onOneServer();

        // Daily booking report — 11 PM MYT (server is UTC+8)
        $schedule->command('bookings:daily-report')->dailyAt('23:00')->onOneServer();
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
