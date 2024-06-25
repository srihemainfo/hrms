<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\CourseEnrollMaster;
use App\Models\CreditLimitMaster;
use App\Models\ExamFeeMaster;
use App\Models\ExamRegistration;
use App\Models\GradeBook;
use App\Models\GradeMaster;
use App\Models\Student;
use App\Models\StudentPromotionHistory;
use App\Models\Subject;
use App\Models\SubjectRegistration;
use App\Models\ToolsCourse;
use App\Models\ToolssyllabusYear;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Http\Request;

ini_set('max_execution_time', 3600);

class ExamEnrollmentController extends Controller
{

    public function ClassIndex(Request $request)
    {
        $regulations = ToolssyllabusYear::pluck('name', 'id');
        $batches = Batch::pluck('name', 'id');
        $courses = ToolsCourse::pluck('short_form', 'id');
        $ays = AcademicYear::pluck('name', 'id');
        $years = Year::pluck('year', 'id');
        $dataArray = [];

        $regularData = ExamRegistration::where(['exam_type' => 'Regular'])->select('exam_year', 'exam_month', 'regulation', 'semester', 'course', 'batch', 'academic_year')->groupBy('exam_year', 'exam_month', 'regulation', 'semester', 'course', 'batch', 'academic_year')->get();

        if (count($regularData) > 0) {
            foreach ($regularData as $i => $data) {
                $regularSubjects = ExamRegistration::where(['exam_type' => 'Regular', 'exam_year' => $data->exam_year, 'exam_month' => $data->exam_month, 'regulation' => $data->regulation, 'semester' => $data->semester, 'course' => $data->course, 'batch' => $data->batch, 'academic_year' => $data->academic_year])->selectRaw('COUNT(user_name_id) as count, subject_id')->groupBy('subject_id')->get();

                $arrearSubjects = ExamRegistration::where(['exam_type' => 'Arrear', 'exam_year' => $data->exam_year, 'exam_month' => $data->exam_month, 'regulation' => $data->regulation, 'semester' => $data->semester, 'course' => $data->course, 'batch' => $data->batch, 'academic_year' => $data->academic_year])->selectRaw('COUNT(user_name_id) as count, subject_id')->groupBy('subject_id')->get();

                $getDate = ExamRegistration::where(['academic_year' => $data->academic_year, 'batch' => $data->batch, 'course' => $data->course, 'semester' => $data->semester, 'regulation' => $data->regulation, 'exam_month' => $data->exam_month, 'exam_year' => $data->exam_year, 'exam_type' => 'Regular'])->select('uploaded_date')->orderBy('uploaded_date', 'desc')->first();

                $thedate = Carbon::parse($getDate->uploaded_date);

                $enrolled_date = $thedate->format('d-m-Y');

                $getBatch = Batch::where(['id' => $data->batch])->select('name')->first();
                if ($getBatch != '') {
                    $batch = $getBatch->name;
                } else {
                    $batch = '';
                }
                $getCourse = ToolsCourse::where(['id' => $data->course])->select('short_form')->first();
                if ($getCourse != '') {
                    $course = $getCourse->short_form;
                } else {
                    $course = '';
                }
                $regularSubjectCount = 0;
                $regularStudentCount = 0;
                $arrearSubjectCount = 0;
                $arrearStudentCount = 0;

                if (count($regularSubjects) > 0) {
                    foreach ($regularSubjects as $subjectData) {
                        $regularSubjectCount++;
                        if ($regularStudentCount < $subjectData->count) {
                            $regularStudentCount = $subjectData->count;
                        }
                    }
                }

                if (count($arrearSubjects) > 0) {
                    foreach ($arrearSubjects as $subject) {
                        $arrearSubjectCount++;

                        if ($arrearStudentCount < $subject->count) {
                            $arrearStudentCount = $subject->count;
                        }
                    }
                }

                array_push($dataArray, ['theBatch' => $data->batch, 'theAy' => $data->academic_year, 'theCourse' => $data->course, 'theSemester' => $data->semester, 'theRegulation' => $data->regulation, 'theExamMonth' => $data->exam_month, 'theExamYear' => $data->exam_year, 'date' => $enrolled_date, 'semester' => '0' . $data->semester, 'course' => $course, 'batch' => $batch, 'regularSubjectCount' => $regularSubjectCount, 'regularStudentCount' => $regularStudentCount, 'arrearSubjectCount' => $arrearSubjectCount, 'arrearStudentCount' => $arrearStudentCount]);
            }
        }

        return view('admin.examEnrollment.classIndex', compact('regulations', 'batches', 'courses', 'ays', 'years', 'dataArray'));
    }

    public function StudentIndex(Request $request)
    {
        $regulations = ToolssyllabusYear::pluck('name', 'id');
        $batches = Batch::pluck('name', 'id');
        $courses = ToolsCourse::pluck('short_form', 'id');
        $ays = AcademicYear::pluck('name', 'id');
        $years = Year::pluck('year', 'id');
        return view('admin.examEnrollment.studentIndex', compact('regulations', 'batches', 'courses', 'ays', 'years'));
    }

    public function checkMasters(Request $request)
    {

        if (isset($request->regulation) && $request->regulation != '') {
            $check_1 = ExamFeeMaster::where('regulation_id', $request->regulation)->count();
            $check_2 = CreditLimitMaster::where(['regulation_id' => $request->regulation])->count();
            if ($check_1 > 0 && $check_2 > 0) {
                return response()->json(['status' => true, 'data' => '']);
            } else if ($check_1 <= 0 && $check_2 > 0) {
                return response()->json(['status' => false, 'data' => 'Exam Fee Not Created For This Regulation']);
            } else if ($check_1 > 0 && $check_2 <= 0) {
                return response()->json(['status' => false, 'data' => 'Credit Limit Not Created For This Regulation']);
            } else {
                return response()->json(['status' => false, 'data' => 'Exam Fee & Credit Limit Not Created For This Regulation']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Couldn\'t Find The Regulation']);
        }
    }

