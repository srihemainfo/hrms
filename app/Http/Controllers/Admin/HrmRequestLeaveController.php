<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyHrmRequestLeafRequest;
use App\Http\Requests\UpdateHrmRequestLeafRequest;
use App\Models\ClassTimeTableOne;
use App\Models\ClassTimeTableTwo;
use App\Models\CourseEnrollMaster;
use App\Models\Document;
use App\Models\ExperienceDetail;
use App\Models\HrmRequestLeaf;
use App\Models\LeaveImplement;
use App\Models\LeaveType;
use App\Models\NonTeachingStaff;
use App\Models\StaffAlteration;
use App\Models\StaffAlterationRegister;
use App\Models\StaffBiometric;
use App\Models\TeachingStaff;
use App\Models\ToolsCourse;
use App\Models\User;
use App\Models\UserAlert;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Nette\Utils\DateTime;
use Symfony\Component\HttpFoundation\Response;

class HrmRequestLeaveController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('hrm_request_leaf_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $leave_types = LeaveType::pluck('name', 'id');
        $retrieveDateStart = [Carbon::now()->subMonths(2)->startOfMonth()];
        $retrieveDateEnd = [Carbon::now()->endOfMonth()];
        // dd($retrieveDateEnd, $retrieveDateStart);
        if (isset($request->status)) {

            $status = $request->status;
        } else {

            $status = 'Pending';
        }
        $principal = false;
        $query1 = [];
        if ($status == 'Pending') {

            if (auth()->user()->roles[0]->id == 14 || auth()->user()->roles[0]->id == 42) { // HOD || R & D Head
                $query = HrmRequestLeaf::where(['status' => 'Pending', 'level' => 0])->whereBetween('created_at', [$retrieveDateStart, $retrieveDateEnd])->get();
            } elseif (auth()->user()->roles[0]->id == 15) { //Pricipal
                $principal = true;
                $query = HrmRequestLeaf::where(['status' => 'Pending', 'level' => 1])->whereIn('leave_type', [2, 3, 4, 6, 7, 8])->whereBetween('created_at', [$retrieveDateStart, $retrieveDateEnd])->get();
            } elseif (auth()->user()->roles[0]->id == 13 || auth()->user()->roles[0]->id == 1) { // Hr || Admin
                // $query = HrmRequestLeaf::where(['status' => 'Pending', 'level' => 0])->get();
                // $query = HrmRequestLeaf::where(['status' => 'Pending'])->whereIn('level', [0,1])->get();
                $query = HrmRequestLeaf::where(['status' => 'Pending'])->whereIn('level', [0, 1])->whereIn('leave_type', [2, 3, 4, 6, 7, 8])->whereBetween('created_at', [$retrieveDateStart, $retrieveDateEnd])->get();
                $query1 = HrmRequestLeaf::where(['status' => 'Pending', 'level' => 0])->whereIn('leave_type', [1, 5])->whereBetween('created_at', [$retrieveDateStart, $retrieveDateEnd])->get();
                // dd($query);
            } else {
                $query = [];
            }

            $list = $query;
        } elseif ($status == 'Approved') {
            // if (auth()->user()->roles[0]->id == 14 || auth()->user()->roles[0]->id == 42) { // HOD || R & D Head
            //     $query = HrmRequestLeaf::where('status','Approved')->get();
            // } elseif (auth()->user()->roles[0]->id == 15) { //Pricipal
            //     // $query = HrmRequestLeaf::where(['status' => 'Pending', 'level' => 95])->whereIn('leave_type', [2, 3, 4, 6, 7])->get();
            //     $query1 = HrmRequestLeaf::where(['status' => 'Approved'])->get();
            // } else {
            $query = HrmRequestLeaf::where(['status' => 'Approved'])->whereBetween('created_at', [$retrieveDateStart, $retrieveDateEnd])->get();
            // }

            $list = $query;
        } elseif ($status == 'Rejected') {
            $query = HrmRequestLeaf::where(['status' => 'Rejected'])->whereBetween('created_at', [$retrieveDateStart, $retrieveDateEnd])->get();

            $list = $query;
        } elseif ($status == 'Verified') {

            if (auth()->user()->roles[0]->id == 14 || auth()->user()->roles[0]->id == 42) { // HOD || R & D Head
                $query = HrmRequestLeaf::where(['status' => 'Pending', 'level' => 1])->whereBetween('created_at', [$retrieveDateStart, $retrieveDateEnd])->get();
            } elseif (auth()->user()->roles[0]->id == 15) { //Pricipal
                $query = HrmRequestLeaf::where(['status' => 'Pending', 'level' => 95])->whereIn('leave_type', [2, 3, 4, 6, 7, 8])->whereBetween('created_at', [$retrieveDateStart, $retrieveDateEnd])->get();
                $query1 = HrmRequestLeaf::where(['status' => 'Pending', 'level' => 1])->whereIn('leave_type', [1, 5])->whereBetween('created_at', [$retrieveDateStart, $retrieveDateEnd])->get();
            } elseif (auth()->user()->roles[0]->id == 13 || auth()->user()->roles[0]->id == 1) { // Hr || Admin
                $query = HrmRequestLeaf::where(['status' => 'Pending', 'level' => 95])->whereIn('leave_type', [2, 3, 4, 6, 7, 8])->whereBetween('created_at', [$retrieveDateStart, $retrieveDateEnd])->get();
                $query1 = HrmRequestLeaf::where(['status' => 'Pending', 'level' => 1])->whereIn('leave_type', [1, 5])->whereBetween('created_at', [$retrieveDateStart, $retrieveDateEnd])->get();
            } else {
                $query = [];
            }

            $list = $query;
        } elseif ($status == 'NeedClarification') {
            $query = HrmRequestLeaf::where(['status' => 'NeedClarification', 'level' => 99])->whereBetween('created_at', [$retrieveDateStart, $retrieveDateEnd])->get();
            $list = $query;
        }

        $unwanted = [];
        if ($query1 != '' && count($query1) > 0) {
            if (auth()->user()->roles[0]->id == 15) { //Pricipal
                for ($i = 0; $i < count($query1); $i++) {
                    $staff = TeachingStaff::where(['user_name_id' => $query1[$i]->user_id])->select('name', 'user_name_id', 'Dept', 'StaffCode')->first();
                    if ($staff) {
                        $query1[$i]->name = $staff->name;
                        $query1[$i]->dept = $staff->Dept;
                        $query1[$i]->staff_code = $staff->StaffCode;
                        $query1[$i]->url = 'teaching-staff-edge';
                    } else {
                        $n_staff = NonTeachingStaff::where(['user_name_id' => $query1[$i]->user_id])->select('name', 'user_name_id', 'Dept', 'StaffCode')->first();
                        $getRole = DB::table('role_user')->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')->where(['role_user.user_id' => $query1[$i]->user_id])->select('roles.type_id')->first();
                        if ($getRole != '') {
                            if ($getRole->type_id == 2) {
                                $query1[$i]->name = $n_staff->name;
                                $query1[$i]->dept = $n_staff->Dept;
                                $query1[$i]->staff_code = $n_staff->StaffCode;
                                $query1[$i]->url = 'non-teaching-staff-edge';
                            } else {
                                array_push($unwanted, $i);
                            }
                        }
                    }
                }
            } else {
                for ($i = 0; $i < count($query1); $i++) {
                    $staff = TeachingStaff::where(['user_name_id' => $query1[$i]->user_id])->select('name', 'user_name_id', 'Dept', 'StaffCode')->first();

                    if ($staff) {
                        $query1[$i]->name = $staff->name;
                        $query1[$i]->dept = $staff->Dept;
                        $query1[$i]->staff_code = $staff->StaffCode;
                        $query1[$i]->url = 'teaching-staff-edge';
                    } else {
                        $n_staff = NonTeachingStaff::where(['user_name_id' => $query1[$i]->user_id])->select('name', 'user_name_id', 'Dept', 'StaffCode')->first();
                        $query1[$i]->name = $n_staff->name;
                        $query1[$i]->dept = $n_staff->Dept;
                        $query1[$i]->staff_code = $n_staff->StaffCode;
                        $query1[$i]->url = 'non-teaching-staff-edge';
                    }
                }
            }
        }
        if ($list != '' && count($list) > 0) {
            if (auth()->user()->roles[0]->id == 15) { //Pricipal
                for ($i = 0; $i < count($list); $i++) {
                    $staff = TeachingStaff::where(['user_name_id' => $list[$i]->user_id])->select('name', 'user_name_id', 'Dept', 'StaffCode')->first();
                    if ($staff) {
                        $list[$i]->name = $staff->name;
                        $list[$i]->dept = $staff->Dept;
                        $list[$i]->staff_code = $staff->StaffCode;
                        $list[$i]->url = 'teaching-staff-edge';
                    } else {
                        $n_staff = NonTeachingStaff::where(['user_name_id' => $list[$i]->user_id])->select('name', 'user_name_id', 'Dept', 'StaffCode')->first();
                        $getRole = DB::table('role_user')->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')->where(['role_user.user_id' => $list[$i]->user_id])->select('roles.type_id')->first();
                        if ($getRole != '') {
                            if ($getRole->type_id == 2) {
                                $list[$i]->name = $n_staff->name;
                                $list[$i]->dept = $n_staff->Dept;
                                $list[$i]->staff_code = $n_staff->StaffCode;
                                $list[$i]->url = 'non-teaching-staff-edge';
                            } else {
                                array_push($unwanted, $i);
                            }
                        }
                    }
                }
            } else if (auth()->user()->roles[0]->id == 42) { // R & D Head
                for ($i = 0; $i < count($list); $i++) {
                    $staff = TeachingStaff::where(['user_name_id' => $list[$i]->user_id, 'rd_staff' => '1'])->select('name', 'user_name_id', 'Dept', 'StaffCode')->first();
                    if ($staff != '') {
                        $list[$i]->name = $staff->name;
                        $list[$i]->dept = $staff->Dept;
                        $list[$i]->staff_code = $staff->StaffCode;
                        $list[$i]->url = 'teaching-staff-edge';
                    } else {
                        array_push($unwanted, $i);
                    }
                }
            } else if (auth()->user()->roles[0]->id == 14) { // HOD
                for ($i = 0; $i < count($list); $i++) {
                    $staff = TeachingStaff::where(['user_name_id' => $list[$i]->user_id, 'rd_staff' => '0', 'Dept' => auth()->user()->dept])->select('name', 'user_name_id', 'Dept', 'StaffCode')->first();
                    if ($staff != '') {
                        $list[$i]->name = $staff->name;
                        $list[$i]->dept = $staff->Dept;
                        $list[$i]->staff_code = $staff->StaffCode;
                        $list[$i]->url = 'teaching-staff-edge';
                    } else {
                        $n_staff = NonTeachingStaff::where(['user_name_id' => $list[$i]->user_id, 'Dept' => auth()->user()->dept])->select('name', 'user_name_id', 'Dept', 'StaffCode')->first();
                        if ($n_staff != '') {
                            $list[$i]->name = $n_staff->name;
                            $list[$i]->dept = $n_staff->Dept;
                            $list[$i]->staff_code = $n_staff->StaffCode;
                            $list[$i]->url = 'non-teaching-staff-edge';
                        } else {
                            array_push($unwanted, $i);
                        }
                    }
                }
            } else {
                for ($i = 0; $i < count($list); $i++) {
                    $staff = TeachingStaff::where(['user_name_id' => $list[$i]->user_id])->select('name', 'user_name_id', 'Dept', 'StaffCode')->first();

                    if ($staff) {
                        $list[$i]->name = $staff->name;
                        $list[$i]->dept = $staff->Dept;
                        $list[$i]->staff_code = $staff->StaffCode;
                        $list[$i]->url = 'teaching-staff-edge';
                    } else {
                        $n_staff = NonTeachingStaff::where(['user_name_id' => $list[$i]->user_id])->select('name', 'user_name_id', 'Dept', 'StaffCode')->first();
                        $list[$i]->name = $n_staff->name;
                        $list[$i]->dept = $n_staff->Dept;
                        $list[$i]->staff_code = $n_staff->StaffCode;
                        $list[$i]->url = 'non-teaching-staff-edge';
                    }
                    // dd($staff);
                }
            }
        }
        if (count($list) > 0) {
            $list = $list->toArray();
        }
        if ($query1 != '' && count($query1) > 0) {
            $list1 = $query1->toArray();
        } else {
            $list1 = [];
        }

        if (count($unwanted) > 0) {
            foreach ($unwanted as $data) {
                unset($list[$data]);
                unset($list1[$data]);
            }
            $list = array_values($list);
            $list1 = array_values($list1);
        }
        $data = $list;
        return view('admin.hrmRequestLeaves.index', compact('data', 'status', 'leave_types', 'list1', 'principal'));
    }

    public function create()
    {
        abort_if(Gate::denies('hrm_request_leaf_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.hrmRequestLeaves.create', compact('users'));
    }

    public function staff_index(Request $request)
    {

        // abort_if(Gate::denies('hrm_request_leaf_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user_name_id = auth()->user()->id;
        $leave_type = LeaveType::pluck('name', 'id');

        $teaching_staff = TeachingStaff::where(['user_name_id' => $user_name_id])->first();

        if (empty($teaching_staff)) {
            $non_tech_staff = NonTeachingStaff::where(['user_name_id' => $user_name_id])->first();
            $got_staff = $non_tech_staff;

            $who = 'non_tech';
        } else {
            $got_staff = $teaching_staff;

            $who = 'tech';
        }
        if ($got_staff == null || $got_staff == '') {
            return redirect()->route('admin.home');
        }

        if (!$request->updater) {
            $query = HrmRequestLeaf::where(['user_id' => $user_name_id])->get();

            if ($query->count() <= 0) {

                $query->user_name_id = $user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->leave_types = $leave_type;
                $query->leave_type = '';
                $query->reason = '';
                $query->subject = '';
                $query->status = 'Pending';
                $query->from_date = '';
                $query->to_date = null;
                $query->off_date = null;
                $query->half_day_leave = null;
                $query->noon = null;
                $query->avail_cl = $got_staff->casual_leave;
                $query->alter_date = '';
                $query->add = 'Submit';

                $staff = $query;
                $staff_edit = $query;
                $list = [];
            } else {

                $query[0]['user_name_id'] = $user_name_id;

                $query[0]['name'] = $request->name;

                $staff = $query[0];

                for ($i = 0; $i < count($query); $i++) {

                    $query[$i]->leave_types = $leave_type;
                }

                $list = $query;

                $staff_edit = new HrmRequestLeaf;
                $staff_edit->add = 'Submit';
                $staff_edit->id = '';
                $staff_edit->leave_types = $leave_type;
                $staff_edit->reason = '';
                $staff_edit->subject = '';
                $staff_edit->from_date = '-';
                $staff_edit->to_date = null;
                $staff_edit->off_date = null;
                $staff_edit->alter_date = null;
                $staff_edit->half_day_leave = null;
                $staff_edit->noon = '';
                $staff_edit->avail_cl = $got_staff->casual_leave;
            }
            $get_AssignedStaff = [];
        } else {

            $query_one = HrmRequestLeaf::where(['user_id' => $user_name_id])->get();
            $query_two = HrmRequestLeaf::where(['id' => $request->id])->get();

            if (!$query_two->count() <= 0) {

                $query_one[0]['user_name_id'] = $user_name_id;

                $query_one[0]['name'] = $request->name;

                $query_two[0]['add'] = 'Update';

                $query_two[0]['leave_types'] = $leave_type;

                $query_two[0]['avail_cl'] = $got_staff->casual_leave;

                $staff = $query_one[0];

                for ($i = 0; $i < count($query_one); $i++) {

                    $query_one[$i]->leave_types = $leave_type;
                }

                $list = $query_one;
                // dd($staff);
                $staff_edit = $query_two[0];
            } else {
                dd('Error');
            }
            $get_AssignedStaff = StaffAlteration::where(['from_date' => $staff_edit->from_date, 'to_date' => $staff_edit->to_date, 'from_id' => $staff_edit->user_id])->get();

            if ($get_AssignedStaff->count() > 0) {
                foreach ($get_AssignedStaff as $AssignedStaff) {
                    $get_Staff = TeachingStaff::where(['user_name_id' => $AssignedStaff->to_id])->select('name', 'user_name_id', 'StaffCode')->first();
                    if ($get_Staff != '') {
                        $AssignedStaff->staff_name = $get_Staff->name;
                        $AssignedStaff->staff_code = $get_Staff->StaffCode;
                    } else {
                        $AssignedStaff->staff_name = null;
                        $AssignedStaff->staff_code = null;
                    }

                    $get_Class = CourseEnrollMaster::where(['id' => $AssignedStaff->classname])->first();

                    if ($get_Class != '') {

                        $explode = explode('/', $get_Class->enroll_master_number);

                        $get_short_form = ToolsCourse::where(['name' => $explode[1]])->select('short_form')->first();

                        if ($get_short_form != '') {
                            $AssignedStaff->class = $get_short_form->short_form . ' / ' . $explode[3] . ' / ' . $explode[4];
                        } else {
                            $AssignedStaff->class = null;
                        }
                    } else {
                        $AssignedStaff->class = null;
                    }
                }
            }
        }

        $check = 'leave_details';
        $user_id = auth()->user()->id;
        if ($who == 'tech') {
            $staffName = TeachingStaff::where('user_name_id', $user_name_id)->first();
            $department = $staffName->Dept;
            $users = TeachingStaff::where('user_name_id', '!=', $user_id)->select('name', 'user_name_id', 'StaffCode')->get();
        }

        $staffAlter = StaffAlteration::where(['status' => '0', 'to_id' => auth()->user()->id])
            ->get();
        // dd($staffAlter);
        if ($staffAlter->count() > 0) {
            foreach ($staffAlter as $checkings) {
                $checkings->classID = $checkings->classname;
                $checkings->class_period = $checkings->period;
                $checkings->to_id = $checkings->to_id;

                $staffName = TeachingStaff::where('user_name_id', $checkings->from_id)->first();
                if ($staffName) {
                    $checkings->name = $staffName->name;
                    $checkings->staff_code = $staffName->StaffCode;
                }

                $enroll = CourseEnrollMaster::find($checkings->classname);
                if ($enroll) {
                    $className = explode('/', $enroll->enroll_master_number);
                    if (isset($className[1])) {
                        $get_short_form = ToolsCourse::where('name', $className[1])->value('short_form');
                        if ($get_short_form) {
                            $className[1] = $get_short_form;
                        }
                        $checkings->classname = $className[1] . ' / ' . $className[3] . ' / ' . $className[4];
                    }
                }

                //     $checkings->approveButton = "<button class='btn btn-xs btn-primary' type='submit' name='approve' value='$checkings->id'>Approve</button>";
                //     $checkings->rejectButton = "<button class='btn btn-xs btn-danger' type='submit' name='reject' value='$checkings->id'>Reject</button>";
            }
            $checking = $staffAlter;
        } else {
            $checking = [];
        }

        if ($who == 'tech') {
            return view('admin.addLeave.staff_leaveindex', compact('staff', 'check', 'list', 'staff_edit', 'users', 'checking', 'get_AssignedStaff'));
        }
        if ($who == 'non_tech') {
            return view('admin.addLeave.nt_staff_leaveindex', compact('staff', 'check', 'list', 'staff_edit'));
        }
    }

    public function alter_staff(Request $request)
    {
        // dd($request);
        if ($request->data != '') {
            $form_data = $request->data;
            $alter_data = [];
            foreach ($form_data as $id => $data) {

                if ($data[5]['value'] != null && $data[6]['value'] == '') {
                    if ($id == 0) {
                        $delete = DB::table('staff_alteration_request')->where(['from_id' => auth()->user()->id, 'from_date' => $data[4]['value'], 'to_date' => $data[5]['value']])->update([
                            'deleted_at' => Carbon::now(),
                        ]);
                    }
                    $user_id = auth()->user()->id;
                    $check = DB::table('staff_alteration_request')->where(['from_id' => auth()->user()->id, 'from_date' => $data[4]['value'], 'to_date' => $data[5]['value'], 'to_id' => $data[3]['value'], 'period' => $data[2]['value'], 'day' => $data[1]['value'], 'classname' => $data[0]['value']])->first();

                    if ($check != '') {
                        DB::table('staff_alteration_request')->where(['from_id' => auth()->user()->id, 'from_date' => $data[4]['value'], 'to_date' => $data[5]['value'], 'to_id' => $data[3]['value'], 'period' => $data[2]['value'], 'day' => $data[1]['value'], 'classname' => $data[0]['value']])->update([
                            'deleted_at' => null,
                            'status' => '0',
                            'approval' => '0',

                        ]);
                    } else {
                        // dd('hello one');
                        $alterationRequest = StaffAlteration::create([
                            'from_id' => auth()->user()->id,
                            'date' => Carbon::now()->format('Y-m-d'),
                            'from_date' => $data[4]['value'],
                            'to_date' => $data[5]['value'],
                            'status' => '0',
                            'approval' => '0',
                            'read' => '0',
                            'to_id' => $data[3]['value'],
                            'period' => $data[2]['value'],
                            'day' => $data[1]['value'],
                            'classname' => $data[0]['value'],

                        ]);
                        if ($alterationRequest) {
                            // $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                            // $fullUrl = URL::previous();
                            $user = TeachingStaff::where(['user_name_id' => $data[3]['value']])->first();
                            if ($user) {
                                $userAlert = new UserAlert;
                                $userAlert->alert_text = auth()->user()->name . ' Requesting For Alteration';
                                $userAlert->alert_link = null;
                                $userAlert->save();
                                $userAlert->users()->sync($request->input('users', $user->user_name_id));
                            }
                        }
                    }
                } elseif ($data[5]['value'] == null && $data[6]['value'] != '') {
                    // dd('hi');
                    if ($id == 0) {
                        $delete = DB::table('staff_alteration_request')->where(['from_id' => auth()->user()->id, 'from_date' => $data[4]['value'], 'to_date' => $data[4]['value']])->update([
                            'deleted_at' => Carbon::now(),
                        ]);
                    }
                    $user_id = auth()->user()->id;
                    $check = DB::table('staff_alteration_request')->where(['from_id' => auth()->user()->id, 'from_date' => $data[4]['value'], 'to_date' => $data[4]['value'], 'to_id' => $data[3]['value'], 'period' => $data[2]['value'], 'day' => $data[1]['value'], 'classname' => $data[0]['value']])->first();

                    if ($check != '') {
                        DB::table('staff_alteration_request')->where(['from_id' => auth()->user()->id, 'from_date' => $data[4]['value'], 'to_date' => $data[4]['value'], 'to_id' => $data[3]['value'], 'period' => $data[2]['value'], 'day' => $data[1]['value'], 'classname' => $data[0]['value']])->update([
                            'deleted_at' => null,
                            'status' => '0',
                            'approval' => '0',
                        ]);
                    } else {
                        // dd('hello two');

                        $alterationRequest = StaffAlteration::create([
                            'from_id' => auth()->user()->id,
                            'date' => Carbon::parse($first_date)->format('Y-m-d'),
                            'from_date' => $data[4]['value'],
                            'to_date' => $data[4]['value'],
                            'status' => '0',
                            'approval' => '0',
                            'read' => '0',
                            'to_id' => $data[3]['value'],
                            'period' => $data[2]['value'],
                            'day' => $data[1]['value'],
                            'classname' => $data[0]['value'],

                        ]);
                        if ($alterationRequest) {
                            // $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                            // $fullUrl = URL::previous();
                            $user = TeachingStaff::where(['user_name_id' => $data[3]['value']])->first();
                            if ($user) {
                                $userAlert = new UserAlert;
                                $userAlert->alert_text = auth()->user()->name . ' Requesting For Alteration';
                                $userAlert->alert_link = null;
                                $userAlert->save();
                                $userAlert->users()->sync($request->input('users', $user->user_name_id));
                            }
                        }
                    }
                }
            }
            return response()->json(['data' => true]);
        }
    }

    public function staff_update(UpdateHrmRequestLeafRequest $request, HrmRequestLeaf $hrmRequestLeaf, Document $document)
    {
        $role = DB::table('role_user')->where(['user_id' => $request->user_name_id])->first();
        $level = 0;

        if (isset($request->certificate)) {

            $request->validate([
                'certificate' => 'required|image|mimes:jpg,JPG,jpeg,png,PNG,JPEG|max:2048',
            ]);
            $file = $request->file('certificate');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $destinationPath = public_path('uploads'); // Set the destination path

            // Move the uploaded file to the destination manually
            $file->move($destinationPath, $fileName);

            // Set the storage path for further use if needed
            $path = 'uploads/' . $fileName;
        } else {
            $path = '';
        }

        if ($request->from_date != null && $request->from_date != '' && $request->to_date != null && $request->to_date != '') {

            $from_date_month = Carbon::parse($request->from_date)->month;
            $to_date_month = Carbon::parse($request->to_date)->month;

            if ($from_date_month != $to_date_month) {

                $f_date = Carbon::parse($request->from_date);

                $t_date = Carbon::parse($request->to_date);

                $f_daysInMonth = $f_date->daysInMonth;

                $remainingDays = ($f_daysInMonth - $f_date->day) + 1;

                $t_daysInMonth = ($f_date->diffInDays($t_date) + 1) - $remainingDays;

                $diffInDays_f_month = $remainingDays;

                $diffInDays_t_month = $t_daysInMonth;
            } else {
                $date1 = Carbon::parse($request->from_date);
                $date2 = Carbon::parse($request->to_date);

                $diffInDays_f_month = $date1->diffInDays($date2) + 1;
                $diffInDays_t_month = 0;
            }
        } elseif ($request->half_day_leave != '' && $request->noon != '') {
            $diffInDays_f_month = 0.5;
            $diffInDays_t_month = 0;
        } elseif ($request->off_date != '' && $request->alter_date != '') {
            $diffInDays_f_month = null;
            $diffInDays_t_month = null;
        } else {
            return back()->with('error', 'All Datas Required');
        }

        $assigned_staff = [];

        $explode = explode(',', $request->assign_staff);

        if (count($explode) > 0) {
            foreach ($explode as $data) {
                if ($data != '') {
                    $get_Staff = TeachingStaff::where(['user_name_id' => $data])->first();

                    if ($get_Staff != '') {
                        array_push($assigned_staff, $get_Staff->name . '(' . $get_Staff->StaffCode . ')');
                    }
                }
            }
        }

        $json_data = json_encode($assigned_staff, true);

        if (!$request->id == 0 || $request->id != '') {
            $leave_reqg = $hrmRequestLeaf->where(['user_id' => $request->user_name_id, 'id' => $request->id])->first();
            if ($leave_reqg) {
                $filePath = public_path(!empty($leave_reqg->certificate) ? $leave_reqg->certificate : '');
                // dd($filePath);
                if ($filePath && file_exists($filePath) && is_file($filePath) && is_readable($filePath)) {
                    unlink($filePath);
                }
            }
            $getcl = TeachingStaff::where(['user_name_id' => $request->user_name_id])->select('casual_leave')->first();
            if ($getcl == '') {
                $getcl = NonTeachingStaff::where(['user_name_id' => $request->user_name_id])->select('casual_leave')->first();
                $level = 1;
                $checkRole = auth()->user()->roles[0]->id;
                if ($checkRole == 33 || $checkRole == 34) {
                    $level = 0;
                }
            } else {
                $checkRole = auth()->user()->roles[0]->id;
                if ($checkRole == 50) {
                    if ($request->leave_type == 1 || $request->leave_type == 5) {
                        $level = 1;
                    } else {
                        $level = 95;
                    }
                } else if ($checkRole == 51 || $checkRole == 52) {
                    $level = 1;
                }
            }
            if ($getcl != '') {
                $balance_cl = $getcl->casual_leave;
            } else {
                $balance_cl = null;
            }
            $leave_req = $hrmRequestLeaf->where(['user_id' => $request->user_name_id, 'id' => $request->id])->update([
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'off_date' => $request->off_date,
                'alter_date' => $request->alter_date,
                'half_day_leave' => $request->half_day_leave,
                'noon' => $request->noon,
                'subject' => $request->subject,
                'reason' => $request->reason,
                'level' => $level,
                'leave_type' => $request->leave_type,
                'total_days' => $diffInDays_f_month,
                'assigning_staff' => $json_data,
                'total_days_nxt_mn' => $diffInDays_t_month,
                'certificate' => $path,
                'balance_cl' => $balance_cl,
            ]);
        } else {
            $leave_req = false;
        }

        if ($leave_req) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

            $checkLeaveType = LeaveType::where(['id' => $request->leave_type])->first();
            if ($checkLeaveType != '') {
                $leaveType = $checkLeaveType->name;
            } else {
                $leaveType = '';
            }
            if ($request->leave_type == '2' || $request->leave_type == '3' || $request->leave_type == '4' || $request->leave_type == '6' || $request->leave_type == '7' || $request->leave_type == '8') {
                $alertReceiver = DB::table('role_user')->whereIn('role_id', [1, 13, 15])->get();
            } else {
                $alertReceiver = DB::table('role_user')->whereIn('role_id', [1, 13])->get();
            }
            $receiverArray = [];
            $checkDeptOfStaff = TeachingStaff::where(['user_name_id' => $request->user_name_id])->first();
            if ($checkDeptOfStaff != '') {
                $dept = $checkDeptOfStaff->Dept;
                $getHOD = DB::table('role_user')->where(['role_id' => 14])->get();
                if (count($getHOD) > 0) {
                    foreach ($getHOD as $hod) {
                        $checkUser = User::where(['id' => $hod->user_id, 'dept' => $dept])->select('id')->get();
                        if (count($checkUser) > 0) {
                            foreach ($checkUser as $user) {
                                array_push($receiverArray, $user->id);
                            }
                        }
                    }
                }
            }

            if (count($alertReceiver) > 0) {
                foreach ($alertReceiver as $receiver) {
                    array_push($receiverArray, $receiver->user_id);
                }
            }
            $userAlert = new UserAlert;
            $userAlert->alert_text = auth()->user()->name . ' Updated a ' . $leaveType . ' Request';
            $userAlert->alert_link = url('admin/hrm-request-leaves/' . $request->id);
            $userAlert->save();
            $userAlert->users()->sync($receiverArray);
        } else {
            $getcl = TeachingStaff::where(['user_name_id' => $request->user_name_id])->select('casual_leave')->first();
            if ($getcl == '') {
                $getcl = NonTeachingStaff::where(['user_name_id' => $request->user_name_id])->select('casual_leave')->first();
                $level = 1;
                $checkRole = auth()->user()->roles[0]->id;
                if ($checkRole == 33 || $checkRole == 34) {
                    $level = 0;
                }
            } else {
                $checkRole = auth()->user()->roles[0]->id;
                if ($checkRole == 50) {
                    if ($request->leave_type == 1 || $request->leave_type == 5) {
                        $level = 1;
                    } else {
                        $level = 95;
                    }
                } else if ($checkRole == 51) {
                    $level = 1;
                }
            }
            if ($getcl != '') {
                $balance_cl = $getcl->casual_leave;
            } else {
                $balance_cl = null;
            }

            $hrm_leave_req = new HrmRequestLeaf;
            $hrm_leave_req->reason = $request->reason;
            $hrm_leave_req->subject = $request->subject;
            $hrm_leave_req->status = 'Pending';
            if ($path != '') {
                $hrm_leave_req->certificate = $path;
            }
            $hrm_leave_req->leave_type = $request->leave_type;
            $hrm_leave_req->level = $level;
            $hrm_leave_req->from_date = $request->from_date;
            $hrm_leave_req->to_date = $request->to_date;
            $hrm_leave_req->off_date = $request->off_date;
            $hrm_leave_req->alter_date = $request->alter_date;
            $hrm_leave_req->half_day_leave = $request->half_day_leave;
            $hrm_leave_req->noon = $request->noon;
            $hrm_leave_req->total_days = $diffInDays_f_month;
            $hrm_leave_req->total_days_nxt_mn = $diffInDays_t_month;
            $hrm_leave_req->assigning_staff = $json_data;
            $hrm_leave_req->user_id = $request->user_name_id;
            $hrm_leave_req->balance_cl = $balance_cl;
            $hrm_leave_req->save();

            if ($hrm_leave_req) {

                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

                $checkLeaveType = LeaveType::where(['id' => $request->leave_type])->first();
                if ($checkLeaveType != '') {
                    $leaveType = $checkLeaveType->name;
                } else {
                    $leaveType = '';
                }
                if ($request->leave_type == '2' || $request->leave_type == '3' || $request->leave_type == '4' || $request->leave_type == '6' || $request->leave_type == '7' || $request->leave_type == '8') {
                    $alertReceiver = DB::table('role_user')->whereIn('role_id', [1, 13, 15])->get();
                } else {
                    $alertReceiver = DB::table('role_user')->whereIn('role_id', [1, 13])->get();
                }
                $receiverArray = [];
                $checkDeptOfStaff = TeachingStaff::where(['user_name_id' => $request->user_name_id])->first();
                if ($checkDeptOfStaff != '') {
                    $dept = $checkDeptOfStaff->Dept;
                    $getHOD = DB::table('role_user')->where(['role_id' => 14])->get();
                    if (count($getHOD) > 0) {
                        foreach ($getHOD as $hod) {
                            $checkUser = User::where(['id' => $hod->user_id, 'dept' => $dept])->select('id')->get();
                            if (count($checkUser) > 0) {
                                foreach ($checkUser as $user) {
                                    array_push($receiverArray, $user->id);
                                }
                            }
                        }
                    }
                }

                if (count($alertReceiver) > 0) {
                    foreach ($alertReceiver as $receiver) {
                        array_push($receiverArray, $receiver->user_id);
                    }
                }
                // dd($receiverA  rray);
                $userAlert = new UserAlert;
                $userAlert->alert_text = auth()->user()->name . ' Applied a ' . $leaveType;
                $userAlert->alert_link = url('admin/hrm-request-leaves/' . $hrm_leave_req->id);
                $userAlert->save();
                $userAlert->users()->sync($receiverArray);

                // dd($staff);
            } else {
                dd('Error');
            }
        }

        return redirect()->route('admin.staff-request-leaves.staff_index', $staff);
    }

    public function update_hr(Request $request)
    {

        $who = auth()->user()->roles[0]->id;
        $mannualStatus = '';
        if ($request->data['id'] != '') {
            $check_hr = HrmRequestLeaf::where(['id' => $request->data['id']])->first();
            $checkLeaveType = LeaveType::where(['id' => $check_hr->leave_type])->first();
            if ($checkLeaveType != '') {
                $leaveType = $checkLeaveType->name;
            } else {
                $leaveType = '';
            }

            if (($who == 14 || $who == 42) && $check_hr->level == 0) {
                if ($request->data['status'] == 'Approved') {

                    $action = HrmRequestLeaf::where(['id' => $request->data['id']])->update([
                        'level' => 1,
                        'approved_by' => auth()->user()->name,
                    ]);

                    $update_level = 1;
                } elseif ($request->data['status'] == 'Rejected') {

                    $updatedAction = HrmRequestLeaf::find($request->data['id']);

                    if ($updatedAction != '') {
                        $from_date = $updatedAction->from_date;
                        $to_date = $updatedAction->to_date;
                        $from_id = $updatedAction->user_id;
                    } else {
                        $from_date = null;
                        $to_date = null;
                        $from_id = null;
                    }

                    if ($from_id != null) {
                        $get_alterData = StaffAlteration::where(['from_id' => $from_id, 'from_date' => $from_date, 'to_date' => $to_date])->get();

                        if (count($get_alterData) > 0) {
                            foreach ($get_alterData as $data) {

                                $updateAlteration = StaffAlteration::where(['id' => $data['id']])->update(['status' => '2', 'approval' => '2']);
                                $updateAlterRegister = StaffAlterationRegister::where(['from_date' => $data['from_date'], 'to_date' => $data['to_date'], 'staff_id' => $data['from_id'], 'alter_staffid' => $data['to_id'], 'period' => $data['period'], 'day' => $data['day'], 'class_name' => $data['classname']])->update([
                                    'deleted_at' => Carbon::now(),
                                ]);
                            }
                        }
                    } else {
                        return response()->json(['status' => 'Technical Error']);
                    }

                    $action = HrmRequestLeaf::where(['id' => $request->data['id']])->update([
                        'level' => 0,
                        'status' => $request->data['status'],
                        'rejected_reason' => $request->data['rejected_reason'],
                        'approved_by' => auth()->user()->name,
                    ]);

                    $update_level = 0;

                    $userAlert = new UserAlert;
                    $userAlert->alert_text = 'HOD Rejected Your ' . $leaveType . ' Request';
                    $userAlert->alert_link = url('admin/staff-request-leaves/staff_index');
                    $userAlert->save();
                    $userAlert->users()->sync($updatedAction->user_id);
                } elseif ($request->data['status'] == 'NeedClarification') {
                    $updatedAction = HrmRequestLeaf::find($request->data['id']);

                    if ($updatedAction != '') {
                        $from_date = $updatedAction->from_date;
                        $to_date = $updatedAction->to_date;
                        $from_id = $updatedAction->user_id;
                    } else {
                        $from_date = null;
                        $to_date = null;
                        $from_id = null;
                    }

                    if ($from_id != null) {
                        $get_alterData = StaffAlteration::where(['from_id' => $from_id, 'from_date' => $from_date, 'to_date' => $to_date])->get();

                        if (count($get_alterData) > 0) {
                            foreach ($get_alterData as $data) {

                                $updateAlteration = StaffAlteration::where(['id' => $data['id']])->update(['status' => '2', 'approval' => '2']);
                                $updateAlterRegister = StaffAlterationRegister::where(['from_date' => $data['from_date'], 'to_date' => $data['to_date'], 'staff_id' => $data['from_id'], 'alter_staffid' => $data['to_id'], 'period' => $data['period'], 'day' => $data['day'], 'class_name' => $data['classname']])->update([
                                    'deleted_at' => Carbon::now(),
                                ]);
                            }
                        }
                    } else {
                        return response()->json(['status' => 'Technical Error']);
                    }

                    $action = HrmRequestLeaf::where(['id' => $request->data['id']])->update([
                        'level' => 99,
                        'status' => $request->data['status'],
                        'clarification_reason' => $request->data['clarification_reason'],
                        'approved_by' => auth()->user()->name,
                    ]);

                    $userAlert = new UserAlert;
                    $userAlert->alert_text = 'HOD Need Clarification For Your ' . $leaveType . ' Request';
                    $userAlert->alert_link = url('admin/staff-request-leaves/staff_index');
                    $userAlert->save();
                    $userAlert->users()->sync($updatedAction->user_id);
                }
            } elseif ($who == 15 || $who == 1 || $who == 13) {
                if ($request->data['status'] == 'Approved') {
                    $update_level = 2;
                    $mannualStatus = $request->data['status'];
                    if ($who == 15) {
                        $update_level = 95;
                        $mannualStatus = 'Pending';
                    }
                } elseif ($request->data['status'] == 'NeedClarification') {
                    $update_level = 99;
                    $mannualStatus = $request->data['status'];
                    $mannualClarification = $request->data['clarification_reason'];
                } else {
                    $update_level = 0;
                    $mannualStatus = $request->data['status'];

                    $updatedAction = HrmRequestLeaf::find($request->data['id']);

                    if ($updatedAction != '') {
                        $from_date = $updatedAction->from_date;
                        $to_date = $updatedAction->to_date;
                        $from_id = $updatedAction->user_id;

                        if ($from_date == null) {
                            $from_date = $updatedAction->half_day_leave;
                            $to_date = $updatedAction->half_day_leave;
                        }
                    } else {
                        $from_date = null;
                        $to_date = null;
                        $from_id = null;
                    }

                    if ($from_id != null) {
                        $get_alterData = StaffAlteration::where(['from_id' => $from_id, 'from_date' => $from_date, 'to_date' => $to_date])->get();
                        //    dd($get_alterData);
                        foreach ($get_alterData as $data) {

                            $updateAlteration = StaffAlteration::where(['id' => $data['id']])->update(['status' => '2', 'approval' => '2']);
                            $updateAlterRegister = StaffAlterationRegister::where(['from_date' => $data['from_date'], 'to_date' => $data['to_date'], 'staff_id' => $data['from_id'], 'alter_staffid' => $data['to_id'], 'period' => $data['period'], 'day' => $data['day'], 'class_name' => $data['classname']])->update([
                                'deleted_at' => Carbon::now(),
                            ]);
                        }
                    } else {
                        return response()->json(['status' => 'Technical Error']);
                    }
                }

                if (!isset($mannualClarification)) {
                    $mannualClarification = null;
                }
                if (isset($request->data['rejected_reason'])) {
                    $manualRejectReason = $request->data['rejected_reason'];
                } else {
                    $manualRejectReason = null;
                }

                $update_hr = HrmRequestLeaf::where(['id' => $request->data['id']])->update(['level' => $update_level, 'status' => $mannualStatus, 'approved_by' => auth()->user()->name, 'rejected_reason' => $manualRejectReason, 'clarification_reason' => $mannualClarification]);
                // $update_hr = true; // Test
                if ($update_hr) {
                    $get = HrmRequestLeaf::where(['id' => $request->data['id']])->first();
                    if ($get->status == 'NeedClarification') {
                        $userAlert = new UserAlert;
                        $userAlert->alert_text = $who . ' Need Clarification For Your ' . $leaveType . ' Request';
                        $userAlert->alert_link = url('admin/staff-request-leaves/staff_index');
                        $userAlert->save();
                        $userAlert->users()->sync($get->user_id);
                    } else if ($get->status == 'Approved') {
                        $userAlert = new UserAlert;
                        $userAlert->alert_text = $who . ' Approved Your ' . $leaveType . ' Request';
                        $userAlert->alert_link = url('admin/staff-request-leaves/staff_index');
                        $userAlert->save();
                        $userAlert->users()->sync($get->user_id);
                    } else if ($get->status == 'Rejected') {
                        $userAlert = new UserAlert;
                        $userAlert->alert_text = $who . ' Rejected Your ' . $leaveType . ' Request';
                        $userAlert->alert_link = url('admin/staff-request-leaves/staff_index');
                        $userAlert->save();
                        $userAlert->users()->sync($get->user_id);
                    }

                    $get_from_date = Carbon::parse($get->from_date)->month;
                    $get_to_date = Carbon::parse($get->to_date)->month;
                    // $get->status = 'Approved'; // Test
                    if ($get->status == 'Approved' && auth()->user()->roles[0]->id == 13) {
                        $teaching_staff_get = TeachingStaff::where(['user_name_id' => $get->user_id])->select('casual_leave', 'past_casual_leave', 'subtracted_cl', 'casual_leave_taken', 'user_name_id')->first();

                        if ($teaching_staff_get == '' || $teaching_staff_get == null) {
                            $non_tech_staff = NonTeachingStaff::where(['user_name_id' => $get->user_id])->select('casual_leave', 'past_casual_leave', 'subtracted_cl', 'casual_leave_taken', 'user_name_id')->first();
                            $teaching_staff_get = $non_tech_staff;
                        }
                        $first_date = $get->from_date;
                        $last_date = $get->to_date;

                        if ($get->leave_type == 1) {
                            $forward = false;
                            $getDOJ = ExperienceDetail::where(['user_name_id' => $get->user_id])->select('doj')->first();
                            if ($getDOJ != '' && $getDOJ->doj != null) {
                                $theDoj = Carbon::parse($getDOJ->doj);
                                $month = $theDoj->month;
                                $year = $theDoj->year;
                                if ($month < 12) {
                                    $theMonth = $month + 1;
                                    $theYear = $year;
                                } else {
                                    $theMonth = 1;
                                    $theYear = $year + 1;
                                }
                                $checkableMonth = $theYear . '-' . $theMonth;
                                $nextMonth25th = Carbon::parse($checkableMonth)->day(25);
                                $dateToCheck = Carbon::now();
                                if ($dateToCheck->gt($nextMonth25th)) {
                                    $forward = true;
                                } else {
                                    $forward = false;
                                }
                            } else {
                                $forward = true;
                            }
                            if ($forward == true) {
                                if ($teaching_staff_get->casual_leave > 0 || $teaching_staff_get->past_casual_leave > 0) {
                                    $leaveDaysCurrent = 0;
                                    $leaveDaysPast = 0;
                                    $thisMonth = false;
                                    $pastMonth = false;
                                    $lateApprove = false;
                                    $leave_days = 0;
                                    $halfDay = false;
                                    if ($first_date == null) {
                                        $first_date = $get->half_day_leave;
                                        $last_date = $get->half_day_leave;
                                        $halfDay = true;
                                    }
                                    $fromMonth = Carbon::parse($first_date)->month;
                                    $fromYear = Carbon::parse($first_date)->year;
                                    $toMonth = Carbon::parse($last_date)->month;
                                    $toYear = Carbon::parse($last_date)->year;
                                    $currentMonth = Carbon::now()->month;
                                    $currentYear = Carbon::now()->year;
                                    $currentDate = (int) Carbon::now()->format('d');
                                    // $currentDate = 25;
                                    $getFromDate = (int) Carbon::parse($first_date)->format('d');
                                    $getToDate = (int) Carbon::parse($last_date)->format('d');
                                    $month = Carbon::parse($first_date)->month;

                                    $leaveInCurrent = 0;
                                    $leaveInPast = 0;
                                    $pastDates = [];
                                    $currentDates = [];

                                    if ($fromMonth == $toMonth) {

                                        if ($fromMonth == $currentMonth) {
                                            if ($getFromDate <= 25 && $getToDate <= 25) {
                                                if ($currentDate > 25) {
                                                    $dates = Carbon::parse($first_date)->daysUntil($last_date);
                                                    foreach ($dates as $date) {
                                                        array_push($pastDates, $date->format('Y-m-d'));
                                                    }
                                                    $leaveInPast += $check_hr->total_days;
                                                    $thisMonth = true;
                                                } else {
                                                    $dates = Carbon::parse($first_date)->daysUntil($last_date);
                                                    foreach ($dates as $date) {
                                                        array_push($currentDates, $date->format('Y-m-d'));
                                                    }
                                                    $leaveInCurrent += $check_hr->total_days;
                                                }
                                            } elseif ($getFromDate > 25 && $getToDate > 25) {
                                                if ($currentDate > 25) {
                                                    $dates = Carbon::parse($first_date)->daysUntil($last_date);
                                                    foreach ($dates as $date) {
                                                        array_push($currentDates, $date->format('Y-m-d'));
                                                    }
                                                    $leaveInCurrent += $check_hr->total_days;
                                                }
                                            } else {
                                                $theFromLeaveDays = (25 - $getFromDate) + 1;
                                                $theToLeaveDays = $getToDate - 25;
                                                if ($theFromLeaveDays > 0) {
                                                    if ($getFromDate > 25) {
                                                        if ($currentDate > 25) {
                                                            $leaveInCurrent += $theFromLeaveDays;
                                                            for ($i = $getFromDate; $i <= 25; $i++) {
                                                                $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                                $formattedDate = $makeDate->format('Y-m-d');
                                                                array_push($currentDates, $formattedDate);
                                                            }
                                                        } else {
                                                            $thisMonth = true;
                                                            $leaveInPast += $theFromLeaveDays;
                                                            for ($i = $getFromDate; $i <= 25; $i++) {
                                                                $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                                $formattedDate = $makeDate->format('Y-m-d');
                                                                array_push($pastDates, $formattedDate);
                                                            }
                                                        }
                                                    } else {
                                                        if ($currentDate > 25) {
                                                            $thisMonth = true;
                                                            $leaveInPast += $theFromLeaveDays;
                                                            for ($i = $getFromDate; $i <= 25; $i++) {
                                                                $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                                $formattedDate = $makeDate->format('Y-m-d');
                                                                array_push($pastDates, $formattedDate);
                                                            }
                                                        } else {
                                                            $leaveInCurrent += $theFromLeaveDays;
                                                            for ($i = $getFromDate; $i <= 25; $i++) {
                                                                $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                                $formattedDate = $makeDate->format('Y-m-d');
                                                                array_push($currentDates, $formattedDate);
                                                            }
                                                        }
                                                    }
                                                }
                                                if ($theToLeaveDays > 0) {
                                                    if ($currentDate > 25) {
                                                        $leaveInCurrent += $theToLeaveDays;
                                                        for ($i = 26; $i <= $getToDate; $i++) {
                                                            $makeDate = Carbon::createFromDate($toYear, $toMonth, $i);
                                                            $formattedDate = $makeDate->format('Y-m-d');
                                                            array_push($currentDates, $formattedDate);
                                                        }
                                                    }
                                                }
                                            }
                                        } else if ((int) $fromMonth < (int) $currentMonth) {
                                            if ($fromYear <= $currentYear) {

                                                if ($getFromDate <= 25 && $getToDate <= 25) {
                                                    $pastMonth = true;
                                                    $leaveInPast += $check_hr->total_days;
                                                    $dates = Carbon::parse($first_date)->daysUntil($last_date);
                                                    foreach ($dates as $date) {
                                                        array_push($pastDates, $date->format('Y-m-d'));
                                                    }
                                                } elseif ($getFromDate > 25 && $getToDate > 25) {
                                                    if ($currentDate > 25) {
                                                        $thisMonth = true;
                                                        $leaveInPast += $check_hr->total_days;
                                                        $dates = Carbon::parse($first_date)->daysUntil($last_date);
                                                        foreach ($dates as $date) {
                                                            array_push($pastDates, $date->format('Y-m-d'));
                                                        }
                                                    } else {
                                                        $leaveInCurrent += $check_hr->total_days;
                                                        $dates = Carbon::parse($first_date)->daysUntil($last_date);
                                                        foreach ($dates as $date) {
                                                            array_push($currentDates, $date->format('Y-m-d'));
                                                        }
                                                    }
                                                } else {
                                                    $theFromLeaveDays = (25 - $getFromDate) + 1;
                                                    $theToLeaveDays = $getToDate - 25;

                                                    if ($theFromLeaveDays > 0) {
                                                        if ($getFromDate > 25) {
                                                            if ($currentDate > 25) {
                                                                $thisMonth = true;
                                                                $leaveInPast += $theFromLeaveDays;
                                                                for ($i = $getFromDate; $i <= 25; $i++) {
                                                                    $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                                    $formattedDate = $makeDate->format('Y-m-d');
                                                                    array_push($pastDates, $formattedDate);
                                                                }
                                                            } else {
                                                                $leaveInCurrent += $theFromLeaveDays;
                                                                for ($i = $getFromDate; $i <= 25; $i++) {
                                                                    $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                                    $formattedDate = $makeDate->format('Y-m-d');
                                                                    array_push($currentDates, $formattedDate);
                                                                }
                                                            }
                                                        } else {
                                                            // if ($currentDate > 25) {
                                                            $pastMonth = true;
                                                            // } else {
                                                            //     $thisMonth = true;
                                                            // }

                                                            $leaveInPast += $theFromLeaveDays;
                                                            for ($i = $getFromDate; $i <= 25; $i++) {
                                                                $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                                $formattedDate = $makeDate->format('Y-m-d');
                                                                array_push($pastDates, $formattedDate);
                                                            }
                                                        }
                                                    } elseif ($theFromLeaveDays == 0) {
                                                        dd('Wrong');
                                                    }
                                                    if ($theToLeaveDays > 0) {
                                                        if ($currentDate <= 25) {
                                                            $leaveInCurrent += $theToLeaveDays;
                                                            for ($i = 26; $i <= $getToDate; $i++) {
                                                                $makeDate = Carbon::createFromDate($toYear, $toMonth, $i);
                                                                $formattedDate = $makeDate->format('Y-m-d');
                                                                array_push($currentDates, $formattedDate);
                                                            }
                                                        }
                                                    }
                                                }
                                            } else {
                                                if ($getFromDate <= 25 && $getToDate <= 25 && $currentDate > 25) {
                                                    $leaveInPast += $check_hr->total_days;
                                                    $pastMonth = true;
                                                    for ($i = $getFromDate; $i <= $getToDate; $i++) {
                                                        $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($pastDates, $formattedDate);
                                                    }
                                                } else if ($getFromDate > 25 && $getToDate > 25 && $currentDate < 25) {
                                                    $leaveInCurrent += $check_hr->total_days;
                                                    $thisMonth = true;
                                                    for ($i = $getFromDate; $i <= $getToDate; $i++) {
                                                        $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($pastDates, $formattedDate);
                                                    }
                                                } else {
                                                    $theFromLeaveDays = (25 - $getFromDate) + 1;
                                                    $theToLeaveDays = $getToDate - 25;

                                                    if ($theFromLeaveDays > 0) {
                                                        if ($getFromDate > 25) {
                                                            if ($currentDate > 25) {
                                                                $thisMonth = true;
                                                                $leaveInPast += $theFromLeaveDays;
                                                                for ($i = $getFromDate; $i <= 25; $i++) {
                                                                    $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                                    $formattedDate = $makeDate->format('Y-m-d');
                                                                    array_push($pastDates, $formattedDate);
                                                                }
                                                            } else {
                                                                $leaveInCurrent += $theFromLeaveDays;
                                                                for ($i = $getFromDate; $i <= 25; $i++) {
                                                                    $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                                    $formattedDate = $makeDate->format('Y-m-d');
                                                                    array_push($currentDates, $formattedDate);
                                                                }
                                                            }
                                                        } else {
                                                            // if ($currentDate > 25) {
                                                            $pastMonth = true;
                                                            // } else {
                                                            // $thisMonth = true;
                                                            // }
                                                            $leaveInPast += $theFromLeaveDays;
                                                            for ($i = $getFromDate; $i <= 25; $i++) {
                                                                $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                                $formattedDate = $makeDate->format('Y-m-d');
                                                                array_push($pastDates, $formattedDate);
                                                            }
                                                        }
                                                    } elseif ($theFromLeaveDays == 0) {
                                                        dd('Wrong');
                                                    }
                                                    if ($theToLeaveDays > 0) {
                                                        if ($currentDate <= 25) {
                                                            $leaveInCurrent += $theToLeaveDays;
                                                            for ($i = 26; $i <= $getToDate; $i++) {
                                                                $makeDate = Carbon::createFromDate($toYear, $toMonth, $i);
                                                                $formattedDate = $makeDate->format('Y-m-d');
                                                                array_push($currentDates, $formattedDate);
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        } else if ((int) $fromMonth > (int) $currentMonth) {
                                            if ($fromYear < $currentYear) {
                                                // dd($getFromDate > 25 && $getToDate > 25);
                                                if ($getFromDate <= 25 && $getToDate <= 25) {
                                                    $pastMonth = true;
                                                    $leaveInPast += $check_hr->total_days;
                                                    $dates = Carbon::parse($first_date)->daysUntil($last_date);
                                                    foreach ($dates as $date) {
                                                        array_push($pastDates, $date->format('Y-m-d'));
                                                    }
                                                } elseif ($getFromDate > 25 && $getToDate > 25) {
                                                    if ($currentDate > 25) {
                                                        $thisMonth = true;
                                                        $leaveInPast += $check_hr->total_days;
                                                        $dates = Carbon::parse($first_date)->daysUntil($last_date);
                                                        foreach ($dates as $date) {
                                                            array_push($pastDates, $date->format('Y-m-d'));
                                                        }
                                                    } else {
                                                        $leaveInCurrent += $check_hr->total_days;
                                                        $dates = Carbon::parse($first_date)->daysUntil($last_date);
                                                        foreach ($dates as $date) {
                                                            array_push($currentDates, $date->format('Y-m-d'));
                                                        }
                                                    }
                                                } else {
                                                    $theFromLeaveDays = (25 - $getFromDate) + 1;
                                                    $theToLeaveDays = $getToDate - 25;

                                                    if ($theFromLeaveDays > 0) {
                                                        if ($getFromDate > 25) {
                                                            if ($currentDate > 25) {
                                                                $thisMonth = true;
                                                                $leaveInPast += $theFromLeaveDays;
                                                                for ($i = $getFromDate; $i <= 25; $i++) {
                                                                    $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                                    $formattedDate = $makeDate->format('Y-m-d');
                                                                    array_push($pastDates, $formattedDate);
                                                                }
                                                            } else {
                                                                $leaveInCurrent += $theFromLeaveDays;
                                                                for ($i = $getFromDate; $i <= 25; $i++) {
                                                                    $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                                    $formattedDate = $makeDate->format('Y-m-d');
                                                                    array_push($currentDates, $formattedDate);
                                                                }
                                                            }
                                                        } else {
                                                            // if ($currentDate > 25) {
                                                            $pastMonth = true;
                                                            // } else {
                                                            //     $thisMonth = true;
                                                            // }

                                                            $leaveInPast += $theFromLeaveDays;
                                                            for ($i = $getFromDate; $i <= 25; $i++) {
                                                                $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                                $formattedDate = $makeDate->format('Y-m-d');
                                                                array_push($pastDates, $formattedDate);
                                                            }
                                                        }
                                                    } elseif ($theFromLeaveDays == 0) {
                                                        dd('Wrong');
                                                    }
                                                    if ($theToLeaveDays > 0) {
                                                        if ($currentDate <= 25) {
                                                            $leaveInCurrent += $theToLeaveDays;
                                                            for ($i = 26; $i <= $getToDate; $i++) {
                                                                $makeDate = Carbon::createFromDate($toYear, $toMonth, $i);
                                                                $formattedDate = $makeDate->format('Y-m-d');
                                                                array_push($currentDates, $formattedDate);
                                                            }
                                                        }
                                                    }
                                                }
                                            } else if ($fromYear == $currentYear) {
                                                if ($currentDate >= 26) {
                                                    if ($getFromDate <= 25 && $getToDate <= 25) {
                                                        $thisMonth = true;
                                                        $leaveInCurrent += $check_hr->total_days;
                                                        $dates = Carbon::parse($first_date)->daysUntil($last_date);
                                                        foreach ($dates as $date) {
                                                            array_push($currentDates, $date->format('Y-m-d'));
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        if ($fromMonth == $currentMonth) {
                                            if ($getFromDate > 25 && $getToDate <= 25) {
                                                $getLastDay = (int) Carbon::parse($first_date)->lastOfMonth()->format('d');
                                                $theDays = ($getLastDay - (int) $getFromDate) + (int) $getToDate + 1;
                                                if ($currentDate > 25) {
                                                    $leaveInCurrent += $theDays;
                                                    $getDayOfMonth = Carbon::createFromDate($year, $month, 1);
                                                    $daysInMonth = $getDayOfMonth->daysInMonth;
                                                    for ($i = $getFromDate; $i <= $daysInMonth; $i++) {
                                                        $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($currentDates, $formattedDate);
                                                    }
                                                    for ($i = 1; $i <= $getToDate; $i++) {
                                                        $makeDate = Carbon::createFromDate($toYear, $toMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($currentDates, $formattedDate);
                                                    }
                                                }
                                            } elseif ($getFromDate > 25 && $getToDate > 25) {
                                                $getLastDay = (int) Carbon::parse($first_date)->lastOfMonth()->format('d');
                                                $theDays = ($getLastDay - (int) $getFromDate) + 25 + 1;
                                                if ($currentDate > 25) {
                                                    $leaveInCurrent += $theDays;
                                                    for ($i = $getFromDate; $i <= $getLastDay; $i++) {
                                                        $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($currentDates, $formattedDate);
                                                    }
                                                    for ($i = 1; $i <= 25; $i++) {
                                                        $makeDate = Carbon::createFromDate($toYear, $toMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($currentDates, $formattedDate);
                                                    }
                                                }
                                            } elseif ($getFromDate <= 25 && $getToDate <= 25) {
                                                $getLastDay = (int) Carbon::parse($first_date)->lastOfMonth()->format('d');
                                                $theDays = (25 - (int) $getFromDate) + 1;
                                                if ($currentDate > 25) {
                                                    $leaveInPast = $theDays;
                                                    $thisMonth = true;
                                                    $leaveInCurrent += ($getLastDay - 25);
                                                    for ($i = $getFromDate; $i <= 25; $i++) {
                                                        $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($pastDates, $formattedDate);
                                                    }
                                                    for ($i = 26; $i <= $getLastDay; $i++) {
                                                        $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($currentDates, $formattedDate);
                                                    }
                                                    for ($i = 1; $i <= $getToDate; $i++) {
                                                        $makeDate = Carbon::createFromDate($toYear, $toMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($currentDates, $formattedDate);
                                                    }
                                                } else {
                                                    $leaveInCurrent += $theDays;
                                                    for ($i = $getFromDate; $i <= 25; $i++) {
                                                        $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($currentDates, $formattedDate);
                                                    }
                                                }
                                            } elseif ($getFromDate <= 25 && $getToDate > 25) {
                                                $getLastDay = (int) Carbon::parse($first_date)->lastOfMonth()->format('d');
                                                $theDays = (25 - (int) $getFromDate) + 1;
                                                $theBalanceLeaveDays = (int) $getToDate - 25;
                                                if ($currentDate > 25) {
                                                    $leaveInPast = $theDays;
                                                    $thisMonth = true;
                                                    $leaveInCurrent += ($getLastDay - 25) + 25;

                                                    for ($i = $getFromDate; $i <= 25; $i++) {
                                                        $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($pastDates, $formattedDate);
                                                    }
                                                    for ($i = 26; $i <= $getLastDay; $i++) {
                                                        $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($currentDates, $formattedDate);
                                                    }
                                                    for ($i = 1; $i <= 25; $i++) {
                                                        $makeDate = Carbon::createFromDate($toYear, $toMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($currentDates, $formattedDate);
                                                    }
                                                } else {
                                                    $leaveInCurrent += $theDays;
                                                    for ($i = $getFromDate; $i <= 25; $i++) {
                                                        $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($currentDates, $formattedDate);
                                                    }
                                                }
                                            } else {
                                                dd('Wrong');
                                            }
                                        } elseif ($toMonth == $currentMonth) {

                                            if ($getFromDate > 25 && $getToDate <= 25) {
                                                $getLastDay = (int) Carbon::parse($first_date)->lastOfMonth()->format('d');
                                                $theDays = ($getLastDay - (int) $getFromDate) + (int) $getToDate + 1;
                                                if ($currentDate > 25) {
                                                    $thisMonth = true;
                                                    $leaveInPast += $theDays;
                                                    for ($i = $getFromDate; $i <= $getLastDay; $i++) {
                                                        $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($pastDates, $formattedDate);
                                                    }
                                                    for ($i = 1; $i <= $getToDate; $i++) {
                                                        $makeDate = Carbon::createFromDate($toYear, $toMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($pastDates, $formattedDate);
                                                    }
                                                } else {
                                                    $leaveInCurrent += $theDays;
                                                    for ($i = $getFromDate; $i <= $getLastDay; $i++) {
                                                        $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($currentDates, $formattedDate);
                                                    }
                                                    for ($i = 1; $i <= $getToDate; $i++) {
                                                        $makeDate = Carbon::createFromDate($toYear, $toMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($currentDates, $formattedDate);
                                                    }
                                                }
                                            } elseif ($getFromDate > 25 && $getToDate > 25) {
                                                $getLastDay = (int) Carbon::parse($first_date)->lastOfMonth()->format('d');
                                                $theDays = ($getLastDay - (int) $getFromDate) + 25 + 1;
                                                $theBalanceLeaveDays = (int) $getToDate - 25;
                                                if ($currentDate > 25) {
                                                    $leaveInPast += $theDays;
                                                    $thisMonth = true;
                                                    $leaveInCurrent += $theBalanceLeaveDays;
                                                    for ($i = $getFromDate; $i <= $getLastDay; $i++) {
                                                        $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($pastDates, $formattedDate);
                                                    }
                                                    for ($i = 1; $i <= 25; $i++) {
                                                        $makeDate = Carbon::createFromDate($toYear, $toMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($pastDates, $formattedDate);
                                                    }
                                                    for ($i = 26; $i <= $getToDate; $i++) {
                                                        $makeDate = Carbon::createFromDate($toYear, $toMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($currentDates, $formattedDate);
                                                    }
                                                } else {
                                                    $leaveInCurrent += $theDays;
                                                    for ($i = $getFromDate; $i <= $getLastDay; $i++) {
                                                        $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($currentDates, $formattedDate);
                                                    }
                                                    for ($i = 1; $i <= 25; $i++) {
                                                        $makeDate = Carbon::createFromDate($toYear, $toMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($currentDates, $formattedDate);
                                                    }
                                                }
                                            } elseif ($getFromDate <= 25 && $getToDate <= 25) {
                                                $getLastDay = (int) Carbon::parse($first_date)->lastOfMonth()->format('d');
                                                $theDays = (25 - (int) $getFromDate) + 1;
                                                $theBalanceLeaveDays = (int) $getToDate;
                                                if ($currentDate > 25) {
                                                    $pastMonth = true;
                                                    $leaveInPast = $theDays + ($getLastDay - 25) + $theBalanceLeaveDays;

                                                    for ($i = $getFromDate; $i <= $getLastDay; $i++) {
                                                        $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($pastDates, $formattedDate);
                                                    }
                                                    for ($i = 1; $i <= $getToDate; $i++) {
                                                        $makeDate = Carbon::createFromDate($toYear, $toMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($pastDates, $formattedDate);
                                                    }
                                                } else {
                                                    $leaveInPast += $theDays;
                                                    $thisMonth = true;
                                                    $leaveInCurrent += ($getLastDay - 25) + $theBalanceLeaveDays;
                                                    for ($i = $getFromDate; $i <= 25; $i++) {
                                                        $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($pastDates, $formattedDate);
                                                    }
                                                    for ($i = 26; $i <= $getLastDay; $i++) {
                                                        $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($pastDates, $formattedDate);
                                                    }
                                                    for ($i = 1; $i <= $getToDate; $i++) {
                                                        $makeDate = Carbon::createFromDate($toYear, $toMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($currentDates, $formattedDate);
                                                    }
                                                }
                                            } elseif ($getFromDate <= 25 && $getToDate > 25) {
                                                $getLastDay = (int) Carbon::parse($first_date)->lastOfMonth()->format('d');
                                                $theDays = (25 - (int) $getFromDate) + 1;
                                                $theBalanceLeaveDays = (int) $getToDate - 25;
                                                if ($currentDate > 25) {
                                                    $pastMonth = true;
                                                    $leaveInPast = $theDays + ($getLastDay - 25) + 25;
                                                    $leaveInCurrent += $theBalanceLeaveDays;

                                                    for ($i = $getFromDate; $i <= $getLastDay; $i++) {
                                                        $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($pastDates, $formattedDate);
                                                    }
                                                    for ($i = 1; $i <= 25; $i++) {
                                                        $makeDate = Carbon::createFromDate($toYear, $toMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($pastDates, $formattedDate);
                                                    }
                                                    for ($i = 26; $i <= $getToDate; $i++) {
                                                        $makeDate = Carbon::createFromDate($toYear, $toMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($currentDates, $formattedDate);
                                                    }
                                                } else {
                                                    $thisMonth = true;
                                                    $leaveInPast += $theDays;
                                                    $leaveInCurrent += ($getLastDay - 25) + 25;
                                                    for ($i = $getFromDate; $i <= 25; $i++) {
                                                        $makeDate = Carbon::createFromDate($fromYear, $frotmMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($pastDates, $formattedDate);
                                                    }
                                                    for ($i = 26; $i <= $getLastDay; $i++) {
                                                        $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($currentDates, $formattedDate);
                                                    }
                                                    for ($i = 1; $i <= 25; $i++) {
                                                        $makeDate = Carbon::createFromDate($toYear, $toMonth, $i);
                                                        $formattedDate = $makeDate->format('Y-m-d');
                                                        array_push($currentDates, $formattedDate);
                                                    }
                                                }
                                            } else {
                                                dd('Wrong');
                                            }
                                        } elseif ($fromMonth < $currentMonth && $toMonth < $currentMonth) {

                                            $getLastDay = (int) Carbon::parse($first_date)->lastOfMonth()->format('d');
                                            $pastMonth = true;
                                            $theDays = ($getLastDay - (int) $getFromDate) + (int) $getToDate + 1;
                                            $leaveInPast += $theDays;

                                            for ($i = $getFromDate; $i <= $getLastDay; $i++) {
                                                $makeDate = Carbon::createFromDate($fromYear, $fromMonth, $i);
                                                $formattedDate = $makeDate->format('Y-m-d');
                                                array_push($pastDates, $formattedDate);
                                            }
                                            for ($i = 1; $i <= $getToDate; $i++) {
                                                $makeDate = Carbon::createFromDate($toYear, $toMonth, $i);
                                                $formattedDate = $makeDate->format('Y-m-d');
                                                array_push($pastDates, $formattedDate);
                                            }
                                            $lateApprove = true;
                                        }
                                    }
                                    if ($halfDay == true) {
                                        if ($leaveInCurrent > 0) {
                                            $leaveInCurrent = 0.5;
                                        } elseif ($leaveInPast > 0) {
                                            $leaveInPast = 0.5;
                                        }
                                    }

                                    if ($leaveInCurrent > 0 && $leaveInPast == 0) {
                                        if ($fromMonth == $toMonth) {
                                            $dateToCheck_From = Carbon::create(null, $month - 1, 25);
                                            $dateToCheck_FromForHalf = Carbon::create(null, $month - 1, 26);
                                            $dateToCheck_To = Carbon::create(null, $month, 25);
                                        } else {
                                            $dateToCheck_From = Carbon::create(null, $month, 25);
                                            $dateToCheck_FromForHalf = Carbon::create(null, $month, 26);
                                            $dateToCheck_To = Carbon::create(null, $month + 1, 25);
                                        }

                                        $check_the_month = HrmRequestLeaf::where('from_date', '>', $dateToCheck_From)->where('to_date', '<=', $dateToCheck_To)->where(['leave_type' => 1, 'status' => 'Approved', 'user_id' => $check_hr->user_id])->whereNot('id', $check_hr->id)->get();
                                        $check_the_month_1 = HrmRequestLeaf::where('from_date', '<=', $dateToCheck_From)->where('to_date', '>', $dateToCheck_From)->where(['leave_type' => 1, 'status' => 'Approved', 'user_id' => $check_hr->user_id])->whereNot('id', $check_hr->id)->get();
                                        $check_the_month_2 = HrmRequestLeaf::whereBetween('half_day_leave', [$dateToCheck_FromForHalf, $dateToCheck_To])->where(['leave_type' => 1, 'status' => 'Approved', 'user_id' => $check_hr->user_id])->whereNot('id', $check_hr->id)->get();

                                        if (count($check_the_month) > 0) {
                                            foreach ($check_the_month as $req) {
                                                $date1 = Carbon::parse($req->from_date);
                                                $date2 = Carbon::parse($req->to_date);

                                                $diffInDays = $date1->diffInDays($date2) + 1;
                                                $leaveDaysCurrent += $diffInDays;
                                            }
                                        }
                                        if (count($check_the_month_1) > 0) {
                                            foreach ($check_the_month_1 as $req) {
                                                $theToDate = (int) Carbon::parse($req->to_date)->format('d');
                                                $getDays = $theToDate - 25;
                                                $leaveDaysCurrent += $getDays;
                                            }
                                        }
                                        if (count($check_the_month_2) > 0) {
                                            foreach ($check_the_month_2 as $req) {
                                                $leaveDaysCurrent += 0.5;
                                            }
                                        }
                                    } else if ($leaveInCurrent == 0 && $leaveInPast > 0) {

                                        if ($pastMonth == true) {
                                            $dateToCheck_From = Carbon::create(null, $month - 1, 25);
                                            $dateToCheck_FromForHalf = Carbon::create(null, $month - 1, 26);
                                            $dateToCheck_To = Carbon::create(null, $month, 25);
                                        } else {
                                            $dateToCheck_From = Carbon::create(null, $month - 1, 25);
                                            $dateToCheck_FromForHalf = Carbon::create(null, $month - 1, 26);
                                            $dateToCheck_To = Carbon::create(null, $month, 25);
                                        }

                                        $check_the_month = HrmRequestLeaf::where('from_date', '>', $dateToCheck_From)->where('to_date', '<=', $dateToCheck_To)->where(['leave_type' => 1, 'status' => 'Approved', 'user_id' => $check_hr->user_id])->whereNot('id', $check_hr->id)->get();
                                        $check_the_month_1 = HrmRequestLeaf::where('from_date', '<=', $dateToCheck_From)->where('to_date', '>', $dateToCheck_From)->where(['leave_type' => 1, 'status' => 'Approved', 'user_id' => $check_hr->user_id])->whereNot('id', $check_hr->id)->get();
                                        $check_the_month_2 = HrmRequestLeaf::whereBetween('half_day_leave', [$dateToCheck_FromForHalf, $dateToCheck_To])->where(['leave_type' => 1, 'status' => 'Approved', 'user_id' => $check_hr->user_id])->whereNot('id', $check_hr->id)->get();

                                        if (count($check_the_month) > 0) {
                                            foreach ($check_the_month as $req) {
                                                $date1 = Carbon::parse($req->from_date);
                                                $date2 = Carbon::parse($req->to_date);

                                                $diffInDays = $date1->diffInDays($date2) + 1;
                                                $leaveDaysPast += $diffInDays;
                                            }
                                        }
                                        if (count($check_the_month_1) > 0) {
                                            foreach ($check_the_month_1 as $req) {
                                                $theToDate = (int) Carbon::parse($req->to_date)->format('d');
                                                $getDays = $theToDate - 25;
                                                $leaveDaysPast += $getDays;
                                            }
                                        }
                                        if (count($check_the_month_2) > 0) {
                                            foreach ($check_the_month_2 as $req) {
                                                $leaveDaysPast += 0.5;
                                            }
                                        }
                                    } else if ($leaveInCurrent > 0 && $leaveInPast > 0) {

                                        if ($pastMonth == true) {

                                            $dateToCheck_From = Carbon::create(null, $month - 1, 25);
                                            $dateToCheck_FromForHalf = Carbon::create(null, $month - 1, 26);
                                            $dateToCheck_To = Carbon::create(null, $month, 25);
                                            $check_the_month = HrmRequestLeaf::where('from_date', '>', $dateToCheck_From)->where('to_date', '<=', $dateToCheck_To)->where(['leave_type' => 1, 'status' => 'Approved', 'user_id' => $check_hr->user_id])->whereNot('id', $check_hr->id)->get();
                                            $check_the_month_1 = HrmRequestLeaf::where('from_date', '<=', $dateToCheck_From)->where('to_date', '>', $dateToCheck_From)->where(['leave_type' => 1, 'status' => 'Approved', 'user_id' => $check_hr->user_id])->whereNot('id', $check_hr->id)->get();
                                            $check_the_month_2 = HrmRequestLeaf::whereBetween('half_day_leave', [$dateToCheck_FromForHalf, $dateToCheck_To])->where(['leave_type' => 1, 'status' => 'Approved', 'user_id' => $check_hr->user_id])->whereNot('id', $check_hr->id)->get();

                                            if (count($check_the_month) > 0) {
                                                foreach ($check_the_month as $req) {
                                                    $date1 = Carbon::parse($req->from_date);
                                                    $date2 = Carbon::parse($req->to_date);

                                                    $diffInDays = $date1->diffInDays($date2) + 1;
                                                    $leaveDaysPast += $diffInDays;
                                                }
                                            }
                                            if (count($check_the_month_1) > 0) {
                                                foreach ($check_the_month_1 as $req) {
                                                    $theToDate = (int) Carbon::parse($req->to_date)->format('d');
                                                    $getDays = $theToDate - 25;
                                                    $leaveDaysPast += $getDays;
                                                }
                                            }
                                            if (count($check_the_month_2) > 0) {
                                                foreach ($check_the_month_2 as $req) {
                                                    $leaveDaysPast += 0.5;
                                                }
                                            }
                                        } else {

                                            $dateToCheck_From = Carbon::create(null, $month - 1, 25);
                                            $dateToCheck_FromForHalf = Carbon::create(null, $month - 1, 26);
                                            $dateToCheck_To = Carbon::create(null, $month, 25);

                                            $check_the_month = HrmRequestLeaf::where('from_date', '>', $dateToCheck_From)->where('to_date', '<=', $dateToCheck_To)->where(['leave_type' => 1, 'status' => 'Approved', 'user_id' => $check_hr->user_id])->whereNot('id', $check_hr->id)->get();
                                            $check_the_month_1 = HrmRequestLeaf::where('from_date', '<=', $dateToCheck_From)->where('to_date', '>', $dateToCheck_From)->where(['leave_type' => 1, 'status' => 'Approved', 'user_id' => $check_hr->user_id])->whereNot('id', $check_hr->id)->get();
                                            $check_the_month_2 = HrmRequestLeaf::whereBetween('half_day_leave', [$dateToCheck_FromForHalf, $dateToCheck_To])->where(['leave_type' => 1, 'status' => 'Approved', 'user_id' => $check_hr->user_id])->whereNot('id', $check_hr->id)->get();

                                            if (count($check_the_month) > 0) {
                                                foreach ($check_the_month as $req) {
                                                    $date1 = Carbon::parse($req->from_date);
                                                    $date2 = Carbon::parse($req->to_date);

                                                    $diffInDays = $date1->diffInDays($date2) + 1;
                                                    $leaveDaysCurrent += $diffInDays;
                                                }
                                            }
                                            if (count($check_the_month_1) > 0) {
                                                foreach ($check_the_month_1 as $req) {
                                                    $theToDate = (int) Carbon::parse($req->to_date)->format('d');
                                                    $getDays = $theToDate - 25;
                                                    $leaveDaysCurrent += $getDays;
                                                }
                                            }
                                            if (count($check_the_month_2) > 0) {
                                                foreach ($check_the_month_2 as $req) {
                                                    $leaveDaysCurrent += 0.5;
                                                }
                                            }
                                        }
                                    }
                                    if ($leaveDaysCurrent >= 3) {
                                        $clAllocatedForCurrent = '';
                                    } else {
                                        $clAllocatedForCurrent = $leaveDaysCurrent;
                                    }
                                    if ($lateApprove == true) {
                                        $clAllocatedForPast = '';
                                    } else {
                                        if ($leaveDaysPast >= 3) {
                                            $clAllocatedForPast = '';
                                        } else {
                                            $clAllocatedForPast = $leaveDaysPast;
                                        }
                                    }
                                    if ($leaveInPast > 0) {
                                        if ($clAllocatedForPast != 0) {
                                            if ($clAllocatedForPast != '') {
                                                if ($teaching_staff_get->past_casual_leave > 0) {

                                                    if ($leaveInPast >= 3) {

                                                        $check_cl = 3 - $clAllocatedForPast;

                                                        $deduct_cl = $check_cl - $teaching_staff_get->past_casual_leave;

                                                        if ($deduct_cl > 0) {
                                                            $cl = 0;
                                                            $subtracted_cl = $teaching_staff_get->subtracted_cl + $deduct_cl;

                                                            if ($halfDay == false) {
                                                                for ($i = 0; $i < count($pastDates); $i++) {
                                                                    if ($i <= $deduct_cl) {

                                                                        $staff_biometric = StaffBiometric::where(['date' => $pastDates[$i], 'user_name_id' => $teaching_staff_get->user_name_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                        if ($staff_biometric != '') {
                                                                            if ($staff_biometric->details != null) {
                                                                                $tempDetail = $staff_biometric->details;
                                                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                    $tempDetail = 'Casual Leave (CL Provided)';
                                                                                }
                                                                                $staff_biometric->status = 'Absent';
                                                                                $staff_biometric->details = $tempDetail;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->save();
                                                                            } else {
                                                                                $staff_biometric->status = 'Absent';
                                                                                $staff_biometric->details = 'Casual Leave (CL Provided)';
                                                                                $staff_biometric->update_status = 1;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->save();
                                                                            }
                                                                        }
                                                                    } else {
                                                                        $staff_biometric = StaffBiometric::where(['date' => $pastDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                        if ($staff_biometric != '') {
                                                                            if ($staff_biometric->details != null) {
                                                                                $tempDetail = $staff_biometric->details;
                                                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                    $tempDetail = 'Casual Leave';
                                                                                }
                                                                                $staff_biometric->status = 'Absent';
                                                                                $staff_biometric->details = $tempDetail;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->save();
                                                                            } else {
                                                                                $staff_biometric->status = 'Absent';
                                                                                $staff_biometric->details = 'Casual Leave';
                                                                                $staff_biometric->update_status = 1;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->save();
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            } else {
                                                                foreach ($pastDates as $date) {
                                                                    $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            $status = $staff_biometric->status;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                                    if (strpos($staff_biometric->details, ',') !== false) {
                                                                                        $explode = explode(',', $staff_biometric->details);
                                                                                        if (in_array('Late', $explode)) {
                                                                                            $theIndex = array_search('Late', $explode);
                                                                                            unset($explode[$theIndex]);
                                                                                        } else if (in_array('Too Late', $explode)) {
                                                                                            $theIndex = array_search('Too Late', $explode);
                                                                                            unset($explode[$theIndex]);
                                                                                        }
                                                                                        $implode = implode(',', $explode);
                                                                                        $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';
                                                                                    } else {
                                                                                        if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                            $tempDetail = $get->noon . ' Casual Leave (CL Provided)';
                                                                                        }
                                                                                    }

                                                                                } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                                    if (strpos($staff_biometric->details, ',') !== false) {
                                                                                        $explode = explode(',', $staff_biometric->details);
                                                                                        if (in_array('Early Out', $explode)) {
                                                                                            $theIndex = array_search('Early Out', $explode);
                                                                                            unset($explode[$theIndex]);
                                                                                            $status = 'Present';
                                                                                        }
                                                                                        $implode = implode(',', $explode);
                                                                                        $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';
                                                                                    } else {
                                                                                        if ($staff_biometric->details == 'Early Out') {
                                                                                            $tempDetail = $get->noon . ' Casual Leave (CL Provided)';
                                                                                            $status = 'Present';
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                            //$staff_biometric->status = 'Present';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->status = $status;
                                                                            $staff_biometric->save();
                                                                        } else {
                                                                            //$staff_biometric->status = 'Present';
                                                                            $staff_biometric->details = $get->noon . ' Casual Leave (CL Provided)';
                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                }
                                                            }

                                                            $check_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->get();

                                                            if (count($check_teaching_staff) > 0) {
                                                                $teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                    'subtracted_cl' => $subtracted_cl,
                                                                    'past_casual_leave' => $cl,
                                                                    'casual_leave' => $cl,
                                                                ]);
                                                            } else {
                                                                $non_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                    'subtracted_cl' => $subtracted_cl,
                                                                    'past_casual_leave' => $cl,
                                                                    'casual_leave' => $cl,
                                                                ]);
                                                            }
                                                        } else {
                                                            $cl = abs($deduct_cl);
                                                            $subtracted_cl = $teaching_staff_get->subtracted_cl + $check_cl;

                                                            if ($halfDay == false) {
                                                                for ($i = 0; $i < count($pastDates); $i++) {
                                                                    if ($i <= $check_cl) {

                                                                        $staff_biometric = StaffBiometric::where(['date' => $pastDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                        if ($staff_biometric != '') {
                                                                            if ($staff_biometric->details != null) {
                                                                                $tempDetail = $staff_biometric->details;
                                                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                    $tempDetail = 'Casual Leave (CL Provided)';
                                                                                }
                                                                                $staff_biometric->status = 'Absent';
                                                                                $staff_biometric->details = $tempDetail;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->save();
                                                                            } else {

                                                                                $staff_biometric->status = 'Absent';
                                                                                $staff_biometric->details = 'Casual Leave (CL Provided)';
                                                                                $staff_biometric->update_status = 1;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->save();
                                                                            }
                                                                        }
                                                                    } else {

                                                                        $staff_biometric = StaffBiometric::where(['date' => $pastDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                        if ($staff_biometric != '') {
                                                                            if ($staff_biometric->details != null) {
                                                                                $tempDetail = $staff_biometric->details;
                                                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                    $tempDetail = 'Casual Leave';
                                                                                }
                                                                                $staff_biometric->status = 'Absent';
                                                                                $staff_biometric->details = $tempDetail;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->save();
                                                                            } else {

                                                                                $staff_biometric->status = 'Absent';
                                                                                $staff_biometric->details = 'Casual Leave';
                                                                                $staff_biometric->update_status = 1;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->save();
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            } else {
                                                                foreach ($pastDates as $date) {
                                                                    $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            $status = $staff_biometric->status;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                                    if (strpos($staff_biometric->details, ',') !== false) {
                                                                                        $explode = explode(',', $staff_biometric->details);
                                                                                        if (in_array('Late', $explode)) {
                                                                                            $theIndex = array_search('Late', $explode);
                                                                                            unset($explode[$theIndex]);
                                                                                        } else if (in_array('Too Late', $explode)) {
                                                                                            $theIndex = array_search('Too Late', $explode);
                                                                                            unset($explode[$theIndex]);
                                                                                        }
                                                                                        $implode = implode(',', $explode);
                                                                                        $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';
                                                                                    } else {
                                                                                        if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                            $tempDetail = $get->noon . ' Casual Leave (CL Provided)';
                                                                                        }
                                                                                    }

                                                                                } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                                    if (strpos($staff_biometric->details, ',') !== false) {
                                                                                        $explode = explode(',', $staff_biometric->details);
                                                                                        if (in_array('Early Out', $explode)) {
                                                                                            $theIndex = array_search('Early Out', $explode);
                                                                                            unset($explode[$theIndex]);
                                                                                            $status = 'Present';
                                                                                        }
                                                                                        $implode = implode(',', $explode);
                                                                                        $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';
                                                                                    } else {
                                                                                        if ($staff_biometric->details == 'Early Out') {
                                                                                            $tempDetail = $get->noon . ' Casual Leave (CL Provided)';
                                                                                            $status = 'Present';
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                            //$staff_biometric->status = 'Present';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->status = $status;
                                                                            $staff_biometric->save();
                                                                        } else {

                                                                            //$staff_biometric->status = 'Present';
                                                                            $staff_biometric->details = $get->noon . ' Casual Leave (CL Provided)';
                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                }
                                                            }

                                                            $check_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->get();

                                                            if (count($check_teaching_staff) > 0) {
                                                                $teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                    'subtracted_cl' => $subtracted_cl,
                                                                    'past_casual_leave' => $cl,
                                                                    'casual_leave' => $cl,
                                                                ]);
                                                            } else {
                                                                $non_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                    'subtracted_cl' => $subtracted_cl,
                                                                    'past_casual_leave' => $cl,
                                                                    'casual_leave' => $cl,
                                                                ]);
                                                            }
                                                        }
                                                    } else {

                                                        if ($clAllocatedForPast < 3) {

                                                            $deduct_cl = $teaching_staff_get->past_casual_leave - $leaveInPast;

                                                            if ($deduct_cl > 0) {
                                                                $cl = $deduct_cl;
                                                                $subtracted_cl = $teaching_staff_get->subtracted_cl + $leaveInPast;

                                                                if ($halfDay == false) {
                                                                    for ($i = 0; $i < count($pastDates); $i++) {
                                                                        if ($i <= $clAllocatedForPast) {

                                                                            $staff_biometric = StaffBiometric::where(['date' => $pastDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                            if ($staff_biometric != '') {
                                                                                if ($staff_biometric->details != null) {
                                                                                    $tempDetail = $staff_biometric->details;
                                                                                    if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                        $tempDetail = 'Casual Leave (CL Provided)';
                                                                                    }
                                                                                    $staff_biometric->status = 'Absent';
                                                                                    $staff_biometric->details = $tempDetail;
                                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                                    $staff_biometric->save();
                                                                                } else {
                                                                                    $staff_biometric->status = 'Absent';
                                                                                    $staff_biometric->details = 'Casual Leave (CL Provided)';
                                                                                    $staff_biometric->update_status = 1;
                                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                                    $staff_biometric->save();
                                                                                }
                                                                            }
                                                                        } else {
                                                                            $staff_biometric = StaffBiometric::where(['date' => $pastDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                            if ($staff_biometric != '') {
                                                                                if ($staff_biometric->details != null) {
                                                                                    $tempDetail = $staff_biometric->details;
                                                                                    if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                        $tempDetail = 'Casual Leave';
                                                                                    }
                                                                                    $staff_biometric->status = 'Absent';
                                                                                    $staff_biometric->details = $tempDetail;
                                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                                    $staff_biometric->save();
                                                                                } else {
                                                                                    $staff_biometric->status = 'Absent';
                                                                                    $staff_biometric->details = 'Casual Leave';
                                                                                    $staff_biometric->update_status = 1;
                                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                                    $staff_biometric->save();
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                } else {
                                                                    foreach ($pastDates as $date) {

                                                                        $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                        if ($staff_biometric != '') {
                                                                            if ($staff_biometric->details != null) {
                                                                                $tempDetail = $staff_biometric->details;
                                                                                $status = $staff_biometric->status;
                                                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                    if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                                        if (strpos($staff_biometric->details, ',') !== false) {
                                                                                            $explode = explode(',', $staff_biometric->details);
                                                                                            if (in_array('Late', $explode)) {
                                                                                                $theIndex = array_search('Late', $explode);
                                                                                                unset($explode[$theIndex]);
                                                                                            } else if (in_array('Too Late', $explode)) {
                                                                                                $theIndex = array_search('Too Late', $explode);
                                                                                                unset($explode[$theIndex]);
                                                                                            }
                                                                                            $implode = implode(',', $explode);
                                                                                            $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                        } else {
                                                                                            if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                                $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                            }
                                                                                        }

                                                                                    } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                                        if (strpos($staff_biometric->details, ',') !== false) {
                                                                                            $explode = explode(',', $staff_biometric->details);
                                                                                            if (in_array('Early Out', $explode)) {
                                                                                                $theIndex = array_search('Early Out', $explode);
                                                                                                unset($explode[$theIndex]);
                                                                                                $status = 'Present';
                                                                                            }
                                                                                            $implode = implode(',', $explode);
                                                                                            $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                        } else {
                                                                                            if ($staff_biometric->details == 'Early Out') {
                                                                                                $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                                $status = 'Present';
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                                //$staff_biometric->status = 'Present';
                                                                                $staff_biometric->details = $tempDetail;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->status = $status;
                                                                                $staff_biometric->save();
                                                                            } else {
                                                                                //$staff_biometric->status = 'Present';
                                                                                $staff_biometric->details = $get->noon . ' Casual Leave (CL Provided)';

                                                                                $staff_biometric->update_status = 1;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->save();
                                                                            }
                                                                        }
                                                                    }
                                                                }

                                                                $check_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->get();

                                                                $theCl = $teaching_staff_get->casual_leave - $leaveInPast;
                                                                if (count($check_teaching_staff) > 0) {
                                                                    $teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                        'subtracted_cl' => $subtracted_cl,
                                                                        'past_casual_leave' => $cl,
                                                                        'casual_leave' => $theCl,
                                                                    ]);
                                                                } else {
                                                                    $non_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                        'subtracted_cl' => $subtracted_cl,
                                                                        'past_casual_leave' => $cl,
                                                                        'casual_leave' => $theCl,
                                                                    ]);
                                                                }
                                                            } else {
                                                                $cl = 0;
                                                                $subtracted_cl = $teaching_staff_get->subtracted_cl + $teaching_staff_get->past_casual_leave;

                                                                if ($halfDay == false) {
                                                                    for ($i = 0; $i < count($pastDates); $i++) {
                                                                        if ($i <= $teaching_staff_get->past_casual_leave) {
                                                                            $staff_biometric = StaffBiometric::where(['date' => $pastDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                            if ($staff_biometric != '') {
                                                                                if ($staff_biometric->details != null) {
                                                                                    $tempDetail = $staff_biometric->details;
                                                                                    if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                        $tempDetail = 'Casual Leave (CL Provided)';
                                                                                    }
                                                                                    $staff_biometric->status = 'Absent';
                                                                                    $staff_biometric->details = $tempDetail;
                                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                                    $staff_biometric->save();
                                                                                } else {
                                                                                    $staff_biometric->status = 'Absent';
                                                                                    $staff_biometric->details = 'Casual Leave (CL Provided)';
                                                                                    $staff_biometric->update_status = 1;
                                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                                    $staff_biometric->save();
                                                                                }
                                                                            }
                                                                        } else {
                                                                            $staff_biometric = StaffBiometric::where(['date' => $pastDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                            if ($staff_biometric != '') {
                                                                                if ($staff_biometric->details != null) {
                                                                                    $tempDetail = $staff_biometric->details;
                                                                                    if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                        $tempDetail = 'Casual Leave';
                                                                                    }
                                                                                    $staff_biometric->status = 'Absent';
                                                                                    $staff_biometric->details = $tempDetail;
                                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                                    $staff_biometric->save();
                                                                                } else {
                                                                                    $staff_biometric->status = 'Absent';
                                                                                    $staff_biometric->details = 'Casual Leave';
                                                                                    $staff_biometric->update_status = 1;
                                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                                    $staff_biometric->save();
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                } else {
                                                                    foreach ($pastDates as $date) {
                                                                        $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                        if ($staff_biometric != '') {
                                                                            if ($staff_biometric->details != null) {
                                                                                $tempDetail = $staff_biometric->details;
                                                                                $status = $staff_biometric->status;
                                                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                    if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                                        if (strpos($staff_biometric->details, ',') !== false) {
                                                                                            $explode = explode(',', $staff_biometric->details);
                                                                                            if (in_array('Late', $explode)) {
                                                                                                $theIndex = array_search('Late', $explode);
                                                                                                unset($explode[$theIndex]);
                                                                                            } else if (in_array('Too Late', $explode)) {
                                                                                                $theIndex = array_search('Too Late', $explode);
                                                                                                unset($explode[$theIndex]);
                                                                                            }
                                                                                            $implode = implode(',', $explode);
                                                                                            $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';
                                                                                        } else {
                                                                                            if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                                $tempDetail = $get->noon . ' Casual Leave (CL Provided)';
                                                                                            }
                                                                                        }

                                                                                    } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                                        if (strpos($staff_biometric->details, ',') !== false) {
                                                                                            $explode = explode(',', $staff_biometric->details);
                                                                                            if (in_array('Early Out', $explode)) {
                                                                                                $theIndex = array_search('Early Out', $explode);
                                                                                                unset($explode[$theIndex]);
                                                                                                $status = 'Present';
                                                                                            }
                                                                                            $implode = implode(',', $explode);
                                                                                            $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                        } else {
                                                                                            if ($staff_biometric->details == 'Early Out') {
                                                                                                $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                                $status = 'Present';
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                                //$staff_biometric->status = 'Present';
                                                                                $staff_biometric->details = $tempDetail;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->status = $status;
                                                                                $staff_biometric->save();
                                                                            } else {

                                                                                //$staff_biometric->status = 'Present';
                                                                                $staff_biometric->details = $get->noon . ' Casual Leave (CL Provided)';

                                                                                $staff_biometric->update_status = 1;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->save();
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                $check_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->get();

                                                                $theCl = $teaching_staff_get->casual_leave - $teaching_staff_get->past_casual_leave;

                                                                if (count($check_teaching_staff) > 0) {
                                                                    $teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                        'subtracted_cl' => $subtracted_cl,
                                                                        'past_casual_leave' => $cl,
                                                                        'casual_leave' => $theCl,
                                                                    ]);
                                                                } else {
                                                                    $non_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                        'subtracted_cl' => $subtracted_cl,
                                                                        'past_casual_leave' => $cl,
                                                                        'casual_leave' => $theCl,
                                                                    ]);
                                                                }
                                                            }
                                                        } else {

                                                            if ($halfDay == false) {
                                                                foreach ($pastDates as $date) {

                                                                    $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                $tempDetail = 'Casual Leave';
                                                                            }
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        } else {
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = 'Casual Leave';
                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                }
                                                            } else {
                                                                foreach ($pastDates as $date) {
                                                                    $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            $status = $staff_biometric->status;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                                    if (strpos($staff_biometric->details, ',') !== false) {
                                                                                        $explode = explode(',', $staff_biometric->details);
                                                                                        if (in_array('Late', $explode)) {
                                                                                            $theIndex = array_search('Late', $explode);
                                                                                            unset($explode[$theIndex]);
                                                                                        } else if (in_array('Too Late', $explode)) {
                                                                                            $theIndex = array_search('Too Late', $explode);
                                                                                            unset($explode[$theIndex]);
                                                                                        }
                                                                                        $implode = implode(',', $explode);
                                                                                        $tempDetail = $implode . ',' . $get->noon . ' Casual Leave';
                                                                                    } else {
                                                                                        if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                            $tempDetail = $get->noon . ' Casual Leave';
                                                                                        }
                                                                                    }

                                                                                } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                                    if (strpos($staff_biometric->details, ',') !== false) {
                                                                                        $explode = explode(',', $staff_biometric->details);
                                                                                        if (in_array('Early Out', $explode)) {
                                                                                            $theIndex = array_search('Early Out', $explode);
                                                                                            unset($explode[$theIndex]);
                                                                                            $status = 'Present';
                                                                                        }
                                                                                        $implode = implode(',', $explode);
                                                                                        $tempDetail = $implode . ',' . $get->noon . ' Casual Leave';
                                                                                    } else {
                                                                                        if ($staff_biometric->details == 'Early Out') {
                                                                                            $tempDetail = $get->noon . ' Casual Leave';
                                                                                            $status = 'Present';
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                            //$staff_biometric->status = 'Present';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->status = $status;
                                                                            $staff_biometric->save();
                                                                        } else {

                                                                            //$staff_biometric->status = 'Present';
                                                                            $staff_biometric->details = $get->noon . ' Casual Leave';
                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                } else {

                                                    if ($halfDay == false) {
                                                        foreach ($pastDates as $date) {
                                                            $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                            if ($staff_biometric != '') {
                                                                if ($staff_biometric->details != null) {
                                                                    $tempDetail = $staff_biometric->details;
                                                                    if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                        $tempDetail = 'Casual Leave';
                                                                    }
                                                                    $staff_biometric->status = 'Absent';
                                                                    $staff_biometric->details = $tempDetail;
                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                    $staff_biometric->save();
                                                                } else {

                                                                    $staff_biometric->status = 'Absent';
                                                                    $staff_biometric->details = 'Casual Leave';
                                                                    $staff_biometric->update_status = 1;
                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                    $staff_biometric->save();
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        foreach ($pastDates as $date) {
                                                            $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                            if ($staff_biometric != '') {
                                                                if ($staff_biometric->details != null) {
                                                                    $tempDetail = $staff_biometric->details;
                                                                    $status = $staff_biometric->status;
                                                                    if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                        if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                            if (strpos($staff_biometric->details, ',') !== false) {
                                                                                $explode = explode(',', $staff_biometric->details);
                                                                                if (in_array('Late', $explode)) {
                                                                                    $theIndex = array_search('Late', $explode);
                                                                                    unset($explode[$theIndex]);
                                                                                } else if (in_array('Too Late', $explode)) {
                                                                                    $theIndex = array_search('Too Late', $explode);
                                                                                    unset($explode[$theIndex]);
                                                                                }
                                                                                $implode = implode(',', $explode);
                                                                                $tempDetail = $implode . ',' . $get->noon . ' Casual Leave';
                                                                            } else {
                                                                                if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                    $tempDetail = $get->noon . ' Casual Leave';
                                                                                }
                                                                            }

                                                                        } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                            if (strpos($staff_biometric->details, ',') !== false) {
                                                                                $explode = explode(',', $staff_biometric->details);
                                                                                if (in_array('Early Out', $explode)) {
                                                                                    $theIndex = array_search('Early Out', $explode);
                                                                                    unset($explode[$theIndex]);
                                                                                    $status = 'Present';
                                                                                }
                                                                                $implode = implode(',', $explode);
                                                                                $tempDetail = $implode . ',' . $get->noon . ' Casual Leave';
                                                                            } else {
                                                                                if ($staff_biometric->details == 'Early Out') {
                                                                                    $tempDetail = $get->noon . ' Casual Leave';
                                                                                    $status = 'Present';
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    //$staff_biometric->status = 'Present';
                                                                    $staff_biometric->details = $tempDetail;
                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                    $staff_biometric->status = $status;
                                                                    $staff_biometric->save();
                                                                } else {
                                                                    //$staff_biometric->status = 'Present';
                                                                    $staff_biometric->details = $get->noon . ' Casual Leave';
                                                                    $staff_biometric->update_status = 1;
                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                    $staff_biometric->save();
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            } else {

                                                if ($halfDay == false) {
                                                    foreach ($pastDates as $date) {
                                                        $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                        if ($staff_biometric != '') {
                                                            if ($staff_biometric->details != null) {
                                                                $tempDetail = $staff_biometric->details;
                                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                    $tempDetail = 'Casual Leave';
                                                                }
                                                                $staff_biometric->status = 'Absent';
                                                                $staff_biometric->details = $tempDetail;
                                                                $staff_biometric->updated_at = Carbon::now();
                                                                $staff_biometric->save();
                                                            } else {
                                                                $staff_biometric->status = 'Absent';
                                                                $staff_biometric->details = 'Casual Leave';
                                                                $staff_biometric->update_status = 1;
                                                                $staff_biometric->updated_at = Carbon::now();
                                                                $staff_biometric->save();
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    foreach ($pastDates as $date) {
                                                        $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                        if ($staff_biometric != '') {
                                                            if ($staff_biometric->details != null) {
                                                                $tempDetail = $staff_biometric->details;
                                                                $status = $staff_biometric->status;
                                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                    if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                        if (strpos($staff_biometric->details, ',') !== false) {
                                                                            $explode = explode(',', $staff_biometric->details);
                                                                            if (in_array('Late', $explode)) {
                                                                                $theIndex = array_search('Late', $explode);
                                                                                unset($explode[$theIndex]);
                                                                            } else if (in_array('Too Late', $explode)) {
                                                                                $theIndex = array_search('Too Late', $explode);
                                                                                unset($explode[$theIndex]);
                                                                            }
                                                                            $implode = implode(',', $explode);
                                                                            $tempDetail = $implode . ',' . $get->noon . ' Casual Leave';
                                                                        } else {
                                                                            if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                $tempDetail = $get->noon . ' Casual Leave';
                                                                            }
                                                                        }

                                                                    } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                        if (strpos($staff_biometric->details, ',') !== false) {
                                                                            $explode = explode(',', $staff_biometric->details);
                                                                            if (in_array('Early Out', $explode)) {
                                                                                $theIndex = array_search('Early Out', $explode);
                                                                                unset($explode[$theIndex]);
                                                                                $status = 'Present';
                                                                            }
                                                                            $implode = implode(',', $explode);
                                                                            $tempDetail = $implode . ',' . $get->noon . ' Casual Leave';
                                                                        } else {
                                                                            if ($staff_biometric->details == 'Early Out') {
                                                                                $tempDetail = $get->noon . ' Casual Leave';
                                                                                $status = 'Present';
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                //$staff_biometric->status = 'Present';
                                                                $staff_biometric->details = $tempDetail;
                                                                $staff_biometric->updated_at = Carbon::now();
                                                                $staff_biometric->status = $status;
                                                                $staff_biometric->save();
                                                            } else {
                                                                //$staff_biometric->status = 'Present';
                                                                $staff_biometric->details = $get->noon . ' Casual Leave';
                                                                $staff_biometric->update_status = 1;
                                                                $staff_biometric->updated_at = Carbon::now();
                                                                $staff_biometric->save();
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        } else {
                                            if ($teaching_staff_get->past_casual_leave > 0) {

                                                if ($leaveInPast >= 3) {

                                                    $deduct_cl = 3 - $teaching_staff_get->past_casual_leave;

                                                    if ($deduct_cl > 0) {
                                                        $cl = 0;
                                                        $subtracted_cl = $teaching_staff_get->subtracted_cl + $teaching_staff_get->past_casual_leave;

                                                        if ($halfDay == false) {
                                                            for ($i = 0; $i < count($pastDates); $i++) {
                                                                if ($i <= $teaching_staff_get->past_casual_leave) {
                                                                    $staff_biometric = StaffBiometric::where(['date' => $pastDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                $tempDetail = 'Casual Leave (CL Provided)';
                                                                            }
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        } else {
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = 'Casual Leave (CL Provided)';
                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                } else {
                                                                    $staff_biometric = StaffBiometric::where(['date' => $pastDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                $tempDetail = 'Casual Leave';
                                                                            }
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        } else {
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = 'Casual Leave';
                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        } else {
                                                            foreach ($pastDates as $date) {
                                                                $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                if ($staff_biometric != '') {
                                                                    if ($staff_biometric->details != null) {
                                                                        $tempDetail = $staff_biometric->details;
                                                                        $status = $staff_biometric->status;
                                                                        if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                            if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                                if (strpos($staff_biometric->details, ',') !== false) {
                                                                                    $explode = explode(',', $staff_biometric->details);
                                                                                    if (in_array('Late', $explode)) {
                                                                                        $theIndex = array_search('Late', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                    } else if (in_array('Too Late', $explode)) {
                                                                                        $theIndex = array_search('Too Late', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                    }
                                                                                    $implode = implode(',', $explode);
                                                                                    $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                } else {
                                                                                    if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                        $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                    }
                                                                                }

                                                                            } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                                if (strpos($staff_biometric->details, ',') !== false) {
                                                                                    $explode = explode(',', $staff_biometric->details);
                                                                                    if (in_array('Early Out', $explode)) {
                                                                                        $theIndex = array_search('Early Out', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                        $status = 'Present';
                                                                                    }
                                                                                    $implode = implode(',', $explode);
                                                                                    $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                } else {
                                                                                    if ($staff_biometric->details == 'Early Out') {
                                                                                        $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                        $status = 'Present';
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                        //$staff_biometric->status = 'Present';
                                                                        $staff_biometric->details = $tempDetail;
                                                                        $staff_biometric->updated_at = Carbon::now();
                                                                        $staff_biometric->status = $status;
                                                                        $staff_biometric->save();
                                                                    } else {
                                                                        //$staff_biometric->status = 'Present';
                                                                        $staff_biometric->details = $get->noon . ' Casual Leave (CL Provided)';

                                                                        $staff_biometric->update_status = 1;
                                                                        $staff_biometric->updated_at = Carbon::now();
                                                                        $staff_biometric->save();
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        $check_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->get();

                                                        $theCl = $teaching_staff_get->casual_leave - $cl;
                                                        if (count($check_teaching_staff) > 0) {
                                                            $teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                'subtracted_cl' => $subtracted_cl,
                                                                'past_casual_leave' => $cl,
                                                                'casual_leave' => $theCl,
                                                            ]);
                                                        } else {
                                                            $non_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                'subtracted_cl' => $subtracted_cl,
                                                                'past_casual_leave' => $cl,
                                                                'casual_leave' => $theCl,
                                                            ]);
                                                        }
                                                    } else {
                                                        $cl = abs($deduct_cl);
                                                        $subtracted_cl = $teaching_staff_get->subtracted_cl + 3;

                                                        if ($halfDay == false) {
                                                            for ($i = 0; $i < count($pastDates); $i++) {
                                                                if ($i <= 3) {
                                                                    $staff_biometric = StaffBiometric::where(['date' => $pastDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                $tempDetail = 'Casual Leave (CL Provided)';
                                                                            }
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        } else {
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = 'Casual Leave (CL Provided)';
                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                } else {
                                                                    $staff_biometric = StaffBiometric::where(['date' => $pastDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                $tempDetail = 'Casual Leave';
                                                                            }
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        } else {
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = 'Casual Leave';
                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        } else {
                                                            foreach ($pastDates as $date) {
                                                                $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                if ($staff_biometric != '') {
                                                                    if ($staff_biometric->details != null) {
                                                                        $tempDetail = $staff_biometric->details;
                                                                        $status = $staff_biometric->status;
                                                                        if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                            if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                                if (strpos($staff_biometric->details, ',') !== false) {
                                                                                    $explode = explode(',', $staff_biometric->details);
                                                                                    if (in_array('Late', $explode)) {
                                                                                        $theIndex = array_search('Late', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                    } else if (in_array('Too Late', $explode)) {
                                                                                        $theIndex = array_search('Too Late', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                    }
                                                                                    $implode = implode(',', $explode);
                                                                                    $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                } else {
                                                                                    if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                        $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                    }
                                                                                }

                                                                            } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                                if (strpos($staff_biometric->details, ',') !== false) {
                                                                                    $explode = explode(',', $staff_biometric->details);
                                                                                    if (in_array('Early Out', $explode)) {
                                                                                        $theIndex = array_search('Early Out', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                        $status = 'Present';
                                                                                    }
                                                                                    $implode = implode(',', $explode);
                                                                                    $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                } else {
                                                                                    if ($staff_biometric->details == 'Early Out') {
                                                                                        $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                        $status = 'Present';
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                        //$staff_biometric->status = 'Present';
                                                                        $staff_biometric->details = $tempDetail;
                                                                        $staff_biometric->updated_at = Carbon::now();
                                                                        $staff_biometric->status = $status;
                                                                        $staff_biometric->save();
                                                                    } else {
                                                                        //$staff_biometric->status = 'Present';
                                                                        $staff_biometric->details = $get->noon . ' Casual Leave (CL Provided)';

                                                                        $staff_biometric->update_status = 1;
                                                                        $staff_biometric->updated_at = Carbon::now();
                                                                        $staff_biometric->save();
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        $check_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->get();

                                                        $theCl = $teaching_staff_get->casual_leave - $cl;
                                                        if (count($check_teaching_staff) > 0) {
                                                            $teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                'subtracted_cl' => $subtracted_cl,
                                                                'past_casual_leave' => $cl,
                                                                'casual_leave' => $theCl,
                                                            ]);
                                                        } else {
                                                            $non_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                'subtracted_cl' => $subtracted_cl,
                                                                'past_casual_leave' => $cl,
                                                                'casual_leave' => $theCl,
                                                            ]);
                                                        }
                                                    }
                                                } else {

                                                    $deduct_cl = $teaching_staff_get->past_casual_leave - $leaveInPast;

                                                    if ($deduct_cl > 0) {
                                                        $cl = $deduct_cl;
                                                        $subtracted_cl = $teaching_staff_get->subtracted_cl + $leaveInPast;

                                                        if ($halfDay == false) {
                                                            for ($i = 0; $i < count($pastDates); $i++) {
                                                                if ($i <= $leaveInPast) {
                                                                    $staff_biometric = StaffBiometric::where(['date' => $pastDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                $tempDetail = 'Casual Leave (CL Provided)';
                                                                            }
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        } else {
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = 'Casual Leave (CL Provided)';
                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                } else {
                                                                    $staff_biometric = StaffBiometric::where(['date' => $pastDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                $tempDetail = 'Casual Leave';
                                                                            }
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        } else {
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = 'Casual Leave';
                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        } else {
                                                            foreach ($pastDates as $date) {
                                                                $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                if ($staff_biometric != '') {
                                                                    if ($staff_biometric->details != null) {
                                                                        $tempDetail = $staff_biometric->details;
                                                                        $status = $staff_biometric->status;
                                                                        if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                            if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                                if (strpos($staff_biometric->details, ',') !== false) {
                                                                                    $explode = explode(',', $staff_biometric->details);
                                                                                    if (in_array('Late', $explode)) {
                                                                                        $theIndex = array_search('Late', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                    } else if (in_array('Too Late', $explode)) {
                                                                                        $theIndex = array_search('Too Late', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                    }
                                                                                    $implode = implode(',', $explode);
                                                                                    $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                } else {
                                                                                    if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                        $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                    }
                                                                                }

                                                                            } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                                if (strpos($staff_biometric->details, ',') !== false) {
                                                                                    $explode = explode(',', $staff_biometric->details);
                                                                                    if (in_array('Early Out', $explode)) {
                                                                                        $theIndex = array_search('Early Out', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                        $status = 'Present';
                                                                                    }
                                                                                    $implode = implode(',', $explode);
                                                                                    $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                } else {
                                                                                    if ($staff_biometric->details == 'Early Out') {
                                                                                        $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                        $status = 'Present';
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                        //$staff_biometric->status = 'Present';
                                                                        $staff_biometric->details = $tempDetail;
                                                                        $staff_biometric->updated_at = Carbon::now();
                                                                        $staff_biometric->status = $status;
                                                                        $staff_biometric->save();
                                                                    } else {
                                                                        //$staff_biometric->status = 'Present';
                                                                        $staff_biometric->details = $get->noon . ' Casual Leave (CL Provided)';

                                                                        $staff_biometric->update_status = 1;
                                                                        $staff_biometric->updated_at = Carbon::now();
                                                                        $staff_biometric->save();
                                                                    }
                                                                }

                                                            }
                                                        }
                                                        $check_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->get();

                                                        $theCl = $teaching_staff_get->casual_leave - $leaveInPast;
                                                        if (count($check_teaching_staff) > 0) {
                                                            $teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                'subtracted_cl' => $subtracted_cl,
                                                                'past_casual_leave' => $cl,
                                                                'casual_leave' => $theCl,
                                                            ]);
                                                        } else {
                                                            $non_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                'subtracted_cl' => $subtracted_cl,
                                                                'past_casual_leave' => $cl,
                                                                'casual_leave' => $theCl,
                                                            ]);
                                                        }
                                                    } else {
                                                        $cl = 0;
                                                        $subtracted_cl = $teaching_staff_get->subtracted_cl + $teaching_staff_get->past_casual_leave;

                                                        if ($halfDay == false) {
                                                            for ($i = 0; $i < count($pastDates); $i++) {
                                                                if ($i <= $teaching_staff_get->past_casual_leave) {

                                                                    $staff_biometric = StaffBiometric::where(['date' => $pastDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                $tempDetail = 'Casual Leave (CL Provided)';
                                                                            }
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        } else {
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = 'Casual Leave (CL Provided)';
                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                } else {
                                                                    $staff_biometric = StaffBiometric::where(['date' => $pastDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                $tempDetail = 'Casual Leave';
                                                                            }
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        } else {
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = 'Casual Leave';
                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        } else {
                                                            foreach ($pastDates as $date) {
                                                                $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                if ($staff_biometric != '') {
                                                                    if ($staff_biometric->details != null) {
                                                                        $tempDetail = $staff_biometric->details;
                                                                        $status = $staff_biometric->status;
                                                                        if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                            if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                                if (strpos($staff_biometric->details, ',') !== false) {
                                                                                    $explode = explode(',', $staff_biometric->details);
                                                                                    if (in_array('Late', $explode)) {
                                                                                        $theIndex = array_search('Late', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                    } else if (in_array('Too Late', $explode)) {
                                                                                        $theIndex = array_search('Too Late', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                    }
                                                                                    $implode = implode(',', $explode);
                                                                                    $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                } else {
                                                                                    if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                        $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                    }
                                                                                }

                                                                            } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                                if (strpos($staff_biometric->details, ',') !== false) {
                                                                                    $explode = explode(',', $staff_biometric->details);
                                                                                    if (in_array('Early Out', $explode)) {
                                                                                        $theIndex = array_search('Early Out', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                        $status = 'Present';
                                                                                    }
                                                                                    $implode = implode(',', $explode);
                                                                                    $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                } else {
                                                                                    if ($staff_biometric->details == 'Early Out') {
                                                                                        $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                        $status = 'Present';
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                        //$staff_biometric->status = 'Present';
                                                                        $staff_biometric->details = $tempDetail;
                                                                        $staff_biometric->updated_at = Carbon::now();
                                                                        $staff_biometric->status = $status;
                                                                        $staff_biometric->save();
                                                                    } else {
                                                                        //$staff_biometric->status = 'Present';
                                                                        $staff_biometric->details = $get->noon . ' Casual Leave (CL Provided)';

                                                                        $staff_biometric->update_status = 1;
                                                                        $staff_biometric->updated_at = Carbon::now();
                                                                        $staff_biometric->save();
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        $check_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->get();
                                                        $theCl = $teaching_staff_get->casual_leave - $teaching_staff_get->past_casual_leave;

                                                        if (count($check_teaching_staff) > 0) {
                                                            $teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                'subtracted_cl' => $subtracted_cl,
                                                                'past_casual_leave' => $cl,
                                                                'casual_leave' => $theCl,
                                                            ]);
                                                        } else {
                                                            $non_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                'subtracted_cl' => $subtracted_cl,
                                                                'past_casual_leave' => $cl,
                                                                'casual_leave' => $theCl,
                                                            ]);
                                                        }
                                                    }
                                                }
                                            } else {

                                                if ($halfDay == false) {
                                                    foreach ($pastDates as $date) {
                                                        $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                        if ($staff_biometric != '') {
                                                            if ($staff_biometric->details != null) {
                                                                $tempDetail = $staff_biometric->details;
                                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                    $tempDetail = 'Casual Leave';
                                                                }
                                                                $staff_biometric->status = 'Absent';
                                                                $staff_biometric->details = $tempDetail;
                                                                $staff_biometric->updated_at = Carbon::now();
                                                                $staff_biometric->save();
                                                            } else {
                                                                $staff_biometric->status = 'Absent';
                                                                $staff_biometric->details = 'Casual Leave';
                                                                $staff_biometric->update_status = 1;
                                                                $staff_biometric->updated_at = Carbon::now();
                                                                $staff_biometric->save();
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    foreach ($pastDates as $date) {
                                                        $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                        if ($staff_biometric != '') {
                                                            if ($staff_biometric->details != null) {
                                                                $tempDetail = $staff_biometric->details;
                                                                $status = $staff_biometric->status;
                                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                    if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                        if (strpos($staff_biometric->details, ',') !== false) {
                                                                            $explode = explode(',', $staff_biometric->details);
                                                                            if (in_array('Late', $explode)) {
                                                                                $theIndex = array_search('Late', $explode);
                                                                                unset($explode[$theIndex]);
                                                                            } else if (in_array('Too Late', $explode)) {
                                                                                $theIndex = array_search('Too Late', $explode);
                                                                                unset($explode[$theIndex]);
                                                                            }
                                                                            $implode = implode(',', $explode);
                                                                            $tempDetail = $implode . ',' . $get->noon . ' Casual Leave';
                                                                        } else {
                                                                            if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                $tempDetail = $get->noon . ' Casual Leave';
                                                                            }
                                                                        }

                                                                    } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                        if (strpos($staff_biometric->details, ',') !== false) {
                                                                            $explode = explode(',', $staff_biometric->details);
                                                                            if (in_array('Early Out', $explode)) {
                                                                                $theIndex = array_search('Early Out', $explode);
                                                                                unset($explode[$theIndex]);
                                                                                $status = 'Present';
                                                                            }
                                                                            $implode = implode(',', $explode);
                                                                            $tempDetail = $implode . ',' . $get->noon . ' Casual Leave';
                                                                        } else {
                                                                            if ($staff_biometric->details == 'Early Out') {
                                                                                $tempDetail = $get->noon . ' Casual Leave';
                                                                                $status = 'Present';
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                //$staff_biometric->status = 'Present';
                                                                $staff_biometric->details = $tempDetail;
                                                                $staff_biometric->updated_at = Carbon::now();
                                                                $staff_biometric->status = $status;
                                                                $staff_biometric->save();
                                                            } else {
                                                                //$staff_biometric->status = 'Present';
                                                                $staff_biometric->details = $get->noon . ' Casual Leave';
                                                                $staff_biometric->update_status = 1;
                                                                $staff_biometric->updated_at = Carbon::now();
                                                                $staff_biometric->save();
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    $teaching_staff_get = TeachingStaff::where(['user_name_id' => $get->user_id])->select('casual_leave', 'past_casual_leave', 'subtracted_cl', 'casual_leave_taken', 'user_name_id')->first();

                                    if ($teaching_staff_get == '') {
                                        $non_tech_staff = NonTeachingStaff::where(['user_name_id' => $get->user_id])->select('casual_leave', 'past_casual_leave', 'subtracted_cl', 'casual_leave_taken', 'user_name_id')->first();
                                        $teaching_staff_get = $non_tech_staff;
                                    }
                                    if ($leaveInCurrent > 0) {
                                        if ($clAllocatedForCurrent != 0) {
                                            if ($clAllocatedForCurrent != '') {
                                                if ($teaching_staff_get->casual_leave > 0) {

                                                    if ($leaveInCurrent >= 3) {

                                                        $check_cl = 3 - $clAllocatedForCurrent;

                                                        $deduct_cl = $check_cl - $teaching_staff_get->casual_leave;

                                                        if ($deduct_cl > 0) {
                                                            $cl = 0;
                                                            $subtracted_cl = $teaching_staff_get->subtracted_cl + $deduct_cl;

                                                            if ($halfDay == false) {
                                                                for ($i = 0; $i < count($currentDates); $i++) {
                                                                    if ($i <= $deduct_cl) {

                                                                        $staff_biometric = StaffBiometric::where(['date' => $currentDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                        if ($staff_biometric != '') {
                                                                            if ($staff_biometric->details != null) {
                                                                                $tempDetail = $staff_biometric->details;
                                                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                    $tempDetail = 'Casual Leave (CL Provided)';
                                                                                }
                                                                                $staff_biometric->status = 'Absent';
                                                                                $staff_biometric->details = $tempDetail;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->save();
                                                                            } else {
                                                                                $staff_biometric->status = 'Absent';
                                                                                $staff_biometric->details = 'Casual Leave (CL Provided)';
                                                                                $staff_biometric->update_status = 1;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->save();
                                                                            }
                                                                        }
                                                                    } else {

                                                                        $staff_biometric = StaffBiometric::where(['date' => $currentDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                        if ($staff_biometric != '') {
                                                                            if ($staff_biometric->details != null) {
                                                                                $tempDetail = $staff_biometric->details;
                                                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                    $tempDetail = 'Casual Leave';
                                                                                }
                                                                                $staff_biometric->status = 'Absent';
                                                                                $staff_biometric->details = $tempDetail;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->save();
                                                                            } else {
                                                                                $staff_biometric->status = 'Absent';
                                                                                $staff_biometric->details = 'Casual Leave';
                                                                                $staff_biometric->update_status = 1;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->save();
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            } else {
                                                                foreach ($currentDates as $date) {
                                                                    $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            $status = $staff_biometric->status;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                                    if (strpos($staff_biometric->details, ',') !== false) {
                                                                                        $explode = explode(',', $staff_biometric->details);
                                                                                        if (in_array('Late', $explode)) {
                                                                                            $theIndex = array_search('Late', $explode);
                                                                                            unset($explode[$theIndex]);
                                                                                        } else if (in_array('Too Late', $explode)) {
                                                                                            $theIndex = array_search('Too Late', $explode);
                                                                                            unset($explode[$theIndex]);
                                                                                        }
                                                                                        $implode = implode(',', $explode);
                                                                                        $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                    } else {
                                                                                        if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                            $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                        }
                                                                                    }

                                                                                } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                                    if (strpos($staff_biometric->details, ',') !== false) {
                                                                                        $explode = explode(',', $staff_biometric->details);
                                                                                        if (in_array('Early Out', $explode)) {
                                                                                            $theIndex = array_search('Early Out', $explode);
                                                                                            unset($explode[$theIndex]);
                                                                                            $status = 'Present';
                                                                                        }
                                                                                        $implode = implode(',', $explode);
                                                                                        $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                    } else {
                                                                                        if ($staff_biometric->details == 'Early Out') {
                                                                                            $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                            $status = 'Present';
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                            //$staff_biometric->status = 'Present';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->status = $status;
                                                                            $staff_biometric->save();
                                                                        } else {
                                                                            //$staff_biometric->status = 'Present';
                                                                            $staff_biometric->details = $get->noon . ' Casual Leave (CL Provided)';

                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                }
                                                            }

                                                            $check_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->get();
                                                            if (count($check_teaching_staff) > 0) {
                                                                $teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                    'subtracted_cl' => $subtracted_cl,
                                                                    'casual_leave' => 0,
                                                                    'past_casual_leave' => 0,
                                                                ]);
                                                            } else {
                                                                $non_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                    'subtracted_cl' => $subtracted_cl,
                                                                    'casual_leave' => 0,
                                                                    'past_casual_leave' => 0,
                                                                ]);
                                                            }
                                                        } else {
                                                            $cl = abs($deduct_cl);
                                                            $subtracted_cl = $teaching_staff_get->subtracted_cl + $check_cl;

                                                            if ($halfDay == false) {
                                                                for ($i = 0; $i < count($currentDates); $i++) {
                                                                    if ($i <= $check_cl) {

                                                                        $staff_biometric = StaffBiometric::where(['date' => $currentDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                        if ($staff_biometric != '') {
                                                                            if ($staff_biometric->details != null) {
                                                                                $tempDetail = $staff_biometric->details;
                                                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                    $tempDetail = 'Casual Leave (CL Provided)';
                                                                                }
                                                                                $staff_biometric->status = 'Absent';
                                                                                $staff_biometric->details = $tempDetail;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->save();
                                                                            } else {
                                                                                $staff_biometric->status = 'Absent';
                                                                                $staff_biometric->details = 'Casual Leave (CL Provided)';
                                                                                $staff_biometric->update_status = 1;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->save();
                                                                            }
                                                                        }
                                                                    } else {

                                                                        $staff_biometric = StaffBiometric::where(['date' => $currentDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                        if ($staff_biometric != '') {
                                                                            if ($staff_biometric->details != null) {
                                                                                $tempDetail = $staff_biometric->details;
                                                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                    $tempDetail = 'Casual Leave';
                                                                                }
                                                                                $staff_biometric->status = 'Absent';
                                                                                $staff_biometric->details = $tempDetail;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->save();
                                                                            } else {
                                                                                $staff_biometric->status = 'Absent';
                                                                                $staff_biometric->details = 'Casual Leave';
                                                                                $staff_biometric->update_status = 1;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->save();
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            } else {
                                                                foreach ($currentDates as $date) {
                                                                    $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            $status = $staff_biometric->status;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                                    if (strpos($staff_biometric->details, ',') !== false) {
                                                                                        $explode = explode(',', $staff_biometric->details);
                                                                                        if (in_array('Late', $explode)) {
                                                                                            $theIndex = array_search('Late', $explode);
                                                                                            unset($explode[$theIndex]);
                                                                                        } else if (in_array('Too Late', $explode)) {
                                                                                            $theIndex = array_search('Too Late', $explode);
                                                                                            unset($explode[$theIndex]);
                                                                                        }
                                                                                        $implode = implode(',', $explode);
                                                                                        $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                    } else {
                                                                                        if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                            $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                        }
                                                                                    }

                                                                                } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                                    if (strpos($staff_biometric->details, ',') !== false) {
                                                                                        $explode = explode(',', $staff_biometric->details);
                                                                                        if (in_array('Early Out', $explode)) {
                                                                                            $theIndex = array_search('Early Out', $explode);
                                                                                            unset($explode[$theIndex]);
                                                                                            $status = 'Present';
                                                                                        }
                                                                                        $implode = implode(',', $explode);
                                                                                        $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                    } else {
                                                                                        if ($staff_biometric->details == 'Early Out') {
                                                                                            $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                            $status = 'Present';
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                            //$staff_biometric->status = 'Present';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->status = $status;
                                                                            $staff_biometric->save();
                                                                        } else {
                                                                            //$staff_biometric->status = 'Present';
                                                                            $staff_biometric->details = $get->noon . ' Casual Leave (CL Provided)';

                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                }
                                                            }

                                                            $check_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->get();
                                                            if (count($check_teaching_staff) > 0) {
                                                                $teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                    'subtracted_cl' => $subtracted_cl,
                                                                    'casual_leave' => $cl,
                                                                    'past_casual_leave' => $cl,
                                                                ]);
                                                            } else {
                                                                $non_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                    'subtracted_cl' => $subtracted_cl,
                                                                    'casual_leave' => $cl,
                                                                    'past_casual_leave' => $cl,
                                                                ]);
                                                            }
                                                        }
                                                    } else {
                                                        if ($clAllocatedForCurrent <= 3) {
                                                            $check_cl = 3 - $clAllocatedForCurrent;
                                                            if ($clAllocatedForCurrent >= 1) {
                                                                $deduct_cl = $teaching_staff_get->casual_leave - $check_cl;
                                                            } else {
                                                                $deduct_cl = $teaching_staff_get->casual_leave - 0.5;
                                                            }

                                                            if ($deduct_cl > 0) {
                                                                $cl = $deduct_cl;
                                                                $subtracted_cl = $teaching_staff_get->subtracted_cl + $clAllocatedForCurrent;

                                                                if ($halfDay == false) {
                                                                    for ($i = 0; $i < count($currentDates); $i++) {
                                                                        if ($i <= $clAllocatedForCurrent) {

                                                                            $staff_biometric = StaffBiometric::where(['date' => $currentDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                            if ($staff_biometric != '') {
                                                                                if ($staff_biometric->details != null) {
                                                                                    $tempDetail = $staff_biometric->details;
                                                                                    if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                        $tempDetail = 'Casual Leave (CL Provided)';
                                                                                    }
                                                                                    $staff_biometric->status = 'Absent';
                                                                                    $staff_biometric->details = $tempDetail;
                                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                                    $staff_biometric->save();
                                                                                } else {
                                                                                    $staff_biometric->status = 'Absent';
                                                                                    $staff_biometric->details = 'Casual Leave (CL Provided)';
                                                                                    $staff_biometric->update_status = 1;
                                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                                    $staff_biometric->save();
                                                                                }
                                                                            }
                                                                        } else {

                                                                            $staff_biometric = StaffBiometric::where(['date' => $currentDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                            if ($staff_biometric != '') {
                                                                                if ($staff_biometric->details != null) {
                                                                                    $tempDetail = $staff_biometric->details;
                                                                                    if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                        $tempDetail = 'Casual Leave';
                                                                                    }
                                                                                    $staff_biometric->status = 'Absent';
                                                                                    $staff_biometric->details = $tempDetail;
                                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                                    $staff_biometric->save();
                                                                                } else {
                                                                                    $staff_biometric->status = 'Absent';
                                                                                    $staff_biometric->details = 'Casual Leave';
                                                                                    $staff_biometric->update_status = 1;
                                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                                    $staff_biometric->save();
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                } else {
                                                                    foreach ($currentDates as $date) {
                                                                        $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                        if ($staff_biometric != '') {
                                                                            if ($staff_biometric->details != null) {
                                                                                $tempDetail = $staff_biometric->details;
                                                                                $status = $staff_biometric->status;
                                                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                    if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                                        if (strpos($staff_biometric->details, ',') !== false) {
                                                                                            $explode = explode(',', $staff_biometric->details);
                                                                                            if (in_array('Late', $explode)) {
                                                                                                $theIndex = array_search('Late', $explode);
                                                                                                unset($explode[$theIndex]);
                                                                                            } else if (in_array('Too Late', $explode)) {
                                                                                                $theIndex = array_search('Too Late', $explode);
                                                                                                unset($explode[$theIndex]);
                                                                                            }
                                                                                            $implode = implode(',', $explode);
                                                                                            $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                        } else {
                                                                                            if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                                $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                            }
                                                                                        }

                                                                                    } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                                        if (strpos($staff_biometric->details, ',') !== false) {
                                                                                            $explode = explode(',', $staff_biometric->details);
                                                                                            if (in_array('Early Out', $explode)) {
                                                                                                $theIndex = array_search('Early Out', $explode);
                                                                                                unset($explode[$theIndex]);
                                                                                                $status = 'Present';
                                                                                            }
                                                                                            $implode = implode(',', $explode);
                                                                                            $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                        } else {
                                                                                            if ($staff_biometric->details == 'Early Out') {
                                                                                                $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                                $status = 'Present';
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                                //$staff_biometric->status = 'Present';
                                                                                $staff_biometric->details = $tempDetail;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->status = $status;
                                                                                $staff_biometric->save();
                                                                            } else {
                                                                                //$staff_biometric->status = 'Present';
                                                                                $staff_biometric->details = $get->noon . ' Casual Leave (CL Provided)';

                                                                                $staff_biometric->update_status = 1;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->save();
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                $check_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->get();
                                                                if (count($check_teaching_staff) > 0) {
                                                                    $teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                        'subtracted_cl' => $subtracted_cl,
                                                                        'casual_leave' => $cl,
                                                                        'past_casual_leave' => $cl,
                                                                    ]);
                                                                } else {
                                                                    $non_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                        'subtracted_cl' => $subtracted_cl,
                                                                        'casual_leave' => $cl,
                                                                        'past_casual_leave' => $cl,
                                                                    ]);
                                                                }
                                                            } else {
                                                                $cl = 0;
                                                                $subtracted_cl = $teaching_staff_get->subtracted_cl + $teaching_staff_get->casual_leave;

                                                                if ($halfDay == false) {
                                                                    for ($i = 0; $i < count($currentDates); $i++) {
                                                                        if ($i <= $teaching_staff_get->casual_leave) {
                                                                            $staff_biometric = StaffBiometric::where(['date' => $currentDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                            if ($staff_biometric != '') {
                                                                                if ($staff_biometric->details != null) {
                                                                                    $tempDetail = $staff_biometric->details;
                                                                                    if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                        $tempDetail = 'Casual Leave (CL Provided)';
                                                                                    }
                                                                                    $staff_biometric->status = 'Absent';
                                                                                    $staff_biometric->details = $tempDetail;
                                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                                    $staff_biometric->save();
                                                                                } else {
                                                                                    $staff_biometric->status = 'Absent';
                                                                                    $staff_biometric->details = 'Casual Leave (CL Provided)';
                                                                                    $staff_biometric->update_status = 1;
                                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                                    $staff_biometric->save();
                                                                                }
                                                                            }

                                                                        } else {
                                                                            $staff_biometric = StaffBiometric::where(['date' => $currentDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                            if ($staff_biometric != '') {
                                                                                if ($staff_biometric->details != null) {
                                                                                    $tempDetail = $staff_biometric->details;
                                                                                    if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                        $tempDetail = 'Casual Leave';
                                                                                    }
                                                                                    $staff_biometric->status = 'Absent';
                                                                                    $staff_biometric->details = $tempDetail;
                                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                                    $staff_biometric->save();
                                                                                } else {
                                                                                    $staff_biometric->status = 'Absent';
                                                                                    $staff_biometric->details = 'Casual Leave';
                                                                                    $staff_biometric->update_status = 1;
                                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                                    $staff_biometric->save();
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                } else {
                                                                    foreach ($currentDates as $date) {
                                                                        $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                        if ($staff_biometric != '') {
                                                                            if ($staff_biometric->details != null) {
                                                                                $tempDetail = $staff_biometric->details;
                                                                                $status = $staff_biometric->status;
                                                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                    if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                                        if (strpos($staff_biometric->details, ',') !== false) {
                                                                                            $explode = explode(',', $staff_biometric->details);
                                                                                            if (in_array('Late', $explode)) {
                                                                                                $theIndex = array_search('Late', $explode);
                                                                                                unset($explode[$theIndex]);
                                                                                            } else if (in_array('Too Late', $explode)) {
                                                                                                $theIndex = array_search('Too Late', $explode);
                                                                                                unset($explode[$theIndex]);
                                                                                            }
                                                                                            $implode = implode(',', $explode);
                                                                                            $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                        } else {
                                                                                            if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                                $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                            }
                                                                                        }

                                                                                    } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                                        if (strpos($staff_biometric->details, ',') !== false) {
                                                                                            $explode = explode(',', $staff_biometric->details);
                                                                                            if (in_array('Early Out', $explode)) {
                                                                                                $theIndex = array_search('Early Out', $explode);
                                                                                                unset($explode[$theIndex]);
                                                                                                $status = 'Present';
                                                                                            }
                                                                                            $implode = implode(',', $explode);
                                                                                            $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                        } else {
                                                                                            if ($staff_biometric->details == 'Early Out') {
                                                                                                $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                                $status = 'Present';
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                                //$staff_biometric->status = 'Present';
                                                                                $staff_biometric->details = $tempDetail;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->status = $status;
                                                                                $staff_biometric->save();
                                                                            } else {
                                                                                //$staff_biometric->status = 'Present';
                                                                                $staff_biometric->details = $get->noon . ' Casual Leave (CL Provided)';

                                                                                $staff_biometric->update_status = 1;
                                                                                $staff_biometric->updated_at = Carbon::now();
                                                                                $staff_biometric->save();
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                $check_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->get();
                                                                if (count($check_teaching_staff) > 0) {
                                                                    $teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                        'subtracted_cl' => $subtracted_cl,
                                                                        'casual_leave' => $cl,
                                                                        'past_casual_leave' => $cl,
                                                                    ]);
                                                                } else {
                                                                    $non_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                        'subtracted_cl' => $subtracted_cl,
                                                                        'casual_leave' => $cl,
                                                                        'past_casual_leave' => $cl,
                                                                    ]);
                                                                }
                                                            }
                                                        } else {

                                                            if ($halfDay == false) {
                                                                foreach ($currentDates as $date) {
                                                                    $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                $tempDetail = 'Casual Leave';
                                                                            }
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        } else {
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = 'Casual Leave';
                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                }
                                                            } else {
                                                                foreach ($currentDates as $date) {
                                                                    $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            $status = $staff_biometric->status;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                                    if (strpos($staff_biometric->details, ',') !== false) {
                                                                                        $explode = explode(',', $staff_biometric->details);
                                                                                        if (in_array('Late', $explode)) {
                                                                                            $theIndex = array_search('Late', $explode);
                                                                                            unset($explode[$theIndex]);
                                                                                        } else if (in_array('Too Late', $explode)) {
                                                                                            $theIndex = array_search('Too Late', $explode);
                                                                                            unset($explode[$theIndex]);
                                                                                        }
                                                                                        $implode = implode(',', $explode);
                                                                                        $tempDetail = $implode . ',' . $get->noon . ' Casual Leave';
                                                                                    } else {
                                                                                        if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                            $tempDetail = $get->noon . ' Casual Leave';
                                                                                        }
                                                                                    }

                                                                                } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                                    if (strpos($staff_biometric->details, ',') !== false) {
                                                                                        $explode = explode(',', $staff_biometric->details);
                                                                                        if (in_array('Early Out', $explode)) {
                                                                                            $theIndex = array_search('Early Out', $explode);
                                                                                            unset($explode[$theIndex]);
                                                                                            $status = 'Present';
                                                                                        }
                                                                                        $implode = implode(',', $explode);
                                                                                        $tempDetail = $implode . ',' . $get->noon . ' Casual Leave';
                                                                                    } else {
                                                                                        if ($staff_biometric->details == 'Early Out') {
                                                                                            $tempDetail = $get->noon . ' Casual Leave';
                                                                                            $status = 'Present';
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                            //$staff_biometric->status = 'Present';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->status = $status;
                                                                            $staff_biometric->save();
                                                                        } else {
                                                                            //$staff_biometric->status = 'Present';
                                                                            $staff_biometric->details = $get->noon . ' Casual Leave';
                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                } else {

                                                    if ($halfDay == false) {
                                                        foreach ($currentDates as $date) {
                                                            $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                            if ($staff_biometric != '') {
                                                                if ($staff_biometric->details != null) {
                                                                    $tempDetail = $staff_biometric->details;
                                                                    if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                        $tempDetail = 'Casual Leave';
                                                                    }
                                                                    $staff_biometric->status = 'Absent';
                                                                    $staff_biometric->details = $tempDetail;
                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                    $staff_biometric->save();
                                                                } else {
                                                                    $staff_biometric->status = 'Absent';
                                                                    $staff_biometric->details = 'Casual Leave';
                                                                    $staff_biometric->update_status = 1;
                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                    $staff_biometric->save();
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        foreach ($currentDates as $date) {
                                                            $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                            if ($staff_biometric != '') {
                                                                if ($staff_biometric->details != null) {
                                                                    $tempDetail = $staff_biometric->details;
                                                                    $status = $staff_biometric->status;
                                                                    if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                        if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                            if (strpos($staff_biometric->details, ',') !== false) {
                                                                                $explode = explode(',', $staff_biometric->details);
                                                                                if (in_array('Late', $explode)) {
                                                                                    $theIndex = array_search('Late', $explode);
                                                                                    unset($explode[$theIndex]);
                                                                                } else if (in_array('Too Late', $explode)) {
                                                                                    $theIndex = array_search('Too Late', $explode);
                                                                                    unset($explode[$theIndex]);
                                                                                }
                                                                                $implode = implode(',', $explode);
                                                                                $tempDetail = $implode . ',' . $get->noon . ' Casual Leave';
                                                                            } else {
                                                                                if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                    $tempDetail = $get->noon . ' Casual Leave';
                                                                                }
                                                                            }

                                                                        } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                            if (strpos($staff_biometric->details, ',') !== false) {
                                                                                $explode = explode(',', $staff_biometric->details);
                                                                                if (in_array('Early Out', $explode)) {
                                                                                    $theIndex = array_search('Early Out', $explode);
                                                                                    unset($explode[$theIndex]);
                                                                                    $status = 'Present';
                                                                                }
                                                                                $implode = implode(',', $explode);
                                                                                $tempDetail = $implode . ',' . $get->noon . ' Casual Leave';
                                                                            } else {
                                                                                if ($staff_biometric->details == 'Early Out') {
                                                                                    $tempDetail = $get->noon . ' Casual Leave';
                                                                                    $status = 'Present';
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    //$staff_biometric->status = 'Present';
                                                                    $staff_biometric->details = $tempDetail;
                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                    $staff_biometric->status = $status;
                                                                    $staff_biometric->save();
                                                                } else {

                                                                    //$staff_biometric->status = 'Present';
                                                                    $staff_biometric->details = $get->noon . ' Casual Leave';
                                                                    $staff_biometric->update_status = 1;
                                                                    $staff_biometric->updated_at = Carbon::now();
                                                                    $staff_biometric->save();
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            } else {

                                                if ($halfDay == false) {
                                                    foreach ($currentDates as $date) {
                                                        $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                        if ($staff_biometric != '') {
                                                            if ($staff_biometric->details != null) {
                                                                $tempDetail = $staff_biometric->details;
                                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                    $tempDetail = 'Casual Leave';
                                                                }
                                                                $staff_biometric->status = 'Absent';
                                                                $staff_biometric->details = $tempDetail;
                                                                $staff_biometric->updated_at = Carbon::now();
                                                                $staff_biometric->save();
                                                            } else {
                                                                $staff_biometric->status = 'Absent';
                                                                $staff_biometric->details = 'Casual Leave';
                                                                $staff_biometric->update_status = 1;
                                                                $staff_biometric->updated_at = Carbon::now();
                                                                $staff_biometric->save();

                                                            }
                                                        }
                                                    }
                                                } else {
                                                    foreach ($currentDates as $date) {

                                                        $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                        if ($staff_biometric != '') {
                                                            if ($staff_biometric->details != null) {
                                                                $tempDetail = $staff_biometric->details;
                                                                $status = $staff_biometric->status;
                                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                    if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                        if (strpos($staff_biometric->details, ',') !== false) {
                                                                            $explode = explode(',', $staff_biometric->details);
                                                                            if (in_array('Late', $explode)) {
                                                                                $theIndex = array_search('Late', $explode);
                                                                                unset($explode[$theIndex]);
                                                                            } else if (in_array('Too Late', $explode)) {
                                                                                $theIndex = array_search('Too Late', $explode);
                                                                                unset($explode[$theIndex]);
                                                                            }
                                                                            $implode = implode(',', $explode);
                                                                            $tempDetail = $implode . ',' . $get->noon . ' Casual Leave';
                                                                        } else {
                                                                            if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                $tempDetail = $get->noon . ' Casual Leave';
                                                                            }
                                                                        }

                                                                    } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                        if (strpos($staff_biometric->details, ',') !== false) {
                                                                            $explode = explode(',', $staff_biometric->details);
                                                                            if (in_array('Early Out', $explode)) {
                                                                                $theIndex = array_search('Early Out', $explode);
                                                                                unset($explode[$theIndex]);
                                                                                $status = 'Present';
                                                                            }
                                                                            $implode = implode(',', $explode);
                                                                            $tempDetail = $implode . ',' . $get->noon . ' Casual Leave';
                                                                        } else {
                                                                            if ($staff_biometric->details == 'Early Out') {
                                                                                $tempDetail = $get->noon . ' Casual Leave';
                                                                                $status = 'Present';
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                //$staff_biometric->status = 'Present';
                                                                $staff_biometric->details = $tempDetail;
                                                                $staff_biometric->updated_at = Carbon::now();
                                                                $staff_biometric->status = $status;
                                                                $staff_biometric->save();
                                                            } else {
                                                                //$staff_biometric->status = 'Present';
                                                                $staff_biometric->details = $get->noon . ' Casual Leave';
                                                                $staff_biometric->update_status = 1;
                                                                $staff_biometric->updated_at = Carbon::now();
                                                                $staff_biometric->save();

                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        } else {
                                            if ($teaching_staff_get->casual_leave > 0) {
                                                if ($leaveInCurrent >= 3) {
                                                    $deduct_cl = 3 - $teaching_staff_get->casual_leave;

                                                    if ($deduct_cl > 0) {
                                                        $cl = 0;
                                                        $subtracted_cl = $teaching_staff_get->subtracted_cl + $teaching_staff_get->casual_leave;

                                                        if ($halfDay == false) {
                                                            for ($i = 0; $i < count($currentDates); $i++) {
                                                                if ($i <= $teaching_staff_get->casual_leave) {
                                                                    $staff_biometric = StaffBiometric::where(['date' => $currentDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                $tempDetail = 'Casual Leave (CL Provided)';
                                                                            }
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        } else {
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = 'Casual Leave (CL Provided)';
                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();

                                                                        }
                                                                    }
                                                                } else {
                                                                    $staff_biometric = StaffBiometric::where(['date' => $currentDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                $tempDetail = 'Casual Leave';
                                                                            }
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        } else {
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = 'Casual Leave';
                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        } else {
                                                            foreach ($currentDates as $date) {
                                                                $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                if ($staff_biometric != '') {
                                                                    if ($staff_biometric->details != null) {
                                                                        $tempDetail = $staff_biometric->details;
                                                                        $status = $staff_biometric->status;
                                                                        if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                            if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                                if (strpos($staff_biometric->details, ',') !== false) {
                                                                                    $explode = explode(',', $staff_biometric->details);
                                                                                    if (in_array('Late', $explode)) {
                                                                                        $theIndex = array_search('Late', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                    } else if (in_array('Too Late', $explode)) {
                                                                                        $theIndex = array_search('Too Late', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                    }
                                                                                    $implode = implode(',', $explode);
                                                                                    $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                } else {
                                                                                    if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                        $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                    }
                                                                                }

                                                                            } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                                if (strpos($staff_biometric->details, ',') !== false) {
                                                                                    $explode = explode(',', $staff_biometric->details);
                                                                                    if (in_array('Early Out', $explode)) {
                                                                                        $theIndex = array_search('Early Out', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                        $status = 'Present';
                                                                                    }
                                                                                    $implode = implode(',', $explode);
                                                                                    $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                } else {
                                                                                    if ($staff_biometric->details == 'Early Out') {
                                                                                        $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                        $status = 'Present';
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                        //$staff_biometric->status = 'Present';
                                                                        $staff_biometric->details = $tempDetail;
                                                                        $staff_biometric->updated_at = Carbon::now();
                                                                        $staff_biometric->status = $status;
                                                                        $staff_biometric->save();
                                                                    } else {
                                                                        //$staff_biometric->status = 'Present';
                                                                        $staff_biometric->details = $get->noon . ' Casual Leave (CL Provided)';

                                                                        $staff_biometric->update_status = 1;
                                                                        $staff_biometric->updated_at = Carbon::now();
                                                                        $staff_biometric->save();

                                                                    }
                                                                }
                                                            }
                                                        }

                                                        $check_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->get();
                                                        if (count($check_teaching_staff) > 0) {
                                                            $teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                'subtracted_cl' => $subtracted_cl,
                                                                'casual_leave' => 0,
                                                                'past_casual_leave' => 0,
                                                            ]);
                                                        } else {
                                                            $non_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                'subtracted_cl' => $subtracted_cl,
                                                                'casual_leave' => 0,
                                                                'past_casual_leave' => 0,
                                                            ]);
                                                        }
                                                    } else {
                                                        $cl = abs($deduct_cl);
                                                        $subtracted_cl = $teaching_staff_get->subtracted_cl + 3;

                                                        if ($halfDay == false) {
                                                            for ($i = 0; $i < count($currentDates); $i++) {
                                                                if ($i <= 3) {
                                                                    $staff_biometric = StaffBiometric::where(['date' => $currentDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                $tempDetail = 'Casual Leave (CL Provided)';
                                                                            }
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        } else {
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = 'Casual Leave (CL Provided)';
                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                } else {
                                                                    $staff_biometric = StaffBiometric::where(['date' => $currentDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                $tempDetail = 'Casual Leave';
                                                                            }
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        } else {
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = 'Casual Leave';
                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        } else {
                                                            foreach ($currentDates as $date) {
                                                                $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                if ($staff_biometric != '') {
                                                                    if ($staff_biometric->details != null) {
                                                                        $tempDetail = $staff_biometric->details;
                                                                        $status = $staff_biometric->status;
                                                                        if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                            if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                                if (strpos($staff_biometric->details, ',') !== false) {
                                                                                    $explode = explode(',', $staff_biometric->details);
                                                                                    if (in_array('Late', $explode)) {
                                                                                        $theIndex = array_search('Late', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                    } else if (in_array('Too Late', $explode)) {
                                                                                        $theIndex = array_search('Too Late', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                    }
                                                                                    $implode = implode(',', $explode);
                                                                                    $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                } else {
                                                                                    if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                        $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                    }
                                                                                }

                                                                            } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                                if (strpos($staff_biometric->details, ',') !== false) {
                                                                                    $explode = explode(',', $staff_biometric->details);
                                                                                    if (in_array('Early Out', $explode)) {
                                                                                        $theIndex = array_search('Early Out', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                        $status = 'Present';
                                                                                    }
                                                                                    $implode = implode(',', $explode);
                                                                                    $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                } else {
                                                                                    if ($staff_biometric->details == 'Early Out') {
                                                                                        $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                        $status = 'Present';
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                        //$staff_biometric->status = 'Present';
                                                                        $staff_biometric->details = $tempDetail;
                                                                        $staff_biometric->updated_at = Carbon::now();
                                                                        $staff_biometric->status = $status;
                                                                        $staff_biometric->save();
                                                                    } else {
                                                                        //$staff_biometric->status = 'Present';
                                                                        $staff_biometric->details = $get->noon . ' Casual Leave (CL Provided)';

                                                                        $staff_biometric->update_status = 1;
                                                                        $staff_biometric->updated_at = Carbon::now();
                                                                        $staff_biometric->save();
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        $check_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->get();
                                                        if (count($check_teaching_staff) > 0) {
                                                            $teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                'subtracted_cl' => $subtracted_cl,
                                                                'casual_leave' => $cl,
                                                                'past_casual_leave' => $cl + 1,
                                                            ]);
                                                        } else {
                                                            $non_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                'subtracted_cl' => $subtracted_cl,
                                                                'casual_leave' => $cl,
                                                                'past_casual_leave' => $cl + 1,
                                                            ]);
                                                        }
                                                    }
                                                } else {
                                                    $deduct_cl = $teaching_staff_get->casual_leave - $leaveInCurrent;

                                                    if ($deduct_cl > 0) {

                                                        $cl = $deduct_cl;
                                                        $subtracted_cl = $teaching_staff_get->subtracted_cl + $leaveInCurrent;

                                                        if ($halfDay == false) {
                                                            for ($i = 0; $i < count($currentDates); $i++) {
                                                                if ($i <= $leaveInCurrent) {
                                                                    $staff_biometric = StaffBiometric::where(['date' => $currentDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                $tempDetail = 'Casual Leave (CL Provided)';
                                                                            }
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        } else {
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = 'Casual Leave (CL Provided)';
                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                } else {
                                                                    $staff_biometric = StaffBiometric::where(['date' => $currentDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                $tempDetail = 'Casual Leave';
                                                                            }
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        } else {
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = 'Casual Leave';
                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        } else {
                                                            foreach ($currentDates as $date) {
                                                                $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                if ($staff_biometric != '') {
                                                                    if ($staff_biometric->details != null) {
                                                                        $tempDetail = $staff_biometric->details;
                                                                        $status = $staff_biometric->status;
                                                                        if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                            if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                                if (strpos($staff_biometric->details, ',') !== false) {
                                                                                    $explode = explode(',', $staff_biometric->details);
                                                                                    if (in_array('Late', $explode)) {
                                                                                        $theIndex = array_search('Late', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                    } else if (in_array('Too Late', $explode)) {
                                                                                        $theIndex = array_search('Too Late', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                    }
                                                                                    $implode = implode(',', $explode);
                                                                                    $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                } else {
                                                                                    if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                        $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                    }
                                                                                }

                                                                            } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                                if (strpos($staff_biometric->details, ',') !== false) {
                                                                                    $explode = explode(',', $staff_biometric->details);
                                                                                    if (in_array('Early Out', $explode)) {
                                                                                        $theIndex = array_search('Early Out', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                        $status = 'Present';
                                                                                    }
                                                                                    $implode = implode(',', $explode);
                                                                                    $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                } else {
                                                                                    if ($staff_biometric->details == 'Early Out') {
                                                                                        $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                        $status = 'Present';
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                        //$staff_biometric->status = 'Present';
                                                                        $staff_biometric->details = $tempDetail;
                                                                        $staff_biometric->updated_at = Carbon::now();
                                                                        $staff_biometric->status = $status;
                                                                        $staff_biometric->save();
                                                                    } else {
                                                                        //$staff_biometric->status = 'Present';
                                                                        $staff_biometric->details = $get->noon . ' Casual Leave (CL Provided)';

                                                                        $staff_biometric->update_status = 1;
                                                                        $staff_biometric->updated_at = Carbon::now();
                                                                        $staff_biometric->save();
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        $check_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->get();
                                                        if (count($check_teaching_staff) > 0) {
                                                            $teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                'subtracted_cl' => $subtracted_cl,
                                                                'casual_leave' => $cl,
                                                                'past_casual_leave' => $cl,
                                                            ]);
                                                        } else {
                                                            $non_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                'subtracted_cl' => $subtracted_cl,
                                                                'casual_leave' => $cl,
                                                                'past_casual_leave' => $cl,
                                                            ]);
                                                        }
                                                    } else {

                                                        $cl = 0;
                                                        $subtracted_cl = $teaching_staff_get->subtracted_cl + $teaching_staff_get->casual_leave;

                                                        if ($halfDay == false) {
                                                            for ($i = 0; $i < count($currentDates); $i++) {
                                                                if ($i <= $teaching_staff_get->casual_leave) {
                                                                    $staff_biometric = StaffBiometric::where(['date' => $currentDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                $tempDetail = 'Casual Leave (CL Provided)';
                                                                            }
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        } else {
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = 'Casual Leave (CL Provided)';
                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                } else {
                                                                    $staff_biometric = StaffBiometric::where(['date' => $currentDates[$i], 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                    if ($staff_biometric != '') {
                                                                        if ($staff_biometric->details != null) {
                                                                            $tempDetail = $staff_biometric->details;
                                                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                                $tempDetail = 'Casual Leave';
                                                                            }
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = $tempDetail;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        } else {
                                                                            $staff_biometric->status = 'Absent';
                                                                            $staff_biometric->details = 'Casual Leave';
                                                                            $staff_biometric->update_status = 1;
                                                                            $staff_biometric->updated_at = Carbon::now();
                                                                            $staff_biometric->save();
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        } else {
                                                            foreach ($currentDates as $date) {
                                                                $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                                if ($staff_biometric != '') {
                                                                    if ($staff_biometric->details != null) {
                                                                        $tempDetail = $staff_biometric->details;
                                                                        $status = $staff_biometric->status;
                                                                        if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                            if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                                if (strpos($staff_biometric->details, ',') !== false) {
                                                                                    $explode = explode(',', $staff_biometric->details);
                                                                                    if (in_array('Late', $explode)) {
                                                                                        $theIndex = array_search('Late', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                    } else if (in_array('Too Late', $explode)) {
                                                                                        $theIndex = array_search('Too Late', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                    }
                                                                                    $implode = implode(',', $explode);
                                                                                    $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                } else {
                                                                                    if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                        $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                    }
                                                                                }

                                                                            } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                                if (strpos($staff_biometric->details, ',') !== false) {
                                                                                    $explode = explode(',', $staff_biometric->details);
                                                                                    if (in_array('Early Out', $explode)) {
                                                                                        $theIndex = array_search('Early Out', $explode);
                                                                                        unset($explode[$theIndex]);
                                                                                        $status = 'Present';
                                                                                    }
                                                                                    $implode = implode(',', $explode);
                                                                                    $tempDetail = $implode . ',' . $get->noon . ' Casual Leave (CL Provided)';

                                                                                } else {
                                                                                    if ($staff_biometric->details == 'Early Out') {
                                                                                        $tempDetail = $get->noon . ' Casual Leave (CL Provided)';

                                                                                        $status = 'Present';
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                        //$staff_biometric->status = 'Present';
                                                                        $staff_biometric->details = $tempDetail;
                                                                        $staff_biometric->updated_at = Carbon::now();
                                                                        $staff_biometric->status = $status;
                                                                        $staff_biometric->save();
                                                                    } else {
                                                                        //$staff_biometric->status = 'Present';
                                                                        $staff_biometric->details = $get->noon . ' Casual Leave (CL Provided)';

                                                                        $staff_biometric->update_status = 1;
                                                                        $staff_biometric->updated_at = Carbon::now();
                                                                        $staff_biometric->save();
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        $check_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->get();
                                                        if (count($check_teaching_staff) > 0) {
                                                            $teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                'subtracted_cl' => $subtracted_cl,
                                                                'casual_leave' => 0,
                                                                'past_casual_leave' => 0,
                                                            ]);
                                                        } else {
                                                            $non_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                                                'subtracted_cl' => $subtracted_cl,
                                                                'casual_leave' => 0,
                                                                'past_casual_leave' => 0,
                                                            ]);
                                                        }
                                                    }
                                                }
                                            } else {

                                                if ($halfDay == false) {
                                                    foreach ($currentDates as $date) {
                                                        $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                        if ($staff_biometric != '') {
                                                            if ($staff_biometric->details != null) {
                                                                $tempDetail = $staff_biometric->details;
                                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                    $tempDetail = 'Casual Leave';
                                                                }
                                                                $staff_biometric->status = 'Absent';
                                                                $staff_biometric->details = $tempDetail;
                                                                $staff_biometric->updated_at = Carbon::now();
                                                                $staff_biometric->save();
                                                            } else {
                                                                $staff_biometric->status = 'Absent';
                                                                $staff_biometric->details = 'Casual Leave';
                                                                $staff_biometric->update_status = 1;
                                                                $staff_biometric->updated_at = Carbon::now();
                                                                $staff_biometric->save();
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    foreach ($currentDates as $date) {
                                                        $staff_biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                                        if ($staff_biometric != '') {
                                                            if ($staff_biometric->details != null) {
                                                                $tempDetail = $staff_biometric->details;
                                                                $status = $staff_biometric->status;
                                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                                    if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                                        if (strpos($staff_biometric->details, ',') !== false) {
                                                                            $explode = explode(',', $staff_biometric->details);
                                                                            if (in_array('Late', $explode)) {
                                                                                $theIndex = array_search('Late', $explode);
                                                                                unset($explode[$theIndex]);
                                                                            } else if (in_array('Too Late', $explode)) {
                                                                                $theIndex = array_search('Too Late', $explode);
                                                                                unset($explode[$theIndex]);
                                                                            }
                                                                            $implode = implode(',', $explode);
                                                                            $tempDetail = $implode . ',' . $get->noon . ' Casual Leave';
                                                                        } else {
                                                                            if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                                $tempDetail = $get->noon . ' Casual Leave';
                                                                            }
                                                                        }

                                                                    } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                                        if (strpos($staff_biometric->details, ',') !== false) {
                                                                            $explode = explode(',', $staff_biometric->details);
                                                                            if (in_array('Early Out', $explode)) {
                                                                                $theIndex = array_search('Early Out', $explode);
                                                                                unset($explode[$theIndex]);
                                                                                $status = 'Present';
                                                                            }
                                                                            $implode = implode(',', $explode);
                                                                            $tempDetail = $implode . ',' . $get->noon . ' Casual Leave';
                                                                        } else {
                                                                            if ($staff_biometric->details == 'Early Out') {
                                                                                $tempDetail = $get->noon . ' Casual Leave';
                                                                                $status = 'Present';
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                //$staff_biometric->status = 'Present';
                                                                $staff_biometric->details = $tempDetail;
                                                                $staff_biometric->updated_at = Carbon::now();
                                                                $staff_biometric->status = $status;
                                                                $staff_biometric->save();
                                                            } else {
                                                                //$staff_biometric->status = 'Present';
                                                                $staff_biometric->details = $get->noon . ' Casual Leave';
                                                                $staff_biometric->update_status = 1;
                                                                $staff_biometric->updated_at = Carbon::now();
                                                                $staff_biometric->save();
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    $dates = Carbon::parse($first_date)->daysUntil($last_date);

                                    if ($get->half_day_leave != null && $get->noon != null) {

                                        $staff_biometric = StaffBiometric::where(['date' => $get->half_day_leave, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                        if ($staff_biometric != '') {
                                            if ($staff_biometric->details != null) {
                                                $tempDetail = $staff_biometric->details;
                                                $status = $staff_biometric->status;
                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                    if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                        if (strpos($staff_biometric->details, ',') !== false) {
                                                            $explode = explode(',', $staff_biometric->details);
                                                            if (in_array('Late', $explode)) {
                                                                $theIndex = array_search('Late', $explode);
                                                                unset($explode[$theIndex]);
                                                            } else if (in_array('Too Late', $explode)) {
                                                                $theIndex = array_search('Too Late', $explode);
                                                                unset($explode[$theIndex]);
                                                            }
                                                            $implode = implode(',', $explode);
                                                            $tempDetail = $implode . ',' . $get->noon . ' Casual Leave';
                                                        } else {
                                                            if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                                $tempDetail = $get->noon . ' Casual Leave';
                                                            }
                                                        }

                                                    } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                        if (strpos($staff_biometric->details, ',') !== false) {
                                                            $explode = explode(',', $staff_biometric->details);
                                                            if (in_array('Early Out', $explode)) {
                                                                $theIndex = array_search('Early Out', $explode);
                                                                unset($explode[$theIndex]);
                                                                $status = 'Present';
                                                            }
                                                            $implode = implode(',', $explode);
                                                            $tempDetail = $implode . ',' . $get->noon . ' Casual Leave';
                                                        } else {
                                                            if ($staff_biometric->details == 'Early Out') {
                                                                $tempDetail = $get->noon . ' Casual Leave';
                                                                $status = 'Present';
                                                            }
                                                        }
                                                    }
                                                }
                                                //$staff_biometric->status = 'Present';
                                                $staff_biometric->details = $tempDetail;
                                                $staff_biometric->updated_at = Carbon::now();
                                                $staff_biometric->status = $status;
                                                $staff_biometric->save();
                                            } else {

                                                //$staff_biometric->status = 'Present';
                                                $staff_biometric->details = $get->noon . ' Casual Leave';
                                                $staff_biometric->update_status = 1;
                                                $staff_biometric->updated_at = Carbon::now();
                                                $staff_biometric->save();
                                            }
                                        }

                                    } else {

                                        foreach ($dates as $date) {

                                            $staff_biometric = StaffBiometric::where(['date' => $date->format('Y-m-d'), 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                            if ($staff_biometric != '') {
                                                if ($staff_biometric->details != null) {
                                                    $tempDetail = $staff_biometric->details;
                                                    if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                        $tempDetail = 'Casual Leave';
                                                    }
                                                    $staff_biometric->status = 'Absent';
                                                    $staff_biometric->details = $tempDetail;
                                                    $staff_biometric->updated_at = Carbon::now();
                                                    $staff_biometric->save();
                                                } else {

                                                    $staff_biometric->status = 'Absent';
                                                    $staff_biometric->details = 'Casual Leave';
                                                    $staff_biometric->update_status = 1;
                                                    $staff_biometric->updated_at = Carbon::now();
                                                    $staff_biometric->save();

                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                $dates = Carbon::parse($first_date)->daysUntil($last_date);

                                if ($get->half_day_leave != null && $get->noon != null) {

                                    $staff_biometric = StaffBiometric::where(['date' => $get->half_day_leave, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                    if ($staff_biometric != '') {
                                        if ($staff_biometric->details != null) {
                                            $tempDetail = $staff_biometric->details;
                                            $status = $staff_biometric->status;
                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                    if (strpos($staff_biometric->details, ',') !== false) {
                                                        $explode = explode(',', $staff_biometric->details);
                                                        if (in_array('Late', $explode)) {
                                                            $theIndex = array_search('Late', $explode);
                                                            unset($explode[$theIndex]);
                                                        } else if (in_array('Too Late', $explode)) {
                                                            $theIndex = array_search('Too Late', $explode);
                                                            unset($explode[$theIndex]);
                                                        }
                                                        $implode = implode(',', $explode);
                                                        $tempDetail = $implode . ',' . $get->noon . ' Casual Leave';
                                                    } else {
                                                        if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                            $tempDetail = $get->noon . ' Casual Leave';
                                                        }
                                                    }

                                                } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                    if (strpos($staff_biometric->details, ',') !== false) {
                                                        $explode = explode(',', $staff_biometric->details);
                                                        if (in_array('Early Out', $explode)) {
                                                            $theIndex = array_search('Early Out', $explode);
                                                            unset($explode[$theIndex]);
                                                            $status = 'Present';
                                                        }
                                                        $implode = implode(',', $explode);
                                                        $tempDetail = $implode . ',' . $get->noon . ' Casual Leave';
                                                    } else {
                                                        if ($staff_biometric->details == 'Early Out') {
                                                            $tempDetail = $get->noon . ' Casual Leave';
                                                            $status = 'Present';
                                                        }
                                                    }
                                                }
                                            }
                                            //$staff_biometric->status = 'Present';
                                            $staff_biometric->details = $tempDetail;
                                            $staff_biometric->updated_at = Carbon::now();
                                            $staff_biometric->status = $status;
                                            $staff_biometric->save();
                                        } else {

                                            //$staff_biometric->status = 'Present';
                                            $staff_biometric->details = $get->noon . ' Casual Leave';
                                            $staff_biometric->update_status = 1;
                                            $staff_biometric->updated_at = Carbon::now();
                                            $staff_biometric->save();
                                        }
                                    }

                                } else {

                                    foreach ($dates as $date) {

                                        $staff_biometric = StaffBiometric::where(['date' => $date->format('Y-m-d'), 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                        if ($staff_biometric != '') {
                                            if ($staff_biometric->details != null) {
                                                $tempDetail = $staff_biometric->details;
                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                    $tempDetail = 'Casual Leave';
                                                }
                                                $staff_biometric->status = 'Absent';
                                                $staff_biometric->details = $tempDetail;
                                                $staff_biometric->updated_at = Carbon::now();
                                                $staff_biometric->save();
                                            } else {
                                                $staff_biometric->status = 'Absent';
                                                $staff_biometric->details = 'Casual Leave';
                                                $staff_biometric->update_status = 1;
                                                $staff_biometric->updated_at = Carbon::now();
                                                $staff_biometric->save();
                                            }
                                        }
                                    }
                                }
                            }
                        } else if ($get->leave_type == 2) {

                            $dates = Carbon::parse($first_date)->daysUntil($last_date);

                            if ($get->half_day_leave != null && $get->noon != null) {

                                if ($get->user_id != null) {

                                    $staff_biometric = StaffBiometric::where(['date' => $get->half_day_leave, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                    if ($staff_biometric != '') {
                                        if ($staff_biometric->details != null) {
                                            $tempDetail = $staff_biometric->details;
                                            $status = $staff_biometric->status;
                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                    if (strpos($staff_biometric->details, ',') !== false) {
                                                        $explode = explode(',', $staff_biometric->details);
                                                        if (in_array('Late', $explode)) {
                                                            $theIndex = array_search('Late', $explode);
                                                            unset($explode[$theIndex]);
                                                        } else if (in_array('Too Late', $explode)) {
                                                            $theIndex = array_search('Too Late', $explode);
                                                            unset($explode[$theIndex]);
                                                        }
                                                        $implode = implode(',', $explode);
                                                        $tempDetail = $implode . ',' . $get->noon . ' Admin OD';
                                                    } else {
                                                        if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                            $tempDetail = $get->noon . ' Admin OD';
                                                        }
                                                    }

                                                } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                    if (strpos($staff_biometric->details, ',') !== false) {
                                                        $explode = explode(',', $staff_biometric->details);
                                                        if (in_array('Early Out', $explode)) {
                                                            $theIndex = array_search('Early Out', $explode);
                                                            unset($explode[$theIndex]);
                                                            $status = 'Present';
                                                        }
                                                        $implode = implode(',', $explode);
                                                        $tempDetail = $implode . ',' . $get->noon . ' Admin OD';
                                                    } else {
                                                        if ($staff_biometric->details == 'Early Out') {
                                                            $tempDetail = $get->noon . ' Admin OD';
                                                            $status = 'Present';
                                                        }
                                                    }
                                                }
                                            }
                                            //$staff_biometric->status = 'Present';
                                            $staff_biometric->details = $tempDetail;
                                            $staff_biometric->updated_at = Carbon::now();
                                            $staff_biometric->status = $status;
                                            $staff_biometric->save();
                                        } else {
                                            //$staff_biometric->status = 'Present';
                                            $staff_biometric->details = $get->noon . ' Admin OD';
                                            $staff_biometric->update_status = 1;
                                            $staff_biometric->updated_at = Carbon::now();
                                            $staff_biometric->save();
                                        }
                                    }
                                }
                            } else {

                                foreach ($dates as $date) {

                                    $staff_biometric = StaffBiometric::where(['date' => $date->format('Y-m-d'), 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                    if ($staff_biometric != '') {
                                        if ($staff_biometric->details != null) {
                                            $tempDetail = $staff_biometric->details;
                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                $tempDetail = 'Admin OD';
                                            } else if ($staff_biometric->details == 'Sunday') {
                                                $tempDetail = 'Sunday,Admin OD';
                                            } else if ($staff_biometric->details == 'Holiday') {
                                                $tempDetail = 'Holiday,Admin OD';
                                            }
                                            $staff_biometric->status = 'Absent';
                                            $staff_biometric->details = $tempDetail;
                                            $staff_biometric->updated_at = Carbon::now();
                                            $staff_biometric->save();

                                        } else {

                                            $staff_biometric->status = 'Absent';
                                            $staff_biometric->details = 'Admin OD';
                                            $staff_biometric->update_status = 1;
                                            $staff_biometric->updated_at = Carbon::now();
                                            $staff_biometric->save();
                                        }
                                    }
                                }
                            }

                            $check_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->get();
                            if (count($check_teaching_staff) > 0) {
                                $teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                    'admin_od' => $teaching_staff_get->admin_od + $get->total_days,
                                ]);
                            } else {
                                $non_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                    'admin_od' => $teaching_staff_get->admin_od + $get->total_days,
                                ]);
                            }
                        } else if ($get->leave_type == 3) {

                            $dates = Carbon::parse($first_date)->daysUntil($last_date);
                            if ($get->half_day_leave != null && $get->noon != null) {

                                if ($get->user_id != null) {

                                    $staff_biometric = StaffBiometric::where(['date' => $get->half_day_leave, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                    if ($staff_biometric != '') {
                                        if ($staff_biometric->details != null) {
                                            $tempDetail = $staff_biometric->details;
                                            $status = $staff_biometric->status;
                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                    if (strpos($staff_biometric->details, ',') !== false) {
                                                        $explode = explode(',', $staff_biometric->details);
                                                        if (in_array('Late', $explode)) {
                                                            $theIndex = array_search('Late', $explode);
                                                            unset($explode[$theIndex]);
                                                        } else if (in_array('Too Late', $explode)) {
                                                            $theIndex = array_search('Too Late', $explode);
                                                            unset($explode[$theIndex]);
                                                        }
                                                        $implode = implode(',', $explode);
                                                        $tempDetail = $implode . ',' . $get->noon . ' Exam OD';
                                                    } else {
                                                        if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                            $tempDetail = $get->noon . ' Exam OD';
                                                        }
                                                    }

                                                } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                    if (strpos($staff_biometric->details, ',') !== false) {
                                                        $explode = explode(',', $staff_biometric->details);
                                                        if (in_array('Early Out', $explode)) {
                                                            $theIndex = array_search('Early Out', $explode);
                                                            unset($explode[$theIndex]);
                                                            $status = 'Present';
                                                        }
                                                        $implode = implode(',', $explode);
                                                        $tempDetail = $implode . ',' . $get->noon . ' Exam OD';
                                                    } else {
                                                        if ($staff_biometric->details == 'Early Out') {
                                                            $tempDetail = $get->noon . ' Exam OD';
                                                            $status = 'Present';
                                                        }
                                                    }
                                                }
                                            }
                                            //$staff_biometric->status = 'Present';
                                            $staff_biometric->details = $tempDetail;
                                            $staff_biometric->updated_at = Carbon::now();
                                            $staff_biometric->status = $status;
                                            $staff_biometric->save();

                                        } else {

                                            //$staff_biometric->status = 'Present';
                                            $staff_biometric->details = $get->noon . ' Exam OD';
                                            $staff_biometric->update_status = 1;
                                            $staff_biometric->updated_at = Carbon::now();
                                            $staff_biometric->save();
                                        }
                                    }
                                }
                            } else {

                                foreach ($dates as $date) {

                                    $staff_biometric = StaffBiometric::where(['date' => $date->format('Y-m-d'), 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                    if ($staff_biometric != '') {
                                        if ($staff_biometric->details != null) {
                                            $tempDetail = $staff_biometric->details;
                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                $tempDetail = 'Exam OD';
                                            } else if ($staff_biometric->details == 'Sunday') {
                                                $tempDetail = 'Sunday,Exam OD';
                                            } else if ($staff_biometric->details == 'Holiday') {
                                                $tempDetail = 'Holiday,Exam OD';
                                            }
                                            $staff_biometric->status = 'Absent';
                                            $staff_biometric->details = $tempDetail;
                                            $staff_biometric->updated_at = Carbon::now();
                                            $staff_biometric->save();

                                        } else {

                                            $staff_biometric->status = 'Absent';
                                            $staff_biometric->details = 'Exam OD';
                                            $staff_biometric->update_status = 1;
                                            $staff_biometric->updated_at = Carbon::now();
                                            $staff_biometric->save();
                                        }
                                    }
                                }
                            }

                            $check_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->get();
                            if (count($check_teaching_staff) > 0) {
                                $teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                    'exam_od' => $teaching_staff_get->exam_od + $get->total_days,
                                ]);
                            } else {
                                $non_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                    'exam_od' => $teaching_staff_get->exam_od + $get->total_days,
                                ]);
                            }
                        } else if ($get->leave_type == 4) {

                            $dates = Carbon::parse($first_date)->daysUntil($last_date);

                            if ($get->half_day_leave != null && $get->noon != null) {

                                if ($get->user_id != null) {
                                    $staff_biometric = StaffBiometric::where(['date' => $get->half_day_leave, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                    if ($staff_biometric != '') {
                                        if ($staff_biometric->details != null) {
                                            $tempDetail = $staff_biometric->details;
                                            $status = $staff_biometric->status;
                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                    if (strpos($staff_biometric->details, ',') !== false) {
                                                        $explode = explode(',', $staff_biometric->details);
                                                        if (in_array('Late', $explode)) {
                                                            $theIndex = array_search('Late', $explode);
                                                            unset($explode[$theIndex]);
                                                        } else if (in_array('Too Late', $explode)) {
                                                            $theIndex = array_search('Too Late', $explode);
                                                            unset($explode[$theIndex]);
                                                        }
                                                        $implode = implode(',', $explode);
                                                        $tempDetail = $implode . ',' . $get->noon . ' Training OD';
                                                    } else {
                                                        if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                            $tempDetail = $get->noon . ' Training OD';
                                                        }
                                                    }

                                                } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                    if (strpos($staff_biometric->details, ',') !== false) {
                                                        $explode = explode(',', $staff_biometric->details);
                                                        if (in_array('Early Out', $explode)) {
                                                            $theIndex = array_search('Early Out', $explode);
                                                            unset($explode[$theIndex]);
                                                            $status = 'Present';
                                                        }
                                                        $implode = implode(',', $explode);
                                                        $tempDetail = $implode . ',' . $get->noon . ' Training OD';
                                                    } else {
                                                        if ($staff_biometric->details == 'Early Out') {
                                                            $tempDetail = $get->noon . ' Training OD';
                                                            $status = 'Present';
                                                        }
                                                    }
                                                }
                                            }
                                            //$staff_biometric->status = 'Present';
                                            $staff_biometric->details = $tempDetail;
                                            $staff_biometric->updated_at = Carbon::now();
                                            $staff_biometric->status = $status;
                                            $staff_biometric->save();

                                        } else {

                                            //$staff_biometric->status = 'Present';
                                            $staff_biometric->details = $get->noon . ' Training OD';
                                            $staff_biometric->update_status = 1;
                                            $staff_biometric->updated_at = Carbon::now();
                                            $staff_biometric->save();
                                        }
                                    }
                                }
                            } else {

                                foreach ($dates as $date) {

                                    $staff_biometric = StaffBiometric::where(['date' => $date->format('Y-m-d'), 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                    if ($staff_biometric != '') {
                                        if ($staff_biometric->details != null) {
                                            $tempDetail = $staff_biometric->details;
                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                $tempDetail = 'Training OD';
                                            } else if ($staff_biometric->details == 'Sunday') {
                                                $tempDetail = 'Sunday,Training OD';
                                            } else if ($staff_biometric->details == 'Holiday') {
                                                $tempDetail = 'Holiday,Training OD';
                                            }
                                            $staff_biometric->status = 'Absent';
                                            $staff_biometric->details = $tempDetail;
                                            $staff_biometric->updated_at = Carbon::now();
                                            $staff_biometric->save();

                                        } else {

                                            $staff_biometric->status = 'Absent';
                                            $staff_biometric->details = 'Training OD';
                                            $staff_biometric->update_status = 1;
                                            $staff_biometric->updated_at = Carbon::now();
                                            $staff_biometric->save();
                                        }
                                    }
                                }
                            }

                            $check_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->get();
                            if (count($check_teaching_staff) > 0) {
                                $teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                    'training_od' => $teaching_staff_get->training_od + $get->total_days,
                                ]);
                            } else {
                                $non_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                    'training_od' => $teaching_staff_get->training_od + $get->total_days,
                                ]);
                            }
                        } else if ($get->leave_type == 5) {

                            $get_off_date = Carbon::parse($get->off_date)->month;

                            if ($get->half_day_leave != null && $get->noon != null) {

                                $staff_biometric = StaffBiometric::where(['date' => $get->half_day_leave, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                if ($staff_biometric != '') {
                                    if ($staff_biometric->details != null) {
                                        $tempDetail = $staff_biometric->details;
                                        $status = $staff_biometric->status;
                                        if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                            if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                if (strpos($staff_biometric->details, ',') !== false) {
                                                    $explode = explode(',', $staff_biometric->details);
                                                    if (in_array('Late', $explode)) {
                                                        $theIndex = array_search('Late', $explode);
                                                        unset($explode[$theIndex]);
                                                    } else if (in_array('Too Late', $explode)) {
                                                        $theIndex = array_search('Too Late', $explode);
                                                        unset($explode[$theIndex]);
                                                    }
                                                    $implode = implode(',', $explode);
                                                    $tempDetail = $implode . ',' . $get->noon . ' Compensation Leave';
                                                } else {
                                                    if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                        $tempDetail = $get->noon . ' Compensation Leave';
                                                    }
                                                }

                                            } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                if (strpos($staff_biometric->details, ',') !== false) {
                                                    $explode = explode(',', $staff_biometric->details);
                                                    if (in_array('Early Out', $explode)) {
                                                        $theIndex = array_search('Early Out', $explode);
                                                        unset($explode[$theIndex]);
                                                        $status = 'Present';
                                                    }
                                                    $implode = implode(',', $explode);
                                                    $tempDetail = $implode . ',' . $get->noon . ' Compensation Leave';
                                                } else {
                                                    if ($staff_biometric->details == 'Early Out') {
                                                        $tempDetail = $get->noon . ' Compensation Leave';
                                                        $status = 'Present';
                                                    }
                                                }
                                            }
                                        }
                                        //$staff_biometric->status = 'Present';
                                        $staff_biometric->details = $tempDetail;
                                        $staff_biometric->updated_at = Carbon::now();
                                        $staff_biometric->status = $status;
                                        $staff_biometric->save();

                                    } else {

                                        ////$staff_biometric->status = 'Present';
                                        $staff_biometric->details = $get->noon . ' Compensation Leave';
                                        $staff_biometric->update_status = 1;
                                        $staff_biometric->updated_at = Carbon::now();
                                        $staff_biometric->save();
                                    }
                                }

                            } else {

                                $staff_biometric = StaffBiometric::where(['date' => $get->off_date, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                if ($staff_biometric != '') {
                                    if ($staff_biometric->details != null) {
                                        $tempDetail = $staff_biometric->details;
                                        if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                            $tempDetail = 'Compensation Leave';
                                        }
                                        $staff_biometric->status = 'Absent';
                                        $staff_biometric->details = $tempDetail;
                                        $staff_biometric->updated_at = Carbon::now();
                                        $staff_biometric->save();

                                    } else {

                                        $staff_biometric->status = 'Absent';
                                        $staff_biometric->details = 'Compensation Leave';
                                        $staff_biometric->update_status = 1;
                                        $staff_biometric->updated_at = Carbon::now();
                                        $staff_biometric->save();
                                    }
                                }
                            }

                            $check_teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->get();
                            if (count($check_teaching_staff) > 0) {
                                $teaching_staff = TeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                    'compensation_leave' => $teaching_staff_get->compensation_leave + 1,
                                ]);
                            } else {
                                $non_teaching_staff = NonTeachingStaff::where(['user_name_id' => $get->user_id])->update([
                                    'compensation_leave' => $teaching_staff_get->compensation_leave + 1,
                                ]);
                            }
                        } else if ($get->leave_type == 6 || $get->leave_type == 7 || $get->leave_type == 8) {
                            $dates = Carbon::parse($first_date)->daysUntil($last_date);
                            if ($get->leave_type == 6) {
                                $leave_type = 'Winter Vacation';
                            } else if ($get->leave_type == 7) {
                                $leave_type = 'Summer Vacation';
                            } else {
                                $leave_type = 'Paternity Leave';
                            }
                            if ($get->half_day_leave != null && $get->noon != null) {

                                $detailOfHalfDay = $get->noon . ' ' . $leave_type;

                                $staff_biometric = StaffBiometric::where(['date' => $get->half_day_leave, 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                if ($staff_biometric != '') {
                                    if ($staff_biometric->details != null) {
                                        $tempDetail = $staff_biometric->details;
                                        $status = $staff_biometric->status;
                                        if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                            if ($get->noon == 'Fore Noon' && strpos($staff_biometric->details, 'Late') !== false) {
                                                if (strpos($staff_biometric->details, ',') !== false) {
                                                    $explode = explode(',', $staff_biometric->details);
                                                    if (in_array('Late', $explode)) {
                                                        $theIndex = array_search('Late', $explode);
                                                        unset($explode[$theIndex]);
                                                    } else if (in_array('Too Late', $explode)) {
                                                        $theIndex = array_search('Too Late', $explode);
                                                        unset($explode[$theIndex]);
                                                    }
                                                    $implode = implode(',', $explode);
                                                    $tempDetail = $implode . ',' . $get->noon . ' ' . $detailOfHalfDay;
                                                } else {
                                                    if ($staff_biometric->details == 'Late' || $staff_biometric->details == 'Too Late') {
                                                        $tempDetail = $get->noon . ' ' . $detailOfHalfDay;
                                                    }
                                                }

                                            } else if ($get->noon == 'After Noon' && strpos($staff_biometric->details, 'Early Out') !== false) {
                                                if (strpos($staff_biometric->details, ',') !== false) {
                                                    $explode = explode(',', $staff_biometric->details);
                                                    if (in_array('Early Out', $explode)) {
                                                        $theIndex = array_search('Early Out', $explode);
                                                        unset($explode[$theIndex]);
                                                        $status = 'Present';
                                                    }
                                                    $implode = implode(',', $explode);
                                                    $tempDetail = $implode . ',' . $get->noon . ' ' . $detailOfHalfDay;
                                                } else {
                                                    if ($staff_biometric->details == 'Early Out') {
                                                        $tempDetail = $get->noon . ' ' . $detailOfHalfDay;
                                                        $status = 'Present';
                                                    }
                                                }
                                            }
                                        }
                                        //$staff_biometric->status = 'Present';
                                        $staff_biometric->details = $tempDetail;
                                        $staff_biometric->updated_at = Carbon::now();
                                        $staff_biometric->status = $status;
                                        $staff_biometric->save();

                                    } else {

                                        //$staff_biometric->status = 'Present';
                                        $staff_biometric->details = $detailOfHalfDay;
                                        $staff_biometric->update_status = 1;
                                        $staff_biometric->updated_at = Carbon::now();
                                        $staff_biometric->save();
                                    }
                                }

                            } else {

                                foreach ($dates as $date) {

                                    $staff_biometric = StaffBiometric::where(['date' => $date->format('Y-m-d'), 'user_name_id' => $get->user_id])->select('id', 'details', 'update_status', 'status')->first();
                                    if ($staff_biometric != '') {
                                        if ($staff_biometric->details != null) {
                                            $tempDetail = $staff_biometric->details;
                                            if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Holiday') {
                                                $tempDetail = $leave_type;
                                            }
                                            $staff_biometric->status = 'Absent';
                                            $staff_biometric->details = $tempDetail;
                                            $staff_biometric->updated_at = Carbon::now();
                                            $staff_biometric->save();

                                        } else {

                                            $staff_biometric->status = 'Absent';
                                            $staff_biometric->details = $leave_type;
                                            $staff_biometric->update_status = 1;
                                            $staff_biometric->updated_at = Carbon::now();
                                            $staff_biometric->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return response()->json(['status' => 'ok']);
    }

    public function edit($id)
    {
        abort_if(Gate::denies('hrm_request_leaf_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hrmRequestLeaf = HrmRequestLeaf::findOrFail($id);

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $hrmRequestLeaf->load('user');

        return view('admin.hrmRequestLeaves.edit', compact('hrmRequestLeaf', 'users'));
    }

    public function update(UpdateHrmRequestLeafRequest $request, HrmRequestLeaf $hrmRequestLeaf)
    {
        $hrmRequestLeaf->update($request->all());

        return redirect()->route('admin.hrm-request-leaves.index');
    }

    public function show($id)
    {
        abort_if(Gate::denies('hrm_request_leaf_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hrmRequestLeaf = HrmRequestLeaf::find($id);
        $odAction = false;
        if ($hrmRequestLeaf != '') {
            $user_name_id = $hrmRequestLeaf->user_id;
            if ($hrmRequestLeaf->from_date != null) {

                $from_date = $hrmRequestLeaf->from_date;
                $to_date = $hrmRequestLeaf->to_date;
            } else if ($hrmRequestLeaf->off_date != null) {

                $from_date = null;
                $to_date = null;
            } else if ($hrmRequestLeaf->half_day_leave != null) {

                $from_date = $hrmRequestLeaf->half_day_leave;
                $to_date = $hrmRequestLeaf->half_day_leave;
            } else {
                $from_date = null;
                $to_date = null;
            }
        } else {
            $user_name_id = null;
            $from_date = null;
            $to_date = null;
        }
        $staffs = [];
        if ($user_name_id != null && $from_date != null) {
            $checkStaff = NonTeachingStaff::where(['user_name_id' => $user_name_id])->select('id')->first();
            if ($checkStaff != '') {
                $role = DB::table('role_user')->where(['user_id' => $user_name_id])->select('role_id')->first();
                if ($role != '' && $role->role_id != 33 && $role->role_id != 34) {
                    $odAction = true;
                }
            }

            $get_alterData = StaffAlteration::where(['from_id' => $user_name_id, 'from_date' => $from_date, 'to_date' => $to_date])->select('to_id', 'status')->get();

            if (count($get_alterData) > 0) {
                foreach ($get_alterData as $data) {
                    $get_staff = TeachingStaff::where(['user_name_id' => $data->to_id])->first();

                    if ($get_staff != '') {
                        array_push($staffs, ['staff_code' => $get_staff->StaffCode, 'staff_name' => $get_staff->name, 'status' => $data->status]);
                    }
                }
            }
        }

        $leave_types = LeaveType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $hrmRequestLeaf->load('user');

        return view('admin.hrmRequestLeaves.show', compact('hrmRequestLeaf', 'leave_types', 'staffs', 'odAction'));
    }

    public function list(Request $request)
    {

        if (isset($request->user_id) && $request->user_id != '') {
            $query = HrmRequestLeaf::where(['user_id' => $request->user_id, 'status' => 'Approved'])->get();
            $leave_types = LeaveType::pluck('name', 'id');
            $details = $query;
        }

        if (count($details) > 0) {

            $staff = TeachingStaff::where(['user_name_id' => $details[0]->user_id])->first();
            if ($staff != '') {
                $name = $staff->name;
                $dept = $staff->Dept;
            } else {
                $staff = NonTeachingStaff::where(['user_name_id' => $details[0]->user_id])->first();
                $name = $staff->name;
                $dept = $staff->Dept;
            }
        } else {
            $name = '';
            $dept = '';
        }

        return view('admin.hrmRequestLeaves.leavelist', compact('details', 'name', 'dept', 'leave_types'));
    }

    public function delete(Request $request)
    {
        $HrmRequestLeaf = HrmRequestLeaf::find($request->id);
        $LeaveDetail = HrmRequestLeaf::where(['id' => $request->id])->first();
        if ($HrmRequestLeaf != '') {
            $HrmRequestLeaf->delete();
        }
        if ($LeaveDetail != '') {
            if ($LeaveDetail->from_date != null && $LeaveDetail->to_date != null) {
                $delete = StaffAlteration::where(['from_id' => $LeaveDetail->user_id, 'from_date' => $LeaveDetail->from_date, 'to_date' => $LeaveDetail->to_date])->get();
                if (count($delete) > 0) {
                    $delete = StaffAlteration::where(['from_id' => $LeaveDetail->user_id, 'from_date' => $LeaveDetail->from_date, 'to_date' => $LeaveDetail->to_date])->update([
                        'deleted_at' => Carbon::now(),
                    ]);
                }
            } else if ($LeaveDetail->half_day_leave != null) {
                $delete = StaffAlteration::where(['from_id' => $LeaveDetail->user_id, 'from_date' => $LeaveDetail->half_day_leave, 'to_date' => $LeaveDetail->half_day_leave])->get();
                if (count($delete) > 0) {
                    $delete = StaffAlteration::where(['from_id' => $LeaveDetail->user_id, 'from_date' => $LeaveDetail->half_day_leave, 'to_date' => $LeaveDetail->half_day_leave])->update([
                        'deleted_at' => Carbon::now(),
                    ]);
                }
            }
        }
        return back();
    }

    public function destroy($id)
    {
        $hrmRequestLeaf = HrmRequestLeaf::find($id);

        if ($hrmRequestLeaf) {
            $certificatePath = public_path($hrmRequestLeaf->certificate);

            if (file_exists($certificatePath) && is_readable($certificatePath)) {
                if (unlink($certificatePath)) {
                    $hrmRequestLeaf->delete();
                    return back();
                } else {
                    return response()->json(['message' => 'Unable to delete file'], 500);
                }
            } else {
                return response()->json(['message' => 'File not found or not readable'], 404);
            }
        } else {
            return response()->json(['message' => 'Record not found'], 404);
        }
    }

    public function check(Request $request)
    {
        if ($request) {
            $user_id = auth()->user()->id;
            if ($user_id) {
                $from_date = $request->from_date;
                $to_date = $request->to_date;
                $get_leave_req = HrmRequestLeaf::where(['user_id' => $user_id])->where('status', '!=', 'Rejected')->get();
                if (count($get_leave_req) > 0) {
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

                        if ($from_date != null) {
                            $inputFromDate = Carbon::parse($from_date);
                        } else {
                            $inputFromDate = null;
                        }
                        if ($to_date != null) {
                            $inputToDate = Carbon::parse($to_date);
                        } else {
                            $inputToDate = null;
                        }

                        if ($inputFromDate != null && $inputToDate != null && $leaveFromDate != null && $leaveToDate != null && (($inputFromDate->between($leaveFromDate, $leaveToDate) || $inputToDate->between($leaveFromDate, $leaveToDate)) || ($leaveFromDate->between($inputFromDate, $inputToDate) || $leaveToDate->between($inputFromDate, $inputToDate)))) {
                            return response()->json(['data' => 'Error']);
                            break;
                        }
                        if ($halfDayLeave != null && $halfDayLeave->between($inputFromDate, $inputToDate)) {
                            return response()->json(['data' => 'Error']);
                            break;
                        }
                        if (($halfDayLeave != null && $inputFromDate != null) && $inputFromDate == $halfDayLeave) {
                            return response()->json(['data' => 'Error']);
                            break;
                        }
                    }
                }
                $startDate = new DateTime($from_date);
                $endDate = new DateTime($to_date);

                $daysArray = array();
                $dateAndDay = [];

                while ($startDate <= $endDate) {
                    $day = $startDate->format('l');
                    $theDate = $startDate->format('Y-m-d');
                    $capital = strtoupper($day);
                    $dateAndDay[$capital] = $theDate;
                    $daysArray[] = $capital;
                    $startDate->modify('+1 day');
                }
                $currentClasses = Session::get('currentClasses');
                $results = ClassTimeTableTwo::whereIn('class_name', $currentClasses)->whereIn('day', $daysArray)
                    ->where(function ($query) use ($user_id) {
                        $query->where('staff', $user_id);
                    })
                    ->where(['status' => 1])->get();

                $got_periods = [];

                foreach ($results as $result) {
                    $currentStatus = false;
                    $enrollMaster = CourseEnrollMaster::find($result->class_name);
                    if ($enrollMaster) {
                        $get_course_7 = explode('/', $enrollMaster->enroll_master_number);
                        $get_short_form_7 = ToolsCourse::where('name', $get_course_7[1])->select('short_form')->first();

                        if ($get_short_form_7) {
                            $get_course_7[1] = $get_short_form_7->short_form;
                            $data = $get_course_7[1] . ' / ' . $get_course_7[3] . ' / ' . $get_course_7[4];
                            $result->shortform = $data != '' ? $data : '';
                        }
                        $academicYear = $get_course_7[2];

                        if ($get_course_7[3] == 1) {
                            $batch = '01';
                            $semType = 'ODD';
                        } elseif ($get_course_7[3] == 2) {
                            $batch = '01';
                            $semType = 'EVEN';
                        } elseif ($get_course_7[3] == 3) {
                            $batch = '02';
                            $semType = 'ODD';
                        } elseif ($get_course_7[3] == 4) {
                            $batch = '02';
                            $semType = 'EVEN';
                        } elseif ($get_course_7[3] == 5) {
                            $batch = '03';
                            $semType = 'ODD';
                        } elseif ($get_course_7[3] == 6) {
                            $batch = '03';
                            $semType = 'EVEN';
                        } elseif ($get_course_7[3] == 7) {
                            $batch = '04';
                            $semType = 'ODD';
                        } elseif ($get_course_7[3] == 8) {
                            $batch = '04';
                            $semType = 'EVEN';
                        }

                        $theDay = $result->day;
                        if (array_key_exists($theDay, $dateAndDay)) {
                            $checkDateInCalen = DB::table('college_calenders_preview')->where(['academic_year' => $academicYear, 'semester_type' => $semType, 'batch' => $batch, 'date' => $dateAndDay[$theDay] . ' 00:00:00'])->whereNull('deleted_at')->select('dayorder')->first();

                            if (($checkDateInCalen != null && ($checkDateInCalen->dayorder == 1 || $checkDateInCalen->dayorder == 4)) || ($checkDateInCalen == null)) {
                                $currentStatus = true;
                            }
                        }
                    }
                    if ($currentStatus == false) {
                        if ($result->period != '') {
                            if ($result->period == 1) {
                                $result->period_name = 'ONE';
                            } else if ($result->period == 2) {
                                $result->period_name = 'TWO';
                            } else if ($result->period == 3) {
                                $result->period_name = 'THREE';
                            } else if ($result->period == 4) {
                                $result->period_name = 'FOUR';
                            } else if ($result->period == 5) {
                                $result->period_name = 'FIVE';
                            } else if ($result->period == 6) {
                                $result->period_name = 'SIX';
                            } else if ($result->period == 7) {
                                $result->period_name = 'SEVEN';
                            } else {
                                $result->period_name = $result->period;
                            }
                        }
                        array_push($got_periods, $result);
                    }
                }

                return response()->json(['data' => $got_periods]);
            }
        }
    }

    public function check_for_off(Request $request)
    {
        if ($request) {
            $user_id = auth()->user()->id;
            if ($user_id) {
                $offDate = $request->offDate;
                $get_leave_req = HrmRequestLeaf::where(['user_id' => $user_id])->where('status', '!=', 'Rejected')->get();
                if (count($get_leave_req) > 0) {
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

                        if ($leave_req->off_date != null) {
                            $OffCOP = Carbon::parse($leave_req->off_date);
                        } else {
                            $OffCOP = null;
                        }
                        if ($leave_req->alter_date != null) {
                            $alterCOP = Carbon::parse($leave_req->alter_date);
                        } else {
                            $alterCOP = null;
                        }
                        if ($offDate != null) {
                            $inputOffDate = Carbon::parse($offDate);
                        } else {
                            $inputOffDate = null;
                        }

                        if ($inputOffDate != null && $leaveFromDate != null && $leaveToDate != null && $inputOffDate->between($leaveFromDate, $leaveToDate)) {
                            return response()->json(['data' => false]);
                            break;
                        }

                        if ($inputOffDate != null && $halfDayLeave != null && $inputOffDate == $halfDayLeave) {
                            return response()->json(['data' => false]);
                            break;
                        }
                        if ($inputOffDate != null && $OffCOP != null && $inputOffDate == $OffCOP) {
                            return response()->json(['data' => false]);
                            break;
                        }
                        if ($inputOffDate != null && $alterCOP != null && $inputOffDate == $alterCOP) {
                            return response()->json(['data' => false]);
                            break;
                        }
                    }
                }
                return response()->json(['data' => true]);
            }
        }
    }

    public function check_for_half(Request $request)
    {

        if ($request) {
            $user_id = auth()->user()->id;
            if ($user_id) {
                $leave_date = $request->leave_date;
                $timing = $request->timing;

                $get_leave_req = HrmRequestLeaf::where(['user_id' => $user_id])->get();
                if (count($get_leave_req) > 0) {
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
                            $leaveHalfDate = Carbon::parse($leave_req->half_day_leave);
                        } else {
                            $leaveHalfDate = null;
                        }

                        if ($leave_date != null) {
                            $inputDate = Carbon::parse($leave_date);
                        } else {
                            $inputDate = null;
                        }

                        if ($inputDate != null && $leaveFromDate != null && $leaveToDate != null && $inputDate->between($leaveFromDate, $leaveToDate)) {
                            return response()->json(['data' => 'Error']);
                            break;
                        } else if ($inputDate != null && $leaveHalfDate != null && ($inputDate == $leaveHalfDate)) {
                            if ($leave_req->noon == $timing) {
                                return response()->json(['data' => 'Error']);
                                break;
                            }
                        }
                    }
                }
                $leaveDate = new DateTime($leave_date);

                $day = $leaveDate->format('l');
                $capital = strtoupper($day);

                if ($timing == 'Fore Noon') {
                    $period = [1, 2, 3, 4, 5];
                } else {
                    $period = [6, 7];
                }
                $currentClasses = Session::get('currentClasses');
                $results = ClassTimeTableTwo::whereIn('class_name', $currentClasses)->where('day', $capital)->whereIn('period', $period)
                    ->where(function ($query) use ($user_id) {
                        $query->where('staff', $user_id);
                    })
                    ->where(['status' => 1])->get();

                $got_periods = [];
                foreach ($results as $result) {
                    $currentStatus = false;
                    $enrollMaster = CourseEnrollMaster::find($result->class_name);
                    if ($enrollMaster) {
                        $get_course_7 = explode('/', $enrollMaster->enroll_master_number);
                        $get_short_form_7 = ToolsCourse::where('name', $get_course_7[1])->select('short_form')->first();

                        if ($get_short_form_7) {
                            $get_course_7[1] = $get_short_form_7->short_form;
                            $data = $get_course_7[1] . ' / ' . $get_course_7[3] . ' / ' . $get_course_7[4];
                            $result->shortform = $data != '' ? $data : '';
                        }
                    }
                    $academicYear = $get_course_7[2];

                    if ($get_course_7[3] == 1) {
                        $batch = '01';
                        $semType = 'ODD';
                    } elseif ($get_course_7[3] == 2) {
                        $batch = '01';
                        $semType = 'EVEN';
                    } elseif ($get_course_7[3] == 3) {
                        $batch = '02';
                        $semType = 'ODD';
                    } elseif ($get_course_7[3] == 4) {
                        $batch = '02';
                        $semType = 'EVEN';
                    } elseif ($get_course_7[3] == 5) {
                        $batch = '03';
                        $semType = 'ODD';
                    } elseif ($get_course_7[3] == 6) {
                        $batch = '03';
                        $semType = 'EVEN';
                    } elseif ($get_course_7[3] == 7) {
                        $batch = '04';
                        $semType = 'ODD';
                    } elseif ($get_course_7[3] == 8) {
                        $batch = '04';
                        $semType = 'EVEN';
                    }
                    $checkDateInCalen = DB::table('college_calenders_preview')->where(['academic_year' => $academicYear, 'semester_type' => $semType, 'batch' => $batch, 'date' => $leave_date . ' 00:00:00'])->whereNull('deleted_at')->select('dayorder')->first();
                    if (($checkDateInCalen != null && ($checkDateInCalen->dayorder == 1 || $checkDateInCalen->dayorder == 4)) || ($checkDateInCalen == null)) {
                        $currentStatus = true;
                    }

                    if ($currentStatus == false) {
                        if ($result->period != '') {
                            if ($result->period == 1) {
                                $result->period_name = 'ONE';
                            } else if ($result->period == 2) {
                                $result->period_name = 'TWO';
                            } else if ($result->period == 3) {
                                $result->period_name = 'THREE';
                            } else if ($result->period == 4) {
                                $result->period_name = 'FOUR';
                            } else if ($result->period == 5) {
                                $result->period_name = 'FIVE';
                            } else if ($result->period == 6) {
                                $result->period_name = 'SIX';
                            } else if ($result->period == 7) {
                                $result->period_name = 'SEVEN';
                            } else {
                                $result->period_name = $result->period;
                            }
                        }
                        array_push($got_periods, $result);
                    }
                }

                return response()->json(['data' => $got_periods]);
            }
        }
    }

    public function check_for_compo(Request $request)
    {

        if ($request->alter_date && $request->leave_date) {

            $date = $request->alter_date;
            $user_id = auth()->user()->id;
            $leave_date = $request->leave_date;

            $get_leave_req = HrmRequestLeaf::where(['user_id' => $user_id])->whereNotIn('status', ['Rejected'])->whereNotIn('leave_type', [2, 3, 4])->get();
            if (count($get_leave_req) > 0) {
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

                    if ($leave_req->off_date != null) {
                        $OffCOP = Carbon::parse($leave_req->off_date);
                    } else {
                        $OffCOP = null;
                    }
                    if ($leave_req->alter_date != null) {
                        $alterCOP = Carbon::parse($leave_req->alter_date);
                    } else {
                        $alterCOP = null;
                    }
                    if ($date != null) {
                        $inputAlterDate = Carbon::parse($date);
                    } else {
                        $inputAlterDate = null;
                    }

                    if ($inputAlterDate != null && $leaveFromDate != null && $leaveToDate != null && $inputAlterDate->between($leaveFromDate, $leaveToDate)) {
                        return response()->json(['data' => 'You Have Applied a Leave/OD for the Selected Date!', 'status' => false]);
                        break;
                    }

                    if ($inputAlterDate != null && $halfDayLeave != null && $inputAlterDate == $halfDayLeave) {
                        return response()->json(['data' => 'You Have Applied a Half Day Leave/OD for the Selected Date!', 'status' => false]);
                        break;
                    }
                    if ($inputAlterDate != null && $OffCOP != null && $inputAlterDate == $OffCOP) {
                        return response()->json(['data' => 'You Have Applied a COP for the Selected Date!', 'status' => false]);
                        break;
                    }
                    if ($inputAlterDate != null && $alterCOP != null && $inputAlterDate == $alterCOP) {
                        return response()->json(['data' => 'You Have Applied a COP for the Selected Date!', 'status' => false]);
                        break;
                    }
                }
            }
            $role_type = auth()->user()->roles[0]->type_id;
            if ($role_type == 1) {
                $staff_type = 'Teaching Staff';
            } else if ($role_type == 2) {
                $staff_type = 'Non-Teaching Staff';
            } else if ($role_type == 3) {
                $staff_type = 'Teaching Admin';
            } else if ($role_type == 4) {
                $staff_type = 'Non-Teaching Admin';
            } else if ($role_type == 5) {
                $staff_type = 'Civil';
            } else {
                $staff_type = null;
            }
            $check_Status = StaffBiometric::where(['date' => $date, 'user_name_id' => $user_id])->where('details', 'LIKE', '%Holiday%')->where('details', 'NOT LIKE', '%Fore Noon Holiday%')->where('details', 'NOT LIKE', '%After Noon Holiday%')->count();

            if ($check_Status == 0) {
                $check_date = Carbon::createFromFormat('Y-m-d', $date);
                if ($check_date->dayOfWeek != Carbon::SUNDAY) {
                    return response()->json(['status' => false, 'data' => 'No Holiday Day For This Date!']);
                } else {
                    return response()->json(['status' => true, 'data' => '']);
                }
            } else {

                if ($user_id) {

                    $leaveDate = new DateTime($leave_date);

                    $day = $leaveDate->format('l');
                    $capital = strtoupper($day);
                    $currentClasses = Session::get('currentClasses');
                    $results = ClassTimeTableTwo::whereIn('class_name', $currentClasses)->where('day', $capital)->where(function ($query) use ($user_id) {
                        $query->where('staff', $user_id);
                    })
                        ->where(['status' => 1])->get();

                    $got_periods = [];
                    foreach ($results as $result) {
                        $enrollMaster = CourseEnrollMaster::find($result->class_name);
                        if ($enrollMaster) {
                            $get_course_7 = explode('/', $enrollMaster->enroll_master_number);
                            $get_short_form_7 = ToolsCourse::where('name', $get_course_7[1])->select('short_form')->first();

                            if ($get_short_form_7) {
                                $get_course_7[1] = $get_short_form_7->short_form;
                                $data = $get_course_7[1] . ' / ' . $get_course_7[3] . ' / ' . $get_course_7[4];
                                $result->shortform = $data != '' ? $data : '';
                            }
                        }

                        if ($result->period != '') {
                            if ($result->period == 1) {
                                $result->period_name = 'ONE';
                            } else if ($result->period == 2) {
                                $result->period_name = 'TWO';
                            } else if ($result->period == 3) {
                                $result->period_name = 'THREE';
                            } else if ($result->period == 4) {
                                $result->period_name = 'FOUR';
                            } else if ($result->period == 5) {
                                $result->period_name = 'FIVE';
                            } else if ($result->period == 6) {
                                $result->period_name = 'SIX';
                            } else if ($result->period == 7) {
                                $result->period_name = 'SEVEN';
                            } else {
                                $result->period_name = $result->period;
                            }
                        }
                        array_push($got_periods, $result);
                    }
                    return response()->json(['data' => $got_periods, 'status' => true]);
                }
            }
        }
    }

    public function checkStaff(Request $request)
    {
        if ($request['data']) {
            $datas = $request['data'];
            $currentClasses = Session::get('currentClasses');
            $results = ClassTimeTableOne::whereIn('class_name', $currentClasses)->where(['period' => $datas[2]['value'], 'day' => $datas[1]['value'], 'staff' => $datas[3]['value']])->get();

            if ($results->count() == 0) {
                return response()->json(['data' => true]);
            } else {
                return response()->json(['data' => false]);
            }
        }
    }

    public function massDestroy(MassDestroyHrmRequestLeafRequest $request)
    {
        $hrmRequestLeaves = HrmRequestLeaf::find(request('ids'));

        foreach ($hrmRequestLeaves as $hrmRequestLeaf) {
            $hrmRequestLeaf->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function approve(Request $request)
    {
        $get = StaffAlteration::where(['id' => $request->id])->first();

        if ($get) {

            $updatedAction = StaffAlteration::where(['id' => $request->id])->update([
                'status' => '1',
                'approval' => '1',
            ]);

            $newst_id = $request->to_id;

            if ($updatedAction) {

                $altertable = new StaffAlterationRegister;
                $altertable->from_date = $get->from_date;
                $altertable->to_date = $get->to_date;
                $altertable->staff_id = $get->from_id;
                $altertable->alter_staffid = $get->to_id;
                $altertable->class_name = $get->classname;
                $altertable->day = $get->day;
                $altertable->period = $get->period;
                $altertable->status = 0;
                $altertable->save();
            }
        }

        return back();
    }

    public function reject(Request $request)
    {
        $reject = StaffAlteration::where('id', $request->id)->update(['status' => '2', 'approval' => '2']);
        return back();
    }

    public function leaveImplement(Request $request)
    {
        dd($request);
    }

    public function NT_Staff_check_for_compo(Request $request)
    {
        if ($request->alter_date && $request->leave_date) {

            $date = $request->alter_date;
            $user_id = auth()->user()->id;
            $leave_date = $request->leave_date;

            $get_leave_req = HrmRequestLeaf::where(['user_id' => $user_id])->whereNotIn('status', ['Rejected'])->whereNotIn('leave_type', [2, 3, 4])->get();
            if (count($get_leave_req) > 0) {
                foreach ($get_leave_req as $leave_req) {

                    $leaveFromDate = Carbon::parse($leave_req->from_date);
                    $leaveToDate = Carbon::parse($leave_req->to_date);
                    $halfDayLeave = Carbon::parse($leave_req->half_day_leave);
                    $OffCOP = Carbon::parse($leave_req->off_date);
                    $alterCOP = Carbon::parse($leave_req->alter_date);
                    $inputAlterDate = Carbon::parse($date);

                    if ($leave_req->from_date != null && $leave_req->to_date != null && $date != null && $inputAlterDate->between($leaveFromDate, $leaveToDate)) {
                        return response()->json(['data' => 'You Have Applied a Leave/OD for the Selected Date!', 'status' => false]);
                        break;
                    }

                    if ($date != null && $leave_req->half_day_leave != null && $inputAlterDate == $halfDayLeave) {
                        return response()->json(['data' => 'You Have Applied a Half Day Leave/OD for the Selected Date!', 'status' => false]);
                        break;
                    }
                    if ($date != null && $leave_req->off_date != null && $inputAlterDate == $OffCOP) {
                        return response()->json(['data' => 'You Have Applied a COP for the Selected Date!', 'status' => false]);
                        break;
                    }
                    if ($date != null && $leave_req->alter_date != null && $inputAlterDate == $alterCOP) {
                        return response()->json(['data' => 'You Have Applied a COP for the Selected Date!', 'status' => false]);
                        break;
                    }
                }
            }
            $role_type = auth()->user()->roles[0]->type_id;
            if ($role_type == 1) {
                $staff_type = 'Teaching Staff';
            } else if ($role_type == 2) {
                $staff_type = 'Non-Teaching Staff';
            } else if ($role_type == 3) {
                $staff_type = 'Teaching Admin';
            } else if ($role_type == 4) {
                $staff_type = 'Non-Teaching Admin';
            } else if ($role_type == 5) {
                $staff_type = 'Civil';
            } else {
                $staff_type = null;
            }
            $check_Status = StaffBiometric::where(['date' => $date, 'user_name_id' => $user_id])->where('details', 'LIKE', '%Holiday%')->where('details', 'NOT LIKE', '%Fore Noon Holiday%')->where('details', 'NOT LIKE', '%After Noon Holiday%')->count();

            if ($check_Status == 0) {
                $check_date = Carbon::createFromFormat('Y-m-d', $date);
                if ($check_date->dayOfWeek != Carbon::SUNDAY) {
                    return response()->json(['status' => false, 'data' => 'No Holiday Day For This Date!']);
                } else {
                    return response()->json(['status' => true, 'data' => '']);
                }
            } else {
                return response()->json(['status' => true, 'data' => '']);
            }
        }
    }

    public function bulkApprove(Request $request)
    {
        if ($request->ids != null && count($request->ids) > 0) {
            $update = HrmRequestLeaf::whereIn('id', $request->ids)->update([
                'level' => 95,
                'approved_by' => auth()->user()->name,
            ]);
            return response()->json(['status' => true, 'data' => 'All OD Requests Approved.']);
        } else {
            return response()->json(['status' => false, 'data' => 'No Data Selected.']);
        }
    }
}
