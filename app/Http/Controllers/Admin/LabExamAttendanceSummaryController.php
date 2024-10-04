<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Models\User;
use App\Models\Course;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\ClassRoom;
use Nette\Utils\DateTime;
use App\Models\ToolsCourse;
use App\Models\AcademicYear;
use App\Models\CollegeBlock;
use Illuminate\Http\Request;
use App\Models\LabFirstmodel;
use App\Models\TeachingStaff;
use App\Models\ToolsDepartment;
use Illuminate\Validation\Rule;
use App\Models\AttendanceRecord;
use App\Models\NonTeachingStaff;
use App\Models\SubjectAllotment;
use App\Models\ClassTimeTableTwo;
use App\Models\LabExamAttendance;
use App\Models\CourseEnrollMaster;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\LabExamAttendanceData;
use App\Models\StudentPromotionHistory;
use Yajra\DataTables\Facades\DataTables;

class LabExamAttendanceSummaryController extends Controller
{

    public function getClassName($request)
    {
        $className = CourseEnrollMaster::find($request);
        $classname = $className != null ? $className->enroll_master_number : '';

        if ($classname != '') {
            $newArray = explode('/', $className->enroll_master_number);
            $get_course = ToolsCourse::where(['name' => $newArray[1]])->first();

            if ($get_course) {
                $class_name = $get_course->short_form . '/' . $newArray[3] . '/' . $newArray[4];
            }
        } else {
            $class_name = '';
        }

        return $class_name;
    }

    public function index(Request $request)
    {

        $departments = ToolsDepartment::pluck('name', 'id')->prepend('Select Department', '');
        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $AcademicYear = AcademicYear::pluck('name', 'id');
        $examNames = LabFirstmodel::select('exam_name')->distinct()->get();
        // dd($examNames);
        $academic_years = AcademicYear::pluck('name', 'id')->prepend('Select Academic Year', '');


        return view('admin.LabExam_attendance_summary.attendanceSummary', compact('departments', 'academic_years', 'courses', 'semester', 'examNames', 'AcademicYear'));
    }

    public function course_get(Request $request)
    {
        $get_course = [];
        if ($request->department_id != '') {
            if ($request->department_id != '5') {
                $get_course = ToolsCourse::where(['department_id' => $request->department_id])->select('id', 'name', 'short_form', 'department_id')->get();
            } else {
                $get_course = ToolsCourse::select('id', 'name', 'short_form', 'department_id')->get();
            }
        }
        if (count($get_course) > 0) {

            return response()->json(['courses' => $get_course]);
        } else {
            return response()->json(['status' => 'fail']);
        }
    }

    function  subject_get(Request $request)
    {

        $academicYear_id = $request->academicYear_id;
        $course_id = $request->course_id;
        $semester_id = $request->semester_id;
        // $section = $request->section;


        if ($request->course_id != '' || $request->semester != '' || $request->accademicYear != '') {

            // $get_section = [];
            // $check_input = is_numeric($request->course_id);

            // if ($check_input) {
            //     $get_section = Section::where(['course_id' => $request->course_id])->select('id', 'section')->get();
            // } else {

            //     $get_course = ToolsCourse::where(['name' => $request->course_id])->first();
            //     if ($get_course != '') {
            //         $get_section = Section::where(['course_id' => $get_course->id])->get();
            //     }
            // }

            $get_subjects = [];
            $get_subjects = SubjectAllotment::where(['semester' => $request->semester, 'course' => $request->course_id, 'academic_year' => $request->accademicYear, 'semester_type' => $request->semesterType])->get();

            $subjects = [];
            $got_subject = [];
            if (count($get_subjects) > 0) {
                foreach ($get_subjects as $subject) {
                    $got_subject = Subject::where(['id' => $subject->subject_id])->first();
                    if ($got_subject != '') {
                        array_push($subjects, $got_subject);
                    }
                }
                // return response()->json(['subjects' => $subjects]);
            }

            if ($academicYear_id  != '' && $course_id != '' &&   $semester_id != '') {


                $exam_name = LabFirstmodel::where(['accademicYear' => $academicYear_id, 'course_id' => $course_id, 'semester' => $semester_id,])->select('exam_name')->distinct()->get();
                if (count($exam_name) > 0) {

                    return response()->json(['exam_name' => $exam_name, 'status' => 'success']);
                } else {
                    return response()->json(['status' => 'exam_Name']);
                }
            }

            //ExamTimetableCreation



            if ($subjects != '') {
                return response()->json(['subjects' => $subjects, 'get_section' => $get_section]);
            } else if ($subjects != '') {

                return response()->json(['subjects' => $subjects]);
            } else {

                return response()->json(['status' => 'fail']);
            }
        } else {
            return response()->json(['status' => 'fail']);
        }
    }

