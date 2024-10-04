<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AttendanceRecord;
use App\Models\AttendenceTable;
use App\Models\BulkOD;
use App\Models\ClassTimeTableTwo;
use App\Models\CourseEnrollMaster;
use App\Models\Examattendance;
use App\Models\ExamTimetableCreation;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentPromotionHistory;
use App\Models\Subject;
use App\Models\SubjectAllotment;
use App\Models\TeachingStaff;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;

ini_set('max_execution_time', 3600);
class AttendanceReportController extends Controller
{
    public function subjectAttendance(Request $request)
    {
        $academic_years = AcademicYear::pluck('name', 'id');
        $role_id = auth()->user()->roles[0]->id;
        $dept = auth()->user()->dept;
        if ($role_id == 14 && $dept != null) {
            if ($dept == 'S & H') {
                $courses = ToolsCourse::pluck('short_form', 'id');
                $semesters = [1, 2];
            } else {
                $getDept = ToolsDepartment::where(['name' => $dept])->select('id')->first();
                if ($getDept != '') {
                    $courses = ToolsCourse::where(['department_id' => $getDept->id])->pluck('short_form', 'id');
                    $semesters = [3, 4, 5, 6, 7, 8];
                } else {
                    $courses = ToolsCourse::pluck('short_form', 'id');
                    $semesters = [1, 2, 3, 4, 5, 6, 7, 8];
                }
            }
        } else {
            $courses = ToolsCourse::pluck('short_form', 'id');
            $semesters = [1, 2, 3, 4, 5, 6, 7, 8];
        }

        return view('admin.variousReports.subjectAttendance', compact('academic_years', 'courses', 'semesters'));
    }

    public function getDetails(Request $request)
    {
        $course = $request->course;
        $ay = $request->ay;
        $semester = $request->semester;
        $subjects = [];

        $sections = Section::where(['course_id' => $request->course])->select('id', 'section')->get();
        $get_subjects = SubjectAllotment::where(['course' => $request->course, 'academic_year' => $request->ay, 'semester' => $request->semester])->select('subject_id')->get();
        if (count($get_subjects) > 0) {
            foreach ($get_subjects as $subject) {
                $got_subjects = Subject::where(['id' => $subject->subject_id])->select('id', 'name', 'subject_code')->first();
                if ($got_subjects != '') {
                    array_push($subjects, $got_subjects);
                }
            }
        }
        return response()->json(['sections' => $sections, 'subjects' => $subjects]);
    }

