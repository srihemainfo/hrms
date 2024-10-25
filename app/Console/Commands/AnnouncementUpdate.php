<?php

namespace App\Console\Commands;

use App\Models\Announcement;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AnnouncementUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'announcemnetupdate:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Status Based on End Date';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::now()->format('Y-m-d');

        Announcement::where('end_date', $today)
            ->where('status', 0)
            ->update([
                'status' => '1',
            ]);

        \Log::info("Announcement Status Updated");

    }

}