    public function search(Request $request)
    {
        if (isset($request->academicYear_id, $request->year, $request->course_id, $request->semester_id, $request->examename, $request->search_date, $request->department)) {
            $searchDate = date('Y-m-d', strtotime($request->search_date));


            $academicYear_id = $request->academicYear_id;
            $year = $request->year;
            $semester_id = $request->semester_id;
            $exameName = $request->examename;
            $date = date('Y-m-d', strtotime($request->search_date));
            $department = $request->department;
            $course_id = $request->course_id;

            $newData = [];
            $newData[] = $academicYear_id;
            $newData[] = $year;
            $newData[] = $semester_id;
            $newData[] = base64_encode($exameName);
            $newData[] = $date;
            $newData[] = $department;
            $newData[] = $course_id;


            $response = LabExamAttendance::select('lab_exam_id', 'id', 'section', 'att_entered', 'subject')
                ->where('acyear', $request->academicYear_id)
                ->where('examename', $request->examename)
                ->where('sem', $request->semester_id)
                ->where('course', $request->course_id)
                ->where('year', $request->year)
                ->where('date', $searchDate)
                ->get();

            if (!$response->isEmpty()) {
                $absentees_data = [];
                $absentees_data2 = [];
                $subject = '';
                $id = '';
                // $newdata=[];
                foreach ($response as $response_item) {
                    $student_data = LabExamAttendanceData::where('lab_exam_name', $response_item->id)->get();
                    $section_absentees = [];
                    $studentReg = [];
                    $totalPres = 0;
                    $totalAbs = 0;
                    $className = '';

                    $findSubject = Subject::find($response_item->subject);
                    if ($findSubject) {
                        $subject = $findSubject->name . '(' . $findSubject->subject_code . ')';
                    }
                    $id = $response_item->lab_exam_id;
                    if ($student_data) {
                        foreach ($student_data as $student_data_item) {
                            if ($student_data_item->attendance == 'Absent') {
                                $totalAbs++;
                                $student = Student::where('user_name_id', $student_data_item->student_id)->first();
                                if ($student) {
                                    $section_absentees[] = $student->name;
                                    $studentReg[] = $student->register_no;
                                }
                            } else {
                                $totalPres++;
                            }
                            $className = $this->getClassName($student_data_item->class_id);
                        }
                    }

                    $absentees_data[] = [
                        'Class' => $className,
                        'Absent Students' => $section_absentees,
                        'Student Register Number' => $studentReg,
                    ];
                    $absentees_data2[] = [
                        'totalCount' => $response_item->att_entered == 'Yes' ? count($student_data) : 'Attendance Not taken',
                        'totalPres' => $response_item->att_entered == 'Yes' ? $totalPres : 'Attendance Not taken',
                        'totalAbs' => $response_item->att_entered == 'Yes' ? $totalAbs : 'Attendance Not taken',
                    ];
                }


                return response()->json(['data' => $absentees_data, 'data2' => $absentees_data2, 'subject' => $subject, 'id' => $id, 'newData' => $newData]);
            }
        }

        return response()->json(['data' => '']);
    }

    public function getDate(Request $request)
    {
        // dd($request);
        $response = [];
        if (isset($request->academicYear_id, $request->year_id, $request->course_id, $request->semester_id, $request->examename)) {
            $response = LabExamAttendance::select('date')
                ->where('acyear', $request->academicYear_id)
                ->where('examename', $request->examename)
                ->where('sem', $request->semester_id)
                ->where('course', $request->course_id)
                ->where('year', $request->year_id)->groupBy('date')
                ->get();
            // foreach( $response as)


        }


        if (count($response) > 0) {

            return response()->json(['data' => $response]);
        } else {
            return response()->json(['status' => 'fail']);
        }
    }

    public function absenteesReportPDF($id, $academicYear_id, $year, $semester_id, $date, $department, $course_id, $exameName)
    {


        // $course_name = ToolsCourse::find($course_id)->first();
        $course_name = ToolsCourse::where('id', $course_id)->select('name', 'short_form')->first();
        $explod_course_name = strtoupper(ltrim(explode('B.E.', $course_name->name)[1]));

        $course_title = 'DEPARTMENT OF ' . $explod_course_name;

        $roman = app(ExamTimetableCreationController::class);
        $parts = explode('.', $course_name->short_form);
        $explosd_short_course = trim(end($parts));
        // $explosd_short_course = explode('.', $course_name->short_form);
        // $course_name = $getCourse -> name;
        // $parts = explode('.',$course_name);
        $roman_year = $roman->toRoman($year);
        $roman_sem = $roman->toRoman($semester_id);
        $department_title = $roman_year . ' YEAR ' . $roman_sem . ' SEM - ' . $explosd_short_course;

        // $class_title = $roman_year. ' YEAR ' . $roman_sem .' SEM - '.$explosd_short_course.' (SEC-'.$section.')' ;
        $analysis = strtoupper('Subjectwise result analysis');

        $assessment_title = strtoupper('internal assessment ') . ltrim(explode('/', explode('-', base64_decode($exameName))[1])[0]);

        // dd($id, $academicYear_id,$year,$semester_id,$date,$department);
        $searchDate = date('Y-m-d', strtotime($date));

        if (isset($id) && $id != '') {

            $query = LabExamAttendance::query()->select('lab_exam_id', 'id', 'section', 'att_entered', 'subject')
                ->where('acyear', $academicYear_id)
                ->where('sem', $semester_id)
                ->where('course', $course_id)
                ->where('year', $year)
                ->where('date', $searchDate);

            $response = $query->get();


            if (!$response->isEmpty()) {
                $absentees_data = [];
                $absentees_data2 = [];
                $subject = '';
                $id = '';
                $si = 0;
                foreach ($response as $response_item) {
                    $student_data = LabExamAttendanceData::where('lab_exam_name', $response_item->id)->get();
                    $section_absentees = [];
                    $studentReg = [];
                    $totalPres = 0;
                    $totalAbs = 0;
                    $className = '';

                    $findSubject = Subject::find($response_item->subject);
                    if ($findSubject) {
                        $subject = $findSubject->name . '(' . $findSubject->subject_code . ')';
                    }
                    $id = $response_item->lab_exam_id;
                    if ($student_data) {
                        foreach ($student_data as $student_data_item) {
                            if ($student_data_item->attendance == 'Absent') {
                                $totalAbs++;
                                $student = Student::where('user_name_id', $student_data_item->student_id)->first();
                                if ($student) {
                                    $section_absentees[] = $student->name;
                                    $studentReg[] = $student->register_no;
                                }
                            } else {
                                $totalPres++;
                            }
                            $className = $this->getClassName($student_data_item->class_id);
                            // dd($className);
                        }
                    }

                    $newData[$si] = [
                        'Class' => $className,
                        'subject' => $subject,
                        'id' => $id,
                        'Absent Students' => $section_absentees,
                        'Student Register Number' => $studentReg,
                        'totalCount' => $response_item->att_entered == 'Yes' ? count($student_data) : 'Attendance Not taken',
                        'totalPres' => $response_item->att_entered == 'Yes' ? $totalPres : 'Attendance Not taken',
                        'totalAbs' => $response_item->att_entered == 'Yes' ? $totalAbs : 'Attendance Not taken',
                        'date' => $searchDate,
                    ];
                    $si++;
                }
                $newData2 = [];
                $newData2[] = [
                    'course_title' => $course_title,
                    'department_title' => $department_title,
                    'analysis' => $analysis,
                    'assessment_title' => $assessment_title,
                ];
                // dd($newData);
                // return response()->json(['data' => $absentees_data, 'data2' => $absentees_data2, 'subject' => $subject, 'id' => $id]);
                $pdf = PDF::loadView('admin.LabExam_attendance_summary.absenteesRepPDF', ['data' => $newData, 'data2' => $newData2]);
                $pdf->setPaper('A4', 'portrait');
                return $pdf->stream('absenteesRepPDF.pdf');
            }



            // $pdf->setPaper('A4', 'landscape');

        }
    }


