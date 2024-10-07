<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NonTeachingStaff;
use App\Models\PersonalDetail;
use App\Models\Staffs;
use App\Models\ToolsDepartment;
use Illuminate\Http\Request;

// staffRelievingreport

class StaffRelievingreport extends Controller
{
    public function index()
    {
        // $datas = [];
        $datas = PersonalDetail::where('employment_status', 'Relieving')->get();
            foreach ($datas as $data) {
                $user_id = $data->user_name_id;
                $data->DOJ = $data->DOJ ? date('d-m-Y', strtotime($data->DOJ)) : '';
                $data->DOR = $data->DOR ? date('d-m-Y', strtotime($data->DOR)) : '';

                $data->user_id_staff_code = $data->name . '(' . $data->employee_id . ')';
                $staff = Staffs::where('user_name_id', $user_id)->first();
            }
        return view('admin.staffRelievingreport.index', compact('datas'));
    }

    public function search(Request $request)
    {

        if ($request) {
            $status = $request->status;
            $data = PersonalDetail::where('employment_status', $status)->get();
            foreach ($data as $datas) {
                $user_id = $datas->user_name_id;
                $datas->DOJ = $datas->DOJ ? date('d-m-Y', strtotime($datas->DOJ)) : '';
                $datas->DOR = $datas->DOR ? date('d-m-Y', strtotime($datas->DOR)) : '';

                $datas->user_id_staff_code = $datas->name . '(' . $datas->employee_id . ')';
                $staff = Staffs::where('user_name_id', $user_id)->first();
            }

            return response()->json(['datas' => $data]);
        }

    }
}
