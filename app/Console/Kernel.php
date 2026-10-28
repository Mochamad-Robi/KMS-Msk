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
        // Berita ulang tahun + notifikasi in-app ke seluruh karyawan lain
        $schedule->command('birthday:generate')
                 ->dailyAt('00:01')
                 ->timezone('Asia/Jakarta')
                 ->withoutOverlapping();

        // Ucapan untuk karyawan yang berulang tahun itu sendiri
        $schedule->command('notifications:birthday')
                 ->dailyAt('00:02')
                 ->timezone('Asia/Jakarta')
                 ->withoutOverlapping();

        // Push notification ke HP, sengaja ditunda sampai pagi
        $schedule->command('birthday:push')
                 ->dailyAt('07:00')
                 ->timezone('Asia/Jakarta')
                 ->withoutOverlapping();
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