    public function Result_Analysis_Class_Wise()
    {
        $departments = ToolsDepartment::pluck('name', 'id')->prepend('Select Department', '');
        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $AcademicYear = AcademicYear::pluck('name', 'id');
        $examNames = LabFirstmodel::select('exam_name')->distinct()->get();
        // dd($examNames);
        $academic_years = AcademicYear::pluck('name', 'id')->prepend('Select Academic Year', '');
        return view('admin.labResultAnalysis.index', compact('departments', 'academic_years', 'courses', 'semester', 'examNames', 'AcademicYear'));
    }

    public function get(Request $request)
    {
        $departments = ToolsDepartment::pluck('name', 'id')->prepend('Select Department', '');
        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $AcademicYear = AcademicYear::pluck('name', 'id');
        $examNames = LabFirstmodel::select('exam_name')->distinct()->get();
        $academic_years = AcademicYear::pluck('name', 'id')->prepend('Select Academic Year', '');

        $newDatas = [];
        $newDatas['Ay'] = $request->AcademicYear;
        $newDatas['Ex'] = $request->examename;
        $newDatas['Sem'] = $request->semester;
        $newDatas['Course'] = $request->course;
        $newDatas['Year'] = $request->year;
        $newDatas['Sec'] = $request->section;

        // Build the query without executing it
        $query = LabExamAttendance::query()
            ->where('acyear', $request->AcademicYear)
            ->where('examename', $request->examename)
            ->where('sem', $request->semester)
            ->where('course', $request->course)
            ->where('year', $request->year)
            ->where('section', $request->section);

        $response = $query->get();

        $status_count = LabExamAttendance::query()
            ->where('acyear', $request->AcademicYear)
            ->where('examename', $request->examename)
            ->where('sem', $request->semester)
            ->where('course', $request->course)
            ->where('year', $request->year)
            ->where('section', $request->section)
            ->where('status', 2);

        $status_count = $status_count->count();


        if ($response->isNotEmpty() && $status_count != '') {
            if ($status_count == count($response)) {
                // Fetch CO marks from ExamTimetableCreation using eager loading

                $exams = LabFirstmodel::whereIn('id', $response->pluck('lab_exam_id'))->get();

                foreach ($response as $responses) {
                    // Find the corresponding ExamTimetableCreation
                    $newData = $exams->where('id', $responses->lab_exam_id)->first();

                    // Calculate subjectTotal

                    $responses->subjectTotal = 100;

                    // Fetch related student data using eager loading
                    $student_data = LabExamAttendanceData::where('lab_exam_name', $responses->id)->get();
                    if (!$student_data->isEmpty() && $student_data[0]->subject == $responses->subject) {
                        $responses->newArray = $student_data;
                    }



                    // Fetch subject name
                    $findSubject = Subject::find($responses->subject);
                    if ($findSubject) {
                        $responses->subjectName = $findSubject->name . '(' . $findSubject->subject_code . ')';
                    }

                    $student_data = LabExamAttendanceData::select('class_id')->where('lab_exam_name', $responses->id)->first();
                    if ($student_data != '') {
                        $stu = CourseEnrollMaster::find($student_data->class_id);
                        if ($stu) {
                            $responses->className = $this->getClassName($student_data->class_id);
                        }
                    }
                }

                $student1 = Student::get();
                $newData = $newDatas;
            } else {
                $student1 = [];
                $newData = [];
                $response = [];
            }
        }else {
            $student1 = [];
            $newData = [];
            $response = [];
        }
            // dd($response[0]['newArray'][0]);
        return view('admin.labResultAnalysis.index', compact('student1', 'departments', 'academic_years', 'courses', 'semester', 'examNames', 'AcademicYear', 'response', 'newData'));
    }
    public function Result_Analysis_Staff_Wise(Request $request)
    {

        $departments = ToolsDepartment::pluck('name', 'id')->prepend('Select Department', '');
        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $AcademicYear = AcademicYear::pluck('name', 'id');
        $examNames = LabFirstmodel::select('exam_name')->distinct()->get();
        $academic_years = AcademicYear::pluck('name', 'id')->prepend('Select Academic Year', '');
        return view('admin.labResultAnalysis.staffWise', compact('departments', 'academic_years', 'courses', 'semester', 'examNames', 'AcademicYear'));
    }

