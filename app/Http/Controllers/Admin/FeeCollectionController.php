<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class FeeCollectionController extends Controller
{
    public function index()
    {
        return view('admin.feeCollection.index');
    }

    public function fetch_detils()
    {
        $student = Student::select('roll_no')->get();
        $array = [];
        if ($student->count() > 0) {
            for ($i = 0; $i < count($student); $i++) {
                array_push($array, $student[$i]->roll_no);
            }
        }
        return response()->json(['student' => $array]);
    }

    public function getStudentData(Request $request)
    {
    
        $roll_no = $request->input('roll_no');
        $student = Student::where('roll_no', $roll_no)->first();
        // dd($student);
        if ($student) {
            $name = $student->name;
            $roll_no = $student->roll_no;
            return response()->json(['status'=> true, 'name' => $name, 'roll_no' => $roll_no]);
        } else {
            
            return response()->json(['error' => 'Student not found']);
        }
    }

}
