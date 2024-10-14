<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\SystemCalendarController;
use App\Models\Projects;
use App\Models\Staffs;
use Illuminate\Support\Facades\DB;

session()->start();

class HomeController extends SystemCalendarController
{
    public function index()
    {
        $role_id = auth()->user()->id;
        // dd($role_id);
        if ($role_id != 2) {
            // dd('hii');
            $staffsCount = Staffs::whereNull('deleted_at')->count();
            $projectCount = Projects::whereNull('deleted_at')->count();

            return view('home', compact('staffsCount', 'projectCount'));
        } else {
            // dd('hello');

            $userId = auth()->user()->id;
            $canEdit = DB::table('staffs')
                ->where('user_name_id', $userId)
                ->value('edit_access');
            return view('layouts.staffs', compact('canEdit'));
        }
    }

}
