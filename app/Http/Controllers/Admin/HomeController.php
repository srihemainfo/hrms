<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\SystemCalendarController;
use Illuminate\Support\Facades\DB;

session()->start();

class HomeController extends SystemCalendarController
{
    public function index()
    {
        $role_id = auth()->user()->id;
        if ($role_id != 2) {
            return view('layouts.admin');
        } else {
            $userId = auth()->user()->id;
            $canEdit = DB::table('staffs')
                ->where('user_name_id', $userId)
                ->value('edit_access');
            return view('layouts.staffs', compact('canEdit'));
        }

    }

}
