<?php

namespace App\Http\Controllers\Admin;

ini_set('memory_limit', '256M');
ini_set('max_execution_time', 600);
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\ConsolidatedStatement;
use App\Models\CourseEnrollMaster;
use App\Models\GradeBook;
use App\Models\GradeSheet;
use App\Models\Student;
use App\Models\ToolsCourse;
use App\Models\ToolssyllabusYear;
use App\Models\Transcript;
use App\Models\Year;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class GradeStatementsController extends Controller
{
    use CsvImportTrait;

    public function gradeBookIndex(Request $request)
    {
        abort_if(Gate::denies('grade_book_upload_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = GradeBook::with('getCourse:id,short_form', 'getAy:id,name')->where(['import' => 1])->groupBy('batch', 'academic_year', 'regulation', 'course', 'exam_date')->select('batch', 'academic_year', 'regulation', 'course', 'exam_date')->get();
            $table = Datatables::of($query);
            $i = 0;
            $table->editColumn('id', function ($row) use (&$i) {
                $i++;
                return $i;
            });
            $table->addColumn('exam_month', function ($row) {
                return explode(' ', $row->exam_date)[0];
            });
            $table->addColumn('exam_year', function ($row) {
                return explode(' ', $row->exam_date)[1];
            });
            $table->editColumn('academic_year', function ($row) {
                return $row->getAy ? $row->getAy->name : '';
            });
            $table->editColumn('course', function ($row) {
                return $row->getCourse ? $row->getCourse->short_form : '';
            });

            return $table->make(true);
        }
        return view('admin.gradeStatements.book');
    }

    public function gradeSheetIndex(Request $request)
    {
        abort_if(Gate::denies('grade_sheet_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $batches = Batch::pluck('name', 'id');
        $regulations = ToolssyllabusYear::pluck('name', 'id');
        $courses = ToolsCourse::pluck('short_form', 'id');
        $ays = AcademicYear::pluck('name', 'id');
        $exam_year = Year::pluck('year', 'id');
        if ($request->ajax()) {
            $query = GradeSheet::with('getCourse:id,short_form', 'getRegulation:id,name', 'getAy:id,name', 'getBatch:id,name')->select('batch', 'academic_year', 'regulation', 'course', 'exam_date', 'updated_at')->get();
            $table = Datatables::of($query);
            $i = 0;
            $table->editColumn('id', function ($row) use (&$i) {
                $i++;
                return $i;
            });
            $table->addColumn('exam_date', function ($row) {
                return $row->exam_date;
            });

            $table->editColumn('regulation', function ($row) {
                return $row->getRegulation ? $row->getRegulation->name : '';
            });
            $table->editColumn('batch', function ($row) {
                return $row->getBatch ? $row->getBatch->name : '';
            });
            $table->editColumn('academic_year', function ($row) {
                return $row->getAy ? $row->getAy->name : '';
            });
            $table->editColumn('course', function ($row) {
                return $row->getCourse ? $row->getCourse->short_form : '';
            });
            $table->editColumn('generated_date', function ($row) {
                $gen_date = Carbon::parse($row->updated_at);
                return $gen_date->format('d-m-Y');
            });
            $table->editColumn('preview', function ($row) {
                return $row;
            });

            return $table->make(true);
        }

        return view('admin.gradeStatements.sheet', compact('batches', 'courses', 'ays', 'exam_year', 'regulations'));
    }
    public function statementIndex(Request $request)
    {
        abort_if(Gate::denies('consolidated_statement_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $courses = ToolsCourse::pluck('short_form', 'id');
        $batches = Batch::pluck('name', 'id');
        $regulations = ToolssyllabusYear::pluck('name', 'id');
        if ($request->ajax()) {
            $query = ConsolidatedStatement::with('getCourse:id,short_form', 'getRegulation:id,name', 'getBatch:id,name')->select('batch', 'regulation', 'course', 'updated_at')->get();

            $table = Datatables::of($query);
            $i = 0;
            $table->editColumn('id', function ($row) use (&$i) {
                $i++;
                return $i;
            });

            $table->editColumn('regulation', function ($row) {
                return $row->getRegulation ? $row->getRegulation->name : '';
            });
            $table->editColumn('batch', function ($row) {
                return $row->getBatch ? $row->getBatch->name : '';
            });
            $table->editColumn('course', function ($row) {
                return $row->getCourse ? $row->getCourse->short_form : '';
            });
            $table->editColumn('generated_date', function ($row) {
                $gen_date = Carbon::parse($row->updated_at);
                return $gen_date->format('d-m-Y');
            });
            $table->editColumn('preview', function ($row) {
                return $row;
            });

            return $table->make(true);
        }
        return view('admin.gradeStatements.statement', compact('courses', 'batches', 'regulations'));
    }
    public function transcriptIndex(Request $request)
    {
        abort_if(Gate::denies('transcript_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $courses = ToolsCourse::pluck('short_form', 'id');
        $batches = Batch::pluck('name', 'id');
        $regulations = ToolssyllabusYear::pluck('name', 'id');
        if ($request->ajax()) {
            $query = Transcript::with('getCourse:id,short_form', 'getRegulation:id,name', 'getBatch:id,name')->select('batch', 'regulation', 'course', 'updated_at')->get();

            $table = Datatables::of($query);
            $i = 0;
            $table->editColumn('id', function ($row) use (&$i) {
                $i++;
                return $i;
            });

            $table->editColumn('regulation', function ($row) {
                return $row->getRegulation ? $row->getRegulation->name : '';
            });
            $table->editColumn('batch', function ($row) {
                return $row->getBatch ? $row->getBatch->name : '';
            });
            $table->editColumn('course', function ($row) {
                return $row->getCourse ? $row->getCourse->short_form : '';
            });
            $table->editColumn('generated_date', function ($row) {
                $gen_date = Carbon::parse($row->updated_at);
                return $gen_date->format('d-m-Y');
            });
            $table->editColumn('preview', function ($row) {
                return $row;
            });

            return $table->make(true);
        }
        return view('admin.gradeStatements.transcript', compact('courses', 'batches', 'regulations'));
    }
    public function searchGradeSheet(Request $request)
    {
        if ($request->ajax()) {
            $query = GradeSheet::with('getCourse:id,short_form', 'getRegulation:id,name', 'getAy:id,name', 'getBatch:id,name')->where(['batch' => $request->batch, 'academic_year' => $request->ay, 'regulation' => $request->regulation, 'course' => $request->course, 'exam_date' => $request->exam_month . ' ' . $request->exam_year])->select('batch', 'academic_year', 'regulation', 'course', 'exam_date', 'updated_at')->get();
            $table = Datatables::of($query);
            $i = 0;
            $table->editColumn('id', function ($row) use (&$i) {
                $i++;
                return $i;
            });
            $table->addColumn('exam_date', function ($row) {
                return $row->exam_date;
            });

            $table->editColumn('regulation', function ($row) {
                return $row->getRegulation ? $row->getRegulation->name : '';
            });
            $table->editColumn('batch', function ($row) {
                return $row->getBatch ? $row->getBatch->name : '';
            });
            $table->editColumn('academic_year', function ($row) {
                return $row->getAy ? $row->getAy->name : '';
            });
            $table->editColumn('course', function ($row) {
                return $row->getCourse ? $row->getCourse->short_form : '';
            });
            $table->editColumn('generated_date', function ($row) {
                $gen_date = Carbon::parse($row->updated_at);
                return $gen_date->format('d-m-Y');
            });
            $table->editColumn('preview', function ($row) {
                return $row;
            });

            return $table->make(true);
        }
    }
    public function searchStatement(Request $request)
    {
        if ($request->ajax()) {
            $query = ConsolidatedStatement::with('getCourse:id,short_form', 'getRegulation:id,name', 'getBatch:id,name')->where(['batch' => $request->batch, 'regulation' => $request->regulation, 'course' => $request->course])->select('batch', 'regulation', 'course', 'updated_at')->get();
            $table = Datatables::of($query);
            $i = 0;
            $table->editColumn('id', function ($row) use (&$i) {
                $i++;
                return $i;
            });
            $table->addColumn('exam_date', function ($row) {
                return $row->exam_date;
            });
            $table->editColumn('regulation', function ($row) {
                return $row->getRegulation ? $row->getRegulation->name : '';
            });
            $table->editColumn('batch', function ($row) {
                return $row->getBatch ? $row->getBatch->name : '';
            });
            $table->editColumn('course', function ($row) {
                return $row->getCourse ? $row->getCourse->short_form : '';
            });
            $table->editColumn('generated_date', function ($row) {
                $gen_date = Carbon::parse($row->updated_at);
                return $gen_date->format('d-m-Y');
            });
            $table->editColumn('preview', function ($row) {
                return $row;
            });

            return $table->make(true);
        }
    }
    public function searchTranscript(Request $request)
    {
        if ($request->ajax()) {
            $query = Transcript::with('getCourse:id,short_form', 'getRegulation:id,name', 'getBatch:id,name')->where(['batch' => $request->batch, 'regulation' => $request->regulation, 'course' => $request->course])->select('batch', 'regulation', 'course', 'updated_at')->get();
            $table = Datatables::of($query);
            $i = 0;
            $table->editColumn('id', function ($row) use (&$i) {
                $i++;
                return $i;
            });
            $table->addColumn('exam_date', function ($row) {
                return $row->exam_date;
            });
            $table->editColumn('regulation', function ($row) {
                return $row->getRegulation ? $row->getRegulation->name : '';
            });
            $table->editColumn('batch', function ($row) {
                return $row->getBatch ? $row->getBatch->name : '';
            });
            $table->editColumn('course', function ($row) {
                return $row->getCourse ? $row->getCourse->short_form : '';
            });
            $table->editColumn('generated_date', function ($row) {
                $gen_date = Carbon::parse($row->updated_at);
                return $gen_date->format('d-m-Y');
            });
            $table->editColumn('preview', function ($row) {
                return $row;
            });

            return $table->make(true);
        }
    }

    public function checkSheetGeneration(Request $request)
    {
        if (Gate::denies('grade_sheet_generate_access')) {
            return response()->json(['status' => false, 'data' => 'Access Forbidden']);
        }
        if (isset($request->regulation) && isset($request->ay) && isset($request->batch) && isset($request->course) && isset($request->exam_month) && isset($request->exam_year)) {
            $count = GradeSheet::where(['batch' => $request->batch, 'academic_year' => $request->ay, 'course' => $request->course, 'regulation' => $request->regulation, 'exam_date' => $request->exam_month . ' ' . $request->exam_year])->count();
            if ($count > 0) {
                return response()->json(['status' => false, 'data' => '']);
            } else {
                return response()->json(['status' => true, 'data' => '']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Required Datas Not Found']);
        }
    }
    public function checkStatementGeneration(Request $request)
    {
        if (Gate::denies('consolidated_statement_generate_access')) {
            return response()->json(['status' => false, 'data' => 'Access Forbidden']);
        }
        if (isset($request->regulation) && isset($request->batch) && isset($request->course)) {
            $count = ConsolidatedStatement::where(['batch' => $request->batch, 'course' => $request->course, 'regulation' => $request->regulation])->count();
            if ($count > 0) {
                return response()->json(['status' => false, 'data' => '']);
            } else {
                return response()->json(['status' => true, 'data' => '']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Required Datas Not Found']);
        }
    }
    public function checkScriptGeneration(Request $request)
    {
        if (Gate::denies('transcript_generate_access')) {
            return response()->json(['status' => false, 'data' => 'Access Forbidden']);
        }
        if (isset($request->regulation) && isset($request->batch) && isset($request->course)) {
            $count = Transcript::where(['batch' => $request->batch, 'course' => $request->course, 'regulation' => $request->regulation])->count();
            if ($count > 0) {
                return response()->json(['status' => false, 'data' => '']);
            } else {
                return response()->json(['status' => true, 'data' => '']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Required Datas Not Found']);
        }
    }

    public function sheetGeneration(Request $request)
    {
        if (Gate::denies('grade_sheet_generate_access')) {
            return response()->json(['status' => false, 'data' => 'Access Forbidden']);
        }
        if (isset($request->regulation) && isset($request->ay) && isset($request->batch) && isset($request->course) && isset($request->exam_month) && isset($request->exam_year) && isset($request->action)) {
            $batch = Batch::where(['id' => $request->batch])->value('name');
            $course = ToolsCourse::where(['id' => $request->course])->value('name');
            $ay = AcademicYear::where(['id' => $request->ay])->value('name');
            $regulation = ToolssyllabusYear::where(['id' => $request->regulation])->value('name');
            if ($batch == '') {
                return response()->json(['status' => false, 'data' => 'Batch Not Found']);
            }
            if ($course == '') {
                return response()->json(['status' => false, 'data' => 'Course Not Found']);
            }
            if ($ay == '') {
                return response()->json(['status' => false, 'data' => 'AY Not Found']);
            }
            if ($regulation == '') {
                return response()->json(['status' => false, 'data' => 'Regulation Not Found']);
            }

            $make_enroll = $batch . '/' . $course . '/' . $ay . '/';

            $get_enrolls = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%$make_enroll%")->select('id', 'enroll_master_number')->get();
            $students = [];

            if (count($get_enrolls) <= 0) {
                return response()->json(['status' => false, 'data' => 'Classes Not Found']);
            } else {
                foreach ($get_enrolls as $enroll) {
                    $getstudents = Student::with('documents:nameofuser_id,filePath', 'personal_details:user_name_id,dob,gender')->where(['enroll_master_id' => $enroll->id])->select('user_name_id', 'name', 'register_no')->get();
                    if (count($getstudents) > 0) {
                        //     $getstudents = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $enroll->id)->select('students.name', 'students.register_no', 'students.user_name_id')->get();
                        foreach ($getstudents as $stu) {
                            array_push($students, $stu);
                        }
                    }
                }
            }

            // $query = GradeBook::with('getProfile:nameofuser_id,filePath', 'getCourse:id,short_form,name', 'getRegulation:id,name', 'getStudent:name,register_no,user_name_id', 'getSubject:id,subject_code,name,credits', 'getPersonal:user_name_id,dob,gender', 'getGrade')->where(['academic_year' => $request->ay, 'course' => $request->course, 'user_name_id' => $student->user_name_id, 'exam_date' => $request->exam_month_year])->get();
            $checkCount = GradeBook::where(['batch' => $request->batch, 'academic_year' => $request->ay, 'regulation' => $request->regulation, 'course' => $request->course, 'exam_date' => $request->exam_month . ' ' . $request->exam_year])->count();
            $data = [];
            if ($checkCount > 0) {
                if (count($students) > 0) {
                    foreach ($students as $student) {
                        $query = GradeBook::with('getSubject:id,subject_code,name,credits', 'getGrade')->where(['batch' => $request->batch, 'academic_year' => $request->ay, 'regulation' => $request->regulation, 'course' => $request->course, 'user_name_id' => $student->user_name_id, 'exam_date' => $request->exam_month . ' ' . $request->exam_year])->select('id', 'user_name_id', 'published_date', 'semester', 'subject', 'grade')->get();
                        if (count($query) > 0) {
                            $getAll = DB::table('grade_book')->leftJoin('subjects', 'grade_book.subject', '=', 'subjects.id')->rightJoin('grade_master', 'grade_master.id', '=', 'grade_book.grade')->whereNull('grade_book.deleted_at')->where('grade_master.grade_sheet_show', '1')->where('subjects.credits', '>', '0')->where('grade_master.result', 'PASS')->where('grade_book.course', $request->course)->where('grade_book.user_name_id', $student->user_name_id)->select('grade_book.id', 'grade_master.grade_point', 'subjects.credits')->get();

                            $allCredits = 0;
                            $allSum = 0;
                            $cgpa = 0;
                            $subjectDetail = [];

                            if (count($getAll) > 0) {
                                foreach ($getAll as $all) {
                                    if ((int) $all->credits > 0) {
                                        $allCredits += (int) $all->credits;
                                        $allSum += (int) $all->credits * (int) $all->grade_point;
                                    }
                                }
                            }
                            if ($allCredits > 0) {
                                $cgpa = round($allSum / $allCredits, 2);
                            }

                            $semOne = ['registered' => 0, 'earned' => 0, 'points' => 0, 'sum' => 0];
                            $semTwo = ['registered' => 0, 'earned' => 0, 'points' => 0, 'sum' => 0];
                            $semThree = ['registered' => 0, 'earned' => 0, 'points' => 0, 'sum' => 0];
                            $semFour = ['registered' => 0, 'earned' => 0, 'points' => 0, 'sum' => 0];
                            $semFive = ['registered' => 0, 'earned' => 0, 'points' => 0, 'sum' => 0];
                            $semSix = ['registered' => 0, 'earned' => 0, 'points' => 0, 'sum' => 0];
                            $semSeven = ['registered' => 0, 'earned' => 0, 'points' => 0, 'sum' => 0];
                            $semEight = ['registered' => 0, 'earned' => 0, 'points' => 0, 'sum' => 0];

                            foreach ($query as $detail) {
                                if ($detail->getGrade != null && $detail->getGrade->grade_sheet_show == 1) {
                                    $subjectDetail[] = $detail;
                                    if ($detail->getSubject != null && (int) $detail->getSubject->credits > 0) {
                                        switch ($detail->semester) {
                                            case '1':

                                                $semOne['registered'] = (int) $semOne['registered'] + (int) $detail->getSubject->credits;

                                                $semOne['earned'] = (int) $semOne['earned'] + ($detail->getGrade->result == 'PASS' ? (int) $detail->getSubject->credits : 0);

                                                $semOne['points'] = (int) $semOne['points'] + ($detail->getGrade->result == 'PASS' ? (int) $detail->getGrade->grade_point : 0);

                                                $tempGradePoint = $detail->getGrade->result == 'PASS' ? (int) $detail->getGrade->grade_point : 0;
                                                $tempCredit = $detail->getGrade->result == 'PASS' ? (int) $detail->getSubject->credits : 0;
                                                $semOne['sum'] = (int) $semOne['sum'] + ($tempGradePoint * $tempCredit);

                                                break;

                                            case '2':

                                                $semTwo['registered'] = (int) $semTwo['registered'] + (int) $detail->getSubject->credits;

                                                $semTwo['earned'] = (int) $semTwo['earned'] + ($detail->getGrade->result == 'PASS' ? (int) $detail->getSubject->credits : 0);

                                                $semTwo['points'] = (int) $semTwo['points'] + ($detail->getGrade->result == 'PASS' ? (int) $detail->getGrade->grade_point : 0);

                                                $tempGradePoint = $detail->getGrade->result == 'PASS' ? (int) $detail->getGrade->grade_point : 0;
                                                $tempCredit = $detail->getGrade->result == 'PASS' ? (int) $detail->getSubject->credits : 0;
                                                $semTwo['sum'] = (int) $semTwo['sum'] + ($tempGradePoint * $tempCredit);

                                                break;

                                            case '3':

                                                $semThree['registered'] = (int) $semThree['registered'] + (int) $detail->getSubject->credits;

                                                $semThree['earned'] = (int) $semThree['earned'] + ($detail->getGrade->result == 'PASS' ? (int) $detail->getSubject->credits : 0);

                                                $semThree['points'] = (int) $semThree['points'] + ($detail->getGrade->result == 'PASS' ? (int) $detail->getGrade->grade_point : 0);

                                                $tempGradePoint = $detail->getGrade->result == 'PASS' ? (int) $detail->getGrade->grade_point : 0;
                                                $tempCredit = $detail->getGrade->result == 'PASS' ? (int) $detail->getSubject->credits : 0;
                                                $semThree['sum'] = (int) $semThree['sum'] + ($tempGradePoint * $tempCredit);

                                                break;

                                            case '4':

                                                $semFour['registered'] = (int) $semFour['registered'] + (int) $detail->getSubject->credits;

                                                $semFour['earned'] = (int) $semFour['earned'] + ($detail->getGrade->result == 'PASS' ? (int) $detail->getSubject->credits : 0);

                                                $semFour['points'] = (int) $semFour['points'] + ($detail->getGrade->result == 'PASS' ? (int) $detail->getGrade->grade_point : 0);

                                                $tempGradePoint = $detail->getGrade->result == 'PASS' ? (int) $detail->getGrade->grade_point : 0;
                                                $tempCredit = $detail->getGrade->result == 'PASS' ? (int) $detail->getSubject->credits : 0;
                                                $semFour['sum'] = (int) $semFour['sum'] + ($tempGradePoint * $tempCredit);

                                                break;

                                            case '5':

                                                $semFive['registered'] = (int) $semFive['registered'] + (int) $detail->getSubject->credits;

                                                $semFive['earned'] = (int) $semFive['earned'] + ($detail->getGrade->result == 'PASS' ? (int) $detail->getSubject->credits : 0);

                                                $semFive['points'] = (int) $semFive['points'] + ($detail->getGrade->result == 'PASS' ? (int) $detail->getGrade->grade_point : 0);

                                                $tempGradePoint = $detail->getGrade->result == 'PASS' ? (int) $detail->getGrade->grade_point : 0;
                                                $tempCredit = $detail->getGrade->result == 'PASS' ? (int) $detail->getSubject->credits : 0;
                                                $semFive['sum'] = (int) $semFive['sum'] + ($tempGradePoint * $tempCredit);

                                                break;

                                            case '6':

                                                $semSix['registered'] = (int) $semSix['registered'] + (int) $detail->getSubject->credits;

                                                $semSix['earned'] = (int) $semSix['earned'] + ($detail->getGrade->result == 'PASS' ? (int) $detail->getSubject->credits : 0);

                                                $semSix['points'] = (int) $semSix['points'] + ($detail->getGrade->result == 'PASS' ? (int) $detail->getGrade->grade_point : 0);

                                                $tempGradePoint = $detail->getGrade->result == 'PASS' ? (int) $detail->getGrade->grade_point : 0;
                                                $tempCredit = $detail->getGrade->result == 'PASS' ? (int) $detail->getSubject->credits : 0;
                                                $semSix['sum'] = (int) $semSix['sum'] + ($tempGradePoint * $tempCredit);

                                                break;

                                            case '7':

                                                $semSeven['registered'] = (int) $semSeven['registered'] + (int) $detail->getSubject->credits;

                                                $semSeven['earned'] = (int) $semSeven['earned'] + ($detail->getGrade->result == 'PASS' ? (int) $detail->getSubject->credits : 0);

                                                $semSeven['points'] = (int) $semSeven['points'] + ($detail->getGrade->result == 'PASS' ? (int) $detail->getGrade->grade_point : 0);

                                                $tempGradePoint = $detail->getGrade->result == 'PASS' ? (int) $detail->getGrade->grade_point : 0;
                                                $tempCredit = $detail->getGrade->result == 'PASS' ? (int) $detail->getSubject->credits : 0;
                                                $semSeven['sum'] = (int) $semSeven['sum'] + ($tempGradePoint * $tempCredit);

                                                break;

                                            case '8':

                                                $semEight['registered'] = (int) $semEight['registered'] + (int) $detail->getSubject->credits;

                                                $semEight['earned'] = (int) $semEight['earned'] + ($detail->getGrade->result == 'PASS' ? (int) $detail->getSubject->credits : 0);

                                                $semEight['points'] = (int) $semEight['points'] + ($detail->getGrade->result == 'PASS' ? (int) $detail->getGrade->grade_point : 0);

                                                $tempGradePoint = $detail->getGrade->result == 'PASS' ? (int) $detail->getGrade->grade_point : 0;
                                                $tempCredit = $detail->getGrade->result == 'PASS' ? (int) $detail->getSubject->credits : 0;
                                                $semEight['sum'] = (int) $semEight['sum'] + ($tempGradePoint * $tempCredit);

                                                break;

                                            default:
                                                # code...
                                                break;
                                        }
                                    }
                                }
                            }
                            $tempData = ['gender' => $student->personal_details->gender, 'dob' => $student->personal_details->dob, 'filePath' => $student->documents->filePath, 'name' => $student->name, 'register_no' => $student->register_no, 'allCredits' => $allCredits, 'cgpa' => $cgpa, 'semOne' => $semOne, 'semTwo' => $semTwo, 'semThree' => $semThree, 'semFour' => $semFour, 'semFive' => $semFive, 'semSix' => $semSix, 'semSeven' => $semSeven, 'semEight' => $semEight, 'regulation' => $regulation, 'course' => $course, 'exam_date' => $request->exam_month . ' ' . $request->exam_year, 'published_date' => $query[0]->published_date, 'subjectDetail' => $subjectDetail];
                            array_push($data, $tempData);
                        }
                    }

                    $pdf = PDF::loadView('admin.gradeStatements.sheetPDF', ['datas' => $data]);

                    $filePath = 'gradeSheets/' . $request->regulation . $request->batch . $request->ay . $request->course . $request->exam_month . $request->exam_year . '.pdf';

                    if (Storage::exists($filePath)) {
                        Storage::delete($filePath);
                    }
                    Storage::put($filePath, $pdf->output());

                    GradeSheet::updateOrCreate([
                        'regulation' => $request->regulation,
                        'batch' => $request->batch,
                        'academic_year' => $request->ay,
                        'course' => $request->course,
                        'exam_date' => $request->exam_month . ' ' . $request->exam_year,
                    ], ['file_path' => $filePath]);

                    return response()->json(['status' => true, 'data' => 'Grade Sheet Generated Successfully']);
                } else {
                    return response()->json(['status' => false, 'data' => 'Students Not Found']);
                }
            } else {
                return response()->json(['status' => false, 'data' => 'Details Not Found In Grade Book']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Required Datas Not Found']);
        }
    }
    public function statementGeneration(Request $request)
    {
        if (Gate::denies('consolidated_statement_generate_access')) {
            return response()->json(['status' => false, 'data' => 'Access Forbidden']);
        }
        if (isset($request->batch) && isset($request->course) && isset($request->regulation)) {

            $batch = Batch::where(['id' => $request->batch])->value('name');
            $course = ToolsCourse::where(['id' => $request->course])->value('name');
            $regulation = ToolssyllabusYear::where(['id' => $request->regulation])->value('name');

            if ($batch == null) {
                return response()->json(['status' => false, 'data' => 'Batch Not Found']);
            }
            if ($course == null) {
                return response()->json(['status' => false, 'data' => 'Course Not Found']);
            }
            if ($regulation == null) {
                return response()->json(['status' => false, 'data' => 'Regulation Not Found']);
            }

            $students = Student::with('documents:nameofuser_id,filePath', 'personal_details:user_name_id,dob,gender')->where(['student_batch' => $batch, 'admitted_course' => $course])->select('user_name_id', 'name', 'register_no')->get();

            $checkCount = GradeBook::where(['batch' => $request->batch, 'regulation' => $request->regulation, 'course' => $request->course])->count();
            $data = [];
            if ($checkCount > 0) {
                if (count($students) > 0) {
                    foreach ($students as $student) {
                        $query = GradeBook::with('getSubject:id,subject_code,name,credits', 'getGrade')->where(['batch' => $request->batch, 'regulation' => $request->regulation, 'course' => $request->course, 'user_name_id' => $student->user_name_id])->select('id', 'user_name_id', 'published_date', 'semester', 'subject', 'grade', 'exam_date')->get();

                        $cgpa = 0;
                        $year = 0;
                        $month = 0;
                        if (count($query) > 0) {
                            $allCredits = 0;
                            $allSum = 0;
                            foreach ($query as $data) {
                                $explode = explode(' ', $data->exam_date);
                                $year = (int) $explode[1] > $year ? (int) $explode[1] : $year;
                                $getMonth = (int) $explode[1] >= $year ? $explode[0] : $month;

                                if (is_string($getMonth)) {
                                    $findMon = Carbon::createFromFormat('F', $getMonth);
                                    $month = $findMon->month;
                                }
                                if ($data->getGrade != null && $data->getSubject != null && (int) $data->getSubject->credits > 0 && $data->getGrade->result == 'PASS') {
                                    $allCredits += (int) $data->getSubject->credits;
                                    $allSum += (int) $data->getSubject->credits * (int) $data->getGrade->grade_point;
                                }
                            }
                            if ($allCredits > 0) {
                                $cgpa = round($allSum / $allCredits, 2);
                            }
                        }

                        $student->subjectDetail = $query;
                        $student->lastMonth = Carbon::createFromDate(null, $month, 1)->format('F');
                        $student->lastYear = $year;
                        $student->cgpa = $cgpa;
                        $student->theBatch = $batch;
                        $student->theCourse = $course;
                        $student->theRegulation = $regulation;
                    }

                    $pdf = PDF::loadView('admin.gradeStatements.statementPDF', ['students' => $students]);
                    $pdf->setPaper('A3', 'landscape');

                    $filePath = 'consolidateStatement/' . $request->regulation . $request->batch . $request->course . '.pdf';

                    if (Storage::exists($filePath)) {
                        Storage::delete($filePath);
                    }
                    Storage::put($filePath, $pdf->output());

                    // // Save the file name to the database
                    ConsolidatedStatement::updateOrCreate([
                        'batch' => $request->batch,
                        'course' => $request->course,
                        'regulation' => $request->regulation,
                    ], ['file_path' => $filePath]);

                    return response()->json(['status' => true, 'data' => 'Consolidated Statement Generated Successfully']);
                } else {
                    return response()->json(['status' => false, 'data' => 'Students Not Found']);
                }
            } else {
                return response()->json(['status' => false, 'data' => 'Details Not Found In Grade Book']);
            }

        } else {
            return response()->json(['status' => false, 'data' => 'Required Datas Not Found']);
        }
    }
    public function scriptGeneration(Request $request)
    {
        if (Gate::denies('transcript_generate_access')) {
            return response()->json(['status' => false, 'data' => 'Access Forbidden']);
        }
        if (isset($request->batch) && isset($request->course) && isset($request->regulation)) {

            $batch = Batch::where(['id' => $request->batch])->value('name');
            $course = ToolsCourse::where(['id' => $request->course])->value('name');
            $regulation = ToolssyllabusYear::where(['id' => $request->regulation])->value('name');

            if ($batch == null) {
                return response()->json(['status' => false, 'data' => 'Batch Not Found']);
            }
            if ($course == null) {
                return response()->json(['status' => false, 'data' => 'Course Not Found']);
            }
            if ($regulation == null) {
                return response()->json(['status' => false, 'data' => 'Regulation Not Found']);
            }

            $students = Student::with('documents:nameofuser_id,filePath', 'personal_details:user_name_id,dob,gender')->where(['student_batch' => $batch, 'admitted_course' => $course])->select('user_name_id', 'name', 'register_no')->get();

            // $query = GradeBook::with('getProfile:nameofuser_id,filePath', 'getCourse:id,short_form,name', 'getRegulation:id,name', 'getStudent:name,register_no,user_name_id', 'getSubject:id,subject_code,name,credits', 'getPersonal:user_name_id,dob,gender', 'getGrade')->where(['academic_year' => $request->ay, 'course' => $request->course, 'user_name_id' => $student->user_name_id, 'exam_date' => $request->exam_month_year])->get();
            $checkCount = GradeBook::where(['batch' => $request->batch, 'regulation' => $request->regulation, 'course' => $request->course])->count();
            $data = [];
            if ($checkCount > 0) {
                if (count($students) > 0) {
                    foreach ($students as $student) {
                        $query = GradeBook::with('getSubject:id,subject_code,name,credits', 'getGrade')->where(['batch' => $request->batch, 'regulation' => $request->regulation, 'course' => $request->course, 'user_name_id' => $student->user_name_id])->select('id', 'user_name_id', 'published_date', 'semester', 'subject', 'grade', 'exam_date')->get();

                        $cgpa = 0;
                        $year = 0;
                        $month = 0;
                        if (count($query) > 0) {
                            $allCredits = 0;
                            $allSum = 0;
                            foreach ($query as $data) {
                                $explode = explode(' ', $data->exam_date);
                                $year = (int) $explode[1] > $year ? (int) $explode[1] : $year;
                                $getMonth = (int) $explode[1] >= $year ? $explode[0] : $month;

                                if (is_string($getMonth)) {
                                    $findMon = Carbon::createFromFormat('F', $getMonth);
                                    $month = $findMon->month;
                                }
                                if ($data->getGrade != null && $data->getSubject != null && (int) $data->getSubject->credits > 0 && $data->getGrade->result == 'PASS') {
                                    $allCredits += (int) $data->getSubject->credits;
                                    $allSum += (int) $data->getSubject->credits * (int) $data->getGrade->grade_point;
                                }
                            }
                            if ($allCredits > 0) {
                                $cgpa = round($allSum / $allCredits, 2);
                            }
                        }

                        $student->subjectDetail = $query;
                        $student->lastMonth = Carbon::createFromDate(null, $month, 1)->format('F');
                        $student->lastYear = $year;
                        $student->cgpa = $cgpa;
                        $student->theBatch = $batch;
                        $student->theCourse = $course;
                        $student->theRegulation = $regulation;
                    }

                    $pdf = PDF::loadView('admin.gradeStatements.scriptPDF', ['students' => $students]);
                    $pdf->setPaper('A3', 'landscape');

                    $filePath = 'tranScripts/' . $request->regulation . $request->batch . $request->course . '.pdf';

                    if (Storage::exists($filePath)) {
                        Storage::delete($filePath);
                    }
                    Storage::put($filePath, $pdf->output());

                    // // Save the file name to the database
                    Transcript::updateOrCreate([
                        'batch' => $request->batch,
                        'course' => $request->course,
                        'regulation' => $request->regulation,
                    ], ['file_path' => $filePath]);

                    return response()->json(['status' => true, 'data' => 'Transcript Generated Successfully']);
                } else {
                    return response()->json(['status' => false, 'data' => 'Students Not Found']);
                }
            } else {
                return response()->json(['status' => false, 'data' => 'Details Not Found In Grade Book']);
            }

        } else {
            return response()->json(['status' => false, 'data' => 'Required Datas Not Found']);
        }
    }
    public function gradeSheetPDF(Request $request)
    {
        if (isset($request->regulation) && isset($request->ay) && isset($request->batch) && isset($request->course) && isset($request->exam_date)) {
            $filePath = GradeSheet::where(['batch' => $request->batch, 'academic_year' => $request->ay, 'course' => $request->course, 'regulation' => $request->regulation, 'exam_date' => $request->exam_date])->value('file_path');

            if ($filePath != null) {
                if (Storage::exists($filePath)) {
                    $fileContents = Storage::get($filePath);
                    return response($fileContents, 200)->header('Content-Type', 'application/pdf');
                }
            } else {
                return redirect()->route('admin.grade-sheet.index');
            }
        } else {
            return redirect()->route('admin.grade-sheet.index');
        }
        // return view('admin.gradeStatements.sheetPDF');
    }
    public function statementPDF(Request $request)
    {
        if (isset($request->regulation) && isset($request->batch) && isset($request->course)) {
            $filePath = ConsolidatedStatement::where(['batch' => $request->batch, 'course' => $request->course, 'regulation' => $request->regulation])->value('file_path');
            if ($filePath != null) {
                if (Storage::exists($filePath)) {
                    $fileContents = Storage::get($filePath);
                    return response($fileContents, 200)->header('Content-Type', 'application/pdf');
                }
            } else {
                return redirect()->route('admin.transcript.index');
            }
        } else {
            return redirect()->route('admin.transcript.index');
        }
    }
    public function tranScriptPDF(Request $request)
    {
        if (isset($request->regulation) && isset($request->batch) && isset($request->course)) {
            $filePath = Transcript::where(['batch' => $request->batch, 'course' => $request->course, 'regulation' => $request->regulation])->value('file_path');
            if ($filePath != null) {
                if (Storage::exists($filePath)) {
                    $fileContents = Storage::get($filePath);
                    return response($fileContents, 200)->header('Content-Type', 'application/pdf');
                }
            } else {
                return redirect()->route('admin.transcript.index');
            }
        } else {
            return redirect()->route('admin.transcript.index');
        }
    }
    public function testScriptGeneration(Request $request)
    {

        if (isset($request->batch) && isset($request->course) && isset($request->regulation)) {

            $batch = Batch::where(['id' => $request->batch])->value('name');
            $course = ToolsCourse::where(['id' => $request->course])->value('name');
            $regulation = ToolssyllabusYear::where(['id' => $request->regulation])->value('name');

            if ($batch == null) {
                return response()->json(['status' => false, 'data' => 'Batch Not Found']);
            }
            if ($course == null) {
                return response()->json(['status' => false, 'data' => 'Course Not Found']);
            }
            if ($regulation == null) {
                return response()->json(['status' => false, 'data' => 'Regulation Not Found']);
            }

            $students = Student::with('documents:nameofuser_id,filePath', 'personal_details:user_name_id,dob,gender')->where(['student_batch' => $batch, 'admitted_course' => $course])->select('user_name_id', 'name', 'register_no')->take(1)->get();

            // $query = GradeBook::with('getProfile:nameofuser_id,filePath', 'getCourse:id,short_form,name', 'getRegulation:id,name', 'getStudent:name,register_no,user_name_id', 'getSubject:id,subject_code,name,credits', 'getPersonal:user_name_id,dob,gender', 'getGrade')->where(['academic_year' => $request->ay, 'course' => $request->course, 'user_name_id' => $student->user_name_id, 'exam_date' => $request->exam_month_year])->get();
            $checkCount = GradeBook::where(['batch' => $request->batch, 'regulation' => $request->regulation, 'course' => $request->course])->count();
            $data = [];
            if ($checkCount > 0) {
                if (count($students) > 0) {
                    foreach ($students as $student) {
                        $query = GradeBook::with('getSubject:id,subject_code,name,credits', 'getGrade')->where(['batch' => $request->batch, 'regulation' => $request->regulation, 'course' => $request->course, 'user_name_id' => $student->user_name_id])->select('id', 'user_name_id', 'published_date', 'semester', 'subject', 'grade', 'exam_date')->get();

                        $cgpa = 0;
                        $year = 0;
                        $month = 0;
                        if (count($query) > 0) {
                            $allCredits = 0;
                            $allSum = 0;
                            foreach ($query as $data) {
                                $explode = explode(' ', $data->exam_date);
                                $explode[0] = strtoupper(Carbon::createFromFormat('F', $explode[0])->format('M'));
                                $year = (int) $explode[1] > $year ? (int) $explode[1] : $year;
                                $getMonth = (int) $explode[1] >= $year ? $explode[0] : $month;
                                $data->exam_date = implode(' ', $explode);
                                if (is_string($getMonth)) {
                                    $findMon = Carbon::createFromFormat('F', $getMonth);
                                    $month = $findMon->month;
                                }
                                if ($data->getGrade != null && $data->getSubject != null && (int) $data->getSubject->credits > 0 && $data->getGrade->result == 'PASS') {
                                    $allCredits += (int) $data->getSubject->credits;
                                    $allSum += (int) $data->getSubject->credits * (int) $data->getGrade->grade_point;
                                }
                            }
                            if ($allCredits > 0) {
                                $cgpa = round($allSum / $allCredits, 2);
                            }
                        }

                        $student->subjectDetail = $query;
                        $student->lastMonth = Carbon::createFromDate(null, $month, 1)->format('F');
                        $student->lastYear = $year;
                        $student->cgpa = $cgpa;
                        $student->theBatch = $batch;
                        $student->theCourse = $course;
                        $student->theRegulation = $regulation;
                    }

                    $pdf = PDF::loadView('admin.gradeStatements.scriptPDF', ['students' => $students]);
                    $pdf->setPaper('A3', 'landscape');
                    return $pdf->stream('Trans');
                } else {
                    return 'Students Not Found';
                }
            } else {
                return 'Details Not Found In Grade Book';
            }

        } else {
            return 'Required Datas Not Found';
        }
    }
}
