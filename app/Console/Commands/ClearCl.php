<?php

namespace App\Console\Commands;

use App\Models\NonTeachingStaff;
use App\Models\TeachingStaff;
use Illuminate\Console\Command;

class ClearCl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clearcl:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'All Exist Casual Leave Cleared';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $get_staff = TeachingStaff::select('user_name_id')->get();

        if ($get_staff->count() > 0) {

            foreach ($get_staff as $staff) {

               TeachingStaff::where(['user_name_id' => $staff->user_name_id])->update([
                    'casual_leave' => 0,
                    'past_casual_leave' => 0,
                ]);
            }

        }

        $get_non_tech_staff = NonTeachingStaff::select('user_name_id')->get();

        if ($get_non_tech_staff->count() > 0) {

            foreach ($get_non_tech_staff as $staff) {

                 NonTeachingStaff::where(['user_name_id' => $staff->user_name_id])->update([
                    'casual_leave' => 0,
                    'past_casual_leave' => 0,
                ]);

            }
        }
        \Log::info("All Exist Casual Leave Cleared");
    }
}
