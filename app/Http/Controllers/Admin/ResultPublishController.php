<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\ExamResultPublish;
use App\Models\GradeBook;
use App\Models\ResultMaster;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ToolsCourse;
use App\Models\ToolssyllabusYear;
use App\Models\Year;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class ResultPublishController extends Controller
{
    public function index(Request $request)
    {
        $courses = ToolsCourse::pluck('short_form', 'id');
        $ays = AcademicYear::pluck('name', 'id');
        $batches = Batch::pluck('name', 'id');
        $years = Year::pluck('year', 'id');
        $regulations = ToolssyllabusYear::pluck('name', 'id');
        $exam_month = ExamResultPublish::select('exam_month')->groupBy('exam_month')->get();
        $exam_year = ExamResultPublish::select('exam_year')->groupBy('exam_year')->get();
        // $publish_date = ExamResultPublish::select('publish_date')->groupBy('publish_date')->get();
        $result_type = ResultMaster::pluck('result_type', 'id');

        if ($request->ajax()) {

            $query = ExamResultPublish::with(['ay', 'batches', 'courses', 'regulations'])->groupBy('academic_year', 'batch', 'course', 'semester', 'regulation', 'result_type', 'exam_month', 'exam_year', 'publish')->select('academic_year', 'batch', 'course', 'semester', 'regulation', 'result_type', 'exam_month', 'exam_year', 'publish')->get();

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');

            $table->editColumn('academic_year', function ($row) {
                return $row->ay ? $row->ay->name : '';
            });
            $table->editColumn('actions', function ($row) {
                $ay = $row->ay ? $row->ay->id : '';
                $batch = $row->batches ? $row->batches->id : '';
                $course = $row->courses ? $row->courses->id : '';
                $regulation = $row->regulations ? $row->regulations->id : '';
                $sem = $row->semester ? $row->semester : '';
                $result_type = $row->result_type ? $row->result_type : '';
                if ($row->publish == 0) {
                    $publish = true;
                } else {
                    $publish = false;
                }
                $data = $ay . '/' . $batch . '/' . $course . '/' . $regulation . '/' . $sem . '/' . $result_type . '/' . $row->publish . '/' . $row->exam_month . '/' . $row->exam_year;
                $dataArray = ['urlData' => $data, 'publish' => $publish];
                return $dataArray;
            });

            $table->editColumn('batch', function ($row) {
                return $row->batches ? $row->batches->name : '';
            });
            $table->editColumn('regulation', function ($row) {
                return $row->regulations ? $row->regulations->name : '';
            });
            $table->editColumn('name', function ($row) {
                $month = $row->exam_month ? $row->exam_month : '';
                $year = $row->exam_year ? $row->exam_year : '';
                $result_type = $row->result_type ? $row->result_type : '';
                $course = $row->courses ? $row->courses->short_form : '';
                $semester = $row->semester ? '0' . $row->semester : '';
                $name = $month . '-' . $year . '-' . $result_type . '-' . $course . '-' . $semester;
                return $name;
            });
            $table->editColumn('publish_date', function ($row) {
                // $publishDate = $row->publish_date;
                // if ($publishDate) {
                //     $publishDate = date('d-m-Y', strtotime($publishDate));
                // }
                // return $row->publish_date ? $publishDate : '';

                $getUploadDate = ExamResultPublish::where(['academic_year' => $row->academic_year, 'batch' => $row->batch, 'course' => $row->course, 'semester' => $row->semester, 'regulation' => $row->regulation, 'result_type' => $row->result_type, 'exam_month' => $row->exam_month, 'exam_year' => $row->exam_year, 'publish' => $row->publish])->select('publish_date')->orderBy('publish_date', 'ASC')->first();
                if ($getUploadDate != '') {
                    $publishDate = $getUploadDate->publish_date;
                    $theDate = date('d-m-Y', strtotime($publishDate));
                } else {
                    $theDate = '';
                }

                return $theDate;
            });
            $table->editColumn('status', function ($row) {
                if ($row->publish == 0) {
                    $publish = 'Not Published';
                } else {
                    $publish = 'Published';
                }
                return $publish;
            });
            $table->editColumn('uploaded_date', function ($row) {

                $getUploadDate = ExamResultPublish::where(['academic_year' => $row->academic_year, 'batch' => $row->batch, 'course' => $row->course, 'semester' => $row->semester, 'regulation' => $row->regulation, 'result_type' => $row->result_type, 'exam_month' => $row->exam_month, 'exam_year' => $row->exam_year, 'publish' => $row->publish])->select('uploaded_date')->orderBy('uploaded_date', 'DESC')->first();
                if ($getUploadDate != '') {
                    $publishDate = $getUploadDate->uploaded_date;
                    $theDate = date('d-m-Y', strtotime($publishDate));
                } else {
                    $theDate = '';
                }

                return $theDate;
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        return view('admin.examResultPublish.index', compact('regulations', 'courses', 'ays', 'batches', 'years', 'exam_month', 'exam_year', 'result_type'));
    }

    public function search(Request $request)
    {
        if (isset($request->regulation) && isset($request->ay) && isset($request->semester) && isset($request->course) && isset($request->result_type) && isset($request->exam_month) && isset($request->exam_year)) {

            // $get = ExamResultPublish::with('ay:id,name', 'batches:id,name', 'courses:id,short_form', 'regulations:id,name', 'student:name,register_no,user_name_id', 'subject:id,subject_code,name,credits,subject_type_id', 'subject.subject_type:id,name')->where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'regulation' => $request->regulation, 'semester' => $request->sem, 'result_type' => $request->result_type])->get();

            $query = ExamResultPublish::with(['ay', 'batches', 'courses', 'regulations'])->where(['regulation' => $request->regulation, 'academic_year' => $request->ay, 'course' => $request->course, 'semester' => $request->semester, 'result_type' => $request->result_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year])->groupBy('academic_year', 'batch', 'course', 'semester', 'regulation', 'result_type', 'exam_month', 'exam_year', 'publish_date', 'uploaded_date', 'exam_name')->select('academic_year', 'batch', 'course', 'semester', 'regulation', 'result_type', 'exam_month', 'exam_year', 'publish_date', 'uploaded_date', 'exam_name')->get();

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');

            $table->editColumn('academic_year', function ($row) {
                return $row->ay ? $row->ay->name : '';
            });
            $table->editColumn('actions', function ($row) {
                $ay = $row->ay ? $row->ay->id : '';
                $batch = $row->batches ? $row->batches->id : '';
                $course = $row->courses ? $row->courses->id : '';
                $regulation = $row->regulations ? $row->regulations->id : '';
                $sem = $row->semester ? $row->semester : '';
                $result_type = $row->result_type ? $row->result_type : '';
                $data = $ay . '/' . $batch . '/' . $course . '/' . $regulation . '/' . $sem . '/' . $result_type . '/' . $row->exam_month . '/' . $row->exam_year;

                if ($row->publish == 0) {
                    $publish = true;
                } else {
                    $publish = false;
                }
                $dataArray = ['urlData' => $data, 'publish' => $publish];
                return $dataArray;
            });

            $table->editColumn('batch', function ($row) {
                return $row->batches ? $row->batches->name : '';
            });

            $table->editColumn('regulation', function ($row) {
                return $row->regulations ? $row->regulations->name : '';
            });

            $table->editColumn('name', function ($row) {
                $month = $row->exam_month ? $row->exam_month : '';
                $year = $row->exam_year ? $row->exam_year : '';
                $result_type = $row->result_type ? $row->result_type : '';
                $course = $row->courses ? $row->courses->short_form : '';
                $semester = $row->semester ? '0' . $row->semester : '';
                $name = $month . '-' . $year . '-' . $result_type . '-' . $course . '-' . $semester;
                return $name;
            });

            $table->editColumn('publish_date', function ($row) {
                $publishDate = $row->publish_date;
                if ($publishDate) {
                    $publishDate = date('d-m-Y', strtotime($publishDate));

                }
                return $row->publish_date ? $publishDate : '';
            });
            $table->editColumn('uploaded_date', function ($row) {
                $publishDate = $row->uploaded_date;
                if ($publishDate) {
                    $publishDate = date('d-m-Y', strtotime($publishDate));

                }
                return $row->uploaded_date ? $publishDate : '';
            });
            $table->editColumn('status', function ($row) {
                if ($row->publish == 0) {
                    $publish = 'Not Published';
                } else {
                    $publish = 'Published';
                }
                return $publish;
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
    }

    public function downloadExcel(Request $request)
    {
        $get = ExamResultPublish::where([
            'academic_year' => $request->ay,
            'batch' => $request->batch,
            'course' => $request->course,
            'regulation' => $request->regulation,
            'semester' => $request->sem,
            'result_type' => $request->result_type,
            'publish' => $request->publish,
            'exam_month' => $request->exam_month,
            'exam_year' => $request->exam_year,
            'deleted_at' => null,
        ])->select('register_no', DB::raw('MAX(id) as max_id'), DB::raw('MAX(created_at) as latest_created_at'))->groupBy('register_no')->get();

        $id = $get->pluck('max_id');

        $getData = ExamResultPublish::with('batches', 'courses', 'ay', 'regulations', 'grades_1', 'grades_2', 'grades_3', 'grades_4', 'grades_5', 'grades_6', 'grades_7', 'grades_8', 'grades_9', 'grades_10')->whereIn('id', $id)->orderBy('register_no', 'ASC')->get();
        $theSubjects = [];
        $subjects = ExamResultPublish::where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'regulation' => $request->regulation, 'semester' => $request->sem, 'result_type' => $request->result_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year])->select('subject_1', 'subject_2', 'subject_3', 'subject_4', 'subject_5', 'subject_6', 'subject_7', 'subject_8', 'subject_9', 'subject_10')->first();
        if ($subjects != '') {
            $getSub1 = Subject::where(['id' => $subjects->subject_1])->select('subject_code', 'id')->first();
            $getSub2 = Subject::where(['id' => $subjects->subject_2])->select('subject_code', 'id')->first();
            $getSub3 = Subject::where(['id' => $subjects->subject_3])->select('subject_code', 'id')->first();
            $getSub4 = Subject::where(['id' => $subjects->subject_4])->select('subject_code', 'id')->first();
            $getSub5 = Subject::where(['id' => $subjects->subject_5])->select('subject_code', 'id')->first();
            $getSub6 = Subject::where(['id' => $subjects->subject_6])->select('subject_code', 'id')->first();
            $getSub7 = Subject::where(['id' => $subjects->subject_7])->select('subject_code', 'id')->first();
            $getSub8 = Subject::where(['id' => $subjects->subject_8])->select('subject_code', 'id')->first();
            $getSub9 = Subject::where(['id' => $subjects->subject_9])->select('subject_code', 'id')->first();
            $getSub10 = Subject::where(['id' => $subjects->subject_10])->select('subject_code', 'id')->first();
            if ($getSub1 != null) {
                $theSubjects[$getSub1->id] = $getSub1->subject_code;
            }
            if ($getSub2 != null) {
                $theSubjects[$getSub2->id] = $getSub2->subject_code;
            }
            if ($getSub3 != null) {
                $theSubjects[$getSub3->id] = $getSub3->subject_code;
            }
            if ($getSub4 != null) {
                $theSubjects[$getSub4->id] = $getSub4->subject_code;
            }
            if ($getSub5 != null) {
                $theSubjects[$getSub5->id] = $getSub5->subject_code;
            }
            if ($getSub6 != null) {
                $theSubjects[$getSub6->id] = $getSub6->subject_code;
            }
            if ($getSub7 != null) {
                $theSubjects[$getSub7->id] = $getSub7->subject_code;
            }
            if ($getSub8 != null) {
                $theSubjects[$getSub8->id] = $getSub8->subject_code;
            }
            if ($getSub9 != null) {
                $theSubjects[$getSub9->id] = $getSub9->subject_code;
            }
            if ($getSub10 != null) {
                $theSubjects[$getSub10->id] = $getSub10->subject_code;
            }
        }
        $data = [];
        if (count($getData) > 0) {
            foreach ($getData as $i => $detail) {
                $tempData = [];
                if ($i == 0) {
                    $tempData['batch'] = $detail->batches->name;
                    $tempData['academic_year'] = $detail->ay->name;
                    $tempData['course'] = $detail->courses->short_form;
                    $tempData['semester'] = $detail->semester;
                    $tempData['regulation'] = $detail->regulations->name;
                    $tempData['exam_month'] = $detail->exam_month;
                    $tempData['exam_year'] = $detail->exam_year;
                    $tempData['result_type'] = $detail->result_type;
                    $tempData['publish_date'] = $detail->publish_date;
                }
                $tempData['register_no'] = $detail->register_no;
                $tempData['subjects'] = [];

                if ($detail->subject_1 != null && $detail->grade_1 != null) {
                    $tempData['subjects'][$detail->subject_1] = $detail->grades_1->grade_letter;
                }
                if ($detail->subject_2 != null && $detail->grade_2 != null) {
                    $tempData['subjects'][$detail->subject_2] = $detail->grades_2->grade_letter;
                }
                if ($detail->subject_3 != null && $detail->grade_3 != null) {
                    $tempData['subjects'][$detail->subject_3] = $detail->grades_3->grade_letter;
                }
                if ($detail->subject_4 != null && $detail->grade_4 != null) {
                    $tempData['subjects'][$detail->subject_4] = $detail->grades_4->grade_letter;
                }
                if ($detail->subject_5 != null && $detail->grade_5 != null) {
                    $tempData['subjects'][$detail->subject_5] = $detail->grades_5->grade_letter;
                }
                if ($detail->subject_6 != null && $detail->grade_6 != null) {
                    $tempData['subjects'][$detail->subject_6] = $detail->grades_6->grade_letter;
                }
                if ($detail->subject_7 != null && $detail->grade_7 != null) {
                    $tempData['subjects'][$detail->subject_7] = $detail->grades_7->grade_letter;
                }
                if ($detail->subject_8 != null && $detail->grade_8 != null) {
                    $tempData['subjects'][$detail->subject_8] = $detail->grades_8->grade_letter;
                }
                if ($detail->subject_9 != null && $detail->grade_9 != null) {
                    $tempData['subjects'][$detail->subject_9] = $detail->grades_9->grade_letter;
                }
                if ($detail->subject_10 != null && $detail->grade_10 != null) {
                    $tempData['subjects'][$detail->subject_10] = $detail->grades_10->grade_letter;
                }
                array_push($data, $tempData);
            }
        }

        return view('admin.examResultPublish.excel', compact('data', 'theSubjects'));
    }

    public function downloadPDF(Request $request)
    {

        $get = ExamResultPublish::where([
            'academic_year' => $request->ay,
            'batch' => $request->batch,
            'course' => $request->course,
            'regulation' => $request->regulation,
            'semester' => $request->sem,
            'result_type' => $request->result_type,
            'publish' => $request->publish,
            'exam_month' => $request->exam_month,
            'exam_year' => $request->exam_year,
            'deleted_at' => null,
        ])
            ->select(
                'register_no',
                DB::raw('MAX(id) as max_id'),
                DB::raw('MAX(created_at) as latest_created_at')
            )
            ->groupBy('register_no')
            ->get();

        $id = $get->pluck('max_id');

        $getData = ExamResultPublish::with('student', 'batches', 'courses', 'ay', 'regulations', 'grades_1', 'grades_2', 'grades_3', 'grades_4', 'grades_5', 'grades_6', 'grades_7', 'grades_8', 'grades_9', 'grades_10')->whereIn('id', $id)->orderBy('register_no', 'ASC')->get();
        $subjects = ExamResultPublish::where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'regulation' => $request->regulation, 'semester' => $request->sem, 'result_type' => $request->result_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year])->select('subject_1', 'subject_2', 'subject_3', 'subject_4', 'subject_5', 'subject_6', 'subject_7', 'subject_8', 'subject_9', 'subject_10')->first();
        $theSubjects = [];
        $subjects = ExamResultPublish::where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'regulation' => $request->regulation, 'semester' => $request->sem, 'result_type' => $request->result_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year])->select('subject_1', 'subject_2', 'subject_3', 'subject_4', 'subject_5', 'subject_6', 'subject_7', 'subject_8', 'subject_9', 'subject_10')->first();
        if ($subjects != '') {
            $getSub1 = Subject::where(['id' => $subjects->subject_1])->select('subject_code', 'id')->first();
            $getSub2 = Subject::where(['id' => $subjects->subject_2])->select('subject_code', 'id')->first();
            $getSub3 = Subject::where(['id' => $subjects->subject_3])->select('subject_code', 'id')->first();
            $getSub4 = Subject::where(['id' => $subjects->subject_4])->select('subject_code', 'id')->first();
            $getSub5 = Subject::where(['id' => $subjects->subject_5])->select('subject_code', 'id')->first();
            $getSub6 = Subject::where(['id' => $subjects->subject_6])->select('subject_code', 'id')->first();
            $getSub7 = Subject::where(['id' => $subjects->subject_7])->select('subject_code', 'id')->first();
            $getSub8 = Subject::where(['id' => $subjects->subject_8])->select('subject_code', 'id')->first();
            $getSub9 = Subject::where(['id' => $subjects->subject_9])->select('subject_code', 'id')->first();
            $getSub10 = Subject::where(['id' => $subjects->subject_10])->select('subject_code', 'id')->first();
            if ($getSub1 != null) {
                $theSubjects[$getSub1->id] = $getSub1->subject_code;
            }
            if ($getSub2 != null) {
                $theSubjects[$getSub2->id] = $getSub2->subject_code;
            }
            if ($getSub3 != null) {
                $theSubjects[$getSub3->id] = $getSub3->subject_code;
            }
            if ($getSub4 != null) {
                $theSubjects[$getSub4->id] = $getSub4->subject_code;
            }
            if ($getSub5 != null) {
                $theSubjects[$getSub5->id] = $getSub5->subject_code;
            }
            if ($getSub6 != null) {
                $theSubjects[$getSub6->id] = $getSub6->subject_code;
            }
            if ($getSub7 != null) {
                $theSubjects[$getSub7->id] = $getSub7->subject_code;
            }
            if ($getSub8 != null) {
                $theSubjects[$getSub8->id] = $getSub8->subject_code;
            }
            if ($getSub9 != null) {
                $theSubjects[$getSub9->id] = $getSub9->subject_code;
            }
            if ($getSub10 != null) {
                $theSubjects[$getSub10->id] = $getSub10->subject_code;
            }
        }
        $data = [];
        if (count($getData) > 0) {
            foreach ($getData as $i => $detail) {
                $tempData = [];
                if ($i == 0) {
                    $tempData['batch'] = $detail->batches->name;
                    $tempData['academic_year'] = $detail->ay->name;
                    $tempData['course'] = $detail->courses->short_form;
                    $tempData['semester'] = $detail->semester;
                    $tempData['regulation'] = $detail->regulations->name;
                    $tempData['exam_month'] = $detail->exam_month;
                    $tempData['exam_year'] = $detail->exam_year;
                    $tempData['result_type'] = $detail->result_type;
                    $tempData['publish_date'] = $detail->publish_date;
                }
                $tempData['register_no'] = $detail->register_no;
                if ($detail->student == null) {
                    $tempData['student'] = DB::table('students')->where(['user_name_id' => $detail->user_name_id])->value('name');
                } else {
                    $tempData['student'] = $detail->student->name;
                }
                $tempData['subjects'] = [];

                if ($detail->subject_1 != null && $detail->grade_1 != null) {
                    $tempData['subjects'][$detail->subject_1] = $detail->grades_1->grade_letter;
                }
                if ($detail->subject_2 != null && $detail->grade_2 != null) {
                    $tempData['subjects'][$detail->subject_2] = $detail->grades_2->grade_letter;
                }
                if ($detail->subject_3 != null && $detail->grade_3 != null) {
                    $tempData['subjects'][$detail->subject_3] = $detail->grades_3->grade_letter;
                }
                if ($detail->subject_4 != null && $detail->grade_4 != null) {
                    $tempData['subjects'][$detail->subject_4] = $detail->grades_4->grade_letter;
                }
                if ($detail->subject_5 != null && $detail->grade_5 != null) {
                    $tempData['subjects'][$detail->subject_5] = $detail->grades_5->grade_letter;
                }
                if ($detail->subject_6 != null && $detail->grade_6 != null) {
                    $tempData['subjects'][$detail->subject_6] = $detail->grades_6->grade_letter;
                }
                if ($detail->subject_7 != null && $detail->grade_7 != null) {
                    $tempData['subjects'][$detail->subject_7] = $detail->grades_7->grade_letter;
                }
                if ($detail->subject_8 != null && $detail->grade_8 != null) {
                    $tempData['subjects'][$detail->subject_8] = $detail->grades_8->grade_letter;
                }
                if ($detail->subject_9 != null && $detail->grade_9 != null) {
                    $tempData['subjects'][$detail->subject_9] = $detail->grades_9->grade_letter;
                }
                if ($detail->subject_10 != null && $detail->grade_10 != null) {
                    $tempData['subjects'][$detail->subject_10] = $detail->grades_10->grade_letter;
                }
                array_push($data, $tempData);
            }
        }
        $pdf = PDF::loadView('admin.examResultPublish.publish_pdf', ['data' => $data, 'theSubjects' => $theSubjects]);

        return $pdf->stream('Result_publish.pdf');

    }

    public function deleteResult(Request $request)
    {
        $get = ExamResultPublish::where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'regulation' => $request->regulation, 'semester' => $request->sem, 'result_type' => $request->result_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'publish' => $request->publish])->update([
            'deleted_at' => Carbon::now(),
        ]);

        return redirect()->route('admin.result-publish.index');
    }

    public function publish(Request $request)
    {

        if (isset($request->batch) && isset($request->ay) && isset($request->course) && isset($request->regulation) && isset($request->sem) && isset($request->result_type) && isset($request->exam_month) && isset($request->exam_year)) {
            $ExamResultPublish = ExamResultPublish::where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'regulation' => $request->regulation, 'semester' => $request->sem, 'result_type' => $request->result_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'publish' => 0])->select('user_name_id', 'subject_1', 'grade_1', 'subject_2', 'grade_2', 'subject_3', 'grade_3', 'subject_4', 'grade_4', 'subject_5', 'grade_5', 'subject_6', 'grade_6', 'subject_7', 'grade_7', 'subject_8', 'grade_8', 'subject_9', 'grade_9', 'subject_10', 'grade_10', 'exam_month', 'exam_year', 'result_type', 'publish_date')->get();

            $getMonth = DateTime::createFromFormat('F', $request->exam_month);
            $requestedMonth = $getMonth->format('n');
            $requestedYear = (int) $request->exam_year;

            if (count($ExamResultPublish) > 0) {
                foreach ($ExamResultPublish as $result) {
                    $subjectArray = [$result->subject_1 => $result->grade_1, $result->subject_2 => $result->grade_2, $result->subject_3 => $result->grade_3, $result->subject_4 => $result->grade_4, $result->subject_5 => $result->grade_5, $result->subject_6 => $result->grade_6, $result->subject_7 => $result->grade_7, $result->subject_8 => $result->grade_8, $result->subject_9 => $result->grade_9, $result->subject_10 => $result->grade_10];

                    if (count($subjectArray) > 0) {
                        foreach ($subjectArray as $subject => $grade) {

                            if ($grade != '' && $grade != null) {
                                $publishAct = true;
                                $checkGradeBook = GradeBook::where(['user_name_id' => $result->user_name_id, 'batch' => $request->batch, 'academic_year' => $request->ay, 'regulation' => $request->regulation, 'semester' => $request->sem, 'subject' => $subject, 'course' => $request->course])->select('id', 'exam_date')->first();

                                if ($checkGradeBook != null && $checkGradeBook != '') {
                                    $explode = explode(' ', $checkGradeBook->exam_date);
                                    $theExamMonth = $explode[0];
                                    $theExamYear = $explode[1];
                                    if ($requestedYear < (int) $theExamYear) {
                                        $publishAct = false;
                                    } elseif ($requestedYear == (int) $theExamYear) {
                                        $getMonth = DateTime::createFromFormat('F', $theExamMonth);
                                        $publishedMonth = $getMonth->format('n');
                                        if ($requestedMonth < $publishedMonth) {
                                            $publishAct = false;
                                        }
                                    }
                                    if ($publishAct == true) {
                                        $updateGradeBook = GradeBook::where(['id' => $checkGradeBook->id])->update([
                                            'grade' => $grade,
                                            'result_type' => $result->result_type,
                                            'exam_date' => $result->exam_month . ' ' . $result->exam_year,
                                            'published_date' => $result->publish_date,
                                        ]);
                                    }
                                } else {
                                    $store = GradeBook::create([
                                        'user_name_id' => $result->user_name_id,
                                        'course' => $request->course,
                                        'batch' => $request->batch,
                                        'academic_year' => $request->ay,
                                        'regulation' => $request->regulation,
                                        'semester' => $request->sem,
                                        'published_date' => $result->publish_date,
                                        'exam_date' => $result->exam_month . ' ' . $result->exam_year,
                                        'subject' => $subject,
                                        'grade' => $grade,
                                        'result_type' => $result->result_type,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
            $getDates = ExamResultPublish::where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'regulation' => $request->regulation, 'semester' => $request->sem, 'result_type' => $request->result_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'publish' => 1])->select('uploaded_date', 'publish_date')->first();
            if ($getDates != null) {
                $updateResult = ExamResultPublish::where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'regulation' => $request->regulation, 'semester' => $request->sem, 'result_type' => $request->result_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'publish' => 0])->update([
                    'publish' => 1,
                    'uploaded_date' => $getDates->uploaded_date,
                    'publish_date' => $getDates->publish_date,
                ]);
            } else {
                $updateResult = ExamResultPublish::where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'regulation' => $request->regulation, 'semester' => $request->sem, 'result_type' => $request->result_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'publish' => 0])->update([
                    'publish' => 1,
                ]);
            }
            if ($updateResult) {
                return response()->json(['status' => true, 'data' => 'Result Published']);
            } else {
                return response()->json(['status' => false, 'data' => 'Result Not Published']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }

    }

    public function studentIndex()
    {
        return view('admin.studentGrade.stu_index');
    }

    public function getGrade(Request $request)
    {

        if (isset($request->semester)) {
            $semester = $request->semester;
            $user_name_id = auth()->user()->id;

            if ($semester != 'All') {
                $getData = GradeBook::with('getSubject:id,name,subject_code', 'getAy:id,name', 'getGrade')->where(['user_name_id' => $user_name_id, 'semester' => $request->semester])->whereNotNull('subject')->select('user_name_id', 'subject', 'grade', 'academic_year', 'regulation', 'course', 'exam_date', 'semester')->get();
            } else {
                $getData = GradeBook::with('getSubject:id,name,subject_code', 'getAy:id,name', 'getGrade')->where(['user_name_id' => $user_name_id])->whereNotNull('subject')->select('user_name_id', 'subject', 'grade', 'academic_year', 'regulation', 'course', 'exam_date', 'semester')->orderBy('semester', 'DESC')->get();
            }

            $table = DataTables::of($getData);

            $table->addColumn('placeholder', '');

            $table->editColumn('ay', function ($row) {
                return $row->getAy ? $row->getAy->name : '';
            });
            $table->editColumn('semester', function ($row) {
                return '0' . $row->semester;
            });
            $table->editColumn('subject_code', function ($row) {
                return $row->getSubject ? $row->getSubject->subject_code : '';
            });
            $table->editColumn('subject_name', function ($row) {
                return $row->getSubject ? $row->getSubject->name : '';
            });
            $table->editColumn('grade_letter', function ($row) {
                return $row->getGrade ? $row->getGrade->grade_letter : '';
            });
            $table->editColumn('result', function ($row) {
                return $row->getGrade ? $row->getGrade->result : '';
            });
            $table->editColumn('exam_date', function ($row) {
                return $row->exam_date;
            });
            return $table->make(true);

        } else {
            return redirect()->route('admin.student_grade_mark.statement')->with('error', 'Technical Error');
        }
    }

    public function gradeBookIndex(Request $request)
    {
        $students = Student::select('name', 'user_name_id', 'register_no')->get();

        return view('admin.studentGrade.index', compact('students'));
    }

    public function getGradeBook(Request $request)
    {
        if (isset($request->result_sem) && isset($request->user_name_id)) {

            if ($request->result_sem != 'All') {
                $getData = GradeBook::with('getSubject:id,name,subject_code', 'getAy:id,name', 'getGrade')->where(['user_name_id' => $request->user_name_id, 'semester' => $request->result_sem])->whereNotNull('subject')->select('user_name_id', 'subject', 'grade', 'academic_year', 'regulation', 'course', 'exam_date', 'semester')->get();
            } else {
                $getData = GradeBook::with('getSubject:id,name,subject_code', 'getAy:id,name', 'getGrade')->where(['user_name_id' => $request->user_name_id])->whereNotNull('subject')->select('user_name_id', 'subject', 'grade', 'academic_year', 'regulation', 'course', 'exam_date', 'semester')->orderBy('semester', 'DESC')->get();
            }
            $table = DataTables::of($getData);

            $table->addColumn('placeholder', '');

            $table->editColumn('ay', function ($row) {
                return $row->getAy ? $row->getAy->name : '';
            });
            $table->editColumn('semester', function ($row) {
                return '0' . $row->semester;
            });
            $table->editColumn('subject_code', function ($row) {
                return $row->getSubject ? $row->getSubject->subject_code : '';
            });
            $table->editColumn('subject_name', function ($row) {
                return $row->getSubject ? $row->getSubject->name : '';
            });
            $table->editColumn('grade_letter', function ($row) {
                return $row->getGrade ? $row->getGrade->grade_letter : '';
            });
            $table->editColumn('result', function ($row) {
                return $row->getGrade ? $row->getGrade->result : '';
            });
            $table->editColumn('exam_date', function ($row) {
                return $row->exam_date;
            });
            return $table->make(true);

        } else {
            return redirect()->route('admin.grade-book.index')->with('error', 'Technical Error');
        }
    }

    public function printGrades(Request $request)
    {
        if (isset($request->result_sem) && isset($request->user_name_id)) {

            if ($request->result_sem != 'All') {
                $getData = GradeBook::with('getSubject:id,name,subject_code', 'getAy:id,name', 'getGrade')->where(['user_name_id' => $request->user_name_id, 'semester' => $request->result_sem])->whereNotNull('subject')->select('user_name_id', 'subject', 'grade', 'academic_year', 'regulation', 'course', 'exam_date', 'semester')->get();
            } else {
                $getData = GradeBook::with('getSubject:id,name,subject_code', 'getAy:id,name', 'getGrade')->where(['user_name_id' => $request->user_name_id])->whereNotNull('subject')->select('user_name_id', 'subject', 'grade', 'academic_year', 'regulation', 'course', 'exam_date', 'semester')->orderBy('semester', 'DESC')->get();
            }
            $pdf = PDF::loadView('admin.studentGrade.print', ['getData' => $getData]);
            $pdf->setPaper('A4', 'portrait');

            return $pdf->stream('GradeBook.pdf');

        } else {
            return redirect()->route('admin.grade-book.index')->with('error', 'Technical Error');
        }
    }

    public function checkData(Request $request)
    {
        if (isset($request->batch) && isset($request->ay) && isset($request->course) && isset($request->sem) && isset($request->exam_month) && isset($request->exam_year)) {
            $ExamResultPublish = ExamResultPublish::where(['academic_year' => $request->ay, 'batch' => $request->batch, 'course' => $request->course, 'semester' => $request->sem, 'publish' => 1])->select('exam_month', 'exam_year')->orderBy('id', 'DESC')->first();

            $getMonth = DateTime::createFromFormat('F', $request->exam_month);
            $requestedMonth = $getMonth->format('n');
            $requestedYear = (int) $request->exam_year;
            $publishAct = true;
            if ($ExamResultPublish != null) {
                $theExamMonth = $ExamResultPublish->exam_month;
                $theExamYear = (int) $ExamResultPublish->exam_year;
                if ($requestedYear < $theExamYear) {
                    $publishAct = false;
                } elseif ($requestedYear == (int) $theExamYear) {
                    $getMonth = DateTime::createFromFormat('F', $theExamMonth);
                    $publishedMonth = $getMonth->format('n');
                    if ($requestedMonth < $publishedMonth) {
                        $publishAct = false;
                    }
                }
                if ($publishAct == true) {
                    return response(['status' => true, 'data' => null]);
                } else {
                    return response(['status' => false, 'data' => 'Can\'t Import The Past Records']);
                }
            } else {
                return response(['status' => true, 'data' => null]);
            }
        } else {
            return response(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }
}
