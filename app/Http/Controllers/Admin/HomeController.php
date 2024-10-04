<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\SystemCalendarController;
use App\Models\ClassTimeTableTwo;
use App\Models\CollegeCalender;
use App\Models\CourseEnrollMaster;
use App\Models\Document;
use App\Models\HrmRequestLeaf;
use App\Models\NonTeachingStaff;

session()->start();
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeachingStaff;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\DB;

class HomeController extends SystemCalendarController
{
    public function index()
    {

        $user_id = auth()->user()->id;
        // dd($user_id);

        if ($user_id == 1) {

            return view('layouts.admin');
        }

    }

}
