<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Models\User;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\ClassRoom;
use App\Models\ToolsCourse;
use App\Models\AcademicYear;
use App\Models\CollegeBlock;
use Illuminate\Http\Request;
use App\Models\TeachingStaff;
use App\Models\AssignmentData;
use App\Models\AssignmentModel;
use App\Models\ToolsDepartment;
use App\Models\NonTeachingStaff;
use App\Models\SubjectAllotment;
use App\Models\ClassTimeTableTwo;
use App\Models\CourseEnrollMaster;
use Illuminate\Support\Facades\DB;
use App\Models\ExamCellCoordinator;
use App\Http\Controllers\Controller;
use App\Models\AssignmentAttendances;
use App\Models\ExamTimetableCreation;
use App\Models\StudentPromotionHistory;
use Yajra\DataTables\Facades\DataTables;

class AssignmentMarkMasterController extends Controller
{
    public function is_serialized($data)
    {
        $data = trim($data);
        if ('N;' === $data) {
            return true;
        }
        if (!preg_match('/^([adObis]):/', $data, $badions)) {
            return false;
        }
        switch ($badions[1]) {
            case 'a':
            case 'O':
            case 's':
                if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data)) {
                    return true;
                }
                break;
            case 'b':
            case 'i':
            case 'd':
                if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data)) {
                    return true;
                }
                break;
        }
        return false;
    }
    public function index(Request $request)
    {
        // dd($request);
        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $Subjects = Subject::get();
        $AcademicYear = AcademicYear::pluck('name', 'id');
        $examNames = AssignmentModel::select('exam_name')->distinct()->get();
        $section = Section::select('section')->distinct()->get();
        // $exameMark = AssignmentAttendances::where('att_entered', 'Yes')->orderBy('status', 'asc')->get();
        $assignmentAttendance = AssignmentAttendances::orderByRaw("FIELD(status, 0,3,4,1, 2)")
            ->get();
        if ($assignmentAttendance->isNotEmpty()) {
            foreach ($assignmentAttendance as $record) {
                $record->totalstudent = '';
                $record->staffName = '';
                // dd(auth()->user()->roles[0]->id);
                // Check if the user is authorized to toggle
                $authorizedToToggle = auth()->user()->roles[0]->id == 40 || auth()->user()->roles[0]->id == 1;

                // Toggle HTML
                $record->toggle = $authorizedToToggle ? '<div class="toggle text-center" onclick="checkFluency(this)">
                    <input type="checkbox" data-id="' . $record->id . '" class="toggleData"  ' . ($record->mark_entry == "0" ? '' : 'checked') . '/>
                    <label></label>
                </div>' : '';

                if($record->status == 4 || $record->status == 2){
                    $enteredBy = TeachingStaff::where('user_name_id', $record->mark_enter_by)->first() ?? NonTeachingStaff::where('user_name_id', $record->mark_enter_by)->first() ?? User::find($record->mark_enter_by);
                    $record->markStaff = $enteredBy ? ($enteredBy->StaffCode ?? $enteredBy->name) : '';

                }else{

                    $enteredBy = '';
                    $record->markStaff =  '';
                    $record->mark_date  =  '';
                }


                $courseFound = ToolsCourse::find($record->course);
                $accYearFound = AcademicYear::find($record->academic_year);
                $semesterFound = Semester::find($record->semester);

                $record->classDetails = '';
                if ($courseFound && $accYearFound && $semesterFound) {
                    $record->classDetails = $courseFound->short_form . '/' . $semesterFound->semester . '/' . $record->section;
                }



                $string = '';
                if ($courseFound && $accYearFound && $semesterFound) {
                    $string = '/' . $courseFound->name . '/' . $accYearFound->name . '/' . $semesterFound->semester . '/' . $record->section;
                }

                $stu = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%' . $string)->first();
                if($stu != ''){

                    $getStu = Student::where('enroll_master_id', $stu->id)->count();
                    if ($getStu <= 0) {
                        $getStu = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $stu->id)->count();
                    }
                    $record->totalstudent = $getStu;
                }else{
                    $record->totalstudent = '';
                }



                $staffId = null;
                if ($stu != null) {
                    $staff = ClassTimeTableTwo::where([
                        'class_name' => $stu->id,
                        'subject' => $record->subject ?? '',
                        'status' => '1',
                    ])->first();
                    if ($staff) {
                        $staffId = $staff->staff;
                        $staff1 = TeachingStaff::where('user_name_id', $staffId)->first();
                        $record->staffName = $staff1 ? $staff1->name . '(' . $staff1->StaffCode . ')' : '';
                    }
                }

                $viewButton = '<a class="btn btn-xs btn-primary" href="' . route('admin.assignment_Exam_Mark.markview', [$stu->id, $record->id]) . '" target="_blank">View</a>';
                $buttons = '';

                if ($record->mark_enter_by != null) {
                    $buttons .= $viewButton;
                } else {
                    $buttons .= '<a class="btn btn-xs btn-info" href="' . route('admin.assignment_Exam_Mark.markEnter', [$stu->id, $record->id]) . '" target="_blank">Enter</a>';
                }

                $role_id = auth()->user()->roles[0]->id;
                if ($role_id == 40 || $role_id == 1) {
                    if ($record->mark_enter_by != null) {
                        $buttons .= ' <a class="btn btn-xs btn-danger" href="' . route('admin.assignment_Exam_Mark.editMark', [$stu->id, $record->id]) . '" target="_blank">Edit</a>';
                    }
                }

                $record->actions = $buttons;

                if ($record->subject != '') {
                    $subject = Subject::find($record->subject);
                    $record->subject = $subject ? $subject->name . '('. $subject->subject_code .')' : '';
                    // $record->subject_code = $subject ? $subject->subject_code ?? '' : '';
                }

                // if ($record->exam_name != '' && $record->year != '' && $record->semester != '') {
                //     $record->exam_name = $record->exam_name . '/' . $this->toRoman($record->year) . '/0' . $record->semester;
                // }
            }
        }

        return view('admin.assignmentExamMark.index', compact('courses', 'semester', 'Subjects', 'AcademicYear', 'examNames', 'assignmentAttendance', 'section'));
    }

    public function markview($classId, $id)
    {
        $exameData = '';
        $examDate = '';
        $examSubject = '';
        $classname = '';
        $examName = '';
        $examCellCo = '';


        $datas = ExamCellCoordinator::get();
        // dd($datas);
        if ($datas) {
            foreach ($datas as $data) {
                if (auth()->user()->id == $data->user_name_id) {
                    $examCellCo = 'yes';
                    break;
                } else {
                    $examCellCo = 'no';
                }
            }
        }
        if (isset($classId, $id)) {

            $secondTable = AssignmentAttendances::find($id);

            if ($secondTable) {

                $a = Subject::find($secondTable->subject);
                if ($a) {
                    $examSubject = $a->name . '(' . $a->subject_code . ')';
                }

                $status = $secondTable->status;

                $examName = $secondTable->exam_name;

                $className = CourseEnrollMaster::find($classId);
                if ($className != '') {
                    $newArray = explode('/', $className->enroll_master_number);
                    $get_course = ToolsCourse::where(['name' => $newArray[1]])->first();
                    if ($get_course) {
                        $classname = $get_course->short_form . '/' . $newArray[3] . '/' . $newArray[4];
                    }

                } else {
                    $classname = '';
                }

                $exameData = AssignmentData::where([
                    'class_id' => $classId,
                    'assignment_name_id' => $id
                ])->get();
                $total_students = $exameData ->count();

                $classId = '';
                $subjectId = '';
                $examId = '';
                if ($exameData) {
                    $assignmentMarks = 50;
                    foreach ($exameData as $exameDatas) {
                        $exameDatas->student_id;
                        $classId = $exameDatas->class_id;
                        $subjectId = $exameDatas->subject;
                        $examId = $exameDatas->assignment_name_id;
                        $student = Student::where('user_name_id', $exameDatas->student_id)->first();

                        if ($student != '') {
                            $exameDatas->studentName = $student->name;
                            $exameDatas->studentReg = $student->register_no;
                        }

                        // if ($coMarks / 2 <= $exameDatas->cycle_mark) {
                        //     if ($exameDatas->cycle_mark != 999) {
                        //         $pass++;
                        //     } else {
                        //         $fail++;
                        //     }
                        // } else {
                        //     $fail++;
                        // }
                    }

                    // $exameData->pass = $pass;
                    // $exameData->fail = $fail;
                    // if ($pass > 0) {
                    //     $passPercentage = number_format((($pass / ($pass + $fail)) * $coMarks), 2);
                    // } else {
                    //     $passPercentage = number_format(0, 2);
                    // }
                    // $exameData->passPercentage = $passPercentage;

                }
            }
        }
        return view('admin.assignmentExamMark.markview', compact('examId', 'total_students', 'subjectId', 'classId', 'exameData', 'assignmentMarks',  'examSubject', 'classname', 'examName', 'status', 'examCellCo'));
    }
    public function MarkEnter($classId, $id)
    {

        $exameData = '';

        $examDate = '';
        $examSubject = '';
        $classname = '';
        $examName = '';
        $totalPres = '';
        $totalAbs = '';
        if (isset($classId, $id)) {

            $secondTable = AssignmentAttendances::find($id);
            if ($secondTable != '' && $secondTable->status == 0) {
                if ($secondTable) {

                    $a = Subject::find($secondTable->subject);
                    if ($a) {
                        $examSubject = $a->name . '(' . $a->subject_code . ')';
                    }


                    $examName = $secondTable->exam_name;
                    $assignmentMark = $secondTable->assignment_mark;

                    $className = CourseEnrollMaster::find($classId);
                    if ($className != '') {
                        $newArray = explode('/', $className->enroll_master_number);
                        $get_course = ToolsCourse::where(['name' => $newArray[1]])->first();
                        if ($get_course) {
                            $classname = $get_course->short_form . '/' . $newArray[3] . '/' . $newArray[4];
                        }
                        // $class_name=$newArray[1].'/'.$newArray[3].'/'.$newArray[4];

                    } else {
                        $classname = '';
                    }

                    $firstTable = AssignmentModel::find($secondTable->assignment_id);

                    // if ($firstTable) {

                    //     $firstTable->co;
                    //     if ($this->is_serialized($firstTable->co)) {
                    //         $coMark = unserialize($firstTable->co);

                    //         // Check if $dummy is an array or object
                    //         if (is_array($coMark) || is_object($coMark)) {
                    //             //  dd($coMark);
                    //             $coMarks = $coMark;
                    //             $data = 0;

                    //             foreach ($coMarks as $index => $coMark) {
                    //                 $data += $coMark;
                    //                 $coMarks['count'] = $data;
                    //             }
                    //         }
                    //     }
                    // }
                }
                $exameData = AssignmentData::where([
                    'class_id' => $classId,
                    'assignment_name_id' => $id
                ])->get();
                // dd($exameData);
                $classId = '';
                $subjectId = '';
                $examId = '';
                if ($exameData) {
                    $student_mark = [];
                    foreach ($exameData as $exameDatas) {
                        $exameDatas->student_id;
                        $classId = $exameDatas->class_id;
                        $subjectId = $exameDatas->subject;
                        $examId = $exameDatas->assignment_name_id;
                        $student = Student::where('user_name_id', $exameDatas->student_id)->first();


                        if ($student != '') {
                            $exameDatas->studentName = $student->name;
                            $exameDatas->studentReg = $student->register_no;
                        }
                    }
                }
            } else {
                return to_route('admin.assignment_Exam_Mark.markview', [$classId, $id]);
            }
        }

        //  dd($exameData,$examId,$classId,$totalAbs,$totalPres,$exameData,$coMarks, $examDate,$examSubject, $classname,$examName);
        return view('admin.assignmentExamMark.markEnter', compact('examId', 'subjectId', 'classId', 'totalAbs', 'totalPres', 'exameData', 'assignmentMark', 'examSubject', 'classname', 'examName'));
    }

    public function MarkStore(Request $request)
    {
        if ($request) {
            $getMark = AssignmentData::where([
                'class_id' => $request->class_name,
                'assignment_name_id' => $request->exam_name,
                'subject' => $request->subject,
            ])->get();
            $newArray = [];

            $originalArray = [];

            for ($i = 1; $i <= 5; $i++) {
                $key = "assignment_mark_" . $i;
                if (isset($request->$key)) {
                    $originalArray[$key] = $request->$key;
                }
            }
            foreach ($originalArray as $coKey => $coValues) {
                foreach ($coValues as $subKey => $subValue) {
                    $newArray[$subKey][$coKey] = $subValue;
                }
            }

            if ($getMark) {

                foreach ($newArray as $stuId => $getMarks) {
                    // Find the individual model by student_id
                    $studentModel = AssignmentData::where([ 'student_id'=> $stuId,'assignment_name_id'=> $request->exam_name])->first();
                    if ($studentModel) {

                            $updateData = [];
                            for ($i = 1; $i <= 5; $i++) {
                                $key = "assignment_mark_". $i;
                                if (isset($request->$key)) {
                                    $updateData[$key] = $request->$key;
                                }
                            }

                            // Assuming $getMarks is an array with CO_1, CO_2, CO_3, etc.
                            foreach ($getMarks as $coKey => $coValue) {
                                $updateData[$coKey] = $coValue ?? null;
                            }
                            // Update the model with the retrieved instance
                            $studentModel->update($updateData);
                    }
                }

                $secondTable = AssignmentAttendances::find($request->exam_name);
                if($secondTable){

                    $mark_entry_by = $secondTable->mark_enter_by;
                }else{
                    $mark_entry_by = '';

                }
                if ($secondTable) {

                    if ($secondTable->mark_enter_by == null) {
                        $secondTable->mark_enter_by = auth()->user()->id;
                        $secondTable->status = 3;
                    } elseif ($secondTable->mark_enter_by != null && !isset($request->publish)) {
                        $secondTable->update_by = auth()->user()->id;
                        $secondTable->status = 3;
                    }

                    $secondTable->mark_date = now();
                    if (isset($request->publish)) {
                        $secondTable->status = '2';
                        $secondTable->publish_by = auth()->user()->id;
                    }else if ($request->final_submit){
                        $secondTable->status = '4';
                        $secondTable->final_submit_by = auth()->user()->id;

                    }
                    $secondTable->save();
                }
            }
        }
        if (isset($request->publish)) {
            $message = 'Mark Published successfully';
        } elseif ($mark_entry_by != '') {
            $message = 'Mark Updated successfully';
        } elseif ($mark_entry_by == '') {
            $message = 'Mark Entered successfully';
        } else {
            $message = '';
        }
        return to_route('admin.assignment_Exam_Mark.markview', [$request->class_name, $request->exam_name])->with('message', $message);
    }

    public function verifiedStatus(Request $request)
    {
        $examId = $request->input('exameId');
        if (isset($examId) && !empty($examId)) {
            $data = AssignmentAttendances::find($examId);
            if ($data) {
                $data->update(['status' => '1']);
                return response()->json(['data' => 200]);
            } else {
                return response()->json(['data' => 400]);
            }
        } else {
            return response()->json(['data' => 400]);
        }
    }
    public function editMark($classId, $id)
    {
        $exameData = '';
        $examDate = '';
        $status = '';
        $examSubject = '';
        $classname = '';
        $examName = '';

        $assignmentMarks = 50;
        $role_id = auth()->user()->id;

        if (isset($classId, $id)) {
            $secondTable = AssignmentAttendances::find($id);
            $status = false;

            if ($role_id == 40 || $role_id == 1) {
                $status = true;
            } else if ($secondTable->status != 2 || $secondTable->status != 1) {
                $status = true;
            }

            if ($secondTable != '' && $status) {


                $secondTable = AssignmentAttendances::find($id);
                $subject_get = Subject::find($secondTable->subject);
                if ($subject_get) {
                    $examSubject = $subject_get->name . '(' . $subject_get->subject_code . ')';
                }

                $status = $secondTable->status;
                $examName = $secondTable->exam_name;
                $className = CourseEnrollMaster::find($classId);
                if ($className != '') {
                    $newArray = explode('/', $className->enroll_master_number);
                    $get_course = ToolsCourse::where(['name' => $newArray[1]])->first();
                    if ($get_course) {
                        $classname = $get_course->short_form . '/' . $newArray[3] . '/' . $newArray[4];
                    }

                } else {
                    $classname = '';
                }

                $firstTable = AssignmentModel::find($secondTable->assignment_id);
                $exameData = AssignmentData::where([
                    'class_id' => $classId,
                    'assignment_name_id' => $id
                ])->get();
                $classId = '';
                $subjectId = '';
                $examId = '';
                if ($exameData) {
                    foreach ($exameData as $exameDatas) {
                        $exameDatas->student_id;
                        $classId = $exameDatas->class_id;
                        $subjectId = $exameDatas->subject;
                        $examId = $exameDatas->assignment_name_id;
                        $student = Student::where('user_name_id', $exameDatas->student_id)->first();
                        if ($student != '') {
                            $exameDatas->studentName = $student->name;
                            $exameDatas->studentReg = $student->register_no;
                            // $exameDatas->cycle_mark = $student->cycle_mark;
                        }

                    }
                }
            } else {
                return to_route('admin.assignment_Exam_Mark.markview', [$classId, $id]);
            }
        } else {
            return to_route('admin.assignment_Exam_Mark.markview', [$classId, $id]);
        }

        return view('admin.assignmentExamMark.editMark', compact('examId', 'subjectId', 'classId','exameData', 'assignmentMarks',  'examSubject', 'classname',  'status'));
    }


    public function toggle_status(Request $request)
    {
        $response = 400;

        if ($request->has('examID') && $request->has('update')) {
            $examID = $request->examID;
            $updateValue = $request->update;

            $examAttendance = AssignmentAttendances::find($examID);

            if ($examAttendance) {
                $examAttendance->mark_entry = $updateValue;
                $examAttendance->save();
                $response = 200;
            }
        }

        return response()->json(['data' => $response]);
    }

    public function find(Request $request)
    {


        $academicYear = $request->input('AcademicYear');
        $semesterType = $request->input('semester_type');
        $assignmentModel = AssignmentModel::where(['academic_year' => $academicYear, 'semester_type' => $semesterType])->select('id', 'course_id', 'year', 'semester', 'section', 'semester_type')->get();
        $lab_table_id = $assignmentModel->pluck('id');

        $course_id = $assignmentModel->pluck('course_id');
        $count = count($lab_table_id);
        if ($count > 0) {

            $semester = $assignmentModel->pluck('semester');
            //$course_get
            $course_get = $assignmentModel->groupBy('course_id')->map(function ($course_id) {
                $data = $course_id->pluck('course_id');
                $data[0] = ToolsCourse::where('id', $data[0])->select('name')->first()->name;
                return $data[0] ? $data[0] : null;
            });

            $year_get = $assignmentModel->groupBy('year')->map(function ($year) {
                $data = $year->pluck('year');
                if (  $data[0] == '01' ) {
                    $data[0] = 'I';
                } elseif ( $data[0] == 02 ) {
                    $data[0] = 'II';
                } elseif ( $data[0] == 03 ) {
                    $data[0] = 'III';
                } elseif ( $data[0] == 04) {
                    $data[0] = 'IV';
                }
                return $data[0] ? $data[0] : null;
            });

            $semester_get = $assignmentModel->groupBy('semester')->map(function ($semester) {
                $data = $semester->pluck('semester');
                return $data[0] ? $data[0] : null;
            });

            $sections_get = $assignmentModel->groupBy('section')->map(function ($section) {
                $data = $section->pluck('section');
                return $data[0] ? $data[0] : null;
            });
        }else{
            $course_get = [];
            $year_get  = [];
            $semester_get = [];
            $sections_get = [];
        }
        $query = AssignmentAttendances::query();


        if ($count > 0) {
            $query->WhereIn('assignment_id', $lab_table_id);
        }


        $query->orderByRaw("FIELD(status, 0,3,4,1, 2)");
        $response = $query->select('id','exam_name','course','academic_year','semester','section','mark_entry','mark_date','mark_enter_by','subject','status','year')->get();


        if ($response->isNotEmpty()) {
            foreach ($response as $record) {

                if ($record->course != null) {

                    $value = ToolsCourse::find($record->course);
                    $course_name = $value->name;
                    $course_shortname = $value->short_form;
                }
                if ($record->academic_year != null) {

                    $value = AcademicYear::find($record->academic_year);
                    $ac_Year_name = $value->name;
                }
                if ($record->semester != null) {

                    $value = Semester::find($record->semester);
                    $semester_id = $value->semester;
                }

                $sectionFound = [];
                if ($record->section != '') {

                    $value = Section::where('section', $record->section)->first();
                    $section_name = $value->section;
                }

                $record->totalstudent = '';
                $record->staffName = '';
                if (auth()->user()->roles[0]->id == 40 || auth()->user()->roles[0]->id == 1) {
                    $record->toggle = '<div class="toggle text-center" onclick="checkFluency(this)">
                    <input type="checkbox" data-id="' . $record->id . '" class="toggleData"  ' . ($record->mark_entry == "0" ? '' : 'checked') . '/>
                    <label></label>
                </div>';
                }else{
                    $record->toggle = '';
                }

                $record->attenteredBY = '';
                $record->markStaff = '';

                $record->classDetails = '';

                if ($course_name && $ac_Year_name && $semester_id) {

                    $record->classDetails = $course_shortname . '/' . $semester_id . '/' . $section_name;

                    $string = '/' . $course_name . '/' . $ac_Year_name . '/' . $semester_id . '/' . $section_name;
                } else {
                    $string = '';
                }
                $stu = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%' . $string)->first();


                $staffId = null;
                if ($stu != null) {
                    $students = Student::where('enroll_master_id', $stu->id)->count();
                    if ($students <= 0) {
                        $students = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $stu->id)->count();
                    }
                    if ($students) {
                        $record->totalstudent = $students;
                    }

                    if ($record->mark_date) {
                        if($record->status == 2 || $record->status == 4 ||  $record->status == 1  ){
                            $record->mark_date = date('d-m-Y', strtotime($record->mark_date));
                        }else{
                            $record->mark_date = null;

                        }
                    }

                    $enteredBy = TeachingStaff::where('user_name_id', $record->mark_enter_by)->first();
                    $enteredBy2 = null;

                    if($record->status == 4 || $record->status == 2 || $record->status  == 1 ){

                    if ($enteredBy === null) {
                        $enteredBy2 = NonTeachingStaff::where('user_name_id', $record->mark_enter_by)->first();
                    }

                        if ($enteredBy !== null) {
                            $record->markStaff = $enteredBy->StaffCode;
                        } elseif ($enteredBy2 !== null) {
                            $record->markStaff = $enteredBy2->StaffCode;
                        } else {
                            $User = User::find($record->mark_enter_by);
                            if ($User) {
                                $record->markStaff = $User->name;
                            }
                        }
                        }else{
                            $record->markStaff =  '' ;
                }
                    $staff = ClassTimeTableTwo::where([
                        'class_name' => $stu->id,
                        'subject' => $record->subject ?? '',
                        'status' => '1',
                    ])->first();
                    if ($staff) {
                        $staffId = $staff->staff;
                        $staff1 = TeachingStaff::where('user_name_id', $staffId)->first();

                        if ($staff1) {
                            $record->staffName = $staff1->name . '(' . $staff1->StaffCode . ')';
                        }
                    }
                };

                // $User1 = TeachingStaff::where('user_name_id', $record->entered_by)->first();
                // $User2 = null;

                // if ($User1 === null) {
                //     $User2 = NonTeachingStaff::where('user_name_id', $record->entered_by)->first();
                // }

                // if ($User1 !== null) {
                //     $record->attenteredBY = $User1->StaffCode;
                // } elseif ($User2 !== null) {
                //     $record->attenteredBY = $User2->StaffCode;
                // } else {
                //     $User = User::find($record->entered_by);
                //     if ($User) {
                //         $record->attenteredBY = $User->name;
                //     }
                // }

                // if($record->status != 4 || $record->status != 2 ){
                //     $record->attenteredBY = '';
                //     $record->status =  0 ;
                // }

                if($record->status == 4 || $record->status == 2 || $record->status  == 1 ){
                    $enteredBy = TeachingStaff::where('user_name_id', $record->mark_enter_by)->first() ?? NonTeachingStaff::where('user_name_id', $record->mark_enter_by)->first() ?? User::find($record->mark_enter_by);
                    $record->attenteredBY = $enteredBy ? ($enteredBy->StaffCode ?? $enteredBy->name) : '';

                }else{

                    $enteredBy = '';
                    $record->attenteredBY =  '';
                    $record->mark_date  =  '';
                }


                $viewButton = '<a class="btn btn-xs btn-primary" href="' . route('admin.assignment_Exam_Mark.markview', [$stu->id, $record->id]) . '" target="_blank">View</a>';
                $viewButtonShown = false; // Variable to track if the "View" button is shown

                $buttons = '';

                if ($record->mark_enter_by != null) {
                    $buttons .= $viewButton;
                    $viewButtonShown = true;
                } else {
                    $buttons .= '<a class="btn btn-xs btn-info" href="' . route('admin.assignment_Exam_Mark.markEnter', [$stu->id, $record->id]) . '" target="_blank">Enter</a>';
                }
                $role_id = auth()->user()->roles[0]->id;
                if ($role_id == 40 || $role_id == 1) {
                    if ($record->mark_enter_by != null) {
                        $buttons .= ' <a class="btn btn-xs btn-danger" href="' . route('admin.assignment_Exam_Mark.editMark', [$stu->id, $record->id]) . '" target="_blank">Edit</a>';
                    }
                }

                $record->actions = $buttons;


                if ($record->subject != '') {
                    $subject = Subject::find($record->subject);
                    if ($subject) {
                        $record->subject = $subject->name. '('. $subject->subject_code. ')' ?? '';
                        // $record->subject_code = $subject->subject_code ?? '';
                    } else {
                        $record->subject = '';
                    }
                }

                // if ($record->exam_name != '' && $record->year != '' && $record->semester != '') {
                //     // $Academicyear = AcademicYear::where('id', $request->input('accademicYear'))->select('name')->first();

                //     $record->exam_name = $record->exam_name . '/' . $this->toRoman($record->year) . '/0' . $record->semester;
                // }
            }
        }

        return response()->json(['data' => $response, 'course'=>$course_get, 'semester'=>$semester_get,'year'=>$year_get,'sections'=>$sections_get]);
    }
}
