<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NonTeachingStaff;
use App\Models\PermissionRequest;
use App\Models\StaffBiometric;
use App\Models\Staffs;
use App\Models\TeachingStaff;
use App\Models\ToolsDepartment;
use Illuminate\Http\Request;

class permissionRegister extends Controller
{
    public function index(Request $request)
    {

        if ($request) {

            $staff = StaffBiometric::distinct('staff_code')->pluck('employee_name', 'staff_code');
            // $department = ToolsDepartment::pluck('name', 'id');
        }
        return view('admin.permissionRegister.index', compact('staff'));

    }

    public function search(Request $request)
    {

        if ($request) {

            $startDate = $request->fromtime;
            $endDate = $request->totime;
            // $dept = $request->Dept;

            if (auth()->user()->roles[0]->id != 42) {
                if ($startDate != null && $endDate != null) {
                    $results = PermissionRequest::whereBetween('date', [$startDate, $endDate])
                        // ->where('dept', '=', $dept)
                        ->where(['status' => 2])
                        ->get();
                } else {
                    $results = PermissionRequest::where(['status' => 2])->get();
                }

                foreach ($results as $data) {
                    $date = explode('-', $data->date);
                    $final_date = $date[2] . '-' . $date[1] . '-' . $date[0];
                    $data->date = $final_date;

                    $get_staff = Staffs::where(['user_name_id' => $data->user_name_id])->select('employee_id')->first();
                    // if ($get_staff == '') {
                    //     $get_staff = NonTeachingStaff::where(['user_name_id' => $data->user_name_id])->select('StaffCode')->first();
                    // }
                    $data->staff_code = $get_staff->StaffCode;
                }

            } else {
                if ($startDate != null && $endDate != null) {

                    $results = PermissionRequest::whereBetween('date', [$startDate, $endDate])
                        ->join('teaching_staffs', 'permissionrequests.user_name_id', '=', 'teaching_staffs.user_name_id')
                        ->where('teaching_staffs.rd_staff', '1')
                        ->where(['status' => 2])
                        ->get();
                } else {
                    $results = PermissionRequest::join('teaching_staffs', 'permissionrequests.user_name_id', '=', 'teaching_staffs.user_name_id')
                    ->where('teaching_staffs.rd_staff', '1')
                    ->where(['status' => 2])
                    ->get();
                }

                foreach ($results as $data) {
                    $date = explode('-', $data->date);
                    $final_date = $date[2] . '-' . $date[1] . '-' . $date[0];
                    $data->date = $final_date;

                    $get_staff = TeachingStaff::where(['user_name_id' => $data->user_name_id, 'rd_staff' => '1'])->select('StaffCode')->first();
                    if ($get_staff == '') {
                        $get_staff = NonTeachingStaff::where(['user_name_id' => $data->user_name_id])->select('StaffCode')->first();
                    }
                    $data->staff_code = $get_staff->StaffCode;
                }
            }
            return response()->json($results);
        }
    }
}
