<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\SystemCalendarController;
use App\Models\Projects;
use Carbon\Carbon;
use App\Models\Staffs;
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
            // dd($todays_date);
            $staffsCount = Staffs::whereNull('deleted_at')->count();
            $projectCount = Projects::whereNull('deleted_at')->count();
            $staff_present = StaffBiometric::where('date' , $todays_date)
            ->where('status' , 'Present')->count();
            $staff_absent = StaffBiometric::where('date' , $todays_date)
            ->where('status' , 'Absent')->count();
            return view('home', compact('staffsCount', 'projectCount','staff_present','staff_absent'));
        } else {
            // dd('hello');
            // dd($role_id);

            $userId = auth()->user()->id;
            $canEdit = DB::table('staffs')
                ->where('user_name_id', $userId)
                ->value('edit_access');
            return view('layouts.staffs', compact('canEdit'));
        }
    }

}
