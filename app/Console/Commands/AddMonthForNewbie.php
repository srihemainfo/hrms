<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddMonthForNewbie extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addMonthForNewbie:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adding Month In Staff Biometric For Newbie';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $date = Carbon::now()->format('Y-m-d');

        $year = Carbon::now()->format('Y');

        $month = Carbon::now()->format('m');

        $numDays = Carbon::createFromDate($year, $month, 1)->daysInMonth;

        $check_1 = DB::table('teaching_staffs')->whereNull('deleted_at')->select('user_name_id','name','BiometricID','StaffCode')->where('created_at', 'like', "{$date}%")->get();
        $check_2 = DB::table('non_teaching_staffs')->whereNull('deleted_at')->select('user_name_id','name','BiometricID','StaffCode','Dept')->where('created_at', 'like', "{$date}%")->get();

        if ($check_1->count() > 0) {

            foreach ($check_1 as $staff) {
                $check_bio = DB::table('staff_biometrics')->where('date', 'like', $year . '-' . $month . '%')->where(['user_name_id' => $staff->user_name_id,'staff_code' => $staff->StaffCode])->get();

                $count = $numDays;

                if (count($check_bio) <= 0) {

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
                            'user_name_id' => $staff->user_name_id,
                            'employee_name' => $staff->name,
                            'employee_code' => $staff->BiometricID,
                            'staff_code' => $staff->StaffCode,
                            'shift' => 1,
                            'details' => $details,
                        ]);
                    }
                }

            }
        }

        if ($check_2->count() > 0) {

            foreach ($check_2 as $staff) {
                $check_bio = DB::table('staff_biometrics')->where('date', 'like', $year . '-' . $month . '%')->where(['user_name_id' => $staff->user_name_id,'staff_code' => $staff->StaffCode])->get();

                $count = $numDays;

                if (count($check_bio) <= 0) {

                    for ($i = 01; $i <= $count; $i++) {

                        $get_day = \Carbon\Carbon::parse($year . '-' . $month . '-' . $i);

                        $dayOfWeek = $get_day->format('l');

                        if ($dayOfWeek == 'Sunday') {
                            $details = 'Sunday';
                        } else {
                            $details = null;
                        }

                        if($staff->Dept != 'CIVIL' && $staff->Dept != 'ADMIN'){
                            $shift = 1;
                        }else{
                            $shift = 2;
                        }

                        DB::table('staff_biometrics')->insert([
                            'date' => $year . '-' . $month . '-' . $i,
                            'day' => $dayOfWeek,
                            'user_name_id' => $staff->user_name_id,
                            'employee_name' => $staff->name,
                            'employee_code' => $staff->BiometricID,
                            'staff_code' => $staff->StaffCode,
                            'shift' => $shift,
                            'details' => $details,
                        ]);
                    }
                }

            }
        }

        // \Log::info("Month Added In Biometric For Newbie");
    }
}
