<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseEnrollMaster;
use App\Models\FeeStructure;
use App\Models\Student;
use App\Models\ToolsCourse;
use Illuminate\Http\Request;

class FeeCollectionController extends Controller
{
    public function index()
    {
        $students = Student::select('register_no', 'name')->get();
        return view('admin.feeCollection.index', compact('students'));
    }
    public function fetch_detils(Request $request)
    {
        $reg_no = $request->reg_no;
        $student = Student::where('register_no', $reg_no)->first();

        if ($student) {
            $name = $student->name;
            $course = $student->admitted_course;
            $batch = $student->student_batch;
            $semester = $student->current_semester;
            $section = $student->section;
            $phone_no = $student->student_phone_no;
            $enroll_master_id = $student->enroll_master_id;

            // Fetching short form of course name
            $toolcourse = ToolsCourse::where('name', $course)->first();
            $short_form = $toolcourse ? $toolcourse->short_form : null;

            // Fetching batch_id and course_id
            $course_enroll_masters = CourseEnrollMaster::where('id', $enroll_master_id)->first();
            $batch_id = $course_enroll_masters ? $course_enroll_masters->batch_id : null;
            $course_id = $course_enroll_masters ? $course_enroll_masters->course_id : null;

            $total_amounts = [];
            $feeStructures = FeeStructure::where('batch_id', $batch_id)
                ->where('course_id', $course_id)
                ->get();

            foreach ($feeStructures as $feeStructure) {
                $fee_component = $feeStructure->fee_component;
                if ($fee_component) {
                    $fee_component_array = json_decode($fee_component, true);

                    foreach ($fee_component_array as $component) {
                        if (isset($component['name']) && $component['name'] === 'Total') {
                            $total_amounts[$feeStructure->semester_id] = $component['amount'];
                        }
                    }
                }
            }

            return response()->json([
                'status' => true,
                'name' => $name,
                'short_form' => $short_form,
                'batch' => $batch,
                'semester' => $semester,
                'section' => $section,
                'phone_no' => $phone_no,
                'fee_details' => $total_amounts,
            ]);
        } else {
            return response()->json(['status' => false, 'data' => 'Please Enter Correct Number']);
        }
    }

}
