<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveTrash extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:unwanted';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the deleted logs from DB';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tables = DB::select('SHOW TABLES');
        $mainTable = ['users', 'roles', 'role_user', 'permission_role', 'permissions','api_biometric','assets_histories','audit_logs','content_category_content_page','content_page_content_tag','course_user','feedback','media','migrations','password_resets','payslip','personal_access_tokens','qa_messages','qa_topics','shift','task_task_tag','tools','user_alerts','user_user_alert'];
        foreach ($tables as $value) {
            if (!in_array($value->Tables_in_rituatkalvierp_rit_3, $mainTable)) {
                $dd = DB::table($value->Tables_in_rituatkalvierp_rit_3)->whereNotNull('deleted_at')->delete();
            }
        }
        \Log::info("All Exist Deleted Logs has been Cleared");
    }
}
