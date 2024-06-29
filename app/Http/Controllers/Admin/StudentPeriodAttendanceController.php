<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AttendanceRecord;
use App\Models\AttendenceTable;
use App\Models\AyCalendar;
use App\Models\BulkOD;
use App\Models\ClassRoom;
use App\Models\ClassTimeTableTwo;
use App\Models\CollegeCalender;
use App\Models\CourseEnrollMaster;
use App\Models\Examattendance;
use App\Models\ExamTimetableCreation;
use App\Models\LessonPlans;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentPeriodAllocate;
use App\Models\Subject;
use App\Models\SubjectRegistration;
use App\Models\TeachingStaff;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use App\Models\UserAlert;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;

class StudentPeriodAttendanceController extends Controller
{
    public function index(Request $request)
    {

        $user_name_id = auth()->user()->id;

        $subjects = [];

        $currentClasses = Session::get('currentClasses');

        $getAys = AcademicYear::pluck('name', 'id');

        $timetable = ClassTimeTableTwo::whereIn('class_name', $currentClasses)->where(['status' => 1, 'staff' => $user_name_id])->get();

        if (count($timetable) > 0) {
            foreach ($timetable as $data) {
                if (!in_array([$data->subject, $data->class_name], $subjects)) {
                    array_push($subjects, [$data->subject, $data->class_name]);
                }
            }
        }

        $got_subjects = [];

        for ($i = 0; $i < count($subjects); $i++) {
            $get_enroll = CourseEnrollMaster::where(['id' => $subjects[$i][1]])->first();
            $get_subjects = Subject::where(['id' => $subjects[$i][0]])->first();
            $get_course = explode('/', $get_enroll->enroll_master_number);
            $get_short_form = ToolsCourse::where('name', $get_course[1])->select('short_form')->first();

            if ($get_short_form) {
                $get_course[1] = $get_short_form->short_form;
                $subjects[$i][1] = $get_course[1] . ' / ' . $get_course[3] . ' / ' . $get_course[4];
            }
            if ($get_subjects) {
                array_push($got_subjects, [$get_subjects->id, $subjects[$i][1], $get_enroll->id, $user_name_id, $get_subjects->name, $get_subjects->subject_code]);
            }
        }

        return view('admin.studentPeriodAttandance.index', compact('got_subjects', 'subjects', 'getAys'));
    }
    public function get_day(Request $request)
    {
        // dd($request);
        if (isset($request->date)) {
            if ($request->date != '') {

                $date = $request->date;

                $actual_date = $date;

                $currentDate = Carbon::now()->format('Y-m-d');

                $get_day = Carbon::createFromFormat('Y-m-d', $request->date)->format('l');

                $day = strtoupper($get_day);
                $day_order_list = [];
                $table = [];
                $got_table = [];
                $make_enroll_ayc = [];
                $dummy = [];
                $sem_from_ayc = null;
                $academic_year_ayc = null;

                // AyCalendar
                $date_for_ay = $actual_date . ' 00:00:00';
                // dd($date);
                $check_ay_calendar_1 = AyCalendar::where(['date' => $date_for_ay, 'batch' => '01', 'semester_type' => 'ODD'])->first();
                $check_ay_calendar_1_1 = AyCalendar::where(['date' => $date_for_ay, 'batch' => '01', 'semester_type' => 'EVEN'])->first();
                $check_ay_calendar_2 = AyCalendar::where(['date' => $date_for_ay, 'batch' => '02', 'semester_type' => 'ODD'])->first();
                $check_ay_calendar_2_2 = AyCalendar::where(['date' => $date_for_ay, 'batch' => '02', 'semester_type' => 'EVEN'])->first();
                $check_ay_calendar_3 = AyCalendar::where(['date' => $date_for_ay, 'batch' => '03', 'semester_type' => 'ODD'])->first();
                $check_ay_calendar_3_3 = AyCalendar::where(['date' => $date_for_ay, 'batch' => '03', 'semester_type' => 'EVEN'])->first();
                $check_ay_calendar_4 = AyCalendar::where(['date' => $date_for_ay, 'batch' => '04', 'semester_type' => 'ODD'])->first();
                $check_ay_calendar_4_4 = AyCalendar::where(['date' => $date_for_ay, 'batch' => '04', 'semester_type' => 'EVEN'])->first();

                // dd($check_ay_calendar_1, $check_ay_calendar_1_1, $check_ay_calendar_2, $check_ay_calendar_2_2, $check_ay_calendar_3, $check_ay_calendar_3_3, $check_ay_calendar_4, $check_ay_calendar_4_4);
                if ($check_ay_calendar_1 != '') {
                    $make_details = ['batch' => '01', 'sem_type' => 'ODD', 'dayorder' => $check_ay_calendar_1->dayorder, 'academic_year' => $check_ay_calendar_1->academic_year];
                    array_push($day_order_list, $make_details);
                }
                if ($check_ay_calendar_1_1 != '') {
                    $make_details = ['batch' => '01', 'sem_type' => 'EVEN', 'dayorder' => $check_ay_calendar_1_1->dayorder, 'academic_year' => $check_ay_calendar_1_1->academic_year];
                    array_push($day_order_list, $make_details);
                }
                if ($check_ay_calendar_2 != '') {
                    $make_details = ['batch' => '02', 'sem_type' => 'ODD', 'dayorder' => $check_ay_calendar_2->dayorder, 'academic_year' => $check_ay_calendar_2->academic_year];
                    array_push($day_order_list, $make_details);
                }
                if ($check_ay_calendar_2_2 != '') {
                    $make_details = ['batch' => '02', 'sem_type' => 'EVEN', 'dayorder' => $check_ay_calendar_2_2->dayorder, 'academic_year' => $check_ay_calendar_2_2->academic_year];
                    array_push($day_order_list, $make_details);
                }
                if ($check_ay_calendar_3 != '') {
                    $make_details = ['batch' => '03', 'sem_type' => 'ODD', 'dayorder' => $check_ay_calendar_3->dayorder, 'academic_year' => $check_ay_calendar_3->academic_year];
                    array_push($day_order_list, $make_details);
                }
                if ($check_ay_calendar_3_3 != '') {
                    $make_details = ['batch' => '03', 'sem_type' => 'EVEN', 'dayorder' => $check_ay_calendar_3_3->dayorder, 'academic_year' => $check_ay_calendar_3_3->academic_year];
                    array_push($day_order_list, $make_details);
                }
                if ($check_ay_calendar_4 != '') {
                    $make_details = ['batch' => '04', 'sem_type' => 'ODD', 'dayorder' => $check_ay_calendar_4->dayorder, 'academic_year' => $check_ay_calendar_4->academic_year];
                    array_push($day_order_list, $make_details);
                }
                if ($check_ay_calendar_4_4 != '') {
                    $make_details = ['batch' => '04', 'sem_type' => 'EVEN', 'dayorder' => $check_ay_calendar_4_4->dayorder, 'academic_year' => $check_ay_calendar_4_4->academic_year];
                    array_push($day_order_list, $make_details);
                }
                $staff = TeachingStaff::where(['user_name_id' => $request->user_id])->first();

                if (count($day_order_list) > 0) {
                    foreach ($day_order_list as $day_order) {

                        if ($day_order['batch'] == '01' && $day_order['sem_type'] == 'ODD') {
                            $sem_from_ayc = 1;
                        } else if ($day_order['batch'] == '01' && $day_order['sem_type'] == 'EVEN') {
                            $sem_from_ayc = 2;
                        } else if ($day_order['batch'] == '02' && $day_order['sem_type'] == 'ODD') {
                            $sem_from_ayc = 3;
                        } else if ($day_order['batch'] == '02' && $day_order['sem_type'] == 'EVEN') {
                            $sem_from_ayc = 4;
                        } else if ($day_order['batch'] == '03' && $day_order['sem_type'] == 'ODD') {
                            $sem_from_ayc = 5;
                        } else if ($day_order['batch'] == '03' && $day_order['sem_type'] == 'EVEN') {
                            $sem_from_ayc = 6;
                        } else if ($day_order['batch'] == '04' && $day_order['sem_type'] == 'ODD') {
                            $sem_from_ayc = 7;
                        } else if ($day_order['batch'] == '04' && $day_order['sem_type'] == 'EVEN') {
                            $sem_from_ayc = 8;
                        }
                        $academic_year_ayc = $day_order['academic_year'];

                        if ($academic_year_ayc != null && $sem_from_ayc != null) {
                            $search_enroll = $academic_year_ayc . '/' . $sem_from_ayc;
                            $make_enroll_ayc = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%{$search_enroll}%")->get();
                        }

                        if ($day_order['dayorder'] == 20) {
                            $order = 'MONDAY';
                        } else if ($day_order['dayorder'] == 7) {
                            $order = 'TUESDAY';
                        } else if ($day_order['dayorder'] == 8) {
                            $order = 'WEDNESDAY';
                        } else if ($day_order['dayorder'] == 9) {
                            $order = 'THURSDAY';
                        } else if ($day_order['dayorder'] == 10) {
                            $order = 'FRIDAY';
                        } else if ($day_order['dayorder'] == 11) {
                            $order = 'SATURDAY';
                        } else {
                            $order = $day;
                        }

                        if ($order != '' && count($make_enroll_ayc) > 0) {

                            foreach ($make_enroll_ayc as $data) {

                                $timetable = ClassTimeTableTwo::with(['subjects', 'staffs'])->where(['status' => 1, 'day' => $order, 'staff' => $staff->user_name_id, 'class_name' => $data->id])->get();

                                if (count($timetable) > 0) {
                                    array_push($table, $timetable);
                                }
                            }
                        }
                    }
                    // dd($table);
                    if (count($table) > 0) {
                        foreach ($table as $days) {
                            if (count($days) > 0) {
                                // dd($days);
                                foreach ($days as $period) {

                                    // dd($period);
                                    $get_enroll = CourseEnrollMaster::where(['id' => $period->class_name])->first();

                                    if ($get_enroll != '') {
                                        $period->raw_class = $get_enroll->enroll_master_number;
                                        $get_course_1 = explode('/', $get_enroll->enroll_master_number);
                                        $get_short_form_1 = ToolsCourse::where('name', $get_course_1[1])->select('short_form')->first();

                                        if ($get_short_form_1) {
                                            $get_course_1[1] = $get_short_form_1->short_form;
                                            $period->class = $get_course_1[1] . ' / ' . $get_course_1[3] . ' / ' . $get_course_1[4];
                                        }

                                        $ay = $get_course_1[2];
                                        $sem = $get_course_1[3];

                                        if ($sem == 1 || $sem == 2) {
                                            $batch = '01';
                                        } else if ($sem == 3 || $sem == 4) {
                                            $batch = '02';
                                        } else if ($sem == 5 || $sem == 6) {
                                            $batch = '03';
                                        } else if ($sem == 7 || $sem == 8) {
                                            $batch = '04';
                                        } else {
                                            $batch = null;
                                        }
                                        if ($sem == 1 || $sem == 3 || $sem == 5 || $sem == 7) {
                                            $sem_type = 'ODD';
                                        } else {
                                            $sem_type = 'EVEN';
                                        }

                                        $check_ay_calendar = AyCalendar::where(['date' => $date_for_ay, 'batch' => $batch, 'semester_type' => $sem_type])->first();

                                        //   dd($check_ay_calendar);

                                        $check = AttendanceRecord::where(['actual_date' => $actual_date, 'staff' => $period->staff, 'enroll_master' => $period->class_name, 'subject' => $period->subject, 'period' => $period->period])->first();

                                        if ($check != '') {
                                            if ($check->status == '100') {
                                                $period->status = 100;
                                            } else if ($check->status == '0') {
                                                $period->status = 0;
                                            } else if ($check->status == '1') {
                                                $period->status = 1;
                                            }
                                        } else {
                                            if ($date > $currentDate) {
                                                $period->status = 2;
                                            } else if ($date == $currentDate) {
                                                $period->status = 3;
                                            } else if ($date < $currentDate) {
                                                $period->status = 4;
                                            }
                                        }

                                        if ($check_ay_calendar != '') {
                                            // dd($check_ay_calendar);
                                            if ($check_ay_calendar->dayorder == 4 || $check_ay_calendar->dayorder == 1 || $check_ay_calendar->dayorder == 2 || $check_ay_calendar->dayorder == 3) {
                                                $period->status = 'Holi Day';
                                            } else if ($check_ay_calendar->dayorder == 5) {
                                                $period->status = 'No Order Day';
                                            } else if ($check_ay_calendar->dayorder == 14) {
                                                $period->status = 'Modal Exam';
                                            } else if ($check_ay_calendar->dayorder == 15) {
                                                $period->status = 'IAT';
                                            } else if ($check_ay_calendar->dayorder == 19) {
                                                $period->status = 'College Day';
                                            } else if ($check_ay_calendar->dayorder == 6) {
                                                $period->status = 'Unit Test';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                // dd($table);
                $one_day = ['date' => $request->date, 'day' => $day, 'timetable' => $table];
                // dd($one_day);
                $subjects = Subject::pluck('name', 'id')->prepend('-', '');

                return view('admin.studentPeriodAttandance.index', compact(['subjects', 'one_day']));
            }
        }
    }

    public function list(Request $request)
    {
        // dd($request);
        $student_list = [];
        $takenUnits = [];
        $theUnit = null;
        $theTopic = null;
        if (isset($request->form_data) && $request->form_data != '') {
            $data = $request->form_data;
            // dd($data);
            if (isset($data[10])) {
                $selected_batchDay = $data[10]['value'];
            } else {
                $selected_batchDay = null;
            }
            $check_attendanceRecord = AttendanceRecord::where(['actual_date' => $data[6]['value'], 'period' => $data[7]['value'], 'subject' => $data[1]['value'], 'enroll_master' => $data[3]['value'], 'status' => '1'])->get();
            if (count($check_attendanceRecord) <= 0) {
                $check_attendanceRecordForEdit = AttendanceRecord::where(['actual_date' => $data[6]['value'], 'period' => $data[7]['value'], 'subject' => $data[1]['value'], 'enroll_master' => $data[3]['value'], 'status' => '100'])->select('id', 'unit', 'topic')->first();
                $find_class = CourseEnrollMaster::where('id', $data[3]['value'])->first();
                $user_id = auth()->user()->id;
                //    dd($find_class);
                $get_staffs = DB::table('role_user')->where(['user_id' => $user_id])->first();
                // dd($get_staffs);
                if ($get_staffs) {
                    $get_staff = $get_staffs->user_id;
                } else {
                    $get_staff = null;
                }

                if ($check_attendanceRecordForEdit != '') {
                    $theUnit = $check_attendanceRecordForEdit->unit;
                    $theTopic = $check_attendanceRecordForEdit->topic;
                }

                $check_attendanceRecordForUnits = AttendanceRecord::where(['subject' => $data[1]['value'], 'enroll_master' => $data[3]['value']])->groupBy('unit')->select('unit')->get();
                if (count($check_attendanceRecordForUnits) > 0) {
                    foreach ($check_attendanceRecordForUnits as $unitData) {
                        array_push($takenUnits, $unitData->unit);
                    }
                }

                $units = LessonPlans::select('unit', 'unit_no')
                    ->where([
                        'class' => $data[3]['value'],
                        'subject' => $data[1]['value'],
                    ])
                    ->distinct()
                    ->get();
                // dd($units);
                $subject = Subject::find($data[1]['value']);
                if ($subject) {
                    $classSubject = $subject->name . ' (' . $subject->subject_code . ') ';
                } else {
                    $classSubject = $data[1]['value'];
                }
                $dateString = $data[6]['value'];

                $getDay = Carbon::parse($dateString);

                $selected_day = $getDay->format('l');

                if ($find_class) {

                    $explode = explode('/', $find_class->enroll_master_number);
                    $gotAy = $explode[2];
                    $gotSem = $explode[3];
                    $gotSec = $explode[4];
                    if ($gotSem == 1 || $gotSem == 2) {
                        $gotYear = '01';
                        $semester_type = 'ODD';
                        if ($gotSem == 2) {
                            $semester_type = 'EVEN';
                        }
                    } else if ($gotSem == 3 || $gotSem == 4) {
                        $gotYear = '02';
                        $semester_type = 'ODD';
                        if ($gotSem == 4) {
                            $semester_type = 'EVEN';
                        }
                    } else if ($gotSem == 5 || $gotSem == 6) {
                        $gotYear = '03';
                        $semester_type = 'ODD';
                        if ($gotSem == 6) {
                            $semester_type = 'EVEN';
                        }
                    } else if ($gotSem == 7 || $gotSem == 8) {
                        $gotYear = '04';
                        $semester_type = 'ODD';
                        if ($gotSem == 8) {
                            $semester_type = 'EVEN';
                        }
                    }
                    $gotCourse = ToolsCourse::where('name', $explode[1])->value('id');
                    $check_examattendance = Examattendance::where(['date' => $request->date, 'year' => $gotYear, 'course' => $gotCourse, 'sem' => $gotSem, 'section' => $gotSec, 'acyear' => $gotAy])->select('exame_id')->first();
                    if ($check_examattendance != '') {
                        $check_examCreation = ExamTimetableCreation::where(['id' => $check_examattendance->exame_id])->select('start_time', 'end_time')->first();

                        if ($check_examCreation != '') {
                            $start_time = substr($check_examCreation->start_time, 0, -3);
                            $end_time = substr($check_examCreation->end_time, 0, -3);
                            if ($start_time >= '8:00' && $end_time <= '9:00') {
                                if ($request->period == 1) {
                                    return response()->json(['error' => 'CAT Exam Scheduled For The Selected Class']);
                                }
                            } elseif ($start_time >= '8:00' && $end_time <= '10:00') {
                                if ($request->period == 1 || $request->period == 2) {
                                    return response()->json(['error' => 'CAT Exam Scheduled For The Selected Class']);
                                }
                            } elseif ($start_time >= '8:00' && ($end_time <= '12:00')) {
                                if ($request->period == 1 || $request->period == 2 || $request->period == 3 || $request->period == 4) {
                                    return response()->json(['error' => 'CAT Exam Scheduled For The Selected Class']);
                                }
                            } elseif ($start_time >= '9:00' && $end_time <= '10:00') {
                                if ($request->period == 2) {
                                    return response()->json(['error' => 'CAT Exam Scheduled For The Selected Class']);
                                }
                            } elseif ($start_time >= '9:00' && $end_time <= '12:00') {
                                if ($request->period == 2 || $request->period == 3 || $request->period == 4) {
                                    return response()->json(['error' => 'CAT Exam Scheduled For The Selected Class']);
                                }
                            } elseif ($start_time >= '10:00' && ($end_time <= '12:00')) {
                                if ($request->period == 3 || $request->period == 4) {
                                    return response()->json(['error' => 'CAT Exam Scheduled For The Selected Class']);
                                }
                            } elseif ($start_time >= '12:00' && ($end_time <= '1:20')) {
                                if ($request->period == 5) {
                                    return response()->json(['error' => 'CAT Exam Scheduled For The Selected Class']);
                                }
                            } elseif ($start_time >= '1:20' && ($end_time <= '2:10')) {
                                if ($request->period == 6) {
                                    return response()->json(['error' => 'CAT Exam Scheduled For The Selected Class']);
                                }
                            } elseif ($start_time >= '12:00' && ($end_time <= '2:10')) {
                                if ($request->period == 5 || $request->period == 6) {
                                    return response()->json(['error' => 'CAT Exam Scheduled For The Selected Class']);
                                }
                            } elseif ($start_time >= '12:00' && ($end_time <= '3:00')) {
                                if ($request->period == 5 || $request->period == 6 || $request->period == 7) {
                                    return response()->json(['error' => 'CAT Exam Scheduled For The Selected Class']);
                                }
                            }
                        }
                    }

                    $check_ay_calendar = AyCalendar::where(['date' => $dateString . ' 00:00:00', 'academic_year' => $gotAy, 'batch' => $gotYear, 'semester_type' => $semester_type])->first();

                    if ($check_ay_calendar != '') {

                        if ($check_ay_calendar->dayorder == 20) {
                            $order = 'MONDAY';
                        } else if ($check_ay_calendar->dayorder == 7) {
                            $order = 'TUESDAY';
                        } else if ($check_ay_calendar->dayorder == 8) {
                            $order = 'WEDNESDAY';
                        } else if ($check_ay_calendar->dayorder == 9) {
                            $order = 'THURSDAY';
                        } else if ($check_ay_calendar->dayorder == 10) {
                            $order = 'FRIDAY';
                        } else if ($check_ay_calendar->dayorder == 11) {
                            $order = 'SATURDAY';
                        } else if ($check_ay_calendar->dayorder == 0) {
                            $order = true;
                        } else {
                            $order = null;
                        }
                        if ($order != null) {

                            if ($selected_batchDay != null) {
                                $allocated_students = StudentPeriodAllocate::where(['class' => $data[3]['value'], 'batch' => $selected_batchDay])->groupby('student')->select('student')->get();
                            } else {
                                $allocated_students = [];
                            }
                            $get_students = Student::where('enroll_master_id', $data[3]['value'])->orderBy('register_no', 'asc')->get();
                            // dd($get_students);
                            $checkSubjectRegArray = [];
                            $checkSubjectReg = SubjectRegistration::where(['enroll_master' => $data[3]['value'], 'subject_id' => $data[1]['value']])->select('user_name_id')->get();
                            if (count($checkSubjectReg) > 0) {

                                foreach ($checkSubjectReg as $reg) {
                                    array_push($checkSubjectRegArray, $reg->user_name_id);
                                }
                            } else {
                                return response()->json(['error' => "No Students Registered This Subjects"]);
                            }

                            if ($get_students) {

                                if ($check_attendanceRecordForEdit != '') {
                                    $get_attendance = AttendenceTable::where(['enroll_master' => $data[3]['value'], 'period' => $data[7]['value'], 'subject' => $data[1]['value'], 'date' => $dateString])->get();
                                } else {
                                    $get_attendance = [];
                                }
                                if (count($allocated_students) > 0) {
                                    foreach ($allocated_students as $student_data) {
                                        if (in_array($student_data->student, $checkSubjectRegArray)) {
                                            foreach ($get_students as $get_student) {
                                                if ($student_data->student == $get_student->user_name_id) {

                                                    $studentID = $get_student->user_name_id;

                                                    $get_data = DB::table('student_leave_apply')
                                                        ->where('user_name_id', $studentID)
                                                        ->where('status', '3')
                                                        ->whereNull('deleted_at')
                                                        ->whereDate('from_date', '<=', $data[5]['value'])
                                                        ->whereDate('to_date', '>=', $data[5]['value'])
                                                        ->first();
                                                    if ($get_data != '') {
                                                        $leaveData = [
                                                            'from_date' => $get_data->from_date,
                                                            'to_date' => $get_data->to_date,
                                                            'leave_type' => $get_data->leave_type,
                                                        ];
                                                    } else {
                                                        $check_bulk_od = BulkOD::where(['user_name_id' => $studentID, 'status' => 1])->whereNull('deleted_at')
                                                            ->whereDate('from_date', '<=', $data[5]['value'])
                                                            ->whereDate('to_date', '>=', $data[5]['value'])
                                                            ->first();

                                                        if ($check_bulk_od != '') {
                                                            $duration = $check_bulk_od->duration;

                                                            if ($duration == 'period') {
                                                                $from_period = $check_bulk_od->from_period;
                                                                $to_period = $check_bulk_od->to_period;
                                                                $req_period = $data[4]['value'];
                                                                if ($req_period >= $from_period && $req_period <= $to_period) {
                                                                    $leaveData = [
                                                                        'from_date' => $check_bulk_od->from_date,
                                                                        'to_date' => $check_bulk_od->to_date,
                                                                        'leave_type' => 'Institute OD',
                                                                    ];
                                                                } else {
                                                                    $leaveData = '';
                                                                }
                                                            } else {
                                                                $leaveData = [
                                                                    'from_date' => $check_bulk_od->from_date,
                                                                    'to_date' => $check_bulk_od->to_date,
                                                                    'leave_type' => 'Institute OD',
                                                                ];
                                                            }
                                                        } else {
                                                            $leaveData = '';
                                                        }
                                                    }
                                                    if (count($get_attendance) > 0) {
                                                        foreach ($get_attendance as $attendance) {
                                                            if ($student_data->student == $attendance->student) {
                                                                $leaveData = $attendance->attendance;
                                                            }
                                                        }
                                                    }
                                                    $get_student->leave = $leaveData;
                                                    array_push($student_list, $get_student);
                                                }
                                            }
                                        }
                                    }
                                } else {

                                    foreach ($get_students as $get_student) {

                                        if (in_array($get_student->user_name_id, $checkSubjectRegArray)) {
                                            $studentID = $get_student->user_name_id;

                                            $get_data = DB::table('student_leave_apply')
                                                ->where('user_name_id', $studentID)
                                                ->where('status', '3')
                                                ->whereNull('deleted_at')
                                                ->whereDate('from_date', '<=', $data[5]['value'])
                                                ->whereDate('to_date', '>=', $data[5]['value'])
                                                ->first();

                                            if ($get_data != '') {
                                                $leaveData = [
                                                    'from_date' => $get_data->from_date,
                                                    'to_date' => $get_data->to_date,
                                                    'leave_type' => $get_data->leave_type,
                                                ];
                                            } else {
                                                $check_bulk_od = BulkOD::where(['user_name_id' => $studentID, 'status' => 1])->whereNull('deleted_at')
                                                    ->whereDate('from_date', '<=', $data[5]['value'])
                                                    ->whereDate('to_date', '>=', $data[5]['value'])
                                                    ->first();
                                                // dd($check_bulk_od);
                                                if ($check_bulk_od != '') {
                                                    $duration = $check_bulk_od->duration;

                                                    if ($duration == 'period') {
                                                        $from_period = $check_bulk_od->from_period;
                                                        $to_period = $check_bulk_od->to_period;
                                                        $req_period = $data[4]['value'];
                                                        if ($req_period >= $from_period && $req_period <= $to_period) {
                                                            $leaveData = [
                                                                'from_date' => $check_bulk_od->from_date,
                                                                'to_date' => $check_bulk_od->to_date,
                                                                'leave_type' => 'Institute OD',
                                                            ];
                                                        } else {
                                                            $leaveData = '';
                                                        }
                                                    } else {
                                                        $leaveData = [
                                                            'from_date' => $check_bulk_od->from_date,
                                                            'to_date' => $check_bulk_od->to_date,
                                                            'leave_type' => 'Institute OD',
                                                        ];
                                                    }
                                                } else {
                                                    $leaveData = '';
                                                }
                                            }
                                            if (count($get_attendance) > 0) {
                                                foreach ($get_attendance as $attendance) {
                                                    if ($studentID == $attendance->student) {
                                                        $leaveData = $attendance->attendance;
                                                    }
                                                }
                                            }
                                            $get_student->leave = $leaveData;
                                            array_push($student_list, $get_student);
                                        }
                                    }
                                }
                            }
                            if (count($student_list) > 0) {
                                return response()->json(['selected_day' => $selected_day, 'date' => $data[6]['value'], 'class_name' => $data[2]['value'], 'students' => $student_list, 'period' => $data[7]['value'], 'get_staff' => $get_staff, 'enroll_master_number' => $data[3]['value'], 'subject' => $data[1]['value'], 'units' => $units ? $units : null, 'classSubject' => $classSubject, 'takenUnits' => $takenUnits, 'theUnit' => $theUnit, 'theTopic' => $theTopic]);
                            } else {
                                return response()->json(['selected_day' => $selected_day, 'date' => $data[6]['value'], 'class_name' => $data[2]['value'], 'students' => [], 'period' => $data[7]['value'], 'get_staff' => $get_staff, 'enroll_master_number' => $data[3]['value'], 'subject' => $data[1]['value'], 'units' => $units ? $units : null, 'classSubject' => $classSubject, 'takenUnits' => $takenUnits, 'theUnit' => $theUnit, 'theTopic' => $theTopic]);
                            }
                        } else {
                            if ($check_ay_calendar->dayorder == 4 || $check_ay_calendar->dayorder == 1 || $check_ay_calendar->dayorder == 2 || $check_ay_calendar->dayorder == 3) {
                                $order = 'Holi Day';
                            } else if ($check_ay_calendar->dayorder == 5) {
                                $order = 'No Order Day';
                            } else if ($check_ay_calendar->dayorder == 14) {
                                $order = 'Modal Exam';
                            } else if ($check_ay_calendar->dayorder == 15) {
                                $order = 'IAT';
                            } else if ($check_ay_calendar->dayorder == 19) {
                                $order = 'College Day';
                            } else if ($check_ay_calendar->dayorder == 6) {
                                $order = 'Unit Test';
                            }
                            // dd($order);
                            return response()->json(['error' => $order]);
                        }
                    } else {
                        return response()->json(['error' => "Academic Calendar Not Found"]);
                    }
                } else {
                    return response()->json(['selected_day' => null, 'date' => null, 'class_name' => null, 'students' => [], 'period' => null, 'get_staff' => $get_staff, 'enroll_master_number' => $data[3]['value'], 'subject' => $data[1]['value'], 'units' => $units ? $units : null, 'classSubject' => $classSubject, 'takenUnits' => $takenUnits, 'theUnit' => $theUnit, 'theTopic' => $theTopic]);
                }
            } else {
                return response()->json(['error' => 'Already Attendance Taken']);
            }
        }
    }

    public function got_list(Request $request)
    {
        $student_list = [];
        if (isset($request->form_data) && $request->form_data != '') {

            $data = $request->form_data;
            // dd($data);
            $find_class = CourseEnrollMaster::where('id', $data[3]['value'])->first();
            $toDay = Carbon::now()->format('Y-m-d');
            $todayAttendance = false;
            if ($toDay == $data[6]['value']) {
                $todayAttendance = true;
            }

            $user_id = auth()->user()->id;
            $check_attendanceRecord = AttendanceRecord::where(['actual_date' => $data[6]['value'], 'period' => $data[7]['value'], 'subject' => $data[1]['value'], 'enroll_master' => $data[3]['value']])->get();

            if (count($check_attendanceRecord) > 0) {
                $get_staffs = DB::table('role_user')->where(['user_id' => $user_id])->first();
                // dd($get_staffs);
                if ($get_staffs) {
                    $get_staff = $get_staffs->user_id;
                } else {
                    $get_staff = null;
                }

                $units = LessonPlans::where([
                    'class' => $data[3]['value'],
                    'subject' => $data[1]['value'],
                ])->get();
                // dd($units);
                $subject = Subject::find($data[1]['value']);
                if ($subject) {
                    $classSubject = $subject->name . ' (' . $subject->subject_code . ') ';
                } else {
                    $classSubject = $data[1]['value'];
                }
                $dateString = $data[6]['value'];

                $getDay = Carbon::parse($dateString);

                if (isset($data[10])) {
                    $selected_batchDay = $data[10]['value'];
                    $allocated_students = StudentPeriodAllocate::where(['class' => $data[3]['value'], 'batch' => $selected_batchDay])->groupby('student')->select('student')->get();
                } else {
                    $selected_batchDay = null;
                    $allocated_students = [];
                }

                $attendance_record = AttendanceRecord::where(['enroll_master' => $data[3]['value'], 'actual_date' => $dateString, 'period' => $data[7]['value'], 'subject' => $data[1]['value']])->first();

                $get_attendance = AttendenceTable::where(['enroll_master' => $data[3]['value'], 'period' => $data[7]['value'], 'subject' => $data[1]['value'], 'date' => $dateString])->get();

                if ($find_class) {
                    $get_students = Student::where('enroll_master_id', $data[3]['value'])->orderBy('register_no', 'asc')->get();

                    if ($get_students) {
                        foreach ($get_students as $get_student) {
                            if (count($allocated_students) > 0) {
                                foreach ($allocated_students as $student_data) {
                                    if ($student_data->student == $get_student->user_name_id) {

                                        $studentID = $get_student->user_name_id;

                                        $get_data = DB::table('student_leave_apply')
                                            ->where('user_name_id', $studentID)
                                            ->where('status', '3')
                                            ->whereNull('deleted_at')
                                            ->whereDate('from_date', '<=', $data[6]['value'])
                                            ->whereDate('to_date', '>=', $data[6]['value'])
                                            ->first();

                                        if ($get_data) {
                                            $leaveData = [
                                                'from_date' => $get_data->from_date,
                                                'to_date' => $get_data->to_date,
                                                'leave_type' => $get_data->leave_type,
                                            ];
                                        } else {
                                            $check_bulk_od = BulkOD::where(['user_name_id' => $studentID, 'status' => 1])->whereNull('deleted_at')
                                                ->whereDate('from_date', '<=', $data[6]['value'])
                                                ->whereDate('to_date', '>=', $data[6]['value'])
                                                ->first();
                                            if ($check_bulk_od != '') {
                                                $duration = $check_bulk_od->duration;

                                                if ($duration == 'period') {
                                                    $from_period = $check_bulk_od->from_period;
                                                    $to_period = $check_bulk_od->to_period;
                                                    $req_period = $data[7]['value'];
                                                    if ($req_period >= $from_period && $req_period <= $to_period) {
                                                        $leaveData = [
                                                            'from_date' => $check_bulk_od->from_date,
                                                            'to_date' => $check_bulk_od->to_date,
                                                            'leave_type' => 'Institute OD',
                                                        ];
                                                    } else {
                                                        $leaveData = '';
                                                    }
                                                } else {
                                                    $leaveData = [
                                                        'from_date' => $check_bulk_od->from_date,
                                                        'to_date' => $check_bulk_od->to_date,
                                                        'leave_type' => 'Institute OD',
                                                    ];
                                                }
                                            } else {
                                                $leaveData = '';
                                            }
                                        }

                                        if (count($get_attendance) > 0) {
                                            foreach ($get_attendance as $attendance) {
                                                if ($student_data->student == $attendance->student) {
                                                    $leaveData = $attendance->attendance;
                                                }
                                            }
                                        }
                                        $get_student->leave = $leaveData;
                                        array_push($student_list, $get_student);
                                    }
                                }
                            } else {

                                $studentID = $get_student->user_name_id;

                                $get_data = DB::table('student_leave_apply')
                                    ->where('user_name_id', $studentID)
                                    ->where('status', '3')
                                    ->whereNull('deleted_at')
                                    ->whereDate('from_date', '<=', $data[6]['value'])
                                    ->whereDate('to_date', '>=', $data[6]['value'])
                                    ->first();

                                if ($get_data) {
                                    $leaveData = [
                                        'from_date' => $get_data->from_date,
                                        'to_date' => $get_data->to_date,
                                        'leave_type' => $get_data->leave_type,
                                    ];
                                } else {
                                    $check_bulk_od = BulkOD::where(['user_name_id' => $studentID, 'status' => 1])->whereNull('deleted_at')
                                        ->whereDate('from_date', '<=', $data[6]['value'])
                                        ->whereDate('to_date', '>=', $data[6]['value'])
                                        ->first();
                                    if ($check_bulk_od != '') {
                                        $duration = $check_bulk_od->duration;

                                        if ($duration == 'period') {
                                            $from_period = $check_bulk_od->from_period;
                                            $to_period = $check_bulk_od->to_period;
                                            $req_period = $data[4]['value'];
                                            if ($req_period >= $from_period && $req_period <= $to_period) {
                                                $leaveData = [
                                                    'from_date' => $check_bulk_od->from_date,
                                                    'to_date' => $check_bulk_od->to_date,
                                                    'leave_type' => 'Institute OD',
                                                ];
                                            } else {
                                                $leaveData = '';
                                            }
                                        } else {
                                            $leaveData = [
                                                'from_date' => $check_bulk_od->from_date,
                                                'to_date' => $check_bulk_od->to_date,
                                                'leave_type' => 'Institute OD',
                                            ];
                                        }
                                    } else {
                                        $leaveData = '';
                                    }
                                }

                                if (count($get_attendance) > 0) {
                                    foreach ($get_attendance as $attendance) {
                                        if ($studentID == $attendance->student) {
                                            $leaveData = $attendance->attendance;
                                        }
                                    }
                                }

                                $get_student->leave = $leaveData;
                                array_push($student_list, $get_student);
                            }
                        }

                        if (count($student_list) > 0) {
                            return response()->json(['date' => $data[6]['value'], 'class_name' => $data[2]['value'], 'students' => $student_list, 'period' => $data[4]['value'], 'get_staff' => $get_staff, 'enroll_master_number' => $data[3]['value'], 'subject' => $data[1]['value'], 'units' => $units ? $units : null, 'classSubject' => $classSubject, 'attendance_record' => $attendance_record, 'stage' => $check_attendanceRecord[0]['status'], 'lab_batch' => $check_attendanceRecord[0]['lab_batch'], 'todayAttendance' => $todayAttendance]);
                        } else {
                            return response()->json(['date' => $data[6]['value'], 'class_name' => $data[2]['value'], 'students' => [], 'period' => $data[4]['value'], 'get_staff' => $get_staff, 'enroll_master_number' => $data[3]['value'], 'subject' => $data[1]['value'], 'units' => $units ? $units : null, 'classSubject' => $classSubject, 'attendance_record' => $attendance_record, 'stage' => $check_attendanceRecord[0]['status'], 'lab_batch' => $check_attendanceRecord[0]['lab_batch'], 'todayAttendance' => $todayAttendance]);
                        }
                    } else {
                        return response()->json(['date' => null, 'class_name' => null, 'students' => [], 'period' => null, 'get_staff' => $get_staff, 'enroll_master_number' => $data[3]['value'], 'subject' => $data[1]['value'], 'units' => $units ? $units : null, 'classSubject' => $classSubject, 'attendance_record' => '', 'stage' => $check_attendanceRecord[0]['status'], 'lab_batch' => $check_attendanceRecord[0]['lab_batch'], 'todayAttendance' => $todayAttendance]);
                    }
                } else {
                    return response()->json(['error' => 'Class Not Found']);
                }
            } else {
                return response()->json(['error' => 'Attendance Not Taken Yet']);
            }
        } else {
            return response()->json(['error' => 'Technical Error']);
        }
    }

    public function takePeriods(Request $request)
    {
        $enrollMaster = $request->class;
        $getEnroll = CourseEnrollMaster::where(['id' => $enrollMaster])->select('enroll_master_number')->first();

        if ($getEnroll != '') {
            $explode = explode('/', $getEnroll->enroll_master_number);
            $theCourse = ToolsCourse::where(['name' => $explode[1]])->select('id')->first();
            if ($theCourse != '') {
                $gotCourse = $theCourse->id;
            } else {
                return response()->json(['status' => false, 'taken_periods' => [], 'data' => 'Course Not Found']);
            }
            $theAY = AcademicYear::where(['name' => $explode[2]])->select('id')->first();
            if ($theAY != '') {
                $gotAY = $theAY->id;
            } else {
                return response()->json(['status' => false, 'taken_periods' => [], 'data' => 'AY Not Found']);
            }
            $gotSem = $explode[3];

            if ($gotSem == '1' || $gotSem == '2') {
                $gotYear = '01';
            } elseif ($gotSem == '3' || $gotSem == '4') {
                $gotYear = '02';
            } elseif ($gotSem == '5' || $gotSem == '6') {
                $gotYear = '03';
            } elseif ($gotSem == '7' || $gotSem == '8') {
                $gotYear = '04';
            } else {
                $gotYear = null;
            }
            if ($gotSem == '1' || $gotSem == '3' || $gotSem == '5' || $gotSem == '7') {
                $semType = 'ODD';
            } else {
                $semType = 'EVEN';
            }

            $gotSec = $explode[4];
            // dd($gotAY,$gotCourse,$gotSem,$gotYear,$gotSec);
        } else {
            return response()->json(['status' => false, 'taken_periods' => [], 'data' => 'Class Not Found']);
        }
        if ($gotYear != null) {

            $targetDate = date_create_from_format('Y-m-d', $request->date);
            $currentDate = date_create();

            // Remove the time portion from the dates
            $targetDate->setTime(0, 0, 0);
            $currentDate->setTime(0, 0, 0);

            if ($targetDate < $currentDate) {
                $checkCC = CollegeCalender::where(['academic_year' => $explode[2], 'semester_type' => $semType, 'batch' => $gotYear])->select('past_attendance_control')->first();

                if ($checkCC != '') {
                    if ($checkCC->past_attendance_control == 0) {
                        return response()->json(['status' => false, 'taken_periods' => [], 'data' => 'Access Denied To Take Past Days Attendance']);
                    }
                } else {
                    return response()->json(['status' => false, 'taken_periods' => [], 'data' => 'Academic Calendar Not Created']);
                }
            }
            $check_attendanceRecord_1 = AttendanceRecord::where(['actual_date' => $request->date, 'enroll_master' => $request->class])->select('period')->get();
            $check_attendanceRecord_2 = AttendanceRecord::where(['actual_date' => $request->date, 'enroll_master' => $request->class, 'subject' => $request->subject])->where('status', '100')->select('period')->get();
            $check_examattendance = Examattendance::where(['date' => $request->date, 'subject' => $request->subject, 'year' => $gotYear, 'course' => $gotCourse, 'sem' => $gotSem, 'section' => $gotSec, 'acyear' => $gotAY])->select('exame_id')->first();
            $removablePeriods = [];
            if ($check_examattendance != '') {
                $check_examCreation = ExamTimetableCreation::where(['id' => $check_examattendance->exame_id])->select('start_time', 'end_time')->first();

                if ($check_examCreation != '') {
                    $start_time = substr($check_examCreation->start_time, 0, -3);
                    $end_time = substr($check_examCreation->end_time, 0, -3);
                    if ($start_time >= '8:00' && $end_time <= '9:00') {
                        array_push($removablePeriods, 1);
                    } elseif ($start_time >= '8:00' && $end_time <= '10:00') {
                        array_push($removablePeriods, 1, 2);
                    } elseif ($start_time >= '8:00' && ($end_time <= '12:00')) {
                        array_push($removablePeriods, 1, 2, 3, 4);
                    } elseif ($start_time >= '9:00' && $end_time <= '10:00') {
                        array_push($removablePeriods, 2);
                    } elseif ($start_time >= '9:00' && $end_time <= '12:00') {
                        array_push($removablePeriods, 2, 3, 4);
                    } elseif ($start_time >= '10:00' && ($end_time <= '12:00')) {
                        array_push($removablePeriods, 3, 4);
                    } elseif ($start_time >= '12:00' && ($end_time <= '1:20')) {
                        array_push($removablePeriods, 5);
                    } elseif ($start_time >= '1:20' && ($end_time <= '2:10')) {
                        array_push($removablePeriods, 6);
                    } elseif ($start_time >= '12:00' && ($end_time <= '2:10')) {
                        array_push($removablePeriods, 5, 6);
                    } elseif ($start_time >= '12:00' && ($end_time <= '3:00')) {
                        array_push($removablePeriods, 5, 6, 7);
                    }
                }
            }

            $periods = [1, 2, 3, 4, 5, 6, 7, 8];
            $taken_periods = [];
            if (count($check_attendanceRecord_1) > 0) {
                foreach ($check_attendanceRecord_1 as $record) {
                    if (in_array($record['period'], $periods)) {
                        array_push($taken_periods, $record['period']);
                    }
                }
            }
            if (count($check_attendanceRecord_2) > 0) {
                foreach ($check_attendanceRecord_2 as $record) {
                    foreach ($taken_periods as $key => $value) {
                        if ($value == $record['period']) {
                            unset($taken_periods[$key]);
                        }
                    }
                }
                $taken_periods = array_values($taken_periods);
            }
            if (count($removablePeriods) > 0) {
                foreach ($removablePeriods as $record) {
                    if (!in_array($record, $taken_periods)) {
                        array_push($taken_periods, (string) $record);
                    }
                }
            }

            return response()->json(['status' => true, 'taken_periods' => $taken_periods, 'data' => '']);
        } else {
            return response()->json(['status' => false, 'taken_periods' => [], 'data' => 'Couldn\'t Get The Year']);
        }
    }

    public function takenPeriods(Request $request)
    {
        $check_attendanceRecord = AttendanceRecord::where(['actual_date' => $request->date, 'enroll_master' => $request->class, 'subject' => $request->subject])->select('period')->get();
        $periods = [1, 2, 3, 4, 5, 6, 7, 8];
        $taken_periods = [];
        if (count($check_attendanceRecord) > 0) {
            foreach ($check_attendanceRecord as $record) {
                // dd($record);
                if (in_array($record['period'], $periods)) {
                    array_push($taken_periods, $record['period']);
                }
            }
        }
        // dd($check_attendanceRecord);
        return response()->json(['taken_periods' => $taken_periods]);
    }

    public function getBatch(Request $request)
    {
        $get_batch = StudentPeriodAllocate::where(['class' => $request->class])->groupby('batch')->select('batch')->get();

        return response()->json(['batch' => $get_batch]);
    }

    public function getPeriod(Request $request)
    {
        $enrollMaster = $request->class;
        $getEnroll = CourseEnrollMaster::where(['id' => $enrollMaster])->select('enroll_master_number')->first();
        if ($getEnroll != '') {
            $explode = explode('/', $getEnroll->enroll_master_number);
            $theCourse = ToolsCourse::where(['name' => $explode[1]])->select('id')->first();
            if ($theCourse != '') {
                $gotCourse = $theCourse->id;
            } else {
                $gotCourse = null;
            }
            $theAY = AcademicYear::where(['name' => $explode[2]])->select('id')->first();
            if ($theAY != '') {
                $gotAY = $theAY->id;
            } else {
                $gotAY = null;
            }
            $gotSem = $explode[3];

            if ($gotSem == '1' || $gotSem == '2') {
                $gotYear = '01';
            } elseif ($gotSem == '3' || $gotSem == '4') {
                $gotYear = '02';
            } elseif ($gotSem == '5' || $gotSem == '6') {
                $gotYear = '03';
            } else {
                $gotYear = '04';
            }

            $gotSec = $explode[4];
        } else {
            return response()->json(['taken_periods' => []]);
        }

        $check_attendanceRecord_1 = AttendanceRecord::where(['actual_date' => $request->date, 'enroll_master' => $request->class])->where('lab_batch', '=', null)->select('period')->get();
        $check_attendanceRecord_4 = AttendanceRecord::where(['actual_date' => $request->date, 'enroll_master' => $request->class])->where('lab_batch', '!=', null)->select('period')->get();
        $check_attendanceRecord_2 = AttendanceRecord::where(['actual_date' => $request->date, 'enroll_master' => $request->class, 'subject' => $request->subject])->where('status', '100')->select('period')->get();
        $check_attendanceRecord_3 = AttendanceRecord::where(['actual_date' => $request->date, 'enroll_master' => $request->class, 'subject' => $request->subject])->where('status', '!=', '100')->select('period')->get();
        $check_examattendance = Examattendance::where(['date' => $request->date, 'subject' => $request->subject, 'year' => $gotYear, 'course' => $gotCourse, 'sem' => $gotSem, 'section' => $gotSec, 'acyear' => $gotAY])->select('exame_id')->first();
        $removablePeriods = [];
        if ($check_examattendance != '') {
            $check_examCreation = ExamTimetableCreation::where(['id' => $check_examattendance->exame_id])->select('start_time', 'end_time')->first();
            if ($check_examCreation != '') {
                $start_time = substr($check_examCreation->start_time, 0, -3);
                $end_time = substr($check_examCreation->end_time, 0, -3);
                if ($start_time >= '8:00' && $end_time <= '9:00') {
                    array_push($removablePeriods, 1);
                } elseif ($start_time >= '8:00' && $end_time <= '10:00') {
                    array_push($removablePeriods, 1, 2);
                } elseif ($start_time >= '8:00' && ($end_time <= '12:00')) {
                    array_push($removablePeriods, 1, 2, 3, 4);
                } elseif ($start_time >= '9:00' && $end_time <= '10:00') {
                    array_push($removablePeriods, 2);
                } elseif ($start_time >= '9:00' && $end_time <= '12:00') {
                    array_push($removablePeriods, 2, 3, 4);
                } elseif ($start_time >= '10:00' && ($end_time <= '12:00')) {
                    array_push($removablePeriods, 3, 4);
                } elseif ($start_time >= '12:00' && ($end_time <= '1:20')) {
                    array_push($removablePeriods, 5);
                } elseif ($start_time >= '1:20' && ($end_time <= '2:10')) {
                    array_push($removablePeriods, 6);
                } elseif ($start_time >= '12:00' && ($end_time <= '2:10')) {
                    array_push($removablePeriods, 5, 6);
                } elseif ($start_time >= '12:00' && ($end_time <= '3:00')) {
                    array_push($removablePeriods, 5, 6, 7);
                }
            }
        }

        $periods = [1, 2, 3, 4, 5, 6, 7, 8];
        $taken_periods = [];
        $gotPeriod = [];
        $numberCounts = [];
        if (count($check_attendanceRecord_1) > 0) {
            foreach ($check_attendanceRecord_1 as $data) {
                $checky = array_search($data->period, $periods);

                if ($checky !== false) {

                    unset($periods[$checky]);
                }
                $periods = array_values($periods);
            }
        }

        if (count($check_attendanceRecord_4) > 0) {

            foreach ($check_attendanceRecord_4 as $data) {

                if (array_key_exists($data->period, $numberCounts)) {

                    $numberCounts[$data->period]++;

                    if ($numberCounts[$data->period] == 2) {
                        $gotPeriod[] = $data->period;
                    }
                } else {

                    $numberCounts[$data->period] = 1;
                }
            }
        }
        $period = array_diff($periods, $gotPeriod);

        if (count($check_attendanceRecord_3) > 0) {
            foreach ($check_attendanceRecord_3 as $record) {
                foreach ($period as $key => $value) {
                    if ($value == $record['period']) {
                        unset($period[$key]);
                    }
                }
            }
        }

        $taken_periods = array_values($period);
        if (count($check_attendanceRecord_2) > 0) {
            foreach ($check_attendanceRecord_2 as $record) {
                if (!in_array($record->period, $taken_periods)) {
                    array_push($taken_periods, (int) $record->period);
                }
            }
            $sort = sort($taken_periods);
        }

        if (count($removablePeriods) > 0) {
            foreach ($removablePeriods as $record) {
                foreach ($taken_periods as $key => $value) {
                    if ($value == $record) {
                        unset($taken_periods[$key]);
                    }
                }
            }
            $taken_periods = array_values($taken_periods);
        }

        return response()->json(['taken_periods' => $taken_periods]);
    }

    public function checkPeriod(Request $request)
    {

        $check_attendanceRecord = AttendanceRecord::where(['enroll_master' => $request->class, 'subject' => $request->subject, 'period' => $request->period, 'actual_date' => $request->date])->get();
        $lab = [];
        if (count($check_attendanceRecord) > 0) {
            if ($check_attendanceRecord[0]->lab_batch != null) {
                $lab = $check_attendanceRecord;
            }
        }
        return response()->json(['lab_hour' => $lab]);
    }

    public function store(Request $request)
    {
        // dd($request);
        if ($request) {

            $form_data = $request->form_data;
            $data_count = count($form_data);
            $class = $request->unit_class;
            $subject = $request->unit_subject;
            $staff = $request->unit_staff;
            $unit = $request->unit;
            $topic = $request->topic;
            $period = $request->period;
            $date = $request->date;
            $day = strtoupper($request->day);
            $lab_batch = $request->lab_batch;

            $records = [];

            if ($lab_batch != null) {
                $delete = DB::table('attendence_tables')->where(['date' => $date, 'period' => $period, 'enroll_master' => $class, 'subject' => $subject])->delete();
            } else {
                $delete = DB::table('attendence_tables')->where(['date' => $date, 'period' => $period, 'enroll_master' => $class])->delete();
            }

            for ($i = 0; $i < $data_count; $i++) {

                $data = $form_data[$i];
                // dd($data);
                $attendance = $data[1]['value'];

                $student = $data[0]['value'];

                $insert = AttendenceTable::create([
                    'date' => $date,
                    'day' => $day,
                    'period' => $period,
                    'staff' => $staff,
                    'enroll_master' => $class,
                    'subject' => $subject,
                    'student' => $student,
                    'attendance' => $attendance,
                    'unit' => $unit,
                    'topic' => $topic,
                ]);
                // }
            }

            $newSubject = Subject::find($subject);

            $check = AttendanceRecord::where([
                'actual_date' => $date,
                'period' => $period,
                'subject' => $subject,
                'enroll_master' => $class,
            ])->get();

            if (count($check) > 0) {
                $update = AttendanceRecord::where('id', $check[0]->id)->update(['date' => Carbon::now()->format('Y-m-d'), 'status' => '1', 'updated_at' => Carbon::now(), 'topic' => $topic, 'unit' => $unit, 'lab_batch' => $lab_batch]);

                if (count($check) > 1) {
                    $delete = DB::table('attendance_record')->where(['actual_date' => $date, 'period' => $period, 'subject' => $subject, 'enroll_master' => $class])->where('id', '!=', $check[0]->id)->delete();
                }
            } else {
                $insert = AttendanceRecord::insert([
                    'actual_date' => $date,
                    'period' => $period,
                    'staff' => $staff,
                    'status' => '1',
                    'subject' => $subject,
                    'enroll_master' => $class,
                    'date' => Carbon::now()->format('Y-m-d'),
                    'topic' => $topic,
                    'unit' => $unit,
                    'lab_batch' => $lab_batch,
                    'created_at' => Carbon::now(),
                ]);
            }

            // dd($request->enroll_number);
            $response = ['message' => 'Attendance Records Submitted Successfully'];

            return response()->json($response, 200);
        } else {
            $response = false;
        }

        if ($response) {
            return response()->json(['message' => 'Attendance Records Submitted Successfully'], 200);
        } else {
            return response()->json(['message' => 'Attendance Records Submition Failed'], 400);
        }
    }

    public function unitGet(Request $request)
    {
        if ($request) {
            $takenTopics = [];
            $getTopics = AttendanceRecord::where(['subject' => $request->subject, 'enroll_master' => $request->class, 'unit' => $request->unit])->groupBy('topic')->select('topic')->get();
            if (count($getTopics) > 0) {
                foreach ($getTopics as $topicData) {
                    array_push($takenTopics, $topicData->topic);
                }
            }
            $topic = LessonPlans::select('topic', 'topic_no')
                ->where([
                    'class' => $request->class,
                    'subject' => $request->subject,
                    'unit_no' => $request->unit,
                ])
                ->distinct()
                ->get();

            // dd($topic);
            return response()->json(['topic' => $topic, 'takenTopics' => $takenTopics]);
        }
    }

    public function editRequesting(Request $request)
    {
        $subject = $request->subject;
        $class = $request->class;
        $period = $request->period;
        $date = $request->date;
        $reason = $request->reason;

        $check = AttendanceRecord::where(['period' => $period, 'actual_date' => $date, 'subject' => $subject, 'enroll_master' => $class])->get();

        if (count($check) > 0) {
            $update_first = AttendanceRecord::where('id', $check[0]->id)->update([
                'staff' => auth()->user()->id,
                'status' => '0',
                'subject' => $subject,
                'enroll_master' => $class,
                'reason' => $reason,
                'updated_at' => Carbon::now(),
                'date' => Carbon::now()->format('Y-m-d'),
            ]);
            if (count($check) > 1) {
                $delete = AttendanceRecord::where(['period' => $period, 'actual_date' => $date, 'subject' => $subject, 'enroll_master' => $class])->where('id', '!=', $check[0]->id)->update([
                    'deleted_at' => Carbon::now(),
                ]);
            }
        } else {
            $insert = AttendanceRecord::insert([
                'date' => Carbon::now()->format('Y-m-d'),
                'period' => $period,
                'actual_date' => $date,
                'staff' => auth()->user()->id,
                'status' => '0',
                'subject' => $subject,
                'enroll_master' => $class,
                'reason' => $reason,
                'created_at' => Carbon::now(),
            ]);
        }

        $userAlert = new UserAlert;
        $userAlert->alert_text = auth()->user()->name . ' Requesting For Approval Of Student Attendance Edit.';
        $userAlert->alert_link = route('admin.student-att-modification.index', ['status' => 'Edit']);
        $userAlert->save();
        $userAlert->users()->sync([1]); // Sync staff ID

        // Additional actions or notifications if needed

        return response()->json(['data' => 'success'], 200);
    }

    public function editAttendance(Request $request)
    {
        $subject = $request->subject;
        $class = $request->class;
        $period = $request->period;
        $date = $request->date;

        $check = AttendanceRecord::where(['period' => $period, 'actual_date' => $date, 'subject' => $subject, 'enroll_master' => $class])->get();

        if (count($check) > 0) {
            $update_first = AttendanceRecord::where('id', $check[0]->id)->update([
                'staff' => auth()->user()->id,
                'status' => '100',
                'subject' => $subject,
                'enroll_master' => $class,
                'reason' => 'Self Edit',
                'updated_at' => Carbon::now(),
                'date' => Carbon::now()->format('Y-m-d'),
            ]);
            if (count($check) > 1) {
                $delete = AttendanceRecord::where(['period' => $period, 'actual_date' => $date, 'subject' => $subject, 'enroll_master' => $class])->where('id', '!=', $check[0]->id)->update([
                    'deleted_at' => Carbon::now(),
                ]);
            }
        } else {
            $insert = AttendanceRecord::insert([
                'date' => Carbon::now()->format('Y-m-d'),
                'period' => $period,
                'actual_date' => $date,
                'staff' => auth()->user()->id,
                'status' => '100',
                'subject' => $subject,
                'enroll_master' => $class,
                'reason' => 'Self Edit',
                'created_at' => Carbon::now(),
            ]);
        }

        return response()->json(['data' => 'success'], 200);
    }

    public function deleteRequesting(Request $request)
    {
        $subject = $request->subject;
        $class = $request->class;
        $period = $request->period;
        $date = $request->date;
        $reason = $request->reason;

        $check = AttendanceRecord::where(['period' => $period, 'actual_date' => $date, 'subject' => $subject, 'enroll_master' => $class])->get();

        if (count($check) > 0) {
            $update_first = AttendanceRecord::where('id', $check[0]->id)->update([
                'staff' => auth()->user()->id,
                'status' => '55',
                'subject' => $subject,
                'enroll_master' => $class,
                'reason' => $reason,
                'updated_at' => Carbon::now(),
                'date' => Carbon::now()->format('Y-m-d'),
            ]);
            if (count($check) > 1) {
                $delete = AttendanceRecord::where(['period' => $period, 'actual_date' => $date, 'subject' => $subject, 'enroll_master' => $class])->where('id', '!=', $check[0]->id)->update([
                    'deleted_at' => Carbon::now(),
                ]);
            }
        } else {
            $insert = AttendanceRecord::insert([
                'date' => Carbon::now()->format('Y-m-d'),
                'period' => $period,
                'actual_date' => $date,
                'staff' => auth()->user()->id,
                'status' => '55',
                'subject' => $subject,
                'enroll_master' => $class,
                'reason' => $reason,
                'created_at' => Carbon::now(),
            ]);
        }

        $userAlert = new UserAlert;
        $userAlert->alert_text = auth()->user()->name . ' Requesting For Approval Of Student Attendance Delete.';
        $userAlert->alert_link = route('admin.student-att-modification.index', ['status' => 'Delete']);
        $userAlert->save();
        $userAlert->users()->sync([1]); // Sync staff ID

        return response()->json(['data' => 'success'], 200);
    }

    public function attendanceSummary(Request $request)
    {
        $departments = ToolsDepartment::pluck('name', 'id')->prepend('Select Department', '');

        $academic_years = AcademicYear::pluck('name', 'id')->prepend('Select Academic Year', '');

        return view('admin.studentPeriodAttandance.attendanceSummary', compact('departments', 'academic_years'));
    }

    public function getCourses(Request $request)
    {
        $get_course = [];
        if ($request->department != '') {
            if ($request->department != '5') {
                $get_course = ToolsCourse::where(['department_id' => $request->department])->select('id', 'name', 'short_form', 'department_id')->get();
            } else {
                $get_course = ToolsCourse::select('id', 'name', 'short_form', 'department_id')->get();
            }
        }
        return response()->json(['courses' => $get_course]);
    }

    public function getSections(Request $request)
    {
        $get_section = [];

        if ($request->course != '') {

            $get_sections = Section::where(['course_id' => $request->course])->select('id', 'section')->get();
        }
        return response()->json(['sections' => $get_sections]);
    }

    public function getData(Request $request)
    {
        $dept = $request->department;
        $course = $request->course;
        $ay = $request->ay;
        $semester = $request->semester;
        $section = $request->section;
        $date = $request->date;
        $got_students = null;
        $subject_name = null;
        $subject_code = null;
        $staff_name = null;
        $staff_code = null;
        $list = [];

        if ($semester == '1' || $semester == '2') {
            $batch = '01';
        } else if ($semester == '3' || $semester == '4') {
            $batch = '02';
        } else if ($semester == '5' || $semester == '6') {
            $batch = '03';
        } else if ($semester == '7' || $semester == '8') {
            $batch = '04';
        } else {
            $batch = '';
        }
        // dd($batch);
        $get_day = Carbon::createFromFormat('Y-m-d', $date);
        $ayc_day = $date . ' 00:00:00';
        $day = strtoupper($get_day->format('l'));

        $get_ay = AcademicYear::where(['id' => $ay])->first();
        $get_course = ToolsCourse::where(['id' => $course])->first();
        $get_section = Section::where(['id' => $section])->first();
        $order_of_day = $day;
        // dd($ayc_day);
        if ($batch != '') {
            $get_calendar = AyCalendar::where(['batch' => $batch, 'academic_year' => $get_ay->name, 'date' => $ayc_day])->first();
            if ($get_calendar != '') {
                $dayorder = $get_calendar->dayorder;
                if ($dayorder == 20) {
                    $order = 'MONDAY';
                } else if ($dayorder == 7) {
                    $order = 'TUESDAY';
                } else if ($dayorder == 8) {
                    $order = 'WEDNESDAY';
                } else if ($dayorder == 9) {
                    $order = 'THURSDAY';
                } else if ($dayorder == 10) {
                    $order = 'FRIDAY';
                } else if ($dayorder == 11) {
                    $order = 'SATURDAY';
                } else {
                    $order = '';
                }
                if ($order != '') {
                    $order_of_day = $order;
                } else {

                    if ($dayorder == 4 || $dayorder == 1 || $dayorder == 2 || $dayorder == 3) {
                        $order = 'Holi Day';
                    } else if ($dayorder == 5) {
                        $order = 'No Order Day';
                    } else if ($dayorder == 14) {
                        $order = 'Modal Exam';
                    } else if ($dayorder == 15) {
                        $order = 'IAT';
                    } else if ($dayorder == 19) {
                        $order = 'College Day';
                    } else if ($dayorder == 6) {
                        $order = 'Unit Test';
                    }
                    return response()->json(['status' => $order]);
                }
            } else {
                return response()->json(['status' => "Academic Calendar Not Found"]);
            }
        } else {
            return response()->json(['status' => "Couldn't Get The Mandatory Details"]);
        }

        if ($get_ay == '') {
            return response()->json(['status' => 'Academic Year Not Found']);
        } elseif ($get_course == '') {
            return response()->json(['status' => 'Course Not Found']);
        } elseif ($get_section == '') {
            return response()->json(['status' => 'Section Not Found']);
        } else {
            // dd($get_ay->name);
            $make_enroll = $get_course->name . '/' . $get_ay->name . '/' . $semester . '/' . $get_section->section;
            $get_enroll = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%{$make_enroll}")->first();

            if ($get_enroll != '') {
                $enroll = $get_enroll->id;

                $get_time_table = ClassTimeTableTwo::where(['class_name' => $enroll, 'day' => $order_of_day, 'status' => '1'])->select('subject', 'staff')->groupby('subject', 'staff')->get();

                if (count($get_time_table) > 0) {
                    $subjectList = [];
                    foreach ($get_time_table as $timetable) {
                        $subjectList[] = $timetable->subject;
                        $get_period = ClassTimeTableTwo::where(['class_name' => $enroll, 'day' => $order_of_day, 'status' => '1', 'staff' => $timetable->staff, 'subject' => $timetable->subject])->select('period')->groupBy('period')->orderBy('period')->get();
                        $tempPeriod = '';
                        if (count($get_period) > 0) {
                            foreach ($get_period as $period) {
                                $tempPeriod .= $period->period . ',';
                            }
                            $thePeriods = rtrim($tempPeriod, ',');
                        }

                        $check_sub = is_numeric($timetable->subject);
                        if ($check_sub == true) {
                            $get_sub = Subject::where(['id' => $timetable->subject])->first();
                            if ($get_sub != '') {
                                $subject_name = $get_sub->name;
                                $subject_code = $get_sub->subject_code;
                            }
                        } else {
                            $subject_name = $timetable->subject;
                        }
                        $get_staff = TeachingStaff::where(['user_name_id' => $timetable->staff])->select('name', 'StaffCode')->first();
                        if ($get_staff != '') {
                            $staff_name = $get_staff->name;
                            $staff_code = $get_staff->StaffCode;
                        }

                        $attendance_check = AttendanceRecord::where(['actual_date' => $date, 'enroll_master' => $enroll, 'subject' => $timetable->subject])->whereIn('status', [1, 100])->get();
                        if (count($attendance_check) > 0) {

                            foreach ($attendance_check as $data) {

                                if ($data->lab_batch != null) {
                                    $allocateDay = $data->lab_batch;

                                    $allocated_students = StudentPeriodAllocate::where(['class' => $enroll, 'batch' => $allocateDay])->count();
                                    if ($allocated_students > 0) {
                                        $got_students = $allocated_students;
                                    } else {
                                        $got_students = 0;
                                    }

                                } else {
                                    $get_students = Student::where('enroll_master_id', $enroll)->count();
                                    if ($get_students > 0) {
                                        $got_students = $get_students;
                                    } else {
                                        $get_students = StudentPromotionHistory::where('enroll_master_id', $enroll)->count();
                                        if ($get_students > 0) {
                                            $got_students = $get_students;
                                        } else {
                                            $got_students = 0;
                                        }
                                    }
                                }

                                $get_attendance = AttendenceTable::where(['enroll_master' => $enroll, 'day' => $day, 'period' => $data->period, 'subject' => $timetable->subject, 'date' => $date])->where('attendance', '!=', 'Absent')->get();
                                $attend_students = count($get_attendance);
                                $attendance_status = true;

                                $list_data = ['subject_code' => $subject_code, 'staff_name' => $staff_name, 'staff_code' => $staff_code, 'subject_name' => $subject_name, 'alloted_periods' => $thePeriods, 'period' => $data->period, 'total_students' => $got_students, 'status' => $attendance_status, 'attend_students' => $attend_students];
                                array_push($list, $list_data);
                            }
                        } else {
                            $attendance_check_2 = AttendanceRecord::where(['status' => 0, 'actual_date' => $date, 'enroll_master' => $enroll, 'subject' => $timetable->subject])->get();
                            if (count($attendance_check_2) > 0) {
                                foreach ($attendance_check_2 as $data) {
                                    $attendance_status = 'Requested For Edit';
                                    $attend_students = 0;

                                    $list_data = ['subject_code' => $subject_code, 'staff_name' => $staff_name, 'staff_code' => $staff_code, 'subject_name' => $subject_name, 'alloted_periods' => $thePeriods, 'period' => $data->period, 'total_students' => 0, 'status' => $attendance_status, 'attend_students' => $attend_students];
                                    array_push($list, $list_data);
                                }
                            } else {

                                $attendance_check_3 = AttendanceRecord::where(['status' => 55, 'actual_date' => $date, 'enroll_master' => $enroll, 'subject' => $timetable->subject])->get();
                                // dd($attendance_check_3);
                                if (count($attendance_check_3) > 0) {
                                    foreach ($attendance_check_3 as $data) {
                                        $attendance_status = 'Requested For Delete';
                                        $attend_students = 0;

                                        $list_data = ['subject_code' => $subject_code, 'staff_name' => $staff_name, 'staff_code' => $staff_code, 'subject_name' => $subject_name, 'alloted_periods' => $thePeriods, 'period' => $data->period, 'total_students' => 0, 'status' => $attendance_status, 'attend_students' => $attend_students];
                                        array_push($list, $list_data);
                                    }
                                } else {
                                    $get_students = Student::where('enroll_master_id', $enroll)->get();
                                    if (count($get_students) > 0) {
                                        $got_students = count($get_students);
                                    } else {
                                        $got_students = 0;
                                    }
                                    $list_data = ['subject_code' => $subject_code, 'staff_name' => $staff_name, 'staff_code' => $staff_code, 'subject_name' => $subject_name, 'alloted_periods' => $thePeriods, 'period' => '', 'total_students' => $got_students, 'status' => false, 'attend_students' => 0];
                                    array_push($list, $list_data);
                                }
                            }
                        }
                    }
                    $attendance_check = AttendanceRecord::with('subjects:id,name,subject_code', 'staffs:id,name,employID')->where(['actual_date' => $date, 'enroll_master' => $enroll])->whereNotIn('subject', $subjectList)->whereIn('status', [1, 100])->get();
                    if (count($attendance_check) > 0) {

                        foreach ($attendance_check as $data) {

                            if ($data->lab_batch != null) {
                                $allocateDay = $data->lab_batch;

                                $allocated_students = StudentPeriodAllocate::where(['class' => $enroll, 'batch' => $allocateDay])->count();
                                if ($allocated_students > 0) {
                                    $got_students = $allocated_students;
                                } else {
                                    $got_students = 0;
                                }

                            } else {
                                $get_students = Student::where('enroll_master_id', $enroll)->count();
                                if ($get_students > 0) {
                                    $got_students = $get_students;
                                } else {
                                    $get_students = StudentPromotionHistory::where('enroll_master_id', $enroll)->count();
                                    if ($get_students > 0) {
                                        $got_students = $get_students;
                                    } else {
                                        $got_students = 0;
                                    }
                                }
                            }

                            $get_attendance = AttendenceTable::where(['enroll_master' => $enroll, 'day' => $day, 'period' => $data->period, 'subject' => $data->subject, 'date' => $date])->where('attendance', '!=', 'Absent')->get();
                            $attend_students = count($get_attendance);
                            $attendance_status = true;

                            $check_sub = is_numeric($data->subject);
                            if ($check_sub == true) {
                                if ($data->subjects != null) {
                                    $subject_name = $data->subjects->name;
                                    $subject_code = $data->subjects->subject_code;
                                }
                            } else {
                                $subject_name = $timetable->subject;
                            }
                            if ($data->staffs != null) {
                                $staff_name = $data->staffs->name;
                                $staff_code = $data->staffs->employID;
                            } else {
                                $staff_name = null;
                                $staff_code = null;
                            }
                            $list_data = ['subject_code' => $subject_code, 'staff_name' => $staff_name, 'staff_code' => $staff_code, 'subject_name' => $subject_name, 'alloted_periods' => '', 'period' => $data->period, 'total_students' => $got_students, 'status' => $attendance_status, 'attend_students' => $attend_students];
                            array_push($list, $list_data);
                        }
                    } else {
                        $attendance_check_2 = AttendanceRecord::with('subjects:id,name,subject_code', 'staffs:name,employID')->where(['status' => 0, 'actual_date' => $date, 'enroll_master' => $enroll])->whereNotIn('subject', $subjectList)->get();
                        if (count($attendance_check_2) > 0) {
                            foreach ($attendance_check_2 as $data) {
                                $attendance_status = 'Requested For Edit';
                                $attend_students = 0;
                                $check_sub = is_numeric($data->subject);
                                if ($check_sub == true) {
                                    if ($data->subjects != null) {
                                        $subject_name = $data->subjects->name;
                                        $subject_code = $data->subjects->subject_code;
                                    }
                                } else {
                                    $subject_name = $timetable->subject;
                                }
                                if ($data->staffs != null) {
                                    $staff_name = $data->staffs->name;
                                    $staff_code = $data->staffs->employID;
                                } else {
                                    $staff_name = null;
                                    $staff_code = null;
                                }

                                $list_data = ['subject_code' => $subject_code, 'staff_name' => $staff_name, 'staff_code' => $staff_code, 'subject_name' => $subject_name, 'alloted_periods' => '', 'period' => $data->period, 'total_students' => 0, 'status' => $attendance_status, 'attend_students' => $attend_students];
                                array_push($list, $list_data);
                            }
                        } else {

                            $attendance_check_3 = AttendanceRecord::with('subjects:id,name,subject_code', 'staffs:name,employID')->where(['status' => 55, 'actual_date' => $date, 'enroll_master' => $enroll])->whereNotIn('subject', $subjectList)->get();
                            // dd($attendance_check_3);
                            if (count($attendance_check_3) > 0) {
                                foreach ($attendance_check_3 as $data) {
                                    $attendance_status = 'Requested For Delete';
                                    $attend_students = 0;

                                    $check_sub = is_numeric($data->subject);
                                    if ($check_sub == true) {
                                        if ($data->subjects != null) {
                                            $subject_name = $data->subjects->name;
                                            $subject_code = $data->subjects->subject_code;
                                        }
                                    } else {
                                        $subject_name = $timetable->subject;
                                    }
                                    if ($data->staffs != null) {
                                        $staff_name = $data->staffs->name;
                                        $staff_code = $data->staffs->employID;
                                    } else {
                                        $staff_name = null;
                                        $staff_code = null;
                                    }

                                    $list_data = ['subject_code' => $subject_code, 'staff_name' => $staff_name, 'staff_code' => $staff_code, 'subject_name' => $subject_name, 'alloted_periods' => '', 'period' => $data->period, 'total_students' => 0, 'status' => $attendance_status, 'attend_students' => $attend_students];
                                    array_push($list, $list_data);
                                }
                            }
                        }
                    }
                    $periods = array_column($list, 'period');

                    array_multisort($periods, SORT_ASC, $list);

                    return response()->json(['status' => $list]);
                } else {
                    return response()->json(['status' => 'Class Time Table Not Found']);
                }
            } else {
                return response()->json(['status' => 'Class Not Found']);
            }
        }
    }

    public function attendanceLog(Request $request)
    {
        if (isset($request->class) && isset($request->subject)) {
            $class = $request->class;
            $subject = $request->subject;

            $getClass = CourseEnrollMaster::where(['id' => $class])->select('enroll_master_number')->first();
            if ($getClass != '') {
                $explode = explode('/', $getClass->enroll_master_number);
                $getCourse = ToolsCourse::where(['name' => $explode[1]])->select('short_form')->first();
                if ($getCourse != '') {
                    $theClass = $getCourse->short_form . '/' . $explode[3] . '/' . $explode[4];
                }
            }
            $getSubject = Subject::where(['id' => $subject])->select('name', 'subject_code')->first();
            if ($getSubject != '') {
                $theSubject = $getSubject->name . '  (' . $getSubject->subject_code . ')';
            } else {
                $theSubject = '';
            }
            $getLog = AttendanceRecord::where(['enroll_master' => $class, 'subject' => $request->subject])->select('actual_date')->groupby('actual_date')->orderby('actual_date', 'desc')->get();
            if (count($getLog) > 0) {
                foreach ($getLog as $log) {
                    $getPeriods = AttendanceRecord::where(['enroll_master' => $class, 'subject' => $request->subject, 'actual_date' => $log->actual_date])->select('period')->groupby('period')->get();
                    $log->period = $getPeriods;
                }
            }
            return response()->json(['status' => true, 'data' => $getLog, 'class' => $theClass, 'subject' => $theSubject]);
        } else {
            return response()->json(['status' => false]);
        }
    }

    public function Student_period_Attendance_report($subject, $class, $excel = false)
    {
        if (isset($class) && isset($subject)) {
            $class = $class;
            $subject = $subject;
            $class_name = ClassRoom::where('name', $class)->first();
            $class_shor_form = strtoupper($class_name->short_form);

            $getClass = CourseEnrollMaster::where(['id' => $class])->select('enroll_master_number')->first();

            if ($getClass != '') {
                $explode = explode('/', $getClass->enroll_master_number);
                $getCourse = ToolsCourse::where(['name' => $explode[1]])->select('short_form', 'name')->first();
                $course_name = $getCourse->name;
                $parts = explode('.', $course_name);
                $endpart_Course_name = strtoupper(trim(end($parts)));

                if ($getCourse != '') {
                    $theClass = $getCourse->short_form . '/' . $explode[3] . '/' . $explode[4];
                }
            }
            $getSubject = Subject::where(['id' => $subject])->select('name', 'subject_code')->first();
            $subjec_name = $getSubject->name;
            if ($getSubject != '') {
                $theSubject = $getSubject->name . '  (' . $getSubject->subject_code . ')';
            } else {
                $theSubject = '';
            }
            $getLog = AttendanceRecord::where(['enroll_master' => $class, 'subject' => $subject])->select('actual_date')->groupby('actual_date')->orderby('actual_date', 'desc')->select('actual_date')->get();

            if (count($getLog) > 0) {

                $student = [];
                $students = [];
                $days = [];
                foreach ($getLog as $log) {
                    $getPeriods = AttendanceRecord::where(['enroll_master' => $class, 'subject' => $subject, 'actual_date' => $log->actual_date])->select('period')->groupby('period')->get();
                    $period_ = [];

                    foreach ($getPeriods as $getperiod) {

                        $getPeriodsAttendances = AttendenceTable::where(['enroll_master' => $class, 'period' => $getperiod->period, 'subject' => $subject, 'date' => $log->actual_date])->select('student', 'attendance', 'subject', 'date', 'period')->get();

                        foreach ($getPeriodsAttendances as $id => $getPeriodsAttendance) {

                            $student_name = Student::where(['user_name_id' => $getPeriodsAttendance->student])->select('name', 'register_no')->first();

                            $students[$student_name->register_no]['details'][0] = $student_name->name;
                            $students[$student_name->register_no]['details'][1] = $student_name->register_no;
                            $students[$student_name->register_no]['day'][$log->actual_date . '|' . $getperiod->period] = $getperiod->period;
                            $students[$student_name->register_no]['present_details'][$log->actual_date . '|' . $getperiod->period] = $getPeriodsAttendance->attendance;
                            $days['day'][$log->actual_date . '|' . $getperiod->period] = $log->actual_date . '|' . $getperiod->period;
                        }
                    }
                }
            }

            $period_attendance = $students;

            $data2 = [
                'class_shor_form' => $class_shor_form,
                'theSubject' => strtoupper($theSubject),
                'course_name' => 'DEPARTMENT OF ' . $endpart_Course_name,
                'report' => strtoupper('Attendance Report'),
            ];
        } else {
            $period_attendance = [];
            $data2 = [];
            $days = [];
        }
        if (isset($excel) && $excel == 'excel') {
            $data = $period_attendance;
            $data2 = $data2;
            $days = $days;
            return view('admin.studentPeriodAttandance.Student_period_Attendance_report_Excel', compact('data', 'data2', 'days'));
        } else {
            $pdf = PDF::loadView('admin.studentPeriodAttandance.Student_period_Attendance_report_PDF', ['data' => $period_attendance, 'data2' => $data2, 'days' => $days]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('Student_period_Attendance_report_PDF.pdf');
        }
    }

    public function staffattendanceSummary(Request $request)
    {

        $user_name_id = auth()->user()->id;

        $subjects = [];

        $currentClasses = Session::get('currentClasses');

        $getAys = AcademicYear::pluck('name', 'id');

        $timetable = ClassTimeTableTwo::whereIn('class_name', $currentClasses)->where(['status' => 1, 'staff' => $user_name_id])->get();

        if (count($timetable) > 0) {
            foreach ($timetable as $data) {
                if (!in_array($data->class_name, $subjects)) {
                    // array_push($subjects, $data->class_name);
                    $subjects[$data->class_name] = $data->class_name;
                }
            }
        }
        $got_subjects = [];
        foreach ($subjects as $sub) {

            $get_enroll = CourseEnrollMaster::where('id', $sub)->first();
            $get_course = explode('/', $get_enroll->enroll_master_number);
            $get_short_form = ToolsCourse::where('name', $get_course[1])->select('short_form')->first();

            if ($get_short_form) {
                $get_course[1] = $get_short_form->short_form;
                $subjects[$sub] = $get_course[1] . ' / ' . $get_course[3] . ' / ' . $get_course[4];
            }
        }

        // dd($subjects);

        return view('admin.studentPeriodAttandance.staff_attendance_summary', compact('subjects'));
    }

    public function staff_attendance(Request $request)
    {
        $user_name_id = auth()->user()->id;
        $subjects = [];
        $timetable = ClassTimeTableTwo::where(['class_name' => $request->class, 'status' => 1])->groupby('class_name', 'subject')->select('class_name', 'subject')->get();
        // dd($timetable);
        if (count($timetable) > 0) {
            foreach ($timetable as $data) {
                if (!in_array($data->subject, $subjects)) {
                    $subjects[] = $data->subject;
                }
            }
        }
        // dd($subjects);

        $got_subjects = [];

        foreach ($subjects as $sub) {
            $get_enroll = CourseEnrollMaster::where('id', $request->class)->first();
            $get_subjects = Subject::where('id', $sub)->first();

            if ($subjects != 'Library') {
                if ($get_subjects) {
                    $data = $get_enroll->id;

                    if (!in_array($data, $got_subjects)) {
                        $got_subjects[] = $data;
                    }
                }
            } else {
                $data = $get_enroll->id;

                if (!in_array($data, $got_subjects)) {
                    $got_subjects[] = $data;
                }
            }
        }
        // dd($got_subjects);
        $items = [];
        foreach ($got_subjects as $enrollmentId) {

            $enroll_id = $enrollmentId;
            $user_name_id = $user_name_id;
            $date = $request->date;
            $get_enroll = CourseEnrollMaster::where('id', $enroll_id)->first();
            $enroll_array_part = explode('/', $get_enroll->enroll_master_number);
            $semester = $enroll_array_part[3];
            $course = $enroll_array_part[1];
            $ay = $enroll_array_part[2];
            $section = $enroll_array_part[4];
            $list = [];

            if ($semester == '1' || $semester == '2') {
                $batch = '01';
            } else if ($semester == '3' || $semester == '4') {
                $batch = '02';
            } else if ($semester == '5' || $semester == '6') {
                $batch = '03';
            } else if ($semester == '7' || $semester == '8') {
                $batch = '04';
            } else {
                $batch = '';
            }
            $get_day = Carbon::createFromFormat('Y-m-d', $date);
            $ayc_day = $date . ' 00:00:00';
            $day = strtoupper($get_day->format('l'));
            // dd($day);
            $get_ay = AcademicYear::where(['name' => $ay])->first();
            $get_course = ToolsCourse::where(['name' => $course])->first();
            $get_section = Section::where(['section' => $section, 'course_id' => $get_course->id])->first();
            $order_of_day = $day;
            // dd($ay, $course, $section, $day,  $ayc_day);

            if ($batch != '') {
                $get_calendar = AyCalendar::where(['batch' => $batch, 'academic_year' => $get_ay->name, 'date' => $ayc_day])->first();
                if ($get_calendar != '') {
                    $dayorder = $get_calendar->dayorder;
                    // dd($get_calendar);
                    if ($dayorder == 20) {
                        $order = 'MONDAY';
                    } else if ($dayorder == 7) {
                        $order = 'TUESDAY';
                    } else if ($dayorder == 8) {
                        $order = 'WEDNESDAY';
                    } else if ($dayorder == 9) {
                        $order = 'THURSDAY';
                    } else if ($dayorder == 10) {
                        $order = 'FRIDAY';
                    } else if ($dayorder == 11) {
                        $order = 'SATURDAY';
                    } else {
                        $order = '';
                    }
                    if ($order != '') {
                        $order_of_day = $order;
                    } else {

                        if ($dayorder == 4 || $dayorder == 1 || $dayorder == 2 || $dayorder == 3) {
                            $order = 'Holi Day';
                        } else if ($dayorder == 5) {
                            $order = 'No Order Day';
                        } else if ($dayorder == 14) {
                            $order = 'Modal Exam';
                        } else if ($dayorder == 15) {
                            $order = 'IAT';
                        } else if ($dayorder == 19) {
                            $order = 'College Day';
                        } else if ($dayorder == 6) {
                            $order = 'Unit Test';
                        }
                        // dd($order);
                        return response()->json(['status' => $order]);
                    }
                } else {
                    return response()->json(['status' => "Academic Calendar Not Found"]);
                }
            } else {
                return response()->json(['status' => "Couldn't Get The Mandatory Details"]);
            }

            if ($get_ay == '') {
                return response()->json(['status' => 'Academic Year Not Found']);
            } elseif ($get_course == '') {
                return response()->json(['status' => 'Course Not Found']);
            } elseif ($get_section == '') {
                return response()->json(['status' => 'Section Not Found']);
            } else {
                // dd($get_ay->name);
                $make_enroll = $get_course->name . '/' . $get_ay->name . '/' . $semester . '/' . $get_section->section;
                $get_enroll = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%{$make_enroll}")->first();

                if ($get_enroll != '') {
                    $enroll = $get_enroll->id;

                    $get_time_table = ClassTimeTableTwo::where(['class_name' => $enroll, 'day' => $order_of_day, 'status' => '1'])->select('subject', 'staff')->groupby('subject', 'staff')->get();

                    if (count($get_time_table) > 0) {

                        foreach ($get_time_table as $timetable) {

                            $get_period = ClassTimeTableTwo::where(['class_name' => $enroll, 'day' => $order_of_day, 'status' => '1', 'staff' => $timetable->staff, 'subject' => $timetable->subject])->select('period')->groupBy('period')->orderBy('period')->get();
                            $tempPeriod = '';
                            if (count($get_period) > 0) {
                                foreach ($get_period as $period) {
                                    $tempPeriod .= $period->period . ',';
                                }
                                $thePeriods = rtrim($tempPeriod, ',');
                            }

                            $check_sub = is_numeric($timetable->subject);
                            if ($check_sub == true) {
                                $get_sub = Subject::where(['id' => $timetable->subject])->first();
                                if ($get_sub != '') {
                                    $subject_name = $get_sub->name;
                                    $subject_code = $get_sub->subject_code;
                                }
                            } else {
                                $subject_name = $timetable->subject;
                            }
                            $get_staff = TeachingStaff::where(['user_name_id' => $timetable->staff])->select('name', 'StaffCode')->first();
                            if ($get_staff != '') {
                                $staff_name = $get_staff->name;
                                $staff_code = $get_staff->StaffCode;
                            }

                            $attendance_check = AttendanceRecord::where(['actual_date' => $date, 'enroll_master' => $enroll, 'subject' => $timetable->subject])->whereIn('status', [1, 100])->get();
                            if (count($attendance_check) > 0) {

                                foreach ($attendance_check as $data) {

                                    if ($data->lab_batch != null) {
                                        $allocateDay = $data->lab_batch;

                                        $allocated_students = StudentPeriodAllocate::where(['class' => $enroll, 'batch' => $allocateDay])->count();
                                        if ($allocated_students > 0) {
                                            $got_students = $allocated_students;
                                        } else {
                                            $got_students = 0;
                                        }
                                    } else {
                                        $get_students = Student::where('enroll_master_id', $enroll)->count();
                                        if ($get_students > 0) {
                                            $got_students = $get_students;
                                        } else {
                                            $get_students = StudentPromotionHistory::where('enroll_master_id', $enroll)->count();
                                            if ($get_students > 0) {
                                                $got_students = $get_students;
                                            } else {
                                                $got_students = 0;
                                            }
                                        }
                                    }

                                    $get_attendance = AttendenceTable::where(['enroll_master' => $enroll, 'day' => $day, 'period' => $data->period, 'subject' => $timetable->subject, 'date' => $date])->where('attendance', '!=', 'Absent')->get();
                                    $attend_students = count($get_attendance);
                                    $attendance_status = true;

                                    $list_data = ['subject_code' => $subject_code, 'staff_name' => $staff_name, 'staff_code' => $staff_code, 'subject_name' => $subject_name, 'alloted_periods' => $thePeriods, 'period' => $data->period, 'total_students' => $got_students, 'status' => $attendance_status, 'attend_students' => $attend_students];
                                    array_push($list, $list_data);
                                }
                            } else {
                                $attendance_check_2 = AttendanceRecord::where(['status' => 0, 'actual_date' => $date, 'enroll_master' => $enroll, 'subject' => $timetable->subject])->get();
                                if (count($attendance_check_2) > 0) {
                                    foreach ($attendance_check_2 as $data) {
                                        $attendance_status = 'Requested For Edit';
                                        $attend_students = 0;

                                        $list_data = ['subject_code' => $subject_code, 'staff_name' => $staff_name, 'staff_code' => $staff_code, 'subject_name' => $subject_name, 'alloted_periods' => $thePeriods, 'period' => $data->period, 'total_students' => 0, 'status' => $attendance_status, 'attend_students' => $attend_students];
                                        array_push($list, $list_data);
                                    }
                                } else {

                                    $attendance_check_3 = AttendanceRecord::where(['status' => 55, 'actual_date' => $date, 'enroll_master' => $enroll, 'subject' => $timetable->subject])->get();
                                    // dd($attendance_check_3);
                                    if (count($attendance_check_3) > 0) {
                                        foreach ($attendance_check_3 as $data) {
                                            $attendance_status = 'Requested For Delete';
                                            $attend_students = 0;

                                            $list_data = ['subject_code' => $subject_code, 'staff_name' => $staff_name, 'staff_code' => $staff_code, 'subject_name' => $subject_name, 'alloted_periods' => $thePeriods, 'period' => $data->period, 'total_students' => 0, 'status' => $attendance_status, 'attend_students' => $attend_students];
                                            array_push($list, $list_data);
                                        }
                                    } else {
                                        $get_students = Student::where('enroll_master_id', $enroll)->count();
                                        if ($get_students > 0) {
                                            $got_students = $get_students;
                                        } else {
                                            $get_students = StudentPromotionHistory::where('enroll_master_id', $enroll)->count();
                                            if ($get_students > 0) {
                                                $got_students = $get_students;
                                            } else {
                                                $got_students = [];
                                            }
                                        }
                                        $list_data = ['subject_code' => $subject_code, 'staff_name' => $staff_name, 'staff_code' => $staff_code, 'subject_name' => $subject_name, 'alloted_periods' => $thePeriods, 'period' => '', 'total_students' => $got_students, 'status' => false, 'attend_students' => 0];
                                        array_push($list, $list_data);
                                    }
                                }
                            }

                            $periods = array_column($list, 'period');

                            array_multisort($periods, SORT_ASC, $list);
                        }
                        return response()->json(['status' => $list]);
                    } else {
                        return response()->json(['status' => 'Class Time Table Not Found']);
                    }
                } else {
                    return response()->json(['status' => 'Class Not Found']);
                }
            }
        }

        if (count($items) > 0) {
            return response()->json(['status' => $items]);
        } else {
            return response()->json(['status' => 'Class Not Found Or Class Time Table Not Found']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getPastRecords(Request $request)
    {
        if (isset($request->past_ay) && isset($request->past_semester)) {

            $user_name_id = auth()->user()->id;

            $enroll = '%/%/' . $request->past_ay . '/' . $request->past_semester . '/%';

            $getClass = CourseEnrollMaster::where('enroll_master_number', "LIKE", $enroll)->select('id', 'enroll_master_number')->get();

            $theClass = [];

            $subjects = [];

            if (count($getClass) > 0) {
                foreach ($getClass as $enrolledClass) {
                    array_push($theClass, $enrolledClass->id);
                }
            }

            $timetable = ClassTimeTableTwo::whereIn('class_name', $theClass)->where(['status' => 1, 'staff' => $user_name_id])->get();

            if (count($timetable) > 0) {
                foreach ($timetable as $data) {
                    if (!in_array([$data->subject, $data->class_name], $subjects)) {
                        array_push($subjects, [$data->subject, $data->class_name]);
                    }
                }
            }

            $got_subjects = [];

            for ($i = 0; $i < count($subjects); $i++) {
                $get_enroll = CourseEnrollMaster::where(['id' => $subjects[$i][1]])->first();
                $get_subjects = Subject::where(['id' => $subjects[$i][0]])->first();
                $get_course = explode('/', $get_enroll->enroll_master_number);
                $get_short_form = ToolsCourse::where('name', $get_course[1])->select('short_form')->first();

                if ($get_short_form) {
                    $get_course[1] = $get_short_form->short_form;
                    $subjects[$i][1] = $get_course[1] . ' / ' . $get_course[3] . ' / ' . $get_course[4];
                }
                if ($get_subjects) {
                    array_push($got_subjects, [$get_subjects->id, $subjects[$i][1], $get_enroll->id, $user_name_id, $get_subjects->name, $get_subjects->subject_code]);
                }
            }
            return response()->json(['status' => true, 'data' => $got_subjects]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }
}
