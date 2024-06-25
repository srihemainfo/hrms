<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AttendanceRecord;
use App\Models\AttendenceTable;
use App\Models\Batch;
use App\Models\CourseEnrollMaster;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectRegistration;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class student_attendence_report extends Controller
{
    public function index(Request $request)
    {
        $role_id = auth()->user()->roles[0]->id;
        $courses = ToolsCourse::pluck('short_form', 'id');
        $semester = Semester::pluck('semester', 'id');
        if ($role_id == 14) {
            $dept = auth()->user()->dept;
            if ($dept != null) {
                $getDept = ToolsDepartment::where(['name' => $dept])->select('id')->first();
                if ($getDept != '') {
                    if ($getDept->id == 5) {
                        $semester = Semester::whereIn('id', [1, 2])->pluck('semester', 'id');
                    } else {
                        $courses = ToolsCourse::where(['department_id' => $getDept->id])->pluck('short_form', 'id');
                        $semester = Semester::whereNotIn('id', [1, 2])->pluck('semester', 'id');
                    }
                }
            }
        }
        $AcademicYear = AcademicYear::pluck('name', 'id');
        $batch = Batch::pluck('name', 'id');

        return view('admin.student_reports.attendence-report', compact('batch', 'courses', 'semester', 'AcademicYear'));
    }

    public function search(Request $request)
    {
        $course = $request->input('course');
        $course_name = ToolsCourse::where('short_form', $course)->select('name')->first();
        $courseName = $course_name ? $course_name->name : '';
        $semester = $request->input('semester');
        $academicYear = $request->input('AcademicYear');

        $enrollMasterNumber = $courseName . '/' . $academicYear . '/' . $semester;

        $courseEnrollMasters = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%{$enrollMasterNumber}%")->get();

        $response = [];
        // dd($courseEnrollMasters);
        foreach ($courseEnrollMasters as $courseEnrollMaster) {
            $enrollId = $courseEnrollMaster->id;
            $students = Student::where('enroll_master_id', $enrollId)->get();
            $response = array_merge($response, $students->toArray());
        }

        return response()->json(['data' => $response]);

    }

    public function studentGet(Request $request)
    {
        $attendancePercentages = [];

        if ($request) {
            $userId = $request->input('student_name');
            $response = Student::select()->where('user_name_id', $userId)->first();

            $enrollMasterName = CourseEnrollMaster::find($response->enroll_master_id);

            $semesters = [1, 2, 3, 4, 5, 6, 7, 8]; // Generate an array with values from 1 to 8

            $enrollMasterParts = explode('/', $enrollMasterName->enroll_master_number);

            $sem = $enrollMasterParts[3];

            foreach ($semesters as $semester) {
                $totalClasses = 0;
                $attendedClasses = 0;
                $attendancePercentage = 0;
                if ($sem == $semester) {
                    $semesterResponse = AttendenceTable::where([
                        'enroll_master' => $response->enroll_master_id,
                        'student' => $userId,
                    ])->get();

                    if (count($semesterResponse) > 0) {

                        // dd($semesterResponse);
                        foreach ($semesterResponse as $data) {
                            $totalClasses += 1;

                            if ($data->attendance === 'Present') {
                                $attendedClasses += 1;
                            }
                        }
                        if ($semester == 1) {
                            dd($response->enroll_master_id);
                        }
                        $attendancePercentage1 = $totalClasses > 0 ? ($attendedClasses / $totalClasses) * 100 : 0;

                        $attendancePercentage = round($attendancePercentage1, 2);

                    }
                }

                $semesterData = [
                    'semester' => $semester,
                    'totalAttended' => $attendancePercentage . '%',
                ];
                $attendancePercentages[] = $semesterData;
            }

            return response()->json(['data' => $response, 'attendence' => $attendancePercentages]);

        }
    }

    public function tableShow(Request $request)
    {
        $newObj = [];

        if ($request->has('user_id') && $request->has('enroll_master_id') && $request->has('divId')) {

            $userId = $request->input('user_id');
            $enrollMasterId = $request->input('enroll_master_id');
            $student = Student::where('user_name_id', $userId)->first();

            if ($student) {
                $registerNo = $student->register_no;
                $enrollMasterNames = CourseEnrollMaster::find($enrollMasterId);
                // dd($enrollMasterName);
                $enrollMasterNumber = $enrollMasterNames->enroll_master_number;
                $sem = explode('-', $request->input('divId'));
                $enrollNum = explode('/', $enrollMasterNumber);
                $enrollNum[3] = $request->input('semester') ?? $sem[1];
                // dd($enrollNum);
                $newEnroll = implode('/', $enrollNum);
                // dd($newEnroll);
                //
                $enrollMasterName = CourseEnrollMaster::where('enroll_master_number', $newEnroll)->first();

                if ($enrollMasterName) {
                    $response = SubjectRegistration::where(['user_name_id' => $userId, 'status' => '2', 'enroll_master' => $enrollMasterName->id])->get();

                    if ($response) {
                        foreach ($response as $data) {
                            $subjects = Subject::find($data->subject_id);

                            if ($subjects) {
                                $attendance = AttendanceRecord::where([
                                    // 'student' => $userId,
                                    'subject' => $data->subject_id,
                                    'enroll_master' => $data->enroll_master,
                                    // 'attendance' => 'Present',
                                ])->get();
                                //                         $attendance = DB::table('attendence_tables')
                                // ->select('subject','enroll_master,', DB::raw('count(*) as total'))
                                // ->where([
                                //     // 'student' => $userId,
                                //     'subject' => $data->subject_id,
                                //     'enroll_master' => $data->enroll_master,
                                //     // 'attendance' => 'Present',
                                // ])
                                // ->groupBy('subject','enroll_master')
                                // ->get();
// dd($attendance);
                                //             $user_info = DB::table('attendence_tables')
                                //  ->select('subject', DB::raw('count(*) as total'))
                                //  ->groupBy('browser')
                                //  ->get();

                                $totalHourSs = $attendance->isNotEmpty() ? count($attendance) : 0;

                                $attendanceAttended = DB::table('attendence_tables')->where([
                                    'student' => $userId,
                                    'subject' => $data->subject_id,
                                    'enroll_master' => $data->enroll_master,
                                    'attendance' => 'Present',
                                ])->get();

                                $totalAttended = $attendanceAttended->isNotEmpty() ? count($attendanceAttended) : 0;

                                $totalHours = $totalHourSs;
                                $percentageTotal = $totalHours > 0 ? ($totalAttended / $totalHours) * 100 : 0;
                                $percentageAttended = $totalHours > 0 ? ($totalAttended / $totalHours) * 100 : 0;

                                $newObj[] = [
                                    'subjectName' => $subjects->name,
                                    'count' => $totalHourSs,
                                    'attended' => $totalAttended,
                                    'percentageTotal' => round($percentageTotal),
                                    'percentageAttended' => $percentageAttended == 0 ? 0 : round($percentageAttended) . '%',
                                    'subject_code' => $subjects->subject_code != '' ? $subjects->subject_code : '',
                                ];
                            } else {
                                $newObj[] = [];
                            }
                        }
                    } else {
                        $newObj[] = [];
                    }

                    return response()->json(['data' => $request, 'div_id' => $request->input('divId'), 'subjects' => $newObj]);
                }
            }
        }

        return response()->json(['error' => 'Invalid request', 'div_id' => $request->input('divId'), 'subjects' => $newObj]);
    }

}
