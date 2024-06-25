<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\ToolsCourse;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\Examattendance;
use App\Models\SubjectAllotment;
use App\Models\CourseEnrollMaster;
use App\Models\ExamattendanceData;
use App\Http\Controllers\Controller;
use App\Models\ExamTimetableCreation;
use App\Models\SubjectRegistration;
use App\Models\ClassTimeTableTwo;

class StudentCatMarkStatementController extends Controller
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
            $newdata = [];
            $co_array = [];
            if ($row) {
                $allotedSubjects = SubjectRegistration::where(['user_name_id' => $row->user_name_id, 'enroll_master' => $row->enroll_master])->select('subject_id')->get();
                $user_name_id = $row->user_name_id;
            }
            $status = isset($row->status) && $row->status != '' ? $row->status : '';
            $examNames = ExamTimetableCreation::where(['course' => $courseId, 'accademicYear' => $accId, 'semester' => $semId, 'sections' => $Section])->get();
            $courses = ToolsCourse::find($courseId);
            $examMarks = [];
            $get_staffs = [];
            $mergedArray = [];
            foreach ($examNames as $examName) {
                $si = 0;
                foreach ($allotedSubjects as $allotedSubject) {

                    $got_subject = Subject::where('id', $allotedSubject->subject_id)
                        ->whereNotIn('subject_type_id', [3, 9, 15])->select('name')
                        ->first();
                    if ($got_subject != null) {

                        $subject_id = $allotedSubject->subject_id;
                        $Subject = Subject::find($subject_id);
                        $staff = ClassTimeTableTwo::where(['class_name' => $enrollMaster->enroll_master_id, 'subject' => $Subject->id])->get();
                        $staff = ClassTimeTableTwo::select('staff')
                            ->where(['class_name' => $enrollMaster->enroll_master_id, 'subject' => $Subject->id])
                            ->distinct()
                            ->get();

                        foreach ($staff as $id => $staff) {
                            $find_staff = User::find($staff->staff);
                            $get_staff = $find_staff->name;
                            if (!in_array($get_staff, $get_staffs)) {
                                array_push($get_staffs, $get_staff);
                            }
                            $newdata[$si]['Staff'] = $get_staff;
                        }

                        $subject_name = $Subject->name;
                        $subject_code = $Subject->subject_code;
                        $newdata[$si]['subject_name'] = $subject_name;
                        $newdata[$si]['subject_code'] = $subject_code;

                        $exameAtt = Examattendance::where(['att_entered' => 'Yes', 'status' => 2, 'course' => $courseId, 'subject' => $Subject->id, 'acyear' => $accId, 'sem' => $semId, 'section' => $Section])->select('id', 'examename', 'exame_id', 'subject', 'att_entered', 'status')->get();
                        if (count($exameAtt) > 0) {
                            $values = [];
                            if (count($exameAtt) > 0) {
                                $g_total = 0;
                                $co = 1;
                                $newdata[$si]['co_total'] = 0;
                                foreach ($exameAtt as $exameatt => $examename) {
                                    $co_names = ExamTimetableCreation::where(['id' => $examename->exame_id])->select('co', 'exam_name')->first();
                                    $co_marks = $co_names->co;
                                    $unserializedData2 = unserialize($co_marks);
                                    foreach ($unserializedData2 as $key => $value2) {
                                        $newdata[$si]['exam_title']["Exam_name$co"] = $examename->examename;
                                        $newdata[$si]['co_val'][$key] = $value2;
                                        $newdata[$si]['co_total'] += $value2;
                                        $co++;
                                    }

                                    $examMark = ExamattendanceData::where(['class_id' => $CourseEnrollMaster->id, 'examename' => $examename->id, 'student_id' => $enrollMaster->user_name_id, 'subject' => $Subject->id])->get();
                                    foreach ($examMark as $id => $value) {

                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($value->{'co_' . $i} != null) {
                                                if ($value->{'co_' . $i} != 999) {
                                                    $total = $value->{'co_' . $i};
                                                    $newdata[$si]['co_mark' . $i] = $value->{'co_' . $i};
                                                } elseif ($value->{'co_' . $i} == 999) {
                                                    $newdata[$si]['co_mark' . $i] = 'Absent';
                                                    $total = 0;
                                                }
                                                $g_total = $g_total + $total;
                                                $newdata[$si]['total'] = $g_total;
                                            }
                                        }
                                    }
                                }
                                $si++;
                            }
                        }
                    }
                }
            }

            if (count($newdata) > 0) {

                $examMarks = $newdata;

                $uniqueCOKeys = [];
                $uniqueExamNames = [];
                $names = [];
                $co_values = [];
                $NO = [];
                $maxCoTotal = 0;
                foreach ($examMarks as $id => $examMark) {

                    if (isset($examMark['exam_title']) &&  isset($examMark['co_val'])) {

                        if (isset($examMark['co_total'])) {
                            $coTotal = $examMark['co_total'];
                            if ($coTotal > $maxCoTotal) {
                                $maxCoTotal = $coTotal; // Update the maximum co_total if a higher value is found
                            }
                        }

                        $exam_title = $examMark['exam_title'] ?? [];
                        $co_val = $examMark['co_val'] ?? [];
                        foreach ($exam_title as $key => $value) {
                            if (!in_array($key, $uniqueExamNames)) {

                                $uniqueExamNames[] = $key;
                                $names[] = $value;
                            }
                        }
                        foreach ($co_val as $key => $value) {
                            if (!in_array($key, $uniqueCOKeys)) {

                                $co_values[$key] = $value;
                                $uniqueCOKeys[] = $key;
                                $no = explode('CO-', $key)[1];
                                $NO[] = $no;
                                asort($NO);
                            }
                        }
                    }
                }
                $co_total = $maxCoTotal;
            } else {
                $examMarks = [];
                $names = [];
                $co_values = [];
                $NO = [];
                $co_total  = '';
            }
        } else {
            $examMarks = [];
            $names = [];
            $co_values = [];
            $NO = [];
            $co_total  = '';
        }
        if (isset($request->cat_mark)) {

            return response()->json(['examMarks' => $examMarks, 'co_values' => $co_values, 'NOs' => $NO, 'names' => $names, 'co_total' => $co_total]);
        } else {

            return view('admin.catStudentMark.index', compact('examMarks', 'names', 'co_values', 'NO', 'co_total'));
        }
    }
}
