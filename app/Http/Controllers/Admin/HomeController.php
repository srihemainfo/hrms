<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\SystemCalendarController;
session()->start();
class HomeController extends SystemCalendarController
{
    public function index()
    {
        $role_id = auth()->user()->roles[0]->id;
        // dd($role_id);

        if ($role_id != 4) {
            return view('layouts.admin');
        }elseif($role_id == 4){
            return view('layouts.staffs');
        }

    }

}
