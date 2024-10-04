<?php

namespace App\Http\Controllers\Admin;

ini_set('memory_limit', '256M');
ini_set('max_execution_time', 600);
use PDF;
use DateTime;
use App\Models\Batch;
use App\Models\Student;
use App\Models\ToolsCourse;
use App\Models\AcademicYear;
use App\Models\ResultMaster;
use Illuminate\Http\Request;
use App\Models\ExamRegistration;
use App\Models\ExamResultPublish;
use App\Models\ToolssyllabusYear;
use App\Models\CourseEnrollMaster;
use App\Http\Controllers\Controller;
use App\Models\StudentPromotionHistory;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Traits\CsvImportTrait;

class ExamRegistrationController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        $regulations = ToolssyllabusYear::pluck('name', 'id');
        $courses = ToolsCourse::pluck('short_form', 'id');
        $ays = AcademicYear::pluck('name', 'id');
        if ($request->ajax()) {
            $query = ExamRegistration::with(['ay', 'batches', 'courses', 'regulations'])->groupBy('academic_year', 'batch', 'course', 'semester', 'regulation', 'exam_type', 'exam_month', 'exam_year')->select('academic_year', 'batch', 'course', 'semester', 'regulation', 'exam_type', 'exam_month', 'exam_year')->get();

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');

            $table->editColumn('academic_year', function ($row) {
                return $row->ay ? $row->ay->name : '';
            });
            $table->editColumn('actions', function ($row) {
                $ay = $row->ay ? $row->ay->id : '';
                $batch = $row->batches ? $row->batches->id : '';
                $course = $row->courses ? $row->courses->id : '';
                $regulation = $row->regulations ? $row->regulations->id : '';
                $sem = $row->semester ? $row->semester : '';
                $exam_type = $row->exam_type ? $row->exam_type : '';
                return $ay . '/' . $batch . '/' . $course . '/' . $regulation . '/' . $sem . '/' . $exam_type;
            });
            $table->editColumn('batch', function ($row) {
                return $row->batches ? $row->batches->name : '';
            });
            $table->editColumn('course', function ($row) {
                return $row->courses ? $row->courses->short_form : '';
            });
            $table->editColumn('regulation', function ($row) {
                return $row->regulations ? $row->regulations->name : '';
            });
            $table->editColumn('semester', function ($row) {
                return $row->semester ? $row->semester : '';
            });
            $table->editColumn('exam_type', function ($row) {
                return $row->exam_type ? $row->exam_type : '';
            });
            // $table->editColumn('uploaded_date', function ($row) {
            //     return $row->uploaded_date ? $row->uploaded_date : '';
            // });
            $table->editColumn('exam_date', function ($row) {
                $month = $row->exam_month ? $row->exam_month : '';
                $year = $row->exam_year ? $row->exam_year : '';
                $exam_date = $month . ' ' . $year;
                return $exam_date;
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        return view('admin.examRegistration.index', compact('regulations', 'courses', 'ays'));
    }

    public function download(Request $request)
    {
        $get = ExamRegistration::with('ay:id,name', 'batches:id,name', 'courses:id,short_form', 'regulations:id,name', 'student:name,register_no,user_name_id', 'subject:id,subject_code,name,credits,subject_type_id', 'subject.subject_type:id,name')->where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'regulation' => $request->regulation, 'semester' => $request->sem, 'exam_type' => $request->exam_type])->get();

        return view('admin.examRegistration.excel', compact('get'));
    }

    public function search(Request $request)
    {
        if (isset($request->regulation) && isset($request->ay) && isset($request->semester) && isset($request->course) && isset($request->exam_type)) {

            $query = ExamRegistration::with(['ay', 'batches', 'courses', 'regulations'])->where(['regulation' => $request->regulation, 'academic_year' => $request->ay, 'course' => $request->course, 'semester' => $request->semester, 'exam_type' => $request->exam_type])->groupBy('academic_year', 'batch', 'course', 'semester', 'regulation', 'exam_type', 'uploaded_date', 'exam_month', 'exam_year')->select('academic_year', 'batch', 'course', 'semester', 'regulation', 'exam_type', 'uploaded_date', 'exam_month', 'exam_year')->get();

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');

            $table->editColumn('academic_year', function ($row) {
                return $row->ay ? $row->ay->name : '';
            });

            $table->editColumn('actions', function ($row) {
                $ay = $row->ay ? $row->ay->id : '';
                $batch = $row->batches ? $row->batches->id : '';
                $course = $row->courses ? $row->courses->id : '';
                $regulation = $row->regulations ? $row->regulations->id : '';
                $sem = $row->semester ? $row->semester : '';
                $exam_type = $row->exam_type ? $row->exam_type : '';
                return $ay . '/' . $batch . '/' . $course . '/' . $regulation . '/' . $sem . '/' . $exam_type;
            });
            $table->editColumn('batch', function ($row) {
                return $row->batches ? $row->batches->name : '';
            });
            $table->editColumn('course', function ($row) {
                return $row->courses ? $row->courses->short_form : '';
            });
            $table->editColumn('regulation', function ($row) {
                return $row->regulations ? $row->regulations->name : '';
            });
            $table->editColumn('semester', function ($row) {
                return $row->semester ? $row->semester : '';
            });
            $table->editColumn('exam_type', function ($row) {
                return $row->exam_type ? $row->exam_type : '';
            });
            $table->editColumn('uploaded_date', function ($row) {
                return $row->uploaded_date ? $row->uploaded_date : '';
            });
            $table->editColumn('exam_date', function ($row) {
                $month = $row->exam_month ? $row->exam_month : '';
                $year = $row->exam_year ? $row->exam_year : '';
                $exam_date = $month . ' ' . $year;
                return $exam_date;
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
    }

    public function preview(Request $request)
    {
        $batches = Batch::pluck('name', 'id');
        $courses = ToolsCourse::pluck('short_form', 'id');
        $ays = AcademicYear::pluck('name', 'id');
        // $registerNos = ExamRegistration::with('student:name,register_no,user_name_id')->groupBy('user_name_id')->select('user_name_id')->get();

        // return view('admin.examRegistration.preview');
        return view('admin.examRegistration.preview', compact('batches', 'courses', 'ays'));
    }

    public function getStudents(Request $request)
    {
        if (isset($request->ay) && isset($request->batch) && isset($request->semester) && isset($request->course)) {
            $batches = Batch::where(['id' => $request->batch])->select('name')->first();
            $courses = ToolsCourse::where(['id' => $request->course])->select('name')->first();
            $ays = AcademicYear::where(['id' => $request->ay])->select('name')->first();

            if ($batches != '') {
                $batch = $batches->name;
            } else {
                return response()->json(['status' => false, 'data' => 'Batch Not Found']);
            }

            if ($courses != '') {
                $course = $courses->name;
            } else {
                return response()->json(['status' => false, 'data' => 'Course Not Found']);
            }

            if ($ays != '') {
                $ay = $ays->name;
            } else {
                return response()->json(['status' => false, 'data' => 'AY Not Found']);
            }

            $make_enroll = $batch . '/' . $course . '/' . $ay . '/' . $request->semester . '/';

            $get_enrolls = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%$make_enroll%")->select('id', 'enroll_master_number')->get();
            $students = [];
            $enrolls = '';
            if (count($get_enrolls) <= 0) {
                return response()->json(['status' => false, 'data' => 'Classes Not Found']);
            } else {
                foreach ($get_enrolls as $enroll) {
                    $getstudents = Student::where(['enroll_master_id' => $enroll->id])->select('user_name_id', 'name', 'register_no')->get();
                    if (count($getstudents) <= 0) {
                        $getstudents = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $enroll->id)->select('students.name', 'students.register_no', 'students.user_name_id')->get();
                    }
                    array_push($students, $getstudents);
                    $enrolls .= '|' . $enroll->id;
                }
            }

            return response()->json(['status' => true, 'data' => $students, 'enrolls' => $enrolls]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Datas Not Found']);
        }
    }

    public function getPreview(Request $request)
    {
        if (isset($request->ay) && isset($request->batch) && isset($request->semester) && isset($request->course) && isset($request->user_name_id)) {
            $stu_status = '';
            $enrolls = [];
            if (str_contains($request->user_name_id, '|')) {
                // $theWord = substr($request->user_name_id,0,(strlen($request->user_name_id) - 1));
                $stu_status = 'All';
                $explode = explode('|', $request->user_name_id);
                if (count($explode) > 1) {
                    foreach ($explode as $enroll) {
                        array_push($enrolls, $enroll);
                    }
                }
                $students = Student::whereIn('enroll_master_id', $enrolls)->select('user_name_id', 'name', 'register_no')->get();
                if (count($students) <= 0) {
                    $students = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->whereIn('student_promotion_history.enroll_master_id', $enrolls)->select('students.name', 'students.register_no', 'students.user_name_id')->get();
                }
            } else if ($request->user_name_id != '') {
                $students = Student::where(['user_name_id' => $request->user_name_id])->select('user_name_id', 'name', 'register_no')->get();
                $stu_status = $request->user_name_id;
            } else {
                $students = [];
            }

            if (count($students) > 0) {
                $data = [];
                foreach ($students as $student) {
                    $query = ExamRegistration::with('ay:id,name', 'batches:id,name', 'courses:id,short_form,name', 'regulations:id,name', 'student:name,register_no,user_name_id', 'subject:id,subject_code,name,credits', 'personal_details:user_name_id,dob,email,mobile_number')->where(['academic_year' => $request->ay, 'course' => $request->course, 'semester' => $request->semester, 'user_name_id' => $student->user_name_id])->get();
                    if (count($query) > 0) {
                        array_push($data, $query);
                    }
                }

                return response()->json(['status' => true, 'data' => $data, 'stu_status' => $stu_status, 'enrolls' => $enrolls]);
            } else {
                return response()->json(['status' => false, 'data' => 'Register No Not Found']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Required Datas Not Found']);
        }
    }

    public function previewPdf(Request $request)
    {
        if (isset($request->batch) && isset($request->ay) && isset($request->course) && isset($request->semester) && isset($request->user_name_id) && isset($request->enroll)) {
            $students = [];
            if ($request->user_name_id == 'All') {
                $batches = Batch::where(['id' => $request->batch])->select('name')->first();
                $courses = ToolsCourse::where(['id' => $request->course])->select('name')->first();
                $ays = AcademicYear::where(['id' => $request->ay])->select('name')->first();
                if ($batches == '') {
                    return response()->json(['status' => false, 'data' => 'Batch Not Found']);
                }

                if ($courses == '') {
                    return response()->json(['status' => false, 'data' => 'Course Not Found']);
                }

                if ($ays == '') {
                    return response()->json(['status' => false, 'data' => 'AY Not Found']);
                }

                $theStudents = Student::where('enroll_master_id', $request->enroll)->select('user_name_id')->get();
                if (count($theStudents) <= 0) {
                    $theStudents = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $request->enroll)->select('students.user_name_id')->get();
                }

                foreach ($theStudents as $student) {
                    $getStudent = ExamRegistration::with('ay:id,name', 'batches:id,name', 'courses:id,short_form,name', 'regulations:id,name', 'student:name,register_no,user_name_id', 'subject:id,subject_code,name,credits', 'personal_details:user_name_id,dob,email,mobile_number')->where(['batch' => $request->batch, 'academic_year' => $request->ay, 'course' => $request->course, 'semester' => $request->semester, 'user_name_id' => $student->user_name_id])->select('academic_year', 'course', 'regulation', 'batch', 'user_name_id', 'subject_sem', 'subject_id', 'credits', 'exam_fee', 'exam_type', 'exam_month', 'exam_year')->get();

                    if (count($getStudent) > 0) {
                        $regularSubjectRows = '';
                        $arrearSubjectRows = '';
                        $regularCredits = 0;
                        $arrearCredits = 0;
                        $regularCount = 0;
                        $arrearCount = 0;
                        $exam_fee = 0;
                        $regularCount = 0;
                        $totalPaper = 0;
                        $exam_date = '';
                        $student_name = '';
                        $course = '';
                        $ay = '';
                        $register_no = '';
                        $regulation = '';
                        $mobile = '';
                        $email = '';

                        foreach ($getStudent as $i => $subjectDetails) {
                            if ($subjectDetails->exam_type == 'Regular') {
                                $regularSubjectRows .= "
                                            <tr>
                                                <td class='text-center' style='border-right:1px solid black;height:20px;'>{$subjectDetails->subject_sem}</td>
                                                <td class='text-center' style='border-right:1px solid black'>{$subjectDetails->subject->subject_code}</td>
                                                <td style='padding-left:5px;'>{$subjectDetails->subject->name}</td>
                                            </tr>";
                                $regularCredits += $subjectDetails->credits;
                                $exam_fee += (int) $subjectDetails->exam_fee;
                                $regularCount++;
                            } else {
                                $arrearSubjectRows .= "
                                            <tr>
                                                <td class='text-center' style='border-right:1px solid black;height:20px;'>{$subjectDetails->subject_sem}</td>
                                                <td class='text-center' style='border-right:1px solid black'>{$subjectDetails->subject->subject_code}</td>
                                                <td style='padding-left:5px;'>{$subjectDetails->subject->name}</td>
                                            </tr>";

                                $arrearCredits += $subjectDetails->credits;
                                $exam_fee += (int) $subjectDetails->exam_fee;

                                $arrearCount++;
                            }

                            if ($i == 0) {
                                if ($subjectDetails->personal_details->dob != null) {
                                    $date = new DateTime($subjectDetails->personal_details->dob);
                                    $formattedDate = $date->format('d-m-Y');
                                } else {
                                    $formattedDate = '';
                                }
                                $exam_date = $subjectDetails->exam_month . ' ' . $subjectDetails->exam_year;
                                $student_name = $subjectDetails->student->name;
                                $course = $subjectDetails->courses->name;
                                $ay = $subjectDetails->ay->name;
                                $register_no = $subjectDetails->student->register_no;
                                $regulation = $subjectDetails->regulations->name;
                                $mobile = $subjectDetails->personal_details->mobile_number;
                                $email = $subjectDetails->personal_details->email;
                            }

                            $totalPaper++;
                        }

                        if ($arrearCount > $regularCount) {
                            $emptyCount = $arrearCount - $regularCount;
                            for ($i = 0; $i < $emptyCount; $i++) {
                                $regularSubjectRows .= '
                                        <tr style="padding:5px">
                                            <td class="text-center" style="border-right:1px solid black;height:20px;">&nbsp;</td>
                                            <td class="text-center" style="border-right:1px solid black">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </td>
                                            <td class="text-center" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>
                                        </tr>';
                            }
                        } else {
                            $emptyCount = $regularCount - $arrearCount;
                            for ($i = 0; $i < $emptyCount; $i++) {
                                $arrearSubjectRows .= '
                                        <tr style="padding:5px">
                                            <td class="text-center" style="border-right:1px solid black;height:20px;">&nbsp;</td>
                                            <td class="text-center" style="border-right:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </td>
                                            <td class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>
                                        </tr>';
                            }
                        }

                        $tempArray = ['formattedDate' => $formattedDate, 'email' => $email, 'mobile' => $mobile, 'regulation' => $regulation, 'register_no' => $register_no, 'ay' => $ay, 'course' => $course, 'student_name' => $student_name, 'exam_date' => $exam_date, 'totalPaper' => $totalPaper, 'regularCount' => $regularCount, 'exam_fee' => $exam_fee, 'arrearCount' => $arrearCount, 'regularCount' => $regularCount, 'arrearCredits' => $arrearCredits, 'regularSubjectRows' => $regularSubjectRows, 'arrearSubjectRows' => $arrearSubjectRows, 'regularCredits' => $regularCredits];

                        array_push($students, $tempArray);
                    }

                }

            } else {
                $getStudent = ExamRegistration::with('ay:id,name', 'batches:id,name', 'courses:id,short_form,name', 'regulations:id,name', 'student:name,register_no,user_name_id', 'subject:id,subject_code,name,credits', 'personal_details:user_name_id,dob,email,mobile_number')->where(['batch' => $request->batch, 'academic_year' => $request->ay, 'course' => $request->course, 'semester' => $request->semester, 'user_name_id' => $request->user_name_id])->select('academic_year', 'course', 'regulation', 'batch', 'user_name_id', 'subject_sem', 'subject_id', 'credits', 'exam_fee', 'exam_type', 'exam_month', 'exam_year')->get();
                if (count($getStudent) > 0) {
                    $regularSubjectRows = '';
                    $arrearSubjectRows = '';
                    $regularCredits = 0;
                    $arrearCredits = 0;
                    $regularCount = 0;
                    $arrearCount = 0;
                    $exam_fee = 0;
                    $regularCount = 0;
                    $totalPaper = 0;
                    $exam_date = '';
                    $student_name = '';
                    $course = '';
                    $ay = '';
                    $register_no = '';
                    $regulation = '';
                    $mobile = '';
                    $email = '';
                    $formattedDate = '';

                    foreach ($getStudent as $i => $subjectDetails) {
                        if ($subjectDetails->exam_type == 'Regular') {
                            $regularSubjectRows .= "
                                    <tr>
                                        <td class='text-center' style='border-right:1px solid black;height:20px;'>{$subjectDetails->subject_sem}</td>
                                        <td class='text-center' style='border-right:1px solid black'>{$subjectDetails->subject->subject_code}</td>
                                        <td style='padding-left:5px;'>{$subjectDetails->subject->name}</td>
                                    </tr>";
                            $regularCredits += $subjectDetails->credits;
                            $exam_fee += (int) $subjectDetails->exam_fee;
                            $regularCount++;
                        } else {
                            $arrearSubjectRows .= "
                                    <tr>
                                        <td class='text-center' style='border-right:1px solid black;height:20px;'>{$subjectDetails->subject_sem}</td>
                                        <td class='text-center' style='border-right:1px solid black'>{$subjectDetails->subject->subject_code}</td>
                                        <td style='padding-left:5px;'>{$subjectDetails->subject->name}</td>
                                    </tr>";

                            $arrearCredits += $subjectDetails->credits;
                            $exam_fee += (int) $subjectDetails->exam_fee;

                            $arrearCount++;
                        }

                        if ($i == 0) {
                            if ($subjectDetails->personal_details->dob != null) {
                                $date = new DateTime($subjectDetails->personal_details->dob);
                                $formattedDate = $date->format('d-m-Y');
                            }
                            $exam_date = $subjectDetails->exam_month . ' ' . $subjectDetails->exam_year;
                            $student_name = $subjectDetails->student->name;
                            $course = $subjectDetails->courses->name;
                            $ay = $subjectDetails->ay->name;
                            $register_no = $subjectDetails->student->register_no;
                            $regulation = $subjectDetails->regulations->name;
                            $mobile = $subjectDetails->personal_details->mobile_number;
                            $email = $subjectDetails->personal_details->email;
                        }

                        $totalPaper++;
                    }

                    if ($arrearCount > $regularCount) {
                        $emptyCount = $arrearCount - $regularCount;
                        for ($i = 0; $i < $emptyCount; $i++) {
                            $regularSubjectRows .= '
                                <tr style="padding:5px">
                                    <td class="text-center" style="border-right:1px solid black;height:20px;">&nbsp;</td>
                                    <td class="text-center" style="border-right:1px solid black">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </td>
                                    <td class="text-center" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                                        </td>
                                </tr>';
                        }
                    } else {
                        $emptyCount = $regularCount - $arrearCount;
                        for ($i = 0; $i < $emptyCount; $i++) {
                            $arrearSubjectRows .= '
                                <tr style="padding:5px">
                                    <td class="text-center" style="border-right:1px solid black;height:20px;">&nbsp;</td>
                                    <td class="text-center" style="border-right:1px solid black">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </td>
                                    <td class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </td>
                                </tr>';
                        }
                    }

                    $tempArray = ['formattedDate' => $formattedDate, 'email' => $email, 'mobile' => $mobile, 'regulation' => $regulation, 'register_no' => $register_no, 'ay' => $ay, 'course' => $course, 'student_name' => $student_name, 'exam_date' => $exam_date, 'totalPaper' => $totalPaper, 'regularCount' => $regularCount, 'exam_fee' => $exam_fee, 'arrearCount' => $arrearCount, 'regularCount' => $regularCount, 'arrearCredits' => $arrearCredits, 'regularSubjectRows' => $regularSubjectRows, 'arrearSubjectRows' => $arrearSubjectRows, 'regularCredits' => $regularCredits];

                    array_push($students, $tempArray);
                }
            }
            $pdf = PDF::loadView('admin.examRegistration.download', ['datas' => $students]);
            // $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('ExamRegistration.pdf');
        }
    }

    public function subjectwise(Request $request)
    {
        $batches = Batch::pluck('name', 'id');
        $courses = ToolsCourse::pluck('short_form', 'id');
        return view('admin.examRegistration.subjectwise', compact('batches', 'courses'));
    }

    public function attendanceSheet(Request $request)
    {
        $courses = ToolsCourse::pluck('short_form', 'id');
        $batches = Batch::pluck('name', 'id');
        $ays = AcademicYear::pluck('name', 'id');
        return view('admin.examRegistration.attendanceSheet', compact('courses', 'batches', 'ays'));
    }

    public function getSubjects(Request $request)
    {
        if (isset($request->course) && isset($request->semester) && isset($request->exam_type) && isset($request->batch) && isset($request->ay) && isset($request->subject_sem)) {
            if ($request->subject_sem == 'All') {
                $getSubjects = ExamRegistration::with('subject:id,subject_code,name')->where(['batch' => $request->batch, 'academic_year' => $request->ay, 'course' => $request->course, 'semester' => $request->semester, 'exam_type' => $request->exam_type])->groupBy('subject_id', 'subject_name')->select('subject_id', 'subject_name')->get();
            } else {
                $getSubjects = ExamRegistration::with('subject:id,subject_code,name')->where(['batch' => $request->batch, 'academic_year' => $request->ay, 'course' => $request->course, 'semester' => $request->semester, 'exam_type' => $request->exam_type, 'subject_sem' => $request->subject_sem])->groupBy('subject_id', 'subject_name')->select('subject_id', 'subject_name')->get();

            }
            return response()->json(['status' => true, 'data' => $getSubjects]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Datas Not Found']);
        }
    }

    public function subjectwiseDownload(Request $request)
    {
        if (isset($request->course) && isset($request->semester) && isset($request->exam_type) && isset($request->subject) && isset($request->batch) && isset($request->ay) && isset($request->subject_sem)) {

            if ($request->subject == 'All') {
                if ($request->subject_sem == 'All') {
                    $getSubjects = ExamRegistration::where(['batch' => $request->batch, 'academic_year' => $request->ay, 'course' => $request->course, 'semester' => $request->semester, 'exam_type' => $request->exam_type])->groupBy('subject_id')->select('subject_id')->get();
                } else {
                    $getSubjects = ExamRegistration::where(['batch' => $request->batch, 'academic_year' => $request->ay, 'course' => $request->course, 'semester' => $request->semester, 'exam_type' => $request->exam_type, 'subject_sem' => $request->subject_sem])->groupBy('subject_id')->select('subject_id')->get();
                }
            } else {
                if ($request->subject_sem == 'All') {
                    $getSubjects = ExamRegistration::where(['batch' => $request->batch, 'academic_year' => $request->ay, 'course' => $request->course, 'semester' => $request->semester, 'exam_type' => $request->exam_type, 'subject_id' => $request->subject])->groupBy('subject_id')->select('subject_id')->get();
                } else {
                    $getSubjects = ExamRegistration::where(['batch' => $request->batch, 'academic_year' => $request->ay, 'course' => $request->course, 'semester' => $request->semester, 'exam_type' => $request->exam_type, 'subject_id' => $request->subject, 'subject_sem' => $request->subject_sem])->groupBy('subject_id')->select('subject_id')->get();
                }
            }

            foreach ($getSubjects as $getSubject) {
                $getStudent = ExamRegistration::join('students', 'exam_registration.user_name_id', '=', 'students.user_name_id')
                    ->join('tools_courses', 'exam_registration.course', '=', 'tools_courses.id')
                    ->join('subjects', 'exam_registration.subject_id', '=', 'subjects.id')
                    ->join('academic_years', 'exam_registration.academic_year', '=', 'academic_years.id')
                    ->where([
                        'exam_registration.course' => $request->course,
                        'exam_registration.semester' => $request->semester,
                        'exam_registration.exam_type' => $request->exam_type,
                        'exam_registration.subject_id' => $getSubject->subject_id,
                    ])
                    ->select('exam_registration.user_name_id', 'exam_registration.semester', 'exam_registration.subject_sem', 'exam_registration.exam_type', 'exam_registration.subject_type', 'exam_registration.subject_id', 'students.name as student_name', 'students.register_no as register_no', 'tools_courses.name as course_name', 'subjects.subject_code', 'subjects.name as subject_name', 'academic_years.name as academic_year')
                    ->orderBy('register_no', 'ASC')->get();
                $getSubject->data = $getStudent;
            }

            $data = $getSubjects;
            $pdf = PDF::loadView('admin.examRegistration.downloadAttendancePDF', ['data' => $data]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('ExamRegistration.pdf');
        }
    }

    // public function resultEntry()
    // {
    //     //Applied Exam Detailed get
    //     $examRegistration = ExamRegistration::all();

    //     // unique Exam Month get
    //     $examMonths = $examRegistration->unique('exam_month')->pluck('exam_month');
    //     $examMonthAsc = $examMonths->sortBy(function ($item, $key) {
    //         return $item;
    //     });

    //     // unique Exam Year get
    //     $examYears = $examRegistration->unique('exam_year')->pluck('exam_year');
    //     $examYearAsc = $examMonths->sortBy(function ($item, $key) {
    //         return $item;
    //     });

    //     //Unique Exam type get
    //     $examTypes = $examRegistration->unique('exam_type')->pluck('exam_type');
    //     $examTypeAsc = $examMonths->sortBy(function ($item, $key) {
    //         return $item;
    //     });

    //     //Unique course Name get
    //     $course_get = $examRegistration->groupBy('course')->map(function ($course) {
    //         $data = $course->pluck('course');
    //         $course_short_form = ToolsCourse::where('id', $data[0])->select('short_form')->first();
    //         if ($course_short_form) {
    //             $data[0] = $course_short_form->short_form;
    //         } else {
    //             $data[0] = '';
    //         }
    //         return $data[0] ? $data[0] : null;
    //     });

    //     //Course Order by respective id to ASC
    //     $courseAsc = $course_get->sortBy(function ($item, $key) {
    //         return $key;
    //     });

    //     // Unique Academic Year get
    //     $AcademicYear = $examRegistration->groupBy('academic_year')->map(function ($academic_year) {
    //         $data = $academic_year->pluck('academic_year');
    //         $academic_year_name = AcademicYear::where('id', $data[0])->select('name')->first();
    //         if ($academic_year_name) {
    //             $data[0] = $academic_year_name->name;
    //         } else {
    //             $data[0] = '';
    //         }
    //         return $data[0] ? $data[0] : null;
    //     }); // Academic Year Order By Respective id to ASC

    //     $academicYearAsc = $AcademicYear->sortBy(function ($item, $key) {
    //         return $key;
    //     });

    //     //Unique Regulation get
    //     $regulationGet = $examRegistration->groupBy('regulation')->map(function ($regulation) {
    //         $data = $regulation->pluck('regulation');
    //         $regulation_name = ToolssyllabusYear::where('id', $data[0])->select('name')->first();
    //         if ($regulation_name) {
    //             $data[0] = $regulation_name->name;
    //         } else {
    //             $data[0] = '';
    //         }
    //         return $data[0] ? $data[0] : null;
    //     });
    //     // Academic Year Order By Respective id to ASC
    //     $regulationAsc = $regulationGet->sortBy(function ($item, $key) {
    //         return $key;
    //     });

    //     // ResultType get
    //     $resultType = ResultMaster::whereNotNull('result_type')->pluck('result_type', 'id');

    //     // unique Exam Month get
    //     $semesters = $examRegistration->unique('semester')->pluck('semester', 'semester');
    //     $semesterAsc = $examMonths->sortBy(function ($item, $key) {
    //         return $item;
    //     });

    //     return view('admin.examResultPublish.index', compact('regulationAsc', 'academicYearAsc', 'examMonthAsc', 'examYearAsc', 'courseAsc', 'semesterAsc', 'resultType'));

    // }

    public function resultEntryIndex()
    {
        $courses = ToolsCourse::pluck('short_form', 'id');
        $ays = AcademicYear::pluck('name', 'id');
        $regulations = ToolssyllabusYear::pluck('name', 'id');
        $exam_month = ExamResultPublish::select('exam_month')->groupBy('exam_month')->get();
        $exam_year = ExamResultPublish::select('exam_year')->groupBy('exam_year')->get();
        $publish_date = ExamResultPublish::select('publish_date')->groupBy('publish_date')->get();
        $result_type = ResultMaster::pluck('result_type', 'id');

        return view('admin.examResultPublish.index', compact('courses', 'ays', 'regulations', 'exam_year', 'exam_month', 'result_type', 'publish_date'));
    }

    public function resultget(Request $request)
    {

        $regulation = $request->input('regulation');
        $academicYear = $request->input('academicYear');
        $examMonth = $request->input('examMonth');
        $examYear = $request->input('examYear');
        $course = $request->input('course');
        $semester = $request->input('semester');
        $resultType = $request->input('resultType');
        $search_date = $request->input('search_date');
        $idValue = $request->title;
        if ($idValue == 'result_type') {
            $result_type = ResultMaster::whereNotNull('result_type')->pluck('result_type', 'id');
        } else {
            $query = ExamRegistration::select($idValue);

            if ($regulation) {
                $query->where('regulation', $regulation);
            }
            if ($academicYear) {
                $query->where('academic_year', $academicYear);
            }
            if ($examMonth) {
                $query->where('exam_month', $examMonth);
            }
            if ($examYear) {
                $query->where('exam_year', $examYear);
            }
            if ($course) {
                $query->where('course', $course);
            }
            if ($semester) {
                $query->where('semester', $semester);
            }
            if ($resultType) {
                $query->where('result_type', $resultType);
            }
            if ($search_date) {
                $query->where('search_date', $search_date);
            }

            $examRegistration = $query->get();

            if (count($examRegistration) > 0) {

                if ($regulation) {
                    $academic_year = $examRegistration->groupBy('academic_year')->map(function ($academic_year) {
                        $data = $academic_year->pluck('academic_year');
                        $academic_year_name = AcademicYear::where('id', $data[0])->select('name')->first();
                        if ($academic_year_name) {
                            $data[0] = $academic_year_name->name;
                        } else {
                            $data[0] = '';
                        }
                        return $data[0] ? $data[0] : null;
                    });
                }

                $examMonths = $examRegistration->unique('exam_month')->pluck('exam_month');
                $exam_month = $examMonths->sortBy(function ($item, $key) {
                    return $item;
                });

                //Unique course Name get
                $course_get = $examRegistration->groupBy('course')->map(function ($course) {
                    $data = $course->pluck('course');
                    $course_short_form = ToolsCourse::where('id', $data[0])->select('short_form')->first();
                    if ($course_short_form) {
                        $data[0] = $course_short_form->short_form;
                    } else {
                        $data[0] = '';
                    }
                    return $data[0] ? $data[0] : null;
                });

                //Course Order by respective id to ASC
                $course = $course_get->sortBy(function ($item, $key) {
                    return $key;
                });

                // unique Exam Year get
                $examYears = $examRegistration->unique('exam_year')->pluck('exam_year');
                $exam_year = $examYears->sortBy(function ($item, $key) {
                    return $item;
                });

                // unique Exam Month get
                $semesters = $examRegistration->unique('semester')->pluck('semester', 'semester');
                $semester = $semesters->sortBy(function ($item, $key) {
                    return $item;
                });

                //Unique Exam type get
                $examTypes = $examRegistration->unique('exam_type')->pluck('exam_type');
                $exam_type = $examTypes->sortBy(function ($item, $key) {
                    return $item;
                });

            } else {
                $$idValue = 0;

            }

        }

        if (count($$idValue) > 0) {
            return response()->json([$idValue => $$idValue]);
        } else {
            return response()->json([$idValue => []]);

        }

    }

    public function hallTicket(Request $request)
    {
        $batches = Batch::pluck('name', 'id');
        $courses = ToolsCourse::pluck('short_form', 'id');
        $ays = AcademicYear::pluck('name', 'id');
        $exam_month_year = ExamRegistration::groupBy('exam_month', 'exam_year')->select('exam_month', 'exam_year')->get();

        return view('admin.examRegistration.hallTicket', compact('batches', 'courses', 'ays', 'exam_month_year'));
    }

    public function hallTicketPreview(Request $request)
    {
        if (isset($request->ay) && isset($request->batch) && isset($request->semester) && isset($request->course) && isset($request->user_name_id) && isset($request->exam_month_year)) {
            $stu_status = '';
            $enrolls = [];
            if (str_contains($request->user_name_id, '|')) {
                // $theWord = substr($request->user_name_id,0,(strlen($request->user_name_id) - 1));
                $stu_status = 'All';
                $explode = explode('|', $request->user_name_id);
                if (count($explode) > 1) {
                    foreach ($explode as $enroll) {
                        array_push($enrolls, $enroll);
                    }
                }
                $students = Student::whereIn('enroll_master_id', $enrolls)->select('user_name_id', 'name', 'register_no')->get();
                if (count($students) <= 0) {
                    $students = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $enrolls)->select('students.user_name_id', 'students.name', 'students.register_no')->get();
                }
            } else if ($request->user_name_id != '') {
                $students = Student::where(['user_name_id' => $request->user_name_id])->select('user_name_id', 'name', 'register_no')->get();
                $stu_status = $request->user_name_id;
            } else {
                $students = [];
            }
            $exam_month = null;
            $exam_year = null;

            $explodeExamMonth = explode('|', $request->exam_month_year);
            if (count($explodeExamMonth) > 0) {
                $exam_month = $explodeExamMonth[0];
                $exam_year = $explodeExamMonth[1];
            } else {
                return response()->json(['status' => false, 'data' => 'Exam Month & Year Not Found']);
            }

            if (count($students) > 0) {
                $data = [];
                foreach ($students as $student) {
                    $query = ExamRegistration::with('profile:nameofuser_id,filePath', 'ay:id,name', 'batches:id,name', 'courses:id,short_form,name', 'regulations:id,name', 'student:name,register_no,user_name_id', 'subject:id,subject_code,name,credits', 'personal_details:user_name_id,dob,email,mobile_number')->where(['academic_year' => $request->ay, 'course' => $request->course, 'semester' => $request->semester, 'user_name_id' => $student->user_name_id, 'exam_year' => $exam_year, 'exam_month' => $exam_month])->orderBy('subject_sem', 'DESC')->get();
                    if (count($query) > 0) {
                        array_push($data, $query);
                    }
                }
                return response()->json(['status' => true, 'data' => $data, 'stu_status' => $stu_status, 'enrolls' => $enrolls]);
            } else {
                return response()->json(['status' => false, 'data' => 'Register No Not Found']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Required Datas Not Found']);
        }
    }

    public function hallticketPreviewPdf(Request $request)
    {
        if (isset($request->batch) && isset($request->ay) && isset($request->course) && isset($request->semester) && isset($request->user_name_id) && isset($request->enroll)) {
            $students = [];

            if ($request->user_name_id == 'All') {
                $batches = Batch::where(['id' => $request->batch])->select('name')->first();
                $courses = ToolsCourse::where(['id' => $request->course])->select('name')->first();
                $ays = AcademicYear::where(['id' => $request->ay])->select('name')->first();
                if ($batches != '') {
                    $batch = $batches->name;
                } else {
                    return response()->json(['status' => false, 'data' => 'Batch Not Found']);
                }

                if ($courses != '') {
                    $course = $courses->name;
                } else {
                    return response()->json(['status' => false, 'data' => 'Course Not Found']);
                }

                if ($ays != '') {
                    $ay = $ays->name;
                } else {
                    return response()->json(['status' => false, 'data' => 'AY Not Found']);
                }

                $theStudents = Student::where('enroll_master_id', $request->enroll)->select('user_name_id')->get();
                if (count($theStudents) <= 0) {
                    $theStudents = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $request->enroll)->select('students.user_name_id')->get();
                }
                foreach ($theStudents as $student) {
                    $getStudent = ExamRegistration::with('profile:nameofuser_id,filePath', 'courses:id,name', 'student:name,register_no,user_name_id', 'subject:id,subject_code,name', 'personal_details:user_name_id,dob')->where(['batch' => $request->batch, 'academic_year' => $request->ay, 'course' => $request->course, 'semester' => $request->semester, 'user_name_id' => $student->user_name_id])->orderBy('subject_sem', 'DESC')->get();

                    if (count($getStudent) > 0) {
                        array_push($students, $getStudent);
                    }

                }

            } else {
                $getStudent = ExamRegistration::with('profile:nameofuser_id,filePath', 'courses:id,name', 'student:name,register_no,user_name_id', 'subject:id,subject_code,name', 'personal_details:user_name_id,dob')->where(['batch' => $request->batch, 'academic_year' => $request->ay, 'course' => $request->course, 'semester' => $request->semester, 'user_name_id' => $request->user_name_id])->orderBy('subject_sem', 'DESC')->get();
                array_push($students, $getStudent);
            }
            // $datas = $students;
            // dd($datas);
            // $sem = $request->semester;
            // return view('admin.examRegistration.downloadHallTicket', compact('datas','sem'));
            $pdf = PDF::loadView('admin.examRegistration.downloadHallTicket', ['datas' => $students, 'sem' => $request->semester]);
            // $pdf->setPaper('A4', 'portrait')->setWarnings(false)->output();
            return $pdf->stream('HallTicket.pdf');
        }
    }
}
