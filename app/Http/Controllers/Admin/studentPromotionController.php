<?php

namespace App\Http\Controllers\Admin;

use App\Models\Batch;
use App\Models\Section;
use App\Models\Student;
use App\Models\Semester;
use App\Models\ToolsCourse;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\AcademicDetail;
use App\Models\CourseEnrollMaster;
use App\Http\Controllers\Controller;
use App\Models\StudentPromotionHistory;
use Illuminate\Support\Facades\Validator;

class studentPromotionController extends Controller
{
    public function index()
    {

        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $AcademicYear = AcademicYear::pluck('name', 'id');
        $batch = Batch::pluck('name', 'id');
        return view('admin.student_promotion.index', compact('courses', 'semester', 'AcademicYear', 'batch'));
    }

    public function search(Request $request)
    {
        $validator = Validator::make($request->data, [
            'batch' => 'required',
            'course' => 'required',
            'accademicyear' => 'required',
            'semester' => 'required',
            'section' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => ['Please fill in all details']], 400);
        }
        $createEnroll = $request->data['batch'] . '/' . $request->data['course'] . '/' . $request->data['accademicyear'] . '/' . $request->data['semester'] . '/' . $request->data['section'];
        $search = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%' . $createEnroll . '%')->first();
        if ($search != null) {
            $studentId = $search->id;
            $studentDetails = Student::where('enroll_master_id', $studentId)->get();
            if ($studentDetails->isNotEmpty()) {
                foreach ($studentDetails as $studentDetail) {
                    $enrollMaster = explode('/', $search->enroll_master_number);
                    $studentDetail->Academic_Year = !empty($enrollMaster[2]) ? $enrollMaster[2] : '';
                    $dept = !empty($enrollMaster[1]) ? $enrollMaster[1] : '';
                    $course = ToolsCourse::where('name', $enrollMaster[1])->first();
                    $studentDetail->admitted_course = ($course) ? $course->short_form : '';
                    $studentDetail->dept = ($course) ? $course->department->name : '';
                    $studentDetail->current_semester = ($enrollMaster) ? $enrollMaster[3] : '';
                    $studentDetail->section = ($enrollMaster) ? $enrollMaster[4] : '';
                }
                return response()->json(['data' => $studentDetails, 'accYear' => $request->data['accademicyear'], 'batch' => $request->data['batch'], 'course' => $request->data['course'], 'semester' => $request->data['semester'], 'section' => $request->data['section']]);
            } else {
                return response()->json(['errors' => ['Sorry, there are no students for these details']], 404);
            }
        } else {

            return response()->json(['errors' => ['Sorry, there are no Class for these details']], 404);
        }

    }
    public function promote(Request $request)
    {
        if ($request) {
            $validator = Validator::make($request->data, [
                'batch' => 'required',
                'course' => 'required',
                'accademicyear' => 'required',
                'semester' => 'required',
                'section' => 'required',
                'semesterUp' => 'required',
                'sectionUP' => 'required',
                'selectedVal' => 'required',
                'accYearUp' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => ['Please fill in all details']], 400);
            }
            $createEnroll = $request->data['batch'] . '/' . $request->data['course'] . '/' . $request->data['accademicyear'] . '/' . $request->data['semester'] . '/' . $request->data['section'];
            $search = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%' . $createEnroll . '%')->first();
            if (!empty($search)) {
                $createEnroll2 = $request->data['batch'] . '/' . $request->data['course'] . '/' . $request->data['accYearUp'] . '/' . $request->data['semesterUp'] . '/' . $request->data['sectionUP'];
                $search2 = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%' . $createEnroll2 . '%')->first();
                if (!empty($search2)) {
                    $count = count($request->data['selectedVal']);
                    $successCount = 0;
                    foreach ($request->data['selectedVal'] as $newDetails) {
                        $getUserId = Student::where(['enroll_master_id' => $search->id, 'register_no' => $newDetails[7]])->first();
                        if ($getUserId != '') {
                            $Got_user = $getUserId->user_name_id;
                        } else {
                            $Got_user = null;
                        }
                        $studentDetails = true;
                        $studentDetails = Student::where([
                            'enroll_master_id' => $search->id,
                            'register_no' => $newDetails[7],
                        ])->update([
                            'enroll_master_id' => $search2->id,
                            'current_semester' => $request->data['semesterUp'],
                            'section' => $request->data['sectionUP'],
                        ]);

                        $accademic = AcademicDetail::where(['enroll_master_number_id' => $search->id,
                            'register_number' => $newDetails[7]])->update(['enroll_master_number_id' => $search2->id,
                        ]);
                        $promotionHistory = StudentPromotionHistory::create([
                            'user_name_id' => $Got_user,
                            'enroll_master_id' => $search->id,
                            'promoted_by' => auth()->user()->id,
                        ]);
                        if ($studentDetails) {
                            $successCount++;
                        }
                    }
                    if ($successCount > 0) {
                        return response()->json(['message' => 'Promotion successful for ' . $successCount . ' out of ' . $count . ' students'], 200);
                    } else {
                        return response()->json(['errors' => 'Promotion failed for all students'], 400);
                    }
                } else {
                    return response()->json(['errors' => ['New Enroll Master ID Not Found']], 400);
                }
            }
            return response()->json(['errors' => ['Promotion failed']], 400);

        }
    }

    public function getSections(Request $request)
    {

        if (isset($request->course) && $request->course != '') {
            $getCourse = ToolsCourse::where('name',$request->course)->select('id')->first();
            if($getCourse != ''){
                $getData = Section::where(['course_id' => $getCourse->id])->select('id', 'section')->get();
                if (count($getData) > 0) {
                    return response()->json(['status' => true, 'data' => $getData]);
                } else {
                    return response()->json(['status' => false, 'data' => 'Sections Not Found']);
                }
            }else{
                return response()->json(['status' => false, 'data' => 'Course Not Found']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Course Not Found']);
        }
    }
}
