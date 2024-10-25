<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HrmRequestLeaf;
use App\Models\LeaveType;
use App\Models\PermissionRequest;
use App\Models\StaffBiometric;
use App\Models\Staffs;
use Illuminate\Support\Facades\Gate;
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
            // $non_tech_staff = NonTeachingStaff::groupBy('name', 'user_name_id', 'employee_id')->select('name', 'user_name_id', 'employee_id')->get();
            $tech_staff = Staffs::get();
            if (count($tech_staff) > 0) {
                foreach ($tech_staff as $data) {
                    array_push($staff, ['user_name_id' => $data->user_name_id, 'name' => $data->name . '  (' . $data->employee_id . ')']);
                }
            }

        }
        return view('admin.staffAttendance.staffAttendanceRegister', compact('staff'));
    }

    public function search(Request $request)
    {
        abort_if(Gate::denies('employee_salary_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request) {
            $attend_rep = '';
            $shift = '';
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $user = $request->user;

            $staff = Staffs::where('user_name_id', $user)->first();
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
                    $one->details = '';
                    if ($attend_rep[$i]['details'] == 'Casual Leave') {
                        $one->details .= "<span style=\"color:red;\">Casual Leave (NA)</span>";
                    } else if ($attend_rep[$i]['details'] == 'After Noon Casual Leave') {
                        $one->details .= "<span style=\"color:red;\">After Noon Casual Leave (NA)</span>";
                    } else if ($attend_rep[$i]['details'] == 'Fore Noon Casual Leave') {
                        $one->details .= "<span style=\"color:red;\">Fore Noon Casual Leave (NA)</span>";
                    } else {
                        if ($attend_rep[$i]['details'] == 'Sunday' || $attend_rep[$i]['details'] == 'Holiday') {
                            $one->details .= '<span style="color:green;">' . $attend_rep[$i]['details'] . '</span>';
                        } else {
                            $one->details .= $attend_rep[$i]['details'];

                        }

                    }

                    if ($attend_rep[$i]['isLate'] == 1) {
                        $one->details .= '<span style="color:red;"> Late </span>';
                    }

                    if ($attend_rep[$i]['earlyOut'] == 1) {
                        $one->details .= '<span style="color:red;"> Early Out </span>';
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
        // $department = ToolsDepartment::select('id', 'name')->get();
        // if ($role_id == 14) {
        //     $dept = auth()->user()->dept;
        //     if ($dept != null) {
        //         $department = ToolsDepartment::where(['name' => $dept])->select('id', 'name')->get();
        //     }
        // }
        return view('admin.staffAttendance.staffDailyAttendance');
    }

    public function attendanceData(Request $request)
    {

        if (isset($request->date)) {

            $department = $request->department;
            $date = $request->date;

            // $getDept = ToolsDepartment::where(['id' => $department])->select('name')->first();
            $getStaff = Staffs::join('personal_details', 'staffs.user_name_id', '=', 'personal_details.user_name_id')->where(function ($query) {
                $query->where('personal_details.employment_status', '!=', 'Relieving')
                    ->WhereNotIn('staffs.role_id', [1, 2, 3])
                    ->orWhereNull('personal_details.employment_status');
            })->select('staffs.name', 'staffs.employee_id', 'staffs.user_name_id', 'personal_details.employment_status')->get();
            // dd($getStaff);
            // if ($getDept != '') {

            //     if (count($getStaff) <= 0) {
            //         $getStaff = NonTeachingStaff::join('personal_details', 'non_staffs.user_name_id', '=', 'personal_details.user_name_id')->where('non_staffs.Dept', $getDept->name)->where(function ($query) {
            //             $query->where('personal_details.employment_status', '!=', 'Relieving')
            //                 ->orWhereNull('personal_details.employment_status');
            //         })->select('non_staffs.name', 'non_staffs.employee_id', 'non_staffs.user_name_id', 'personal_details.employment_status')->get();
            //     }
            //     $the_department = $getDept->name;
            // } else {
            //     $getStaff = [];
            // }
            $the_department = '';
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
            return response()->json(['status' => true, 'data' => $getStaff]);
        } else {
            return response()->json(['status' => false, 'data' => 'Couldn\'t Get The Mandatory Data']);
        }
    }

}
