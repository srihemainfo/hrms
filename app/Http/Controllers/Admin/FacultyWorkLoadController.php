<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ClassTimeTableTwo;
use App\Models\CourseEnrollMaster;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\TeachingStaff;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FacultyWorkLoadController extends Controller
{
    public function index()
    {
        $courses = ToolsCourse::pluck('name', 'id');
        $AcademicYear = AcademicYear::pluck('name', 'id');
        $departments = ToolsDepartment::pluck('name', 'id')->prepend('Select Department', '');

        return view('admin.facultyWorkload.index', compact('courses', 'AcademicYear', 'departments'));
    }

    public function show(Request $request)
    {
        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $AcademicYear = AcademicYear::pluck('name', 'id');
        $departments = ToolsDepartment::pluck('name', 'id')->prepend('Select Department', '');
        $department = ToolsDepartment::find($request->department);
        $staffData = [];
        $theCourse = ToolsCourse::pluck('short_form', 'name');
        $theCourse = $theCourse->toArray();
        $theClass = [];
        if ($request->AcademicYear != '') {
            $getClass = CourseEnrollMaster::where('enroll_master_number', "LIKE", '%/%/' . $request->AcademicYear . '/%/%')->select('id')->get();
            if (count($getClass) > 0) {
                foreach ($getClass as $enrolledClass) {
                    array_push($theClass, $enrolledClass->id);
                }
            }
        } else {
            return back()->with('error', 'AY Not Found');
        }
        if ($request->semester == '') {
            if ($department != '') {
                $teachingStaffs = TeachingStaff::where('Dept', $department->name)->whereHas('personal_details', function ($query) {
                    $query->where('employment_status', '!=', 'Relieving')
                        ->orWhereNull('employment_status');
                })->select('user_name_id', 'name', 'StaffCode')->orderBy('name', 'ASC')->get();

                foreach ($teachingStaffs as $staff) {
                    $getTimeTable = ClassTimeTableTwo::with('subjects:id,name,subject_code', 'enroll_master:id,enroll_master_number')->whereIn('class_name', $theClass)->where(['staff' => $staff->user_name_id])->selectRaw('subject,class_name,COUNT(period)as count')->groupBy('subject', 'class_name')->get();
                    $count = 0;
                    if (count($getTimeTable) > 0) {
                        foreach ($getTimeTable as $timetable) {
                            $class = $timetable->enroll_master->enroll_master_number;
                            $getCourse = explode('/', $class);
                            if (array_key_exists($getCourse[1], $theCourse)) {
                                $getCourse[1] = $theCourse[$getCourse[1]];
                            }
                            $setClass = $getCourse[1] . '/' . $getCourse[3] . '/' . $getCourse[4];
                            $count += (int) $timetable->count;
                            $timetable->class_name = $setClass;
                        }
                    }
                    $data = ['staff' => $staff, 'time_table' => $getTimeTable, 'count' => $count];
                    array_push($staffData, $data);
                }
            }
        } else {
            if ($department != '') {
                $teachingStaffs = TeachingStaff::where('Dept', $department->name)->whereHas('personal_details', function ($query) {
                    $query->where('employment_status', '!=', 'Relieving')
                        ->orWhereNull('employment_status');
                })->select('user_name_id', 'name', 'StaffCode')->orderBy('name', 'ASC')->get();

                foreach ($teachingStaffs as $staff) {
                    $getTimeTable = ClassTimeTableTwo::with('subjects:id,name,subject_code', 'enroll_master:id,enroll_master_number')->whereIn('class_name', $theClass)->where(['staff' => $staff->user_name_id])->selectRaw('subject,class_name,COUNT(period)as count')->groupBy('subject', 'class_name')->get();
                    $tempData = [];
                    $count = 0;
                    if (count($getTimeTable) > 0) {
                        foreach ($getTimeTable as $timetable) {
                            $class = $timetable->enroll_master->enroll_master_number;
                            $getCourse = explode('/', $class);
                            if ($getCourse[3] == $request->semester) {
                                if (array_key_exists($getCourse[1], $theCourse)) {
                                    $getCourse[1] = $theCourse[$getCourse[1]];
                                }
                                $setClass = $getCourse[1] . '/' . $getCourse[3] . '/' . $getCourse[4];
                                $timetable->class_name = $setClass;
                                $count += (int) $timetable->count;
                                array_push($tempData, $timetable);
                            }
                        }
                    }

                    $data = ['staff' => $staff, 'time_table' => $tempData, 'count' => $count];
                    array_push($staffData, $data);
                }
            }
        }
        // dd($staffData[0]);
        return view('admin.facultyWorkload.index', compact('courses', 'semester', 'AcademicYear', 'departments', 'staffData'));
    }

    public function view(Request $request)
    {
        // dd();
        $timetable = [];
        $user_name_id = $request->user_name_id;
        $currentClasses = Session::get('currentClasses');

        $timetable = ClassTimeTableTwo::whereIn('class_name', $currentClasses)->where(['status' => 1, 'staff' => $user_name_id])->get();
        if (count($timetable) > 0) {
            foreach ($timetable as $table) {

                $get_enroll = CourseEnrollMaster::where(['id' => $table->class_name])->first();
                $table->class = $table->class_name;
                if ($get_enroll) {

                    $get_class_name = explode('/', $get_enroll->enroll_master_number);
                    $get_course = $get_class_name[1];
                    $semester = $get_class_name[3];

                    $course = ToolsCourse::where('name', 'LIKE', $get_course)->first();
                    $table->class_name = $course->short_form . ' / ' . $get_class_name[3] . ' / ' . $get_class_name[4];
                }
            }
        }
        $get_subjects = Subject::get();

        return view('admin.staffAcademicManage.timetable', compact('get_subjects', 'timetable'));
    }
}
