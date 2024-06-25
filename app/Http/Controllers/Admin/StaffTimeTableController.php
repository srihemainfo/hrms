<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassTimeTableTwo;
use App\Models\CourseEnrollMaster;
use App\Models\Subject;
use App\Models\ToolsCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StaffTimeTableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $timetable = [];
        $user_name_id = auth()->user()->id;
        // $getAys = AcademicYear::where(['status' => 0])->pluck('name', 'id');
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
