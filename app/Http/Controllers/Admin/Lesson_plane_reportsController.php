<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendenceTable;
use App\Models\LessonPlans;
use App\Models\TeachingStaff;
use App\Models\ToolsDepartment;
use Illuminate\Http\Request;

class Lesson_plane_reportsController extends Controller
{
    public function index()
    {
        $department = ToolsDepartment::get();
        return view('admin.lesson_plane_report.index', compact('department'));
    }

    public function search(Request $request)
    {

        if ($request) {
            $staff = TeachingStaff::where('Dept', $request->input('department'))->get();
        }
        return response()->json(['response' => $staff]);

    }

    public function showTable(Request $request)
    {$lessonPlane = LessonPlans::where(['user_name_id' => $request->input('staff_name'), 'status' => 1])
        ->whereBetween('proposed_date', [$request->input('fromdate'), $request->input('todate')])
        ->get();

    $mergedData = $lessonPlanesArr = $attendenceArr = [];
    $mergedData = [];

    foreach ($lessonPlane as $lessonPlanes) {
        $attendence = AttendenceTable::where([
            'staff' => $lessonPlanes->user_name_id,
            'subject' => $lessonPlanes->subject,
            'unit' => $lessonPlanes->unit_no,
            'topic' => $lessonPlanes->topic_no
        ])->first();

        $mergedData[] = [
            'lessonPlanes' => $lessonPlanes,
            'attendence' => $attendence
        ];
    }

    return response()->json($mergedData);

    }
}
