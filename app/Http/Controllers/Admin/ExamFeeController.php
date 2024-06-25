<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\ExamFeeMaster;
use App\Models\ExamRegistration;
use App\Models\SubjectType;
use App\Models\ToolsCourse;
use App\Models\ToolssyllabusYear;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ExamFeeController extends Controller
{
    public function index(Request $request)
    {
        $batches = Batch::pluck('name', 'id');
        $courses = ToolsCourse::pluck('short_form', 'id');
        $ays = AcademicYear::pluck('name', 'id');
        $exam_month_year = ExamRegistration::groupBy('exam_month', 'exam_year')->select('exam_month', 'exam_year')->get();

        return view('admin.examFee.index', compact('batches', 'courses', 'ays', 'exam_month_year'));
    }

    public function generate(Request $request)
    {
        if (isset($request->ay) && isset($request->batch) && isset($request->semester) && isset($request->course) && isset($request->user_name_id) && isset($request->exam_month) && isset($request->exam_year)) {

            $batches = Batch::where(['id' => $request->batch])->select('name')->first();
            $courses = ToolsCourse::where(['id' => $request->course])->select('short_form')->first();
            $ays = AcademicYear::where(['id' => $request->ay])->select('name')->first();
            $semester = $request->semester;
            if ($batches == '') {
                return back()->withErrors('Batch Not Found');
            } else {
                $batch = $batches->name;
            }
            if ($courses == '') {
                return back()->withErrors('Course Not Found');
            } else {
                $course = $courses->short_form;
            }
            if ($ays == '') {
                return back()->withErrors('AY Not Found');
            } else {
                $ay = $ays->name;
            }
            $exam_date = $request->exam_month . ' ' . $request->exam_year;
            if ($request->user_name_id == 'All') {
                $datas = DB::table('exam_registration')
                    ->where([
                        'batch' => $request->batch,
                        'academic_year' => $request->ay,
                        'course' => $request->course,
                        'semester' => $request->semester,
                    ])
                    ->select('students.register_no', 'students.name', 'students.user_name_id', DB::raw('COUNT(DISTINCT subject_id) as subject_count'), DB::raw('SUM(exam_fee) as exam_fee_sum'))
                    ->join('students', 'exam_registration.user_name_id', '=', 'students.user_name_id')
                    ->groupBy('students.user_name_id', 'students.register_no', 'students.name')
                    ->where('exam_registration.deleted_at', null)
                    ->get();
            } else {
                $datas = DB::table('exam_registration')
                    ->where([
                        'batch' => $request->batch,
                        'academic_year' => $request->ay,
                        'course' => $request->course,
                        'semester' => $request->semester,
                    ])
                    ->select('students.register_no', 'students.name', 'students.user_name_id', DB::raw('COUNT(DISTINCT subject_id) as subject_count'), DB::raw('SUM(exam_fee) as exam_fee_sum'))
                    ->join('students', 'exam_registration.user_name_id', '=', 'students.user_name_id')
                    ->groupBy('students.user_name_id', 'students.register_no', 'students.name')
                    ->where('exam_registration.deleted_at', null)->where('exam_registration.user_name_id', $request->user_name_id)
                    ->get();
            }
            return view('admin.examFee.downloadExcel', compact('ay', 'course', 'semester', 'batch', 'exam_date', 'datas'));
        } else {
            return back()->withErrors('Required Details Not Found');
        }
    }

    public function masterIndex(Request $request)
    {
        if ($request->ajax()) {
            $get = ExamFeeMaster::with('regulations:id,name')->select('regulation_id')->groupBy('regulation_id')->get();
            // dd($get);
            $table = DataTables::of($get);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewExamfee';
                $editFunct = 'editExamfee';
                $deleteFunct = 'deleteExamfee';
                $viewGate      = 'subject_type_show';
                $editGate      = 'subject_type_edit';
                $deleteGate    = 'subject_type_delete';
                $crudRoutePart = 'examfee-master';

                return view('partials.ajaxTableActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'viewFunct',
                    'editFunct',
                    'deleteFunct',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->regulation_id ? $row->regulation_id : '';
            });
            $table->editColumn('regulation', function ($row) {
                return $row->regulations->name ? $row->regulations->name : '';
            });

            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }

        $reg = ToolssyllabusYear::pluck('name', 'id');
        return view('admin.examFeeMaster.index', compact('reg'));
    }

    public function masterCreate()
    {
        $regulations = ToolssyllabusYear::pluck('name', 'id')->prepend('Select Regulation', '');

        return view('admin.examFeeMaster.create', compact('regulations'));
    }

    public function masterStore(Request $request)
    {
        //    dd($request);
        if (isset($request->data) && $request->data != null && isset($request->regulation) && $request->regulation != null) {
            $data = $request->data;
            $regulation = $request->regulation;
            $datalen = count($data);
            if ($datalen > 0) {
                foreach ($data as $data) {
                    $store = ExamFeeMaster::create([
                        'regulation_id' => $regulation,
                        'subject_type_id' => $data['subject_type'],
                        'fee' => $data['exam_fee'],
                    ]);
                }
                return response()->json(['status' => true]);
            } else {
                return response()->json(['status' => false]);
            }
        } else {
            return response()->json(['status' => false]);
        }
    }

    public function masterShow(Request $request)
    {
        // $show = ExamFeeMaster::with('regulations:id,name', 'subject_types:id,name')->where(['regulation_id' => $request->regulation_id])->select('regulation_id', 'subject_type_id', 'fee')->get();
        // return view('admin.examFeeMaster.show', compact('show'));
    }
    public function masterView(Request $request)
    {
        if ((isset($request->id) && $request->id != '')) {
            $data = ExamFeeMaster::with('regulations:id,name', 'subject_types:id,name')->where(['regulation_id' => $request->id])->select('regulation_id', 'subject_type_id', 'fee')->get();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function masterEdit(Request $request)
    {
        if (isset($request->id)) {
            $data = ExamFeeMaster::with('regulations:id,name', 'subject_types:id,name')->where(['regulation_id' => $request->id])->select('id', 'regulation_id', 'subject_type_id', 'fee')->get();
            $sub = SubjectType::where('regulation_id', $data[0]->regulation_id)->pluck('name', 'id');
            return response()->json(['status' => true, 'data' => $data, 'sub' => $sub]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function masterUpdate(Request $request)
    {
        // dd($request);
        if (isset($request->regulation) && $request->regulation != '' && isset($request->data)) {
            $regulation = $request->regulation;
            if (isset($request->removable_id)) {
                $removable_id = $request->removable_id;
            } else {
                $removable_id = [];
            }
            $data = $request->data;
            if (count($removable_id) > 0) {
                foreach ($removable_id as $id) {
                    $delete = ExamFeeMaster::where(['id' => $id])->delete();
                }
            }
            // dd(count($data));
            if (count($data) > 0) {
                foreach ($data as $data) {
                    if ($data['id'] != null) {
                        $check = ExamFeeMaster::where('subject_type_id', $data['subject_type'])->exists();
                        if ($check) {
                            $update = ExamFeeMaster::where(['subject_type_id' => $data['id']])->update([
                                'subject_type_id' => $data['subject_type'],
                                'fee' => $data['exam_fee'],
                            ]);
                        } else {
                            $store = ExamFeeMaster::create([
                                'regulation_id' => $regulation,
                                'subject_type_id' => $data['subject_type'],
                                'fee' => $data['exam_fee'],
                            ]);
                        }
                    } else {
                        $store = ExamFeeMaster::create([
                            'regulation_id' => $regulation,
                            'subject_type_id' => $data['subject_type'],
                            'fee' => $data['exam_fee'],
                        ]);
                    }
                }
                // dd($store);
                return response()->json(['status' => true, 'data' => 'Exam Fee Master Updated Successfully']);
            } else {
                return response()->json(['status' => false, 'data' => 'Data Not Found']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Technical Error']);
        }
    }

    public function masterDestroy(Request $request)
    {
        if (isset($request->id) && $request->id != '') {
            $delete = ExamFeeMaster::where(['regulation_id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'ExamFeeMaster Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function checkRegulation(Request $request)
    {
        if (isset($request->regulation) && $request->regulation != '') {
            $check = ExamFeeMaster::where('regulation_id', $request->regulation)->count();
            if ($check > 0) {
                return response()->json(['status' => false, 'data' => 'Already Fees Master Created For This Regulation']);
            } else {
                $subjectTypes = SubjectType::where(['regulation_id' => $request->regulation])->select('id', 'name')->get();
                return response()->json(['status' => true, 'data' => $subjectTypes]);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Couldn\'t Find The Regulation']);
        }
    }

    public function massDestroy(Request $request)
    {
        $ExamFeeMaster = ExamFeeMaster::whereIn('regulation_id', request('ids'))->get();
        // dd($ExamFeeMaster);
        foreach ($ExamFeeMaster as $e) {
            $e->delete();

            return response()->json(['status' => 'success', 'data' => 'ExamFeeMasters are Deleted Successfully']);
        }
    }
}
