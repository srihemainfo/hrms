<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\CourseEnrollMaster;
use App\Models\Student;
use App\Models\ToolsCourse;
use Illuminate\Http\Request;

class StudentDetailsController extends Controller
{
    public function index(Request $request)
    {
        // abort_if(Gate::denies('student_details_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $courses = ToolsCourse::pluck('short_form', 'id');
        $batches = Batch::pluck('name', 'id');
        $ays = AcademicYear::pluck('name', 'id');

        return view('admin.student_reports.stuDetailIndex', compact('courses', 'batches', 'ays'));
    }

    public function generate(Request $request)
    {

        if (isset($request->batch) && isset($request->ay) && isset($request->course) && isset($request->semester)) {
            $batch = null;
            $ay = null;
            $course = null;
            $course_short_form = null;
            $getBatch = Batch::where(['id' => $request->batch])->select('name')->first();
            if ($getBatch != '') {
                $batch = $getBatch->name;
            }
            $getAy = AcademicYear::where(['id' => $request->ay])->select('name')->first();
            if ($getAy != '') {
                $ay = $getAy->name;
            }
            $getCourse = ToolsCourse::where(['id' => $request->course])->select('name', 'short_form')->first();
            if ($getCourse != '') {
                $course = $getCourse->name;
                $course_short_form = $getCourse->short_form;
            }
            if ($batch == null || $ay == null || $course == null || !is_numeric($request->batch) || !is_numeric($request->ay) || !is_numeric($request->course) || !is_numeric($request->semester)) {

                return back()->with('error', 'Technical Error');

            } else {
                $semester = $request->semester;
                $make_enroll = $batch . '/' . $course . '/' . $ay . '/' . $semester;

                $getEnroll = CourseEnrollMaster::where('enroll_master_number', "LIKE", "%$make_enroll%")->select('id', 'enroll_master_number')->get();

                if (count($getEnroll) > 0) {
                    $enrollIds = [];
                    $enrolls = [];

                    foreach ($getEnroll as $enroll) {
                        array_push($enrollIds, $enroll->id);
                        $explode = explode('/', $enroll->enroll_master_number);
                        $enrolls[$enroll->id] = $explode[4];
                    }

                    $datas = Student::with(['personal_details:user_name_id,dob,email,gender,mobile_number', 'parent_details:user_name_id,father_name', 'documents' => function ($query) {$query->where('fileName', 'Profile')->whereNotNull('filePath')->select('nameofuser_id');}])->whereIn('enroll_master_id', $enrollIds)->select('name', 'register_no', 'user_name_id', 'enroll_master_id')->get();

                    foreach ($datas as $student) {
                        $section = $enrolls[$student->enroll_master_id] ?? null;
                        $student->section = $section;
                        if ($student->documents != null) {
                            $student->photo = true;
                        } else {
                            $student->photo = false;
                        }
                    }
                    return view('admin.student_reports.stuDetailDownload', compact('ay', 'course_short_form', 'semester', 'batch', 'datas'));
                } else {
                    return back()->with('error', 'Classes Not Found');
                }
            }
        } else {
            return back()->with('error', 'Required Details Not Found');
        }
    }
}
