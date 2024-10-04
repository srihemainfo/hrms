<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HostelAttendanceReport extends Controller
{
    public function index(){
        return view('admin.hostel_attendance_report.index');
    }
}
