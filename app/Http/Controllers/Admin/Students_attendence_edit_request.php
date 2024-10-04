<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Subject;
use App\Models\UserAlert;
use App\Models\ToolsCourse;
use Illuminate\Http\Request;
use App\Models\TeachingStaff;
use App\Models\AttendanceRecord;
use App\Models\CourseEnrollMaster;
use App\Http\Controllers\Controller;
use App\Models\ClassTimeTableTwo;
use App\Models\ClassTimeTableOne;
use Illuminate\Support\Facades\DB;
use App\Models\ClassRoom;
use App\Models\User;
use App\Models\ToolsDepartment;
use App\Models\Student;

class Students_attendence_edit_request extends Controller
{
    public function index(Request $request)
    {
        $status = 'Edit';
        if (isset($request->status)) {
            $status = $request->status;
        }
        if ($status == 'Edit') {
            $studentAtt = AttendanceRecord::where(['status' => 0])->get();
        } else {
            $studentAtt = AttendanceRecord::where(['status' => 55])->get();
        }
        $subjects = [];
        foreach ($studentAtt as $i => $studentAtts) {
            $nameEnroll = CourseEnrollMaster::find($studentAtts->enroll_master);
            $staffName = TeachingStaff::where('user_name_id', $studentAtts->staff)->first();
            $studentAtts->staff_ID = $studentAtts->staff;
            if ($staffName) {
                $studentAtts->staff = $staffName->name;
            } else {
                $studentAtts->staff = '';
            }

            if ($nameEnroll) {
                $get_course = explode('/', $nameEnroll->enroll_master_number);
                $get_short_form = ToolsCourse::where('name', $get_course[1])->value('short_form');

                if ($get_short_form) {
                    $get_course[1] = $get_short_form;
                }

                $subjects[$i][1] = $get_course[1] . ' / ' . $get_course[3] . ' / ' . $get_course[4];
                $studentAtts->enroll_master = implode(', ', $subjects[$i]);
            }

            $get_subject = Subject::where(['id' => $studentAtts->subject])->first();
            if ($get_subject != '') {
                $studentAtts->subject = $get_subject->name . '  (' . $get_subject->subject_code . '  )';
            } else {
                $studentAtts->subject = '';
            }
        }

        return view('admin.student_atten_edit.index', compact('studentAtt', 'status'));
    }

    public function approve(Request $request)
    {

        $id = $request->input('id');
        $studentAtt = AttendanceRecord::where('id', $id)->first();
        if ($studentAtt) {
            if ($request->action == 'Edit') {
                $action = AttendanceRecord::where(['id' => $id])->update(['status' => '100']);
            } else {
                $action = AttendanceRecord::where(['id' => $id])->update(['deleted_at' => Carbon::now()]);
                // $updatetable = AttendenceTable::where(['subject' => '','staff' => '','period' => '','enroll_master' =>'','date' => $studentAtt->actual_date ])
            }
            if ($action) {
                $userAlert = new UserAlert;
                $userAlert->alert_text = auth()->user()->name . ' Approved Your Attendance ' . $request->action . ' Request.';
                $userAlert->alert_link = route('admin.student-period-attendance.index');
                $userAlert->save();
                $userAlert->users()->sync($request->input('users', [$studentAtt->staff])); // Sync staff ID

                // Additional actions or notifications if needed
            }
        }

        return redirect()->route('admin.student-att-modification.index',['status' => $request->action]);
    }

    // public function reject(Request $request)
    // {

    //     $id = $request->input('id');
    //     $studentAtt = AttendanceRecord::where('id', $id)->first();
    //     if ($studentAtt) {
    //         $statusChange = AttendanceRecord::where('id', $id)->update(['status' => '99', 'reason' => 'Rejected by  ' . auth()->user()->name . ' ']);
    //         if ($statusChange) {
    //             $staff_id = $studentAtt->staff;
    //             $userAlert = new UserAlert;
    //             $userAlert->alert_text = auth()->user()->name . ' Deleted your attendance edit request.';
    //             $userAlert->alert_link = route('admin.student-period-attendance.index');
    //             $userAlert->save();
    //             $userAlert->users()->sync($request->input('users', [$staff_id])); // Sync staff ID

    //             // Additional actions or notifications if needed
    //         }
    //         if ($statusChange) {
    //             AttendanceRecord::where('id', $id)->delete();
    //         }

    //     }

    //     return redirect()->route('admin.student-att-modification.index');
    // }

    function exam_attdence(Request $request){

        $status = $request -> status;

        if($status == 'Edit'){

            $enrollment_master = '2021-2025/B.Tech. Computer Science & Business Systems/2023-2024/5/A';

            $id=CourseEnrollMaster::where('enroll_master_number','LIKE',"%{$enrollment_master}")->first();
            $enroll_id = $id-> id;

            $class = ClassRoom::where('name', $enroll_id)
            ->select('id', 'short_form', 'class_incharge')
            ->first();


            $students = Student::with(['enroll_master'])->where('enroll_master_id', $enroll_id)->select('user_name_id','name','register_no','student_batch','admitted_course')->get();
            
            $studentList  = [];
            foreach( $students as   $student){
                $studentList [] = $student;

            }
            return view('admin.exam_attendance2.index', compact('studentList','class'));
            // return redirect()->route('admin.exam_Attendance2.index', ['students' => $studentList]);



            // dd($query);
            // $checkTables = ClassTimeTableOne::whereIn('class_name', $get_enrollment->pluck('id'))
            // ->orderBy('updated_at', 'desc')
            // ->get()
            // ->groupBy('class_name')
            // ->sortByDesc('updated_at');

        // $result = [];
        // foreach ($checkTables as $class_name => $data) {
        //     // $user = User::find($data[0]->created_by);
        //     $version = DB::table('timetable_versions')
        //         ->where('class_id', $data[0]->class_name)
        //         ->latest('updated_at')
        //         ->first();
        //     // $class = ClassRoom::with([ 'enroll_master'])-> Where(['enroll_master' =>  $enrollment_master])->get();
        //     $class_name = CourseEnrollMaster::find($class_name);
        //     $result[] = [
        //         'class_name' => $class_name->enroll_master_number ?? '',
        //         // 'user' => $user,
        //         'version' => $version,
        //         'status' => $data[0]->status,
        //         'id' => $data[0]->id,
        //         'classId' => $class_name->id ?? '',
        //     ];
        //     dd($result);

        // }




    }


    }

}
