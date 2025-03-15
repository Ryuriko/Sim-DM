<?php

namespace App\Console;

use App\Jobs\PenggajianJob;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Absensi Tiap Hari
        $schedule->command('php artisan db:seed --class=AbsensiSeeder')
            ->timezone('Asia/Jakarta')
            ->dailyAt('00:01');
        
        // Summarize Penggajian Tiap Bulan
        $tahun = Carbon::now()->year;
        $bulan = Carbon::now()->locale('id')->translatedFormat('F');
        $schedule->job(new PenggajianJob($tahun, $bulan))
            ->monthlyOn(25, '19:00')
            ->timezone('Asia/Jakarta');
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
