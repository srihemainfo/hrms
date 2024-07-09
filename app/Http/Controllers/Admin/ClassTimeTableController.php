<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ClassRoom;
use App\Models\ClassTimeTableOne;
use App\Models\ClassTimeTableTwo;
use App\Models\CollegeCalender;
use App\Models\CourseEnrollMaster;
use App\Models\NonTeachingStaff;
use App\Models\Section;
use App\Models\Semester;
use App\Models\ShiftModel;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectAllotment;
use App\Models\TeachingStaff;
use App\Models\TimeTableVersion;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ClassTimeTableController extends Controller
{

    public function index(Request $request)
    {
        $user_id = auth()->user()->id;

        $get_staff = DB::table('role_user')->where(['user_id' => $user_id])->first();

        $user = isset($get_staff->user_id) ? $get_staff->user_id : '';
        $role = isset($get_staff->role_id) ? $get_staff->role_id : '';

        $get_dept = TeachingStaff::where(['user_name_id' => $user_id])->first();
        if ($get_dept == '') {
            $get_dept = User::where(['id' => $user_id])->first();

            if (!empty($get_dept)) {
                $dept = $get_dept->dept;
            } else {
                $dept = null;
            }
        } else {
            if (!empty($get_dept)) {
                $dept = $get_dept->Dept;
            } else {
                $dept = null;
            }
        }
        $getAys = AcademicYear::where(['status' => 0])->pluck('name', 'id');
        $currentClasses = Session::get('currentClasses');
        $time_tables = ClassTimeTableTwo::whereIn('class_name', $currentClasses)->get()->groupBy('class_name');
        $time_tables_hod = ClassTimeTableOne::whereIn('class_name', $currentClasses)->get()->groupBy('class_name');
        $tables = [];
        $tables_hod = [];
        if ($dept != null) {
            $toolsDept = ToolsDepartment::where(['name' => $dept])->first();
        } else {
            $toolsDept = '';
        }

        if (!empty($toolsDept)) {
            if ($toolsDept->id != 5) {
                $get_course = ToolsCourse::where(['department_id' => $toolsDept->id])->pluck('short_form', 'name');
                $semester = Semester::skip(2)->pluck('id', 'id')->toArray();
                $who = 'HOD';

            } else {
                $get_course = ToolsCourse::pluck('short_form', 'name');
                $who = 'SHOD';
                $semester = Semester::take(2)->pluck('id', 'id')->toArray();
            }
        } else {
            $get_course = ToolsCourse::pluck('short_form', 'name');
            $who = 'ADMIN';
            $semester = Semester::pluck('id', 'id')->toArray();
        }
        $theEnrolls = [];
        if (count($get_course) > 0) {
            foreach ($get_course as $id => $data) {
                if ($who == 'HOD') {
                    $make_enroll_3 = '%/' . $data . '/%/3/%';
                    $make_enroll_4 = '%/' . $data . '/%/4/%';
                    $make_enroll_5 = '%/' . $data . '/%/5/%';
                    $make_enroll_6 = '%/' . $data . '/%/6/%';
                    $make_enroll_7 = '%/' . $data . '/%/7/%';
                    $make_enroll_8 = '%/' . $data . '/%/8/%';

                    $variablesToCheck = [$make_enroll_3, $make_enroll_4, $make_enroll_5, $make_enroll_6, $make_enroll_7, $make_enroll_8];

                } elseif ($who == 'SHOD') {
                    $make_enroll_1 = '%/' . $data . '/%/1/%';
                    $make_enroll_2 = '%/' . $data . '/%/2/%';

                    $variablesToCheck = [$make_enroll_1, $make_enroll_2];

                } else {
                    $make_enroll_1 = '%/' . $data . '/%/1/%';
                    $make_enroll_2 = '%/' . $data . '/%/2/%';
                    $make_enroll_3 = '%/' . $data . '/%/3/%';
                    $make_enroll_4 = '%/' . $data . '/%/4/%';
                    $make_enroll_5 = '%/' . $data . '/%/5/%';
                    $make_enroll_6 = '%/' . $data . '/%/6/%';
                    $make_enroll_7 = '%/' . $data . '/%/7/%';
                    $make_enroll_8 = '%/' . $data . '/%/8/%';

                    $variablesToCheck = [$make_enroll_1, $make_enroll_2, $make_enroll_3, $make_enroll_4, $make_enroll_5, $make_enroll_6, $make_enroll_7, $make_enroll_8];
                }
                $getEnroll = CourseEnrollMaster::where(function ($query) use ($variablesToCheck) {
                    foreach ($variablesToCheck as $variable) {
                        $query->orWhere('enroll_master_number', 'LIKE', "%{$variable}");
                    }
                })->select('id', 'enroll_master_number')->get();

                if (count($getEnroll) > 0) {
                    foreach ($time_tables as $time_table) {
                        foreach ($getEnroll as $enroll_master) {
                            if ($time_table[0]['class_name'] == $enroll_master->id) {
                                $tables[$enroll_master->id] = $time_table;
                            }
                        }
                    }
                    foreach ($time_tables_hod as $time_table) {
                        foreach ($getEnroll as $enroll_master) {
                            if ($time_table[0]['class_name'] == $enroll_master->id) {
                                $tables_hod[$enroll_master->id] = $time_table;
                            }
                        }
                    }
                }
            }
        }

        $check_tables = ClasstimetableOne::with('shift')->whereIn('class_name', $currentClasses)->orderBy('updated_at', 'desc')->get()->groupBy('class_name')->sortByDesc('updated_at');
        // dd($check_tables[1052]);
        if ($role == 15 || $role == 1 || $role == 43) {

            if (count($check_tables) > 0) {

                $created_time_tables = $check_tables;
            } else {
                $created_time_tables = [];
            }
        } else if ($role == 14) {
            if (count($time_tables_hod) > 0) {
                $created_time_tables = $tables_hod;
            } else {
                $created_time_tables = [];
            }
        } else {
            if (count($time_tables) > 0) {

                $created_time_tables = $tables;
            } else {
                $created_time_tables = [];
            }
        }

        $class_name = CourseEnrollMaster::pluck('enroll_master_number', 'id')->prepend('-', '');
        $courses = $get_course;
        $section = Section::pluck('section', 'id')->unique();
        $AcademicYear = AcademicYear::pluck('name', 'id');

        return view('admin.classTimeTable.index', compact('created_time_tables', 'class_name', 'semester', 'courses', 'AcademicYear', 'section', 'getAys'));
    }
    public function search(Request $request)
    {
        $course = $request->course;
        $semester = $request->semester;
        $section = $request->section;
        $accademicyear = $request->accademicyear;
        $enrollMaster = '/' . $course . '/' . $accademicyear . '/' . $semester . '/' . $section;
        $enroll = CourseEnrollMaster::where('enroll_master_number', 'like', '%' . $enrollMaster . '%')->get();

        $checkTables = ClassTimeTableOne::whereIn('class_name', $enroll->pluck('id'))
            ->orderBy('updated_at', 'desc')
            ->get()
            ->groupBy('class_name')
            ->sortByDesc('updated_at');

        $result = [];
        foreach ($checkTables as $class_name => $data) {
            $user = User::find($data[0]->created_by);
            $version = DB::table('timetable_versions')
                ->where('class_id', $data[0]->class_name)
                ->latest('updated_at')
                ->first();
            $class_name = CourseEnrollMaster::find($class_name);
            $result[] = [
                'class_name' => $class_name->enroll_master_number ?? '',
                'user' => $user,
                'version' => $version,
                'status' => $data[0]->status,
                'id' => $data[0]->id,
                'classId' => $class_name->id ?? '',
            ];

        }

        return response()->json(['check_tables' => $result]);
    }

    public function versionShow(Request $request)
    {
        $versions = DB::table('timetable_versions')
            ->where('class_id', $request->classId)
            ->where('version', '<=', $request->versionNumber)
            ->get();
        $result = [];
        if ($versions) {
            foreach ($versions as $version => $data) {
                $class_name = CourseEnrollMaster::find($data->class_id);

                $result[] = [
                    'class_name' => $class_name->enroll_master_number ?? '',
                    'all' => $data,
                ];
            }
        }

        return response()->json(['versionShowing' => $result]);
    }

    public function version($id)
    {
        $version = TimeTableVersion::with(['enroll_master'])->find($id);
        $class_id = '';
        $got_class = $version ? json_decode($version->data, true) : [];
        $class = [];
        foreach ($got_class as $data) {

            $staff = TeachingStaff::where(['user_name_id' => $data['staff']])->first();
            if ($staff == '') {
                $staff = NonTeachingStaff::where(['user_name_id' => $data['staff']])->first();
            }
            $subject = Subject::where(['id' => $data['subject']])->first();

            $data['staff_name'] = $staff->name;
            $data['staff_code'] = $staff->StaffCode;
            $data['subject_name'] = $subject->name ?? $data['subject'];
            $data['subject_code'] = $subject->subject_code ?? null;

            if ($subject == '') {
                $data['status'] = true;
            } else {
                $data['status'] = false;
            }

            array_push($class, $data);
        }
        $class_name = $version->enroll_master->enroll_master_number;
        return view('admin.classTimeTable.versionShow', compact('class', 'class_name', 'class_id'));
    }

    public function create(Request $request)
    {
        $user_name_id = auth()->user()->id;
        $get_dept = TeachingStaff::where(['user_name_id' => $user_name_id])->first();
        if ($get_dept == '') {
            $get_dept = User::where(['id' => $user_name_id])->first();

            if (!empty($get_dept)) {
                $dept = $get_dept->dept;
            } else {
                $dept = null;
            }
        } else {
            if (!empty($get_dept)) {
                $dept = $get_dept->Dept;
            } else {
                $dept = null;
            }
        }
        if ($dept != null) {
            $toolsDept = ToolsDepartment::where(['name' => $dept])->first();
        } else {
            $toolsDept = '';
        }

        $classes = ClassRoom::with('enroll_master')->get();
        $semester = [3, 4, 5, 6, 7, 8];
        if ($toolsDept != '') {
            if ($toolsDept->id == 5) {
                $semester = [1, 2];
                $course = ToolsCourse::pluck('short_form', 'id')->prepend('Select Course', '');
            } else {
                $course = ToolsCourse::where(['department_id' => $toolsDept->id])->pluck('short_form', 'id')->prepend('Select Course', '');
            }
        } else {
            $course = ToolsCourse::pluck('short_form', 'id')->prepend('Select Course', '');
            $semester = [1, 2, 3, 4, 5, 6, 7, 8];
        }

        $academic_years = AcademicYear::pluck('name', 'id')->prepend('Select Academic Year', '');

        $getStaff = TeachingStaff::with('personal_details:user_name_id,employment_status')->select('user_name_id', 'name', 'StaffCode')->get();
        $teaching_staffs = [];
        if (count($getStaff) > 0) {
            foreach ($getStaff as $staff) {
                if ($staff->personal_details->employment_status == 'Active' || $staff->personal_details->employment_status == '') {
                    array_push($teaching_staffs, $staff);
                }
            }
        }

        $shift = ShiftModel::pluck('Name', 'id');

        return view('admin.classTimeTable.create', compact('course', 'academic_years', 'semester', 'teaching_staffs', 'shift'));
    }

    public function getStaffAndSubject(Request $request)
    {
        if ($request) {
            $got_staff = $got_subject = '';

            $check_subject = is_numeric($request['subject']);

            if ($check_subject) {
                $subject = SubjectAllotment::with(['subjects'])->where(['subject_id' => $request['subject']])->select('subject_id', 'category')->first();
            } else {
                $subject = $request['subject'];
            }
            $staff = TeachingStaff::where(['user_name_id' => $request['staff']])->select('user_name_id', 'StaffCode', 'name')->first();
            if ($staff != '') {
                $got_staff = $staff;
            }

            if ($subject != '') {
                $got_subject = $subject;
            }
            return response()->json(['staff' => $got_staff, 'subject' => $got_subject]);
        }
    }

    public function store(Request $request)
    {
        // dd($request);
        if (isset($request->data) && isset($request->class) && $request->class != '') {

            $check = ClassTimeTableOne::where(['class_name' => $request->class])->get();

            $class = $request->class;
            $shift = $request->shift ?? null;
            $datas = $request->data;

            if (!$check->count() > 0) {

                foreach ($datas as $data) {
                    $insert = ClassTimeTableOne::create([
                        'class_name' => $class,
                        'shift_id' => $shift,
                        'day' => $data[0]['value'],
                        'period' => $data[2]['value'],
                        'subject' => $data[3]['value'],
                        'staff' => $data[4]['value'],
                        'status' => 0,
                    ]);
                }

                return response()->json(['status' => true]);
            } else {
                return response()->json(['status' => false]);
            }
        } else {
            return response()->json(['status' => false]);
        }
    }

    public function show($id)
    {
        $user_id = auth()->user()->id;

        $get_staff = DB::table('role_user')->where(['user_id' => $user_id])->first();
        $get_subjects = [];
        $class_name = null;
        $class_id = null;
        $DayTimetable = null;

        $user = isset($get_staff->user_id) ? $get_staff->user_id : '';
        $role = isset($get_staff->role_id) ? $get_staff->role_id : '';
        $get = ClasstimetableOne::where(['id' => $id])->first();
        if ($get) {
            $class_id = $get->class_name;
            $get_class = ClasstimetableOne::with(['staffs', 'subjects'])->where(['class_name' => $get->class_name])->get();
        } else {
            $get_class = [];
        }
        if ($get_class->count() > 0) {
            $class = $get_class;

        } else {
            $class = [];
        }
        if ($get) {
            $get_enroll = CourseEnrollMaster::where(['id' => $get->class_name])->first();
            if ($get_enroll) {

                $get_class_name = explode('/', $get_enroll->enroll_master_number);
                $get_course = $get_class_name[1];
                $semester = $get_class_name[3];

                $course = ToolsCourse::where('name', 'LIKE', $get_course)->first();
                $class_name = $course->short_form . ' / ' . $get_class_name[3] . ' / ' . $get_class_name[4];
                $AcademicYear = AcademicYear::where('name', $get_class_name[2])->first();
                if ($AcademicYear) {
                    $get_subjects = SubjectAllotment::with(['subjects'])->where(['academic_year' => $AcademicYear->id, 'course' => $course->id, 'semester' => $semester])->get();
                }

                $date = date("Y-m-d");
                $formattedDate = Carbon::parse($date)->format('Y-m-d H:i:s');
                $today = Carbon::today();
                $isSaturday = $today->isSaturday();
                if ($isSaturday) {
                    $calenderDate = DB::table('college_calenders_preview')
                        ->select(['date', 'dayorder'])
                        ->where('date', $formattedDate)
                        ->first();

                    if ($calenderDate) {

                        if ($calenderDate->dayorder != 0) {

                            $dayorder = $calenderDate->dayorder;
                            if ($dayorder == 20) {
                                $dayOrder = 'MONDAY';
                            } elseif ($dayorder == 7) {
                                $dayOrder = 'TUESDAY';
                            } elseif ($dayorder == 8) {
                                $dayOrder = 'WEDNESDAY';
                            } elseif ($dayorder == 9) {
                                $dayOrder = 'THURSDAY';
                            } elseif ($dayorder == 10) {
                                $dayOrder = 'FRIDAY';
                            } elseif ($dayorder == 11) {
                                $dayOrder = 'SATURDAY';
                            } else {
                                $dayOrder = '';
                            }

                            if ($dayOrder != '') {
                                $DayTimetable = $dayOrder;
                            }
                        }

                    }
                }
            }
        }

        $teaching_staffs = TeachingStaff::select('user_name_id', 'name', 'StaffCode')->get();

        $show = true;

        return view('admin.classTimeTable.show', compact('show', 'class_id', 'class', 'class_name', 'teaching_staffs', 'get_subjects', 'DayTimetable'));
    }

    public function live_show($id)
    {
        $user_id = auth()->user()->id;

        $get_staff = DB::table('role_user')->where(['user_id' => $user_id])->first();
        $get_subjects = [];
        $class_id = null;
        $class_name = null;
        $DayTimetable = null;

        $user = isset($get_staff->user_id) ? $get_staff->user_id : '';
        $role = isset($get_staff->role_id) ? $get_staff->role_id : '';

        $get = ClasstimetableOne::where(['id' => $id])->first();
        $get_class = [];
        if ($get) {
            $class_id = $get->class_name;
            $get_class = ClasstimetableTwo::with(['staffs', 'subjects'])->where(['class_name' => $get->class_name, 'status' => 1])->get();
        }

        if ($get_class->count() > 0) {

            $class = $get_class;
        } else {
            $class = [];
        }
        if ($get) {
            $get_enroll = CourseEnrollMaster::where(['id' => $get->class_name])->first();
            if ($get_enroll) {

                $get_class_name = explode('/', $get_enroll->enroll_master_number);
                $get_course = $get_class_name[1];
                $semester = $get_class_name[3];

                $course = ToolsCourse::where('name', 'LIKE', $get_course)->first();
                $class_name = $course->short_form . ' / ' . $get_class_name[3] . ' / ' . $get_class_name[4];
                $AcademicYear = AcademicYear::where('name', $get_class_name[2])->first();
                if ($AcademicYear) {
                    $get_subjects = SubjectAllotment::with(['subjects'])->where(['academic_year' => $AcademicYear->id, 'course' => $course->id, 'semester' => $semester])->get();
                }

                $date = date("Y-m-d");
                $formattedDate = Carbon::parse($date)->format('Y-m-d H:i:s');
                $today = Carbon::today();
                $isSaturday = $today->isSaturday();
                if ($isSaturday) {
                    $calenderDate = DB::table('college_calenders_preview')
                        ->select(['date', 'dayorder'])
                        ->where('date', $formattedDate)
                        ->first();

                    if ($calenderDate) {

                        if ($calenderDate->dayorder != 0) {

                            $dayorder = $calenderDate->dayorder;
                            if ($dayorder == 20) {
                                $dayOrder = 'MONDAY';
                            } elseif ($dayorder == 7) {
                                $dayOrder = 'TUESDAY';
                            } elseif ($dayorder == 8) {
                                $dayOrder = 'WEDNESDAY';
                            } elseif ($dayorder == 9) {
                                $dayOrder = 'THURSDAY';
                            } elseif ($dayorder == 10) {
                                $dayOrder = 'FRIDAY';
                            } elseif ($dayorder == 11) {
                                $dayOrder = 'SATURDAY';
                            } else {
                                $dayOrder = '';
                            }

                            if ($dayOrder != '') {
                                $DayTimetable = $dayOrder;
                            }
                        }

                    }
                }
            }
        }

        $teaching_staffs = TeachingStaff::select('user_name_id', 'name', 'StaffCode')->get();

        $show = true;

        return view('admin.classTimeTable.show', compact('show', 'class_id', 'class', 'class_name', 'teaching_staffs', 'get_subjects', 'DayTimetable'));
    }

    public function edit($id)
    {

        $user_id = auth()->user()->id;

        $get_staff = DB::table('role_user')->where(['user_id' => $user_id])->first();

        $user = isset($get_staff->user_id) ? $get_staff->user_id : '';
        $role = isset($get_staff->role_id) ? $get_staff->role_id : '';
        $get_subjects = [];
        $class_name = null;
        $get = ClasstimetableOne::where(['id' => $id])->first();
        if ($get) {
            $get_class = ClasstimetableOne::with(['staffs', 'subjects'])->where(['class_name' => $get->class_name])->get();
        }
        if ($get_class->count() > 0) {
            $class = $get_class;
        } else {
            $class = [];
        }

        if ($get) {
            $get_enroll = CourseEnrollMaster::where(['id' => $get->class_name])->first();
            if ($get_enroll) {

                $get_class_name = explode('/', $get_enroll->enroll_master_number);
                $get_course = $get_class_name[1];
                $semester = $get_class_name[3];

                $course = ToolsCourse::where('name', 'LIKE', $get_course)->first();
                $class_name = $course->short_form . ' / ' . $get_class_name[3] . ' / ' . $get_class_name[4];
                $AcademicYear = AcademicYear::where('name', $get_class_name[2])->first();
                if ($AcademicYear && $course->department_id != null) {
                    if ($semester == 1 || $semester == 2) {
                        $course->department_id = 5; // S & H Department
                    }
                    $SubjectAllotment = SubjectAllotment::where(['department' => $course->department_id, 'academic_year' => $AcademicYear->id, 'course' => $course->id, 'semester' => $semester])->get();
                    if ($SubjectAllotment->count() > 0) {
                        foreach ($SubjectAllotment as $SubjectAllotments) {
                            $getting_subject = Subject::where(['id' => $SubjectAllotments->subject_id])->first();
                            if ($getting_subject != '') {
                                array_push($get_subjects, $getting_subject);
                            }
                        }
                    }
                }

            }
        }

        $getStaff = TeachingStaff::with('personal_details:user_name_id,employment_status')->select('user_name_id', 'name', 'StaffCode')->get();
        $teaching_staffs = [];
        if (count($getStaff) > 0) {
            foreach ($getStaff as $staff) {
                if ($staff->personal_details->employment_status == 'Active' || $staff->personal_details->employment_status == '') {
                    array_push($teaching_staffs, $staff);
                }
            }
        }

        return view('admin.classTimeTable.edit', compact('class', 'class_name', 'teaching_staffs', 'get_subjects'));
    }

    public function updater(Request $request)
    {
        if ($request != '') {
            $class = $request->class;
            foreach ($request->data as $value) {
                $day = strtoupper($value[0]['value']);
                $id = $value[1]['value'];
                $period = $value[2]['value'];
                $subject = $value[3]['value'];
                $staff = $value[4]['value'];
                if ($id != '' && $id != null) {
                    $update = ClassTimeTableOne::where(['id' => $id])->update([
                        'day' => $day,
                        'period' => $period,
                        'subject' => $subject,
                        'staff' => $staff,
                        'status' => 0,
                    ]);
                } else {
                    $insert = ClassTimeTableOne::create([
                        'class_name' => $class,
                        'day' => $day,
                        'period' => $period,
                        'subject' => $subject,
                        'staff' => $staff,
                        'status' => 0,
                    ]);
                }
            }

            if ($request->delete != null) {
                foreach ($request->delete as $id) {
                    $delete = ClassTimeTableOne::find($id);
                    $delete->delete();

                }
            }
            return response()->json(['status' => true]);
        }
    }

    public function destroy($id)
    {

        $classTimeTableOne = ClassTimeTableOne::find($id);

        if ($classTimeTableOne) {
            $classTimeTableOnes = ClassTimeTableOne::where('class_name', $classTimeTableOne->class_name)->get();
            foreach ($classTimeTableOnes as $timeTable) {
                $timeTable->delete();
            }

            $classTimeTableTwos = ClassTimeTableTwo::where('class_name', $classTimeTableOne->class_name)->get();
            foreach ($classTimeTableTwos as $timeTable) {
                $timeTable->delete();
            }
        }

        return redirect()->back();
    }

    public function check(Request $request)
    {
        // dd($request);
        if ($request->data) {
            $currentClasses = Session::get('currentClasses');
            $check_1 = ClassTimeTableTwo::whereIn('class_name', $currentClasses)->where(['period' => $request->data['column'], 'day' => $request->data['day'], 'staff' => $request->data['selected_staff']])->whereNotIn('subject', [$request->data['subject']])->where('status', '!=', 2)->get();
            $check_2 = ClassTimeTableOne::whereIn('class_name', $currentClasses)->where(['period' => $request->data['column'], 'day' => $request->data['day'], 'staff' => $request->data['selected_staff']])->whereNotIn('subject', [$request->data['subject']])->where('status', '!=', 2)->get();
            if ($check_1->count() > 0) {
                return response()->json(['status' => false]);
            } else {
                if ($check_2->count() > 0) {
                    return response()->json(['status' => false]);
                } else {
                    return response()->json(['status' => true]);
                }
            }
        }
    }

    public function subjects(Request $request)
    {
        // dd($request);
        if ($request->course != '' && $request->ay != '' && $request->semester != '' && $request->section != '') {
            $subjects = [];
            $get_course = ToolsCourse::where(['id' => $request->course, 'shift_id' => $request->shift])->orWhere(['id' => $request->course])->first();
            // $get_course = ToolsCourse::where(['id' => $request->course,'shift_id' => null])->first();
            $get_ay = AcademicYear::where(['id' => $request->ay])->first();
            // dd($request->shift);
            $sem_type = $batch = $got_department = null;

            if (intval($request->semester) % 2 == 0) {
                $sem_type = "EVEN";
            } else {
                $sem_type = "ODD";
            }

            // if ($request->semester == '1' || $request->semester == '3' || $request->semester == '5' || $request->semester == '7') {
            // } else {
            // }

            if ($request->semester == '1' || $request->semester == '2') {
                $batch = '01';
            } else if ($request->semester == '3' || $request->semester == '4') {
                $batch = '02';
            } else if ($request->semester == '5' || $request->semester == '6') {
                $batch = '03';
            } else if ($request->semester == '7' || $request->semester == '8') {
                $batch = '04';
            }
            // dd($get_course);
            if ($get_course != '') {
                $got_course = $get_course->name;
                $got_department = $get_course->department_id;
            }
            if ($get_ay != '') {
                $got_ay = $get_ay->name;
            }
            $check_calendar = CollegeCalender::where(['academic_year' => $got_ay, 'semester_type' => $sem_type, 'batch' => $batch])->get();
            // dd($check_calendar);
            if (!count($check_calendar) > 0) {
                return response()->json(['subjects' => 'Calendar Fail', 'class_name' => null]);
            }
            $class = $got_course . '/' . $got_ay . '/' . $request->semester . '/' . $request->section;

            $get_enroll = CourseEnrollMaster::where('enroll_master_number', 'like', "%{$class}")->first();
            // dd($class);
            $class_name = null;
            if (!empty($get_enroll)) {
                $check_class = ClassRoom::where(['name' => $get_enroll->id])->get();
                // dd($check_class);

                if (!count($check_class) > 0) {

                    return response()->json(['subjects' => 'Class Fail', 'class_name' => $class_name]);
                }

                $class_name = $get_enroll->id;
                $check_class_time_table = ClasstimetableOne::where(['class_name' => $get_enroll->id])->get();
                // dd($check_class_time_table);
                if (count($check_class_time_table) > 0) {
                    return response()->json(['subjects' => 'Fail', 'class_name' => $class_name]);
                } else {
                    if ($request->semester == '1' || $request->semester == '2') {
                        $got_department = 5; // S & H Department
                    }
                    $get_subjects = SubjectAllotment::where(['department' => $got_department, 'semester' => $request->semester, 'course' => $request->course, 'academic_year' => $request->ay])->get();
                    // dd($get_subjects);
                    if (count($get_subjects) > 0) {
                        foreach ($get_subjects as $subject) {
                            $got_subject = Subject::where(['id' => $subject->subject_id])->first();
                            if ($got_subject != '') {
                                array_push($subjects, $got_subject);
                            }
                        }
                    }
                }
            }

            return response()->json(['subjects' => $subjects, 'class_name' => $class_name]);

        }
    }

    public function status_update(Request $request)
    {
        $get = ClasstimetableOne::where(['id' => $request->data['id'], 'status' => 0])->first();
        if ($get) {
            $get_class = ClasstimetableOne::where(['class_name' => $get->class_name])->orderBy('updated_at', 'desc')->get();
        }
        if ($get_class->count() > 0) {
            if ($request->data['status'] == 'Approved') {

                $version_array = [];

                foreach ($get_class as $id => $data) {

                    $update_1 = ClassTimeTableOne::where(['id' => $data->id])->update([
                        'rejected_reason' => null,
                        'status' => 1,
                    ]);

                    if ($id == 0) {
                        $update_2 = ClassTimeTableTwo::where(['class_name' => $data->class_name])->get();
                        $for_versions = ClassTimeTableTwo::where(['class_name' => $data->class_name, 'status' => 1])->get();
                        array_push($version_array, $for_versions);
                        if (count($update_2) > 0) {
                            $update = ClassTimeTableTwo::where(['class_name' => $data->class_name])->update([
                                'status' => 0,
                            ]);
                        }
                    }

                    $check_table = ClassTimeTableTwo::where(['class_name' => $data->class_name, 'day' => $data->day, 'period' => $data->period, 'staff' => $data->staff, 'subject' => $data->subject])->get();

                    if (count($check_table) > 0) {
                        $update_3 = ClassTimeTableTwo::where(['class_name' => $data->class_name, 'day' => $data->day, 'period' => $data->period, 'staff' => $data->staff, 'subject' => $data->subject])->update([
                            'status' => 1,
                        ]);

                    } else {
                        $insert_1 = ClassTimeTableTwo::create([
                            'class_name' => $data->class_name,
                            'day' => $data->day,
                            'period' => $data->period,
                            'subject' => $data->subject,
                            'staff' => $data->staff,
                            'status' => 1,
                        ]);
                    }
                }
                foreach ($get_class as $id => $data) {
                    $delete_unwanted = ClassTimeTableTwo::where(['class_name' => $data->class_name, 'status' => 0])->update([
                        'deleted_at' => Carbon::now(),
                    ]);
                }
                $VersionArray = [];
                $class_name = $created_by = $created_at = null;

                if (count($version_array[0]) > 0) {

                    $class_name = $version_array[0][0]->class_name;
                    $created_by = $version_array[0][0]->created_by;
                    $created_at = $version_array[0][0]->created_at;

                    foreach ($version_array[0] as $version) {
                        $data = [
                            'day' => $version->day,
                            'period' => $version->period,
                            'subject' => $version->subject,
                            'staff' => $version->staff,
                        ];
                        array_push($VersionArray, $data);
                    }
                }
                if (count($VersionArray) > 0) {

                    $updateDataJson = json_encode($VersionArray);

                    $newInsertVersion = DB::table('timetable_versions')->insert([
                        'data' => $updateDataJson,
                        'class_id' => $class_name,
                        'created_by' => $created_by,
                        'approved_by' => auth()->user()->name,
                        'version' => DB::table('timetable_versions')->where('class_id', $class_name)->count() + 1,
                        'created_at' => $created_at,
                        'updated_at' => now(),
                    ]);
                }

            } elseif ($request->data['status'] == 'Rejected') {
                foreach ($get_class as $data) {
                    $reject = ClassTimeTableOne::where(['id' => $data->id])->update([
                        'status' => 2,
                        'rejected_reason' => $request->data['rejected_reason'],
                    ]);

                }
            }
        }

        return response()->json(['status' => true]);
    }

    public function get_myTimeTable(Request $request)
    {
        $class_id = null;
        if (isset($request->user_name_id) && $request->user_name_id != '') {

            $get_enroll = Student::where(['user_name_id' => $request->user_name_id])->first();

            if ($get_enroll != '' && $get_enroll->enroll_master_id != null) {
                $get_class = ClassTimeTableTwo::where(['class_name' => $get_enroll->enroll_master_id, 'status' => 1])->get();

                if ($get_class) {
                    $class_id = $get_enroll->enroll_master_id;
                    $class = $get_class;
                    $get_enroll = CourseEnrollMaster::where(['id' => $get_enroll->enroll_master_id])->first();
                    if ($get_enroll) {

                        $get_class_name = explode('/', $get_enroll->enroll_master_number);
                        $get_course = $get_class_name[1];
                        $semester = $get_class_name[3];
                        $ay = $get_class_name[2];

                        $course = ToolsCourse::where('name', 'LIKE', $get_course)->first();
                        $academic_year = AcademicYear::where(['name' => $ay])->first();

                        $class_name = $course->short_form . ' / ' . $get_class_name[3] . ' / ' . $get_class_name[4];

                        if ($course != '' && $academic_year != '') {
                            $get_subjects = SubjectAllotment::with(['subjects'])->where(['semester' => $semester, 'course' => $course->id, 'academic_year' => $academic_year->id])->get();
                        } else {
                            $get_subjects = [];
                        }
                        if ($semester == 1 || $semester == 2) {
                            $batch = '01';
                        } else if ($semester == 3 || $semester == 4) {
                            $batch = '02';
                        } else if ($semester == 5 || $semester == 6) {
                            $batch = '03';
                        } else if ($semester == 7 || $semester == 8) {
                            $batch = '04';
                        }

                        $date = date("Y-m-d");
                        $formattedDate = Carbon::parse($date)->format('Y-m-d H:i:s');
                        $today = Carbon::today();
                        $isSaturday = $today->isSaturday();
                        if ($isSaturday) {
                            $calenderDate = DB::table('college_calenders_preview')
                                ->select('date', 'dayorder')
                                ->where(['date' => $formattedDate, 'academic_year' => $ay, 'batch' => $batch])
                                ->first();
                            $DayTimetable = null;
                            if ($calenderDate) {

                                if ($calenderDate->dayorder != 0) {

                                    $dayorder = $calenderDate->dayorder;
                                    if ($dayorder == 20) {
                                        $dayOrder = 'MONDAY';
                                    } elseif ($dayorder == 7) {
                                        $dayOrder = 'TUESDAY';
                                    } elseif ($dayorder == 8) {
                                        $dayOrder = 'WEDNESDAY';
                                    } elseif ($dayorder == 9) {
                                        $dayOrder = 'THURSDAY';
                                    } elseif ($dayorder == 10) {
                                        $dayOrder = 'FRIDAY';
                                    } elseif ($dayorder == 11) {
                                        $dayOrder = 'SATURDAY';
                                    } else {
                                        $dayOrder = '';
                                    }

                                    if ($dayOrder != '') {
                                        $DayTimetable = $dayOrder;
                                    }
                                }

                            }
                        } else {
                            $DayTimetable = null;
                        }
                    } else {
                        $get_subjects = [];
                        $class_name = null;
                        $DayTimetable = null;
                    }
                } else {
                    $get_subjects = [];
                    $class_name = null;
                    $class = null;
                    $DayTimetable = null;
                }
            } else {
                $get_subjects = [];
                $class_name = null;
                $class = null;
                $DayTimetable = null;
            }

            $teaching_staffs = TeachingStaff::select('user_name_id', 'name', 'StaffCode')->get();
        }
        $show = false;
        return view('admin.classTimeTable.show', compact('show', 'class_id', 'class', 'class_name', 'teaching_staffs', 'get_subjects', 'DayTimetable'));
    }

    public function getSections(Request $request)
    {
        // dd($request);
        $get_section = [];
        if ($request->course_id != '') {
            $check_input = is_numeric($request->course_id);
            if ($check_input) {
                $get_section = Section::where(['course_id' => $request->course_id])->get();
            } else {

                $get_course = ToolsCourse::where(['name' => $request->course_id])->first();
                if ($get_course != '') {
                    $get_section = Section::where(['course_id' => $get_course->id])->get();
                }
            }
        }
        return response()->json(['data' => $get_section]);
    }

    public function getCourse(Request $request)
    {
        if ($request->shift) {
            $data = ToolsCourse::where('shift_id', $request->shift)->pluck('short_form', 'id');
            // $staff = TeachingStaff::where('shift_id', $request->shift)->pluck('short_form', 'id');
            $shift = $request->shift;
            $staff = TeachingStaff::with('personal_details:user_name_id,employment_status')
                ->where(function ($query) use ($shift) {
                    $query->where('shift_id', $shift)
                        ->orWhereNull('shift_id');
                })
                ->select('user_name_id', 'name', 'StaffCode')
                ->get();

            // dd($staff);
            if ($data) {
                return response()->json(['status' => true, 'data' => $data, 'staff' => $staff]);
            } else {
                return response()->json(['status' => false, 'data' => 'Course Not found.']);
            }
        }
    }
}
