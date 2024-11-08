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

        // $today = Carbon::now();
        // $year = $today->format('Y');
        // $month = $today->format('m');

        // $numDays = $today->daysInMonth;

        // $check = DB::table('staff_biometrics')->where('date', 'like', $year . '-' . $month . '%')->get();

        // if ($check->count() <= 0) {
        //     $teach_staffs = DB::table('staffs')->whereNull('deleted_at')->get();

        //     foreach ($teach_staffs as $value) {
        //         for ($i = 1; $i <= $numDays; $i++) {
        //             $get_day = Carbon::create($year, $month, $i);

        //             $calendar = DB::table('college_calenders_preview')
        //                 ->whereNull('deleted_at')
        //                 ->where('date', $get_day)
        //                 ->whereIn('dayorder', [4, 5, 50, 51])
        //                 ->first();

        //             $dayOfWeek = $get_day->format('l');

        //             if ($calendar) {
        //                 if ($calendar->dayorder == 50) {
        //                     $details = 'Special Holiday';
        //                 } elseif ($calendar->dayorder == 5) {
        //                     $details = 'Week Off';
        //                 } elseif ($calendar->dayorder == 51) {
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

        // $previousMonth = Carbon::now()->subMonth();
        // $year = $previousMonth->format('Y');
        // $month = $previousMonth->format('m');

        // $numDays = $previousMonth->daysInMonth;

        // $check = DB::table('staff_biometrics')->where('date', 'like', $year . '-' . $month . '%')->get();

        // if ($check->count() <= 0) {
        //     $teach_staffs = DB::table('staffs')->whereNull('deleted_at')->get();

        //     foreach ($teach_staffs as $value) {
        //         for ($i = 1; $i <= $numDays; $i++) {
        //             $get_day = Carbon::create($year, $month, $i);

        //             $calendar = DB::table('college_calenders_preview')
        //                 ->whereNull('deleted_at')
        //                 ->where('date', $get_day)
        //                 ->whereIn('dayorder', [4,5,50, 51])
        //                 ->first();

        //             $dayOfWeek = $get_day->format('l');

        //             if ($calendar) {
        //                 if ($calendar->dayorder == 50) {
        //                     $details = 'Special Holiday';
        //                 }
        //                 elseif ($calendar->dayorder == 5) {
        //                     $details = 'Week Off';
        //                 }

        //                 elseif ($calendar->dayorder == 51) {
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

        // \Log::info("Previous Month Added For Biometric");

        $nextMonth = Carbon::now()->addMonth();
        $year = $nextMonth->format('Y');
        $month = $nextMonth->format('m');

        $numDays = $nextMonth->daysInMonth;

        $check = DB::table('staff_biometrics')->where('date', 'like', $year . '-' . $month . '%')->get();

        if ($check->count() <= 0) {
            $teach_staffs = DB::table('staffs')->whereNull('deleted_at')->get();

            foreach ($teach_staffs as $value) {
                for ($i = 1; $i <= $numDays; $i++) {
                    $get_day = Carbon::create($year, $month, $i);

                    $calendar = DB::table('college_calenders_preview')
                        ->whereNull('deleted_at')
                        ->where('date', $get_day)
                        ->whereIn('dayorder', [4, 5, 50, 51])
                        ->first();

                    $dayOfWeek = $get_day->format('l');

                    if ($calendar) {
                        if ($calendar->dayorder == 50) {
                            $details = 'Special Holiday';
                        } elseif ($calendar->dayorder == 5) {
                            $details = 'Week Off';
                        } elseif ($calendar->dayorder == 51) {
                            $details = 'Pandemic Holiday';
                        } else {
                            $details = 'Holiday';
                        }
                    } else {
                        $details = null;
                    }

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

        \Log::info("Next Month Added For Biometric");

    }

}
