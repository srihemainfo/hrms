<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\ClassRoom;
use App\Models\CourseEnrollMaster;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectAllotment;
use App\Models\SubjectRegistration;
use App\Models\SubjectType;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use App\Models\ToolssyllabusYear;
use App\Models\User;
use App\Models\UserAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class SubjectRegistrationController extends Controller
{
    use CsvImportTrait;

    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $currentClasses = Session::get('currentClasses');

    //         $query = SubjectRegistration::with(['students', 'subjects', 'enroll_masters'])->whereIn('enroll_master', $currentClasses)->groupBy('student_name', 'user_name_id', 'enroll_master')->select('student_name', 'user_name_id', 'enroll_master')->get();

    //         $table = Datatables::of($query);

    //         $table->addColumn('placeholder',  );
    //         $table->addColumn('actions', '&nbsp;');

    //         $table->editColumn('actions', function ($row) {
    //             $row->id = $row->register_no;
    //             $viewGate = 'subject_registration_show';
    //             $editGate = '';
    //             $deleteGate = '';
    //             $crudRoutePart = 'subject-registration';

    //             return view('partials.datatablesActions', compact(
    //                 'viewGate',
    //                 'editGate',
    //                 'deleteGate',
    //                 'crudRoutePart',
    //                 'row'
    //             ));
    //         });

    //         $table->editColumn('register_no', function ($row) {
    //             return $row->register_no ? $row->register_no : '';
    //         });
    //         $table->addColumn('student_name', function ($row) {

    //             return $row->students ? $row->students->name : '';
    //         });
    //         $table->addColumn('enroll_master', function ($row) {
    //             return $row->enroll_masters ? $row->enroll_masters->enroll_master_number : '';
    //         });

    //         $table->rawColumns(['actions', 'placeholder']);

    //         return $table->make(true);
    //     }

    //     return view('admin.subjectRegistration.index');
    // }

    public function student()
    {

        $user = auth()->user()->id;

        $enrollMaster = Student::where('user_name_id', $user)->first();

        if ($enrollMaster != '') {
            $CourseEnrollMaster = CourseEnrollMaster::find($enrollMaster->enroll_master_id);

            if ($CourseEnrollMaster) {
                $enrollName = $CourseEnrollMaster->enroll_master_number;
                $enrollArray = explode('/', $enrollName);

                if ($enrollArray) {
                    $getCourse = ToolsCourse::where('name', $enrollArray[1])->first();
                    if ($getCourse) {
                        $department = $getCourse->department_id;
                        $courseId = $getCourse->id;
                    } else {
                        $department = '';
                        $courseId = '';
                    }
                    $semester = Semester::where('semester', $enrollArray[3])->first();
                    if ($semester) {
                        $semId = $semester->id;
                    } else {
                        $semId = '';
                    }

                    $getAcademicYear = AcademicYear::where('name', $enrollArray[2])->first();
                    if ($getAcademicYear) {
                        $accId = $getAcademicYear->id;
                    } else {
                        $accId = '';
                    }

                    //Department id change is changed 1 & 2 year for all Department control below S&H Department
                    if ($semId == 1 || $semId == 2) {
                        $department = 5;
                    }
                }

                if ($department != '' && $courseId != '' && $semId != '' && $accId != '') {
                    $allotedSubjects = SubjectAllotment::where([
                        'department' => $department,
                        // 'department' => 5,
                        'semester' => $semId,
                        'course' => $courseId,
                        'academic_year' => $accId,
                    ])->get();
                    // $allotedSubjects = SubjectAllotment::where(['department' => '2', 'semester' => '3', 'course' => '3', 'academic_year' => '5'])->get();

                    if ($allotedSubjects) {
                        $regular = [];
                        $professional = [];
                        $open = [];
                        $others = [];
                        $honors = [];

                        foreach ($allotedSubjects as $subject) {
                            $subject->subjects->subject_type_id = null;
                            $get_sub = Subject::where('id', $subject->subject_id)->first();
                            if ($get_sub) {
                                $get_sub_type = SubjectType::where('id', $get_sub->subject_type_id)->first();
                                if ($get_sub_type) {
                                    $subject->subjects->subject_type_id = $get_sub_type->name;
                                }
                            }

                            if ($subject->category == 'Regular Subject') {
                                array_push($regular, $subject);
                            }
                            if ($subject->category == 'Professional Electives') {
                                array_push($professional, $subject);
                            }
                            if ($subject->category == 'Open Electives') {
                                array_push($open, $subject);
                            }
                            if ($subject->category == 'Others') {
                                array_push($others, $subject);
                            }

                            if ($subject->category == 'Honors Degree') {
                                array_push($honors, $subject);
                            }
                        }
                        // dd($professional);
                    }
                }
            }
        }
        $studentID = auth()->user()->id;
        $statusCheck = null;

        $studentDetails = Student::where('user_name_id', $studentID)->first();
        // dd($studentDetails);

        if ($studentDetails != '') {
            // dd($studentDetails->Register_No);

            $stuStatus = SubjectRegistration::where(['user_name_id' => $studentID, 'enroll_master' => $studentDetails->enroll_master_id])->first();
            if ($stuStatus) {
                $statusCheck = 'notok';
                // $this->show($stuStatus->id);
                return redirect()->route('admin.subjectRegistration.show', ['id' => $stuStatus->id]);
            } else {
                $statusCheck = 'ok';
            }
        }
        return view('admin.subjectRegistration.studentIndex', compact('regular', 'professional', 'open', 'others', 'statusCheck'));
    }

    public function store(Request $request)
    {
        $role_ids = auth()->user()->roles[0]->id;
        if ($role_ids == 1 || $role_ids == 14 || $role_ids == 13) {
            $status = 1;
        }

        if ($role_ids == 10) {
            $status = 0;
        }
        if ($role_ids == 3 || $role_ids == 4 || $role_ids == 5 || $role_ids == 6 || $role_ids == 7 || $role_ids == 8 || $role_ids == 9 || $role_ids == 10 || $role_ids == 18 || $role_ids == 19) {
            $status = 0;
        }

        if ($role_ids == 1) {
            $status = 1;
        }

        if ($request) {
            if (isset($request->user_name_id) && !empty($request->user_name_id)) {

                $studentDetails = Student::where('user_name_id', $request->user_name_id)->first();
                $studentName = $studentDetails->name;
                $user_name_id = $request->user_name_id;
                $studentEnroll = $studentDetails->enroll_master_id;
                $firstSerch = ClassRoom::where('name', $studentEnroll)->first();
                if ($firstSerch) {
                    $classIncharge = $firstSerch->class_incharge;
                    $dept = $firstSerch->department_id;
                } else {
                    $classIncharge = null;
                    $dept = null;
                }
                $receiverHod = [];
                if ($dept != null) {
                    $getDept = ToolsDepartment::where(['id' => $dept])->select('name')->first();
                    if ($getDept != '') {
                        $getHod = DB::table('role_user')->where(['role_id' => 14])->select('user_id')->get();
                        if (count($getHod) > 0) {
                            foreach ($getHod as $hod) {
                                $checkUser = User::where(['id' => $hod->user_id, 'dept' => $getDept->name])->select('id')->get();
                                if (count($checkUser) > 0) {
                                    foreach ($checkUser as $user) {
                                        array_push($receiverHod, $user->id);
                                    }
                                }
                            }
                        }
                    }
                }
                $studentEnroll = $studentDetails->enroll_master->id ?? '';
                $studentReg = $studentDetails->register_no;

                SubjectRegistration::where(['user_name_id' => $user_name_id, 'enroll_master' => $studentEnroll])->delete();
                if (isset($request->selectedSubjects) && !empty($request->selectedSubjects)) {
                    $subjects = $request->selectedSubjects;

                    if ($studentDetails) {

                        if ($classIncharge != null) {
                            foreach ($subjects as $subject) {
                                $subjectAlot = SubjectAllotment::find($subject);

                                if ($subjectAlot) {
                                    $subCategory = $subjectAlot->category;
                                    $subID = $subjectAlot->subject_id;

                                    if ($subCategory != '' && $subID != '') {
                                        SubjectRegistration::create([
                                            'register_no' => $studentReg,
                                            'enroll_master' => $studentEnroll,
                                            'subject_id' => $subID,
                                            'student_name' => $studentName,
                                            'status' => $status,
                                            'user_name_id' => $user_name_id,
                                            'category' => $subCategory,
                                            'class_incharge' => $classIncharge,
                                            'dept' => $dept,
                                        ]);
                                    }
                                }
                            }
                        } else {
                            return back()->with('errors', 'Class Incharge not Assigned');
                        }
                    }
                }

                if (isset($request->selectedProfessional) && !empty($request->selectedProfessional)) {
                    $professionalSubjects = $request->selectedProfessional;
                    if ($classIncharge != null) {
                        foreach ($professionalSubjects as $professionalSubject) {
                            $subjectAlot = SubjectAllotment::find($professionalSubject);

                            if ($subjectAlot) {
                                $studentDetails = Student::where('user_name_id', $request->user_name_id)->first();

                                if ($studentDetails != '') {
                                    $studentEnroll = $studentDetails->enroll_master->id ?? '';
                                    $studentReg = $studentDetails->register_no;
                                    $user_name_id = $studentDetails->user_name_id;
                                    $subCategory = $subjectAlot->category;
                                    $subID = $subjectAlot->subject_id;

                                    if ($studentEnroll != '' && $studentReg != '' && $subCategory != '' && $subID != '') {
                                        SubjectRegistration::create([
                                            'register_no' => $studentReg,
                                            'enroll_master' => $studentEnroll,
                                            'subject_id' => $subID,
                                            'student_name' => $studentName,
                                            'status' => $status,
                                            'user_name_id' => $user_name_id,
                                            'category' => $subCategory,
                                            'class_incharge' => $classIncharge,
                                            'dept' => $dept,
                                        ]);
                                    }
                                }
                            }
                        }
                    } else {
                        return back()->with('errors', 'Class Incharge not Assigned');
                    }
                }

                if (isset($request->selectedOpen) && !empty($request->selectedOpen)) {
                    $openSubjects = $request->selectedOpen;

                    if ($classIncharge != null) {
                        foreach ($openSubjects as $openSubject) {
                            $subjectAlot = SubjectAllotment::find($openSubject);

                            if ($subjectAlot) {
                                $studentDetails = Student::where('user_name_id', $request->user_name_id)->first();

                                if ($studentDetails != '') {
                                    $studentEnroll = $studentDetails->enroll_master->id ?? '';
                                    $studentReg = $studentDetails->register_no;
                                    $user_name_id = $studentDetails->user_name_id;
                                    $subCategory = $subjectAlot->category;
                                    $subID = $subjectAlot->subject_id;

                                    if ($studentEnroll != '' && $studentReg != '' && $subCategory != '' && $subID != '') {
                                        SubjectRegistration::create([
                                            'register_no' => $studentReg,
                                            'enroll_master' => $studentEnroll,
                                            'subject_id' => $subID,
                                            'student_name' => $studentName,
                                            'user_name_id' => $user_name_id,
                                            'status' => $status,
                                            'category' => $subCategory,
                                            'class_incharge' => $classIncharge,
                                            'dept' => $dept,
                                        ]);
                                    }
                                }
                            }
                        }
                    } else {
                        return back()->with('errors', 'Class Incharge not Assigned');
                    }
                }
                if (isset($request->selectedOthers) && !empty($request->selectedOthers)) {
                    $otherSubjects = $request->selectedOthers;

                    foreach ($otherSubjects as $otherSubject) {
                        $subjectAlot = SubjectAllotment::find($otherSubject);

                        if ($subjectAlot) {
                            $studentDetails = Student::where('user_name_id', $request->user_name_id)->first();

                            if ($studentDetails != '') {
                                $studentEnroll = $studentDetails->enroll_master->id ?? '';
                                $studentReg = $studentDetails->register_no;
                                $user_name_id = $studentDetails->user_name_id;
                                $subCategory = $subjectAlot->category;
                                $subID = $subjectAlot->subject_id;
                                if ($classIncharge != null) {
                                    if ($studentEnroll != '' && $studentReg != '' && $subCategory != '' && $subID != '') {

                                        SubjectRegistration::create([
                                            'register_no' => $studentReg,
                                            'enroll_master' => $studentEnroll,
                                            'subject_id' => $subID,
                                            'student_name' => $studentName,
                                            'status' => $status,
                                            'user_name_id' => $user_name_id,
                                            'category' => $subCategory,
                                            'class_incharge' => $classIncharge,
                                            'dept' => $dept,
                                        ]);
                                    }
                                } else {
                                    return back()->with('errors', 'Class Incharge not Assigned');
                                }
                            }
                        }
                    }
                }
                $id = SubjectRegistration::where(['user_name_id' => $user_name_id, 'enroll_master' => $studentEnroll])->first();
                if ($id != '') {
                    if ($status == 0) {
                        $userAlert = new UserAlert;
                        $userAlert->alert_text = $studentName . ' Send a Subject Registration Request';
                        $userAlert->alert_link = url('admin/subject-registration/show/' . $id);
                        $userAlert->save();
                        $userAlert->users()->sync($receiverHod);
                    }
                    return redirect()->route('admin.subjectRegistration.show', ['id' => $id]);
                } else {
                    return back();
                }
            } else {
                $studentName = auth()->user()->name;
                $studentID = auth()->user()->id;
                $studentDetails = Student::where('user_name_id', $studentID)->first();

                if ($studentDetails != '') {
                    $studentEnroll = $studentDetails->enroll_master->id ?? '';
                    $firstSerch = ClassRoom::where('name', $studentEnroll)->first();
                    if ($firstSerch != '') {
                        $classIncharge = $firstSerch->class_incharge;
                    } else {
                        $classIncharge = null;
                    }
                }

                if (isset($request->selectedSubjects) && !empty($request->selectedSubjects)) {
                    $subjects = $request->selectedSubjects;

                    foreach ($subjects as $subject) {
                        $subjectAlot = SubjectAllotment::find($subject);

                        if ($subjectAlot) {
                            $studentDetails = Student::where('user_name_id', $studentID)->first();
                            // $studentEnroll = $studentDetails->enroll_master->id;

                            if ($studentDetails != '') {
                                $studentEnroll = $studentDetails->enroll_master->id;
                                $studentReg = $studentDetails->register_no;
                                $user_name_id = $studentDetails->user_name_id;
                                $subCategory = $subjectAlot->category;
                                $subID = $subjectAlot->subject_id;
                                $firstSerch = ClassRoom::where('name', $studentEnroll)->first();
                                // dd($studentReg);
                                if ($firstSerch) {
                                    $classIncharge = $firstSerch->class_incharge;
                                    $dept = $firstSerch->department_id;
                                } else {
                                    $classIncharge = null;
                                    $dept = null;
                                }
                                // dd($dept);
                                if ($classIncharge != null) {

                                    if ($studentEnroll != '' && $studentReg != '' && $subCategory != '' && $subID != '') {
                                        SubjectRegistration::create([
                                            'register_no' => $studentReg,
                                            'enroll_master' => $studentEnroll,
                                            'subject_id' => $subID,
                                            'student_name' => $studentName,
                                            'status' => '0',
                                            'user_name_id' => $user_name_id,
                                            'category' => $subCategory,
                                            'class_incharge' => $classIncharge,
                                            'dept' => $dept,
                                        ]);
                                    }
                                } else {
                                    return back()->with('errors', 'Class Incharge not Assigned');
                                }
                            }
                        }
                    }
                }

                if (isset($request->selectedProfessional) && !empty($request->selectedProfessional)) {
                    $professionalSubjects = $request->selectedProfessional;

                    foreach ($professionalSubjects as $professionalSubject) {
                        $subjectAlot = SubjectAllotment::find($professionalSubject);

                        if ($subjectAlot) {
                            $studentDetails = Student::where('user_name_id', $studentID)->first();
                            // $studentEnroll = $studentDetails->enroll_master->id;

                            if ($studentDetails != '') {
                                $studentEnroll = $studentDetails->enroll_master->id;
                                $studentReg = $studentDetails->register_no;
                                $user_name_id = $studentDetails->user_name_id;
                                $subCategory = $subjectAlot->category;
                                $subID = $subjectAlot->subject_id;
                                $firstSerch = ClassRoom::where('name', $studentEnroll)->first();
                                if ($firstSerch) {
                                    $classIncharge = $firstSerch->class_incharge;
                                    $dept = $firstSerch->department_id;
                                } else {
                                    $classIncharge = null;
                                    $dept = null;
                                }
                                if ($classIncharge != null) {

                                    if ($studentEnroll != '' && $studentReg != '' && $subCategory != '' && $subID != '') {
                                        SubjectRegistration::create([
                                            'register_no' => $studentReg,
                                            'enroll_master' => $studentEnroll,
                                            'subject_id' => $subID,
                                            'student_name' => $studentName,
                                            'user_name_id' => $user_name_id,
                                            'status' => '0',
                                            'category' => $subCategory,
                                            'class_incharge' => $classIncharge,
                                            'dept' => $dept,
                                        ]);
                                    }
                                } else {
                                    return back()->with('errors', 'Class Incharge not Assigned');
                                }
                            }
                        }
                    }
                }

                if (isset($request->selectedOpen) && !empty($request->selectedOpen)) {
                    $openSubjects = $request->selectedOpen;

                    foreach ($openSubjects as $openSubject) {
                        $subjectAlot = SubjectAllotment::find($openSubject);

                        if ($subjectAlot) {
                            $studentDetails = Student::where('user_name_id', $studentID)->first();

                            if ($studentDetails != '') {
                                $studentEnroll = $studentDetails->enroll_master->id;
                                $studentReg = $studentDetails->register_no;
                                $user_name_id = $studentDetails->user_name_id;
                                $subCategory = $subjectAlot->category;
                                $subID = $subjectAlot->subject_id;
                                $firstSerch = ClassRoom::where('name', $studentEnroll)->first();
                                if ($firstSerch) {
                                    $classIncharge = $firstSerch->class_incharge;
                                    $dept = $firstSerch->department_id;
                                } else {
                                    $classIncharge = null;
                                    $dept = null;
                                }
                                if ($classIncharge != null) {

                                    if ($studentEnroll != '' && $studentReg != '' && $subCategory != '' && $subID != '') {
                                        SubjectRegistration::create([
                                            'register_no' => $studentReg,
                                            'enroll_master' => $studentEnroll,
                                            'subject_id' => $subID,
                                            'student_name' => $studentName,
                                            'user_name_id' => $user_name_id,
                                            'status' => '0',
                                            'category' => $subCategory,
                                            'class_incharge' => $classIncharge,
                                            'dept' => $dept,
                                        ]);
                                    }
                                } else {
                                    return back()->with('errors', 'Class Incharge not Assigned');
                                }
                            }
                        }
                    }
                }
                if (isset($request->selectedOthers) && !empty($request->selectedOthers)) {
                    $otherSubjects = $request->selectedOthers;

                    foreach ($otherSubjects as $otherSubject) {
                        $subjectAlot = SubjectAllotment::find($otherSubject);

                        if ($subjectAlot) {
                            $studentDetails = Student::where('user_name_id', $studentID)->first();

                            if ($studentDetails != '') {
                                $studentEnroll = $studentDetails->enroll_master->id;
                                $studentReg = $studentDetails->register_no;
                                $user_name_id = $studentDetails->user_name_id;
                                $subCategory = $subjectAlot->category;
                                $subID = $subjectAlot->subject_id;
                                $firstSerch = ClassRoom::where('name', $studentEnroll)->first();
                                if ($firstSerch) {
                                    $classIncharge = $firstSerch->class_incharge;
                                    $dept = $firstSerch->department_id;
                                } else {
                                    $classIncharge = null;
                                    $dept = null;
                                }

                                if ($classIncharge != null) {
                                    if ($studentEnroll != '' && $studentReg != '' && $subCategory != '' && $subID != '') {
                                        SubjectRegistration::create([
                                            'register_no' => $studentReg,
                                            'enroll_master' => $studentEnroll,
                                            'subject_id' => $subID,
                                            'student_name' => $studentName,
                                            'user_name_id' => $user_name_id,
                                            'status' => '0',
                                            'category' => $subCategory,
                                            'class_incharge' => $classIncharge,
                                            'dept' => $dept,
                                        ]);
                                    }
                                } else {
                                    return back()->with('errors', 'Class Incharge not Assigned');
                                }
                            }
                        }
                    }
                }
                $getId = SubjectRegistration::where([
                    'enroll_master' => $studentEnroll,
                    'status' => '0',
                    'user_name_id' => auth()->user()->id,
                ])->select('id')->first();

                if ($classIncharge != null && $getId != '') {
                    $userAlert = new UserAlert;
                    $userAlert->alert_text = auth()->user()->name . ' Send a Subject Registration Request';
                    $userAlert->alert_link = url('admin/subject-registration/show/' . $getId->id);
                    $userAlert->save();
                    $userAlert->users()->sync($classIncharge);
                }
            }

            return back();
        }
    }

    public function index(Request $request)
    {
        $role_id = auth()->user()->roles[0]->id;
        $role_type_id = auth()->user()->roles[0]->type_id;
        $deptID = null;
        if ($role_id == 14) {
            $deptId = ToolsDepartment::where('name', auth()->user()->dept)->select('id')->first();
            if ($deptId) {
                $deptID = $deptId->id;
            }
            if ($deptID != 5) {
                $courses = ToolsCourse::where('department_id', $deptID)->select('short_form', 'name')->get();
                $semesters = [3, 4, 5, 6, 7, 8];
            } else {
                $courses = ToolsCourse::select('name','short_form')->get();
                $semesters = [1, 2];
            }
        } else {
            $courses = ToolsCourse::select('name','short_form')->get();
            $semesters = [1, 2, 3, 4, 5, 6, 7, 8];
        }
        $regulations = ToolssyllabusYear::pluck('name', 'id');
        $ays = AcademicYear::pluck('name', 'id');
        $batches = Batch::pluck('name', 'id');
        return view('admin.subjectRegistration.allIndex', compact('regulations', 'ays', 'batches', 'courses', 'semesters', 'role_type_id', 'role_id'));
    }

    public function getData(Request $request)
    {
        $type_id = auth()->user()->roles[0]->type_id;
        $role_id = auth()->user()->roles[0]->id;
        $deptId = ToolsDepartment::where('name', auth()->user()->dept)->first();
        if ($deptId) {
            $deptID = $deptId->id;
        } else {
            $deptID = null;
        }

        $currentClasses = Session::get('currentClasses');
        if ($type_id == 1 || $type_id == 3) {

            $query = SubjectRegistration::with(['students:user_name_id,name,register_no', 'enroll_masters'])->whereIn('enroll_master', $currentClasses)->where(['status' => 0, 'class_incharge' => auth()->user()->id])->select('user_name_id', 'enroll_master')->groupBy('user_name_id', 'enroll_master')->get();
            if (count($query) > 0) {
                foreach ($query as $data) {
                    $getId = SubjectRegistration::where(['user_name_id' => $data->user_name_id, 'enroll_master' => $data->enroll_master])->select('id')->first();
                    if ($getId != '') {
                        $data->id = $getId->id;
                    }
                }
            }
        } else if ($role_id == 14) {
                if ($deptID != 5) {
                    $courses = ToolsCourse::where('department_id', $deptID)->get();
                    $theEnrolls = [];
                    if (count($courses) > 0) {
                        foreach ($courses as $course) {

                            $make_enroll_3 = '%/' . $course->name . '/%/3/%';
                            $make_enroll_4 = '%/' . $course->name . '/%/4/%';
                            $make_enroll_5 = '%/' . $course->name . '/%/5/%';
                            $make_enroll_6 = '%/' . $course->name . '/%/6/%';
                            $make_enroll_7 = '%/' . $course->name . '/%/7/%';
                            $make_enroll_8 = '%/' . $course->name . '/%/8/%';

                            $variablesToCheck = [$make_enroll_3, $make_enroll_4, $make_enroll_5, $make_enroll_6, $make_enroll_7, $make_enroll_8];
                            $getEnroll = CourseEnrollMaster::whereIn('id', $currentClasses)->where(function ($query) use ($variablesToCheck) {
                                foreach ($variablesToCheck as $variable) {
                                    $query->orWhere('enroll_master_number', 'LIKE', "%{$variable}");
                                }
                            })->select('id')->get();

                            if (count($getEnroll) > 0) {
                                foreach ($getEnroll as $enroll) {
                                    array_push($theEnrolls, $enroll->id);
                                }
                            }
                        }
                    }
                    if (count($theEnrolls) > 0) {
                        $query = SubjectRegistration::with('students:user_name_id,name,register_no', 'enroll_masters')->whereIn('enroll_master', $theEnrolls)->select('user_name_id', 'enroll_master')->groupBy('user_name_id', 'enroll_master')->get();
                        if (count($query) > 0) {
                            foreach ($query as $data) {
                                $getId = SubjectRegistration::where(['user_name_id' => $data->user_name_id, 'enroll_master' => $data->enroll_master])->select('id')->first();
                                if ($getId != '') {
                                    $data->id = $getId->id;
                                }
                            }
                        }
                    } else {
                        $query = [];
                    }
                } else {
                    $courses = ToolsCourse::select('name')->get();
                    $theEnrolls = [];
                    if (count($courses) > 0) {
                        foreach ($courses as $course) {
                            $make_enroll_1 = '%/' . $course->name . '/%/1/%';
                            $make_enroll_2 = '%/' . $course->name . '/%/2/%';
                            $variablesToCheck = [$make_enroll_1, $make_enroll_2];
                            $getEnroll = CourseEnrollMaster::whereIn('id', $currentClasses)->where(function ($query) use ($variablesToCheck) {
                                foreach ($variablesToCheck as $variable) {
                                    $query->orWhere('enroll_master_number', 'LIKE', "%{$variable}");
                                }
                            })->select('id')->get();
                            if (count($getEnroll) > 0) {
                                foreach ($getEnroll as $enroll) {
                                    array_push($theEnrolls, $enroll->id);
                                }
                            }
                        }
                    }
                    if (count($theEnrolls) > 0) {
                        $query = SubjectRegistration::with('students:user_name_id,name,register_no', 'enroll_masters')->whereIn('enroll_master', $theEnrolls)->select('user_name_id', 'enroll_master')->groupBy('user_name_id', 'enroll_master')->get();
                        if (count($query) > 0) {
                            foreach ($query as $data) {
                                $getId = SubjectRegistration::where(['user_name_id' => $data->user_name_id, 'enroll_master' => $data->enroll_master])->select('id')->first();
                                if ($getId != '') {
                                    $data->id = $getId->id;
                                }
                            }
                        }
                    } else {
                        $query = [];
                    }
                }
        } else {
            $query = SubjectRegistration::with(['students:user_name_id,name,register_no', 'enroll_masters'])->whereIn('enroll_master', $currentClasses)->select('user_name_id', 'enroll_master')->groupBy('user_name_id', 'enroll_master')->get();
            if (count($query) > 0) {
                foreach ($query as $data) {
                    $getId = SubjectRegistration::where(['user_name_id' => $data->user_name_id, 'enroll_master' => $data->enroll_master])->select('id')->first();
                    if ($getId != '') {
                        $data->id = $getId->id;
                    }
                }
            }
        }
        $table = Datatables::of($query);
        $table->addColumn('placeholder', '&nbsp;');

        $table->editColumn('actions', function ($row) {
            return $row;
        });

        $table->editColumn('register_no', function ($row) {
            return $row->students ? $row->students->register_no : '';
        });
        $table->addColumn('student_name', function ($row) {

            return $row->students ? $row->students->name : '';
        });
        $table->addColumn('enroll_master', function ($row) {
            if ($row->enroll_masters) {
                $array = explode('/', $row->enroll_masters->enroll_master_number);
                return $array[1];
            } else {
                return '';
            }
        });

        $table->rawColumns(['actions', 'placeholder']);

        return $table->make(true);
    }

    public function getDatas(Request $request)
    {
        $role_id = auth()->user()->roles[0]->id;
        $type_id = auth()->user()->roles[0]->type_id;
        $theEnrolls = [];

        if ($request->ay != null && $request->batch != null && $request->course != null && $request->sem != null && $request->regulation != null) {
            $make_enroll = $request->batch . '/' . $request->course . '/' . $request->ay . '/' . $request->sem . '/%';
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
        // $currentClasses = Session::get('currentClasses');
        $getEnroll = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%{$make_enroll}")->select('id')->get();
        if (count($getEnroll) > 0) {
            foreach ($getEnroll as $enroll) {
                array_push($theEnrolls, $enroll->id);
            }
        }

        $query = SubjectRegistration::with('students:user_name_id,name,register_no', 'enroll_masters')->whereIn('enroll_master', $theEnrolls)->where('regulation',$request->regulation)->select('user_name_id', 'enroll_master')->groupBy('user_name_id', 'enroll_master')->get();
        if (count($query) > 0) {
            foreach ($query as $data) {
                $getId = SubjectRegistration::where(['user_name_id' => $data->user_name_id, 'enroll_master' => $data->enroll_master])->select('id')->first();
                if ($getId != '') {
                    $data->id = $getId->id;
                }
            }
        }

        $table = Datatables::of($query);
        $table->addColumn('placeholder', '&nbsp;');

        $table->editColumn('actions', function ($row) {
            return $row->id;
        });
        $table->editColumn('id', function ($row) {
            return $row->id;
        });

        $table->editColumn('register_no', function ($row) {
            return $row->students ? $row->students->register_no : '';
        });
        $table->addColumn('student_name', function ($row) {

            return $row->students ? $row->students->name : '';
        });
        $table->addColumn('enroll_master', function ($row) {
            if ($row->enroll_masters) {
                $array = explode('/', $row->enroll_masters->enroll_master_number);
                return $array[1];
            } else {
                return '';
            }
        });

        $table->rawColumns(['actions', 'placeholder']);

        return $table->make(true);
    }

    public function show($id)
    {

        $row = SubjectRegistration::find($id);
        $regular = [];
        $professional = [];
        $open = [];
        $others = [];
        $honors = [];
        if ($row) {
            // $grouped = SubjectRegistration::select(['register_no','student_name','enroll_master',''])->where('register_no', $row->register_no)->get()
            $allotedSubjects = SubjectRegistration::where(['user_name_id' => $row->user_name_id, 'enroll_master' => $row->enroll_master])->get();
            $user_name_id = $row->user_name_id;
            if ($allotedSubjects) {

                foreach ($allotedSubjects as $subject) {
                    $subject->subjects->subject_type_id = null;
                    $get_sub = Subject::where('id', $subject->subject_id)->first();
                    if ($get_sub) {
                        $get_sub_type = SubjectType::where('id', $get_sub->subject_type_id)->first();
                        if ($get_sub_type) {
                            $subject->subjects->subject_type_id = $get_sub_type->name;
                        }
                    }

                    if ($subject->category == 'Regular Subject') {
                        array_push($regular, $subject);
                    }
                    if ($subject->category == 'Professional Electives') {
                        array_push($professional, $subject);
                    }
                    if ($subject->category == 'Open Electives') {
                        array_push($open, $subject);
                    }
                    if ($subject->category == 'Others') {
                        array_push($others, $subject);
                    }
                    if ($subject->category == 'Honors Degree') {
                        array_push($honors, $subject);
                    }

                }
                // dd($regular);
            }
        }
        $status = isset($row->status) && $row->status != '' ? $row->status : '';

        return view('admin.subjectRegistration.show', compact('regular', 'professional', 'open', 'others','honors', 'user_name_id', 'status'));
    }
    public function update(Request $request)
    {
        // dd($request);
        $role_id = auth()->user()->roles[0]->id;
        $type_id = auth()->user()->roles[0]->type_id;
        $statusNum = null;
        $getClass = Student::where(['user_name_id' => $request->data['user_name_id']])->select('enroll_master_id')->first();
        $enrollMaster = null;
        $theDept = null;
        if ($getClass != '') {
            $enrollMaster = $getClass->enroll_master_id;
            $getdept = ClassRoom::where(['name' => $enrollMaster])->select('department_id')->first();
            if ($getdept != '') {
                $theDept = $getdept->department_id;
            }
            $theId = null;
        }
        if (auth()->user()->roles[0]->id == 14) {
            $statusNum = 2;
            if (isset($request->data['status']) && $request->data['status'] == 'Approved') {
                if ($getClass != '') {
                    $allotedSubjects = SubjectRegistration::where(['user_name_id' => $request->data['user_name_id'], 'enroll_master' => $enrollMaster, 'status' => '1'])->get();
                    if ($allotedSubjects->isNotEmpty()) {
                        foreach ($allotedSubjects as $i => $allotedSubject) {
                            if ($i == 0) {
                                $theId = $allotedSubject->id;
                            }
                            $allotedSubject->status = $statusNum;
                            $allotedSubject->save();
                        }
                        $userAlert = new UserAlert;
                        $userAlert->alert_text = 'Your Subject Registration Approved By HOD';
                        $userAlert->alert_link = url('admin/subject-registration/show/' . $theId);
                        $userAlert->save();
                        $userAlert->users()->sync($request->data['user_name_id']);
                    }
                }
            }
        }
        if ($type_id == 1 || $type_id == 3) {
            $statusNum = 1;
            if (isset($request->data['status']) && $request->data['status'] == 'Approved') {
                $allotedSubjects = SubjectRegistration::where(['user_name_id' => $request->data['user_name_id'], 'enroll_master' => $enrollMaster, 'status' => '0'])->get();

                if ($allotedSubjects->isNotEmpty()) {
                    if ($getClass != '') {
                        $receiverHod = [];
                        if ($theDept != null) {
                            $getDept = ToolsDepartment::where(['id' => $theDept])->select('name')->first();
                            if ($getDept != '') {
                                $getHod = DB::table('role_user')->where(['role_id' => 14])->select('user_id')->get();
                                if (count($getHod) > 0) {
                                    foreach ($getHod as $hod) {
                                        $checkUser = User::where(['id' => $hod->user_id, 'dept' => $getDept->name])->select('id')->get();
                                        if (count($checkUser) > 0) {
                                            foreach ($checkUser as $user) {
                                                array_push($receiverHod, $user->id);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        foreach ($allotedSubjects as $i => $allotedSubject) {
                            if ($i == 0) {
                                $theId = $allotedSubject->id;
                            }
                            $allotedSubject->status = $statusNum;
                            $allotedSubject->save();
                        }
                        $userAlert = new UserAlert;
                        $userAlert->alert_text = 'Your Subject Registration Forwarded To HOD';
                        $userAlert->alert_link = url('admin/subject-registration/show/' . $theId);
                        $userAlert->save();
                        $userAlert->users()->sync($request->data['user_name_id']);
                    }
                }
            }
        }
        if ($role_id == 1) {
            $statusNum = 2;
            if (isset($request->data['status']) && $request->data['status'] == 'Approved') {
                if ($getClass != '') {
                    $allotedSubjects = SubjectRegistration::where(['user_name_id' => $request->data['user_name_id'], 'enroll_master' => $enrollMaster, 'status' => '1'])->get();
                    if ($allotedSubjects->isNotEmpty()) {
                        foreach ($allotedSubjects as $i => $allotedSubject) {
                            if ($i == 0) {
                                $theId = $allotedSubject->id;
                            }
                            $allotedSubject->status = $statusNum;
                            $allotedSubject->save();
                        }
                        $userAlert = new UserAlert;
                        $userAlert->alert_text = 'Your Subject Registration Approved By ADMIN';
                        $userAlert->alert_link = url('admin/subject-registration/show/' . $theId);
                        $userAlert->save();
                        $userAlert->users()->sync($request->data['user_name_id']);
                    }
                }
            }
        }

        // return back();

    }

    public function edit($request)
    {
        // dd($request);

        if ($request) {

            $enrollMaster = Student::where('user_name_id', $request)->first();

            if ($enrollMaster != '') {
                $CourseEnrollMaster = CourseEnrollMaster::find($enrollMaster->enroll_master_id);

                if ($CourseEnrollMaster) {
                    $enrollName = $CourseEnrollMaster->enroll_master_number;
                    $enrollArray = explode('/', $enrollName);

                    if ($enrollArray) {
                        $getCourse = ToolsCourse::where('name', $enrollArray[1])->first();
                        if ($getCourse) {
                            $department = $getCourse->department_id;
                            $courseId = $getCourse->id;
                        } else {
                            $department = '';
                            $courseId = '';
                        }
                        $semester = Semester::where('semester', $enrollArray[3])->first();
                        if ($semester) {
                            $semId = $semester->id;
                        } else {
                            $semId = '';
                        }
                        if ($semId != '') {
                            if ($semId == '1' || $semId == '2') {
                                $department = 5;
                            }
                        }

                        $getAcademicYear = AcademicYear::where('name', $enrollArray[2])->first();
                        if ($getAcademicYear) {
                            $accId = $getAcademicYear->id;
                        } else {
                            $accId = '';
                        }
                    }

                    if ($department != '' && $courseId != '' && $semId != '' && $accId != '') {
                        $allotedSubjects = SubjectAllotment::where([
                            'department' => $department,
                            'semester' => $semId,
                            'course' => $courseId,
                            'academic_year' => $accId,
                        ])->get();

                        if ($allotedSubjects) {
                            $regular = [];
                            $professional = [];
                            $open = [];
                            $others = [];

                            foreach ($allotedSubjects as $subject) {
                                $subject->subjects->subject_type_id = null;
                                $get_sub = Subject::where('id', $subject->subject_id)->first();
                                if ($get_sub) {
                                    $get_sub_type = SubjectType::where('id', $get_sub->subject_type_id)->first();
                                    if ($get_sub_type) {
                                        $subject->subjects->subject_type_id = $get_sub_type->name;
                                    }
                                }

                                if ($subject->category == 'Regular Subject') {
                                    array_push($regular, $subject);
                                }
                                if ($subject->category == 'Professional Electives') {
                                    array_push($professional, $subject);
                                }
                                if ($subject->category == 'Open Electives') {
                                    array_push($open, $subject);
                                }
                                if ($subject->category == 'Others') {
                                    array_push($others, $subject);
                                }
                            }
                        }
                    }
                }
            } else {
                $regular = [];
                $professional = [];
                $open = [];
                $others = [];
            }
            $user_name_id = $request;
        }
        return view('admin.subjectRegistration.edit', compact('regular', 'professional', 'open', 'others', 'user_name_id'));
    }

    public function degreeWise(Request $request)
    {

        $get_dept = ToolsDepartment::get();

        $get_course = ToolsCourse::get();
        $depts = [];
        foreach ($get_dept as $data) {
            $depts[$data->id] = [];
            foreach ($get_course as $course) {
                if ($data->id != 5) {
                    if ($data->id == $course->department_id) {
                        array_push($depts[$data->id], $course);
                    }
                } else {

                    array_push($depts[$data->id], $course);
                }
            }
        }

        $role_id = auth()->user()->roles[0]->id;
        $theDept = auth()->user()->dept;
        if ($role_id == 14 && $theDept != null) {
            $departments = ToolsDepartment::where(['name' => $theDept])->pluck('name', 'id');
            if ($theDept == 'S & H') {
                $semester = Semester::whereIn('semester', [1, 2])->pluck('semester', 'id');
            } else {
                $semester = Semester::whereNotIn('semester', [1, 2])->pluck('semester', 'id');
            }
        } else {
            $departments = ToolsDepartment::pluck('name', 'id');
            $semester = Semester::pluck('semester', 'id');
        }

        $academic_years = AcademicYear::pluck('name', 'id')->prepend('Select Academic Year', '');

        return view('admin.subjectRegistration.degree', compact('departments', 'depts', 'academic_years', 'semester'));
    }

    public function subjectWise(Request $request)
    {

        $get_dept = ToolsDepartment::get();

        $get_course = ToolsCourse::get();
        $depts = [];
        foreach ($get_dept as $data) {
            $depts[$data->id] = [];
            foreach ($get_course as $course) {
                if ($data->id != 5) {
                    if ($data->id == $course->department_id) {
                        array_push($depts[$data->id], $course);
                    }
                } else {

                    array_push($depts[$data->id], $course);
                }
            }
        }

        $role_id = auth()->user()->roles[0]->id;
        $theDept = auth()->user()->dept;
        if ($role_id == 14 && $theDept != null) {
            $departments = ToolsDepartment::where(['name' => $theDept])->pluck('name', 'id');
            if ($theDept == 'S & H') {
                $semester = Semester::whereIn('semester', [1, 2])->pluck('semester', 'id');
            } else {
                $semester = Semester::whereNotIn('semester', [1, 2])->pluck('semester', 'id');
            }
        } else {
            $departments = ToolsDepartment::pluck('name', 'id');
            $semester = Semester::pluck('semester', 'id');
        }

        $academic_years = AcademicYear::pluck('name', 'id')->prepend('Select Academic Year', '');

        return view('admin.subjectRegistration.subject', compact('departments', 'depts', 'academic_years', 'semester'));
    }

    public function degreeWise_search(Request $request)
    {
        //     dd($request);
        if ($request) {
            $department = $request->department;
            $course = $request->course;
            $academic_year = $request->academic_year;
            $semester = $request->semester;
            $section = $request->section;

            $get_dept = ToolsDepartment::where(['id' => $department])->first();
            $got_dept = null;

            if ($get_dept != '') {
                $got_dept = $get_dept->name;
            }

            $get_course = ToolsCourse::where(['id' => $course])->first();
            $got_course = null;

            if ($get_course != '') {
                $got_course = $get_course->name;
            }

            $get_ay = AcademicYear::where(['id' => $academic_year])->first();
            $got_ay = null;

            if ($get_ay != '') {
                $got_ay = $get_ay->name;
            }

            $get_sem = Semester::where(['id' => $semester])->first();
            $got_sem = null;

            if ($get_sem != '') {
                $got_sem = $get_sem->semester;
            }

            $get_section = Section::where(['id' => $section])->first();
            $got_section = null;

            if ($get_section != '') {
                $got_section = $get_section->section;
            }

            $registered = 0;
            $enroll = null;
            $students = 0;

            if ($academic_year != '' && $semester != '' && $section != '') {
                $check_enroll = $got_course . '/' . $got_ay . '/' . $got_sem . '/' . $got_section;
                $get_enroll = CourseEnrollMaster::where('enroll_master_number', 'like', "%$check_enroll")->get();
                // dd($get_enroll);

                if (count($get_enroll) > 0) {
                    $data = [];
                    foreach ($get_enroll as $enrolls) {
                        // dd($enrolls);
                        $get_students = Student::where(['enroll_master_id' => $enrolls->id])->get();

                        if (count($get_students) > 0) {
                            $students = count($get_students);
                        }

                        $get_registrations = SubjectRegistration::where(['enroll_master' => $enrolls->id])->groupBy('register_no')->select('register_no')->get();
                        $registered = count($get_registrations);
                        $enroll = $enrolls->id;

                        $explode = explode('/', $enrolls->enroll_master_number);
                        // dd($explode);
                        $got_batch = $explode[0];

                        $details = ['students' => $students, 'registered' => $registered, 'enroll' => $enroll, 'batch' => $got_batch, 'dept' => $got_dept, 'course' => $got_course, 'ay' => $got_ay, 'semester' => $got_sem, 'section' => $got_section];
                        array_push($data, $details);
                    }
                    // dd($data);
                    return response()->json(['data' => $data]);
                } else {
                    return response()->json(['data' => false]);
                }
            } else if ($academic_year != '' && $semester != '' && $section == '') {
                $check_enroll = $got_course . '/' . $got_ay . '/' . $got_sem;
                $get_enroll = CourseEnrollMaster::where('enroll_master_number', 'like', "%$check_enroll%")->get();
                // dd($get_enroll);
                if (count($get_enroll) > 0) {
                    $data = [];
                    foreach ($get_enroll as $enrolls) {
                        // dd($enrolls);
                        $get_students = Student::where(['enroll_master_id' => $enrolls->id])->get();

                        if (count($get_students) > 0) {
                            $students = count($get_students);
                        }

                        $get_registrations = SubjectRegistration::where(['enroll_master' => $enrolls->id])->groupBy('register_no')->select('register_no')->get();
                        $registered = count($get_registrations);
                        $enroll = $enrolls->id;

                        $explode = explode('/', $enrolls->enroll_master_number);
                        // dd($explode);
                        $got_batch = $explode[0];
                        $got_section = $explode[4];
                        $details = ['students' => $students, 'registered' => $registered, 'enroll' => $enroll, 'batch' => $got_batch, 'dept' => $got_dept, 'course' => $got_course, 'ay' => $got_ay, 'semester' => $got_sem, 'section' => $got_section];
                        array_push($data, $details);
                    }
                    // dd($data);
                    return response()->json(['data' => $data]);
                } else {
                    return response()->json(['data' => false]);
                }
            } else if ($academic_year != '' && $semester == '' && $section == '') {
                $check_enroll = $got_course . '/' . $got_ay;
                $get_enroll = CourseEnrollMaster::where('enroll_master_number', 'like', "%$check_enroll%")->get();
                // dd($get_enroll);
                if (count($get_enroll) > 0) {
                    $data = [];
                    foreach ($get_enroll as $enrolls) {
                        // dd($enrolls);
                        $get_students = Student::where(['enroll_master_id' => $enrolls->id])->get();

                        if (count($get_students) > 0) {
                            $students = count($get_students);
                        }

                        $get_registrations = SubjectRegistration::where(['enroll_master' => $enrolls->id])->groupBy('register_no')->select('register_no')->get();
                        $registered = count($get_registrations);
                        $enroll = $enrolls->id;

                        $explode = explode('/', $enrolls->enroll_master_number);
                        // dd($explode);
                        $got_batch = $explode[0];
                        $got_sem = $explode[3];
                        $got_section = $explode[4];
                        $details = ['students' => $students, 'registered' => $registered, 'enroll' => $enroll, 'batch' => $got_batch, 'dept' => $got_dept, 'course' => $got_course, 'ay' => $got_ay, 'semester' => $got_sem, 'section' => $got_section];
                        array_push($data, $details);
                    }
                    // dd($data);
                    return response()->json(['data' => $data]);
                } else {
                    return response()->json(['data' => false]);
                }
            } else if ($academic_year != '' && $semester == '' && $section != '') {

                $check_enroll = $got_course . '/' . $got_ay;
                $check_enroll_2 = $got_section;

                $get_enroll = CourseEnrollMaster::where('enroll_master_number', 'like', "%$check_enroll%$check_enroll_2")->get();
                // dd($get_enroll);
                if (count($get_enroll) > 0) {
                    $data = [];
                    foreach ($get_enroll as $enrolls) {
                        // dd($enrolls);
                        $get_students = Student::where(['enroll_master_id' => $enrolls->id])->get();

                        if (count($get_students) > 0) {
                            $students = count($get_students);
                        }

                        $get_registrations = SubjectRegistration::where(['enroll_master' => $enrolls->id])->groupBy('register_no')->select('register_no')->get();
                        $registered = count($get_registrations);
                        $enroll = $enrolls->id;

                        $explode = explode('/', $enrolls->enroll_master_number);
                        // dd($explode);
                        $got_batch = $explode[0];
                        $got_sem = $explode[3];
                        $got_course = $explode[1];
                        $details = ['students' => $students, 'registered' => $registered, 'enroll' => $enroll, 'batch' => $got_batch, 'dept' => $got_dept, 'course' => $got_course, 'ay' => $got_ay, 'semester' => $got_sem, 'section' => $got_section];
                        array_push($data, $details);
                    }
                    // dd($data);
                    return response()->json(['data' => $data]);
                } else {
                    return response()->json(['data' => false]);
                }
            } else if ($academic_year == '' && $semester != '' && $section != '') {

                $check_enroll = $got_course;
                $check_enroll_2 = $got_sem . '/' . $got_section;

                $get_enroll = CourseEnrollMaster::where('enroll_master_number', 'like', "%$check_enroll" . "/" . "%$check_enroll_2")->get();
                // dd($get_enroll);
                if (count($get_enroll) > 0) {
                    $data = [];
                    foreach ($get_enroll as $enrolls) {
                        // dd($enrolls);
                        $get_students = Student::where(['enroll_master_id' => $enrolls->id])->get();

                        if (count($get_students) > 0) {
                            $students = count($get_students);
                        }

                        $get_registrations = SubjectRegistration::where(['enroll_master' => $enrolls->id])->groupBy('register_no')->select('register_no')->get();
                        $registered = count($get_registrations);
                        $enroll = $enrolls->id;

                        $explode = explode('/', $enrolls->enroll_master_number);
                        // dd($explode);
                        $got_batch = $explode[0];
                        $got_ay = $explode[2];
                        $got_course = $explode[1];
                        $details = ['students' => $students, 'registered' => $registered, 'enroll' => $enroll, 'batch' => $got_batch, 'dept' => $got_dept, 'course' => $got_course, 'ay' => $got_ay, 'semester' => $got_sem, 'section' => $got_section];
                        array_push($data, $details);
                    }
                    // dd($data);
                    return response()->json(['data' => $data]);
                } else {
                    return response()->json(['data' => false]);
                }
            } else if ($academic_year == '' && $semester == '' && $section != '') {

                $check_enroll = $got_course;
                $check_enroll_2 = $got_section;

                $get_enroll = CourseEnrollMaster::where('enroll_master_number', 'like', "%$check_enroll" . "/" . "%$check_enroll_2")->get();
                // dd($get_enroll);
                if (count($get_enroll) > 0) {
                    $data = [];
                    foreach ($get_enroll as $enrolls) {
                        // dd($enrolls);
                        $get_students = Student::where(['enroll_master_id' => $enrolls->id])->get();

                        if (count($get_students) > 0) {
                            $students = count($get_students);
                        }

                        $get_registrations = SubjectRegistration::where(['enroll_master' => $enrolls->id])->groupBy('register_no')->select('register_no')->get();
                        $registered = count($get_registrations);
                        $enroll = $enrolls->id;

                        $explode = explode('/', $enrolls->enroll_master_number);
                        // dd($explode);
                        $got_batch = $explode[0];
                        $got_ay = $explode[2];
                        $got_course = $explode[1];
                        $got_sem = $explode[3];
                        $details = ['students' => $students, 'registered' => $registered, 'enroll' => $enroll, 'batch' => $got_batch, 'dept' => $got_dept, 'course' => $got_course, 'ay' => $got_ay, 'semester' => $got_sem, 'section' => $got_section];
                        array_push($data, $details);
                    }
                    // dd($data);
                    return response()->json(['data' => $data]);
                } else {
                    return response()->json(['data' => false]);
                }
            } else if ($academic_year == '' && $semester == '' && $section == '') {

                $check_enroll = $got_course;

                $get_enroll = CourseEnrollMaster::where('enroll_master_number', 'like', "%$check_enroll" . "/" . "%")->get();
                // dd($get_enroll);
                if (count($get_enroll) > 0) {
                    $data = [];
                    foreach ($get_enroll as $enrolls) {
                        // dd($enrolls);
                        $get_students = Student::where(['enroll_master_id' => $enrolls->id])->get();

                        if (count($get_students) > 0) {
                            $students = count($get_students);
                        }

                        $get_registrations = SubjectRegistration::where(['enroll_master' => $enrolls->id])->groupBy('register_no')->select('register_no')->get();
                        $registered = count($get_registrations);
                        $enroll = $enrolls->id;

                        $explode = explode('/', $enrolls->enroll_master_number);
                        // dd($explode);
                        $got_batch = $explode[0];
                        $got_ay = $explode[2];
                        $got_course = $explode[1];
                        $got_sem = $explode[3];
                        $got_section = $explode[4];
                        $details = ['students' => $students, 'registered' => $registered, 'enroll' => $enroll, 'batch' => $got_batch, 'dept' => $got_dept, 'course' => $got_course, 'ay' => $got_ay, 'semester' => $got_sem, 'section' => $got_section];
                        array_push($data, $details);
                    }
                    // dd($data);
                    return response()->json(['data' => $data]);
                } else {
                    return response()->json(['data' => false]);
                }
            }
        }
    }

    public function subjectWise_search(Request $request)
    {
        // dd($request);
        if ($request) {

            $department = $request->department;
            $course = $request->course;
            $academic_year = $request->academic_year;
            $semester = $request->semester;
            $section = $request->section;
            $students = [];
            $get_dept = ToolsDepartment::where(['id' => $department])->first();
            $got_dept = null;

            if ($get_dept != '') {
                $got_dept = $get_dept->name;
            }

            $get_course = ToolsCourse::where(['id' => $course])->first();
            $got_course = null;

            if ($get_course != '') {
                $got_course = $get_course->name;
            }

            $get_ay = AcademicYear::where(['id' => $academic_year])->first();
            $got_ay = null;

            if ($get_ay != '') {
                $got_ay = $get_ay->name;
            }

            $get_sem = Semester::where(['id' => $semester])->first();
            $got_sem = null;

            if ($get_sem != '') {
                $got_sem = $get_sem->semester;
            }

            $get_section = Section::where(['id' => $section])->first();
            $got_section = null;

            if ($get_section != '') {
                $got_section = $get_section->section;
            }

            $check_enroll = $got_course . '/' . $got_ay . '/' . $got_sem . '/' . $got_section;

            $get_enroll = CourseEnrollMaster::where('enroll_master_number', 'like', "%$check_enroll")->get();

            if (count($get_enroll) > 0) {
                $get_students = Student::where(['enroll_master_id' => $get_enroll[0]->id])->get();
                $get_batch = explode('/', $get_enroll[0]->enroll_master_number);
                $got_batch = $get_batch[0];
            } else {
                $get_students = [];
                return response()->json(['data' => $students]);
            }

            $get_subjects = SubjectAllotment::with('subjects')->where(['department' => $department, 'course' => $course, 'academic_year' => $academic_year, 'semester' => $semester])->get();
            // dd($get_subjects);

            if (count($get_subjects) > 0) {
                foreach ($get_subjects as $subject) {
                    // dd($subject);
                    $students_count = SubjectRegistration::where(['enroll_master' => $get_enroll[0]->id, 'subject_id' => $subject->subject_id])->get();

                    array_push($students, ['batch' => $got_batch, 'ay' => $got_ay, 'semester' => $got_sem, 'section' => $got_section, 'subject_category' => $subject->category, 'subject_code' => $subject->subjects->subject_code, 'subject_title' => $subject->subjects->name, 'registered' => count($students_count), 'students' => count($get_students), 'enroll' => $get_enroll[0]->id, 'subject' => $subject->subject_id]);
                }
            }

            // $details = ['dept' => $got_dept, 'course' => $got_course, 'ay' => $got_ay, 'semester' => $got_sem, 'section' => $got_section];
            return response()->json(['data' => $students]);
        }
    }

    public function getSections(Request $request)
    {

        // dd($request);
        $sections = Section::where(['course_id' => $request->course])->get();
        // dd($sections);
        return response()->json(['sections' => $sections]);
    }

    public function degreeWise_show(Request $request)
    {
        // dd($request->enroll);
        $get_students = [];
        $batch = ' ';
        $course = ' ';
        $ay = ' ';
        $semester = ' ';
        $section = ' ';

        if ($request->enroll != '') {

            $get_enroll = CourseEnrollMaster::where(['id' => $request->enroll])->first();

            if ($get_enroll != '') {
                $explode = explode('/', $get_enroll->enroll_master_number);

                $batch = $explode[0];
                $course = $explode[1];
                $ay = $explode[2];
                $semester = $explode[3];
                $section = $explode[4];
            }

            $get_students = SubjectRegistration::where(['enroll_master' => $request->enroll])->groupBy('student_name', 'register_no', 'enroll_master')->select('student_name', 'register_no', 'enroll_master')->get();
        }

        return view('admin.subjectRegistration.degreeView', compact('get_students', 'batch', 'course', 'ay', 'semester', 'section'));
    }

    public function subjectWise_show(Request $request)
    {
        // dd($request->enroll,$request->subject);
        $get_students = [];
        $batch = ' ';
        $course = ' ';
        $ay = ' ';
        $semester = ' ';
        $section = ' ';

        if ($request->enroll != '' && $request->subject != '') {
            $get_enroll = CourseEnrollMaster::where(['id' => $request->enroll])->first();

            if ($get_enroll != '') {
                $explode = explode('/', $get_enroll->enroll_master_number);

                $batch = $explode[0];
                $course = $explode[1];
                $ay = $explode[2];
                $semester = $explode[3];
                $section = $explode[4];
            }

            $get_students = SubjectRegistration::where(['enroll_master' => $request->enroll, 'subject_id' => $request->subject])->groupBy('student_name', 'register_no', 'enroll_master')->select('student_name', 'register_no', 'enroll_master')->get();
        }
        // dd($get_students);
        return view('admin.subjectRegistration.subjectView', compact('get_students', 'batch', 'course', 'ay', 'semester', 'section'));
    }

    public function get_course_and_sem(Request $request)
    {
        if ($request->dept != '') {
            if ($request->dept == 5) {
                $get_course = ToolsCourse::get();
                $get_sem = [1, 2];
            } else if ($request->dept != 9 && $request->dept != 10) {
                $get_course = ToolsCourse::where(['department_id' => $request->dept])->get();
                $get_sem = [3, 4, 5, 6, 7, 8];
            }
            return response()->json(['course' => $get_course, 'semester' => $get_sem]);
        }
    }
}
