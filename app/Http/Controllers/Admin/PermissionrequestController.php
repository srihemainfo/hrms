<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HrmRequestLeaf;
use App\Models\PermissionRequest;
use App\Models\Staffs;
use App\Models\User;
use App\Models\UserAlert;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PermissionrequestController extends Controller
{

    public function staff_index(Request $request)
    {
        // dd($request);
        // abort_if(Gate::denies('permission_request'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->accept)) {

            PermissionRequest::where('id', $request->id)->update(['status' => 1]);
        }
        $user_name_id = auth()->user()->id;
        $name = auth()->user()->name;
        $teaching_staff = Staffs::where(['user_name_id' => $user_name_id])->first();
        $who = 'tech';
        if (!$request->updater) {

            $query = PermissionRequest::where(['user_name_id' => $user_name_id])->get();
            // dd($query);
            if ($query->count() <= 0) {

                $query->user_name_id = $user_name_id;
                $query->name = $name;
                $query->id = '';
                $query->from_time = '';
                // $query->from_time_od = '';
                $query->to_time = '';
                $query->Permission = '';
                $query->personal_permission = $teaching_staff->personal_permission ?? 0;
                // $query->project_name = '';
                $query->date = '';
                $query->reason = '';
                // $query->approved_by = '';
                $query->status = '0';
                $query->add = 'Submit';

                $staff = $query;
                $staff_edit = $query;
                $list = [];
            } else {

                $query[0]['user_name_id'] = $user_name_id;

                $query[0]['name'] = $name;

                $staff = $query[0];

                $list = $query;

                $staff_edit = new PermissionRequest;
                $staff_edit->add = 'Submit';
                $staff_edit->id = '';
                $staff_edit->from_time = '';
                // $staff_edit->from_time_od = '';
                $staff_edit->to_time = '';
                $staff_edit->Permission = '';
                // $staff_edit->to_time_od = '';
                // $staff_edit->project_name = '';
                $staff_edit->date = '';
                $staff_edit->reason = '';
                // $staff_edit->approved_by = '';
                $staff_edit->status = '0';
                $staff_edit->personal_permission = $teaching_staff->personal_permission ?? 0;
            }

        } else {

            $query_one = PermissionRequest::where(['user_name_id' => $user_name_id])->get();
            $query_two = PermissionRequest::where(['id' => $request->id])->get();

            if (!($query_two->count() <= 0)) {

                $query_one[0]['user_name_id'] = $user_name_id;

                $query_one[0]['name'] = $name;

                $query_two[0]['add'] = 'Update';

                $query_two[0]['personal_permission'] = $teaching_staff->personal_permission;

                $staff = $query_one[0];

                $list = $query_one;
                // dd($list);
                $staff_edit = $query_two[0];

            }
        }

        return view('admin.permissionrequest.staff_permissionindex', compact('staff', 'list', 'staff_edit', 'who'));

    }

    public function staff_update(Request $request)
    {
        // dd($request);
        // abort_if(Gate::denies('permission_request'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'reason' => 'required',
            'from_time' => 'required',
            'to_time' => 'required',
            'Permission' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors(),
                'errors' => $validator->errors(),
            ]); // 422 Unprocessable Entity
        }

        $user_name_id = auth()->user()->id;
        $from_time = $request->from_time;
        $to_time = $request->to_time;

        $staff = Staffs::where(['user_name_id' => $user_name_id])->first();
        // if ($staff == '') {
        //     $staff = NonStaffs::where(['user_name_id' => $user_name_id])->select('user_name_id', 'name', 'Dept', 'StaffCode', 'BiometricID', 'personal_permission')->first();
        // }
        // $dept = $staff ? $staff->Dept : null;
        $name = $staff ? $staff->name : null;
        $biometric_id = $staff ? $staff->biometric : null;
        $staff_code = $staff ? $staff->employee_id : null;
        $balance_permission = $staff ? $staff->personal_permission : 0;

        if ($request->id != 0 && $request->id != '') {

            $permissionrequest_check = PermissionRequest::where(['user_name_id' => $user_name_id, 'id' => $request->id])->update([
                'from_time' => $from_time,
                'to_time' => $to_time,
                'date' => $request->date,
                'reason' => $request->reason,
                'status' => '0',
                'Permission' => $request->Permission,
                'dept' => $dept,
                'balance_permission' => $balance_permission,
            ]);

            if ($permissionrequest_check == false) {

                return response()->json(['status' => false, 'data' => 'Permission Request Not Sent']);
            }

        } else {
            $permissionrequest_check = false;
        }

        if ($permissionrequest_check) {

            $checkDeptOfStaff = Staffs::where(['user_name_id' => $user_name_id])->first();
            if ($checkDeptOfStaff != '') {
                $dept = $checkDeptOfStaff->Dept;
                $getHOD = DB::table('role_user')->where(['role_id' => 14])->get();
                if (count($getHOD) > 0) {
                    foreach ($getHOD as $hod) {
                        $checkUser = User::where(['id' => $hod->user_id, 'dept' => $dept])->get();
                        if (count($checkUser) > 0) {
                            foreach ($checkUser as $user) {
                                array_push($receiverArray, $user->id);
                            }
                        }
                    }
                }
            }

            $alertReceiver = DB::table('role_user')->whereIn('role_id', [1, 13])->get();
            if (count($alertReceiver) > 0) {
                foreach ($alertReceiver as $receiver) {
                    array_push($receiverArray, $receiver->user_id);
                }
            }

            $userAlert = new UserAlert;
            $userAlert->alert_text = auth()->user()->name . ' Updated a ' . $request->Permission . ' Permission';
            $userAlert->alert_link = url('admin/hrm-request-permissions/' . $permissionrequest_check->id);
            $userAlert->save();
            $userAlert->users()->sync($receiverArray);

        } else {

            if ($request->Permission == "On Duty") {
                $from_time = $request->from_time . ":00";
                $to_time = $request->to_time . ":00";
            }

            $permissionrequest = new PermissionRequest;
            $permissionrequest->user_name_id = $user_name_id;
            $permissionrequest->from_time = $from_time;
            $permissionrequest->to_time = $to_time;
            $permissionrequest->date = $request->date;
            $permissionrequest->reason = $request->reason;
            $permissionrequest->status = '0';
            $permissionrequest->Permission = $request->Permission;
            // $permissionrequest->dept = $dept;
            $permissionrequest->name = $name;
            $permissionrequest->biometric_id = $biometric_id;
            $permissionrequest->staff_code = $staff_code;
            $permissionrequest->balance_permission = $balance_permission;
            $permissionrequest->save();

            if ($permissionrequest) {

                $receiverArray = [];
                $checkDeptOfStaff = Staffs::where(['user_name_id' => $user_name_id])->first();
                if ($checkDeptOfStaff != '') {
                    // $dept = $checkDeptOfStaff->Dept;
                    $getHR_sup = DB::table('role_user')->whereIn('role_id', [1, 5, 6])->get();
                    if (count($getHR_sup) > 0) {
                        foreach ($getHR_sup as $val) {
                            $checkUser = User::where(['id' => $val->user_id])->select('id')->get();
                            if (count($checkUser) > 0) {
                                foreach ($checkUser as $user) {
                                    array_push($receiverArray, $user->id);
                                }
                            }
                        }
                    }
                }

                $alertReceiver = DB::table('role_user')->whereIn('role_id', [1, 5, 6])->get();
                if (count($alertReceiver) > 0) {
                    foreach ($alertReceiver as $receiver) {
                        array_push($receiverArray, $receiver->user_id);
                    }
                }

                $userAlert = new UserAlert;
                $userAlert->alert_text = auth()->user()->name . ' Applied a ' . $request->Permission . ' Permission';
                $userAlert->alert_link = url('admin/hrm-request-permissions/' . $permissionrequest->id);
                $userAlert->save();
                $userAlert->users()->sync($receiverArray);

            } else {
                return response()->json(['status' => false, 'data' => 'Permission Request Not Sent']);
            }
        }
        return response()->json(['status' => true, 'data' => 'Permission Request Sent']);
        // return redirect()->route('admin.staff-permissionsreq.staff_index', $staff);
    }

    public function checkDate(Request $request)
    {
        $checkPermissionReq = PermissionRequest::where(['user_name_id' => auth()->user()->id, 'date' => $request->date])->whereNotIn('status', [3, 4])->select('id')->get();
        // dd($checkPermissionReq);
        if ($checkPermissionReq->count() > 0) {
            return response()->json(['status' => false, 'data' => 'Already You Have Applied Permission For This Date']);
        } else {
            $get_leave_req = HrmRequestLeaf::where(['user_id' => auth()->user()->id])->where('status', '!=', 'Rejected')->get();
            if (count($get_leave_req) > 0) {
                $date = $request->date;
                foreach ($get_leave_req as $leave_req) {

                    if ($leave_req->from_date != null) {
                        $leaveFromDate = Carbon::parse($leave_req->from_date);
                    } else {
                        $leaveFromDate = null;
                    }

                    if ($leave_req->to_date != null) {
                        $leaveToDate = Carbon::parse($leave_req->to_date);
                    } else {
                        $leaveToDate = null;
                    }

                    if ($leave_req->half_day_leave != null) {
                        $halfDayLeave = Carbon::parse($leave_req->half_day_leave);
                    } else {
                        $halfDayLeave = null;
                    }

                    if ($date != null) {
                        $theDate = Carbon::parse($date);
                    } else {
                        $theDate = null;
                    }

                    if ($theDate != null && $leaveFromDate != null && $leaveToDate != null && ($theDate->between($leaveFromDate, $leaveToDate))) {
                        return response()->json(['status' => false, 'data' => 'Already You Have Applied Leave / OD For This Date']);
                        break;
                    }

                }
                return response()->json(['status' => true, 'data' => '']);
            } else {
                return response()->json(['status' => true, 'data' => '']);
            }
        }
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function show(PermissionRequest $permissionrequest)
    {
        //
    }

    public function edit(PermissionRequest $permissionrequest)
    {
        //
    }

    public function update(Request $request, PermissionRequest $permissionrequest)
    {
        //
    }

    public function destroy($request)
    {
        $permissionrequest = PermissionRequest::find($request);
        $permissionrequest->delete();

        return back();
    }
}
