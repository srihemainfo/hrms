<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ClassTimeTableTwo;
use App\Models\CourseEnrollMaster;
use App\Models\ExamCellCoordinator;
use App\Models\LabExamAttendance;
use App\Models\LabExamAttendanceData;
use App\Models\LabFirstmodel;
use App\Models\NonTeachingStaff;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentPromotionHistory;
use App\Models\Subject;
use App\Models\SubjectRegistration;
use App\Models\TeachingStaff;
use App\Models\ToolsCourse;
use App\Models\User;
use Illuminate\Http\Request;

class LabMarkMasterController extends Controller
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
        $examNames = LabFirstmodel::select('exam_name')->distinct()->get();
        $section = Section::select('section')->distinct()->get();
        // $exameMark = LabExamAttendance::where('att_entered', 'Yes')->orderBy('status', 'asc')->get();
        $exameMark = LabExamAttendance::orderByRaw("FIELD(status, 0,3,1, 2)")->orderBy('id', 'desc')->take(200)->get();

        if ($exameMark->isNotEmpty()) {
            foreach ($exameMark as $record) {
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

                // . ' ' . (($record->status == 1 || $record->status == 2) ? 'disabled' : '')

                $enteredBy = TeachingStaff::where('user_name_id', $record->mark_entereby)->first() ?? NonTeachingStaff::where('user_name_id', $record->mark_entereby)->first() ?? User::find($record->mark_entereby);
                $record->markStaff = $enteredBy ? ($enteredBy->StaffCode ?? $enteredBy->name) : '';

                $courseFound = ToolsCourse::find($record->course);
                $accYearFound = AcademicYear::find($record->acyear);
                $semesterFound = Semester::find($record->sem);

                $record->classDetails = '';
                if ($courseFound && $accYearFound && $semesterFound) {
                    $record->classDetails = $courseFound->short_form . '/' . $semesterFound->semester . '/' . $record->section;
                }

                $string = '';
                if ($courseFound && $accYearFound && $semesterFound) {
                    $string = '/' . $courseFound->name . '/' . $accYearFound->name . '/' . $semesterFound->semester . '/' . $record->section;
                }

                $stu = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%' . $string)->first();
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

                $user = User::find($record->entered_by) ?? NonTeachingStaff::where('user_name_id', $record->entered_by)->first() ?? TeachingStaff::where('user_name_id', $record->entered_by)->first();
                $record->attenteredBY = $user ? ($user->StaffCode ?? $user->name) : '';

                $viewButton = '<a class="btn btn-xs btn-primary" href="' . route('admin.lab_Exam_Mark.markview', [$stu->id, $record->id]) . '" target="_blank">View</a>';
                $buttons = '';

                if ($record->mark_entereby != null && $record->status == 3 || $record->status == 1 || $record->status == 2) {
                    $buttons .= $viewButton;
                } else {
                    $buttons .= '<a class="btn btn-xs btn-info" href="' . route('admin.lab_Exam_Mark.markEnter', [$stu->id, $record->id]) . '" target="_blank">Enter</a>';
                }

                $role_id = auth()->user()->roles[0]->id;
                if ($role_id == 40 || $role_id == 1) {
                    if ($record->mark_entereby != null) {
                        $buttons .= ' <a class="btn btn-xs btn-danger" href="' . route('admin.lab_Exam_Mark.editMark', [$stu->id, $record->id]) . '" target="_blank">Edit</a>';
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
        return view('admin.labExamMark.index', compact('courses', 'semester', 'Subjects', 'AcademicYear', 'examNames', 'exameMark', 'section'));
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
        $pass = 0;
        $fail = 0;
        $passPercentage = 0;
        $coMarks = 100;

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

            $secondTable = LabExamAttendance::find($id);

            if ($secondTable) {

                $a = Subject::find($secondTable->subject);
                if ($a) {
                    $examSubject = $a->name . '(' . $a->subject_code . ')';
                }

                $examDate = date('d-m-Y', strtotime($secondTable->date));
                $status = $secondTable->status;

                $total_students = $secondTable->pass_count + $secondTable->fail_count;
                // $totalAbs = $secondTable->total_abscent;
                // $passPercentage = number_format((($totalPres / ($totalPres + $totalAbs)) * $coMarks), 2);

                $examName = $secondTable->examename;

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

                $exameData = LabExamAttendanceData::where([
                    'class_id' => $classId,
                    'lab_exam_name' => $id,
                ])->get();

                $classId = '';
                $subjectId = '';
                $examId = '';
                if ($exameData) {
                    $coMarks = 100;
                    foreach ($exameData as $exameDatas) {

                        $classId = $exameDatas->class_id;
                        $subjectId = $exameDatas->subject;
                        $examId = $exameDatas->lab_exam_name;
                        // $student = Student::where('user_name_id', $exameDatas->student_id)->first();
                        // if ($student != '') {
                        //     $exameDatas->studentName = $student->name;
                        //     $exameDatas->studentReg = $student->register_no;
                        // }
                        $student = SubjectRegistration::where(['user_name_id' => $exameDatas->student_id, 'subject_id' => $subjectId, 'enroll_master' => $classId])->first();
                        if ($student != '') {
                            $exameDatas->studentName = $student->student_name;
                            $exameDatas->studentReg = $student->register_no;
                            if ($coMarks / 2 <= $exameDatas->cycle_mark) {
                                if ($exameDatas->cycle_mark != 999) {
                                    $pass++;
                                } else {
                                    $fail++;
                                }
                            } else {
                                $fail++;
                            }
                        }

                    }

                    $exameData->pass = $pass;
                    $exameData->fail = $fail;
                    if ($pass > 0) {
                        $passPercentage = number_format((($pass / ($pass + $fail)) * $coMarks), 2);
                    } else {
                        $passPercentage = number_format(0, 2);
                    }
                    $exameData->passPercentage = $passPercentage;

                }
            }
        }
        return view('admin.labExamMark.markview', compact('examId', 'total_students', 'subjectId', 'classId', 'exameData', 'coMarks', 'examDate', 'examSubject', 'classname', 'examName', 'status', 'examCellCo', 'passPercentage'));
    }
    public function MarkEnter($classId, $id)
    {

        $exameData = '';
        $coMarks = 100;

        $examDate = '';
        $examSubject = '';
        $classname = '';
        $examName = '';
        $totalPres = '';
        $totalAbs = '';
        if (isset($classId, $id)) {

            $secondTable = LabExamAttendance::find($id);
            if ($secondTable != '' && $secondTable->status == 0) {
                if ($secondTable) {

                    $a = Subject::find($secondTable->subject);
                    if ($a) {
                        $examSubject = $a->name . '(' . $a->subject_code . ')';
                    }

                    $examDate = date('d-m-Y', strtotime($secondTable->date));

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

                    $firstTable = LabFirstmodel::find($secondTable->exame_id);

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
                $exameData = LabExamAttendanceData::where([
                    'class_id' => $classId,
                    'lab_exam_name' => $id,
                ])->get();
                // dd($exameData);
                $classId = '';
                $subjectId = '';
                $examId = '';
                if ($exameData) {
                    foreach ($exameData as $exameDatas) {
                        $exameDatas->student_id;
                        $classId = $exameDatas->class_id;
                        $subjectId = $exameDatas->subject;
                        $examId = $exameDatas->lab_exam_name;
                        // $student = Student::where('user_name_id', $exameDatas->student_id)->first();
                        $student = SubjectRegistration::where(['user_name_id' => $exameDatas->student_id, 'subject_id' => $subjectId, 'enroll_master' => $classId])->first();
                        if ($student != '') {
                            $exameDatas->studentName = $student->student_name;
                            $exameDatas->studentReg = $student->register_no;
                        }
                    }
                }
            } else {
                return to_route('admin.lab_Exam_Mark.markview', [$classId, $id]);
            }
        }

        // dd($exameData);
        //  dd($exameData,$examId,$classId,$totalAbs,$totalPres,$exameData,$coMarks, $examDate,$examSubject, $classname,$examName);
        return view('admin.labExamMark.markEnter', compact('examId', 'subjectId', 'classId', 'totalAbs', 'totalPres', 'exameData', 'coMarks', 'examDate', 'examSubject', 'classname', 'examName'));
    }

    public function MarkStore(Request $request)
    {
        if ($request) {
            // $checkStatus = LabExamAttendance::find($request->exame_name);
            // if ($checkStatus) {
            //     if ($checkStatus->mark_entry != 0) {
            //         return back()->with('error', 'Mark Entry Is Enabled');
            //     }
            // } else {
            //     return back()->with('error', 'Lab Exam Not Found');
            // }
            $getMark = LabExamAttendanceData::where([
                'class_id' => $request->class_name,
                'lab_exam_name' => $request->exame_name,
                'subject' => $request->subject,
            ])->get();
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
            if ($getMark) {
                $pass_count = 0;
                $fail_count = 0;
                foreach ($newArray as $stuId => $getMarks) {
                    // Find the individual model by student_id
                    $studentModel = $getMark->where('student_id', $stuId)->first();

                    if ($getMarks['CO_1'] < 100 / 2) {
                        $fail_count++;
                        $pass = 0;
                    } else {
                        $pass_count++;
                        $pass = 1;
                    }

                    if ($studentModel) {
                        // Update the individual model
                        $studentModel->update([
                            'cycle_mark' => $getMarks['CO_1'] ?? null,
                            'pass' => $pass ?? null,
                        ]);
                    }
                }
                $total_student = $pass_count + $fail_count;
                if ($pass_count > 0) {
                    $pass_percentage = number_format(($pass_count / $total_student) * 100, 2);
                } else {

                    $pass_percentage = 0;
                }
                if ($fail_count > 0) {
                    $fail_percentage = number_format(($fail_count / $total_student) * 100, 2);
                } else {
                    $fail_percentage = 0;
                }
                // dd($pass_percentage ,$fail_percentage ,$pass_count,$fail_count,$total_student );
                // $true = ClassTimeTableTwo::where(['staff' => auth()->user()->id,'subject' =>$request->subject, 'status' => 1])->select('subject')->first();
                $secondTable = LabExamAttendance::find($request->exame_name);
                $mark_entry_by = LabExamAttendance::find($request->exame_name, 'mark_entereby');
                $mark_enter = $mark_entry_by->mark_entereby;
                if ($secondTable) {

                    if ($secondTable->mark_entereby == null) {
                        $secondTable->mark_entereby = auth()->user()->id;
                        $secondTable->status = 3;
                    } elseif ($secondTable->mark_entereby != null && !isset($request->publish)) {
                        $secondTable->updateby = auth()->user()->id;
                        $secondTable->status = 3;
                    }

                    $secondTable->mark_date = now();
                    $secondTable->pass_count = $pass_count;
                    $secondTable->fail_count = $fail_count;
                    $secondTable->pass_percentage = $pass_percentage;
                    $secondTable->fail_percentage = $fail_percentage;
                    if (isset($request->publish)) {
                        $secondTable->status = '2';
                        $secondTable->publishby = auth()->user()->id;
                    }
                    $secondTable->save();
                }
            }
        }
        if (isset($request->publish)) {
            $message = 'Mark Published successfully';
        } elseif ($mark_enter != '') {
            $message = 'Mark Updated successfully';
        } elseif ($mark_enter == '') {
            $message = 'Mark Entered successfully';
        } else {
            $message = '';
        }
        // return redirect()->route('admin.lab_Exam_Mark.markview', [$request->class_name, $request->exame_name])->with('message', $message);
        return to_route('admin.lab_Exam_Mark.markview', [$request->class_name, $request->exame_name])->with('message', $message);
    }
    public function verifiedStatus(Request $request)
    {
        $examId = $request->input('exameId');
        if (isset($examId) && !empty($examId)) {
            $data = LabExamAttendance::find($examId);

            if ($data) {
                if ($data->mark_entry != 1) {
                    $data->update(['status' => '1']);
                    return response()->json(['data' => 200]);
                } else {
                    return response()->json(['data' => 401]);
                }
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
        $coMarks = '';

        $examDate = '';
        $status = '';
        $examSubject = '';
        $classname = '';
        $examName = '';
        $totalPres = '';
        $totalAbs = '';
        $pass = 0;
        $fail = 0;
        $passPercentage = 0;
        $coMarks = 100;
        $role_id = auth()->user()->id;

        if (isset($classId, $id)) {
            $secondTable = LabExamAttendance::find($id);
            $status = false;

            if ($role_id == 40 || $role_id == 1) {
                $status = true;
            } else if ($secondTable->status != 2 || $secondTable->status != 1) {
                $status = true;
            }

            if ($secondTable != '' && $status) {

                $secondTable = LabExamAttendance::find($id);
                $subject_get = Subject::find($secondTable->subject);
                if ($subject_get) {
                    $examSubject = $subject_get->name . '(' . $subject_get->subject_code . ')';
                }

                $examDate = date('d-m-Y', strtotime($secondTable->date));
                $status = $secondTable->status;

                // $totalPres = $secondTable->total_present;
                // $totalAbs = $secondTable->total_abscent;
                $totalStudent = $secondTable->pass_count + $secondTable->fail_count;
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

                $firstTable = LabFirstmodel::find($secondTable->exame_id);

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

                $exameData = LabExamAttendanceData::where([
                    'class_id' => $classId,
                    'lab_exam_name' => $id,
                ])->get();
                $classId = '';
                $subjectId = '';
                $examId = '';
                if ($exameData) {
                    $coMarks = 100;
                    foreach ($exameData as $exameDatas) {
                        $exameDatas->student_id;
                        $classId = $exameDatas->class_id;
                        $subjectId = $exameDatas->subject;
                        $examId = $exameDatas->lab_exam_name;
                        $student = SubjectRegistration::where(['user_name_id' => $exameDatas->student_id, 'subject_id' => $subjectId, 'enroll_master' => $classId])->first();
                        if ($student != '') {
                            $exameDatas->studentName = $student->student_name;
                            $exameDatas->studentReg = $student->register_no;

                            if ($coMarks / 2 <= $exameDatas->cycle_mark) {
                                if ($exameDatas->cycle_mark != 999) {
                                    $pass++;
                                } else {

                                    $fail++;
                                }
                            } else {

                                $fail++;
                            }
                        }
                        $exameData->pass = $pass;
                        $exameData->fail = $fail;
                        if ($pass > 0) {
                            $passPercentage = number_format((($pass / ($pass + $fail)) * $coMarks), 2);
                        } else {
                            $passPercentage = 0;
                        }
                        $exameData->passPercentage = $passPercentage;
                        $exameData->coMarks = $coMarks;
                    }
                }
            } else {
                return to_route('admin.lab_Exam_Mark.markview', [$classId, $id]);
            }
        } else {
            return to_route('admin.lab_Exam_Mark.markview', [$classId, $id]);
        }

        // dd($exameData);
        return view('admin.labExamMark.editMark', compact('examId', 'subjectId', 'classId', 'totalStudent', 'exameData', 'coMarks', 'examDate', 'examSubject', 'classname', 'examName', 'status'));
    }
    public function toggle_status(Request $request)
    {
        $response = 400;

        if ($request->has('examID') && $request->has('update')) {
            $examID = $request->examID;
            $updateValue = $request->update;

            $examAttendance = LabExamAttendance::find($examID);

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
        $accademicYear = $request->input('AcademicYear');
        $semesterType = $request->input('semesterType');
        // $academicYear_id1 = $request->academicYear_id1;
        // $section1 = $request->section1;
        // $course_id1 = $request->course_id1;
        $lab_table_details = LabFirstmodel::where(['exam_name' => $examename, 'accademicYear' => $accademicYear, 'semesterType' => $semesterType])->select('id', 'course_id', 'year', 'semester', 'section', 'semesterType')->get();
        $lab_table_id = $lab_table_details->pluck('id');

        $course_id = $lab_table_details->pluck('course_id');
        $count = count($lab_table_id);
        if ($count > 0) {

            $semester = $lab_table_details->pluck('semester');
            //$course_get
            $course_get = $lab_table_details->groupBy('course_id')->map(function ($course_id) {
                $data = $course_id->pluck('course_id');
                $data[0] = ToolsCourse::where('id', $data[0])->select('name')->first()->name;
                return $data[0] ? $data[0] : null;
            });

            $year_get = $lab_table_details->groupBy('year')->map(function ($year) {
                $data = $year->pluck('year');
                if ($data[0] == '01') {
                    $data[0] = 'I';
                } elseif ($data[0] == 02) {
                    $data[0] = 'II';
                } elseif ($data[0] == 03) {
                    $data[0] = 'III';
                } elseif ($data[0] == 04) {
                    $data[0] = 'IV';
                }
                return $data[0] ? $data[0] : null;
            });

            $semester_get = $lab_table_details->groupBy('semester')->map(function ($semester) {
                $data = $semester->pluck('semester');
                return $data[0] ? $data[0] : null;
            });

            $sections_get = $lab_table_details->groupBy('section')->map(function ($section) {
                $data = $section->pluck('section');
                return $data[0] ? $data[0] : null;
            });
            // $lab_attendance_check = LabExamAttendance::whereIn('lab_exam_id',$lab_table)->get();
            // $count = count($lab_attendance_check);
        } else {
            $course_get = [];
            $year_get = [];
            $semester_get = [];
            $sections_get = [];
        }
        $query = LabExamAttendance::query();

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
        // if ($examename) {
        //     $query->Where('lab_exam_name', $examename);
        // }
        if ($count > 0) {
            $query->WhereIn('lab_exam_id', $lab_table_id);
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
        $query->orderBy('status', 'asc');
        $response = $query->select('id', 'examename', 'course', 'acyear', 'sem', 'section', 'mark_entry', 'edit_request', 'mark_date', 'mark_entereby', 'subject', 'entered_by', 'status', 'year')->get();

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
                    <input type="checkbox" data-id="' . $record->id . '" class="toggleData"  ' . ($record->mark_entry == "0" ? '' : 'checked') . ' ' . (($record->status == 1 || $record->status == 2) ? 'disabled' : '') . '/>
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
                if ($stu != null) {
                    $students = Student::where('enroll_master_id', $stu->id)->count();
                    if ($students <= 0) {
                        $students = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $stu->id)->count();
                    }
                    if ($students) {
                        $record->totalstudent = $students;
                    }

                    if ($record->mark_date) {
                        $record->mark_date = date('d-m-Y', strtotime($record->mark_date));
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
                }
                ;
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

                $viewButton = '<a class="btn btn-xs btn-primary" href="' . route('admin.lab_Exam_Mark.markview', [$stu->id, $record->id]) . '" target="_blank">View</a>';
                $viewButtonShown = false; // Variable to track if the "View" button is shown

                $buttons = '';

                if ($record->mark_entereby != null) {
                    $buttons .= $viewButton;
                    $viewButtonShown = true;
                } else {
                    $buttons .= '<a class="btn btn-xs btn-info" href="' . route('admin.lab_Exam_Mark.markEnter', [$stu->id, $record->id]) . '" target="_blank">Enter</a>';
                }
                $role_id = auth()->user()->roles[0]->id;
                if ($role_id == 40 || $role_id == 1) {
                    if ($record->mark_entereby != null) {
                        $buttons .= ' <a class="btn btn-xs btn-danger" href="' . route('admin.lab_Exam_Mark.editMark', [$stu->id, $record->id]) . '" target="_blank">Edit</a>';
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
                    // $Academicyear = AcademicYear::where('id', $request->input('accademicYear'))->select('name')->first();

                    $record->exam_name = $record->exam_name . '/' . $this->toRoman($record->year) . '/0' . $record->semester;
                }
            }
        }

        return response()->json(['data' => $response, 'course' => $course_get, 'semester' => $semester_get, 'year' => $year_get, 'sections' => $sections_get]);
    }
}