    public function getStaff(Request $request)
    {
        $course = $request->course;
        $ay = $request->ay;
        $semester = $request->semester;
        $section = $request->section;
        $subject = $request->subject;
        $get_staff = '';

        $get_course = ToolsCourse::where(['id' => $course])->select('name')->first();
        if ($get_course != '') {
            $course_name = $get_course->name;
        } else {
            $course_name = null;
        }

        $get_section = Section::where(['id' => $section])->select('section')->first();
        if ($get_section != '') {
            $section_name = $get_section->section;
        } else {
            $section_name = null;
        }

        $get_ay = AcademicYear::where(['id' => $ay])->select('name')->first();

        if ($get_ay != '') {
            $ay_name = $get_ay->name;
        } else {
            $ay_name = null;
        }

        if ($get_ay != null && $course_name != null && $section_name != null) {
            $make_enroll = $course_name . '/' . $ay_name . '/' . $semester . '/' . $section_name;
            $get_enroll = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%{$make_enroll}")->first();
            if ($get_enroll != '') {
                $get_timetable = ClassTimeTableTwo::where(['class_name' => $get_enroll->id, 'subject' => $subject, 'status' => 1])->select('staff')->first();
                if ($get_timetable != '') {
                    $get_staff = TeachingStaff::where(['user_name_id' => $get_timetable->staff])->select('name', 'user_name_id', 'StaffCode')->first();
                    if ($get_staff == '') {
                        return response()->json(['status' => false, 'data' => 'Staff Not Found']);
                    }
                    return response()->json(['status' => true, 'data' => $get_staff]);

                } else {
                    return response()->json(['status' => false, 'data' => 'No Staff Assigned']);
                }
            } else {
                return response()->json(['status' => false, 'data' => 'Class Not Found']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Given Datas Invalid']);
        }
    }

    public function getReport(Request $request)
    {
        $course = $request->course;
        $ay = $request->ay;
        $semester = $request->semester;
        $section = $request->section;
        $subject = $request->subject;
        $staff = $request->staff;
        $list = [];

        $get_course = ToolsCourse::where(['id' => $course])->select('name')->first();
        if ($get_course != '') {
            $course_name = $get_course->name;
        } else {
            $course_name = null;
        }

        $get_section = Section::where(['id' => $section])->select('section')->first();
        if ($get_section != '') {
            $section_name = $get_section->section;
        } else {
            $section_name = null;
        }

        $get_ay = AcademicYear::where(['id' => $ay])->select('name')->first();

        if ($get_ay != '') {
            $ay_name = $get_ay->name;
        } else {
            $ay_name = null;
        }

        if ($get_ay != null && $course_name != null && $section_name != null) {
            $make_enroll = $course_name . '/' . $ay_name . '/' . $semester . '/' . $section_name;
            $get_enroll = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%{$make_enroll}")->first();
            if ($get_enroll != '') {
                $get_time_table = ClassTimeTableTwo::where(['class_name' => $get_enroll->id, 'subject' => $subject, 'status' => 1])->select('staff')->first();
                if ($get_time_table != '') {

                    $get_students = DB::table('students')
                        ->join('subject_registration', 'students.user_name_id', '=', 'subject_registration.user_name_id')
                        ->whereNull('subject_registration.deleted_at')
                        ->whereNull('students.deleted_at')
                        ->where('students.enroll_master_id', $get_enroll->id)
                        ->where('subject_registration.status', 2)
                        ->where('subject_registration.subject_id', $subject)
                        ->select('students.name', 'students.user_name_id', 'students.register_no', 'students.enroll_master_id')
                        ->get();

                    if (count($get_students) <= 0) {
                        $get_students = DB::table('student_promotion_history')->join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->leftJoin('subject_registration', 'student_promotion_history.user_name_id', '=', 'subject_registration.user_name_id')->where('student_promotion_history.enroll_master_id', $get_enroll->id)->whereNull('subject_registration.deleted_at')->whereNull('students.deleted_at')->where('subject_registration.status', 2)->where('subject_registration.subject_id', $subject)->select('students.name', 'students.user_name_id', 'students.register_no', 'student_promotion_history.enroll_master_id')->get();
                    }

                    if (count($get_students) > 0) {
                        $got_data = [];
                        $getAttendance = AttendenceTable::where(['enroll_master' => $get_enroll->id, 'subject' => $subject])->select('student', 'attendance')->get();
                        if (count($getAttendance) > 0) {
                            foreach ($getAttendance as $att) {
                                if (array_key_exists($att->student, $got_data)) {
                                    $count = $got_data[$att->student]['count'];
                                    $got_data[$att->student]['count'] = $count + 1;
                                    if ($att->attendance != 'Absent') {
                                        $present = isset($got_data[$att->student]['present']) ? $got_data[$att->student]['present'] : 0;
                                        $got_data[$att->student]['present'] = $present + 1;
                                    }
                                } else {
                                    $got_data[$att->student] = [];
                                    $got_data[$att->student]['count'] = 1;
                                    if ($att->attendance != 'Absent') {
                                        $got_data[$att->student]['present'] = 1;
                                    }

                                }
                            }
                        }

                        foreach ($get_students as $student) {
                            if (array_key_exists($student->user_name_id, $got_data)) {
                                $count_of_attendance = $got_data[$student->user_name_id]['count'];
                                $attendance = $got_data[$student->user_name_id]['present'];
                                if ($count_of_attendance > 0) {
                                    $percentage = round(($attendance / $count_of_attendance) * 100);
                                } else {
                                    $percentage = 0;
                                }
                                array_push($list, ['registration' => true, 'class' => $get_enroll->id, 'name' => $student->name, 'register_no' => $student->register_no, 'user_name_id' => $student->user_name_id, 'total_hours' => $count_of_attendance, 'attend_hours' => $attendance, 'percentage' => $percentage]);
                            }

                        }
                    }
                    return response()->json(['status' => true, 'data' => $list]);
                } else {
                    return response()->json(['status' => false, 'data' => 'Class Time Table Not Found']);
                }
            } else {
                return response()->json(['status' => false, 'data' => 'Class Not Found']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Given Datas Invalid']);
        }

    }

    public function show(Request $request)
    {

        if (isset($request->user_name_id) && $request->user_name_id != '') {
            $theArray = [];
            $get_enroll = CourseEnrollMaster::where(['id' => $request->class])->first();
            $user_name_id = $request->user_name_id;
            $leave_req = DB::table('student_leave_apply')->where(['user_name_id' => $user_name_id, 'status' => 3])->select('from_date', 'to_date', 'leave_type')->whereNull('deleted_at')->get();
            $check_bulk_od = BulkOD::where(['user_name_id' => $user_name_id, 'status' => 1])->whereNull('deleted_at')->select('from_date', 'to_date')->get();
            $student = Student::where(['user_name_id' => $user_name_id])->first();
            $getAttedanceRecord = AttendenceTable::where(['student' => $request->user_name_id, 'enroll_master' => $request->class, 'attendance' => 'Absent', 'subject' => $request->subject])->groupby('date')->select('date')->get();

            if (count($getAttedanceRecord) > 0) {

                $removeindex = [];
                foreach ($leave_req as $record) {
                    $dummyArray = ['from_date' => $record->from_date, 'to_date' => $record->to_date, 'leave_type' => $record->leave_type];
                    array_push($theArray, $dummyArray);
                }
                $getBulkOdArray = $check_bulk_od->toArray();
                foreach ($getBulkOdArray as $record) {
                    $record['leave_type'] = 'Institute OD';
                    array_push($theArray, $record);
                }

                foreach ($getAttedanceRecord as $i => $record) {
                    if (count($leave_req) > 0) {
                        foreach ($leave_req as $req) {
                            $date = Carbon::parse($record->date);
                            $fromDate = Carbon::parse($req->from_date);
                            $toDate = Carbon::parse($req->to_date);

                            if ($date->eq($fromDate) || $date->eq($toDate) || ($date->gt($fromDate) && $date->lt($toDate))) {
                                $removeindex[] = $i;
                            }
                        }
                    }
                    if (!in_array($i, $removeindex)) {
                        if (count($check_bulk_od) > 0) {
                            foreach ($check_bulk_od as $req) {
                                $date = Carbon::parse($record->date);
                                $fromDate = Carbon::parse($req->from_date);
                                $toDate = Carbon::parse($req->to_date);

                                if ($date->eq($fromDate) || $date->eq($toDate) || ($date->gt($fromDate) && $date->lt($toDate))) {
                                    $removeindex[] = $i;
                                }
                            }
                        }
                    }
                }
                $getAttedanceRecordArray = $getAttedanceRecord->toArray();
                foreach ($removeindex as $index) {
                    unset($getAttedanceRecordArray[$index]);
                }
                $getAttedanceRecord = array_values($getAttedanceRecordArray);
                foreach ($getAttedanceRecord as $record) {
                    $record['leave_type'] = 'Absent';
                    $record['from_date'] = $record['date'];
                    $record['to_date'] = $record['date'];
                    array_push($theArray, $record);
                }
            }
            usort($theArray, function ($a, $b) {

                if (isset($a['from_date']) && isset($b['from_date'])) {
                    return strtotime($b['from_date']) - strtotime($a['from_date']);
                }
            });
            return view('admin.variousReports.attendanceView', compact('get_enroll', 'student', 'theArray'));
        }
    }

    public function classAttendance(Request $request)
    {
        $academic_years = AcademicYear::pluck('name', 'id');
        $role_id = auth()->user()->roles[0]->id;
        $dept = auth()->user()->dept;
        if ($role_id == 14 && $dept != null) {
            if ($dept == 'S & H') {
                $courses = ToolsCourse::pluck('short_form', 'id');
                $semesters = [1, 2];
            } else {
                $getDept = ToolsDepartment::where(['name' => $dept])->select('id')->first();
                if ($getDept != '') {
                    $courses = ToolsCourse::where(['department_id' => $getDept->id])->pluck('short_form', 'id');
                    $semesters = [3, 4, 5, 6, 7, 8];
                } else {
                    $courses = ToolsCourse::pluck('short_form', 'id');
                    $semesters = [1, 2, 3, 4, 5, 6, 7, 8];
                }
            }
        } else {
            $courses = ToolsCourse::pluck('short_form', 'id');
            $semesters = [1, 2, 3, 4, 5, 6, 7, 8];
        }

        return view('admin.variousReports.classAttendance', compact('academic_years', 'courses', 'semesters'));
    }

    public function getClassReport(Request $request)
    {
        $course = $request->course;
        $ay = $request->ay;
        $semester = $request->semester;
        $section = $request->section;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $courseInfo = ToolsCourse::find($course);
        $sectionInfo = Section::find($section);
        $ayInfo = AcademicYear::find($ay);

        if (!$courseInfo || !$sectionInfo || !$ayInfo) {
            return response()->json(['status' => false, 'data' => 'Given Data Invalid']);
        }

        $enrollMasterNumber = "{$courseInfo->name}/{$ayInfo->name}/{$semester}/{$sectionInfo->section}";
        $enrollMaster = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%{$enrollMasterNumber}")->first();

        if (!$enrollMaster) {
            return response()->json(['status' => false, 'data' => 'Class Not Found']);
        }

        $subjectAllotments = SubjectAllotment::where(['academic_year' => $ay, 'course' => $course, 'semester' => $semester])
            ->orderBy('subject_id', 'asc')
            ->get();

        if ($subjectAllotments->isEmpty()) {
            return response()->json(['status' => false, 'data' => 'Allotted Subjects Not Found']);
        }

        $subjectList = [];
        $subjectIds = []; // Create an array to store subject IDs

        foreach ($subjectAllotments as $subjectAllotment) {
            $subject = Subject::find($subjectAllotment->subject_id);

            if ($subject) {
                $subjectName = $subject->name . '<br>   ( ' . $subject->subject_code . '  )';
                $subjectId = $subject->id;
            } else {
                $subjectName = '';
                $subjectId = $subjectAllotment->subject_id;
            }

            $subjectList[] = ['subject_name' => $subjectName, 'subject_id' => $subjectId];
            $subjectIds[] = $subjectId; // Store subject ID in the array
        }

        $subjectList[] = ['subject_name' => 'Library', 'subject_id' => 'Library'];

        $students = Student::where(['enroll_master_id' => $enrollMaster->id])
            ->select('register_no', 'user_name_id', 'name')
            ->get();
        if (count($students) <= 0) {
            $students = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $enrollMaster->id)->select('students.name', 'students.user_name_id', 'students.register_no')->get();
        }
        if ($students->isEmpty()) {
            return response()->json(['status' => false, 'data' => 'Students Not Found']);
        }

        // Fetch attendance data for all subjects at once using subjectIds
        $attendanceData = AttendenceTable::whereBetween('date', [$from_date, $to_date])
            ->where('enroll_master', $enrollMaster->id)
            ->whereIn('subject', $subjectIds) // Use subjectIds array here
            ->selectRaw('subject, student, COUNT(*) as count, SUM(CASE WHEN attendance != "Absent" THEN 1 ELSE 0 END) as attendance')
            ->groupBy('subject', 'student')
            ->get();

        foreach ($students as $student) {
            $studentData = [];

            foreach ($subjectList as $subject) {
                $subjectId = $subject['subject_id'];
                $attendanceSubject = $subjectId == 'Library' ? 'Library' : $subjectId;

                // Find the attendance data for the current subject and student
                $studentAttendance = $attendanceData->where('subject', $attendanceSubject)
                    ->where('student', $student->user_name_id)
                    ->first();

                if ($studentAttendance) {
                    $countOfAttendance = $studentAttendance->count;
                    $attendance = $studentAttendance->attendance;
                } else {
                    $countOfAttendance = 0;
                    $attendance = 0;
                }

                $percentage = $countOfAttendance > 0 ? round(($attendance / $countOfAttendance) * 100) : 0;

                $studentData[] = [
                    'registration' => true,
                    'subject_id' => $subjectId,
                    'name' => $student->name,
                    'register_no' => $student->register_no,
                    'attend_hours' => $attendance,
                    'percentage' => $percentage,
                    'total_hours' => $countOfAttendance,
                ];
            }

            $studentList[] = $studentData;
        }

        return response()->json(['status' => true, 'data' => $studentList, 'subject_list' => $subjectList]);
    }

    public function weeklyReportIndex(Request $request)
    {

        $academic_years = AcademicYear::pluck('name', 'id');

        $role_id = auth()->user()->roles[0]->id;

        $dept = auth()->user()->dept;
        if ($role_id == 14 && $dept != null) {
            $departments = ToolsDepartment::where(['name' => $dept])->pluck('name', 'id');
            if ($dept == 'S & H') {
                $semesters = [1, 2];
            } else {
                $semesters = [3, 4, 5, 6, 7, 8];
            }
        } else {
            $departments = ToolsDepartment::pluck('name', 'id');
            $semesters = [1, 2, 3, 4, 5, 6, 7, 8];
        }

        return view('admin.variousReports.weeklyReport', compact('departments', 'academic_years', 'semesters'));
    }

    public function weeklyReport(Request $request)
    {

        $department = $request->department;
        $course = $request->course;
        $ay = $request->ay;
        $semester = $request->semester;
        $section = $request->section;
        $week = $request->week;
        $explodeWeek = explode('-', $week);
        $weekNumber = substr($explodeWeek[1], 1);
        $year = $explodeWeek[0];

        if ($semester == '1' || $semester == '2') {
            $gotYear = '01';
        } elseif ($semester == '3' || $semester == '4') {
            $gotYear = '02';
        } elseif ($semester == '5' || $semester == '6') {
            $gotYear = '03';
        } else {
            $gotYear = '04';
        }

        $firstDayOfWeek = Carbon::now()->setISODate($year, $weekNumber, 0)->startOfDay();

        $lastDayOfWeek = Carbon::now()->setISODate($year, $weekNumber, 6)->endOfDay();

        $firstDay = $firstDayOfWeek->format('Y-m-d');
        $lastDay = $lastDayOfWeek->format('Y-m-d');

        $first_day = Carbon::parse($firstDayOfWeek);
        $last_day = Carbon::parse($lastDayOfWeek);

        $datesInRange = [];

        while ($first_day->lte($last_day)) {
            $datesInRange[] = $first_day->format('d-m-Y');
            $first_day->addDay();
        }

        $get_course = ToolsCourse::where(['id' => $course])->select('name')->first();
        if ($get_course != '') {
            $course_name = $get_course->name;
        } else {
            $course_name = null;
        }

        $get_section = Section::where(['id' => $section])->select('section')->first();
        if ($get_section != '') {
            $section_name = $get_section->section;
        } else {
            $section_name = null;
        }

        $get_ay = AcademicYear::where(['id' => $ay])->select('name')->first();

        if ($get_ay != '') {
            $ay_name = $get_ay->name;

        } else {
            $ay_name = null;
        }

        $one = [];
        $two = [];
        $three = [];
        $four = [];
        $five = [];
        $six = [];
        $seven = [];

        $examperiods = [];

        if ($get_ay != null && $course_name != null && $section_name != null) {
            $make_enroll = $course_name . '/' . $ay_name . '/' . $semester . '/' . $section_name;
            $get_enroll = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%{$make_enroll}")->first();
            if ($get_enroll != '') {
                $attendanceRecord = AttendanceRecord::with(['subjects', 'staffs'])->whereBetween('actual_date', [$firstDay, $lastDay])->where(['enroll_master' => $get_enroll->id])->select('actual_date', 'subject', 'staff', 'period')->get();
                if (count($attendanceRecord) > 0) {
                    foreach ($attendanceRecord as $record) {
                        $getDate = $record->actual_date;
                        $date = Carbon::parse($getDate);
                        $record->day = strtoupper($date->format('l'));
                        $record->exam = false;

                        if (isset($one[$record->day])) {
                            if ($record->period == 1) {
                                array_push($one[$record->day], $record);
                            }
                        } else {
                            $one[$record->day] = [];
                            if ($record->period == 1) {
                                array_push($one[$record->day], $record);
                            }
                        }

                        if (isset($two[$record->day])) {
                            if ($record->period == 2) {
                                array_push($two[$record->day], $record);
                            }
                        } else {
                            $two[$record->day] = [];
                            if ($record->period == 2) {
                                array_push($two[$record->day], $record);
                            }
                        }
                        if (isset($three[$record->day])) {
                            if ($record->period == 3) {
                                array_push($three[$record->day], $record);
                            }
                        } else {
                            $three[$record->day] = [];
                            if ($record->period == 3) {
                                array_push($three[$record->day], $record);
                            }
                        }
                        if (isset($four[$record->day])) {
                            if ($record->period == 4) {
                                array_push($four[$record->day], $record);
                            }
                        } else {
                            $four[$record->day] = [];
                            if ($record->period == 4) {
                                array_push($four[$record->day], $record);
                            }
                        }
                        if (isset($five[$record->day])) {
                            if ($record->period == 5) {
                                array_push($five[$record->day], $record);
                            }
                        } else {
                            $five[$record->day] = [];
                            if ($record->period == 5) {
                                array_push($five[$record->day], $record);
                            }
                        }
                        if (isset($six[$record->day])) {
                            if ($record->period == 6) {
                                array_push($six[$record->day], $record);
                            }
                        } else {
                            $six[$record->day] = [];
                            if ($record->period == 6) {
                                array_push($six[$record->day], $record);
                            }
                        }
                        if (isset($seven[$record->day])) {
                            if ($record->period == 7) {
                                array_push($seven[$record->day], $record);
                            }
                        } else {
                            $seven[$record->day] = [];
                            if ($record->period == 7) {
                                array_push($seven[$record->day], $record);
                            }
                        }
                    }
                }

                $check_examattendance = Examattendance::whereBetween('date', [$firstDay, $lastDay])->where(['year' => $gotYear, 'course' => $course, 'sem' => $semester, 'section' => $section_name, 'acyear' => $ay])->groupBy('exame_id', 'date')->select('exame_id', 'date')->get();
                if (count($check_examattendance) > 0) {
                    foreach ($check_examattendance as $record) {
                        $getDate = $record->date;
                        $date = Carbon::parse($getDate);
                        $record->day = strtoupper($date->format('l'));
                        $record->exam = true;

                        $check_examCreation = ExamTimetableCreation::where(['id' => $record->exame_id])->select('start_time', 'end_time', 'exam_name')->first();
                        if ($check_examCreation != '') {
                            $record->exam_name = $check_examCreation->exam_name;
                            $start_time = substr($check_examCreation->start_time, 0, -3);
                            $end_time = substr($check_examCreation->end_time, 0, -3);
                            if ($start_time >= '8:00' && $end_time <= '9:00') {
                                array_push($examperiods, 1);
                            } elseif ($start_time >= '8:00' && $end_time <= '10:00') {
                                array_push($examperiods, 1, 2);
                            } elseif ($start_time >= '8:00' && ($end_time <= '12:00')) {
                                array_push($examperiods, 1, 2, 3, 4);
                            } elseif ($start_time >= '9:00' && $end_time <= '10:00') {
                                array_push($examperiods, 2);
                            } elseif ($start_time >= '9:00' && $end_time <= '12:00') {
                                array_push($examperiods, 2, 3, 4);
                            } elseif ($start_time >= '10:00' && ($end_time <= '12:00')) {
                                array_push($examperiods, 3, 4);
                            } elseif ($start_time >= '12:00' && ($end_time <= '1:20')) {
                                array_push($examperiods, 5);
                            } elseif ($start_time >= '1:20' && ($end_time <= '2:10')) {
                                array_push($examperiods, 6);
                            } elseif ($start_time >= '12:00' && ($end_time <= '2:10')) {
                                array_push($examperiods, 5, 6);
                            } elseif ($start_time >= '12:00' && ($end_time <= '3:00')) {
                                array_push($examperiods, 5, 6, 7);
                            }

                            if (count($examperiods) > 0) {
                                foreach ($examperiods as $period) {

                                    if ($period == 1) {
                                        if (isset($one[$record->day])) {
                                            array_push($one[$record->day], $record);
                                        } else {
                                            $one[$record->day] = [];
                                            array_push($one[$record->day], $record);
                                        }
                                    } else if ($period == 2) {
                                        if (isset($two[$record->day])) {
                                            array_push($two[$record->day], $record);
                                        } else {
                                            $two[$record->day] = [];
                                            array_push($two[$record->day], $record);
                                        }
                                    } else if ($period == 3) {
                                        if (isset($three[$record->day])) {
                                            array_push($three[$record->day], $record);
                                        } else {
                                            $three[$record->day] = [];
                                            array_push($three[$record->day], $record);
                                        }
                                    } else if ($period == 4) {
                                        if (isset($four[$record->day])) {
                                            array_push($four[$record->day], $record);
                                        } else {
                                            $four[$record->day] = [];
                                            array_push($four[$record->day], $record);
                                        }
                                    } else if ($period == 5) {
                                        if (isset($five[$record->day])) {
                                            array_push($five[$record->day], $record);
                                        } else {
                                            $five[$record->day] = [];
                                            array_push($five[$record->day], $record);
                                        }
                                    } else if ($period == 6) {
                                        if (isset($six[$record->day])) {
                                            array_push($six[$record->day], $record);
                                        } else {
                                            $six[$record->day] = [];
                                            array_push($six[$record->day], $record);
                                        }
                                    } else if ($period == 7) {
                                        if (isset($seven[$record->day])) {
                                            array_push($seven[$record->day], $record);
                                        } else {
                                            $seven[$record->day] = [];
                                            array_push($seven[$record->day], $record);
                                        }
                                    }

                                }
                            }

                        }
                        $examperiods = [];
                    }
                }
                return response()->json(['status' => true, 'one' => $one, 'two' => $two, 'three' => $three, 'four' => $four, 'five' => $five, 'six' => $six, 'seven' => $seven, 'dates' => $datesInRange]);
            } else {
                return response()->json(['status' => false, 'data' => 'Class Not Found']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Given Datas Invalid']);
        }
    }

    // public function teacherSubject(Request $request)
    // {

    //     $clsData = CourseEnrollMaster::find($request->enrollID);
    //     $subjects = [];
    //     $sections = [];
    //     if ($clsData) {

    //         $subjects = ClassTimeTableTwo::select(['staff', 'status', 'subject'])->where(['staff' => auth()->user()->id, 'class_name' => $request->enrollID, 'status' => 1])->groupBy(['staff', 'status', 'subject'])->get();
    //         if ($subjects) {
    //             foreach ($subjects as $Class) {

    //                 $subjectArray = Subject::find($Class->subject);

    //                 if ($subjectArray) {
    //                     $Class->name = ($subjectArray->name ?? '') . '(' . ($subjectArray->subject_code ?? '') . ')';
    //                     $Class->id = $Class->subject;
    //                 }
    //             }
    //         }

    //     }

    //     return response()->json(['sections' => $sections, 'subjects' => $subjects]);
    // }

    // public function teacher(Request $request)
    // {
    //     $classID = $request->saffclass;
    //     $subject = $request->subject;
    //     $list = [];
    //     if ($classID != null && $subject != null) {
    //         $get_enroll = CourseEnrollMaster::find($classID);
    //         if ($get_enroll != '') {
    //             $get_time_table = ClassTimeTableTwo::where(['class_name' => $get_enroll->id, 'subject' => $subject, 'status' => 1])->select('staff')->first();
    //             if ($get_time_table != '') {
    //                 $allocated_students = StudentPeriodAllocate::where(['class' => $get_enroll->id, 'subject' => $subject])->groupby('student')->select('student')->get();
    //                 if (count($allocated_students) > 0) {
    //                     $got_students = [];
    //                     foreach ($allocated_students as $students) {
    //                         $get_period_in_allot = StudentPeriodAllocate::where(['class' => $get_enroll->id, 'subject' => $subject, 'student' => $students->student])->groupby('period')->select('period')->get();

    //                         $get_students = Student::where(['user_name_id' => $students->student])->select('name', 'user_name_id', 'register_no', 'enroll_master_id')->first();
    //                         if ($get_students != '') {
    //                             $get_students->periods = $get_period_in_allot;

    //                             array_push($got_students, $get_students);
    //                         }
    //                     }
    //                     $count_of_attendance = 0;
    //                     $attendance = 0;
    //                     foreach ($got_students as $student) {
    //                         if (count($student->periods) > 0) {
    //                             foreach ($student->periods as $period) {

    //                                 $get_attendance_count = AttendenceTable::select(['subject', 'enroll_master', 'date', 'period'])->where([
    //                                     'subject' => $subject,
    //                                     'enroll_master' => $get_enroll->id,
    //                                 ])->groupBy('subject', 'enroll_master', 'date', 'period')->get();

    //                                 if ($get_attendance_count->isNotEmpty()) {

    //                                     $count_of_attendance = count($get_attendance_count);
    //                                 } else {
    //                                     $count_of_attendance = 0;
    //                                 }
    //                                 $attendanceAttended = AttendenceTable::select(['attendance', 'student', 'subject', 'enroll_master', 'date', 'period'])->where([
    //                                     'student' => $student->user_name_id,
    //                                     'subject' => $subject,
    //                                     'enroll_master' => $get_enroll->id,
    //                                     'attendance' => 'Present',
    //                                 ])->groupBy(['attendance', 'student', 'subject', 'enroll_master', 'date', 'period'])->get();

    //                                 if ($attendanceAttended->isNotEmpty()) {
    //                                     $attendance = count($attendanceAttended);
    //                                 } else {
    //                                     $attendance = 0;
    //                                 }

    //                             }

    //                         }
    //                         if ($count_of_attendance > 0) {
    //                             $percentage = round(($attendance / $count_of_attendance) * 100);
    //                         } else {
    //                             $percentage = 0;
    //                         }
    //                         array_push($list, ['class' => $get_enroll->id, 'name' => $student->name, 'register_no' => $student->register_no, 'user_name_id' => $student->user_name_id, 'total_hours' => $count_of_attendance, 'attend_hours' => $attendance, 'percentage' => $percentage]);
    //                         $count_of_attendance = 0;
    //                         $attendance = 0;
    //                     }
    //                 } else {
    //                     $get_students = Student::where('enroll_master_id', $get_enroll->id)->select('name', 'user_name_id', 'register_no', 'enroll_master_id')->get();
    //                     if (count($get_students) <= 0) {
    //                         $get_students = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $get_enroll->id)->select('students.name', 'students.user_name_id', 'students.register_no', 'student_promotion_history.enroll_master_id')->get();
    //                     }
    //                     $count_of_attendance = AttendanceRecord::where(['enroll_master' => $get_enroll->id, 'subject' => $subject])->count();

    //                     foreach ($get_students as $student) {
    //                         $attendance = AttendenceTable::where(['student' => $student->user_name_id, 'subject' => $subject, 'enroll_master' => $get_enroll->id])->where('attendance', '!=', 'Absent')->count();
    //                         if ($count_of_attendance > 0) {
    //                             $percentage = round(($attendance / $count_of_attendance) * 100);
    //                         } else {
    //                             $percentage = 0;
    //                         }

    //                         array_push($list, ['class' => $get_enroll->id, 'name' => $student->name, 'register_no' => $student->register_no, 'user_name_id' => $student->user_name_id, 'total_hours' => $count_of_attendance, 'attend_hours' => $attendance, 'percentage' => $percentage]);
    //                     }

    //                 }
    //                 return response()->json(['status' => true, 'data' => $list]);
    //             } else {
    //                 return response()->json(['status' => false, 'data' => 'Class Time Table Not Found']);
    //             }
    //         } else {
    //             return response()->json(['status' => false, 'data' => 'Class Not Found']);
    //         }
    //     } else {
    //         return response()->json(['status' => false, 'data' => 'Given Datas Invalid']);
    //     }

    // }

    public function staffSubjectAttendance(Request $request)
    {

        $staff = auth()->user()->id;
        $currentClasses = Session::get('currentClasses');
        $gotSubjects = ClassTimeTableTwo::with('subjects')->whereIn('class_name', $currentClasses)->where(['staff' => $staff, 'status' => 1])->groupby('class_name', 'subject')->select('class_name', 'subject')->get();

        return view('admin.variousReports.staffSubAttendance', compact('gotSubjects'));
    }

    public function getSubjectReport(Request $request)
    {

        if (isset($request->subject)) {
            $explode = explode('/', $request->subject);
            $subject = $explode[0];
            $class = $explode[1];
            $list = [];

            if ($subject != 'Library') {

                $get_students = DB::table('students')
                    ->join('subject_registration', 'students.user_name_id', '=', 'subject_registration.user_name_id')
                    ->whereNull('subject_registration.deleted_at')
                    ->whereNull('students.deleted_at')
                    ->where('students.enroll_master_id', $class)
                    ->where('subject_registration.status', 2)
                    ->where('subject_registration.subject_id', $subject)
                    ->select('students.name', 'students.user_name_id', 'students.register_no', 'students.enroll_master_id')
                    ->get();

                if (count($get_students) <= 0) {
                    $get_students = DB::table('student_promotion_history')->join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->leftJoin('subject_registration', 'student_promotion_history.user_name_id', '=', 'subject_registration.user_name_id')->where('student_promotion_history.enroll_master_id', $class)->whereNull('subject_registration.deleted_at')->whereNull('students.deleted_at')->where('subject_registration.status', 2)->where('subject_registration.subject_id', $subject)->select('students.name', 'students.user_name_id', 'students.register_no', 'student_promotion_history.enroll_master_id')->get();
                }

                if (count($get_students) > 0) {
                    $got_data = [];
                    $getAttendance = AttendenceTable::where(['enroll_master' => $class, 'subject' => $subject])->select('student', 'attendance')->get();
                    if (count($getAttendance) > 0) {
                        foreach ($getAttendance as $att) {
                            if (array_key_exists($att->student, $got_data)) {
                                $count = $got_data[$att->student]['count'];
                                $got_data[$att->student]['count'] = $count + 1;
                                if ($att->attendance != 'Absent') {
                                    $present = isset($got_data[$att->student]['present']) ? $got_data[$att->student]['present'] : 0;
                                    $got_data[$att->student]['present'] = $present + 1;
                                }
                            } else {
                                $got_data[$att->student] = [];
                                $got_data[$att->student]['count'] = 1;
                                if ($att->attendance != 'Absent') {
                                    $got_data[$att->student]['present'] = 1;
                                }

                            }
                        }
                    }

                    foreach ($get_students as $student) {
                        if (array_key_exists($student->user_name_id, $got_data)) {
                            $count_of_attendance = $got_data[$student->user_name_id]['count'];
                            $attendance = $got_data[$student->user_name_id]['present'];
                            if ($count_of_attendance > 0) {
                                $percentage = round(($attendance / $count_of_attendance) * 100);
                            } else {
                                $percentage = 0;
                            }
                            array_push($list, ['registration' => true, 'class' => $class, 'name' => $student->name, 'register_no' => $student->register_no, 'user_name_id' => $student->user_name_id, 'total_hours' => $count_of_attendance, 'attend_hours' => $attendance, 'percentage' => $percentage]);
                        }

                    }
                } else {
                    return response()->json(['status' => false, 'data' => 'Students Not Found']);
                }
            } else {
                foreach ($get_students as $student) {

                    $count_of_attendance = AttendenceTable::where(['enroll_master' => $class, 'subject' => $subject, 'student' => $student->user_name_id])->count();
                    $attendance = AttendenceTable::where(['student' => $student->user_name_id, 'subject' => $subject, 'enroll_master' => $class])->where('attendance', '!=', 'Absent')->count();
                    if ($count_of_attendance > 0) {
                        $percentage = round(($attendance / $count_of_attendance) * 100);
                    } else {
                        $percentage = 0;
                    }
                    array_push($list, ['registration' => true, 'class' => $class, 'name' => $student->name, 'register_no' => $student->register_no, 'user_name_id' => $student->user_name_id, 'total_hours' => $count_of_attendance, 'attend_hours' => $attendance, 'percentage' => $percentage]);

                }
                $get_students = DB::table('students')
                    ->whereNull('students.deleted_at')
                    ->where('students.enroll_master_id', $class)
                    ->select('students.name', 'students.user_name_id', 'students.register_no', 'students.enroll_master_id')
                    ->get();
                if (count($get_students) <= 0) {
                    $get_students = DB::table('student_promotion_history')->join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $class)->whereNull('students.deleted_at')->select('students.name', 'students.user_name_id', 'students.register_no', 'student_promotion_history.enroll_master_id')->get();
                }
                if (count($get_students) > 0) {
                    $got_data = [];
                    $getAttendance = AttendenceTable::where(['enroll_master' => $class, 'subject' => $subject])->select('student', 'attendance')->get();
                    if (count($getAttendance) > 0) {
                        foreach ($getAttendance as $att) {
                            if (array_key_exists($att->student, $got_data)) {
                                $count = $got_data[$att->student]['count'];
                                $got_data[$att->student]['count'] = $count + 1;
                                if ($att->attendance != 'Absent') {
                                    $present = isset($got_data[$att->student]['present']) ? $got_data[$att->student]['present'] : 0;
                                    $got_data[$att->student]['present'] = $present + 1;
                                }
                            } else {
                                $got_data[$att->student] = [];
                                $got_data[$att->student]['count'] = 1;
                                if ($att->attendance != 'Absent') {
                                    $got_data[$att->student]['present'] = 1;
                                }

                            }
                        }
                    }

                    foreach ($get_students as $student) {
                        if (array_key_exists($student->user_name_id, $got_data)) {
                            $count_of_attendance = $got_data[$student->user_name_id]['count'];
                            $attendance = $got_data[$student->user_name_id]['present'];
                            if ($count_of_attendance > 0) {
                                $percentage = round(($attendance / $count_of_attendance) * 100);
                            } else {
                                $percentage = 0;
                            }
                            array_push($list, ['registration' => true, 'class' => $get_enroll->id, 'name' => $student->name, 'register_no' => $student->register_no, 'user_name_id' => $student->user_name_id, 'total_hours' => $count_of_attendance, 'attend_hours' => $attendance, 'percentage' => $percentage]);
                        }

                    }
                } else {
                    return response()->json(['status' => false, 'data' => 'Students Not Found']);
                }
            }
            return response()->json(['status' => true, 'data' => $list]);

        } else {
            return response()->json(['status' => false, 'data' => 'Technical Error']);
        }

    }

    public function absenteesReportIndex(Request $request)
    {
        $academic_years = AcademicYear::pluck('name', 'id');

        $role_id = auth()->user()->roles[0]->id;

        $dept = auth()->user()->dept;
        if ($role_id == 14 && $dept != null) {
            $departments = ToolsDepartment::where(['name' => $dept])->pluck('name', 'id');
        } else {
            $departments = ToolsDepartment::pluck('name', 'id');
        }

        return view('admin.variousReports.absenteesReport', compact('departments', 'academic_years'));
    }

    public function absenteesReport(Request $request)
    {
        if (isset($request->department) && isset($request->course) && isset($request->ay) && isset($request->sem_type) && isset($request->date)) {
            $department = $request->department;
            $course = $request->course;
            $ay = $request->ay;
            $sem_type = $request->sem_type;
            $date = $request->date;
            if ($department != '5') {
                $get_course = ToolsCourse::where(['id' => $course])->select('name', 'short_form')->first();
                $get_ay = AcademicYear::where(['id' => $ay])->select('name')->first();
                if ($get_course != '') {
                    if ($get_ay != '') {
                        $enrolls = [];
                        if ($sem_type == 'ODD') {
                            $theSem = [3, 5, 7];
                        } else {
                            $theSem = [4, 6, 8];
                        }

                        $theCourse = $get_course->name;
                        $theAy = $get_ay->name;
                        foreach ($theSem as $sem) {
                            $make_enroll = '/' . $theCourse . '/' . $theAy . '/' . $sem . '/';
                            $get_enrolls = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%$make_enroll%")->select('id', 'enroll_master_number')->get();

                            foreach ($get_enrolls as $enroll) {
                                $explode = explode('/', $enroll->enroll_master_number);

                                $enroll->name = $get_course->short_form . '/' . $explode[3] . '/' . $explode[4];
                            }
                            if ($sem == 3 || $sem == 4) {
                                $year = 'Second Year';
                            } else if ($sem == 5 || $sem == 6) {
                                $year = 'Third Year';
                            } else {
                                $year = 'Final Year';
                            }
                            array_push($enrolls, ['year' => $year, 'data' => $get_enrolls]);

                        }

                        if (count($enrolls) > 0) {
                            foreach ($enrolls as $enroll) {
                                foreach ($enroll['data'] as $enroll_master) {
                                    $students = Student::where(['enroll_master_id' => $enroll_master['id']])->count();
                                    $check_attendance = AttendanceRecord::where('status', '!=', '0')->where(['actual_date' => $date, 'enroll_master' => $enroll_master['id'], 'period' => 1])->get();
                                    if (count($check_attendance) > 0) {
                                        $get_attendance = AttendenceTable::where(['date' => $date, 'enroll_master' => $enroll_master['id'], 'period' => 1])->where('attendance', '!=', 'Present')->select('student', 'attendance')->get();
                                        $leaveArray = [];
                                        $absentArray = [];
                                        $odArray = [];
                                        if (count($get_attendance) > 0) {
                                            $get_attendance = $get_attendance->toArray();
                                            foreach ($get_attendance as $att) {
                                                $getStudent = Student::where(['user_name_id' => $att['student']])->select('register_no')->first();
                                                if ($getStudent != '') {
                                                    $att['student'] = $getStudent->register_no;
                                                }
                                                if ($att['attendance'] == 'Absent') {
                                                    array_push($absentArray, $att);
                                                } else if ($att['attendance'] == 'Leave Taken') {
                                                    array_push($leaveArray, $att);
                                                } else {
                                                    array_push($odArray, $att);
                                                }
                                            }
                                        }
                                        $enroll_master['students'] = $students;
                                        $enroll_master['leavelist'] = $leaveArray;
                                        $enroll_master['absentlist'] = $absentArray;
                                        $enroll_master['odlist'] = $odArray;
                                        $enroll_master['status'] = true;
                                    } else {
                                        $enroll_master['students'] = $students;
                                        $enroll_master['leavelist'] = [];
                                        $enroll_master['absentlist'] = [];
                                        $enroll_master['odlist'] = [];
                                        $enroll_master['status'] = false;
                                    }

                                }
                            }
                            return response()->json(['status' => true, 'data' => $enrolls]);
                        } else {
                            return response()->json(['status' => false, 'data' => 'Classes Not Found']);
                        }
                    } else {
                        return response()->json(['status' => false, 'data' => 'AY Not Found']);
                    }
                } else {
                    return response()->json(['status' => false, 'data' => 'Course Not Found']);
                }
            } else {
                $get_course = ToolsCourse::where(['id' => $course])->select('name', 'short_form')->first();
                $get_ay = AcademicYear::where(['id' => $ay])->select('name')->first();
                if ($get_course != '') {
                    if ($get_ay != '') {
                        $enrolls = [];
                        if ($sem_type == 'ODD') {
                            $theSem = [1];
                        } else {
                            $theSem = [2];
                        }
                        $theCourse = $get_course->name;
                        $theAy = $get_ay->name;

                        foreach ($theSem as $sem) {
                            $make_enroll = '/' . $theCourse . '/' . $theAy . '/' . $sem . '/';

                            $get_enrolls = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%$make_enroll%")->select('id', 'enroll_master_number')->get();
                            if (count($get_enrolls) <= 0) {
                                return response()->json(['status' => false, 'data' => 'Classes Not Found']);
                            }
                            foreach ($get_enrolls as $enroll) {
                                $explode = explode('/', $enroll->enroll_master_number);

                                $enroll->name = $get_course->short_form . '/' . $explode[3] . '/' . $explode[4];
                            }

                            array_push($enrolls, ['year' => 'First Year', 'data' => $get_enrolls]);
                        }
                        if (count($enrolls) > 0) {
                            foreach ($enrolls as $enroll) {
                                foreach ($enroll['data'] as $enroll_master) {
                                    $students = Student::where(['enroll_master_id' => $enroll_master['id']])->count();
                                    $check_attendance = AttendanceRecord::where('status', '!=', '0')->where(['actual_date' => $date, 'enroll_master' => $enroll_master['id'], 'period' => 1])->get();
                                    if (count($check_attendance) > 0) {
                                        $get_attendance = AttendenceTable::where(['date' => $date, 'enroll_master' => $enroll_master['id'], 'period' => 1])->where('attendance', '!=', 'Present')->select('student', 'attendance')->get();
                                        $leaveArray = [];
                                        $absentArray = [];
                                        $odArray = [];
                                        if (count($get_attendance) > 0) {
                                            $get_attendance = $get_attendance->toArray();
                                            foreach ($get_attendance as $att) {
                                                $getStudent = Student::where(['user_name_id' => $att['student']])->select('register_no')->first();
                                                if ($getStudent != '') {
                                                    $att['student'] = $getStudent->register_no;
                                                }
                                                if ($att['attendance'] == 'Absent') {
                                                    array_push($absentArray, $att);
                                                } else if ($att['attendance'] == 'Leave Taken') {
                                                    array_push($leaveArray, $att);
                                                } else {
                                                    array_push($odArray, $att);
                                                }
                                            }
                                        }
                                        $enroll_master['students'] = $students;
                                        $enroll_master['leavelist'] = $leaveArray;
                                        $enroll_master['absentlist'] = $absentArray;
                                        $enroll_master['odlist'] = $odArray;
                                        $enroll_master['status'] = true;
                                    } else {
                                        $enroll_master['students'] = $students;
                                        $enroll_master['leavelist'] = [];
                                        $enroll_master['absentlist'] = [];
                                        $enroll_master['odlist'] = [];
                                        $enroll_master['status'] = false;
                                    }

                                }
                            }
                            return response()->json(['status' => true, 'data' => $enrolls]);
                        } else {
                            return response()->json(['status' => false, 'data' => 'Classes Not Found']);
                        }

                    } else {
                        return response()->json(['status' => false, 'data' => 'AY Not Found']);
                    }
                } else {
                    return response()->json(['status' => false, 'data' => 'Course Not Found']);
                }
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Couldn\'t Get The Mandatory Details']);
        }

    }

    public function absenteesReportPDF(Request $request)
    {
        if (isset($request->department) && isset($request->course) && isset($request->ay) && isset($request->sem_type) && isset($request->date)) {
            $department = $request->department;
            $course = $request->course;
            $ay = $request->ay;
            $sem_type = $request->sem_type;
            $date = $request->date;
            if ($department != '5') {
                $get_dept = ToolsDepartment::where(['id' => $department])->select('name')->first();
                if ($get_dept != '') {
                    $theDept = $get_dept->name;
                } else {
                    $theDept = null;
                }
                $get_course = ToolsCourse::where(['id' => $course])->select('name', 'short_form')->first();
                $get_ay = AcademicYear::where(['id' => $ay])->select('name')->first();
                if ($get_course != '') {
                    if ($get_ay != '') {
                        $enrolls = [];
                        if ($sem_type == 'ODD') {
                            $theSem = [3, 5, 7];
                        } else {
                            $theSem = [4, 6, 8];
                        }

                        $theCourse = $get_course->name;
                        $theAy = $get_ay->name;
                        foreach ($theSem as $sem) {
                            $make_enroll = '/' . $theCourse . '/' . $theAy . '/' . $sem . '/';
                            $get_enrolls = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%$make_enroll%")->select('id', 'enroll_master_number')->get();
                            foreach ($get_enrolls as $enroll) {
                                $explode = explode('/', $enroll->enroll_master_number);

                                $enroll->name = $get_course->short_form . '/' . $explode[3] . '/' . $explode[4];
                            }
                            if ($sem == 3 || $sem == 4) {
                                $year = 'Second Year';
                            } else if ($sem == 5 || $sem == 6) {
                                $year = 'Third Year';
                            } else {
                                $year = 'Final Year';
                            }
                            array_push($enrolls, ['year' => $year, 'data' => $get_enrolls]);
                        }
                        if (count($enrolls) > 0) {
                            foreach ($enrolls as $enroll) {
                                foreach ($enroll['data'] as $enroll_master) {
                                    $students = Student::where(['enroll_master_id' => $enroll_master['id']])->count();
                                    $check_attendance = AttendanceRecord::where('status', '!=', '0')->where(['actual_date' => $date, 'enroll_master' => $enroll_master['id'], 'period' => 1])->get();
                                    if (count($check_attendance) > 0) {
                                        $get_attendance = AttendenceTable::where(['date' => $date, 'enroll_master' => $enroll_master['id'], 'period' => 1])->where('attendance', '!=', 'Present')->select('student', 'attendance')->get();
                                        $leaveArray = [];
                                        $absentArray = [];
                                        $odArray = [];
                                        if (count($get_attendance) > 0) {
                                            $get_attendance = $get_attendance->toArray();
                                            foreach ($get_attendance as $att) {
                                                $getStudent = Student::where(['user_name_id' => $att['student']])->select('register_no')->first();
                                                if ($getStudent != '') {
                                                    $att['student'] = $getStudent->register_no;
                                                }
                                                if ($att['attendance'] == 'Absent') {
                                                    array_push($absentArray, $att);
                                                } else if ($att['attendance'] == 'Leave Taken') {
                                                    array_push($leaveArray, $att);
                                                } else {
                                                    array_push($odArray, $att);
                                                }
                                            }
                                        }
                                        $enroll_master['students'] = $students;
                                        $enroll_master['leavelist'] = $leaveArray;
                                        $enroll_master['absentlist'] = $absentArray;
                                        $enroll_master['odlist'] = $odArray;
                                        $enroll_master['status'] = true;
                                    } else {
                                        $enroll_master['students'] = $students;
                                        $enroll_master['leavelist'] = [];
                                        $enroll_master['absentlist'] = [];
                                        $enroll_master['odlist'] = [];
                                        $enroll_master['status'] = false;
                                    }

                                }
                            }
                            $carbonDate = Carbon::createFromFormat('Y-m-d', $request->date);
                            $formattedDate = $carbonDate->format('d-m-Y');
                            $final_data = ['enrolls' => $enrolls, 'dept' => $theDept, 'date' => $formattedDate];
                            $pdf = PDF::loadView('admin.variousReports.absenteesRepPDF', $final_data);
                            return $pdf->stream('AbsenteesReport.pdf');
                        } else {
                            return back()->with('error', 'Classes Not Found');
                        }
                    } else {
                        return back()->with('error', 'AY Not Found');
                    }
                } else {
                    return back()->with('error', 'Course Not Found');
                }
            } else {
                $get_course = ToolsCourse::where(['id' => $course])->select('name', 'short_form')->first();
                $get_ay = AcademicYear::where(['id' => $ay])->select('name')->first();
                if ($get_course != '') {
                    if ($get_ay != '') {
                        $enrolls = [];
                        if ($sem_type == 'ODD') {
                            $theSem = [1];
                        } else {
                            $theSem = [2];
                        }
                        $theCourse = $get_course->name;
                        $theAy = $get_ay->name;

                        foreach ($theSem as $sem) {
                            $make_enroll = '/' . $theCourse . '/' . $theAy . '/' . $sem . '/';

                            $get_enrolls = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%$make_enroll%")->select('id', 'enroll_master_number')->get();
                            if (count($get_enrolls) <= 0) {
                                return response()->json(['status' => false, 'data' => 'Classes Not Found']);
                            }
                            foreach ($get_enrolls as $enroll) {
                                $explode = explode('/', $enroll->enroll_master_number);

                                $enroll->name = $get_course->short_form . '/' . $explode[3] . '/' . $explode[4];
                            }

                            array_push($enrolls, ['year' => 'First Year', 'data' => $get_enrolls]);
                        }
                        if (count($enrolls) > 0) {
                            foreach ($enrolls as $enroll) {
                                foreach ($enroll['data'] as $enroll_master) {
                                    $students = Student::where(['enroll_master_id' => $enroll_master['id']])->count();
                                    $check_attendance = AttendanceRecord::where('status', '!=', '0')->where(['actual_date' => $date, 'enroll_master' => $enroll_master['id'], 'period' => 1])->get();
                                    if (count($check_attendance) > 0) {
                                        $get_attendance = AttendenceTable::where(['date' => $date, 'enroll_master' => $enroll_master['id'], 'period' => 1])->where('attendance', '!=', 'Present')->select('student', 'attendance')->get();
                                        $leaveArray = [];
                                        $absentArray = [];
                                        $odArray = [];
                                        if (count($get_attendance) > 0) {
                                            $get_attendance = $get_attendance->toArray();
                                            foreach ($get_attendance as $att) {
                                                $getStudent = Student::where(['user_name_id' => $att['student']])->select('register_no')->first();
                                                if ($getStudent != '') {
                                                    $att['student'] = $getStudent->register_no;
                                                }
                                                if ($att['attendance'] == 'Absent') {
                                                    array_push($absentArray, $att);
                                                } else if ($att['attendance'] == 'Leave Taken') {
                                                    array_push($leaveArray, $att);
                                                } else {
                                                    array_push($odArray, $att);
                                                }
                                            }
                                        }
                                        $enroll_master['students'] = $students;
                                        $enroll_master['leavelist'] = $leaveArray;
                                        $enroll_master['absentlist'] = $absentArray;
                                        $enroll_master['odlist'] = $odArray;
                                        $enroll_master['status'] = true;
                                    } else {
                                        $enroll_master['students'] = $students;
                                        $enroll_master['leavelist'] = [];
                                        $enroll_master['absentlist'] = [];
                                        $enroll_master['odlist'] = [];
                                        $enroll_master['status'] = false;
                                    }

                                }
                            }
                            return view('admin.variousReports.absenteesRepPDF', compact('enrolls'));
                        } else {
                            return back()->with('error', 'Classes Not Found');
                        }

                    } else {
                        return back()->with('error', 'AY Not Found');
                    }
                } else {
                    return back()->with('error', 'Course Not Found');
                }
            }
        } else {
            return back()->with('error', 'Couldn\'t Get The Mandatory Details');
        }

    }
}
