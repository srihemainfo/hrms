<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendenceTable;
use App\Models\ClassTimeTableTwo;
use App\Models\CourseEnrollMaster;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectRegistration;
use App\Models\TeachingStaff;
use Illuminate\Http\Request;

class StudentPersonalAttController extends Controller
{
    public function report(Request $request)
    {
        if (isset($request->user_name_id)) {
            $user_name_id = $request->user_name_id;
        } else {
            $user_name_id = auth()->user()->id;
        }

        $student = Student::where('user_name_id', $user_name_id)->first();
        $year = '';
        $sem = '';
        if ($student != '') {

            $response = SubjectRegistration::where(['user_name_id' => $user_name_id, 'status' => '2', 'enroll_master' => $student->enroll_master_id])->get();

            if ($response) {
                $enroll_no = $student->enroll_master_id;
                $enroll = CourseEnrollMaster::find($enroll_no);
                if ($enroll != '') {
                    $explode = explode('/', $enroll->enroll_master_number);
                    if ($explode) {
                        $year = $explode[2];
                        $sem = $explode[3];
                    } else {
                        $year = '';
                        $sem = '';
                    }
                } else {
                    $year = '';
                    $sem = '';
                }
                foreach ($response as $responses) {

                    $subject = Subject::find($responses->subject_id);

                    if ($subject) {
                        $classTeacher = ClassTimeTableTwo::where([
                            'class_name' => $responses->enroll_master,
                            'subject' => $subject->id,
                        ])->first();

                        if ($classTeacher) {
                            $TeachingStaff = TeachingStaff::where('user_name_id', $classTeacher->staff)->first();
                        } else {
                            $TeachingStaff = null;
                        }

                        $totalHours = AttendenceTable::where([
                            'student' => $user_name_id,
                            'subject' => $responses->subject_id,
                            'enroll_master' => $responses->enroll_master,
                        ])->count();

                        $attendanceAttended = AttendenceTable::where([
                            'student' => $user_name_id,
                            'subject' => $responses->subject_id,
                            'enroll_master' => $responses->enroll_master,
                        ])->where('attendance', '!=', 'Absent')->get();

                        if ($attendanceAttended->isNotEmpty()) {
                            $totalAttended = count($attendanceAttended);
                        } else {
                            $totalAttended = 0;
                        }

                        $percentage = $totalHours !== 0 ? ($totalAttended / $totalHours) * 100 : 100;

                        $responses->subject_code = $subject->subject_code ?? '';
                        $responses->name = $subject->name ?? '';
                        $responses->classTeacher = $TeachingStaff->name ?? '';
                        $responses->totalHours = $totalHours;
                        $responses->totalAttended = $totalAttended;
                        $responses->percentage = round($percentage);
                    }
                }
                $userid = $user_name_id;

            } else {
                $response = [];
                $year = '';
                $sem = '';
                $userid = '';
            }
            // dd($response);
        } else {
            $response = [];
            $year = [];
            $sem = '';
            $userid = '';
        }
        if(isset($request->attendance_isset)){
        return response()->json(['response' => $response,'year'=> $year,'sem'=> $sem,'userid'=> $userid]);
        }
        else{

            return view('admin.student_reports.personalAttendence', compact('response', 'year', 'sem'));
        }
    }
}
