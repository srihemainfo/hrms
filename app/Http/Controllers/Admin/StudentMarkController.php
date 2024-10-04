<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ToolsCourse;
use App\Models\StudentMarks;
use Illuminate\Http\Request;
use App\Models\TeachingStaff;
use App\Models\ClassTimeTableTwo;
use App\Models\CourseEnrollMaster;
use App\Http\Controllers\Controller;
use App\Models\StudentPromotionHistory;

class StudentMarkController extends Controller
{
    public function index(Request $request)
    {

        $user_name_id = auth()->user()->id;

        $staff = TeachingStaff::where(['user_name_id' => $user_name_id])->select('id')->first();
        $subjects = [];
        // first period
        $timetable = ClassTimeTableTwo::where(['status' => 1, 'staff' => $staff->id])->groupBy('class_name', 'subject')->select('subject', 'class_name')->get();

        if (count($timetable) > 0) {
            foreach ($timetable as $data) {
                if (!in_array([$data->subject, $data->class_name], $subjects)) {
                    array_push($subjects, [$data->subject, $data->class_name]);
                }
            }
        }

        $got_subjects = [];
        for ($i = 0; $i < count($subjects); $i++) {
            // dd($subjects[$i][1]);
            $get_enroll = CourseEnrollMaster::where(['id' => $subjects[$i][1]])->first();
            $get_subjects = Subject::where(['id' => $subjects[$i][0]])->first();
            $get_course = explode('/', $get_enroll->enroll_master_number);
            $get_short_form = ToolsCourse::where('name', $get_course[1])->select('short_form')->first();

            if ($get_short_form) {
                $get_course[1] = $get_short_form->short_form;
                $subjects[$i][1] = $get_course[1] . ' / ' . $get_course[3] . ' / ' . $get_course[4];
            }
            if ($get_subjects) {
                // dd($get_subjects->name);
                array_push($got_subjects, [$get_subjects->id, $subjects[$i][1], $get_enroll->id]);
            }

        }

        $subjects = Subject::pluck('name', 'id');
        $enroll = CourseEnrollMaster::pluck('enroll_master_number', 'id');
        // dd($enroll);
        // $teaching_staffs = TeachingStaff::pluck('name', 'id')->prepend('-', '');
        return view('admin.staffAcademicManage.studentmark', compact('got_subjects', 'subjects', 'enroll'));
    }

    public function get_students(Request $request)
    {
        if (isset($request->class) && isset($request->subject) && isset($request->exam) && isset($request->short_name)) {

            // dd($request->class,$request->subject,$request->exam);
            // $get_enroll = CourseEnrollMaster::where(['id' => $request->class])->select('enroll_master_number')->first();
            $get_marks = StudentMarks::where(['class' => $request->class, 'exam' => $request->exam, 'subject' => $request->subject])->select(['user_name_id', 'marks'])->get();
            $get_students = Student::where(['enroll_master_id' => $request->class])->select(['name', 'roll_no', 'user_name_id'])->get();
            if ($get_students <= 0) {
                $get_students = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $request->class)->select('students.name', 'students.roll_no', 'students.user_name_id')->get();
            }
            $students = [];
            if (count($get_marks) > 0) {
                foreach ($get_students as $data) {
                    foreach ($get_marks as $mark) {
                        if ($mark->user_name_id == $data->user_name_id) {
                            array_push($students, ['user_name_id' => $data->user_name_id, 'roll_no' => $data->roll_no, 'name' => $data->name, 'mark' => $mark->marks]);
                        }
                    }
                }
            } else {
                foreach ($get_students as $data) {
                    array_push($students, ['user_name_id' => $data->user_name_id, 'roll_no' => $data->roll_no, 'name' => $data->name, 'mark' => null]);
                }
            }

            $subjects = Subject::pluck('name', 'id');
            // $enroll_master = CourseEnrollMaster::pluck('enroll_master_number', 'id');

            if (count($subjects) > 0) {
                foreach ($subjects as $id => $entry) {
                    if ($id == $request->subject) {
                        $subject = $entry;
                    }
                }
            } else {
                $subject = null;
            }

            //             if (count($enroll_master) > 0) {
            //                 foreach ($enroll_master as $id => $entry) {
            //                     if ($id == $request->class) {
            //                         $enroll = $entry;
            //                     }
            //                 }
            //             }else{
            //                 $enroll = null;
            //             }
            // dd($enroll);
            return response()->json(['students' => $students, 'subject_id' => $request->subject, 'subject' => $subject, 'exam' => $request->exam, 'class' => $request->short_name, 'enroll' => $request->class]);
        }
    }

    public function store(Request $request)
    {
        // dd(Carbon::now());
        if (isset($request->data) && count($request->data) > 0) {
            foreach ($request->data as $store_mark) {
                $check = StudentMarks::where(['user_name_id' => $store_mark[3]['value'], 'class' => $store_mark[2]['value'], 'subject' => $store_mark[1]['value'], 'exam' => $store_mark[0]['value']])->get();

                if (count($check) > 0) {

                    $update = StudentMarks::where(['user_name_id' => $store_mark[3]['value'], 'class' => $store_mark[2]['value'], 'subject' => $store_mark[1]['value'], 'exam' => $store_mark[0]['value']])->update([
                        'marks' => $store_mark[4]['value'],
                        'updated_by' => auth()->user()->name,
                        'updated_at' => Carbon::now(),
                    ]);

                } else {
                    $mark = new StudentMarks;
                    $mark->exam = $store_mark[0]['value'];
                    $mark->subject = $store_mark[1]['value'];
                    $mark->class = $store_mark[2]['value'];
                    $mark->user_name_id = $store_mark[3]['value'];
                    $mark->updated_by = auth()->user()->name;
                    $mark->marks = $store_mark[4]['value'];
                    $mark->created_at = Carbon::now();
                    $mark->save();
                }
            }
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }

    public function show(Request $request)
    {
        if (isset($request->class) && isset($request->exam) && isset($request->subject) && isset($request->short_form)) {
            // dd($request->class,$request->exam,$request->subject);
            $get_marks = StudentMarks::where(['class' => $request->class, 'exam' => $request->exam, 'subject' => $request->subject])->select(['user_name_id', 'marks'])->get();

            $marks = [];
            if (count($get_marks) > 0) {

                foreach ($get_marks as $data) {

                    $get_student = Student::where(['user_name_id' => $data->user_name_id])->first();

                    if ($get_student != '') {
                        array_push($marks, ['name' => $get_student->name, 'roll_no' => $get_student->roll_no, 'mark' => $data->marks]);
                    }
                }
            }
            $get_subject = Subject::where(['id' => $request->subject])->first();

            if ($get_subject != '') {

                $subject = $get_subject->name;
            } else {
                $subject = null;
            }
            $class = $request->short_form;
            $exam = $request->exam;

            return view('admin.staffAcademicManage.studentmarkshow', compact('marks', 'class', 'exam', 'subject'));
        }
    }
}
