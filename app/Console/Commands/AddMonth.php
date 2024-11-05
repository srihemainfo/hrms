<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addmonth:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adding Month for Staff Biometric';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get the previous month and year
        $previousMonth = Carbon::now()->subMonth();
        $year = $previousMonth->format('Y');
        $month = $previousMonth->format('m');

        // Get the number of days in the previous month
        $numDays = $previousMonth->daysInMonth;

        // Check if there are already records for the previous month in 'staff_biometrics'
        $check = DB::table('staff_biometrics')->where('date', 'like', $year . '-' . $month . '%')->get();

        if ($check->count() <= 0) {
            $teach_staffs = DB::table('staffs')->whereNull('deleted_at')->get();

            foreach ($teach_staffs as $value) {
                for ($i = 1; $i <= $numDays; $i++) {
                    $get_day = Carbon::create($year, $month, $i);
                    $calender = DB::table('college_calenders_preview')
                        ->whereNull('deleted_at')
                        ->where(['date' => $get_day, 'dayorder' => 4])
                        ->exists();
                    $dayOfWeek = $get_day->format('l');

                    // Determine if the day is Sunday or a holiday
                    if ($dayOfWeek == 'Sunday') {
                        $details = 'Sunday';
                    } elseif ($calender) {
                        $details = 'Holiday';
                    } else {
                        $details = null;
                    }

                    // Insert the record into 'staff_biometrics'
                    DB::table('staff_biometrics')->insert([
                        'date' => $get_day->format('Y-m-d'),
                        'day' => $dayOfWeek,
                        'user_name_id' => $value->user_name_id,
                        'employee_name' => $value->name,
                        'employee_code' => $value->biometric,
                        'staff_code' => $value->employee_id,
                        'shift' => $value->shift,
                        'details' => $details,
                    ]);
                }
            }
        }

        \Log::info("Previous Month Added For Biometric");
    }

}
