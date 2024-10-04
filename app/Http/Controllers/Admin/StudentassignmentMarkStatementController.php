<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AssignmentAttendances;
use App\Models\ClassRoom;
use App\Models\CollegeBlock;
use App\Models\CourseEnrollMaster;
use App\Models\AssignmentModel;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use App\Models\SubjectAllotment;
use App\Models\AssignmentData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ExamCellCoordinator;
use App\Models\User;
use App\Models\TeachingStaff;
use App\Models\NonTeachingStaff;
use App\Models\ClassTimeTableTwo;
use App\Models\Section;
use App\Models\SubjectRegistration;
use App\Models\PersonalDetail;
use PDF;

class StudentassignmentMarkStatementController extends Controller
{
    public function index(Request $request)
    {
        if (isset($request->user_name_id)) {
            $user = $request->user_name_id;
        } else {
            $user = auth()->user()->id;
        }
        $enrollMaster = Student::where('user_name_id', $user)->first();
        if ($enrollMaster) {
            $CourseEnrollMaster = CourseEnrollMaster::find($enrollMaster->enroll_master_id);

            if ($CourseEnrollMaster) {
                $enrollName = $CourseEnrollMaster->enroll_master_number;
                $enrollArray = explode('/', $enrollName);

                if ($enrollArray) {
                    $getCourse = ToolsCourse::where('name', $enrollArray[1])->first();
                    if ($getCourse) {
                        $department = $getCourse->department_id;
                        $courseId = $getCourse->id;
                    } else {
                        $department = '';
                        $courseId = '';
                    }
                    $semester = Semester::where('semester', $enrollArray[3])->first();
                    if ($semester) {
                        $semId = $semester->id;
                    } else {
                        $semId = '';
                    }

                    $getAcademicYear = AcademicYear::where('name', $enrollArray[2])->first();
                    if ($getAcademicYear) {
                        $accId = $getAcademicYear->id;
                    } else {
                        $accId = '';
                    }
                    $getSection = Section::with('course')->where('section', $enrollArray[4])->where('course_id', $courseId)->first();
                    if ($getSection) {
                        $secId = $getSection->id;
                        $Section = $getSection->section;
                    } else {
                        $secId = '';
                    }
                }

                if ($department != '' && $courseId != '' && $semId != '' && $accId != '') {
                    $allotedSubjects = SubjectAllotment::where([
                        'department' => $department,
                        'semester' => $semId,
                        'course' => $courseId,
                        'academic_year' => $accId,
                    ])->get();
                }
            }
        }
        $studentID = $user;
        $statusCheck = null;

        $studentDetails = Student::where('user_name_id', $studentID)->first();
        $stuStatus = SubjectRegistration::where(['user_name_id' => $studentID, 'enroll_master' => $studentDetails->enroll_master_id])->first();
        if ($stuStatus != '') {
            $id = $stuStatus->id;

            $row = SubjectRegistration::find($id);

            if ($row) {
                $allotedSubjects = SubjectRegistration::where(['user_name_id' => $row->user_name_id, 'enroll_master' => $row->enroll_master])->select('subject_id')->get();
                $allSubjectId = $allotedSubjects->pluck('subject_id');
                $user_name_id = $row->user_name_id;
                $assignmentExamAttendanceIdExamName = AssignmentAttendances::where(['status' => 2, 'course' => $courseId, 'academic_year' => $accId, 'semester' => $semId, 'section' => $Section])->whereIn('subject',$allSubjectId)->select('id','exam_name','subject')->get();

            if(count($assignmentExamAttendanceIdExamName) > 0){
            foreach($assignmentExamAttendanceIdExamName as $subjectId){

                $getSubjectNameCode = Subject::where('id', $subjectId->subject)->whereIn('subject_type_id', [1,2,7,8,13,14])->select('name','subject_code')->first();
                if($getSubjectNameCode ){
                $subjectId -> subjectName = $getSubjectNameCode->name;
                $subjectId -> subjectCode = $getSubjectNameCode->subject_code;
                $subjectStaffNameId = ClassTimeTableTwo::where(['class_name' => $enrollMaster->enroll_master_id, 'subject' => $subjectId->subject])->select('staff')->first()->staff;
                $StaffName = PersonalDetail::where('user_name_id', $subjectStaffNameId)->select('name')->first()->name;
                $subjectId -> StaffName = $StaffName ;

                // $assignmentExamAttendanceIdExamName = AssignmentAttendances::where(['status' => 2, 'course' => $courseId, 'subject' => $subjectId->subject_id, 'academic_year' => $accId, 'semester' => $semId, 'section' => $Section])->select('id','exam_name')->first();


                // if($assignmentExamAttendanceIdExamName){

                    // $subjectId-> exam_name = $subjectId->name;
                    $studentExamMarkDetails = AssignmentData::where(['class_id' => $CourseEnrollMaster->id, 'assignment_name_id' => $subjectId->id, 'student_id' => $enrollMaster->user_name_id, 'subject' => $subjectId->subject ])->first();
                    $total = 0;
                    $count = 0;
                    if($studentExamMarkDetails){


                        for($si=1; $si <=5; $si++ ){
                            $subjectId -> {'Assignment_Mark_Title_'.$si} = 'Assignment Mark-'.$si;

                            if($studentExamMarkDetails->{'assignment_mark_'.$si} != null){
                            $subjectId -> {'assignment_mark_'.$si} = $studentExamMarkDetails->{'assignment_mark_'.$si};
                            $subjectId -> total += $studentExamMarkDetails->{'assignment_mark_'.$si};
                        }else{

                            $subjectId -> {'assignment_mark_'.$si} = $studentExamMarkDetails->{'assignment_mark_'.$si};
                            $subjectId -> total += 0;
                            $count += 1;
                            if($count == 5){
                                $subjectId -> total = '';

                            }


                            }
                        }

                    }
                    else{

                        for($si=1; $si <=5; $si++ ){
                            $subjectId -> {'assignment_mark_'.$si} = '';
                            $subjectId -> {'Assignment_Mark_Title_'.$si} = 'assignment_mark_'.$si;
                        }
                        $subjectId ->exam_name = $assignmentExamAttendanceIdExamName->name;
                        $subjectId -> total = '';

                    }
                // }
                // else{

                //     for($si=0; $si <=5; $si++ ){
                //         $subjectId -> {'assignment_mark_'.$si} = '';
                //         $subjectId -> {'Assignment_Mark_Title_'.$si} = 'Assignment Mark-'.$si;
                //     }
                //     $subjectId ->exam_name = '';
                //     $subjectId -> total = '';

                // }
            }


            }
        }


          $examMarks = $assignmentExamAttendanceIdExamName ;

        }else{

            $examMarks = [];
        }

        } else {
            $examMarks = [];

        }
        if (isset($request->cat_mark)) {

            return response()->json(['examMarks' => $examMarks]);
        } else {

            return view('admin.assignmentStudentMarkView.view', compact('examMarks'));
        }
    }
}
