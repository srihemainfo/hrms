<?php

namespace App\Console\Commands;

use App\Models\NonTeachingStaff;
use App\Models\TeachingStaff;
use Illuminate\Console\Command;

class AddCl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addcl:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adding Casual Leave / Personal Permission for all staff';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $check_staff = TeachingStaff::select('casual_leave', 'personal_permission', 'user_name_id')->get();

        if ($check_staff->count() > 0) {
            foreach ($check_staff as $staff) {
                $cl = $staff->casual_leave;
                $past_personal_permission = $staff->personal_permission;

                $store_staff = TeachingStaff::where(['user_name_id' => $staff->user_name_id])->update([
                    'casual_leave' => $cl + 1,
                    'past_casual_leave' => $cl,
                    'personal_permission' => 2,
                    'past_personal_permission' => $past_personal_permission,
                ]);
            }
        }

        $check_non_tech_staff = NonTeachingStaff::select('casual_leave', 'personal_permission','user_name_id')->get();

        if ($check_non_tech_staff->count() > 0) {

            foreach ($check_non_tech_staff as $staff) {
                $cl = $staff->casual_leave;
                $past_personal_permission = $staff->personal_permission;

                $store_non_staff = NonTeachingStaff::where(['user_name_id' => $staff->user_name_id])->update([
                    'casual_leave' => $cl + 1,
                    'past_casual_leave' => $cl,
                    'personal_permission' => 2,
                    'past_personal_permission' => $past_personal_permission,
                ]);
            }
        }

        \Log::info("Casual Leave Added");
    }
}
