<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staffs;


class PayslipRequest extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        // dd($user);
        if ($user) {
            $userId = $user->id;
            $employeeName = $user->name;
            $employeeID = $user->employee_id;

        }

        return view('admin.payslipRequest.index');
    }
}
