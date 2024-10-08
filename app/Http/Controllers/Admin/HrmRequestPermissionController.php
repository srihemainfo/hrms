<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyHrmRequestPermissionRequest;
use App\Http\Requests\StoreHrmRequestPermissionRequest;
use App\Http\Requests\UpdateHrmRequestPermissionRequest;
use App\Models\HrmRequestPermission;
use App\Models\PermissionRequest;
use App\Models\StaffBiometric;
use App\Models\Staffs;
use App\Models\User;
use App\Models\UserAlert;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class HrmRequestPermissionController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        // abort_if(Gate::denies('hrm_request_permission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->status)) {

            $status = $request->status;
        } else {

            $status = 0;
        }

        if ($status == 0) {

            if (auth()->user()->roles[0]->id == 15 || auth()->user()->roles[0]->id == 14 || auth()->user()->roles[0]->id == 42 || auth()->user()->roles[0]->id == 13 || auth()->user()->roles[0]->id == 1) {
                $query = PermissionRequest::where(['status' => 0])->get();
            } else {
                $query = [];
            }
            $list = $query;
        } elseif ($status == 1) {

            if (auth()->user()->roles[0]->id == 15 || auth()->user()->roles[0]->id == 14 || auth()->user()->roles[0]->id == 42 || auth()->user()->roles[0]->id == 13 || auth()->user()->roles[0]->id == 1) {
                $query = PermissionRequest::where(['status' => 1])->get();
            } else {
                $query = [];
            }

            $list = $query;
        } elseif ($status == 2) {
            if (auth()->user()->roles[0]->id == 15 || auth()->user()->roles[0]->id == 14 || auth()->user()->roles[0]->id == 42 || auth()->user()->roles[0]->id == 13 || auth()->user()->roles[0]->id == 1) {
                $query = PermissionRequest::where(['status' => 2])->get();
            } else {
                $query = [];
            }

            $list = $query;
        } elseif ($status == 3) {
            if (auth()->user()->roles[0]->id == 15 || auth()->user()->roles[0]->id == 14 || auth()->user()->roles[0]->id == 42 || auth()->user()->roles[0]->id == 13 || auth()->user()->roles[0]->id == 1) {
                $query = PermissionRequest::where(['status' => 3])->get();
            } else {
                $query = [];
            }

            $list = $query;
        } elseif ($status == 4) {
            if (auth()->user()->roles[0]->id == 15 || auth()->user()->roles[0]->id == 14 || auth()->user()->roles[0]->id == 42 || auth()->user()->roles[0]->id == 13 || auth()->user()->roles[0]->id == 1) {
                $query = PermissionRequest::where(['status' => 4])->get();
            } else {
                $query = [];
            }

            $list = $query;
        }
        // dd($list);
        $unwanted = [];
        if (auth()->user()->roles[0]->id == 42) {
            if ($list != '') {
                if (count($list) > 0) {
                    for ($i = 0; $i < count($list); $i++) {
                        $staff = Staffs::where(['user_name_id' => $list[$i]->user_name_id, 'rd_staff' => '1'])->first();
                        if ($staff != '') {
                            $list[$i]->name = $staff->name;
                            $list[$i]->dept = $staff->Dept;
                            $list[$i]->staff_code = $staff->employee_id;
                        } else {
                            array_push($unwanted, $i);
                        }
                    }
                }
            }
        } else if (auth()->user()->roles[0]->id == 14) {
            if ($list != '') {
                if (count($list) > 0) {
                    for ($i = 0; $i < count($list); $i++) {
                        $staff = Staffs::where(['user_name_id' => $list[$i]->user_name_id, 'rd_staff' => '0', 'Dept' => auth()->user()->dept])->first();
                        if ($staff != '') {
                            $list[$i]->name = $staff->name;
                            // $list[$i]->dept = $staff->Dept;
                            $list[$i]->staff_code = $staff->employee_id;
                        }
                    }
                }
            }
        } else {
            if ($list != '') {
                if (count($list) > 0) {
                    for ($i = 0; $i < count($list); $i++) {
                        $staff = Staffs::where(['user_name_id' => $list[$i]->user_name_id])->first();
                        // dd($list);
                        if ($staff != '') {
                            $list[$i]->name = $staff->name;
                            $list[$i]->staff_code = $staff->employee_id;
                        } else {
                            array_push($unwanted, $i);
                        }
                    }
                }
            }
        }
        if ($list != '') {
            $list = $list->toArray();
        }
        if (count($unwanted) > 0) {
            foreach ($unwanted as $data) {
                unset($list[$data]);
            }
            $list = array_values($list);
        }

        // if (auth()->user()->roles[0]->id == 14) {
        //     $dept = User::where(['id' => auth()->user()->id])->first();
        //     $data = [];

        //     foreach ($list as $details) {
        //         if ($details['dept'] == $dept->dept) {
        //             array_push($data, $details);
        //         }
        //     }
        // } else {
        $data = $list;
        // }
        return view('admin.hrmRequestPermissions.index', compact('data', 'status'));
    }

    public function update_hr(Request $request)
    {
        // dd($request);
        if ($request->data['id'] != '') {

            $name = auth()->user()->name;

            $id = $request->data['id'];
            $permissionType = null;

            if ($request->data['status'] == '1') {

                if (auth()->user()->roles[0]->id == 5 || auth()->user()->roles[0]->id == 6 || auth()->user()->roles[0]->id == 1) {
                    $permissionUpdate = PermissionRequest::find($id);
                    $permissionUpdate->update(['status' => 2, 'approved_by' => $name]);
                    $permissionType = $permissionUpdate->Permission;
                    $action = 'Approved';
                }
                // else {
                //     $permissionUpdate = PermissionRequest::find($id);
                //     $permissionUpdate->update(['status' => 2, 'approved_by' => $name]);
                //     $permissionType = $permissionUpdate->Permission;
                //     $action = 'Approved';
                // }
            }

            if ($request->data['status'] == '3') {

                $permissionUpdate = PermissionRequest::find($id);
                $reason = $request->data['rejected_reason'];

                $permissionUpdate->update(['status' => 3, 'approved_by' => $name, 'rejected_reason' => $reason]);
                $permissionType = $permissionUpdate->Permission;
                $action = 'Rejected';
            }

            if ($request->data['status'] == '4') {

                $reason = $request->data['rejected_reason'];
                $permissionUpdate = PermissionRequest::find($id);

                $permissionUpdate->update(['status' => 4, 'rejected_reason' => $reason, 'approved_by' => $name]);
                $permissionType = $permissionUpdate->Permission;
                $action = 'NeedClarification';
            }

            $get = PermissionRequest::where(['id' => $id])->first();
            // dd($get);
            $req_staff = $get->user_name_id;
            $req_staff_name = $get->name;
            // dd($personalPermStatus);
            if ($permissionUpdate) {
                $receiverArray = [];
                if (auth()->user()->roles[0]->id == 5 || auth()->user()->roles[0]->id == 6 || auth()->user()->roles[0]->id == 1) {
                    $checkDeptOfStaff = Staffs::where(['user_name_id' => $req_staff])->first();
                    if ($checkDeptOfStaff != '') {
                        // $dept = $checkDeptOfStaff->Dept;
                        $getHr_sup = DB::table('role_user')->whereIn('role_id', [1, 5, 6])->get();
                        if (count($getHr_sup) > 0) {
                            foreach ($getHr_sup as $val) {
                                $checkUser = User::where(['id' => $val->user_id])->get();
                                if (count($checkUser) > 0) {
                                    foreach ($checkUser as $user) {
                                        array_push($receiverArray, $user->id);
                                    }
                                }
                            }
                        }
                    }
                }

                if ($permissionUpdate->status == 2 || $permissionUpdate->status == 3 || $permissionUpdate->status == 4) {
                    $userAlert = new UserAlert;
                    $userAlert->alert_text = 'Your ' . $permissionType . ' Request ' . $action . ' By ' . $name;
                    $userAlert->alert_link = url('admin/staff-permissionsreq/staff_index');
                    $userAlert->save();
                    $userAlert->users()->sync($req_staff);
                }

                if ($permissionUpdate->status == 1) {
                    $userAlert = new UserAlert;
                    $userAlert->alert_text = $req_staff_name . $permissionType . ' Permission Approved by ' . auth()->user()->name;
                    $userAlert->alert_link = url('admin/hrm-request-permissions/' . $permissionUpdate->id);
                    $userAlert->save();
                    $userAlert->users()->sync($receiverArray);
                }
                if ($permissionUpdate->status == 4) {
                    $userAlert = new UserAlert;
                    $userAlert->alert_text = $req_staff_name . $permissionType . ' Permission Need Clarification by ' . auth()->user()->name;
                    $userAlert->alert_link = url('admin/hrm-request-permissions/' . $permissionUpdate->id);
                    $userAlert->save();
                    $userAlert->users()->sync($receiverArray);
                }

            }

            if ($get != null && $get->status == 2) {
                // if ($get->Permission == 'Personal' && $get->from_time != '' && $get->to_time != '') {

                //     $permission = 'Personal Permission ' .'(' . $get->from_time . '-' . $get->to_time . ')';

                // } elseif ($get->Permission == 'On Duty' && $get->from_time != '' && $get->to_time != '') {

                //     $permission = 'OD Permission ' . '(' . $get->from_time . '-' . $get->to_time . ')';

                // }

                if ($get->Permission == 'On Duty') {
                    $personal_permission = 0;
                } else {
                    $personal_permission = 1;
                }
                $permission = null;
                if ($get->Permission == 'Personal') {
                    $staff = Staffs::where(['user_name_id' => $get->user_name_id])->first();

                    if ($staff != '') {
                        if ($staff->personal_permission != '' && $staff->personal_permission > 0) {
                            $p_permission = $staff->personal_permission - $personal_permission;
                            $permission = 'Personal Permission (Provided) ' . '(' . $get->from_time . '-' . $get->to_time . ')';
                        } else {
                            $p_permission = 0;
                            $permission = 'Personal Permission ' . '(' . $get->from_time . '-' . $get->to_time . ')';
                        }
                    }
                    $update_teaching_staff = Staffs::where(['user_name_id' => $get->user_name_id])->update([
                        'personal_permission' => $p_permission,
                        'personal_permission_taken' => $staff->personal_permission_taken + $personal_permission,
                    ]);
                } else {
                    $staff = Staffs::where(['user_name_id' => $get->user_name_id])->first();
                    $permission = 'OD Permission ' . '(' . $get->from_time . '-' . $get->to_time . ')';
                    $update_teaching_staff = Staffs::where(['user_name_id' => $get->user_name_id])->update([
                        'on_duty' => $staff ? $staff->on_duty + 1 : 1,
                    ]);
                }
                if ($permission) {
                    $staff_biometric = StaffBiometric::where(['date' => $get->date, 'user_name_id' => $get->user_name_id])->first();
                    if ($staff_biometric != '') {
                        $status = $staff_biometric->status;
                        $permission_times = $staff_biometric->permission_times;

                        if ($staff_biometric->details != 'Sunday' || $staff_biometric->details != 'Holiday') {
                            if ($permission_times) {
                                $decode = json_decode($permission_times, true);
                                $decode[] = $permission;
                                $permission_times = json_encode($decode);
                            } else {
                                $permission_times = json_encode([$permission]);
                            }
                            $staff_biometric->permission = $permission;
                            $staff_biometric->permission_times = $permission_times;
                            $staff_biometric->update_status = 2;
                            $staff_biometric->status = $status;
                            $staff_biometric->updated_at = Carbon::now();
                            $staff_biometric->save();
                        } else {
                            \Log::info('This Date is Holiday or Sunday.');
                        }
                    }
                } else {
                    \Log::info('Permission value is null.');
                }
            }
        }

        return response()->json(['status' => 'ok']);

        return redirect()->route('admin.hrm-request-permissions.index');
    }
    public function create()
    {
        abort_if(Gate::denies('hrm_request_permission_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.hrmRequestPermissions.create', compact('users'));
    }

    public function store(StoreHrmRequestPermissionRequest $request)
    {
        $hrmRequestPermission = HrmRequestPermission::create($request->all());

        return redirect()->route('admin.hrm-request-permissions.index');
    }

    public function edit(HrmRequestPermission $hrmRequestPermission)
    {
        abort_if(Gate::denies('hrm_request_permission_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $hrmRequestPermission->load('user');

        return view('admin.hrmRequestPermissions.edit', compact('hrmRequestPermission', 'users'));
    }

    public function update(UpdateHrmRequestPermissionRequest $request, HrmRequestPermission $hrmRequestPermission)
    {
        $hrmRequestPermission->update($request->all());

        return redirect()->route('admin.hrm-request-permissions.index');
    }

    public function list(Request $request)
    {
        dd($request);
        if (isset($request->user_name_id) && $request->user_name_id != '') {
            $query = PermissionRequest::where(['user_name_id' => $request->user_name_id, 'status' => 1])->get();

            $details = $query;
        }

        if (count($details) > 0) {

            $staff = Staffs::where(['user_name_id' => $details[0]->user_name_id])->first();

            $name = $staff->name;
            $dept = $staff->Dept;
        } else {
            $name = '';
            $dept = '';
        }
        // dd($details);
        return view('admin.hrmRequestPermissions.permissionlist', compact('details', 'name', 'dept'));
    }

    public function show($id)
    {
        // abort_if(Gate::denies('hrm_request_permission_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($hrmRequestPermission);
        $permissionrequest = PermissionRequest::findOrFail($id);
        // $permissionrequest->load('user');
        // dd($permissionrequest);

        return view('admin.hrmRequestPermissions.show', compact('permissionrequest'));
    }

    public function destroy(HrmRequestPermission $hrmRequestPermission)
    {
        abort_if(Gate::denies('hrm_request_permission_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hrmRequestPermission->delete();

        return back();
    }

    public function massDestroy(MassDestroyHrmRequestPermissionRequest $request)
    {
        $hrmRequestPermissions = HrmRequestPermission::find(request('ids'));

        foreach ($hrmRequestPermissions as $hrmRequestPermission) {
            $hrmRequestPermission->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
