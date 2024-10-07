<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HrmRequestLeaf;
use App\Models\LeaveType;
use App\Models\StaffBiometric;
use App\Models\Staffs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class staff_leave_register extends Controller
{
    public function index(Request $request)
    {

        abort_if(Gate::denies('staff_leave_report_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request) {
            $staff = StaffBiometric::distinct('staff_code')->pluck('employee_name', 'staff_code');

        }

        return view('admin.leave_Register.index', compact('staff'));

    }

    public function index_rep(Request $request)
    {
        $userId = Staffs::where('employee_id', $request['staff_code'])
            ->first();

        if ($userId) {
            $requests = HrmRequestLeaf::where([
                ['user_id', $userId->user_name_id],
                ['status', 'Approved'],
                ['level', 2],
            ])->whereBetween('from_date', [$request['start_date'], $request['end_date']])
                ->get();

            foreach ($requests as $requested) {
                $requested->name = $userId->name;
                $requested->StaffCode = $userId->employee_id;

                if ($requested->from_date != null) {
                    $from_date = explode('-', $requested->from_date);
                    $final_from_date = $from_date[2] . '-' . $from_date[1] . '-' . $from_date[0];
                    $requested->from_date = $final_from_date;
                }
                if ($requested->to_date != null) {
                    $to_date = explode('-', $requested->to_date);
                    $final_to_date = $to_date[2] . '-' . $to_date[1] . '-' . $to_date[0];
                    $requested->to_date = $final_to_date;
                }
                if ($requested->off_date != null) {
                    $off_date = explode('-', $requested->off_date);
                    $final_off_date = $off_date[2] . '-' . $off_date[1] . '-' . $off_date[0];
                    $requested->off_date = $final_off_date;
                }
                if ($requested->alter_date != null) {
                    $alter_date = explode('-', $requested->alter_date);
                    $final_alter_date = $alter_date[2] . '-' . $alter_date[1] . '-' . $alter_date[0];
                    $requested->alter_date = $final_alter_date;
                }

                if ($requested->leave_type) {
                    $leaveType = LeaveType::find($requested->leave_type);
                    $requested->leave_type = $leaveType ? $leaveType->name : null;
                }
            }
            return response()->json($requests);
        }
    }
}
