<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\ActualizarSalarioMinimo::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Si querés que se ejecute automáticamente:
        // $schedule->command('salario:actualizar')->yearly();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
