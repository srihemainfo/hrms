<?php

namespace App\Console\Commands;

use App\Models\ClassTimeTableTwo;
use App\Models\StaffAlterationRegister;
use Carbon\Carbon;
use Illuminate\Console\Command;

class StaffAltRew extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'staff_alt_rew:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Staff Alteration Return Back In Class Time Table';

    public function handle()
    {
        // $yesterday = Carbon::today()->subDay()->toDateString();

        // $results = StaffAlterationRegister::whereDate('to_date', $yesterday)->get();

        // if ($results) {

        //     foreach ($results as $result) {

        //         $new_id = $result->staff_id;
        //         $users_id = $result->alter_staffid;
        //         $class_name = $result->class_name;
        //         $day = $result->day;
        //         $period = $result->period;

        //         if ($users_id != '' && $class_name != '' && $day != '' && $period != '') {
        //             $from_date = $result->from_date;
        //             $to_date = $result->to_date;

        //             $updateResult = ClassTimeTableTwo::where('class_name', $class_name)
        //                 ->where('day', $day)
        //                 ->where('period', $period)
        //                 ->update(['staff' => $new_id]);

        //             $updateAlteration = StaffAlterationRegister::where(['id' => $result->id])->update([
        //                 'dummy' => 'Alteration Reversed',
        //             ]);

        //         }
        //     }
        // }

        // \Log::info("All Alteration's Cleared");

    }
}
