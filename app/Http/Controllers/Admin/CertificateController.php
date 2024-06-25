<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\CourseEnrollMaster;
use App\Models\IssueCertificate;
use App\Models\Student;
use App\Models\StudentBonafide;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use App\Models\User;
use App\Models\UserAlert;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $departments = ToolsDepartment::pluck('name', 'id');
        $academic_years = AcademicYear::pluck('name', 'id');
        if ($request->ajax()) {
            $status = 0;
            if ($request->status != '') {
                $status = $request->status;
            }
            $enroll_master = [];
            if ($request->ay != '' && $request->semester != '' && $request->dept == '' && $request->course == '') {
                $make_enroll = '%' . '/' . '%' . '/' . $request->ay . '/' . $request->semester . '/' . '%';
                $get_enrolls = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "$make_enroll")->select('id')->get();
                if (count($get_enrolls) > 0) {
                    foreach ($get_enrolls as $enroll) {
                        array_push($enroll_master, $enroll->id);
                    }
                }
            } else if ($request->ay != '' && $request->semester != '' && $request->dept != '' && $request->course == '') {
                $get_courses = ToolsCourse::where(['department_id' => $request->dept])->select('name')->get();
                $enrolls = [];
                if (count($get_courses) > 0) {
                    foreach ($get_courses as $course) {
                        $make_enroll = '%' . '/' . $course->name . '/' . $request->ay . '/' . $request->semester . '/' . '%';
                        array_push($enrolls, $make_enroll);
                    }
                }

                if (count($enrolls) > 0) {
                    foreach ($enrolls as $enroll) {
                        $get_enrolls = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "$enroll")->select('id')->get();
                        if (count($get_enrolls) > 0) {
                            foreach ($get_enrolls as $enroll) {
                                array_push($enroll_master, $enroll->id);
                            }
                        }
                    }
                }
            } else if ($request->ay != '' && $request->semester != '' && $request->dept != '' && $request->course != '') {
                $get_courses = ToolsCourse::where(['id' => $request->course])->select('name')->first();

                $make_enroll = '%' . '/' . $get_courses->name . '/' . $request->ay . '/' . $request->semester . '/' . '%';
                $get_enrolls = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "$make_enroll")->select('id')->get();
                if (count($get_enrolls) > 0) {
                    foreach ($get_enrolls as $enroll) {
                        array_push($enroll_master, $enroll->id);
                    }
                }
            }

            if (count($enroll_master) <= 0) {
                if ($request->from_date != '' && $request->to_date != '') {
                    $list = IssueCertificate::with('student', 'class')->where(['status' => $status])->whereBetween('date', [$request->from_date, $request->to_date])->select('id', 'user_name_id', 'enroll_master', 'date', 'certificate', 'approved_date', 'purpose', 'status')->get();
                } elseif ($request->from_date != '' && $request->to_date == '') {
                    $list = IssueCertificate::with('student', 'class')->where(['status' => $status])->whereBetween('date', [$request->from_date, Carbon::now()->format('Y-m-d')])->select('id', 'user_name_id', 'enroll_master', 'date', 'certificate', 'approved_date', 'purpose', 'status')->get();
                } elseif ($request->from_date == '' && $request->to_date != '') {
                    $list = IssueCertificate::with('student', 'class')->where(['status' => $status])->whereBetween('date', [Carbon::now()->format('Y-m-d'), $request->to_date])->select('id', 'user_name_id', 'enroll_master', 'date', 'certificate', 'approved_date', 'purpose', 'status')->get();
                } else {
                    $list = IssueCertificate::with('student', 'class')->where(['status' => $status])->select('id', 'user_name_id', 'enroll_master', 'date', 'certificate', 'approved_date', 'purpose', 'status')->get();
                }
            } else {
                if ($request->from_date != '' && $request->to_date != '') {
                    $list = IssueCertificate::with('student', 'class')->where(['status' => $status])->whereIn('enroll_master', $enroll_master)->whereBetween('date', [$request->from_date, $request->to_date])->select('id', 'user_name_id', 'enroll_master', 'date', 'certificate', 'approved_date', 'purpose', 'status')->get();
                } elseif ($request->from_date != '' && $request->to_date == '') {
                    $list = IssueCertificate::with('student', 'class')->where(['status' => $status])->whereIn('enroll_master', $enroll_master)->whereBetween('date', [$request->from_date, Carbon::now()->format('Y-m-d')])->select('id', 'user_name_id', 'enroll_master', 'date', 'certificate', 'approved_date', 'purpose', 'status')->get();
                } elseif ($request->from_date == '' && $request->to_date != '') {
                    $list = IssueCertificate::with('student', 'class')->where(['status' => $status])->whereIn('enroll_master', $enroll_master)->whereBetween('date', [Carbon::now()->format('Y-m-d'), $request->to_date])->select('id', 'user_name_id', 'enroll_master', 'date', 'certificate', 'approved_date', 'purpose', 'status')->get();
                } else {
                    $list = IssueCertificate::with('student', 'class')->where(['status' => $status])->whereIn('enroll_master', $enroll_master)->select('id', 'user_name_id', 'enroll_master', 'date', 'certificate', 'approved_date', 'purpose', 'status')->get();
                }
            }
            if (count($list) > 0) {
                $i = 1;
                foreach ($list as $data) {

                    if ($data->class != null) {
                        $enroll = $data->class->enroll_master_number;
                        $explode = explode('/', $enroll);
                        $getCourse = ToolsCourse::where(['name' => $explode[1]])->select('short_form')->first();
                        if ($getCourse != '') {
                            $explode[1] = $getCourse->short_form;
                        }
                        $data->class = $explode[1] . ' / ' . $explode[3] . ' / ' . $explode[4];
                    }
                    if ($data->student != null) {
                        $data->name = $data->student->name . ' ( ' . $data->student->register_no . ' )';
                    } else {
                        $data->name = null;
                    }
                    $data->sn = $i;
                    $formattedDate = Carbon::createFromFormat('Y-m-d', $data->date)->format('d-m-Y');
                    $data->date = $formattedDate;
                    $i++;
                }
            }

            $table = Datatables::of($list);

            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'certificate_provision';

                if ($row->status == 1) {
                    $editGate = 'certificate_provision';
                } else {
                    $editGate = '';
                }
                $deleteGate = '';
                $crudRoutePart = 'certificate-provision';

                return view('partials.datatablesActions', compact('viewGate', 'editGate', 'deleteGate', 'crudRoutePart', 'row'));
            });

            $table->editColumn('sn', function ($row) {
                return $row->sn ? $row->sn : '';
            });

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->editColumn('class', function ($row) {
                return $row->class ? $row->class : '';
            });

            $table->editColumn('date', function ($row) {
                return $row->date ? $row->date : '';
            });

            $table->editColumn('certificate', function ($row) {
                return $row->certificate ? $row->certificate : '';
            });

            $table->editColumn('purpose', function ($row) {
                return $row->purpose ? $row->purpose : '';
            });

            $table->editColumn('approved_date', function ($row) {
                return $row->approved_date ? $row->approved_date : '';
            });

            $table->rawColumns(['actions']);

            return $table->make(true);
        }
        return view('admin.certificate_provision.index', compact('departments', 'academic_years'));
    }

    public function show(Request $request)
    {
        $getData = '';
        $data = IssueCertificate::where(['id' => $request->id])->select('id', 'user_name_id', 'date', 'certificate', 'purpose', 'status', 'approved_date', 'message')->first();
        if ($data != '') {
            $user_name_id = $data->user_name_id;
            $getData = DB::table('students')
                ->join('personal_details', 'students.user_name_id', '=', 'personal_details.user_name_id')
                ->join('parent_details', 'students.user_name_id', '=', 'parent_details.user_name_id')
                ->where('students.user_name_id', '=', $user_name_id)
                ->where('students.deleted_at', '=', null)
                ->select('students.name', 'students.roll_no','students.register_no', 'students.enroll_master_id', 'personal_details.gender', 'parent_details.father_name')
                ->first();
            if ($getData != '') {
                if ($getData->enroll_master_id != null && $getData->enroll_master_id != '') {
                    $getClass = CourseEnrollMaster::where(['id' => $getData->enroll_master_id])->select('enroll_master_number')->first();
                    if ($getClass != '') {
                        $explode = explode('/', $getClass->enroll_master_number);
                        $course = $explode[1];
                        $courseExplode = explode('.', $course);
                        $getData->degree = $courseExplode[0] . '.' . $courseExplode[1] . '.,';
                        $getData->course = $courseExplode[2];
                        $getData->ay = $explode[2];
                        $sem = $explode[3];
                        if ($sem == 1 || $sem == 2) {
                            $getData->year = 'First Year';
                        } else if ($sem == 3 || $sem == 4) {
                            $getData->year = 'Second Year';
                        } else if ($sem == 5 || $sem == 6) {
                            $getData->year = 'Third Year';
                        } else if ($sem == 7 || $sem == 8) {
                            $getData->year = 'Forth Year';
                        }

                        $getData->date = $data->date;
                        $getData->certificate = $data->certificate;
                        $getData->purpose = $data->purpose;
                        $getData->message = $data->message;
                        $getData->status = $data->status;
                        $getData->approved_date = $data->approved_date;

                        if ($getData->gender == 'MALE') {
                            $getData->gender = 'S/o';
                            $getData->stu_front = 'Mr.';
                            $getData->stu_gen = 'him';
                        } else {
                            $getData->gender = 'D/o';
                            $getData->stu_front = 'Ms.';
                            $getData->stu_gen = 'her';
                        }

                    }
                }
            }
        }
        return view('admin.certificate_provision.show', compact('getData'));
    }

    public function indexShow(Request $request)
    {
        $getData = '';
        $data = IssueCertificate::where(['id' => $request->id])->select('id', 'user_name_id', 'date', 'certificate', 'purpose', 'status', 'approved_date', 'action_reason')->first();
        if ($data != '') {
            $user_name_id = $data->user_name_id;
            $getData = Student::where(['user_name_id' => $user_name_id])->select('name', 'register_no', 'enroll_master_id')->first();
            if ($getData != '') {
                $getData->id = $request->id;
                if ($getData->enroll_master_id != null && $getData->enroll_master_id != '') {
                    $getClass = CourseEnrollMaster::where(['id' => $getData->enroll_master_id])->select('enroll_master_number')->first();
                    if ($getClass != '') {
                        $explode = explode('/', $getClass->enroll_master_number);
                        $getCourse = ToolsCourse::where(['name' => $explode[1]])->select('short_form')->first();
                        if ($getCourse != '') {
                            $explode[1] = $getCourse->short_form;
                        }
                        $getData->course = $explode[1] . ' / ' . $explode[3] . ' / ' . $explode[4];

                        $getData->date = $data->date;
                        $getData->status = $data->status;
                        $getData->certificate = $data->certificate;
                        $getData->purpose = $data->purpose;
                        $getData->action_reason = $data->action_reason;

                    }
                }
            }
        }

        return view('admin.certificate_provision.indexShow', compact('getData'));
    }

    public function edit(Request $request)
    {

        $getData = '';
        $data = IssueCertificate::where(['id' => $request->id])->select('user_name_id', 'date', 'certificate', 'purpose', 'status', 'approved_date','hostelcheck','hostel_no','message')->first();
        if ($data != '') {
            $user_name_id = $data->user_name_id;
            if($data->certificate == 'BONAFIDE'){
                $get_reasons = StudentBonafide::pluck('bonafide_type', 'id');
            }else{
                $get_reasons = null;
            }
            $getData = DB::table('students')
                ->join('personal_details', 'students.user_name_id', '=', 'personal_details.user_name_id')
                ->join('parent_details', 'students.user_name_id', '=', 'parent_details.user_name_id')
                ->where('students.user_name_id', '=', $user_name_id)
                ->where('students.deleted_at', '=', null)
                ->select('students.name', 'students.roll_no', 'students.register_no', 'students.enroll_master_id', 'personal_details.gender', 'parent_details.father_name')
                ->first();
            if ($getData != '') {
                $getData->id = $request->id;
                if ($getData->enroll_master_id != null && $getData->enroll_master_id != '') {
                    $getClass = CourseEnrollMaster::where(['id' => $getData->enroll_master_id])->select('enroll_master_number')->first();
                    if ($getClass != '') {
                        $explode = explode('/', $getClass->enroll_master_number);
                        $course = $explode[1];
                        $courseExplode = explode('.', $course);
                        $getData->degree = $courseExplode[0] . '.' . $courseExplode[1] . '.,';
                        $getData->course = $courseExplode[2];
                        $getData->ay = $explode[2];
                        $sem = $explode[3];
                        if ($sem == 1 || $sem == 2) {
                            $getData->year = 'First Year';
                        } else if ($sem == 3 || $sem == 4) {
                            $getData->year = 'Second Year';
                        } else if ($sem == 5 || $sem == 6) {
                            $getData->year = 'Third Year';
                        } else if ($sem == 7 || $sem == 8) {
                            $getData->year = 'Forth Year';
                        }

                        $getData->date = $data->date;
                        $getData->certificate = $data->certificate;
                        $getData->purpose = $data->purpose;
                        $getData->hostelcheck = $data->hostelcheck;
                        $getData->hostelmessage = $data->message;
                        $getData->hostel_no = $data->hostel_no;
                        $getData->status = $data->status;
                        $getData->approved_date = $data->approved_date;
                        $getData->get_reasons = $get_reasons;

                        if ($getData->gender == 'MALE') {
                            $getData->gender = 'S/o';
                            $getData->stu_front = 'Mr.';
                            $getData->stu_gen = 'him';
                        } else {
                            $getData->gender = 'D/o';
                            $getData->stu_front = 'Ms.';
                            $getData->stu_gen = 'her';
                        }

                    }
                }
            }
        }

        return view('admin.certificate_provision.edit', compact('getData'));
    }

    public function stuIndex(Request $request)
    {
        $getStudent = Student::where(['user_name_id' => auth()->user()->id])->select('enroll_master_id')->first();
        if ($getStudent != '' && $getStudent->enroll_master_id != null) {
            $list = IssueCertificate::with('student')->where(['user_name_id' => auth()->user()->id, 'enroll_master' => $getStudent->enroll_master_id])->select('id', 'user_name_id', 'date', 'purpose', 'certificate', 'purpose', 'status', 'action_reason')->orderBy('id', 'desc')->get();
        } else {
            $list = IssueCertificate::with('student')->where(['user_name_id' => auth()->user()->id])->select('id', 'user_name_id', 'date', 'purpose', 'certificate', 'purpose', 'status', 'action_reason')->orderBy('id', 'desc')->get();
        }
        if (count($list) > 0) {
            foreach ($list as $data) {
                $formattedDate = Carbon::createFromFormat('Y-m-d', $data->date)->format('d-m-Y');
                $data->date = $formattedDate;
            }
        }
        return view('admin.certificate_provision.stu_index', compact('list'));
    }

    public function stuCreate(Request $request)
    {

        $bonafideType = StudentBonafide::pluck('bonafide_type', 'id');
        return view('admin.certificate_provision.stu_create', compact('bonafideType'));
    }

    public function store(Request $request)
    {

        if (isset($request->date) && isset($request->certificate) && isset($request->purpose)) {

            $user_name_id = auth()->user()->id;
            $student = Student::where(['user_name_id' => $user_name_id])->select('enroll_master_id')->first();
            if ($student == '') {
                return response()->json(['status' => false, 'data' => 'Couldn\'t Get Your Academic Details']);
            } else {
                $enroll_master = $student->enroll_master_id;

                $store = new IssueCertificate;
                $store->user_name_id = $user_name_id;
                $store->enroll_master = $enroll_master;
                $store->date = $request->date;
                $store->purpose = $request->purpose;
                $store->certificate = $request->certificate;
                $store->purpose_type = $request->purpose_type;
                $store->hostelcheck = $request->hostelcheck;
                $store->hostel_no = $request->hostel_no;
                $store->message = $request->message;
                $store->status = 0;
                $store->save();

                $receiverArray = [];

                $getAO = DB::table('role_user')->whereIn('role_id', [1, 27])->get();
                if (count($getAO) > 0) {
                    foreach ($getAO as $ao) {
                        $checkUser = User::where(['id' => $ao->user_id])->select('id')->get();
                        if (count($checkUser) > 0) {
                            foreach ($checkUser as $user) {
                                array_push($receiverArray, $user->id);
                            }
                        }
                    }
                }

                $userAlert = new UserAlert;
                $userAlert->alert_text = auth()->user()->name . ' Applied For a ' . ucfirst($request->certificate) . ' Certificate';
                $userAlert->alert_link = url('admin/certificate-provision/' . $store->id);
                $userAlert->save();
                $userAlert->users()->sync($receiverArray);

                return response()->json(['status' => true]);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Technical Error']);
        }
    }

    public function update(Request $request)
    {

        if (isset($request->id) && isset($request->date) && isset($request->certificate) && isset($request->purpose)) {

            $update = IssueCertificate::where(['id' => $request->id])->update([
                'date' => $request->date,
                'purpose' => $request->purpose,
                'certificate' => $request->certificate,
                'hostel_no' => $request->hostel_no,
                'message' => $request->message,
                'status' => 0,
            ]);

            $receiverArray = [];

            $getAO = DB::table('role_user')->whereIn('role_id', [1, 27])->get();
            if (count($getAO) > 0) {
                foreach ($getAO as $ao) {
                    $checkUser = User::where(['id' => $ao->user_id])->select('id')->get();
                    if (count($checkUser) > 0) {
                        foreach ($checkUser as $user) {
                            array_push($receiverArray, $user->id);
                        }
                    }
                }
            }

            $userAlert = new UserAlert;
            $userAlert->alert_text = auth()->user()->name . ' Applied For a ' . ucfirst($request->certificate) . ' Certificate';
            $userAlert->alert_link = url('admin/certificate-provision/' . $request->id);
            $userAlert->save();
            $userAlert->users()->sync($receiverArray);
            if ($update == true) {
                return response()->json(['status' => true]);
            } else {
                return response()->json(['status' => false, 'data' => 'Not Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Technical Error']);
        }
    }

    public function updateAction(Request $request)
    {

        if (isset($request->id) && isset($request->status)) {

            $update = IssueCertificate::where(['id' => $request->id])->update([
                'action_reason' => $request->action_reason,
                'status' => (int) $request->status,
            ]);

            $getStudent = IssueCertificate::where(['id' => $request->id])->select('user_name_id', 'certificate')->first();
            if ($getStudent != '') {
                $user_name_id = $getStudent->user_name_id;
                $certificate = $getStudent->certificate;
            } else {
                $user_name_id = null;
                $certificate = null;
            }
            if ($request->status == 2) {
                $text = ' Your ' . ucfirst($certificate) . ' Certificate Is Approved and Signed';
            } else if ($request->status == 3) {
                $text = 'Need Revision For Your ' . ucfirst($certificate) . ' Certificate Application';
            } else if ($request->status == 4) {
                $text = ' Your ' . ucfirst($certificate) . ' Certificate Application Is Rejected';
            } else {
                $text = ' Your ' . ucfirst($certificate) . ' Certificate Is Waiting For Principal Sign';
            }

            if ($user_name_id != null && $certificate != null) {
                $userAlert = new UserAlert;
                $userAlert->alert_text = $text;
                $userAlert->alert_link = url('admin/student-apply-certificate/show/' . $request->id);
                $userAlert->save();
                $userAlert->users()->sync($user_name_id);
            }
            if ($update == true) {
                return response()->json(['status' => true]);
            } else {
                return response()->json(['status' => false, 'data' => 'Not Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Technical Error']);
        }
    }

    public function updatePurpose(Request $request)
    {

        if (isset($request->id)) {

            $update = IssueCertificate::where(['id' => $request->id])->update([
                'purpose' => $request->purpose,
            ]);

            $getStudent = IssueCertificate::where(['id' => $request->id])->select('user_name_id', 'certificate')->first();
            if ($getStudent != '') {
                $user_name_id = $getStudent->user_name_id;
                $certificate = $getStudent->certificate;
            } else {
                $user_name_id = null;
                $certificate = null;
            }

            $text = ' Your ' . ucfirst($certificate) . ' Certificate Application\'s Purpose Is Modified';

            if ($user_name_id != null && $certificate != null) {
                $userAlert = new UserAlert;
                $userAlert->alert_text = $text;
                $userAlert->alert_link = url('admin/student-apply-certificate/show/' . $request->id);
                $userAlert->save();
                $userAlert->users()->sync($user_name_id);
            }
            if ($update == true) {
                return response()->json(['status' => true]);
            } else {
                return response()->json(['status' => false, 'data' => 'Not Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Technical Error']);
        }
    }

    public function getDetails()
    {
        $user_name_id = auth()->user()->id;
        $getData = DB::table('students')
            ->join('personal_details', 'students.user_name_id', '=', 'personal_details.user_name_id')
            ->join('parent_details', 'students.user_name_id', '=', 'parent_details.user_name_id')
            ->where('students.user_name_id', '=', $user_name_id)
            ->where('students.deleted_at', '=', null)
            ->select('students.name', 'students.roll_no', 'students.register_no', 'students.enroll_master_id', 'personal_details.gender', 'parent_details.father_name')
            ->first();
        if ($getData != '') {
            if ($getData->enroll_master_id != null && $getData->enroll_master_id != '') {
                $getClass = CourseEnrollMaster::where(['id' => $getData->enroll_master_id])->select('enroll_master_number')->first();
                if ($getClass != '') {
                    $explode = explode('/', $getClass->enroll_master_number);
                    $course = $explode[1];
                    $courseExplode = explode('.', $course);
                    $getData->degree = $courseExplode[0] . '.' . $courseExplode[1] . '.,';
                    $getData->course = $courseExplode[2];
                    $getData->ay = $explode[2];
                    $sem = $explode[3];
                    if ($sem == 1 || $sem == 2) {
                        $getData->year = 'First Year';
                    } else if ($sem == 3 || $sem == 4) {
                        $getData->year = 'Second Year';
                    } else if ($sem == 5 || $sem == 6) {
                        $getData->year = 'Third Year';
                    } else if ($sem == 7 || $sem == 8) {
                        $getData->year = 'Forth Year';
                    }

                    if ($getData->gender == 'MALE') {
                        $getData->gender = 'S/o';
                        $getData->stu_front = 'Mr.';
                        $getData->stu_gen = 'him';
                    } else {
                        $getData->gender = 'D/o';
                        $getData->stu_front = 'Ms.';
                        $getData->stu_gen = 'her';
                    }
                    return response()->json(['status' => true, 'data' => $getData]);
                } else {
                    return response()->json(['status' => false, 'data' => 'Class Not Found']);
                }
            } else {
                return response()->json(['status' => false, 'data' => 'Class Not Found']);
            }
        } else {
            return response()->json(['status' => false]);
        }
    }

    public function printCertificate(Request $request)
    {
        $getData = '';
        $data = IssueCertificate::where(['id' => $request->id])->select('user_name_id', 'date', 'certificate', 'purpose', 'status', 'approved_date', 'message')->first();
        if ($data != '') {
            $user_name_id = $data->user_name_id;
            $getData = DB::table('students')
                ->join('personal_details', 'students.user_name_id', '=', 'personal_details.user_name_id')
                ->join('parent_details', 'students.user_name_id', '=', 'parent_details.user_name_id')
                ->where('students.user_name_id', '=', $user_name_id)
                ->where('students.deleted_at', '=', null)
                ->select('students.name', 'students.roll_no', 'students.register_no', 'students.enroll_master_id', 'personal_details.gender', 'parent_details.father_name')
                ->first();
            if ($getData != '') {
                $getData->id = $request->id;
                if ($getData->enroll_master_id != null && $getData->enroll_master_id != '') {
                    $getClass = CourseEnrollMaster::where(['id' => $getData->enroll_master_id])->select('enroll_master_number')->first();
                    if ($getClass != '') {
                        $explode = explode('/', $getClass->enroll_master_number);
                        $course = $explode[1];
                        $courseExplode = explode('.', $course);
                        $getData->degree = $courseExplode[0] . '.' . $courseExplode[1] . '.,';
                        $getData->course = $courseExplode[2];
                        $getData->ay = $explode[2];
                        $sem = $explode[3];
                        if ($sem == 1 || $sem == 2) {
                            $getData->year = 'First Year';
                        } else if ($sem == 3 || $sem == 4) {
                            $getData->year = 'Second Year';
                        } else if ($sem == 5 || $sem == 6) {
                            $getData->year = 'Third Year';
                        } else if ($sem == 7 || $sem == 8) {
                            $getData->year = 'Forth Year';
                        }

                        $getData->date = $data->date;
                        $getData->certificate = $data->certificate;
                        $getData->purpose = $data->purpose;
                        $getData->status = $data->status;
                        $getData->approved_date = $data->approved_date;
                        $getData->message = $data->message;

                        if ($getData->gender == 'MALE') {
                            $getData->gender = 'S/o';
                            $getData->stu_front = 'Mr.';
                            $getData->stu_gen = 'him';
                        } else {
                            $getData->gender = 'D/o';
                            $getData->stu_front = 'Ms.';
                            $getData->stu_gen = 'her';
                        }

                    }
                }
                $final_data = ['getData' => $getData];

                $pdf = PDF::loadView('admin.certificate_provision.certificatePDF', $final_data);

                return $pdf->stream($getData->certificate . '.pdf');
            } else {
                return back();
            }
        } else {
            return back();
        }

    }

    public function bonafideReson(Request $request)
    {

        $bonafideCertificate = $request->input('certificate');
        $bonfideType = '';

        if ($bonafideCertificate) {
            $bonfideType = StudentBonafide::pluck('bonafide_type', 'id');
        } else {
            $bonfideType = false;
        }
        return response()->json(['data' => $bonfideType]);

    }

}
