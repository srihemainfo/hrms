<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HrmRequestLeaf;
use App\Models\LeaveType;
use App\Models\NonTeachingStaff;
use App\Models\PermissionRequest;
use App\Models\StaffBiometric;
use App\Models\TeachingStaff;
use App\Models\ToolsDepartment;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffAttendanceRegisterController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('staff_attendance_register_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $staff = [];
        if ($request) {
            // dd($request);
            $role_id = auth()->user()->roles[0]->id;
            $non_tech_staff = NonTeachingStaff::groupBy('name', 'user_name_id', 'StaffCode')->select('name', 'user_name_id', 'StaffCode')->get();
            $tech_staff = TeachingStaff::groupBy('name', 'user_name_id', 'StaffCode')->select('name', 'user_name_id', 'StaffCode')->get();
            if ($role_id == 14) {
                $dept = auth()->user()->dept;
                if ($dept != null) {
                    $tech_staff = TeachingStaff::where('Dept', $dept)->groupBy('name', 'user_name_id', 'StaffCode')->select('name', 'user_name_id', 'StaffCode')->get();
                    $non_tech_staff = [];
                }
            }

            if (count($tech_staff) > 0) {
                foreach ($tech_staff as $data) {
                    array_push($staff, ['user_name_id' => $data->user_name_id, 'name' => $data->name . '  (' . $data->StaffCode . ')']);
                }
            }

            if (count($tech_staff) > 0) {
                foreach ($non_tech_staff as $data) {
                    array_push($staff, ['user_name_id' => $data->user_name_id, 'name' => $data->name . '  (' . $data->StaffCode . ')']);
                }
            }

        }
        return view('admin.staffAttendance.staffAttendanceRegister', compact('staff'));
    }

    public function search(Request $request)
    {
        // abort_if(Gate::denies('employee_salary_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request) {
            $attend_rep = '';
            $shift = '';
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $user = $request->user;

            $staff = TeachingStaff::where('user_name_id', $user)->first();

            if ($staff == '') {
                $staff = NonTeachingStaff::where('user_name_id', $user)->first();
            }

            if ($staff != '') {
                $day_array = [];
                $matching = [];

                $query = StaffBiometric::where('user_name_id', $staff->user_name_id)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->get();

                if ($query->count() > 0) {
                    $attend_rep = $query;

                    for ($a = 0; $a < count($attend_rep); $a++) {
                        if ($attend_rep[$a]->shift != '' || $attend_rep[$a]->shift != null) {
                            $shift = $attend_rep[$a]->shift;
                            break;
                        } else {
                            $shift = '';
                        }
                        // dd($attend_rep[$a]);
                        $date = explode('-', $attend_rep[$a]->date);
                        $final_date = $date[2] . '-' . $date[1] . '-' . $date[0];
                        $attend_rep[$a]->date = $final_date;
                    }
                    foreach ($attend_rep as $data) {
                        $date = explode('-', $data->date);
                        $final_date = $date[2] . '-' . $date[1] . '-' . $date[0];
                        $data->date = $final_date;
                    }
                } else {
                    $attend_rep = '';
                    $shift = '';
                }
            }

            $final_array = [];

            if ($attend_rep != '') {
                for ($i = 0; $i < count($attend_rep); $i++) {
                    if ($attend_rep[$i]['in_time'] == null) {
                        $attend_rep[$i]['in_time'] = '-';
                    }
                    if ($attend_rep[$i]['out_time'] == null) {
                        $attend_rep[$i]['out_time'] = '-';
                    }
                    if ($attend_rep[$i]['total_hours'] == null || $attend_rep[$i]['total_hours'] == '00:00:00') {
                        $attend_rep[$i]['total_hours'] = '-';
                    }

                    $one = new StaffBiometric;
                    $one->SNo = $i + 1;
                    $one->date = $attend_rep[$i]['date'];
                    if ($attend_rep[$i]['day'] == 'Sunday') {
                        $one->day = "<span style=\"color:red;\"> Sunday </span>";
                    } else {
                        $one->day = $attend_rep[$i]['day'];
                    }
                    $one->permission = $attend_rep[$i]['permission'];
                    $one->day_punches = $attend_rep[$i]['day_punches'];
                    $one->in_time = $attend_rep[$i]['in_time'];
                    $one->out_time = $attend_rep[$i]['out_time'];
                    $one->total_hours = $attend_rep[$i]['total_hours'];
                    $one->status = $attend_rep[$i]['status'];
                    if ($attend_rep[$i]['details'] == 'Late') {
                        $one->details = "<span style=\"color:red;\"> Late </span>";
                    } else if ($attend_rep[$i]['details'] == 'Too Late') {
                        $one->details = "<span style=\"color:red;\">Too Late </span>";
                    } else if ($attend_rep[$i]['details'] == 'Casual Leave') {
                        $one->details = "<span style=\"color:red;\">Casual Leave (NA)</span>";
                    } else if ($attend_rep[$i]['details'] == 'After Noon Casual Leave') {
                        $one->details = "<span style=\"color:red;\">After Noon Casual Leave (NA)</span>";
                    } else if ($attend_rep[$i]['details'] == 'Fore Noon Casual Leave') {
                        $one->details = "<span style=\"color:red;\">Fore Noon Casual Leave (NA)</span>";
                    } else {
                        $one->details = $attend_rep[$i]['details'];
                    }
                    array_push($final_array, $one);
                }
            } else {
                return response()->json(['data' => [], 'shift' => $shift, 'staff' => $staff]);
            }
            return response()->json(['data' => $final_array, 'shift' => $shift, 'staff' => $staff]);
        } else {
            return response()->json(['error' => true]);
        }
    }

    public function attendanceIndex(Request $request)
    {
        $role_id = auth()->user()->roles[0]->id;
        $department = ToolsDepartment::select('id', 'name')->get();
        if ($role_id == 14) {
            $dept = auth()->user()->dept;
            if ($dept != null) {
                $department = ToolsDepartment::where(['name' => $dept])->select('id', 'name')->get();
            }
        }
        return view('admin.staffAttendance.staffDailyAttendance', compact('department'));
    }

    public function attendanceData(Request $request)
    {

        if (isset($request->department) && isset($request->date)) {

            $department = $request->department;
            $date = $request->date;

            $getDept = ToolsDepartment::where(['id' => $department])->select('name')->first();
            if ($getDept != '') {
                $getStaff = TeachingStaff::join('personal_details', 'teaching_staffs.user_name_id', '=', 'personal_details.user_name_id')->where('teaching_staffs.Dept', $getDept->name)->where(function ($query) {
                    $query->where('personal_details.employment_status', '!=', 'Relieving')
                        ->orWhereNull('personal_details.employment_status');
                })->select('teaching_staffs.name', 'teaching_staffs.StaffCode', 'teaching_staffs.user_name_id', 'personal_details.employment_status')->get();

                if (count($getStaff) <= 0) {
                    $getStaff = NonTeachingStaff::join('personal_details', 'non_teaching_staffs.user_name_id', '=', 'personal_details.user_name_id')->where('non_teaching_staffs.Dept', $getDept->name)->where(function ($query) {
                        $query->where('personal_details.employment_status', '!=', 'Relieving')
                            ->orWhereNull('personal_details.employment_status');
                    })->select('non_teaching_staffs.name', 'non_teaching_staffs.StaffCode', 'non_teaching_staffs.user_name_id', 'personal_details.employment_status')->get();
                }
                $the_department = $getDept->name;
            } else {
                $getStaff = [];
                $the_department = '';
            }
            if (count($getStaff) > 0) {
                foreach ($getStaff as $staff) {
                    $leaveType = null;
                    $permission = null;
                    $status = null;
                    $getLeave = HrmRequestLeaf::where(['user_id' => $staff->user_name_id])->where('status', '!=', 'Rejected')->whereDate('from_date', '>=', $date)->whereDate('to_date', '<=', $date)->select('leave_type')->first();
                    if ($getLeave == '') {
                        $getLeave = HrmRequestLeaf::where(['user_id' => $staff->user_name_id, 'off_date' => $date])->where('status', '!=', 'Rejected')->select('leave_type')->first();
                        if ($getLeave == '') {
                            $getLeave = HrmRequestLeaf::where(['user_id' => $staff->user_name_id, 'half_day_leave' => $date, 'noon' => 'Fore Noon'])->where('status', '!=', 'Rejected')->select('leave_type')->first();
                            if ($getLeave != '') {
                                $leaveType = $getLeave->leave_type;
                            }
                        } else {
                            $leaveType = $getLeave->leave_type;
                        }
                    } else {
                        $leaveType = $getLeave->leave_type;
                    }
                    if ($leaveType == null) {
                        $getPermission = PermissionRequest::where(['user_name_id' => $staff->user_name_id, 'date' => $date, 'from_time' => '08:00:00'])->select('Permission')->first();
                        if ($getPermission != '') {
                            $permission = $getPermission->Permission;
                        } else {
                            $getBiometric = StaffBiometric::where(['user_name_id' => $staff->user_name_id, 'date' => $date])->where('in_time', '!=', null)->select('status')->first();
                            if ($getBiometric != '') {
                                $status = $getBiometric->status;
                            }
                        }
                    } else {
                        $getLeaveType = LeaveType::where(['id' => $leaveType])->first();
                        if ($getLeaveType != '') {
                            $leaveType = $getLeaveType->name;
                        }
                    }
                    $staff->leaveType = $leaveType;
                    $staff->permission = $permission;
                    $staff->status = $status;
                }
            }
            return response()->json(['status' => true, 'data' => $getStaff, 'department' => $the_department]);
        } else {
            return response()->json(['status' => false, 'data' => 'Couldn\'t Get The Mandatory Data']);
        }
    }

}
