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

        $year = Carbon::now()->format('Y');

        $month = Carbon::now()->format('m');

        $numDays = Carbon::createFromDate($year, $month, 1)->daysInMonth;

        $check = DB::table('staff_biometrics')->where('date', 'like', $year . '-' . $month . '%')->get();
        // dd($check);
        if ($check->count() <= 0) {

            $teach_staffs = DB::table('staffs')->whereNull('deleted_at')->get();

            $count = $numDays;

            foreach ($teach_staffs as $value) {

                for ($i = 01; $i <= $count; $i++) {
                    $get_day = \Carbon\Carbon::parse($year . '-' . $month . '-' . $i);
                    $calender = DB::table('college_calenders_preview')->WhereNull('deleted_at')->where(['date' => $get_day, 'dayorder' => 4])->exists();
                    $dayOfWeek = $get_day->format('l');

                    if ($dayOfWeek == 'Sunday') {
                        $details = 'Sunday';
                    } elseif ($calender) {
                        $details = 'Holiday';
                    }else{
                        $details = null;
                    }

                    DB::table('staff_biometrics')->insert([
                        'date' => $year . '-' . $month . '-' . $i,
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

        \Log::info("Month Added For Biometric");
    }

}
