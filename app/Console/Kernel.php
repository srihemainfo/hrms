<?php

namespace App\Console;


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
    protected $command = [
        Commands\AddCl::class,
        Commands\AddMonth::class,
        Commands\ClearCl::class,
        Commands\SalaryStmtGeneration::class,
        Commands\StaffAltRew::class,
        Commands\AddMonthForNewbie::class,
        Commands\FutureLeaveCalculation::class,
    ];
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('addcl:log')->everyMinute();
        // $schedule->command('addcl:log')->monthly();
        // $schedule->command('salarystatement:log')->monthly();
        // $schedule->command('addmonth:log')->monthly();
        // $schedule->command('clearcl:log')->yearly();
        // $schedule->command('staffAltRew:log')->daily();
        // $schedule->command('addMonthForNewbie:log')->daily();


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