    public function staff_wise(Request $request)
    {


        $AcademicYear = $request->AcademicYear;
        $year = $request->year;
        $semester = $request->semester;
        $examename = $request->examename;
        $department = $request->department;
        $course = $request->course;
        $section = $request->section;

        $newData = [];
        $newData[] = $AcademicYear;
        $newData[] = $year;
        $newData[] = $semester;
        $newData[] = $examename;
        $newData[] = $course;
        $newData[] = $section;

        $departments = ToolsDepartment::pluck('name', 'id')->prepend('Select Department', '');
        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $AcademicYear = AcademicYear::pluck('name', 'id');
        $examNames = LabFirstmodel::select('exam_name')->distinct()->get();
        $academic_years = AcademicYear::pluck('name', 'id')->prepend('Select Academic Year', '');

        $query = LabExamAttendance::query()
            ->where('acyear', $request->AcademicYear)
            ->where('examename', $request->examename)
            ->where('sem', $request->semester)
            ->where('course', $request->course)
            ->where('year', $request->year)
            ->where('section', $request->section);

        $response = $query->select('lab_exam_id', 'examename', 'total_present', 'total_abscent', 'subject', 'id', 'mark_entereby', 'course', 'acyear', 'sem')->get();

        $count = LabExamAttendance::query()
            ->where('acyear', $request->AcademicYear)
            ->where('examename', $request->examename)
            ->where('sem', $request->semester)
            ->where('course', $request->course)
            ->where('year', $request->year)
            ->where('section', $request->section)
            ->where('status', 2);
        $subject_count = $count->count();

        if ($response->isNotEmpty() && $subject_count == count($response) && $subject_count != '') {
            foreach ($response as $responses) {
                $findSubject = Subject::find($responses->subject);
                if ($findSubject) {
                    $responses->subjectName = $findSubject->name . '(' . $findSubject->subject_code . ')';
                }
                // Calculate total marks for the exam
                $firstTable = LabFirstmodel::find($responses->lab_exam_id);
                $totalMark = 100;


                // Initialize pass and fail counters
                $studentPass = 0;
                $studentFail = 0;


                // Fetch student data for the exam
                $student_data = LabExamAttendanceData::where('lab_exam_name', $responses->id)->select('attendance','cycle_mark')->get();
                $new = [];
                if (!$student_data->isEmpty()) {
                    // $count=0;
                    foreach ($student_data as $student_datas) {
                        // $studentTotal = 0;
                        $studentTotal = $student_datas->cycle_mark;
                        // if( $studentTotal == 0){
                        //     $count++;
                        // }
                        // Check if the student passed or failed based on total marks
                        // if($student_datas->subject=43){
                        //     $new[]=$studentTotal;
                        // }
                        if ($student_datas->attendance == 'Present') {
                            if ($studentTotal >= ($totalMark * 0.5)) {

                                $studentPass++;
                                // $new[]=$studentTotal;
                            }else{

                                $studentFail++;

                            }

                        }else{

                            $studentFail++;

                        }
                    }
                }



                //

                // Assign the counts and total marks to the $responses object
                $responses->studentFail = $studentFail;
                $responses->studentPass = $studentPass;
                $responses->totalMark = $totalMark;

                // Fetch additional data and assign it to the $responses object

                $student_data = LabExamAttendanceData::select('class_id')->where('lab_exam_name', $responses->id)->first();
                if ($student_data != '') {
                    $stu = CourseEnrollMaster::find($student_data->class_id);
                    $staffId = null;

                    $stu = CourseEnrollMaster::find($student_data->class_id);
                    $staffId = null;

                    if ($stu != null) {

                        $responses->className = $this->getClassName($student_data->class_id);
                        $students = Student::where('enroll_master_id', $stu->id)->count();

                        if ($students <= 0) {
                            $students = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $stu->id)->count();
                        }
                        if ($students) {
                            $responses->totalstudent = $students;
                        }

                        $staff = ClassTimeTableTwo::where([
                            'class_name' => $stu->id,
                            'subject' => $responses->subject ?? '',
                            'status' => '1',
                        ])->first();

                        if ($staff) {
                            $staffId = $staff->staff;
                            $staff1 = TeachingStaff::where('user_name_id', $staffId)->first();
                            //  dd($staff1);
                            if ($staff1) {

                                // dd('helo');
                                $responses->staffName = $staff1->name . '(' . $staff1->StaffCode . ')';
                            }
                        }
                    }
                }

                if ($responses->total_present > 0) {
                    $passPercentage = ($responses->studentPass / $responses->totalstudent) * 100;
                    $responses->subPassper = number_format($passPercentage, 2);
                } else {
                    $responses->subPassper = 0; // Handle the case where there are no students
                }
            }
        } else {

            $response = [];
        }