    public function getSubjectClasswise(Request $request)
    {

        if (isset($request->regulation) && $request->regulation != '' && isset($request->ay) && $request->ay != '' && isset($request->exam_month) && $request->exam_month != '' && isset($request->exam_year) && $request->exam_year != '' && isset($request->course) && $request->course != '' && isset($request->batch) && $request->batch != '' && isset($request->semester) && $request->semester != '') {
            $regulations = ToolssyllabusYear::where(['id' => $request->regulation])->select('name')->first();
            $batches = Batch::where(['id' => $request->batch])->select('name')->first();
            $courses = ToolsCourse::where(['id' => $request->course])->select('name')->first();
            $ays = AcademicYear::where(['id' => $request->ay])->select('name')->first();
            if ($regulations != '') {
                $regulation = $regulations->name;
            } else {
                return response()->json(['status' => false, 'data' => 'Regulation Not Found']);
            }

            if ($batches != '') {
                $batch = $batches->name;
            } else {
                return response()->json(['status' => false, 'data' => 'Batch Not Found']);
            }

            if ($courses != '') {
                $course = $courses->name;
            } else {
                return response()->json(['status' => false, 'data' => 'Course Not Found']);
            }

            if ($ays != '') {
                $ay = $ays->name;
            } else {
                return response()->json(['status' => false, 'data' => 'AY Not Found']);
            }

            $make_enroll = $batch . '/' . $course . '/' . $ay . '/' . $request->semester . '/';

            $get_enrolls = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%$make_enroll%")->select('id', 'enroll_master_number')->get();

            if (count($get_enrolls) > 0) {
                $enrolls = [];
                // $theEnrolls = '';
                foreach ($get_enrolls as $enroll) {
                    array_push($enrolls, $enroll->id);
                    // $theEnrolls .= $enroll->id . '|';
                }
                $stuArray = [];
                $getStudents = Student::whereIn('enroll_master_id', $enrolls)->select('user_name_id')->get();
                // if (count($getStudents) <= 0) {
                //     $getStudents = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->whereIn('student_promotion_history.enroll_master_id', $enrolls)->select('students.user_name_id')->get();
                // }
                foreach ($getStudents as $student) {
                    array_push($stuArray, $student->user_name_id);
                }

                $getRegulars = SubjectRegistration::with('subjects:id,name,subject_code')->whereIn('enroll_master', $enrolls)->where(['regulation' => $request->regulation, 'status' => 2])->select('subject_id')->groupBy('subject_id')->get();

                $regularData = [];
                $regularSubs = [];
                $arrearData = [];
                $arrearSubs = [];
                if (count($getRegulars) > 0) {
                    foreach ($getRegulars as $subject) {
                        $regularCount = SubjectRegistration::whereIn('enroll_master', $enrolls)->whereIn('user_name_id', $stuArray)->where(['regulation' => $request->regulation, 'subject_id' => $subject->subject_id, 'status' => 2])->select('register_no')->count();
                        $details = ['subject_id' => $subject->subject_id, 'subject_name' => $subject->subjects->name, 'subject_code' => $subject->subjects->subject_code, 'count' => $regularCount];
                        array_push($regularData, $details);
                        array_push($regularSubs, $subject->subject_id);
                    }
                    $theGrades = [];
                    $getGrade = GradeMaster::where(['regulation_id' => $request->regulation])->whereNotIn('result', ['PASS'])->select('id')->get();

                    if (count($getGrade) > 0) {
                        foreach ($getGrade as $grade) {
                            array_push($theGrades, $grade->id);
                        }
                    }

                    $getArrears = GradeBook::with('getSubject:id,name,subject_code')->where(['regulation' => $request->regulation, 'course' => $request->course])->whereIn('grade', $theGrades)->select('subject', 'semester')->groupBy('subject', 'semester')->get();
                    if (count($getArrears) > 0) {
                        foreach ($getArrears as $subject) {
                            $arrearCount = GradeBook::whereIn('user_name_id', $stuArray)->where(['regulation' => $request->regulation, 'course' => $request->course, 'subject' => $subject->subject])->whereIn('grade', $theGrades)->select('user_name_id')->count();
                            if ($arrearCount > 0) {
                                $details = ['subject_id' => $subject->subject, 'subject_sem' => $subject->semester, 'subject_name' => $subject->getSubject->name, 'subject_code' => $subject->getSubject->subject_code, 'count' => $arrearCount];
                                array_push($arrearData, $details);
                                array_push($arrearSubs, $subject->subject);
                            }
                        }
                    }
                    return response()->json(['status' => true, 'data' => ['regular' => $regularData, 'arrear' => $arrearData, 'enrolls' => $enrolls, 'regularSubs' => $regularSubs, 'arrearSubs' => $arrearSubs]]);
                } else {
                    return response()->json(['status' => false, 'data' => 'Subjects Not Found']);
                }

            } else {
                return response()->json(['status' => false, 'data' => 'Classes Not Found']);
            }

        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function enrollClasswise(Request $request)
    {
        if (isset($request->regulation) && isset($request->ay) && isset($request->exam_month) && isset($request->exam_year) && isset($request->course) && isset($request->batch) && isset($request->semester) && isset($request->enrolls) && isset($request->regularSubs)) {

            $regularSubs = explode(',', $request->regularSubs);
            if (isset($request->arrearSubs)) {
                $arrearSubs = explode(',', $request->arrearSubs);
            }
            $enrolls = explode(',', $request->enrolls);
            $gotEnroll = [];
            foreach ($enrolls as $enroll) {
                array_push($gotEnroll, $enroll);
            }

            $getCourse = ToolsCourse::where(['id' => $request->course])->select('short_form')->first();
            $getAy = AcademicYear::where(['id' => $request->ay])->select('name')->first();
            if ($getCourse == '') {
                return response()->json(['status' => false, 'data' => 'Course Not Found']);
            }
            if ($getAy == '') {
                return response()->json(['status' => false, 'data' => 'AY Not Found']);
            }

            $stuArray = [];
            $getStudents = Student::whereIn('enroll_master_id', $gotEnroll)->select('user_name_id')->get();
            // if (count($getStudents) <= 0) {
            //     $getStudents = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->whereIn('student_promotion_history.enroll_master_id', $gotEnroll)->select('students.user_name_id')->get();
            // }
            foreach ($getStudents as $student) {
                array_push($stuArray, $student->user_name_id);
            }

            $check = ExamRegistration::where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'semester' => $request->semester, 'regulation' => $request->regulation, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year])->count();
            if ($check > 0) {

                // iii
                // $delete = ExamRegistration::whereIn('user_name_id', $stuArray)->where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'semester' => $request->semester, 'regulation' => $request->regulation, 'exam_type' => 'Arrear', 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year])->update(['deleted_at' => Carbon::now()]);

                // $getExamFee = ExamFeeMaster::where(['regulation_id' => $request->regulation])->pluck('fee', 'subject_type_id');
                // $examFeeArray = $getExamFee->toArray();

                // $theGrades = [];
                // $getGrade = GradeMaster::where(['regulation_id' => $request->regulation])->whereNotIn('result', ['PASS'])->select('id')->get();

                // if (count($getGrade) > 0) {
                //     foreach ($getGrade as $grade) {
                //         array_push($theGrades, $grade->id);
                //     }
                // }

                // if (isset($request->arrearSubs)) {
                //     foreach ($arrearSubs as $subject) {
                //         $getSubject = Subject::with('subject_type')->where(['id' => $subject])->select('name', 'subject_code', 'subject_type_id', 'credits')->first();
                //         if ($getSubject == '') {
                //             return response()->json(['status' => false, 'data' => 'Subject (' . $subject . ') Not Found']);
                //         }
                //         if (array_key_exists($getSubject->subject_type->id, $examFeeArray)) {
                //             $examFee = $examFeeArray[$getSubject->subject_type->id];
                //         } else {
                //             $examFee = null;
                //         }
                //         $arrear = GradeBook::whereIn('user_name_id', $stuArray)->where(['regulation' => $request->regulation, 'course' => $request->course, 'subject' => $subject])->whereIn('grade', $theGrades)->select('user_name_id', 'semester')->get();
                //         foreach ($arrear as $student) {

                //             $store = ExamRegistration::create([
                //                 'academic_year' => $request->ay,
                //                 'batch' => $request->batch,
                //                 'course' => $request->course,
                //                 'semester' => $request->semester,
                //                 'regulation' => $request->regulation,
                //                 'user_name_id' => $student->user_name_id,
                //                 'subject_id' => $subject,
                //                 'subject_name' => $getSubject->name,
                //                 'subject_sem' => $student->semester,
                //                 'subject_type' => $getSubject->subject_type->name,
                //                 'credits' => $getSubject->credits,
                //                 'exam_type' => 'Arrear',
                //                 'exam_fee' => $examFee,
                //                 'exam_month' => $request->exam_month,
                //                 'exam_year' => $request->exam_year,
                //                 'uploaded_date' => Carbon::now()->format('Y-m-d'),
                //             ]);
                //         }
                //     }
                // }
                // iii
                return response()->json(['status' => false, 'data' => 'Already Exam Enrollment Completed For This Classes']);
            } else {
                $getExamFee = ExamFeeMaster::where(['regulation_id' => $request->regulation])->pluck('fee', 'subject_type_id');
                $examFeeArray = $getExamFee->toArray();
                foreach ($regularSubs as $subject) {

                    $getSubject = Subject::with('subject_type')->where(['id' => $subject])->select('name', 'subject_code', 'subject_type_id', 'credits')->first();
                    if ($getSubject == '') {
                        return response()->json(['status' => false, 'data' => 'Subject (' . $subject . ') Not Found']);
                    }
                    if (array_key_exists($getSubject->subject_type->id, $examFeeArray)) {
                        $examFee = $examFeeArray[$getSubject->subject_type->id];
                    } else {
                        $examFee = null;
                    }

                    $regular = SubjectRegistration::whereIn('enroll_master', $gotEnroll)->whereIn('user_name_id', $stuArray)->where(['regulation' => $request->regulation, 'subject_id' => $subject, 'status' => 2])->select('user_name_id')->get();

                    foreach ($regular as $student) {
                        $store = ExamRegistration::create([
                            'academic_year' => $request->ay,
                            'batch' => $request->batch,
                            'course' => $request->course,
                            'semester' => $request->semester,
                            'regulation' => $request->regulation,
                            'user_name_id' => $student->user_name_id,
                            'subject_id' => $subject,
                            'subject_name' => $getSubject->name,
                            'subject_sem' => $request->semester,
                            'subject_type' => $getSubject->subject_type->name,
                            'credits' => $getSubject->credits,
                            'exam_type' => 'Regular',
                            'exam_fee' => $examFee,
                            'exam_month' => $request->exam_month,
                            'exam_year' => $request->exam_year,
                            'uploaded_date' => Carbon::now()->format('Y-m-d'),
                        ]);
                    }
                }
                $theGrades = [];
                $getGrade = GradeMaster::where(['regulation_id' => $request->regulation])->whereNotIn('result', ['PASS'])->select('id')->get();

                if (count($getGrade) > 0) {
                    foreach ($getGrade as $grade) {
                        array_push($theGrades, $grade->id);
                    }
                }

                if (isset($request->arrearSubs)) {
                    foreach ($arrearSubs as $subject) {
                        $getSubject = Subject::with('subject_type')->where(['id' => $subject])->select('name', 'subject_code', 'subject_type_id', 'credits')->first();
                        if ($getSubject == '') {
                            return response()->json(['status' => false, 'data' => 'Subject (' . $subject . ') Not Found']);
                        }
                        if (array_key_exists($getSubject->subject_type->id, $examFeeArray)) {
                            $examFee = $examFeeArray[$getSubject->subject_type->id];
                        } else {
                            $examFee = null;
                        }
                        $arrear = GradeBook::whereIn('user_name_id', $stuArray)->where(['regulation' => $request->regulation, 'course' => $request->course, 'subject' => $subject])->whereIn('grade', $theGrades)->select('user_name_id', 'semester')->get();
                        foreach ($arrear as $student) {
                            $store = ExamRegistration::create([
                                'academic_year' => $request->ay,
                                'batch' => $request->batch,
                                'course' => $request->course,
                                'semester' => $request->semester,
                                'regulation' => $request->regulation,
                                'user_name_id' => $student->user_name_id,
                                'subject_id' => $subject,
                                'subject_name' => $getSubject->name,
                                'subject_sem' => $student->semester,
                                'subject_type' => $getSubject->subject_type->name,
                                'credits' => $getSubject->credits,
                                'exam_type' => 'Arrear',
                                'exam_fee' => $examFee,
                                'exam_month' => $request->exam_month,
                                'exam_year' => $request->exam_year,
                                'uploaded_date' => Carbon::now()->format('Y-m-d'),
                            ]);
                        }
                    }
                }

            }
            return response()->json(['status' => true, 'data' => 'Exam Enrollment Completed.']);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function getDetailsClasswise(Request $request)
    {

        if (isset($request->regulation) && $request->regulation != '' && isset($request->ay) && $request->ay != '' && isset($request->exam_month) && $request->exam_month != '' && isset($request->exam_year) && $request->exam_year != '' && isset($request->course) && $request->course != '' && isset($request->batch) && $request->batch != '' && isset($request->semester) && $request->semester != '') {
            $batches = Batch::where(['id' => $request->batch])->select('name')->first();
            if ($batches != '') {
                $batch = $batches->name;
            } else {
                return response()->json(['status' => false, 'data' => 'Batch Not Found']);
            }

            $getCourse = ToolsCourse::where(['id' => $request->course])->select('short_form')->first();
            if ($getCourse != '') {
                $course = $getCourse->short_form;
            } else {
                return response()->json(['status' => false, 'data' => 'Course Not Found']);
            }

            $regularSubjects = ExamRegistration::with('subject:id,name,subject_code')->where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'semester' => $request->semester, 'regulation' => $request->regulation, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'exam_type' => 'Regular'])->selectRaw('COUNT(user_name_id) as count, subject_id')->groupBy('subject_id')->get();

            if (count($regularSubjects) > 0) {
                $arrearSubjects = ExamRegistration::with('subject:id,name,subject_code')->where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'semester' => $request->semester, 'regulation' => $request->regulation, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'exam_type' => 'Arrear'])->selectRaw('COUNT(user_name_id) as count, subject_id')->groupBy('subject_id')->get();

                $getDate = ExamRegistration::where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'semester' => $request->semester, 'regulation' => $request->regulation, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'exam_type' => 'Regular'])->select('uploaded_date')->orderBy('uploaded_date', 'desc')->first();

                $thedate = Carbon::parse($getDate->uploaded_date);

                $enrolled_date = $thedate->format('d-m-Y');

                $regularSubjectCount = 0;
                $regularStudentCount = 0;
                $arrearSubjectCount = 0;
                $arrearStudentCount = 0;

                if (count($regularSubjects) > 0) {
                    foreach ($regularSubjects as $subjectData) {
                        $regularSubjectCount++;
                        if ($regularStudentCount < $subjectData->count) {
                            $regularStudentCount = $subjectData->count;
                        }
                    }
                }

                if (count($arrearSubjects) > 0) {
                    foreach ($arrearSubjects as $subject) {
                        $arrearSubjectCount++;

                        if ($arrearStudentCount < $subject->count) {
                            $arrearStudentCount = $subject->count;
                        }
                    }
                }

                return response()->json(['status' => true, 'data' => ['theRegulation' => $request->regulation, 'theAy' => $request->ay, 'theBatch' => $request->batch, 'theCourse' => $request->course, 'theSemester' => $request->semester, 'theExamMonth' => $request->exam_month, 'theExamYear' => $request->exam_year, 'batch' => $batch, 'currentSem' => '0' . $request->semester, 'course' => $course, 'enrolled_date' => $enrolled_date, 'regularSubjectCount' => $regularSubjectCount, 'regularStudentCount' => $regularStudentCount, 'arrearSubjectCount' => $arrearSubjectCount, 'arrearStudentCount' => $arrearStudentCount]]);
            } else {
                return response()->json(['status' => false, 'data' => 'Exam Enrollment Not Completed.']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function showSubjectClasswise(Request $request)
    {

        if (isset($request->regulation) && $request->regulation != '' && isset($request->ay) && $request->ay != '' && isset($request->exam_month) && $request->exam_month != '' && isset($request->exam_year) && $request->exam_year != '' && isset($request->course) && $request->course != '' && isset($request->batch) && $request->batch != '' && isset($request->semester) && $request->semester != '') {
            $regulations = ToolssyllabusYear::where(['id' => $request->regulation])->select('name')->first();
            $batches = Batch::where(['id' => $request->batch])->select('name')->first();
            $courses = ToolsCourse::where(['id' => $request->course])->select('name', 'short_form')->first();
            $ays = AcademicYear::where(['id' => $request->ay])->select('name')->first();
            if ($regulations != '') {
                $regulation = $regulations->name;
            } else {
                return back()->with('error', 'Regulation Not Found');
            }

            if ($batches != '') {
                $batch = $batches->name;
            } else {
                return back()->with('error', 'Batch Not Found');
            }

            if ($courses != '') {
                $course = $courses->name;
                $courseShort = $courses->short_form;
            } else {
                return back()->with('error', 'Course Not Found');
            }

            if ($ays != '') {
                $ay = $ays->name;
            } else {
                return back()->with('error', 'AY Not Found');
            }

            $make_enroll = $batch . '/' . $course . '/' . $ay . '/' . $request->semester . '/';

            $get_enrolls = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%$make_enroll%")->select('id', 'enroll_master_number')->get();

            if (count($get_enrolls) > 0) {
                $enrolls = [];

                foreach ($get_enrolls as $enroll) {
                    array_push($enrolls, $enroll->id);
                }
                $getRegulars = ExamRegistration::with('subject:id,name,subject_code')->where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'semester' => $request->semester, 'regulation' => $request->regulation, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'exam_type' => 'Regular'])->selectRaw('COUNT(user_name_id) as count, subject_id')->groupBy('subject_id')->get();
                // $getRegulars = SubjectRegistration::with('subjects:id,name,subject_code')->whereIn('enroll_master', $enrolls)->where(['regulation' => $request->regulation, 'status' => 2])->select('subject_id')->groupBy('subject_id')->get();
                $regularData = [];

                $arrearData = [];
                if (count($getRegulars) > 0) {
                    $getArrears = ExamRegistration::with('subject:id,name,subject_code')->where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'semester' => $request->semester, 'regulation' => $request->regulation, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'exam_type' => 'Arrear'])->selectRaw('COUNT(user_name_id) as count,semester, subject_id')->groupBy('subject_id', 'semester')->get();
                    if (count($getArrears) > 0) {
                        foreach ($getArrears as $arrSub) {
                            $details = ['subject_id' => $arrSub->subject_id, 'subject_sem' => $arrSub->semester, 'subject_name' => $arrSub->subject->name, 'subject_code' => $arrSub->subject->subject_code, 'count' => $arrSub->count];
                            array_push($arrearData, $details);
                        }
                    }
                    foreach ($getRegulars as $regSub) {
                        $details = ['subject_id' => $regSub->subject_id, 'subject_name' => $regSub->subject->name, 'subject_code' => $regSub->subject->subject_code, 'count' => $regSub->count];
                        array_push($regularData, $details);
                    }

                } else {
                    $getRegulars = SubjectRegistration::with('subjects:id,name,subject_code')->whereIn('enroll_master', $enrolls)->where(['regulation' => $request->regulation, 'status' => 2])->selectRaw('COUNT(user_name_id) as count, subject_id')->groupBy('subject_id')->get();
                    if (count($getRegulars) > 0) {
                        foreach ($getRegulars as $regSub) {
                            $details = ['subject_id' => $regSub->subject_id, 'subject_name' => $regSub->subjects->name, 'subject_code' => $regSub->subjects->subject_code, 'count' => $regSub->count];
                            array_push($regularData, $details);
                        }
                    }

                    $theGrades = [];
                    $getGrade = GradeMaster::where(['regulation_id' => $request->regulation])->whereNotIn('result', ['PASS'])->select('id')->get();

                    if (count($getGrade) > 0) {
                        foreach ($getGrade as $grade) {
                            array_push($theGrades, $grade->id);
                        }
                    }

                    $getArrears = GradeBook::with('getSubject:id,name,subject_code')->where(['regulation' => $request->regulation, 'course' => $request->course])->whereIn('grade', $theGrades)->select('subject', 'semester')->groupBy('subject', 'semester')->get();
                    if (count($getArrears) > 0) {
                        foreach ($getArrears as $subject) {
                            $arrearCount = GradeBook::where(['regulation' => $request->regulation, 'course' => $request->course, 'subject' => $subject->subject])->whereIn('grade', $theGrades)->select('user_name_id')->count();
                            $details = ['subject_id' => $subject->subject, 'subject_sem' => $subject->semester, 'subject_name' => $subject->getSubject->name, 'subject_code' => $subject->getSubject->subject_code, 'count' => $arrearCount];
                            array_push($arrearData, $details);
                        }
                    }
                }
                $exam_month = $request->exam_month;
                $exam_year = $request->exam_year;
                $semester = $request->semester;
                $regulations = ToolssyllabusYear::pluck('name', 'id');
                $batches = Batch::pluck('name', 'id');
                $courses = ToolsCourse::pluck('short_form', 'id');
                $ays = AcademicYear::pluck('name', 'id');
                $years = Year::pluck('year', 'id');
                $course = $courseShort;
                return view('admin.examEnrollment.showClassWise', compact('regulations', 'batches', 'courses', 'ays', 'years', 'regulation', 'batch', 'course', 'ay', 'exam_month', 'exam_year', 'semester', 'regularData', 'arrearData'));

                //  else {
                //     return back()->with('error', 'Subjects Not Found');
                // }

            } else {
                return back()->with('error', 'Classes Not Found');
            }

        } else {
            return back()->with('error', 'Required Datas Not Found');
        }
    }

    public function getDetailsStudentwise(Request $request)
    {

        if (isset($request->regulation) && isset($request->ay) && isset($request->batch) && isset($request->semester) && isset($request->course) && isset($request->user_name_id) && isset($request->exam_month) && isset($request->exam_year)) {
            $stu_status = '';
            if (str_contains($request->user_name_id, '|')) {
                $stu_status = 'All';
                $explode = explode('|', $request->user_name_id);
                $enrolls = [];
                if (count($explode) > 1) {
                    foreach ($explode as $enroll) {
                        array_push($enrolls, $enroll);
                    }
                }
                $students = Student::whereIn('enroll_master_id', $enrolls)->select('user_name_id', 'name', 'register_no', 'enroll_master_id')->orderBy('register_no', 'ASC')->get();
                // if (count($students) <= 0) {
                //     $students = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->whereIn('student_promotion_history.enroll_master_id', $enrolls)->select('students.user_name_id', 'students.name', 'students.register_no', 'students.enroll_master_id')->orderBy('students.register_no', 'ASC')->get();
                // }
            } else if ($request->user_name_id != '') {
                $students = Student::where(['user_name_id' => $request->user_name_id])->select('user_name_id', 'name', 'register_no', 'enroll_master_id')->orderBy('register_no', 'ASC')->get();
                $stu_status = $request->user_name_id;
            } else {
                $students = [];
            }

            if (count($students) > 0) {
                $subjectData = [];
                $getCreditLimit = CreditLimitMaster::where(['regulation_id' => $request->regulation])->select('credit_limit')->first();
                $creditLimit = null;
                if ($getCreditLimit != '') {
                    $creditLimit = $getCreditLimit->credit_limit;
                }
                $theGrades = [];
                $getGrade = GradeMaster::where(['regulation_id' => $request->regulation])->whereNotIn('result', ['PASS'])->select('id')->get();

                if (count($getGrade) > 0) {
                    foreach ($getGrade as $grade) {
                        array_push($theGrades, $grade->id);
                    }
                }
                foreach ($students as $student) {
                    $getRegulars = SubjectRegistration::with('subjects:id,credits')->where(['regulation' => $request->regulation, 'status' => 2, 'user_name_id' => $student->user_name_id, 'enroll_master' => $student->enroll_master_id])->select('subject_id')->groupBy('subject_id')->get();
                    $arrearSubs = [];
                    $credits = 0;
                    $regularSubjects = 0;
                    $arrearSubjects = 0;
                    if (count($getRegulars) > 0) {
                        $regularSubjects = count($getRegulars);
                        foreach ($getRegulars as $subject) {
                            if ($subject->subjects != null) {
                                if ($subject->subjects->credits != null) {
                                    $credits += (int) $subject->subjects->credits;
                                }
                            }
                        }

                        $getArrears = GradeBook::with('getSubject:id,credits')->where(['regulation' => $request->regulation, 'course' => $request->course, 'user_name_id' => $student->user_name_id])->whereIn('grade', $theGrades)->select('subject')->groupBy('subject')->get();
                        if (count($getArrears) > 0) {
                            $arrearSubjects = count($getArrears);
                            foreach ($getArrears as $data) {
                                if ($data->getSubject != null) {
                                    if ($data->getSubject->credits != null) {
                                        $credits += (int) $data->getSubject->credits;
                                    }
                                }
                                array_push($arrearSubs, $data->subject);
                            }
                        }
                    }

                    $getArrears = ExamRegistration::with('subject:id,name,subject_code,credits')->whereNotIn('subject_id', $arrearSubs)->where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'semester' => $request->semester, 'regulation' => $request->regulation, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'user_name_id' => $student->user_name_id, 'exam_type' => 'Arrear'])->select('subject_id')->groupBy('subject_id')->get();
                    if (count($getArrears) > 0) {
                        $arrearSubjects += count($getArrears);
                        foreach ($getArrears as $data) {
                            if ($data->subject != null) {
                                if ($data->subject->credits != null) {
                                    $credits += (int) $data->subject->credits;
                                }
                            }
                        }
                    }
                    $checkEnrollment = ExamRegistration::where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'semester' => $request->semester, 'regulation' => $request->regulation, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'user_name_id' => $student->user_name_id])->select('id')->first();
                    if ($checkEnrollment != '') {
                        $enrollment = true;
                    } else {
                        $enrollment = false;
                    }
                    array_push($subjectData, ['credit_limit' => $creditLimit, 'enrollment' => $enrollment, 'name' => $student->name, 'user_name_id' => $student->user_name_id, 'enroll' => $student->enroll_master_id, 'register_no' => $student->register_no, 'credits' => $credits, 'regularCount' => $regularSubjects, 'arrearCount' => $arrearSubjects, 'enroll' => $student->enroll_master_id]);
                }
                return response()->json(['status' => true, 'data' => $subjectData, 'stu_status' => $stu_status]);
            } else {
                return response()->json(['status' => false, 'data' => 'Register No Not Found']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Required Datas Not Found']);
        }
    }

    public function enrollStudentwise(Request $request)
    {
        if (isset($request->regulation) && $request->regulation != '' && isset($request->ay) && $request->ay != '' && isset($request->exam_month) && $request->exam_month != '' && isset($request->exam_year) && $request->exam_year != '' && isset($request->course) && $request->course != '' && isset($request->batch) && $request->batch != '' && isset($request->semester) && $request->semester != '' && isset($request->user_name_id) && $request->user_name_id != '' && isset($request->enroll) && $request->enroll != '') {

            $check = ExamRegistration::where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'semester' => $request->semester, 'regulation' => $request->regulation, 'user_name_id' => $request->user_name_id, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year])->count();
            if ($check > 0) {
                return response()->json(['status' => false, 'data' => 'Already Exam Enrollment Completed For This Student']);
            } else {
                $getExamFee = ExamFeeMaster::where(['regulation_id' => $request->regulation])->pluck('fee', 'subject_type_id');
                $examFeeArray = $getExamFee->toArray();

                $regular = SubjectRegistration::where(['regulation' => $request->regulation, 'status' => 2, 'enroll_master' => $request->enroll, 'user_name_id' => $request->user_name_id])->select('subject_id')->get();
                if (count($regular) > 0) {
                    foreach ($regular as $student) {

                        $getSubject = Subject::with('subject_type:id,name')->where(['id' => $student->subject_id])->select('name', 'subject_code', 'subject_type_id', 'credits')->first();
                        if ($getSubject == '') {
                            return response()->json(['status' => false, 'data' => 'Subject (' . $student->subject_id . ') Not Found']);
                        }
                        if (array_key_exists($getSubject->subject_type->id, $examFeeArray)) {
                            $examFee = $examFeeArray[$getSubject->subject_type->id];
                        } else {
                            $examFee = null;
                        }

                        $store = ExamRegistration::create([
                            'academic_year' => $request->ay,
                            'batch' => $request->batch,
                            'course' => $request->course,
                            'semester' => $request->semester,
                            'regulation' => $request->regulation,
                            'user_name_id' => $request->user_name_id,
                            'subject_id' => $student->subject_id,
                            'subject_name' => $getSubject->name,
                            'subject_sem' => $request->semester,
                            'subject_type' => $getSubject->subject_type->name,
                            'credits' => $getSubject->credits,
                            'exam_type' => 'Regular',
                            'exam_fee' => $examFee,
                            'exam_month' => $request->exam_month,
                            'exam_year' => $request->exam_year,
                            'uploaded_date' => Carbon::now()->format('Y-m-d'),
                        ]);
                    }

                    $theGrades = [];
                    $getGrade = GradeMaster::where(['regulation_id' => $request->regulation])->whereNotIn('result', ['PASS'])->select('id')->get();

                    if (count($getGrade) > 0) {
                        foreach ($getGrade as $grade) {
                            array_push($theGrades, $grade->id);
                        }
                    }

                    $arrear = GradeBook::where(['regulation' => $request->regulation, 'course' => $request->course, 'academic_year' => $request->ay, 'user_name_id' => $request->user_name_id])->whereIn('grade', $theGrades)->select('subject', 'semester')->get();
                    if (count($arrear) > 0) {
                        foreach ($arrear as $student) {
                            $getSubject = Subject::with('subject_type')->where(['id' => $student->subject])->select('name', 'subject_code', 'subject_type_id', 'credits')->first();
                            if ($getSubject == '') {
                                return response()->json(['status' => false, 'data' => 'Subject (' . $subject . ') Not Found']);
                            }
                            if (array_key_exists($getSubject->subject_type->id, $examFeeArray)) {
                                $examFee = $examFeeArray[$getSubject->subject_type->id];
                            } else {
                                $examFee = null;
                            }
                            $store = ExamRegistration::create([
                                'academic_year' => $request->ay,
                                'batch' => $request->batch,
                                'course' => $request->course,
                                'semester' => $request->semester,
                                'regulation' => $request->regulation,
                                'user_name_id' => $student->user_name_id,
                                'subject_id' => $student->subject,
                                'subject_name' => $getSubject->name,
                                'subject_sem' => $student->semester,
                                'subject_type' => $getSubject->subject_type->name,
                                'credits' => $getSubject->credits,
                                'exam_type' => 'Arrear',
                                'exam_fee' => $examFee,
                                'exam_month' => $request->exam_month,
                                'exam_year' => $request->exam_year,
                                'uploaded_date' => Carbon::now()->format('Y-m-d'),
                            ]);
                        }
                    }
                    return response()->json(['status' => true, 'data' => 'Exam Enrollment Completed.']);
                } else {
                    return response()->json(['status' => false, 'data' => 'Subject Registration Not Completed']);
                }
            }

        } else {
            return response()->json(['status' => false, 'data' => 'Required Datas Not Found']);
        }
    }

    public function viewStudent(Request $request)
    {

        if (isset($request->regulation) && $request->regulation != '' && isset($request->ay) && $request->ay != '' && isset($request->exam_month) && $request->exam_month != '' && isset($request->exam_year) && $request->exam_year != '' && isset($request->course) && $request->course != '' && isset($request->batch) && $request->batch != '' && isset($request->semester) && $request->semester != '' && isset($request->user_name_id) && $request->user_name_id != '') {
            $regulations = ToolssyllabusYear::where(['id' => $request->regulation])->select('name')->first();
            $batches = Batch::where(['id' => $request->batch])->select('name')->first();
            $courses = ToolsCourse::where(['id' => $request->course])->select('name', 'short_form')->first();
            $ays = AcademicYear::where(['id' => $request->ay])->select('name')->first();
            if ($regulations != '') {
                $regulation = $regulations->name;
            } else {
                return back()->with('error', 'Regulation Not Found');
            }

            if ($batches != '') {
                $batch = $batches->name;
            } else {
                return back()->with('error', 'Batch Not Found');
            }

            if ($courses != '') {
                $course = $courses->name;
                $courseShort = $courses->short_form;
            } else {
                return back()->with('error', 'Course Not Found');
            }

            if ($ays != '') {
                $ay = $ays->name;
            } else {
                return back()->with('error', 'AY Not Found');
            }

            $make_enroll = $batch . '/' . $course . '/' . $ay . '/' . $request->semester . '/';

            $get_enrolls = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%$make_enroll%")->select('id', 'enroll_master_number')->get();

            if (count($get_enrolls) > 0) {
                $enrolls = [];
                foreach ($get_enrolls as $enroll) {
                    array_push($enrolls, $enroll->id);
                }
            } else {
                return back()->with('error', 'Classes Not Found');
            }
            $regulation = $request->regulation;
            $ay = $request->ay;
            $course = $request->course;
            $semester = $request->semester;
            $batch = $request->batch;
            $exam_month = $request->exam_month;
            $exam_year = $request->exam_year;
            $user_name_id = $request->user_name_id;
            $regulations = ToolssyllabusYear::pluck('name', 'id');
            $batches = Batch::pluck('name', 'id');
            $courses = ToolsCourse::pluck('short_form', 'id');
            $ays = AcademicYear::pluck('name', 'id');
            $years = Year::pluck('year', 'id');

            $students = Student::whereIn('enroll_master_id', $enrolls)->select('user_name_id', 'name', 'register_no')->get();
            if (count($students) <= 0) {
                $students = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->whereIn('student_promotion_history.enroll_master_id', $enrolls)->select('students.user_name_id', 'students.name', 'students.register_no')->get();
            }
            $regularSubjects = ExamRegistration::with('subject:id,name,subject_code,credits')->where(['exam_type' => 'Regular', 'exam_year' => $request->exam_year, 'exam_month' => $request->exam_month, 'regulation' => $request->regulation, 'semester' => $request->semester, 'course' => $request->course, 'batch' => $request->batch, 'academic_year' => $request->ay, 'user_name_id' => $request->user_name_id])->select('subject_id', 'subject_sem')->orderBy('subject_sem')->get();
            $arrearSubjects = ExamRegistration::with('subject:id,name,subject_code,credits')->where(['exam_type' => 'Arrear', 'exam_year' => $request->exam_year, 'exam_month' => $request->exam_month, 'regulation' => $request->regulation, 'semester' => $request->semester, 'course' => $request->course, 'batch' => $request->batch, 'academic_year' => $request->ay, 'user_name_id' => $request->user_name_id])->select('subject_id', 'subject_sem')->orderBy('subject_sem')->get();

            return view('admin.examEnrollment.studentView', compact('students', 'user_name_id', 'regulation', 'batch', 'course', 'ay', 'semester', 'exam_year', 'exam_month', 'regulations', 'batches', 'courses', 'ays', 'years', 'regularSubjects', 'arrearSubjects'));

        } else {
            return back()->with('error', 'Required Datas Not Found');
        }
    }

    public function viewEachStudent(Request $request)
    {
        if (isset($request->regulation) && $request->regulation != '' && isset($request->ay) && $request->ay != '' && isset($request->exam_month) && $request->exam_month != '' && isset($request->exam_year) && $request->exam_year != '' && isset($request->course) && $request->course != '' && isset($request->batch) && $request->batch != '' && isset($request->semester) && $request->semester != '' && isset($request->user_name_id) && $request->user_name_id != '') {

            $regulation = $request->regulation;
            $ay = $request->ay;
            $course = $request->course;
            $semester = $request->semester;
            $batch = $request->batch;
            $exam_month = $request->exam_month;
            $exam_year = $request->exam_year;
            $user_name_id = $request->user_name_id;

            $regularSubjects = ExamRegistration::with('subject:id,name,subject_code,credits')->where(['exam_type' => 'Regular', 'exam_year' => $request->exam_year, 'exam_month' => $request->exam_month, 'regulation' => $request->regulation, 'semester' => $request->semester, 'course' => $request->course, 'batch' => $request->batch, 'academic_year' => $request->ay, 'user_name_id' => $request->user_name_id])->select('subject_id', 'subject_sem')->orderBy('subject_sem', 'ASC')->get();
            $arrearSubjects = ExamRegistration::with('subject:id,name,subject_code,credits')->where(['exam_type' => 'Arrear', 'exam_year' => $request->exam_year, 'exam_month' => $request->exam_month, 'regulation' => $request->regulation, 'semester' => $request->semester, 'course' => $request->course, 'batch' => $request->batch, 'academic_year' => $request->ay, 'user_name_id' => $request->user_name_id])->select('subject_id', 'subject_sem')->orderBy('subject_sem', 'ASC')->get();

            return response()->json(['status' => true, 'data' => ['regularSubjects' => $regularSubjects, 'arrearSubjects' => $arrearSubjects]]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Datas Not Found']);
        }
    }

    public function editStudent(Request $request)
    {
        if (isset($request->regulation) && $request->regulation != '' && isset($request->ay) && $request->ay != '' && isset($request->exam_month) && $request->exam_month != '' && isset($request->exam_year) && $request->exam_year != '' && isset($request->course) && $request->course != '' && isset($request->batch) && $request->batch != '' && isset($request->semester) && $request->semester != '' && isset($request->user_name_id) && $request->user_name_id != '' && isset($request->enroll) && $request->enroll != '') {

            $arrearSubs = [];
            $regularSubs = [];
            $regulations = ToolssyllabusYear::where(['id' => $request->regulation])->select('name')->first();
            $batches = Batch::where(['id' => $request->batch])->select('name')->first();
            $courses = ToolsCourse::where(['id' => $request->course])->select('name', 'short_form')->first();
            $ays = AcademicYear::where(['id' => $request->ay])->select('name')->first();
            if ($regulations != '') {
                $regulation = $regulations->name;
            } else {
                return back()->with('error', 'Regulation Not Found');
            }

            if ($batches != '') {
                $batch = $batches->name;
            } else {
                return back()->with('error', 'Batch Not Found');
            }
            $student = Student::where(['enroll_master_id' => $request->enroll, 'user_name_id' => $request->user_name_id])->select('user_name_id', 'name', 'register_no')->first();
            if ($student == '') {
                $students = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('students.user_name_id', '=', $request->user_name_id)->where('student_promotion_history.enroll_master_id', $request->enroll)->select('students.user_name_id', 'students.name', 'students.register_no')->first();
            }
            if ($student == '') {
                return back()->with('error', 'Student Not Found');
            }
            if ($courses != '') {
                $course = $courses->name;
                $courseShort = $courses->short_form;
            } else {
                return back()->with('error', 'Course Not Found');
            }

            if ($ays != '') {
                $ay = $ays->name;
            } else {
                return back()->with('error', 'AY Not Found');
            }

            $make_enroll = $batch . '/' . $course . '/' . $ay . '/' . $request->semester . '/';

            $get_enrolls = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%$make_enroll%")->select('id', 'enroll_master_number')->get();

            if (count($get_enrolls) > 0) {
                $enrolls = [];
                foreach ($get_enrolls as $enroll) {
                    array_push($enrolls, $enroll->id);
                }
            } else {
                return back()->with('error', 'Classes Not Found');
            }
            $getCreditLimit = CreditLimitMaster::where(['regulation_id' => $request->regulation])->select('credit_limit')->first();
            $creditLimit = null;
            if ($getCreditLimit != '') {
                $creditLimit = $getCreditLimit->credit_limit;
            }
            $regulation = $request->regulation;
            $ay = $request->ay;
            $course = $request->course;
            $semester = $request->semester;
            $batch = $request->batch;
            $exam_month = $request->exam_month;
            $exam_year = $request->exam_year;
            $user_name_id = $request->user_name_id;
            $regulations = ToolssyllabusYear::pluck('name', 'id');
            $batches = Batch::pluck('name', 'id');
            $courses = ToolsCourse::pluck('short_form', 'id');
            $ays = AcademicYear::pluck('name', 'id');
            $years = Year::pluck('year', 'id');

            $students = Student::whereIn('enroll_master_id', $enrolls)->select('user_name_id', 'name', 'register_no')->orderBy('register_no', 'ASC')->get();
            if (count($students) <= 0) {
                $students = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->whereIn('student_promotion_history.enroll_master_id', $enrolls)->select('students.user_name_id', 'students.name', 'students.register_no')->orderBy('students.register_no', 'ASC')->get();
            }
            $regularSubjects = ExamRegistration::with('subject:id,name,subject_code,credits')->where(['exam_type' => 'Regular', 'exam_year' => $request->exam_year, 'exam_month' => $request->exam_month, 'regulation' => $request->regulation, 'semester' => $request->semester, 'course' => $request->course, 'batch' => $request->batch, 'academic_year' => $request->ay, 'user_name_id' => $request->user_name_id])->select('subject_id', 'subject_sem')->orderBy('semester', 'ASC')->get();
            $arrearSubjects = ExamRegistration::with('subject:id,name,subject_code,credits')->where(['exam_type' => 'Arrear', 'exam_year' => $request->exam_year, 'exam_month' => $request->exam_month, 'regulation' => $request->regulation, 'semester' => $request->semester, 'course' => $request->course, 'batch' => $request->batch, 'academic_year' => $request->ay, 'user_name_id' => $request->user_name_id])->select('subject_id', 'subject_sem')->orderBy('semester', 'ASC')->get();
            $theGrades = [];
            $getGrade = GradeMaster::where(['regulation_id' => $request->regulation])->whereNotIn('result', ['PASS'])->select('id')->get();

            if (count($getGrade) > 0) {
                foreach ($getGrade as $grade) {
                    array_push($theGrades, $grade->id);
                }
            }

            if (count($regularSubjects) > 0) {
                foreach ($regularSubjects as $subjects) {
                    array_push($regularSubs, $subjects->subject_id);
                }
                $subFromSubRegis = SubjectRegistration::with('subjects:id,credits,name,subject_code')->whereNotIn('subject_id', $regularSubs)->where(['regulation' => $request->regulation, 'status' => 2, 'user_name_id' => $request->user_name_id, 'enroll_master' => $request->enroll])->select('subject_id')->groupBy('subject_id')->get();
            } else {
                $subFromSubRegis = SubjectRegistration::with('subjects:id,credits,name,subject_code')->where(['regulation' => $request->regulation, 'status' => 2, 'user_name_id' => $request->user_name_id, 'enroll_master' => $request->enroll])->select('subject_id')->groupBy('subject_id')->get();
            }
            if (count($arrearSubjects) > 0) {
                foreach ($arrearSubjects as $subjects) {
                    array_push($arrearSubs, $subjects->subject_id);
                }
                $balanceArrears = GradeBook::with('getSubject:id,name,subject_code,credits')->whereNotIn('subject', $arrearSubs)->whereIn('grade', $theGrades)->where(['regulation' => $request->regulation, 'course' => $request->course, 'academic_year' => $request->ay, 'user_name_id' => $request->user_name_id])->select('subject', 'semester')->orderBy('semester', 'ASC')->get();
            } else {
                $balanceArrears = GradeBook::with('getSubject:id,name,subject_code,credits')->whereIn('grade', $theGrades)->where(['regulation' => $request->regulation, 'course' => $request->course, 'academic_year' => $request->ay, 'user_name_id' => $request->user_name_id])->select('subject', 'semester')->orderBy('semester', 'ASC')->get();
            }
            return view('admin.examEnrollment.studentEdit', compact('creditLimit', 'students', 'user_name_id', 'regulation', 'batch', 'course', 'ay', 'semester', 'exam_year', 'exam_month', 'regulations', 'batches', 'courses', 'ays', 'years', 'regularSubjects', 'subFromSubRegis', 'arrearSubjects', 'balanceArrears'));
        } else {
            return back()->with('error', 'Required Datas Not Found');
        }
    }

    public function editEachStudent(Request $request)
    {
        if (isset($request->regulation) && $request->regulation != '' && isset($request->ay) && $request->ay != '' && isset($request->exam_month) && $request->exam_month != '' && isset($request->exam_year) && $request->exam_year != '' && isset($request->course) && $request->course != '' && isset($request->batch) && $request->batch != '' && isset($request->semester) && $request->semester != '' && isset($request->user_name_id) && $request->user_name_id != '') {

            $regulation = $request->regulation;
            $ay = $request->ay;
            $course = $request->course;
            $semester = $request->semester;
            $batch = $request->batch;
            $exam_month = $request->exam_month;
            $exam_year = $request->exam_year;
            $user_name_id = $request->user_name_id;
            $regularSubs = [];
            $arrearSubs = [];

            $student = Student::where(['user_name_id' => $request->user_name_id])->select('user_name_id', 'enroll_master_id')->first();
            if ($student == '') {
                return back()->with('error', 'Student Not Found');
            }
            $regularSubjects = ExamRegistration::with('subject:id,name,subject_code,credits')->where(['exam_type' => 'Regular', 'exam_year' => $request->exam_year, 'exam_month' => $request->exam_month, 'regulation' => $request->regulation, 'semester' => $request->semester, 'course' => $request->course, 'batch' => $request->batch, 'academic_year' => $request->ay, 'user_name_id' => $request->user_name_id])->select('subject_id', 'subject_sem')->orderBy('subject_sem', 'ASC')->get();
            $arrearSubjects = ExamRegistration::with('subject:id,name,subject_code,credits')->where(['exam_type' => 'Arrear', 'exam_year' => $request->exam_year, 'exam_month' => $request->exam_month, 'regulation' => $request->regulation, 'semester' => $request->semester, 'course' => $request->course, 'batch' => $request->batch, 'academic_year' => $request->ay, 'user_name_id' => $request->user_name_id])->select('subject_id', 'subject_sem')->orderBy('subject_sem', 'ASC')->get();

            $theGrades = [];
            $getGrade = GradeMaster::where(['regulation_id' => $request->regulation])->whereNotIn('result', ['PASS'])->select('id')->get();

            if (count($getGrade) > 0) {
                foreach ($getGrade as $grade) {
                    array_push($theGrades, $grade->id);
                }
            }

            if (count($regularSubjects) > 0) {
                foreach ($regularSubjects as $subjects) {
                    array_push($regularSubs, $subjects->subject_id);
                }
                $subFromSubRegis = SubjectRegistration::with('subjects:id,credits,name,subject_code')->whereNotIn('subject_id', $regularSubs)->where(['regulation' => $request->regulation, 'status' => 2, 'user_name_id' => $request->user_name_id, 'enroll_master' => $student->enroll_master_id])->select('subject_id')->groupBy('subject_id')->get();
            } else {
                $subFromSubRegis = SubjectRegistration::with('subjects:id,credits,name,subject_code')->where(['regulation' => $request->regulation, 'status' => 2, 'user_name_id' => $request->user_name_id, 'enroll_master' => $student->enroll_master_id])->select('subject_id')->groupBy('subject_id')->get();
            }
            if (count($arrearSubjects) > 0) {
                foreach ($arrearSubjects as $subjects) {
                    array_push($arrearSubs, $subjects->subject_id);
                }
                $balanceArrears = GradeBook::with('getSubject:id,name,subject_code,credits')->whereNotIn('subject', $arrearSubs)->whereIn('grade', $theGrades)->where(['regulation' => $request->regulation, 'course' => $request->course, 'academic_year' => $request->ay, 'user_name_id' => $request->user_name_id])->select('subject', 'semester')->orderBy('semester', 'ASC')->get();
            } else {
                $balanceArrears = GradeBook::with('getSubject:id,name,subject_code,credits')->whereIn('grade', $theGrades)->where(['regulation' => $request->regulation, 'course' => $request->course, 'academic_year' => $request->ay, 'user_name_id' => $request->user_name_id])->select('subject', 'semester')->orderBy('semester', 'ASC')->get();

            }
            return response()->json(['status' => true, 'data' => ['regularSubjects' => $regularSubjects, 'subFromSubRegis' => $subFromSubRegis, 'arrearSubjects' => $arrearSubjects, 'balanceArrears' => $balanceArrears]]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Datas Not Found']);
        }
    }

    public function storeStudent(Request $request)
    {
        if (isset($request->regulation) && $request->regulation != '' && isset($request->ay) && $request->ay != '' && isset($request->exam_month) && $request->exam_month != '' && isset($request->exam_year) && $request->exam_year != '' && isset($request->course) && $request->course != '' && isset($request->batch) && $request->batch != '' && isset($request->semester) && $request->semester != '' && isset($request->user_name_id) && $request->user_name_id != '' && (isset($request->regularSubjects) || isset($request->arrearSubjects))) {

            if (isset($request->removeSubjects)) {
                $subjects = $request->removeSubjects;
                foreach ($subjects as $subject) {
                    $delete = ExamRegistration::where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'semester' => $request->semester, 'regulation' => $request->regulation, 'user_name_id' => $request->user_name_id, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'subject_id' => $subject])->update([
                        'deleted_at' => Carbon::now(),
                    ]);
                }
            }
            if (isset($request->arrearSubjects)) {
                $arrearSubjects = $request->arrearSubjects;
                if (count($arrearSubjects) > 0) {
                    foreach ($arrearSubjects as $subject) {
                        $check = ExamRegistration::where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'semester' => $request->semester, 'regulation' => $request->regulation, 'user_name_id' => $request->user_name_id, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'subject_id' => $subject])->count();
                        if ($check <= 0) {
                            $getSubject = Subject::with('subject_type')->where(['id' => $subject])->select('id', 'name', 'subject_code', 'credits', 'subject_type_id')->first();

                            if ($getSubject == null) {
                                return response()->json(['status' => false, 'data' => 'Subject(' . $subject . ') Not Found']);
                            } else {
                                $getSem = GradeBook::where(['regulation' => $request->regulation, 'course' => $request->course, 'academic_year' => $request->ay, 'user_name_id' => $request->user_name_id, 'subject' => $subject])->select('semester')->first();
                                if ($getSem == '') {
                                    return response()->json(['status' => false, 'data' => 'Subject(' . $subject . ') Not Found In Grade Book']);
                                }

                                $getExamFee = ExamFeeMaster::where(['regulation_id' => $request->regulation])->pluck('fee', 'subject_type_id');
                                $examFeeArray = $getExamFee->toArray();
                                if (array_key_exists($getSubject->subject_type->id, $examFeeArray)) {
                                    $examFee = $examFeeArray[$getSubject->subject_type->id];
                                } else {
                                    $examFee = null;
                                }
                                $store = ExamRegistration::create([
                                    'academic_year' => $request->ay,
                                    'batch' => $request->batch,
                                    'course' => $request->course,
                                    'semester' => $request->semester,
                                    'regulation' => $request->regulation,
                                    'user_name_id' => $request->user_name_id,
                                    'subject_id' => $subject,
                                    'subject_name' => $getSubject->name,
                                    'subject_sem' => $getSem->semester,
                                    'subject_type' => $getSubject->subject_type ? $getSubject->subject_type->name : null,
                                    'credits' => $getSubject->credits,
                                    'exam_type' => 'Arrear',
                                    'exam_fee' => $examFee,
                                    'exam_month' => $request->exam_month,
                                    'exam_year' => $request->exam_year,
                                    'uploaded_date' => Carbon::now()->format('Y-m-d'),
                                ]);

                            }
                        }
                    }
                }
            }
            if (isset($request->regularSubjects)) {
                $regularSubjects = $request->regularSubjects;
                if (count($regularSubjects) > 0) {
                    foreach ($regularSubjects as $subject) {
                        $check = ExamRegistration::where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'semester' => $request->semester, 'regulation' => $request->regulation, 'user_name_id' => $request->user_name_id, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'subject_id' => $subject])->count();
                        if ($check <= 0) {
                            $getSubject = Subject::with('subject_type')->where(['id' => $subject])->select('id', 'name', 'subject_code', 'credits', 'subject_type_id')->first();
                            if ($getSubject == '') {
                                return response()->json(['status' => false, 'data' => 'Subject(' . $subject . ') Not Found']);
                            } else {

                                $getExamFee = ExamFeeMaster::where(['regulation_id' => $request->regulation])->pluck('fee', 'subject_type_id');
                                $examFeeArray = $getExamFee->toArray();
                                if (array_key_exists($getSubject->subject_type->id, $examFeeArray)) {
                                    $examFee = $examFeeArray[$getSubject->subject_type->id];
                                } else {
                                    $examFee = null;
                                }
                                $store = ExamRegistration::create([
                                    'academic_year' => $request->ay,
                                    'batch' => $request->batch,
                                    'course' => $request->course,
                                    'semester' => $request->semester,
                                    'regulation' => $request->regulation,
                                    'user_name_id' => $request->user_name_id,
                                    'subject_id' => $subject,
                                    'subject_name' => $getSubject->name,
                                    'subject_sem' => $request->semester,
                                    'subject_type' => $getSubject->subject_type ? $getSubject->subject_type->name : null,
                                    'credits' => $getSubject->credits,
                                    'exam_type' => 'Regular',
                                    'exam_fee' => $examFee,
                                    'exam_month' => $request->exam_month,
                                    'exam_year' => $request->exam_year,
                                    'uploaded_date' => Carbon::now()->format('Y-m-d'),
                                ]);

                            }
                        }
                    }
                }
            }
            return response()->json(['status' => true, 'data' => 'Exam Enrollment Updated.']);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Datas Not Found']);
        }
    }

    public function downloadStudent(Request $request)
    {
        if (isset($request->regulation) && $request->regulation != '' && isset($request->ay) && $request->ay != '' && isset($request->exam_month) && $request->exam_month != '' && isset($request->exam_year) && $request->exam_year != '' && isset($request->course) && $request->course != '' && isset($request->batch) && $request->batch != '' && isset($request->semester) && $request->semester != '' && isset($request->user_name_id) && $request->user_name_id != '') {
        } else {
            return back()->with('error', 'Required Datas Not Found');
        }
        return view('admin.examEnrollment.studentDownload');
    }

    public function run()
    {
        // $getData = GradeBook::where(['result_type' => 'Arrear'])->select('user_name_id', 'regulation', 'academic_year', 'course', 'semester', 'subject')->get();
        // $sdf = [];
        // foreach ($getData as $data) {
        //     $update = ExamRegistration::where(['academic_year' => $data->academic_year, 'course' => $data->course, 'subject_sem' => $data->semester, 'regulation' => $data->regulation, 'user_name_id' => $data->user_name_id, 'subject_id' => $data->subject])->update([
        //         'deleted_at' => Carbon::now()
        //     ]);

        // }
        return back();
    }

    public function getSplSubjects(Request $request)
    {
        if (isset($request->sem)) {
            $getSubjects = Subject::where(['semester_id' => $request->sem])->select('id', 'name', 'subject_code', 'credits')->get();
            return response()->json(['status' => true, 'data' => $getSubjects]);
        } else {
            return response()->json(['status' => false, 'data' => 'Semester Not Found']);
        }
    }
}
