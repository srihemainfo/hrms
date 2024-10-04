<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyHrmRequestPermissionRequest;
use App\Http\Requests\StoreHrmRequestPermissionRequest;
use App\Http\Requests\UpdateHrmRequestPermissionRequest;
use App\Models\HrmRequestPermission;
use App\Models\NonTeachingStaff;
use App\Models\PermissionRequest;
use App\Models\StaffBiometric;
use App\Models\TeachingStaff;
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
        abort_if(Gate::denies('hrm_request_permission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
                        $staff = TeachingStaff::where(['user_name_id' => $list[$i]->user_name_id, 'rd_staff' => '1'])->first();
                        if ($staff != '') {
                            $list[$i]->name = $staff->name;
                            $list[$i]->dept = $staff->Dept;
                            $list[$i]->staff_code = $staff->StaffCode;
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
                        $staff = TeachingStaff::where(['user_name_id' => $list[$i]->user_name_id, 'rd_staff' => '0', 'Dept' => auth()->user()->dept])->first();
                        if ($staff != '') {
                            $list[$i]->name = $staff->name;
                            $list[$i]->dept = $staff->Dept;
                            $list[$i]->staff_code = $staff->StaffCode;
                        } else {
                            $n_staff = NonTeachingStaff::where(['user_name_id' => $list[$i]->user_name_id, 'Dept' => auth()->user()->dept])->select('name', 'user_name_id', 'Dept', 'StaffCode')->first();
                            if ($n_staff != '') {
                                $list[$i]->name = $n_staff->name;
                                $list[$i]->dept = $n_staff->Dept;
                                $list[$i]->staff_code = $n_staff->StaffCode;
                            } else {
                                array_push($unwanted, $i);
                            }
                        }
                    }
                }
            }
        } else {
            if ($list != '') {
                if (count($list) > 0) {
                    for ($i = 0; $i < count($list); $i++) {
                        $staff = TeachingStaff::where(['user_name_id' => $list[$i]->user_name_id])->first();
                        if ($staff == null) {
                            $staff = NonTeachingStaff::where(['user_name_id' => $list[$i]->user_name_id])->first();
                        }
                        if ($staff != '') {
                            $list[$i]->name = $staff->name;
                            $list[$i]->dept = $staff->Dept;
                            $list[$i]->staff_code = $staff->StaffCode;
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
        if ($request->data['id'] != '') {

            $name = auth()->user()->name;

            $id = $request->data['id'];
            $permissionType = null;

            if ($request->data['status'] == '1') {

                if (auth()->user()->roles[0]->id == 13) {
                    $permissionUpdate = PermissionRequest::find($id);

                    if ($permissionUpdate->Permission == 'On Duty') {
                        $permissionUpdate->update(['status' => 2, 'approved_by' => $name]);
                    } else {
                        $appliedDate = $permissionUpdate->date;
                        $appliedMonth = Carbon::parse($appliedDate)->month;
                        $theDate = (int) Carbon::parse($appliedDate)->format('d');
                        $currentMonth = Carbon::now()->month;
                        $currentDate = (int) Carbon::now()->format('d');
                        if ($appliedMonth == $currentMonth) {
                            if ($theDate > 25) {
                                $personalPermStatus = 'PRESENT';
                            } else {
                                if ($currentDate > 25) {
                                    $personalPermStatus = 'PAST';
                                } else {
                                    $personalPermStatus = 'PRESENT';
                                }
                            }
                        } elseif ($appliedMonth < $currentMonth) {
                            if ($theDate > 25) {
                                if ($currentDate > 25) {
                                    $personalPermStatus = 'PAST';
                                } else {
                                    $personalPermStatus = 'PRESENT';
                                }
                            } else {
                                $personalPermStatus = 'PAST';
                            }
                        } elseif ($appliedMonth > $currentMonth) {
                            if ($theDate > 25) {
                                if ($currentDate > 25) {
                                    $personalPermStatus = 'PAST';
                                } else {
                                    $personalPermStatus = 'PRESENT';
                                }
                            } else {
                                $personalPermStatus = 'PAST';
                            }
                        }
                    }

                    $permissionUpdate->update(['status' => 2, 'approved_by' => $name]);
                    // $permissionUpdate->update(['status' => 1, 'approved_by' => $name]);
                    $permissionType = $permissionUpdate->Permission;
                    $action = 'Approved';
                } else {

                    $permissionUpdate = PermissionRequest::find($id);

                    $permissionUpdate->update(['status' => 1, 'approved_by' => $name]);
                    $permissionType = $permissionUpdate->Permission;
                    $action = 'Pending';
                }
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
            $req_staff = $get->user_name_id;
            $req_staff_name = $get->name;
            // dd($personalPermStatus);
            if ($permissionUpdate) {
                $receiverArray = [];
                if (auth()->user()->roles[0]->id == 13) {
                    $checkDeptOfStaff = TeachingStaff::where(['user_name_id' => $req_staff])->first();
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
                }
                if (auth()->user()->roles[0]->id != 13) {
                    $alertReceiver = DB::table('role_user')->whereIn('role_id', [13])->get();
                    if (count($alertReceiver) > 0) {
                        foreach ($alertReceiver as $receiver) {
                            array_push($receiverArray, $receiver->user_id);
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
                if ($get->Permission == 'Personal' && $get->from_time == '08:00:00') {
                    $permission = 'FN Permission';
                } elseif ($get->Permission == 'Personal' && ($get->from_time == '15:00:00' || $get->from_time == '16:00:00')) {
                    $permission = 'AN Permission';
                } elseif ($get->Permission == 'On Duty') {
                    $permission = 'OD Permission';
                }

                if ($permission != 'OD Permission') {
                    $personal_permission = 1;
                } else {
                    $personal_permission = 0;
                }

                if ($get->Permission == 'Personal' && $personalPermStatus == 'PRESENT') {
                    $teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_name_id])->first();
                    if ($teaching_staff == null) {
                        $teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_name_id])->first();
                    }

                    if ($teaching_staff != '') {
                        if ($teaching_staff->personal_permission != '' && $teaching_staff->personal_permission > 0) {
                            $p_permission = $teaching_staff->personal_permission - $personal_permission;
                        } else {
                            $p_permission = 0;
                        }
                    }
                    $update_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_name_id])->update([
                        'personal_permission' => $p_permission,
                        'personal_permission_taken' => $teaching_staff->personal_permission_taken + $personal_permission,
                    ]);
                    if ($update_teaching_staff == 0) {
                        $update_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_name_id])->update([
                            'personal_permission' => $p_permission,
                            'personal_permission_taken' => $teaching_staff->personal_permission_taken + $personal_permission,
                        ]);
                    }
                } else {
                    $teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_name_id])->select('admin_od', 'exam_od', 'training_od')->first();
                    if ($teaching_staff == null) {
                        $teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_name_id])->select('admin_od', 'exam_od', 'training_od')->first();
                    }

                    if ($get->Permission == 'Admin OD') {
                        $update_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_name_id])->update([
                            'admin_od' => $teaching_staff ? $teaching_staff->admin_od + 1 : 1,
                        ]);

                        $update_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_name_id])->update([
                            'admin_od' => $teaching_staff ? $teaching_staff->admin_od + 1 : 1,
                        ]);
                    }
                    if ($get->Permission == 'Exam OD') {
                        $update_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_name_id])->update([
                            'exam_od' => $teaching_staff ? $teaching_staff->exam_od + 1 : 1,
                        ]);

                        $update_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_name_id])->update([
                            'exam_od' => $teaching_staff ? $teaching_staff->exam_od + 1 : 1,
                        ]);
                    }
                    if ($get->Permission == 'Training OD') {
                        $update_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_name_id])->update([
                            'training_od' => $teaching_staff ? $teaching_staff->training_od + 1 : 1,
                        ]);

                        $update_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_name_id])->update([
                            'training_od' => $teaching_staff ? $teaching_staff->training_od + 1 : 1,
                        ]);
                    }

                }

                $staff_biometric = StaffBiometric::where(['date' => $get->date, 'user_name_id' => $get->user_name_id])->select('id', 'details', 'update_status', 'status', 'permission', 'updated_at', 'in_time', 'out_time')->first();
                if ($staff_biometric != '') {
                    $status = $staff_biometric->status;
                    $tempDetail = $get->Permission . ' Permission';
                    if ($staff_biometric->details != null) {
                        $tempDetail = $staff_biometric->details;
                        if ($staff_biometric->details != 'Sunday') {
                            if (strpos($staff_biometric->details, ',') !== false) {
                                $explode = explode(',', $staff_biometric->details);
                                if (!in_array('Holiday', $explode)) {
                                    if (in_array('Late', $explode) && ($permission == 'FN Permission')) {
                                        $theIndex = array_search('Late', $explode);
                                        unset($explode[$theIndex]);
                                    } else if (in_array('Too Late', $explode) && ($permission == 'FN Permission')) {
                                        $theIndex = array_search('Too Late', $explode);
                                        unset($explode[$theIndex]);
                                    } else if (in_array('Early Out', $explode) && ($permission == 'AN Permission')) {
                                        $theIndex = array_search('Early Out', $explode);
                                        $status = 'Present';
                                        unset($explode[$theIndex]);
                                    } else if ($permission == 'OD Permission') {
                                        if (in_array('Late', $explode) && (strtotime($staff_biometric->in_time) >= strtotime($get->from_time) && strtotime($staff_biometric->in_time) <= strtotime($get->to_time))) {
                                            $theIndex = array_search('Late', $explode);
                                            unset($explode[$theIndex]);
                                        } else if (in_array('Too Late', $explode) && (strtotime($staff_biometric->in_time) >= strtotime($get->from_time) && strtotime($staff_biometric->in_time) <= strtotime($get->to_time))) {
                                            $theIndex = array_search('Too Late', $explode);
                                            unset($explode[$theIndex]);
                                        } else if (in_array('Early Out', $explode) && (strtotime($staff_biometric->out_time) <= strtotime($get->to_time))) {
                                            $theIndex = array_search('Early Out', $explode);
                                            $status = 'Present';
                                            unset($explode[$theIndex]);
                                        }
                                    }
                                    $implode = implode(',', $explode);
                                    $staff_biometric->details = $implode;
                                }
                            } else {
                                if ($staff_biometric->details != 'Holiday') {
                                    if ($staff_biometric->details == 'Late' && $permission == 'FN Permission') {
                                        $staff_biometric->details = '';
                                    } else if ($staff_biometric->details == 'Too Late' && $permission == 'FN Permission') {
                                        $staff_biometric->details = '';
                                    } else if ($staff_biometric->details == 'Early Out' && $permission == 'AN Permission') {
                                        $staff_biometric->details == '';
                                        $status = 'Present';
                                    } else if ($permission == 'OD Permission') {
                                        if ($staff_biometric->details == 'Late' && (strtotime($staff_biometric->in_time) >= strtotime($get->from_time) && strtotime($staff_biometric->in_time) <= strtotime($get->to_time))) {
                                            $staff_biometric->details = '';
                                        } else if ($staff_biometric->details == 'Too Late' && (strtotime($staff_biometric->in_time) >= strtotime($get->from_time) && strtotime($staff_biometric->in_time) <= strtotime($get->to_time))) {
                                            $staff_biometric->details = '';
                                        } else if ($staff_biometric->details == 'Early Out' && (strtotime($staff_biometric->out_time) <= strtotime($get->to_time))) {
                                            $staff_biometric->details == '';
                                            $status = 'Present';
                                        }
                                    }
                                }
                            }
                            if ($staff_biometric->details != '') {
                                $tempDetail = $staff_biometric->details . ',' . $get->Permission . ' Permission';
                            } else {
                                $tempDetail = $get->Permission . ' Permission';
                            }
                        }
                        $staff_biometric->permission = $permission;
                        $staff_biometric->details = $tempDetail;
                        $staff_biometric->update_status = 2;
                        $staff_biometric->status = $status;
                        $staff_biometric->updated_at = Carbon::now();
                        $staff_biometric->save();

                    } else {

                        $staff_biometric->permission = $permission;
                        $staff_biometric->details = $get->Permission . ' Permission';
                        $staff_biometric->update_status = 2;
                        $staff_biometric->updated_at = Carbon::now();
                        $staff_biometric->save();
                    }
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

        if (isset($request->user_name_id) && $request->user_name_id != '') {
            $query = PermissionRequest::where(['user_name_id' => $request->user_name_id, 'status' => 1])->get();

            $details = $query;
        }

        if (count($details) > 0) {

            $staff = TeachingStaff::where(['user_name_id' => $details[0]->user_name_id])->first();

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
