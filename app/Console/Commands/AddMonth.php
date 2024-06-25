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

        $month = \Carbon\Carbon::now()->format('m');

        $numDays = Carbon::createFromDate($year, $month, 1)->daysInMonth;

        $check = DB::table('staff_biometrics')->where('date', 'like', $year . '-' . $month . '%')->get();

        if ($check->count() <= 0) {

            $teach_staffs = DB::table('teaching_staffs')->whereNull('deleted_at')->select('user_name_id','name','BiometricID','StaffCode')->get();

            $count = $numDays;

            foreach ($teach_staffs as $value) {

                for ($i = 01; $i <= $count; $i++) {

                    $get_day = \Carbon\Carbon::parse($year . '-' . $month . '-' . $i);

                    $dayOfWeek = $get_day->format('l');

                    if ($dayOfWeek == 'Sunday') {
                        $details = 'Sunday';
                    } else {
                        $details = null;
                    }

                    DB::table('staff_biometrics')->insert([
                        'date' => $year . '-' . $month . '-' . $i,
                        'day' => $dayOfWeek,
                        'user_name_id' => $value->user_name_id,
                        'employee_name' => $value->name,
                        'employee_code' => $value->BiometricID,
                        'staff_code' => $value->StaffCode,
                        'shift' => 1,
                        'details' => $details,
                    ]);
                }
            }
            $non_teach_staffs = DB::table('non_teaching_staffs')->whereNull('deleted_at')->select('user_name_id','name','BiometricID','StaffCode','Dept')->get();

            foreach ($non_teach_staffs as $value) {

                for ($i = 01; $i <= $count; $i++) {

                    $get_day = \Carbon\Carbon::parse($year . '-' . $month . '-' . $i);

                    $dayOfWeek = $get_day->format('l');

                    if ($dayOfWeek == 'Sunday') {
                        $details = 'Sunday';
                    } else {
                        $details = null;
                    }
                    if($value->Dept != 'CIVIL' && $value->Dept != 'ADMIN'){
                        $shift = 1;
                    }else{
                        $shift = 2;
                    }

                    DB::table('staff_biometrics')->insert([
                        'date' => $year . '-' . $month . '-' . $i,
                        'day' => $dayOfWeek,
                        'user_name_id' => $value->user_name_id,
                        'employee_name' => $value->name,
                        'employee_code' => $value->BiometricID,
                        'staff_code' => $value->StaffCode,
                        'shift' => $shift,
                        'details' => $details,
                    ]);
                }
            }
        }

        \Log::info("Month Added For Biometric");
    }

}
