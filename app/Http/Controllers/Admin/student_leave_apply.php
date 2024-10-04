<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseEnrollMaster;
use App\Models\Student;
use App\Models\StudentLeaveApply;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use App\Models\User;
use App\Models\UserAlert;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class student_leave_apply extends Controller
{
    public function index(Request $student)
    {

        $leave_apply = DB::table('student_leave_apply')
            ->where('user_name_id', $student->user_name_id)
            ->whereNull('deleted_at')
            ->first();
        $check = 'student_leave_details';

        $list = DB::table('student_leave_apply')
            ->where('user_name_id', $student->user_name_id)
            ->whereNull('deleted_at')
            ->get();

        if ($student->updater) {
            $leave_apply = DB::table('student_leave_apply')->where(['user_name_id' => $student->user_name_id, 'id' => $student->id])->whereNull('deleted_at')->first();
            if ($leave_apply) {

                $leave_apply->add = 'Update';
            }
        } else {
            $leave_apply = (object) [
                'id' => '',
                'leave_type' => '',
                'reason' => '',
                'subject' => '',
                'status' => '',
                'from_date' => '',
                'to_date' => '',
                'add' => 'Request',
            ];

            if (!$leave_apply) {
                $list = [];
            }
        }

        return view('admin.student_Leave_apply.index', compact('leave_apply', 'student', 'check', 'list'));

    }
    public function store(Request $request)
    {
        if (isset($request->status)) {
            if ($request->filled('status') && in_array($request->status, ['Approved', 'Rejected', 'Approved-HOD', 'Rejected-HOD'])) {
                $userName = null;

                if (Auth::check()) {
                    $userId = Auth::id();
                    $user = User::find($userId);

                    if ($user) {
                        $userName = $user->name;
                    }
                }
                $role_id = auth()->user()->roles[0]->id;
                $type_id = auth()->user()->roles[0]->type_id;
                $roleTitles = ['Sr. Associate Professor', 'Dean', 'Professor', 'Assistant Professor', 'Assistant Professor (SS)', 'Professor & Dean - Academics', 'Assistant Professor (SG)', 'Director', 'Associate Professor', 'Associate Professor & Head'];
                $hasRequiredRoles = $user->roles()->whereIn('title', $roleTitles)->exists();

                $roleTitle = ['HOD'];
                $hasRequiredRole = $user->roles()->whereIn('title', $roleTitle)->exists();

                $updateData = ['approved_by' => $userName];

                if ($request->status == 'Approved') {
                    if ($type_id == 1 || $type_id == 3) {

                        $updateData['status'] = '1';
                        $statusOfReq = 'Forward To HOD';
                    } elseif ($role_id == 14) {
                        $updateData['status'] = '3';
                        $statusOfReq = 'Approved By HOD';
                    } else {
                        $updateData['level'] = '0';
                        $updateData['status'] = '0';
                        $updateData['approved_by'] = '';
                        $statusOfReq = '';
                    }
                } elseif ($request->status == 'Rejected') {
                    if ($request->has('rejected_reason')) {
                        $updateData['rejected_reason'] = $request->rejected_reason;
                    }
                    if ($hasRequiredRoles) {
                        $updateData['status'] = '2';
                        $statusOfReq = 'Rejected By Class Incharge';
                    } elseif ($hasRequiredRole) {
                        $updateData['status'] = '2';
                        $statusOfReq = 'Rejected By HOD';
                    } else {
                        $updateData['level'] = '0';
                        $updateData['status'] = '0';
                        $updateData['approved_by'] = '';
                        $statusOfReq = '';
                    }
                }

                if ($request->status == 'Approved-HOD') {
                    $updateData['status'] = '3';
                }
                if ($request->status == 'Rejected-HOD') {
                    $updateData['status'] = '2';
                }
                $leaveType = null;
                $student = '';
                $checkLeaveType = StudentLeaveApply::find($request->id);
                if ($checkLeaveType != '') {
                    $student = Student::where(['user_name_id' => $checkLeaveType->user_name_id])->select('name', 'user_name_id')->first();
                    $leaveType = $checkLeaveType->leave_type;
                }
                if ($student != '' && $leaveType != null) {
                    $update = StudentLeaveApply::where('id', $request->id)->update($updateData);
                } else {
                    $update = false;
                }
                if ($update) {
                    $userAlert = new UserAlert;
                    $userAlert->alert_text = 'Your ' . $leaveType . ' Application ' . $statusOfReq;
                    $userAlert->alert_link = url('admin/student-request-leaves/index/' . $student->user_name_id . '/' . $student->name);
                    $userAlert->save();
                    $userAlert->users()->sync($student->user_name_id);

                    if ($updateData['status'] == '1') {

                        $getClass = CourseEnrollMaster::where(['id' => $student->enroll_master_id])->select('enroll_master_number')->first();
                        if ($getClass != '') {
                            $explode = explode('/', $getClass->enroll_master_number);
                            $getDept = ToolsCourse::where(['name' => $explode[1]])->select('department_id')->first();
                            if ($getDept != '') {
                                $theDept = ToolsDepartment::where(['id' => $getDept->department_id])->select('name')->first();
                                if ($theDept != '') {
                                    $hodArray = [];
                                    $getHodRole = DB::table('role_user')->where(['role_id' => 14])->select('user_id')->get();
                                    foreach ($getHodRole as $hod) {
                                        $checkUser = User::where(['id' => $hod->user_id, 'dept' => $theDept->name])->select('id')->first();
                                        if ($checkUser != '') {
                                            array_push($hodArray, $checkUser);
                                        }
                                    }
                                    $userAlert = new UserAlert;
                                    $userAlert->alert_text = $student->name.' Applied a ' . $leaveType;
                                    $userAlert->alert_link = url('admin/student-leave-requests/show/' . $request->id);
                                    $userAlert->save();
                                    $userAlert->users()->sync($hodArray);
                                }
                            }
                        }
                    }
                }
            }

            return redirect()->back()->with('success', 'Success.');
        }

        if (isset($request->certificate)) {

            $request->validate([
                'certificate' => 'required|image|mimes:jpg,JPG,jpeg,png,PNG,JPEG|max:2048',
            ]);
            $file = $request->file('certificate');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $path = $file->storeAs('uploads', $fileName);
            $file->move(public_path('uploads'), $fileName);
        } else {
            $path = '';
        }
        $studentName = null;
        $student = Student::where('user_name_id', $request->user_name_id)->first();

        if ($student != '') {
            $enrollNo = $student->enroll_master_id;
            $classRoom = DB::table('class_rooms')->where('name', $enrollNo)->first();
            // dd($classRoom);
            if ($classRoom) {
                $classIncharge = $classRoom->class_incharge;
            }

            if (!isset($classIncharge) || $classIncharge == null) {
                return back()->with('errors', 'Class Incharge Not Assigned.');
            }
            $studentName = $student->name;
        } else {
            $enrollNo = '';
        }

        if (!empty($enrollNo)) {
            $CourseEnrollMaster = CourseEnrollMaster::find($enrollNo);
            if ($CourseEnrollMaster) {
                $split = $CourseEnrollMaster->enroll_master_number;
                $splitedData = explode('/', $split);

                if (isset($splitedData[1])) {
                    $dummy = ToolsCourse::where('name', $splitedData[1])->first();
                    if ($dummy) {
                        $department_id = $dummy->department_id;

                        if ($department_id) {
                            $deparName = ToolsDepartment::find($department_id);
                            if ($deparName) {
                                $depName = $deparName->name;
                            } else {
                                $depName = '';
                            }
                        }
                    }
                }
            }
        }

        if (isset($request->id) && $request->id != '') {
            $update = StudentLeaveApply::where(['id' => $request->id])->update([
                'leave_type' => request()->input('leave_type'),
                'reason' => request()->input('reason'),
                'subject' => request()->input('subject'),
                'status' => '0',
                'level' => '0',
                'from_date' => request()->input('from_date'),
                'to_date' => request()->input('to_date'),
                'dept' => $depName,
                'certificate_path' => $path,
                'enrollno' => $enrollNo,
                'classincharge' => $classIncharge,
                'user_name_id' => $request->user_name_id,
                'updated_at' => Carbon::now(),
            ]);
            $reqId = $request->id;
        } else {
            $create = new StudentLeaveApply;
            $create->leave_type = request()->input('leave_type');
            $create->reason = request()->input('reason');
            $create->subject = request()->input('subject');
            $create->status = '0';
            $create->level = '0';
            $create->from_date = request()->input('from_date');
            $create->to_date = request()->input('to_date');
            $create->dept = $depName;
            $create->certificate_path = $path;
            $create->enrollno = $enrollNo;
            $create->classincharge = $classIncharge;
            $create->user_name_id = $request->user_name_id;
            $create->created_at = Carbon::now();
            $create->save();
            $reqId = $create->id;
        }

        $userAlert = new UserAlert;
        $userAlert->alert_text = $studentName . ' Applied a ' . request()->input('leave_type');
        $userAlert->alert_link = url('admin/student-leave-requests/show/' . $reqId);
        $userAlert->save();
        $userAlert->users()->sync($classIncharge);

        $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        // Insert successful, perform further actions or redirect
        return redirect()->route('admin.student-request-leaves.index', $staff);
    }
    public function delete(Request $request)
    {
        $student_leave_apply = DB::table('student_leave_apply')->find($request->id);
        if ($student_leave_apply) {
            DB::table('student_leave_apply')->where('id', $request->id)->update(['deleted_at' => now()]);
        }
        return back();
    }

    public function stu_index(Request $request)
    {
        $type_id = auth()->user()->roles[0]->type_id;
        $role_id = auth()->user()->roles[0]->id;
        $userId = auth()->user()->id;
        // $roleTitles = ['Dean', 'Sr. Associate Professor', 'Professor', 'Assistant Professor', 'Assistant Professor (SS)', 'Professor & Dean - Academics', 'Assistant Professor (SG)', 'Director', 'Associate Professor', 'Associate Professor & Head'];

        if ($request->ajax()) {
            if ($type_id == 1 || $type_id == 3) {
                $query = DB::table('student_leave_apply')
                    ->where('classincharge', $userId)
                    ->whereNull('deleted_at')
                    ->whereNotIn('status', [2, 1, 3])
                    ->get();

            } elseif ($role_id == 14) {

                $hod = User::where('id', $userId)->first();
                if ($hod) {
                    $Dept = $hod->dept;
                } else {
                    $Dept = null;
                }
                $query = DB::table('student_leave_apply')
                    ->whereNull('deleted_at')
                    ->whereNotIn('status', [2, 3])
                    ->where('dept', $Dept)
                    ->get();
            } else {
                $query = DB::table('student_leave_apply')
                    ->whereNull('deleted_at')
                    ->whereNotIn('status', [2, 3])
                    ->get();
            }
            // dd($query);
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'student_leave_request';
                $editGate = '';
                $deleteGate = '';
                $crudRoutePart = 'student-leave-requests';

                return view(
                    'partials.datatablesActions',
                    compact(
                        'viewGate',
                        'editGate',
                        'deleteGate',
                        'crudRoutePart',
                        'row'
                    )
                );
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->addColumn('from_date', function ($row) {
                return $row->from_date ? $row->from_date : '';
            });
            $table->editColumn('to_date', function ($row) {
                return $row->to_date ? $row->to_date : '';
            });
            $table->editColumn('approved_by', function ($row) {
                return $row->approved_by ? $row->approved_by : '';
            });
            $table->editColumn('leave_type', function ($row) {
                return $row->leave_type ? $row->leave_type : '';
            });
            $table->editColumn('name', function ($row) {
                if ($row->user_name_id) {
                    $user = User::find($row->user_name_id);
                    if ($user) {
                        return $user->name;
                    }
                }
                return '';
            });
            $table->addColumn('register_no', function ($row) {
                if ($row->user_name_id) {
                    $student = Student::where('user_name_id', $row->user_name_id)->first();
                    return $student != '' ? ($student->register_no ? $student->register_no : 'Not Updated') : '';
                } else {
                    return '';
                }
            });

            $table->editColumn('approved_by', function ($row) {
                return $row->approved_by ? $row->approved_by : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.student_leave_request.index');
    }

    public function show($id)
    {
        // dd($id);
        if (isset($id)) {
            $data = DB::table('student_leave_apply')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->first();

        }

        return view('admin.student_leave_request.requests', compact('data'));
    }

}
