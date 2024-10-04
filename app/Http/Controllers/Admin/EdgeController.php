<?php

namespace App\Http\Controllers\Admin;

use App\Models\NonTeachingStaff;
use Gate;
use App\Models\Student;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Models\TeachingStaff;
use App\Models\CourseEnrollMaster;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class EdgeController extends Controller
{
    public function staff()
    {
        abort_if(Gate::denies('staff_edge_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $name = '';
        $first_entry = '';

        return view('admin.edges.staff', compact('name','first_entry'));
    }

    public function staff_geter()
    {
        $staff = TeachingStaff::select('name', 'StaffCode')->get();
        $non_teaching_staff = NonTeachingStaff::select('name', 'StaffCode')->get();

        $array = [];

        if ($staff->count() > 0) {
            for ($i = 0; $i < count($staff); $i++) {
                array_push($array, $staff[$i]->name . '  ( ' . $staff[$i]->StaffCode . ')');
            }
        }
        if ($non_teaching_staff->count() > 0) {
            for ($i = 0; $i < count($non_teaching_staff); $i++) {
                array_push($array,$non_teaching_staff[$i]->name . '  ( ' . $non_teaching_staff[$i]->StaffCode .' )');
            }
        }

        return response()->json(['staff' => $array]);

    }

    public function student()
    {
        abort_if(Gate::denies('student_edge_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $name = '';

        return view('admin.edges.student', compact('name'));
    }

    public function student_geter()
    {
        $student = Student::select('name', 'register_no')->get();

        $array = [];
        if ($student->count() > 0) {
            for ($i = 0; $i < count($student); $i++) {
                array_push($array, $student[$i]->name . '  ( '. $student[$i]->register_no . ' )');
            }
        }
        return response()->json(['student' => $array]);
    }

    public function student_edge(Request $request)
    {

        if ($request) {

            $enroll_masters = CourseEnrollMaster::pluck('enroll_master_number', 'id');

            $name_by_student = Student::where(['name' => $request->name])->first();

            if ($name_by_student) {
                $document = Document::where(['nameofuser_id' => $name_by_student->user_name_id, 'fileName' => 'Profile'])->first();
                $student = $name_by_student;
                if ($document) {
                    $student->filePath = $document->filePath;
                } else {
                    $student->filePath = '';
                }

            } else {
                $code_by_student = Student::where(['roll_no' => $request->name])->first();
                $document = Document::where(['nameofuser_id' => $code_by_student->user_name_id, 'fileName' => 'Profile'])->first();
                $student = $code_by_student;
                if ($document) {
                    $student->filePath = $document->filePath;
                } else {
                    $student->filePath = '';
                }

            }
            if ($student->enroll_master_id != '') {
                foreach ($enroll_masters as $id => $entry) {
                    if ($id == $student->enroll_master_id) {
                        $student->enroll_master = $entry;
                    }
                }
            } else {
                $student->enroll_master = '';
            }

            return response()->json($student);

        }
    }
    public function staff_hr(Request $request)
    {
        if (isset($request->name) && $request->name != '') {
            $name = $request->name;
            $first_entry = '';
        } else {
            $first_entry = '';
            $name = '';
        }

        return view('admin.edges.staff', compact('first_entry', 'name'));
    }
}
