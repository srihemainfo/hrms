<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Models\AcademicFee;
use App\Models\AcademicYear;
use App\Models\AdmissionMode;
use App\Models\Batch;
use App\Models\CourseEnrollMaster;
use App\Models\CustomsFee;
use App\Models\FeeComponents;
use App\Models\FeeCycle;
use App\Models\FeeList;
use App\Models\FeeStructure;
use App\Models\Scholarship;
use App\Models\Semester;
use App\Models\ShiftModel;
use App\Models\Student;
use App\Models\ToolsCourse;
use App\Models\ToolsDegreeType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class FeeStructureController extends Controller
{
    use CsvImportTrait;

    // public function feecycle(Request $request)
    // {
    //     $feeCycles = FeeCycle::select('cycle_name')->get();
    //     return view('admin.feeStructure.index', compact('feeCycles'));

    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('fee_structure')
                ->whereNull('fee_structure.deleted_at')
                ->leftJoin('users', 'users.id', '=', 'fee_structure.created_by')
                ->leftJoin('semesters', 'semesters.id', '=', 'fee_structure.semester_id')
                ->leftJoin('shift', 'shift.id', '=', 'fee_structure.shift_id')
                ->leftJoin('batches', 'batches.id', '=', 'fee_structure.batch_id')
                ->leftJoin('customs_fee', 'customs_fee.id', '=', 'fee_structure.customs_id')
                ->leftJoin('tools_courses', 'tools_courses.id', '=', 'fee_structure.course_id')
                ->leftJoin('academic_years', 'academic_years.id', '=', 'fee_structure.academic_year_id')
            // ->leftJoin('admission_mode', 'admission_mode.id', '=', 'fee_structure.admission_id')
                ->select('shift.Name as shi', 'semesters.semester as sem', 'fee_structure.id', 'users.name as user', 'academic_years.name as ay', 'tools_courses.short_form as course', 'batches.name as batch', 'fee_structure.status', 'customs_fee.fee_name as cusfee_name')->get();

            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'fee_structure_show';
                $editGate = 'fee_structure_edit';
                $deleteGate = 'fee_structure_delete';
                $viewFunct = 'viewfeeStructure';
                $editFunct = 'editfeeStructure';
                $deleteFunct = 'deletefeeStructure';
                $crudRoutePart = 'fee-structure';

                return view('partials.ajaxTableActions', compact(
                    'viewGate',
                    // 'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'viewFunct',
                    'editFunct',
                    'deleteFunct',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('shift', function ($row) {
                return $row->shi ? $row->shi : '';
            });
            $table->editColumn('batch', function ($row) {
                return $row->batch ? $row->batch : '';
            });
            $table->editColumn('course', function ($row) {
                return $row->course ? $row->course : '';
            });

            $table->editColumn('semester', function ($row) {
                return $row->sem ? $row->sem : '';
            });

            $table->editColumn('customs_fee', function ($row) {
                return $row->cusfee_name ? $row->cusfee_name : '';
            });

            $table->editColumn('academic_years', function ($row) {
                return $row->sem ? $row->ay : '';
            });
            // $table->editColumn('admission', function ($row) {
            //     return $row->admission ? $row->admission : '';
            // });

            $table->editColumn('user', function ($row) {
                return $row->user ? $row->user : '';
            });

            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }

        $degreeType = ToolsDegreeType::pluck('name', 'id');
        $customsFee = CustomsFee::pluck('fee_name', 'id');
        $course = ToolsCourse::pluck('short_form', 'id');
        $admission = AdmissionMode::pluck('name', 'id');
        $scholarship = Scholarship::pluck('name', 'id');
        $ay = AcademicYear::pluck('name', 'id');
        $feeCycles = FeeCycle::pluck('cycle_name');
        $batch = Batch::pluck('name', 'id');
        $shift = ShiftModel::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $fee_compnents = FeeComponents::pluck('name', 'id');
        return view('admin.feeStructure.index', compact('scholarship', 'admission', 'course', 'degreeType', 'ay', 'batch', 'shift', 'semester', 'fee_compnents', 'feeCycles', 'customsFee'));
    }
    public function store(Request $request)
    {
        // dd($request);
        if (isset($request->componentsJson)) {
            $feeCycleText = $request->feeCycleText;
            if ($request->id == '') {
                // Check for existing fee structure based on feeCycleText
                if ($feeCycleText == 'SemesterWise') {
                    $check = FeeStructure::where(['batch_id' => $request->batch, 'course_id' => $request->course, 'semester_id' => $request->semester])->count();
                } else if ($feeCycleText == 'YearlyWise') {
                    $check = FeeStructure::where(['batch_id' => $request->batch, 'course_id' => $request->course, 'academic_year_id' => $request->applied_ay])->count();
                } else if ($feeCycleText == 'CustomsWise') {

                    $check = FeeStructure::where(['batch_id' => $request->batch, 'course_id' => $request->course, 'academic_year_id' => $request->applied_ay, 'customs_id' => $request->customs])->count();
                }

                if ($check > 0) {
                    return response()->json(['status' => false, 'data' => 'Fees Structure Already Exist']);
                } else {
                    $store = FeeStructure::create([
                        'batch_id' => $request->batch,
                        'course_id' => $request->course,
                        'fee_component' => $request->componentsJson,
                        'shift_id' => $request->shift,
                        'semester_id' => $request->semester,
                        'customs_id' => $request->customs,
                        'academic_year_id' => $request->applied_ay,
                        'created_by' => auth()->id(),
                    ]);
                    return response()->json(['status' => true, 'data' => 'Fees Structure Created']);
                }
            } else {

                $update = FeeStructure::where(['id' => $request->id])->update([
                    'fee_component' => $request->componentsJson,
                    'created_by' => auth()->id(),
                ]);
                return response()->json(['status' => true, 'data' => 'Fees Structure Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Fees Structure Not Updated']);
        }
    }

    public function view(Request $request)
    {
        // dd($request);
        if (isset($request->id)) {
            $data = DB::table('fee_structure')
                ->where('fee_structure.id', $request->id)
                ->leftJoin('users', 'users.id', '=', 'fee_structure.created_by')
                ->leftJoin('semesters', 'semesters.id', '=', 'fee_structure.semester_id')
                ->leftJoin('shift', 'shift.id', '=', 'fee_structure.shift_id')
                ->leftJoin('academic_years', 'academic_years.id', '=', 'fee_structure.academic_year_id')
                ->leftJoin('tools_courses', 'tools_courses.id', '=', 'fee_structure.course_id')
                ->leftJoin('batches', 'batches.id', '=', 'fee_structure.batch_id')
            // ->leftJoin('admission_mode', 'admission_mode.id', '=', 'fee_structure.admission_id')
                ->select(
                    'fee_structure.id as fees_id',
                    'users.id as user',
                    'academic_years.id as ay',
                    'tools_courses.id as course',
                    'batches.id as batch',
                    'shift.id as shi',
                    'semesters.id as sem',
                    'fee_structure.fee_component'

                )
                ->first();
            // dd($data);
            if ($data) {
                $data->fee_component = json_decode($data->fee_component);
                // dd($data);
                return response()->json(['status' => true, 'data' => $data]);
            } else {
                return response()->json(['status' => false, 'data' => 'No data found for the given ID']);
            }

        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {

        if (isset($request->id)) {
            $data = DB::table('fee_structure')
                ->where('fee_structure.id', $request->id)
                ->leftJoin('semesters', 'semesters.id', '=', 'fee_structure.semester_id')
                ->leftJoin('shift', 'shift.id', '=', 'fee_structure.shift_id')
                ->leftJoin('users', 'users.id', '=', 'fee_structure.created_by')
                ->leftJoin('academic_years', 'academic_years.id', '=', 'fee_structure.academic_year_id')
                ->leftJoin('tools_courses', 'tools_courses.id', '=', 'fee_structure.course_id')
                ->leftJoin('batches', 'batches.id', '=', 'fee_structure.batch_id')
                ->select(
                    'fee_structure.id as fees_id',
                    'users.id as user',
                    'academic_years.id as ay',
                    'tools_courses.id as course',
                    'batches.id as batch',
                    'shift.id as shi',
                    'semesters.id as sem',
                    'fee_structure.fee_component'

                )
                ->first();

            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = FeeStructure::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'FeeStructure Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $nation = FeeStructure::whereIn('id', request('ids'))->update([
            'deleted_at' => Carbon::now(),
        ]);
        return response()->json(['status' => 'success', 'data' => 'FeeStructure Deleted Successfully']);
    }

    public function generateFee(Request $request)
    {
        $feeCycleText = $request->feeCycleText;

        if ($feeCycleText == 'YearlyWise') {
            if (isset($request->batch) && isset($request->ay)) {

                $getCount = FeeList::where(['batch_id' => $request->batch, 'academic_year_id' => $request->ay])->count();
                if ($getCount <= 0) {

                    $getData = FeeStructure::where(['batch_id' => $request->batch, 'academic_year_id' => $request->ay])->select('fee_component', 'course_id', 'id')->get();

                    foreach ($getData as $data) {
                        $components = json_decode($data->fee_component, true);

                        $totalAmount = null;

                        foreach ($components as $component) {
                            if (isset($component['name']) && $component['name'] === 'Total') {
                                $totalAmount = $component['amount'];
                                break;
                            }
                        }

                        $course_enroll_mas = CourseEnrollMaster::where(['batch_id' => $request->batch, 'academic_id' => $request->ay])
                            ->where('course_id', $data->course_id)
                            ->pluck('id');

                        // dd($course_enroll_mas);

                        $students = Student::with('enroll_master')->whereIn('enroll_master_id', $course_enroll_mas)
                            ->select('id', 'admitted_course', 'enroll_master_id')
                            ->get();

                        if ($students->isEmpty()) {
                            return response()->json(['status' => false, 'data' => 'Students not found']);
                        }

                        foreach ($students as $student) {
                            FeeList::create([
                                'student_id' => $student->id,
                                'batch_id' => $request->batch,
                                'academic_year_id' => $request->ay,
                                'status' => 0,
                                'course_id' => $student->enroll_master->course->id,
                                'fee_id' => $data->id,
                                'total' => $totalAmount,

                            ]);
                        }
                    }

                    if (count($getData) > 0) {

                        return response()->json(['status' => true, 'data' => 'Fees Generated Successfully']);

                    } else {
                        return response()->json(['status' => false, 'data' => 'Fees Structure Not Created Yet']);
                    }

                } else {
                    return response()->json(['status' => false, 'data' => 'The Fees Dues was already generated for the selected Batch and Academic Year']);
                }
            }
        } else if ($feeCycleText == 'SemesterWise') {
            // dd($request);

            if (isset($request->batch) && isset($request->feeSem)) {

                $getCount1 = FeeList::where(['batch_id' => $request->batch, 'semester_id' => $request->feeSem])->count();
                if ($getCount1 <= 0) {

                    $getData1 = FeeStructure::where(['batch_id' => $request->batch, 'semester_id' => $request->feeSem])->select('fee_component', 'course_id', 'id')->get();

                    foreach ($getData1 as $data1) {
                        // dd($data);

                        $components1 = json_decode($data1->fee_component, true);

                        $totalAmount = null;

                        foreach ($components1 as $component1) {
                            if (isset($component1['name']) && $component1['name'] === 'Total') {
                                $totalAmount = $component1['amount'];
                                break;
                            }
                        }

                        $course_enroll_mas1 = CourseEnrollMaster::where(['batch_id' => $request->batch, 'semester_id' => $request->feeSem])
                            ->where('course_id', $data1->course_id)
                            ->pluck('id');

                        // dd($course_enroll_mas1);

                        $studentsss = Student::with('enroll_master')->whereIn('enroll_master_id', $course_enroll_mas1)
                            ->select('id', 'admitted_course', 'enroll_master_id')
                            ->get();

                        if ($studentsss->isEmpty()) {
                            return response()->json(['status' => false, 'data' => 'Students not found']);
                        }

                        foreach ($studentsss as $studenttt) {

                            FeeList::create([
                                'student_id' => $studenttt->id,
                                'batch_id' => $request->batch,
                                'academic_year_id' => $request->ay,
                                'semester_id' => $request->feeSem,
                                'status' => 0,
                                'course_id' => $studenttt->enroll_master->course->id,
                                'fee_id' => $data1->id,
                                'total' => $totalAmount,

                            ]);
                        }
                    }

                    if (count($getData1) > 0) {

                        return response()->json(['status' => true, 'data' => 'Fees Generated Successfully']);

                    } else {
                        return response()->json(['status' => false, 'data' => 'Fees Structure Not Created Yet']);
                    }

                } else {
                    return response()->json(['status' => false, 'data' => 'The Fees Dues was already generated for the selected Batch and Academic Year']);
                }
            }

        } else if ($feeCycleText == 'CustomsWise') {
            // dd($request);
            if (isset($request->batch) && isset($request->customsfeegenerate) && isset($request->ay)) {

                $getCount2 = FeeList::where(['batch_id' => $request->batch, 'academic_year_id' => $request->ay, 'customs_id' => $request->customsfeegenerate])->count();

                if ($getCount2 <= 0) {

                    $getData2 = FeeStructure::where(['batch_id' => $request->batch, 'academic_year_id' => $request->ay, 'customs_id' => $request->customsfeegenerate])->select('fee_component', 'course_id', 'id')->get();

                    foreach ($getData2 as $data2) {
                        // dd($data);

                        $components2 = json_decode($data2->fee_component, true);

                        $totalAmount = null;

                        foreach ($components2 as $component2) {
                            if (isset($component2['name']) && $component2['name'] === 'Total') {
                                $totalAmount = $component2['amount'];
                                break;
                            }
                        }

                        $course_enroll_mas2 = CourseEnrollMaster::where(['batch_id' => $request->batch, 'academic_id' => $request->ay])
                            ->where('course_id', $data2->course_id)
                            ->pluck('id');

                        // dd($course_enroll_mas1);

                        $studentsss2 = Student::with('enroll_master')->whereIn('enroll_master_id', $course_enroll_mas2)
                            ->select('id', 'admitted_course', 'enroll_master_id')
                            ->get();

                        if ($studentsss2->isEmpty()) {
                            return response()->json(['status' => false, 'data' => 'Students not found']);
                        }

                        foreach ($studentsss2 as $studenttt2) {

                            FeeList::create([
                                'student_id' => $studenttt2->id,
                                'batch_id' => $request->batch,
                                'academic_year_id' => $request->ay,
                                'customs_id' => $request->customsfeegenerate,
                                // 'semester_id' => $request->feeSem,
                                'status' => 0,
                                'course_id' => $studenttt2->enroll_master->course->id,
                                'fee_id' => $data2->id,
                                'total' => $totalAmount,

                            ]);
                        }
                    }

                    if (count($getData2) > 0) {

                        return response()->json(['status' => true, 'data' => 'Fees Generated Successfully']);

                    } else {
                        return response()->json(['status' => false, 'data' => 'Fees Structure Not Created Yet']);
                    }

                } else {
                    return response()->json(['status' => false, 'data' => 'The Fees Dues was already generated for the selected Batch and Academic Year']);
                }

            }
        }
    }

    public function publishFee(Request $request)
    {
        if (isset($request->batch) && isset($request->ay)) {
            $getCount = AcademicFee::where(['batch' => $request->batch, 'ay' => $request->ay, 'status' => 1])->count();
            if ($getCount <= 0) {
                $update = AcademicFee::where(['batch' => $request->batch, 'ay' => $request->ay])->update(['status' => 1]);
                FeeStructure::where(['batch_id' => $request->batch, 'academic_year_id' => $request->ay])->update(['status' => 2]);
                if ($update) {
                    return response()->json(['status' => true, 'data' => 'Fees Published Successfully']);
                } else {
                    return response()->json(['status' => false, 'data' => 'Fees Not Published']);
                }
            } else {
                return response()->json(['status' => false, 'data' => 'The Fees Dues was already published for the selected Batch and Academic Year']);
            }

        } else {
            return response()->json(['status' => false, 'data' => 'Fees Not Published']);
        }
    }

    public function getfeecomponents(Request $request)
    {
        $courseVal = $request->courseVal;
        $batchVal = $request->batchVal;
        $semesterVal = $request->semesterVal;

        $feeComponents = FeeComponents::where('batch_id', $batchVal)
            ->where('course_id', $courseVal)
            ->where('semester_id', $semesterVal)
            ->pluck('name', 'id');

        if ($feeComponents->isNotEmpty()) {
            return response()->json(['status' => true, 'data' => $feeComponents]);
        } else {
            return response()->json(['status' => false, 'data' => 'Fees Components was not Created..']);
        }
    }

    public function getCourse(Request $request)
    {
        $shiftSelect = $request->shiftSelect;
        $fetchCourses = ToolsCourse::where('shift_id', $shiftSelect)
            ->pluck('short_form', 'id');
        if ($fetchCourses) {
            return response()->json(['status' => true, 'data' => $fetchCourses]);
        } else {
            return response()->json(['status' => false, 'data' => 'Not able to Fetch Courses']);
        }

    }

}
