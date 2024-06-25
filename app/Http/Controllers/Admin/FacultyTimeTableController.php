<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassTimeTableTwo;
use App\Models\CourseEnrollMaster;
use App\Models\Subject;
use App\Models\TeachingStaff;
use App\Models\ToolsCourse;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class FacultyTimeTableController extends Controller
{
    public function staff()
    {
        abort_if(Gate::denies('staff_edge_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $name = '';
        $first_entry = '';

        return view('admin.Faculty_timetable.timetable_faculty_edge', compact('name', 'first_entry'));
    }

    public function staff_geter()
    {

        $id = auth()->user()->roles[0]->id;
        if ($id == 14) {

            $array = [];
            $user_id = auth()->user()->id;
            $hodName = User::where('id', $user_id)->first();
            $hod = $hodName->dept;
            $staff = TeachingStaff::where('Dept', $hod)->select('name', 'StaffCode')->get();

            if ($staff->count() > 0) {
                for ($i = 0; $i < count($staff); $i++) {
                    array_push($array, $staff[$i]->name . '  ( ' . $staff[$i]->StaffCode . ')');
                }
            }

        } else {

            $staff = TeachingStaff::select('name', 'StaffCode')->get();

            $array = [];

            if ($staff->count() > 0) {
                for ($i = 0; $i < count($staff); $i++) {
                    array_push($array, $staff[$i]->name . '  ( ' . $staff[$i]->StaffCode . ')');
                }
            }

        }
        return response()->json(['staff' => $array]);
    }

    public function Facultytimetable(Request $request)
    {
        $name = $request->staff_name;
        $first_entry = '';
        $user_name = $request->staff_name;
        $delimiter = "(";
        $parts = explode($delimiter, $user_name);
        $csValue = trim($parts[1], " )");
        $staff = TeachingStaff::where('StaffCode', $csValue)->select('user_name_id')->first();

        $timetable = [];
        $user_name_id = $staff->user_name_id;
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
            $get_subjects = Subject::get();

        } else {
            $get_subjects = [];
            $timetable = [];
        }

        return view('admin.Faculty_timetable.timetable_faculty_edge', compact('get_subjects', 'timetable', 'name', 'first_entry'));
    }
}
