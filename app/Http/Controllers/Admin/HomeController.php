<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\SystemCalendarController;
use App\Models\Projects;
use Carbon\Carbon;
use App\Models\Staffs;
use App\Models\UserAlert;
use App\Models\StaffBiometric;
use Illuminate\Support\Facades\DB;

session()->start();

class HomeController extends SystemCalendarController
{
    public function index()
    {

        $role_id = auth()->user()->id;
        // dd($role_id);
        if ($role_id == 1 || $role_id == 28) {
            $todays_date =  Carbon::now()->format('Y-m-d');
            $staffsCount = Staffs::whereNull('deleted_at')->count();
            $projectCount = Projects::whereNull('deleted_at')->count();
            $staff_present = StaffBiometric::where('date' , $todays_date)
            ->where('status' , 'Present')->count();
            $staff_absent = StaffBiometric::where('date' , $todays_date)
            ->where('status' , 'Absent')->count();
            $alerts = UserAlert::where('alert_text', 'like', '%Applied%')->get();
            $alertData = $alerts->map(function ($alert) {
                return [
                    'text' => $alert->alert_text,
                    'link' => $alert->alert_link,
                ];
            });
            
            return view('home', compact('staffsCount', 'projectCount', 'staff_present', 'staff_absent', 'alertData'));
        } else {
            $userId = auth()->user()->id;
            $canEdit = DB::table('staffs')
                ->where('user_name_id', $userId)
                ->value('edit_access');
            return view('layouts.staffs', compact('canEdit'));
        }
    }

}
