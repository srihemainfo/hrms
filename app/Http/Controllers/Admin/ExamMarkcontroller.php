<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Models\AcademicYear;
use App\Models\cat_exam_edit_request;
use App\Models\ClassTimeTableTwo;
use App\Models\CourseEnrollMaster;
use App\Models\Examattendance;
use App\Models\ExamattendanceData;
use App\Models\ExamCellCoordinator;
use App\Models\ExamTimetableCreation;
use App\Models\NonTeachingStaff;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentPromotionHistory;
use App\Models\Subject;
use App\Models\TeachingStaff;
use App\Models\ToolsCourse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;

class ExamMarkcontroller extends Controller
{
    use CsvImportTrait;
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
        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $Subjects = Subject::get();
        $AcademicYear = AcademicYear::pluck('name', 'id');
        $getAys = AcademicYear::where(['status' => 1])->select('id')->get();
        $Ays = [];
        if (count($getAys) > 0) {
            foreach ($getAys as $ay) {
                array_push($Ays, $ay->id);
            }
        }
        $getSem = Semester::where(['status' => 1])->select('semester')->get();
        $Sems = [];
        if (count($getSem) > 0) {
            foreach ($getSem as $sem) {
                array_push($Sems, $sem->semester);
            }
        }
        $examNames = ExamTimetableCreation::select('exam_name')->distinct()->get();
        $currentClasses = Session::get('currentClasses');
        $exameMark = Examattendance::with('courseEnrollMaster:id,short_form,name', 'academicYear:id,name', 'semester:id,semester')->whereIn('acyear', $Ays)->whereIn('sem', $Sems)->where('att_entered', 'Yes')->get();
        $authorizedToToggle = auth()->user()->roles[0]->id == 40 || auth()->user()->roles[0]->id == 1;
        if ($exameMark->isNotEmpty()) {
            foreach ($exameMark as $record) {
                $record->totalstudent = '';
                $record->staffName = '';

                $record->toggle = $authorizedToToggle ? '<div class="toggle text-center" onclick="checkFluency(this)">
                    <input type="checkbox" data-id="' . $record->id . '" class="toggleData"  ' . ($record->mark_entry == "0" ? '' : 'checked') . '/>
                    <label></label>
                </div>' : '';

                $enteredBy = User::find($record->mark_entereby);
                $record->markStaff = $enteredBy ? ($enteredBy->employID ?? $enteredBy->name) : '';

                $courseFound = $record->courseEnrollMaster ? $record->courseEnrollMaster->short_form : '';
                $courseNameFound = $record->courseEnrollMaster ? $record->courseEnrollMaster->name : '';
                $accYearFound = $record->academicYear ? $record->academicYear->name : '';
                $semesterFound = $record->semester ? $record->semester->semester : '';

                if ($courseNameFound != '' && $accYearFound != '' && $semesterFound != '') {
                    $string = '/' . $courseNameFound . '/' . $accYearFound . '/' . $semesterFound . '/' . $record->section;
                } else {
                    $string = '';
                }
                $record->classDetails = $courseFound . '/' . $semesterFound . '/' . $record->section;

                if ($record->edit_request != '') {
                    $editRequest = cat_exam_edit_request::find($record->edit_request);
                    if ($editRequest) {
                        $markenteredBy = User::find($editRequest->exam_staff_id);
                        $record->edit_request = $markenteredBy ? ($markenteredBy->employID ?? $markenteredBy->name) . '(' . $editRequest->date . ')' : '';
                    }
                }

                $stu = CourseEnrollMaster::whereIn('id', $currentClasses)->where('enroll_master_number', 'LIKE', '%' . $string)->select('id')->first();

                if ($stu != '') {

                    $getStu = Student::where('enroll_master_id', $stu->id)->count();
                    if ($getStu <= 0) {
                        $getStu = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $stu->id)->count();
                    }
                    $record->totalstudent = $getStu;
                } else {
                    $record->totalstudent = '';
                }
                $staffId = null;
                if ($stu != null) {
                    $staff = ClassTimeTableTwo::whereIn('class_name', $currentClasses)->where([
                        'class_name' => $stu->id,
                        'subject' => $record->subject ?? '',
                        'status' => '1',
                    ])->first();
                    if ($staff) {
                        $staffId = $staff->staff;
                        $staff1 = User::find($staffId);
                        $record->staffName = $staff1 ? $staff1->name . '(' . $staff1->employID . ')' : '';
                    }
                }

                $user = User::find($record->entered_by);
                $record->attenteredBY = $user ? ($user->employID ?? $user->name) : '';

                $viewButton = '<a class="btn btn-xs btn-primary" href="' . route('admin.Exam-Mark.markview', [$stu->id, $record->id]) . '" target="_blank">View</a>';
                $buttons = '';

                if ($record->mark_entereby != null) {
                    $buttons .= $viewButton;
                } else {
                    $buttons .= '<a class="btn btn-xs btn-info" href="' . route('admin.Exam-Mark.markEnter', [$stu->id, $record->id]) . '" target="_blank">Enter</a>';
                }

                $role_id = auth()->user()->roles[0]->id;
                if ($role_id == 40 || $role_id == 1) {
                    if ($record->mark_entereby != null) {
                        $buttons .= ' <a class="btn btn-xs btn-danger" href="' . route('admin.Exam-Mark.editMark', [$stu->id, $record->id]) . '" target="_blank">Edit</a>';
                    }
                }

                $record->actions = $buttons;

                if ($record->subject != '') {
                    $subject = Subject::find($record->subject);
                    $record->subject = $subject ? $subject->name ?? '' : '';
                    $record->subject_code = $subject ? $subject->subject_code ?? '' : '';
                }

                if ($record->exam_name != '' && $record->year != '' && $record->semester != '') {
                    $record->exam_name = $record->exam_name . '/' . $this->toRoman($record->year) . '/0' . $record->semester;
                }
            }
        }
        return view('admin.examMark.index', compact('courses', 'semester', 'Subjects', 'AcademicYear', 'examNames', 'exameMark'));
    }

    // public function find(Request $request)
    // {
    //     // dd($request);
    //     $accademicYear = $request->input('ay');
    //     $course = $request->input('course');
    //     $semester = $request->input('semester');
    //     $year = $request->input('year');
    //     $section = $request->input('section');
    //     $examename = $request->input('examename');

    //     $response = Examattendance::select()
    //         ->where('acyear', $accademicYear)
    //         ->where('examename', $examename)
    //         ->where('sem', $semester)
    //         ->where('section', $section)
    //         ->where('course', $course)
    //         ->where('year', $year)
    //         ->where('att_entered', 'Yes')
    //         ->get();
    //     if ($response->isNotEmpty()) {
    //         foreach ($response as $record) {
    //             // dd();
    //             $record->totalstudent='';
    //             $record->staffName='';
    //             $record->toggle='<div class="toggle text-center" onclick="checkFluency(this)">
    //             <input type="checkbox" data-id="' . $record->id . '" class="toggleData"  ' . ($record->mark_entry == "0" ? '' : 'checked') . '/>
    //             <label></label>
    //         </div>';
    //             // $record->attenteredDate='';
    //             $record->attenteredBY='';
    //             $record->markStaff ='';
    //             $courseFound = ToolsCourse::find($course);
    //             $accYearFound = AcademicYear::find($accademicYear);
    //             $semesterFound = Semester::find($semester);

    //             if ($courseFound && $accYearFound && $semesterFound) {
    //                 $courseName = $courseFound->name;
    //                 $academicYearName = $accYearFound->name;
    //                 $semesterName = $semesterFound->semester;

    //                 $string = '/' . $courseName . '/' . $academicYearName . '/' . $semesterName . '/' . $section;
    //             } else {
    //                 $string = '';
    //             }
    //             if($record->edit_request !=''){
    //             $editRequest=cat_exam_edit_request::find($record->edit_request);
    //             if($editRequest){
    //                 $editRequest->exam_staff_id;

    //                 $markenteredBy = TeachingStaff::where('user_name_id', $editRequest->exam_staff_id)->first();
    //             $markenteredBy2 = null;

    //             if ($markenteredBy === null) {
    //                 $markenteredBy2 = NonTeachingStaff::where('user_name_id', $editRequest->exam_staff_id)->first();
    //             }

    //             if ($markenteredBy !== null) {
    //                 $record->edit_request = $markenteredBy->name.'('.$markenteredBy->StaffCode.') /'.$editRequest->date;
    //             } elseif ($markenteredBy2 !== null) {
    //                 $record->edit_request = $markenteredBy2->name.'('.$markenteredBy2->StaffCode.') /'.$editRequest->date;
    //             } else {
    //                 $User = User::find($editRequest->exam_staff_id);
    //                 if ($User) {
    //                     $record->edit_request = $User->name.'/'.$editRequest->date;
    //                 }

    //             }
    //             }

    //             }

    //             $stu = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%' . $string)->first();
    //             // dd($stu);
    //             $staffId=null;
    //             if($stu != null){
    //             $students=Student::where('enroll_master_id',$stu->id)->count();
    //             if($students){
    //             $record->totalstudent=$students;

    //             }
    //             $enteredBy = TeachingStaff::where('user_name_id', $record->mark_entereby)->first();
    //             $enteredBy2 = null;

    //             if ($enteredBy === null) {
    //                 $enteredBy2 = NonTeachingStaff::where('user_name_id', $record->mark_entereby)->first();
    //             }

    //             if ($enteredBy !== null) {
    //                 $record->markStaff = $enteredBy->StaffCode;
    //             } elseif ($enteredBy2 !== null) {
    //                 $record->markStaff = $enteredBy2->StaffCode;
    //             } else {
    //                 $User = User::find($record->mark_entereby);
    //                 if ($User) {
    //                     $record->markStaff = $User->name;
    //                 }

    //             }
    //             $staff=ClassTimeTableTwo::where([
    //                 'class_name' => $stu->id,
    //                 'subject' => $record->subject ?? '',
    //                 'status' => '1',
    //             ])->first();
    //             if($staff){
    //                 $staffId=$staff->staff;
    //                 $staff1 = TeachingStaff::where('user_name_id', $staffId)->first();

    //                 if($staff1){
    //                     $record->staffName=$staff1->name.'('.$staff1->StaffCode.')';
    //                 }

    //             }

    //             };
    //             $User1 = TeachingStaff::where('user_name_id', $record->entered_by)->first();
    //             $User2 = null;

    //             if ($User1 === null) {
    //                 $User2 = NonTeachingStaff::where('user_name_id', $record->entered_by)->first();
    //             }

    //             if ($User1 !== null) {
    //                 $record->attenteredBY = $User1->StaffCode;
    //             } elseif ($User2 !== null) {
    //                 $record->attenteredBY = $User2->StaffCode;
    //             } else {
    //                 $User = User::find($record->entered_by);
    //                 if ($User) {
    //                     $record->attenteredBY = $User->name;
    //                 }
    //             }

    //             $viewButton ='<a class="btn btn-xs btn-primary" href="' . route('admin.Exam-Mark.markview', [$stu->id,$record->id]) . '" target="_blank">View</a>';
    //             $viewButtonShown = false; // Variable to track if the "View" button is shown

    //             $buttons = '';

    //             if ($record->mark_entereby != null) {
    //                 $buttons .= $viewButton;
    //                 $viewButtonShown = true;
    //             } else {
    //                 $buttons .= '<a class="btn btn-xs btn-info" href="' . route('admin.Exam-Mark.markEnter', [$stu->id,$record->id]) . '" target="_blank">Enter</a>';
    //             }
    //             $role_id = auth()->user()->roles[0]->id;
    //             if ($role_id == 40 || $role_id == 1 ) {
    //                 if($record->mark_entereby != null){
    //                 $buttons .= ' <a class="btn btn-xs btn-danger" href="' . route('admin.Exam-Mark.editMark', [$stu->id,$record->id]) . '" target="_blank">Edit</a>';

    //                 }

    //             }

    //             $record->actions = $buttons;

    //             if($record->subject !=''){
    //                 $subject=Subject::find($record->subject);
    //                 if($subject){
    //                     $record->subject=$subject->name ?? '';
    //                     $record->subject_code=$subject->subject_code ?? '';
    //                 }else{
    //                     $record->subject='';
    //                 }
    //             }
    //             if($record->exam_name !='' && $record->year !='' && $record->semester !=''){

    //                 $record->exam_name=$record->exam_name.'/'.$this->toRoman($record->year).'/0'.$record->semester;

    //             }

    //         }

    //     }

    //     return response()->json(['data' => $response]);
    // }

    public function toggle_status(Request $request)
    {
        $response = 400;

        if ($request->has('examID') && $request->has('update')) {
            $examID = $request->examID;
            $updateValue = $request->update;

            $examAttendance = Examattendance::find($examID);

            if ($examAttendance) {
                $examAttendance->mark_entry = $updateValue;
                $examAttendance->save();
                $response = 200;
            }
        }

        return response()->json(['data' => $response]);
    }

    public function staff(Request $request)
    {
        $user_name_id = auth()->user()->id;
        $subjects = [];
        $response = [];
        $buttons = '';
        $timetable = ClassTimeTableTwo::where(['staff' => $user_name_id, 'status' => 1])->groupBy('class_name', 'subject')->select('class_name', 'subject')->get();

        if ($timetable) {
            foreach ($timetable as $timetables) {
                $get_enroll = CourseEnrollMaster::where(['id' => $timetables->class_name])->first();

                if ($get_enroll) {
                    if ($get_enroll != '') {
                        $newArray = explode('/', $get_enroll->enroll_master_number);
                        $get_course = ToolsCourse::where(['name' => $newArray[1]])->first();
                        if ($get_course) {
                            $classname = $get_course->short_form . '/' . $newArray[3] . '/' . $newArray[4];
                        }

                    } else {
                        $classname = '';
                    }
                    $examAtt = ExamattendanceData::where(['class_id' => $timetables->class_name, 'subject' => $timetables->subject])->groupBy(['class_id', 'subject', 'examename'])->select(['class_id', 'subject', 'examename'])->get();
                    if ($examAtt) {
                        foreach ($examAtt as $attendanceData) {

                            $responsesss = Examattendance::where([
                                'id' => $attendanceData->examename,
                                'att_entered' => 'Yes',
                                'mark_entry' => '1',
                            ])->first();

                            if ($responsesss) {
                                $responsesss->classname = $classname;

                                if ($responsesss->mark_entereby == null) {
                                    $buttonss = '<a class="btn btn-xs btn-info" href="' . route('admin.Exam-Mark.markEnter', [$timetables->class_name, $responsesss->id]) . '" target="_blank">Enter</a>';
                                } else {
                                    $buttonss = '<a class="btn btn-xs btn-primary" href="' . route('admin.Exam-Mark.markview', [$timetables->class_name, $responsesss->id]) . '" target="_blank">View</a>';
                                }
                                $responsesss->button = $buttonss;
                                $response[] = $responsesss;
                            }
                        }
                    }
                }
            }
        }
        $subjects = Subject::get();
        return view('admin.examMark.staff', compact('subjects', 'response'));
    }

    public function MarkEnter($classId, $id)
    {
        // dd($id,$classId);
        $exameData = '';
        $coMarks = [];

        $examDate = '';
        $examSubject = '';
        $classname = '';
        $examName = '';
        $totalPres = '';
        $totalAbs = '';
        if (isset($classId, $id)) {

            $secondTable = Examattendance::find($id);
            if ($secondTable != '') {

                $a = Subject::find($secondTable->subject);
                if ($a) {
                    $examSubject = $a->name . '(' . $a->subject_code . ')';
                }

                $examDate = $secondTable->date;

                $totalPres = $secondTable->total_present;
                $totalAbs = $secondTable->total_abscent;
                $examName = $secondTable->examename;

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

                $firstTable = ExamTimetableCreation::find($secondTable->exame_id);

                if ($firstTable) {

                    $firstTable->co;
                    if ($this->is_serialized($firstTable->co)) {
                        $coMark = unserialize($firstTable->co);

                        // Check if $dummy is an array or object
                        if (is_array($coMark) || is_object($coMark)) {
                            //  dd($coMark);
                            $coMarks = $coMark;
                            $data = 0;

                            foreach ($coMarks as $index => $coMark) {
                                $data += $coMark;
                                $coMarks['count'] = $data;
                            }
                        }
                    }
                }
            } else {
                return back();
            }
            $exameData = DB::table('examattendance_data')->leftJoin('students', 'examattendance_data.student_id', '=', 'students.user_name_id')->whereNull('students.deleted_at')->whereNull('examattendance_data.deleted_at')->where([
                'examattendance_data.class_id' => $classId,
                'examattendance_data.examename' => $id,
            ])->select('students.name', 'students.register_no', 'examattendance_data.student_id', 'examattendance_data.class_id', 'examattendance_data.subject', 'examattendance_data.examename', 'examattendance_data.attendance', 'examattendance_data.co_1', 'examattendance_data.co_2', 'examattendance_data.co_3', 'examattendance_data.co_4', 'examattendance_data.co_5')->orderBy('students.register_no', 'ASC')->get();
            // dd($exameData);
            $classId = '';
            $subjectId = '';
            $examId = '';
            if ($exameData) {
                $classId = $exameData[0]->class_id;
                $subjectId = $exameData[0]->subject;
                $examId = $exameData[0]->examename;
            }
        }
        return view('admin.examMark.enter', compact('examId', 'subjectId', 'classId', 'totalAbs', 'totalPres', 'exameData', 'coMarks', 'examDate', 'examSubject', 'classname', 'examName'));
    }

    public function MarkStore(Request $request)
    {

        if ($request) {
            $getMark = ExamattendanceData::where([
                'class_id' => $request->class_name,
                'examename' => $request->exame_name,
                'subject' => $request->subject,
            ])->get();
            // dd($getMark);
            $newArray = [];

            $originalArray = [];

            for ($i = 1; $i <= 5; $i++) {
                $key = "CO_" . $i;
                if (isset($request->$key)) {
                    $originalArray[$key] = $request->$key;
                }
            }
            foreach ($originalArray as $coKey => $coValues) {
                foreach ($coValues as $subKey => $subValue) {
                    $newArray[$subKey][$coKey] = $subValue;
                }
            }
            // dd($newArray);
            if ($getMark) {
                foreach ($newArray as $stuId => $getMarks) {
                    // Find the individual model by student_id
                    $studentModel = $getMark->where('student_id', $stuId)->first();

                    if ($studentModel) {
                        // Update the individual model
                        $studentModel->update([
                            'co_1' => $getMarks['CO_1'] ?? null,
                            'co_2' => $getMarks['CO_2'] ?? null,
                            'co_3' => $getMarks['CO_3'] ?? null,
                            'co_4' => $getMarks['CO_4'] ?? null,
                            'co_5' => $getMarks['CO_5'] ?? null,
                        ]);
                    }
                }

                $secondTable = Examattendance::find($request->exame_name);

                if ($secondTable) {
                    $secondTable->mark_date = now();
                    if (isset($request->publish)) {
                        $secondTable->status = '2';
                    }else{
                        $secondTable->mark_entereby = auth()->user()->id;
                    }
                    $secondTable->save();
                }
            }
        }
        return redirect()->route('admin.Exam-Mark.markview', [$request->class_name, $request->exame_name]);
    }

    public function markview($classId, $id)
    {
        $exameData = '';
        $coMarks = '';

        $examDate = '';
        $examSubject = '';
        $classname = '';
        $examName = '';
        $totalPres = '';
        $totalAbs = '';

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

            $secondTable = Examattendance::find($id);
            if ($secondTable) {

                $a = Subject::find($secondTable->subject);
                if ($a) {
                    $examSubject = $a->name . '(' . $a->subject_code . ')';
                }

                $examDate = $secondTable->date;
                $status = $secondTable->status;

                $totalPres = $secondTable->total_present;
                $totalAbs = $secondTable->total_abscent;
                $examName = $secondTable->examename;

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

                $firstTable = ExamTimetableCreation::find($secondTable->exame_id);

                if ($firstTable) {
                    $firstTable->co;
                    if ($this->is_serialized($firstTable->co)) {
                        $coMark = unserialize($firstTable->co);

                        // Check if $dummy is an array or object
                        if (is_array($coMark) || is_object($coMark)) {
                            //  dd($coMark);
                            $coMarks = $coMark;
                            $data = 0;

                            foreach ($coMarks as $index => $coMark) {
                                $data += $coMark;
                                $coMarks['count'] = $data;
                            }
                        }
                    }
                }
            }
            $exameData = DB::table('examattendance_data')->leftJoin('students', 'examattendance_data.student_id', '=', 'students.user_name_id')->whereNull('students.deleted_at')->whereNull('examattendance_data.deleted_at')->where([
                'examattendance_data.class_id' => $classId,
                'examattendance_data.examename' => $id,
            ])->select('students.name', 'students.register_no', 'examattendance_data.student_id', 'examattendance_data.class_id', 'examattendance_data.subject', 'examattendance_data.examename', 'examattendance_data.attendance', 'examattendance_data.co_1', 'examattendance_data.co_2', 'examattendance_data.co_3', 'examattendance_data.co_4', 'examattendance_data.co_5')->orderBy('students.register_no', 'ASC')->get();
            $classId = '';
            $subjectId = '';
            $examId = '';
            if ($exameData) {
                $classId = $exameData[0]->class_id;
                $subjectId = $exameData[0]->subject;
                $examId = $exameData[0]->examename;
            }
        }
        return view('admin.examMark.view', compact('examId', 'subjectId', 'classId', 'totalAbs', 'totalPres', 'exameData', 'coMarks', 'examDate', 'examSubject', 'classname', 'examName', 'status', 'examCellCo'));
    }

    public function editMark($classId, $id)
    {
        $exameData = '';
        $coMarks = '';

        $examDate = '';
        $status = '';
        $examSubject = '';
        $classname = '';
        $examName = '';
        $totalPres = '';
        $totalAbs = '';
        if (isset($classId, $id)) {

            $secondTable = Examattendance::find($id);
            if ($secondTable) {

                $a = Subject::find($secondTable->subject);
                if ($a) {
                    $examSubject = $a->name . '(' . $a->subject_code . ')';
                }

                $examDate = $secondTable->date;
                $status = $secondTable->status;

                $totalPres = $secondTable->total_present;
                $totalAbs = $secondTable->total_abscent;
                $examName = $secondTable->examename;

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

                $firstTable = ExamTimetableCreation::find($secondTable->exame_id);

                if ($firstTable) {
                    $firstTable->co;
                    if ($this->is_serialized($firstTable->co)) {
                        $coMark = unserialize($firstTable->co);

                        // Check if $dummy is an array or object
                        if (is_array($coMark) || is_object($coMark)) {
                            //  dd($coMark);
                            $coMarks = $coMark;
                            $data = 0;

                            foreach ($coMarks as $index => $coMark) {
                                $data += $coMark;
                                $coMarks['count'] = $data;
                            }
                        }
                    }
                }
            }
            $exameData = DB::table('examattendance_data')->leftJoin('students', 'examattendance_data.student_id', '=', 'students.user_name_id')->whereNull('students.deleted_at')->whereNull('examattendance_data.deleted_at')->where([
                'examattendance_data.class_id' => $classId,
                'examattendance_data.examename' => $id,
            ])->select('students.name', 'students.register_no', 'examattendance_data.student_id', 'examattendance_data.class_id', 'examattendance_data.subject', 'examattendance_data.examename', 'examattendance_data.attendance', 'examattendance_data.co_1', 'examattendance_data.co_2', 'examattendance_data.co_3', 'examattendance_data.co_4', 'examattendance_data.co_5')->orderBy('students.register_no', 'ASC')->get();
            // dd($exameData);
            $classId = '';
            $subjectId = '';
            $examId = '';
            if ($exameData) {
                $classId = $exameData[0]->class_id;
                $subjectId = $exameData[0]->subject;
                $examId = $exameData[0]->examename;
            }
        }
        // dd($exameData);
        return view('admin.examMark.edit', compact('examId', 'subjectId', 'classId', 'totalAbs', 'totalPres', 'exameData', 'coMarks', 'examDate', 'examSubject', 'classname', 'examName', 'status'));
    }

    public function editMark_request(Request $request)
    {
        $Exam_id = $request->input('Exam_id');
        $exam_name = $request->input('exam_name');
        $Exam_date = $request->input('Exam_date');
        $class_name = $request->input('class_name');
        $Class_subject = $request->input('Class_subject');
        $exam_staff_id = auth()->user()->id;
        $reason = $request->input('reason');
        $examAttendance = examattendance::find($Exam_id);
        if ($examAttendance) {
            $editRequest = cat_exam_edit_request::create([
                'Exam_id' => $Exam_id,
                'exam_name' => $exam_name,
                'Exam_date' => $Exam_date,
                'class_name' => $class_name,
                'Class_subject' => $Class_subject,
                'exam_staff_id' => $exam_staff_id,
                'status' => 0,
                'date' => now(),
                'reason' => $reason,
            ]);
            $examAttendance->update(['edit_request' => $editRequest->id]);
            return response()->json(['data' => 200]);
        } else {
            return response()->json(['data' => 400]);
        }
    }
    // branch_05 13-09-23 End
    public function verifiedStatus(Request $request)
    {
        $examId = $request->input('exameId');
        if (isset($examId) && !empty($examId)) {
            $data = examattendance::find($examId);
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

    public function find(Request $request)
    {
        // dd($request);
        // $accademicYear = $request->input('ay');
        // $course = $request->input('course');
        // $semester = $request->input('semester');
        // $year = $request->input('year');
        // $section = $request->input('section');
        // $examename = $request->input('examename');
        // $data = $request->input('data');
        // $accademicYear = $request->input('academicYear_id');
        // $course = $request->input('course_id');
        // $semester = $request->input('semester_id');
        // $year = $request->input('year');
        // $section = $request->input('section');
        $examename = $request->input('examename');
        // $examename = $request->input('examename');
        // $academicYear_id1 = $request->academicYear_id1;
        // $section1 = $request->section1;
        // $course_id1 = $request->course_id1;

        $query = Examattendance::query();
        // dd( $query );
        // mark_date

        // Check each input value and add a where clause if it's present
        // if ($accademicYear) {
        //     $query->Where('acyear', $accademicYear);
        // }
        // if ($course) {
        //     $query->Where('course', $course);
        // }
        // if ($semester) {
        //     $query->Where('sem', $semester);
        // }
        // if ($year) {
        //     $query->Where('year', $year);
        // }
        // if ($section) {
        //     $query->Where('section', $section);
        // }
        if ($examename) {
            $query->Where('examename', $examename);

        }
        // if ($academicYear_id1) {
        //     $query->Where('acyear', $academicYear_id1);

        // }
        // if ($section1) {
        //     $query->Where('section', $section1);

        // }
        // if ($course_id1) {
        //     $query->Where('course', $course_id1);

        // }
        $query->where('att_entered', 'Yes');
        $response = $query->get();

        // dd($response);
        // $response = Examattendance::select()
        //     ->where('acyear', $accademicYear)
        //     ->where('examename', $examename)
        //     ->where('sem', $semester)
        //     ->where('section', $section)
        //     ->where('course', $course)
        //     ->where('year', $year)
        //     ->where('att_entered', 'Yes')
        //     ->get();
        if ($response->isNotEmpty()) {
            foreach ($response as $record) {

                if ($record->course != null) {

                    $value = ToolsCourse::find($record->course);
                    $course_name = $value->name;
                    $course_shortname = $value->short_form;
                }
                if ($record->acyear != null) {

                    $value = AcademicYear::find($record->acyear);
                    $ac_Year_name = $value->name;
                }
                if ($record->sem != null) {

                    $value = Semester::find($record->sem);
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
                } else {
                    $record->toggle = '';
                }

                // $record->attenteredDate='';
                $record->attenteredBY = '';
                $record->markStaff = '';
                // $courseFound = ToolsCourse::find($course);
                // $accYearFound = AcademicYear::find($accademicYear);
                // $semesterFound = Semester::find($semester);

                // if ($courseFound && $accYearFound && $semesterFound) {
                //     $courseName = $courseFound->name;
                //     $academicYearName = $accYearFound->name;
                //     $semesterName = $semesterFound->semester;

                //     $string = '/' . $courseName . '/' . $academicYearName . '/' . $semesterName . '/' . $section;
                // } else {
                //     $string = '';
                // }
                $record->classDetails = '';

                if ($course_name && $ac_Year_name && $semester_id) {

                    $record->classDetails = $course_shortname . '/' . $semester_id . '/' . $section_name;

                    $string = '/' . $course_name . '/' . $ac_Year_name . '/' . $semester_id . '/' . $section_name;
                } else {
                    $string = '';
                }
                $stu = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%' . $string)->first();

                $staffId = null;
                if ($record->mark_date != '') {
                    $record->mark_date = $record->mark_date ? date('d-m-Y', strtotime($record->mark_date)) : 'Not Yet Entered';
                }

                if ($record->edit_request != '') {
                    $editRequest = cat_exam_edit_request::find($record->edit_request);
                    if ($editRequest) {
                        $editRequest->exam_staff_id;

                        $markenteredBy = TeachingStaff::where('user_name_id', $editRequest->exam_staff_id)->first();
                        $markenteredBy2 = null;

                        if ($markenteredBy === null) {
                            $markenteredBy2 = NonTeachingStaff::where('user_name_id', $editRequest->exam_staff_id)->first();
                        }

                        if ($markenteredBy !== null) {
                            $record->edit_request = $markenteredBy->name . '(' . $markenteredBy->StaffCode . ')  /' . date('d-m-Y', strtotime($editRequest->date));
                        } elseif ($markenteredBy2 !== null) {
                            $record->edit_request = $markenteredBy2->name . '(' . $markenteredBy2->StaffCode . ') /' . date('d-m-Y', strtotime($editRequest->date));
                        } else {
                            $User = User::find($editRequest->exam_staff_id);
                            if ($User) {
                                $record->edit_request = $User->name . '/' . date('d-m-Y', strtotime($editRequest->date));
                            }
                        }
                    }
                }
                if ($stu != null) {
                    $students = Student::where('enroll_master_id', $stu->id)->count();

                    if ($students <= 0) {
                        $students = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $stu->id)->count();
                    }

                    if ($students) {
                        $record->totalstudent = $students;
                    }

                    $enteredBy = TeachingStaff::where('user_name_id', $record->mark_entereby)->first();
                    $enteredBy2 = null;

                    if ($enteredBy === null) {
                        $enteredBy2 = NonTeachingStaff::where('user_name_id', $record->mark_entereby)->first();
                    }

                    if ($enteredBy !== null) {
                        $record->markStaff = $enteredBy->StaffCode;
                    } elseif ($enteredBy2 !== null) {
                        $record->markStaff = $enteredBy2->StaffCode;
                    } else {
                        $User = User::find($record->mark_entereby);
                        if ($User) {
                            $record->markStaff = $User->name;
                        }
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
                $User1 = TeachingStaff::where('user_name_id', $record->entered_by)->first();
                $User2 = null;

                if ($User1 === null) {
                    $User2 = NonTeachingStaff::where('user_name_id', $record->entered_by)->first();
                }

                if ($User1 !== null) {
                    $record->attenteredBY = $User1->StaffCode;
                } elseif ($User2 !== null) {
                    $record->attenteredBY = $User2->StaffCode;
                } else {
                    $User = User::find($record->entered_by);
                    if ($User) {
                        $record->attenteredBY = $User->name;
                    }
                }

                $viewButton = '<a class="btn btn-xs btn-primary" href="' . route('admin.Exam-Mark.markview', [$stu->id, $record->id]) . '" target="_blank">View</a>';
                $viewButtonShown = false; // Variable to track if the "View" button is shown

                $buttons = '';

                if ($record->mark_entereby != null) {
                    $buttons .= $viewButton;
                    $viewButtonShown = true;
                } else {
                    $buttons .= '<a class="btn btn-xs btn-info" href="' . route('admin.Exam-Mark.markEnter', [$stu->id, $record->id]) . '" target="_blank">Enter</a>';
                }
                $role_id = auth()->user()->roles[0]->id;
                if ($role_id == 40 || $role_id == 1) {
                    if ($record->mark_entereby != null) {
                        $buttons .= ' <a class="btn btn-xs btn-danger" href="' . route('admin.Exam-Mark.editMark', [$stu->id, $record->id]) . '" target="_blank">Edit</a>';
                    }
                }

                $record->actions = $buttons;

                if ($record->subject != '') {
                    $subject = Subject::find($record->subject);
                    if ($subject) {
                        $record->subject = $subject->name ?? '';
                        $record->subject_code = $subject->subject_code ?? '';
                    } else {
                        $record->subject = '';
                    }
                }
                if ($record->exam_name != '' && $record->year != '' && $record->semester != '') {

                    $record->exam_name = $record->exam_name . '/' . $this->toRoman($record->year) . '/0' . $record->semester;
                }
            }
        }

        return response()->json(['data' => $response]);
    }

    public function indexStaff()
    {

        $user_name_id = auth()->user()->id;
        $subjects = [];
        $response = [];
        $buttons = '';
        $buttonss = '';

        $getAys = AcademicYear::pluck('name', 'id');
        $currentClasses = Session::get('currentClasses');

        $timetable = ClassTimeTableTwo::whereIn('class_name', $currentClasses)->where(['staff' => $user_name_id, 'status' => 1])->groupBy('class_name', 'subject')->select('class_name', 'subject')->get();
        if ($timetable) {
            foreach ($timetable as $timetables) {

                $got_subject = Subject::where('id', $timetables->subject)
                    ->whereNotIn('subject_type_id', [3, 9, 15])->select('name', 'subject_code')
                    ->first();

                if ($got_subject != null) {
                    $get_enroll = CourseEnrollMaster::where(['id' => $timetables->class_name])->first();
                    if ($get_enroll) {
                        if ($get_enroll != '') {
                            $newArray = explode('/', $get_enroll->enroll_master_number);
                            $get_course = ToolsCourse::where(['name' => $newArray[1]])->first();
                            if ($get_course) {
                                $classname = $get_course->short_form . '/' . $newArray[3] . '/' . $newArray[4];
                            }
                            // $class_name=$newArray[1].'/'.$newArray[3].'/'.$newArray[4];

                        } else {
                            $classname = '';
                        }

                        if ($timetables) {
                            $timetables->classname = $classname;
                            $timetables->subject_name = $got_subject->name . ' (' . $got_subject->subject_code . ')';
                            $buttonss = '<a class="btn btn-xs btn-primary" href="' . route('admin.Exam-Mark-Result.resultview', [$timetables->class_name, $timetables->subject]) . '" target="_blank">View</a>';
                            $timetables->button = $buttonss;
                            $response[] = $timetables;
                        }
                    }
                }
            }
        }
        // $subjects = Subject::get();
        return view('admin.catResult.index', compact('response', 'getAys'));
    }

    public function resultview($classId, $subjectId, $pdf = '')
    {

        if (isset($classId, $subjectId)) {
            $student = Student::where('enroll_master_id', $classId)->select('name', 'register_no', 'user_name_id')->get();
            if (count($student) <= 0) {
                $student = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $classId)->select('students.name', 'students.register_no', 'students.user_name_id')->get();
            }
            if ($student) {
                foreach ($student as $students) {
                    // dd();

                    $get_enroll = CourseEnrollMaster::find($classId);

                    if ($get_enroll) {
                        if ($get_enroll != '') {
                            $newArray = explode('/', $get_enroll->enroll_master_number);
                            $get_course = ToolsCourse::where(['name' => $newArray[1]])->first();
                            if ($get_course) {
                                $students->classname = $get_course->short_form . '/' . $newArray[3] . '/' . $newArray[4];
                            }
                            // $class_name=$newArray[1].'/'.$newArray[3].'/'.$newArray[4];

                        }
                    }
                    $exameData = ExamattendanceData::where([
                        'class_id' => $classId,
                        'subject' => $subjectId,
                        'student_id' => $students->user_name_id,
                    ])->get();

                    if ($exameData) {

                        foreach ($exameData as $exameDatas) {

                            $exametable2 = Examattendance::find($exameDatas->examename);

                            if ($exametable2 && $exametable2->status == '2') {

                                $a = Subject::find($subjectId);
                                $attendenceData = ExamattendanceData::where('examename', $exametable2->id)->get();
                                // ;
                                if ($a) {
                                    $students->subjectName = $a->name . '(' . $a->subject_code . ')';
                                }
                                // $exametable2
                                // $co_mark = unserialize($exametable2->co_mark);
                                if ($this->is_serialized($exametable2->co_mark)) {

                                    $co_mark = unserialize($exametable2->co_mark);
                                } else {
                                    $co_get = ExamTimetableCreation::where('id', $exametable2->exame_id)->select('co')->first();
                                    $co_mark = unserialize($co_get->co);
                                }
                                $exameDatas->examename;
                                if ($exameDatas->co_1 != null) {
                                    $students->co_1 = $exameDatas->co_1;
                                    $students->co_1Name = $exametable2->examename;
                                    $students->co_1Mark = $co_mark['CO-1'] ?? 1;
                                    $students->co_1Absent = 0;
                                    $students->co_1Present = 0;
                                    // $arrayCo1=[];
                                    foreach ($attendenceData as $attendenceDatas) {
                                        // dd($attendenceDatas);
                                        if ($attendenceDatas->attendance == 'Absent') {
                                            $students->co_1Absent++;
                                        } else {
                                            $students->co_1Present++;
                                        }
                                        // $students->attendance=$attendenceDatas->attendance;

                                    }
                                }
                                if ($exameDatas->co_2 != null) {
                                    $students->co_2 = $exameDatas->co_2;
                                    $students->co_2Name = $exametable2->examename;
                                    $students->co_2Mark = $co_mark['CO-2'] ?? 1;

                                    $students->co_2Absent = 0;
                                    $students->co_2Present = 0;
                                    foreach ($attendenceData as $attendenceDatas) {
                                        if ($attendenceDatas->attendance == 'Absent') {
                                            $students->co_2Absent++;
                                        } else {
                                            $students->co_2Present++;
                                        }
                                    }
                                }
                                if ($exameDatas->co_3 != null) {
                                    $students->co_3 = $exameDatas->co_3;
                                    $students->co_3Name = $exametable2->examename;
                                    $students->co_3Mark = $co_mark['CO-3'] ?? 1;

                                    $students->co_3Absent = 0;
                                    $students->co_3Present = 0;
                                    foreach ($attendenceData as $attendenceDatas) {
                                        if ($attendenceDatas->attendance == 'Absent') {
                                            $students->co_3Absent++;
                                        } else {
                                            $students->co_3Present++;
                                        }
                                    }
                                }
                                if ($exameDatas->co_4 != null) {
                                    $students->co_4 = $exameDatas->co_4;
                                    $students->co_4Name = $exametable2->examename;
                                    $students->co_4Mark = $co_mark['CO-4'] ?? 1;
                                    $students->co_4Absent = 0;
                                    $students->co_4Present = 0;
                                    foreach ($attendenceData as $attendenceDatas) {
                                        if ($attendenceDatas->attendance == 'Absent') {
                                            $students->co_4Absent++;
                                        } else {
                                            $students->co_4Present++;
                                        }
                                    }
                                }
                                if ($exameDatas->co_5 != null) {
                                    $students->co_5 = $exameDatas->co_5;
                                    $students->co_5Name = $exametable2->examename;
                                    $students->co_5Mark = $co_mark['CO-5'] ?? 1;
                                    $students->co_5Absent = 0;
                                    $students->co_5Present = 0;
                                    foreach ($attendenceData as $attendenceDatas) {
                                        if ($attendenceDatas->attendance == 'Absent') {
                                            $students->co_5Absent++;
                                        } else {
                                            $students->co_5Present++;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $count = 0;
        for ($i = 1; $i <= 5; $i++) {
            if (isset($student[0]->{'co_' . $i})) {
                $count++;
            }
        }

        if ($pdf != '') {

            $pdf = PDF::loadView('admin.catResult.Exam-result-StaffWise-reportPDF', ['student' => $student, 'count' => $count])->setOption('margin-top', 0)->setOption('margin-bottom', 0)->setOption('margin-left', 0)->setOption('margin-right', 0);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('Exam-result-StaffWise-report.pdf');
        } else {

            return view('admin.catResult.view', compact('student', 'classId', 'subjectId', 'count'));
        }
    }

    public function getPastRecords(Request $request)
    {
        if (isset($request->past_ay) && isset($request->past_semester)) {
            $array = [];
            // $request->past_ay = '2023-2024';
            $enroll = '%/%/' . $request->past_ay . '/' . $request->past_semester . '/%';

            $getClass = CourseEnrollMaster::where('enroll_master_number', "LIKE", $enroll)->select('id', 'enroll_master_number')->get();

            $theClass = [];

            if (count($getClass) > 0) {
                foreach ($getClass as $enrolledClass) {
                    array_push($theClass, $enrolledClass->id);
                }
            }

            $type_id = auth()->user()->roles[0]->type_id;

            $user_name_id = auth()->user()->id;
            $subjects = [];
            $response = [];
            $buttons = '';
            $buttonss = '';

            $currentClasses = Session::get('currentClasses');

            $timetable = ClassTimeTableTwo::whereIn('class_name', $theClass)->where(['staff' => $user_name_id, 'status' => 1])->groupBy('class_name', 'subject')->select('class_name', 'subject')->get();
            if (count($timetable) > 0) {
                foreach ($timetable as $timetables) {

                    $got_subject = Subject::where('id', $timetables->subject)
                        ->whereNotIn('subject_type_id', [3, 9, 15])->select('name', 'subject_code')
                        ->first();

                    if ($got_subject != null) {
                        $get_enroll = CourseEnrollMaster::where(['id' => $timetables->class_name])->first();
                        if ($get_enroll) {
                            if ($get_enroll != '') {
                                $newArray = explode('/', $get_enroll->enroll_master_number);
                                $get_course = ToolsCourse::where(['name' => $newArray[1]])->first();
                                if ($get_course) {
                                    $classname = $get_course->short_form . '/' . $newArray[3] . '/' . $newArray[4];
                                }
                            } else {
                                $classname = '';
                            }

                            if ($timetables) {
                                $timetables->classname = $classname;
                                $timetables->subject_name = $got_subject->name . ' (' . $got_subject->subject_code . ')';
                                $buttonss = '<a class="btn btn-xs btn-primary" href="' . route('admin.Exam-Mark-Result.resultview', [$timetables->class_name, $timetables->subject]) . '" target="_blank">View</a>';
                                $timetables->button = $buttonss;
                                $response[] = $timetables;
                            }
                        }
                    }
                }
                return response()->json(['status' => true, 'data' => $response]);
            } else {
                return response()->json(['status' => false, 'data' => 'Classes Not Found']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }
}