        return view('admin.labResultAnalysis.staffWise', compact('departments', 'academic_years', 'courses', 'semester', 'examNames', 'AcademicYear', 'response', 'newData'));
    }

    public function staff_wisePDF(Request $request, $ay, $year, $sem, $examname, $course, $section)
    {

        $AcademicYear = $ay;
        $year = $year;
        $semester = $sem;
        $examename = base64_decode($examname);
        $course = $course;
        $section = $section;

        $course_name = ToolsCourse::find($course)->first();
        $course_name = ToolsCourse::where('id', $course)->select('name', 'short_form')->first();
        // $explod_course_name = strtoupper(ltrim(explode('B.E.', $course_name->name)[1]));
        $parts = explode('.', $course_name->short_form);
        $explosd_short_course = trim(end($parts));

        $course_title = 'DEPARTMENT OF ' . $explosd_short_course;

        $roman = app(ExamTimetableCreationController::class);
        $explosd_short_course = trim(explode('B.E.', $course_name->short_form)[1]);

        $roman_year = $roman->toRoman($year);
        $roman_sem = $roman->toRoman($sem);

        $class_title = $roman_year . ' YEAR ' . $roman_sem . ' SEM - ' . $explosd_short_course . ' (SEC-' . $section . ')';
        $analysis = strtoupper('staffwise result analysis');

        $assessment_title = strtoupper('internal assessment ') . ltrim(explode('/', explode('-', base64_decode($examname))[1])[0]);




        $query = LabExamAttendance::query()
            ->where('acyear', $AcademicYear)
            ->where('examename', $examename)
            ->where('sem', $semester)
            ->where('course', $course)
            ->where('year', $year)
            ->where('section', $section);


        $response = $query->select('lab_exam_id', 'examename', 'total_present', 'total_abscent', 'subject', 'id', 'mark_entereby', 'course', 'acyear', 'sem')->get();
        $response->course_title = $course_title;
        $response->class_title = $class_title;
        $response->analysis = $analysis;
        $response->assessment_title = $assessment_title;
        if ($response->isNotEmpty()) {
            foreach ($response as $responses) {
                $findSubject = Subject::find($responses->subject);
                $responses->exam_name = $examename;
                if ($findSubject) {
                    $responses->subjectName = $findSubject->name . '(' . $findSubject->subject_code . ')';
                }
                $firstTable = LabFirstmodel::find($responses->lab_exam_id)->select('exam_name')->first();
                $response->exam_name = $firstTable->exam_name;
                $totalMark = 100;
                $studentPass = 0;
                $studentFail = 0;


                $student_data = LabExamAttendanceData::where('lab_exam_name', $responses->id)->select('attendance','cycle_mark')->get();
                $new = [];
                if (!$student_data->isEmpty()) {
                    foreach ($student_data as $student_datas) {
                        $studentTotal = 0;
                        $studentTotal = $student_datas->cycle_mark;
                        if ($student_datas->attendance == 'Present') {
                            if ($studentTotal >= ($totalMark * 0.5)) {

                                $studentPass++;
                                // $new[]=$studentTotal;
                            }else{

                                $studentFail++;

                            }

                        }else{

                            $studentFail++;

                        }
                    }
                }

                //

                // Assign the counts and total marks to the $responses object
                $responses->studentFail = $studentFail;
                $responses->studentPass = $studentPass;
                $responses->totalMark = $totalMark;

                // Fetch additional data and assign it to the $responses object
                $student_data = LabExamAttendanceData::select('class_id')->where('lab_exam_name', $responses->id)->first();
                $stu = CourseEnrollMaster::find($student_data->class_id);
                $staffId = null;

                if ($stu != null) {
                    $responses->className = $this->getClassName($student_data->class_id);

                    $students = Student::where('enroll_master_id', $stu->id)->count();
                    if ($students <= 0) {
                        $students = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $stu->id)->count();
                    }
                    if ($students) {
                        $responses->totalstudent = $students;
                    }

                    $staff = ClassTimeTableTwo::where([
                        'class_name' => $stu->id,
                        'subject' => $responses->subject ?? '',
                        'status' => '1',
                    ])->first();

                    if ($staff) {
                        $staffId = $staff->staff;
                        $staff1 = TeachingStaff::where('user_name_id', $staffId)->first();
                        if ($staff1) {

                            // dd('helo');
                            $responses->staffName = $staff1->name . '(' . $staff1->StaffCode . ')';
                        }
                    }
                }

                if ($responses->total_present > 0) {
                    $passPercentage = ($responses->studentPass / $responses->totalstudent) * 100;
                    $responses->subPassper = number_format($passPercentage, 2);
                } else {
                    $responses->subPassper = 0; // Handle the case where there are no students
                }
            }
        }
        $pdf = PDF::loadView('admin.labResultAnalysis.Exam_subject_wise_report', ['response' => $response]);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Exam_subject_wise_report.pdf');
    }

    public function Abstract(Request $request)
    {
        // dd($request);
        $departments = ToolsDepartment::pluck('name', 'id')->prepend('Select Department', '');
        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $AcademicYear = AcademicYear::pluck('name', 'id');
        $examNames = LabFirstmodel::select('exam_name')->distinct()->get();
        $uniqueExamNames = [];

        if ($examNames) {
            foreach ($examNames as $examName) {
                $catName = explode('/', $examName->exam_name);
                $examNameValue = $catName[0];

                // Check if the exam name is not already in the array
                if (!in_array($examNameValue, $uniqueExamNames)) {
                    $uniqueExamNames[] = $examNameValue;
                }
            }
        }
        // dd($examNames);
        $academic_years = AcademicYear::pluck('name', 'id')->prepend('Select Academic Year', '');


        return view('admin.labResultAnalysis.Abstract', compact('departments', 'academic_years', 'courses', 'semester', 'uniqueExamNames', 'AcademicYear'));
    }
    public function chart(request $request)
    {
        $departments = ToolsDepartment::pluck('name', 'id')->prepend('Select Department', '');
        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $AcademicYear = AcademicYear::pluck('name', 'id');
        $examNames = LabFirstmodel::select('exam_name')->distinct()->get();
        $academic_years = AcademicYear::pluck('name', 'id')->prepend('Select Academic Year', '');
        return view('admin.labResultAnalysis.chart', compact('departments', 'academic_years', 'courses', 'semester', 'examNames', 'AcademicYear'));
    }

    public function showChart(Request $request)
    {
        $query = LabExamAttendance::query()
            ->where('acyear', $request->academicYear_id)
            ->where('examename', $request->examename)
            ->where('sem', $request->semester_id)
            ->where('course', $request->course_id)
            ->where('year', $request->year)
            ->where('section', $request->section);

        $secondTable = $query->get();
        // dd($secondTable);
        $mainArray = [];
        if ($secondTable->isNotEmpty()) {
            // Fetch CO marks from ExamTimetableCreation using eager loading


            if (!$secondTable->isEmpty()) {

                $studentsPassedAllSubjects = 0; // Move this variable outside the inner loop
                $AllAbsent = 0;

                foreach ($secondTable as $secondTables) {
                    $firstTable = LabFirstmodel::find($secondTables->lab_exam_id);
                    $totalMark = 100;
                    $subject = [];
                    $test = [];

                    $student_data = LabExamAttendanceData::where('lab_exam_name', $secondTables->id)->where('subject', $secondTables->subject)->select('attendance','cycle_mark')->get();
                    $studentPass = 0;
                    $studentFail = 0;
                    $absent = 0;
                    $present = 0;
                    $totalStudents = count($student_data);

                    if (!$student_data->isEmpty()) {
                        foreach ($student_data as $student_datas) {
                            $studentTotal = 0;
                            $passedAllSubjects = false;
                            $allpresent = false;
                            $studentTotal = $student_datas-> cycle_mark;

                            if ($student_datas->attendance == 'Present') {
                                $present++;
                                if ($studentTotal >= ($totalMark * 0.5)) {

                                    $studentPass++;
                                    // $new[]=$studentTotal;
                                }else{

                                    $studentFail++;

                                }

                            }else{

                                $studentFail++;
                                $absent++;

                            }

                            if ($student_datas->attendance == 'Present') {
                                if ($studentTotal < ($totalMark * 0.5)) {
                                    $passedAllSubjects = true;
                                }
                            }

                            if ($student_datas->attendance != 'Present') {
                                $allpresent = true;
                            }
                        }

                        if ($passedAllSubjects) {
                            $studentsPassedAllSubjects++;
                        }
                        if ($allpresent) {
                            $AllAbsent++;
                        }
                    }

                    $subject = [
                        // $present,$absent,$studentPass,$studentFail,$secondTables->subject
                        'studentPass' => $studentPass,
                        'studentFail' => $studentFail,
                        'subject' => $secondTables->subject,
                        'present' => $present,
                        'absent' => $absent,
                    ];
                    // $labels=[];
                    $sub = Subject::find($secondTables->subject);
                    $label = $sub->subject_code;

                    $test['labels'] = $label;

                    $test['response'] = $subject;

                    $mainArray[] = $test;


                }
            }
        }
        return response()->json($mainArray);
    }


    public function Exam_classWise_ReportPDF($ay, $year, $sem, $examname, $course, $section)
    {

        $course_name = ToolsCourse::find($course)->first();
        $course_name = ToolsCourse::where('id', $course)->select('name', 'short_form')->first();
        $explod_course_name = strtoupper(ltrim(explode('B.E.', $course_name->name)[1]));

        $course_title = 'DEPARTMENT OF ' . $explod_course_name;

        $roman = app(ExamTimetableCreationController::class);
        // $explosd_short_course = trim(explode('B.E.', $course_name->short_form)[1]);
        $parts = explode('.', $course_name->short_form);
        $explosd_short_course = trim(end($parts));
        $roman_year = $roman->toRoman($year);
        $roman_sem = $roman->toRoman($sem);

        $class_title = $roman_year . ' YEAR ' . $roman_sem . ' SEM - ' . $explosd_short_course . ' (SEC-' . $section . ')';
        $analysis = strtoupper('classwise result analysis');

        $assessment_title = strtoupper('internal assessment ') . ltrim(explode('/', explode('-', base64_decode($examname))[1])[0]);

        $query = LabExamAttendance::query()
            ->where('acyear', $ay)
            ->where('examename', base64_decode($examname))
            ->where('sem', $sem)
            ->where('course', $course)
            ->where('year', $year)
            ->where('section', $section);

        $response = $query->get();
        $response->course_title = $course_title;
        $response->class_title = $class_title;
        $response->analysis = $analysis;
        $response->assessment_title = $assessment_title;
        $response->exam_name = '';

        if ($response->isNotEmpty()) {
            // Fetch CO marks from ExamTimetableCreation using eager loading
            $exams = LabFirstmodel::whereIn('id', $response->pluck('lab_exam_id'))->select('exam_name')->first();
            $response->exam_name = $exams-> exam_name;
            foreach ($response as $responses) {
                // Find the corresponding ExamTimetableCreation

                // Calculate subjectTotal
                $subjectTotal = 0;

                $responses->subjectTotal = 100;

                // Fetch related student data using eager loading
                $student_data = LabExamAttendanceData::where('lab_exam_name', $responses->id)->get();
                if (!$student_data->isEmpty() && $student_data[0]->subject == $responses->subject) {
                    $responses->newArray = $student_data;
                }

                // Fetch subject name
                $findSubject = Subject::find($responses->subject);
                if ($findSubject) {
                    $responses->subjectName = $findSubject->name . '(' . $findSubject->subject_code . ')';
                }

                $student_data = LabExamAttendanceData::select('class_id')->where('lab_exam_name', $responses->id)->first();
                $stu = CourseEnrollMaster::find($student_data->class_id);
                if ($stu) {
                    $responses->className = $this->getClassName($student_data->class_id);
                }
            }
        }
        $student1 = Student::get();
        $pdf = PDF::loadView('admin.labResultAnalysis.Exam_Class_wise_report', ['response' => $response, 'student1' => $student1]);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Exam_Class_wise_report.pdf');
    }


    public function Abstractget(Request $request)
    {

        $departments = ToolsDepartment::pluck('name', 'id')->prepend('Select Department', '');
        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $AcademicYear = AcademicYear::pluck('name', 'id');
        $examNames = LabFirstmodel::select('exam_name')->distinct()->get();
        $uniqueExamNames = [];

        if ($examNames) {
            foreach ($examNames as $examName) {
                $catName = explode('/', $examName->exam_name);
                $examNameValue = $catName[0];

                // Check if the exam name is not already in the array
                if (!in_array($examNameValue, $uniqueExamNames)) {
                    $uniqueExamNames[] = $examNameValue;
                }
            }
        }

        $course_name = ToolsCourse::where('id', $request->course)->select('name')->first();
        $parts = explode('.', $course_name->name);
        $lastPart = trim(end($parts));
        $explod_course_name = strtoupper($lastPart);

        //Assement Name
        $parts2 = explode(' ', $request->examename);
        $lastPart2 = trim(end($parts2));
        $assessment_title = strtoupper('INTERNAL ASSESSMENT TEST - ' . $lastPart2 . ' pass%');


        $LabFirstmodel = LabFirstmodel::query()
            ->select('id', 'section')
            ->where('accademicYear', $request->AcademicYear)
            ->where('exam_name', 'LIKE', $request->examename . '%')
            ->where('course_id', $request->course)
            ->get();

        $sectionPassFailCounts = [];
        $totalStudentCount = 0;
        $result = [];


        if (count($LabFirstmodel) > 0) {
            foreach ($LabFirstmodel as $LabFirstmodels) {

                $status = LabExamAttendance::select('subject', 'id', 'sem')
                    ->where('lab_exam_id', $LabFirstmodels->id)
                    ->where('section', $LabFirstmodels->section)
                    ->where('status', 2)
                    ->get();
                $status_count = count($status);

                $ExamAttendances = LabExamAttendance::select('subject', 'id', 'sem')
                    ->where('lab_exam_id', $LabFirstmodels->id)
                    ->where('section', $LabFirstmodels->section)
                    ->get();


                $subject_count = count($ExamAttendances);
                if ($status_count  ==  $subject_count) {

                    $studentAttendanceCounts = [];
                    $totalAbsentCount = 0;
                    $totalPresentCount = 0;
                    $studentsWithExactlyOneAbsent = 0;
                    $studentsWithOverallAbsent = 0;
                    $studentsWithOverallPresent = 0;
                    $studentsWithOverallPass = 0;
                    $studentsWithOneFail = 0;
                    $studentSubjectFail = [];
                    $studentOverallSubjectPass = [];
                    $uniqueStudentsWithOneFail = [];


                    $total_pass = 0;
                    $total_fail = 0;
                    $all_fail = 0;
                    $all_pass = 0;
                    foreach ($ExamAttendances as $ExamAttendance) {
                        $totalStudentCount = 0;
                        $roman = app(ExamTimetableCreationController::class);
                        $semester = $roman->toRoman($ExamAttendance->sem);

                        $examattendance_datas2 = LabExamAttendanceData::where('lab_exam_name', $ExamAttendance->id)
                            ->where('subject', $ExamAttendance->subject)
                            ->select('student_id', 'attendance','cycle_mark')
                            ->groupBy('student_id', 'attendance','cycle_mark')
                            ->get();

                        foreach ($examattendance_datas2 as $examattendance_data) {
                            $student_id = $examattendance_data->student_id;
                            $studentIDs[] = $student_id;
                            // Initialize student's attendance counts if not exists
                            if (!isset($studentAttendanceCounts[$student_id])) {
                                $studentAttendanceCounts[$student_id] = [
                                    'Present' => 0,
                                    'Absent' => 0,
                                    'overallAbsent' => 0,
                                    'overallPresent' => 0,
                                    'student_id' => [$student_id],
                                    'pass' => 0,
                                ];
                            }

                            if ($examattendance_data->cycle_mark < 100/2) {

                                if (!isset($uniqueStudentsWithOneFail[$student_id])) {
                                    $uniqueStudentsWithOneFail[$student_id] = true; // Add the student to the unique array
                                }
                                $all_fail = count($uniqueStudentsWithOneFail);
                            }


                            if ($examattendance_data->cycle_mark >= 100 / 2) {
                                $studentAttendanceCounts[$student_id]['pass']++;
                            }

                            if ($subject_count == $studentAttendanceCounts[$student_id]['pass']) {
                                // Check for one subject fail
                                if (!isset($studentOverallSubjectPass[$student_id])) {
                                    $studentOverallSubjectPass[$student_id] = true; // Add the student to the unique array
                                }
                                $all_pass = count($studentOverallSubjectPass);
                            }

                            if ($examattendance_data->attendance == 'Present') {
                                $studentAttendanceCounts[$student_id]['Present']++;
                                $totalPresentCount++;
                            } else {
                                $studentAttendanceCounts[$student_id]['Absent']++;
                                $totalAbsentCount++;
                            }

                            if ($subject_count == $studentAttendanceCounts[$student_id]['Absent']) {
                                $studentsWithOverallAbsent++;
                            }
                            if ($subject_count == $studentAttendanceCounts[$student_id]['Present']) {
                                $studentsWithOverallPresent++;
                            }

                            // Check if the student has exactly one absent and one present entry for each subject
                            if ($studentAttendanceCounts[$student_id]['Absent'] == 1 && $studentAttendanceCounts[$student_id]['Present'] == 1) {
                                $studentsWithExactlyOneAbsent++;
                            }
                            if ($student_id) {

                                $totalStudentCount++;
                            }
                        }
                    }

                    // Merge pass and fail counts for each subject in this section
                    foreach ($studentAttendanceCounts as $student_id => $attendanceData) {
                        if (!isset($sectionPassFailCounts[$LabFirstmodels->id])) {
                            $sectionPassFailCounts[$LabFirstmodels->id] = [
                                'total_pass' => 0,
                                'total_fail' => 0,
                                'total_subject_count' => 0,
                                'total_absent_count' => 0,
                                'total_present_count' => 0,
                            ];
                        }
                    }

                    if ($all_pass > 0 || $all_fail > 0) {
                        $passPercentage = ($all_pass / ($all_pass + $all_fail)) * 100;
                        $failPercentage = ($all_fail / ($all_pass + $all_fail)) * 100;
                    } else {
                        $passPercentage = 0;
                        $failPercentage = 0;
                    }

                    $sectionPassFailCounts[$LabFirstmodels->id]['pass_percentage'] = $passPercentage;
                    $sectionPassFailCounts[$LabFirstmodels->id]['fail_percentage'] = $failPercentage;

                    $sectionData = [
                        "pass_percentage" => $passPercentage,
                        "fail_percentage" => $failPercentage,
                        "studentsWithOverallAbsent" => $studentsWithOverallAbsent,
                        "studentsWithOverallPresent" => $studentsWithOverallPresent,
                        "section" => $LabFirstmodels->section,
                        "semester" => $semester,
                        "total_student" => $totalStudentCount,
                        "total_present" => $totalStudentCount - $studentsWithOverallAbsent,
                        "studentsWithOverallPass" => count($studentOverallSubjectPass),
                        "studentsWithOneFail" => count($uniqueStudentsWithOneFail),
                    ];

                    $result[$LabFirstmodels->id] = $sectionData;
                    $sectionPassFailCount = [];
                    foreach ($result as $id => $value) {
                        $sem = $value['semester'];
                        if (!isset($sectionPassFailCount[$sem])) {
                            $sectionPassFailCount[$sem] = [
                                'semester_count' => 0,
                                'pass_percentage' => 0,
                                'fail_percentage' => 0,
                                'total_present' => 0,
                                'total_absent' => 0,
                                'total_student' => 0,
                                'total_pass' => 0,
                                'total_fail' => 0,
                            ];
                        }

                        $sectionPassFailCount[$sem]['pass_percentage'] += $value['pass_percentage'];
                        $sectionPassFailCount[$sem]['fail_percentage'] += $value['fail_percentage'];
                        $sectionPassFailCount[$sem]['total_present'] += $value['total_present'];
                        $sectionPassFailCount[$sem]['total_absent'] += $value['studentsWithOverallAbsent'];
                        $sectionPassFailCount[$sem]['total_student'] += $value['total_student'];
                        $sectionPassFailCount[$sem]['total_pass'] += $value['studentsWithOverallPass'];
                        $sectionPassFailCount[$sem]['total_fail'] += $value['studentsWithOneFail'];
                        $sectionPassFailCount[$sem]['semester_count']++; // Increment the semester count
                        $sectionPassFailCount[$sem]['semester'] = $value['semester']; // Increment the semester count
                    }

                    foreach ($sectionPassFailCount as $sem => &$data) {
                        if ($data['semester_count'] > 0) {
                            $data['pass_percentage'] /= $data['semester_count'];
                            $data['fail_percentage'] /= $data['semester_count'];
                        }
                    }
                } else {
                    $result = [];
                    $sectionPassFailCount = [];
                }
            }

            if (count($result) > 0) {
                $countUniqueStudentsWithOneFail = count($uniqueStudentsWithOneFail);
            }
            $classPassFail_list = $result;
            $result['course_name'] = 'DEPARTMENT OF ' . $explod_course_name;
            $result['course_name_title'] = strtoupper($course_name->name);
            $result['assessment_title'] = strtoupper($assessment_title);

            // dd($sectionPassFailCount, $classPassFail_list);
        } else {
            $classPassFail_list = [];
            $sectionPassFailCount = [];
        }

        return view('admin.labResultAnalysis.Abstract', compact('departments', 'AcademicYear', 'courses', 'semester', 'uniqueExamNames', 'sectionPassFailCount', 'classPassFail_list'));
    }

     function  subject_get2(Request $request)
    {

        $academicYear_id = $request->academicYear_id;
        $course_id = $request->course_id;
        $semester_id = $request->semester_id;
        $section = $request->section;


        if ($request->course_id != '' || $request->semester != '' || $request->accademicYear != '') {

            $get_section = [];
            $check_input = is_numeric($request->course_id);

            if ($check_input) {
                $get_section = Section::where(['course_id' => $request->course_id])->select('id', 'section')->get();
            } else {

                $get_course = ToolsCourse::where(['name' => $request->course_id])->first();
                if ($get_course != '') {
                    $get_section = Section::where(['course_id' => $get_course->id])->get();
                }
            }




            $get_subjects = [];
            $get_subjects = SubjectAllotment::where(['semester' => $request->semester, 'course' => $request->course_id, 'academic_year' => $request->accademicYear, 'semester_type' => $request->semesterType])->get();

            $subjects = [];
            $got_subject = [];
            if (count($get_subjects) > 0) {
                foreach ($get_subjects as $subject) {
                    $got_subject = Subject::where(['id' => $subject->subject_id])->first();
                    if ($got_subject != '') {
                        array_push($subjects, $got_subject);
                    }
                }
                // return response()->json(['subjects' => $subjects]);
            }

            if ($academicYear_id  != '' && $course_id != '' &&   $semester_id != '' && $section != '') {


                $exam_name = LabFirstmodel::where(['accademicYear' => $academicYear_id, 'course_id' => $course_id, 'semester' => $semester_id, 'section' => $section])->select('exam_name')->get();
                if (count($exam_name) > 0) {

                    return response()->json(['exam_name' => $exam_name, 'status' => 'success']);
                } else {
                    return response()->json(['status' => 'exam_Name']);
                }
            }

            if ($get_section != '' && $subjects != '') {
                return response()->json(['subjects' => $subjects, 'get_section' => $get_section]);
            } else if ($subjects != '') {

                return response()->json(['subjects' => $subjects]);
            } else if ($get_section != '') {
                return response()->json(['get_section' => $get_section]);
            } else {

                return response()->json(['status' => 'fail']);
            }
        } else {
            return response()->json(['status' => 'fail']);
        }
    }

    // function  subject_get(Request $request)
    // {


    //     $academicYear_id = $request->academicYear_id;
    //     $course_id = $request->course_id;
    //     $semester_id = $request->semester_id;
    //     $section = $request->section;


    //     if ($request->course_id != '' || $request->semester != '' || $request->accademicYear != '') {

    //         $get_section = [];
    //         $check_input = is_numeric($request->course_id);

    //         if ($check_input) {
    //             $get_section = Section::where(['course_id' => $request->course_id])->select('id', 'section')->get();
    //         } else {

    //             $get_course = ToolsCourse::where(['name' => $request->course_id])->first();
    //             if ($get_course != '') {
    //                 $get_section = Section::where(['course_id' => $get_course->id])->get();
    //             }
    //         }




    //         $get_subjects = [];
    //         $get_subjects = SubjectAllotment::where(['semester' => $request->semester, 'course' => $request->course_id, 'academic_year' => $request->accademicYear, 'semester_type' => $request->semesterType])->get();

    //         $subjects = [];
    //         $got_subject = [];
    //         if (count($get_subjects) > 0) {
    //             foreach ($get_subjects as $subject) {
    //                 $got_subject = Subject::where(['id' => $subject->subject_id])->first();
    //                 if ($got_subject != '') {
    //                     array_push($subjects, $got_subject);
    //                 }
    //             }
    //             // return response()->json(['subjects' => $subjects]);
    //         }

    //         if ($academicYear_id  != '' && $course_id != '' &&   $semester_id != '' && $section != '') {


    //             $exam_name = LabFirstmodel::where(['accademicYear' => $academicYear_id, 'course_id' => $course_id, 'semester' => $semester_id, 'section' => $section])->select('exam_name')->get();
    //             if (count($exam_name) > 0) {

    //                 return response()->json(['exam_name' => $exam_name, 'status' => 'success']);
    //             } else {
    //                 return response()->json(['status' => 'exam_Name']);
    //             }
    //         }

    //         if ($get_section != '' && $subjects != '') {
    //             return response()->json(['subjects' => $subjects, 'get_section' => $get_section]);
    //         } else if ($subjects != '') {

    //             return response()->json(['subjects' => $subjects]);
    //         } else if ($get_section != '') {
    //             return response()->json(['get_section' => $get_section]);
    //         } else {

    //             return response()->json(['status' => 'fail']);
    //         }
    //     } else {
    //         return response()->json(['status' => 'fail']);
    //     }
    // }
}
