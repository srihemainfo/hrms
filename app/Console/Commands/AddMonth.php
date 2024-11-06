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

        // $currentMonth = Carbon::now();
        // $year = $currentMonth->format('Y');
        // $month = $currentMonth->format('m');

        // $numDays = $currentMonth->daysInMonth;

        // $check = DB::table('staff_biometrics')->where('date', 'like', $year . '-' . $month . '%')->get();

        // if ($check->count() <= 0) {
        //     $teach_staffs = DB::table('staffs')->whereNull('deleted_at')->get();

        //     foreach ($teach_staffs as $value) {
        //         for ($i = 1; $i <= $numDays; $i++) {
        //             $get_day = Carbon::create($year, $month, $i);

        //             $calender = DB::table('college_calenders_preview')
        //                 ->whereNull('deleted_at')
        //                 ->where('date', $get_day)
        //                 ->whereIn('dayorder', [4, 50, 51])
        //                 ->first();

        //             $dayOfWeek = $get_day->format('l');

        //             if ($dayOfWeek == 'Sunday') {
        //                 $details = 'Sunday';
        //             } elseif ($calender) {

        //                 if ($calender->dayorder == 50) {
        //                     $details = 'Special Holiday';
        //                 } elseif ($calender->dayorder == 51) {
        //                     $details = 'Pandemic Holiday';
        //                 } else {
        //                     $details = 'Holiday';

        //                 }

        //             } else {
        //                 $details = null;
        //             }

        //             DB::table('staff_biometrics')->insert([
        //                 'date' => $get_day->format('Y-m-d'),
        //                 'day' => $dayOfWeek,
        //                 'user_name_id' => $value->user_name_id,
        //                 'employee_name' => $value->name,
        //                 'employee_code' => $value->biometric,
        //                 'staff_code' => $value->employee_id,
        //                 'shift' => $value->shift,
        //                 'details' => $details,
        //                 'worktype_id' => $value->worktype_id,
        //             ]);
        //         }
        //     }
        // }

        // \Log::info("Current Month Added For Biometric");

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

                    // Check if the day is a holiday or special day in the calendar
                    $calendar = DB::table('college_calenders_preview')
                        ->whereNull('deleted_at')
                        ->where('date', $get_day)
                        ->whereIn('dayorder', [4, 50, 51])
                        ->first();

                    $dayOfWeek = $get_day->format('l');

                    // Determine the type of day for 'details' column
                    if ($dayOfWeek == 'Sunday') {
                        $details = 'Sunday';
                    } elseif ($calendar) {
                        if ($calendar->dayorder == 50) {
                            $details = 'Special Holiday';
                        } elseif ($calendar->dayorder == 51) {
                            $details = 'Pandemic Holiday';
                        } else {
                            $details = 'Holiday';
                        }
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
                        'worktype_id' => $value->worktype_id,
                    ]);
                }
            }
        }

        \Log::info("Previous Month Added For Biometric");
    }

}
