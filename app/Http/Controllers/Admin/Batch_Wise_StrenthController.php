<?php

namespace App\Http\Controllers\Admin;

use App\Models\Batch;

use App\Models\Student;
use App\Models\ToolsCourse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;


class Batch_Wise_StrenthController extends Controller
{
    public function index(Request $request)
    {

        $courseBatchCounts = Student::select('admitted_course', 'student_batch')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('admitted_course', 'student_batch')
            ->get();

        $courses = ToolsCourse::pluck('name', 'id');
        $allCourses = $courses->all();

        $batches = Batch::pluck('name', 'id');
        $allBatches = $batches->all();
        $shortForm = ToolsCourse::pluck('short_form', 'id');


        $batchCourseCounts = [];


        foreach ($courseBatchCounts as $row) {
            $batch = $row->student_batch;
            $course = $row->admitted_course;
            $count = $row->count;

            // Initialize the batch count if it doesn't exist
            if (!isset($batchCourseCounts[$batch])) {
                $batchCourseCounts[$batch] = [];
            }

            // Assign the count for the corresponding course in the batch
            $batchCourseCounts[$batch][$course] = $count;
        }
        return view('admin.studentBatchwiseReport.index',compact('batchCourseCounts','allCourses','allBatches','shortForm'));
    }
}
