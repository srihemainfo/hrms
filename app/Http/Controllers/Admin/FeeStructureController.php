<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Models\AcademicDetail;
use App\Models\AcademicFee;
use App\Models\AcademicYear;
use App\Models\AdmissionMode;
use App\Models\Batch;
use App\Models\CourseEnrollMaster;
use App\Models\FeeComponents;
use App\Models\FeeStructure;
use App\Models\Scholarship;
use App\Models\Semester;
use App\Models\ShiftModel;
use App\Models\ToolsCourse;
use App\Models\ToolsDegreeType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class FeeStructureController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('fee_structure')
                ->whereNull('fee_structure.deleted_at')
                ->leftJoin('users', 'users.id', '=', 'fee_structure.created_by')
                ->leftJoin('semesters', 'semesters.id', '=', 'fee_structure.semester_id')
                ->leftJoin('shift', 'shift.id', '=', 'fee_structure.shift_id')
                ->leftJoin('batches', 'batches.id', '=', 'fee_structure.batch_id')
                ->leftJoin('tools_courses', 'tools_courses.id', '=', 'fee_structure.course_id')
                ->leftJoin('academic_years', 'academic_years.id', '=', 'fee_structure.academic_year_id')
                // ->leftJoin('admission_mode', 'admission_mode.id', '=', 'fee_structure.admission_id')
                ->select('shift.Name as shi', 'semesters.semester as sem', 'fee_structure.id', 'users.name as user', 'academic_years.name as ay', 'tools_courses.short_form as course', 'batches.name as batch',  'fee_structure.status')->get();

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
        $course = ToolsCourse::pluck('short_form', 'id');
        $admission = AdmissionMode::pluck('name', 'id');
        $scholarship = Scholarship::pluck('name', 'id');
        $ay = AcademicYear::pluck('name', 'id');
        $batch = Batch::pluck('name', 'id');
        $shift = ShiftModel::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        return view('admin.feeStructure.index', compact('scholarship', 'admission', 'course', 'degreeType', 'ay', 'batch', 'shift', 'semester'));
    }
    public function store(Request $request)
    {
        // dd($request);

        if (isset($request->componentsJson)) {
            if ($request->id == '') {
                $check = FeeStructure::where(['admission_id' => $request->admission, 'batch_id' => $request->batch, 'course_id' => $request->course])->count();
                if ($check > 0) {
                    return response()->json(['status' => false, 'data' => 'Fees Structure Already Exist']);
                } else {
                    $store = FeeStructure::create([
                        // 'admission_id' => $request->admission,
                        'batch_id' => $request->batch,
                        'course_id' => $request->course,
                        // 'academic_year_id' => $request->ay,
                        // 'admission_fee' => $request->admission_fee,
                        // 'tuition_fee' => $request->tuition_fee,
                        // 'special_fee' => $request->special_fee,
                        // 'hostel_fee' => $request->hostel_fee,
                        // 'other_fee' => $request->other_fee,
                        'fee_component'=>$request->componentsJson,
                        'shift_id' => $request->shift,
                        'semester_id' => $request->semester,
                        'created_by' => auth()->id(),
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Fees Structure Created']);
            } else {
                $update = FeeStructure::where(['id' => $request->id])->update([
                    'admission_fee' => $request->admission_fee,
                    'tuition_fee' => $request->tuition_fee,
                    'special_fee' => $request->special_fee,
                    'hostel_fee' => $request->hostel_fee,
                    'other_fee' => $request->other_fee,
                    'created_by' => auth()->id(),
                ]);
                return response()->json(['status' => true, 'data' => 'Fees Structure Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Fees Structure Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = DB::table('fee_structure')
                ->where('fee_structure.id', $request->id)
                ->leftJoin('users', 'users.id', '=', 'fee_structure.created_by')
                ->leftJoin('semesters', 'semesters.id', '=', 'fee_structure.semester_id')
                ->leftJoin('shift', 'shift.id', '=', 'fee_structure.shift_id')
            // ->leftJoin('academic_years', 'academic_years.id', '=', 'fee_structure.academic_year_id')
                ->leftJoin('tools_courses', 'tools_courses.id', '=', 'fee_structure.course_id')
                ->leftJoin('batches', 'batches.id', '=', 'fee_structure.batch_id')
                // ->leftJoin('admission_mode', 'admission_mode.id', '=', 'fee_structure.admission_id')
                ->select(
                    'fee_structure.id as fees_id',
                    'users.id as user',
                    // 'academic_years.id as ay',
                    'tools_courses.id as course',
                    'batches.id as batch',
                    // 'admission_mode.id as admission',
                    // 'fee_structure.tuition_fee',
                    // 'fee_structure.special_fee',
                    // 'fee_structure.admission_fee',
                    // 'fee_structure.hostel_fee',
                    // 'fee_structure.other_fee',
                    'shift.id as shi',
                    'semesters.id as sem'

                )
                ->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = DB::table('fee_structure')
                ->where('fee_structure.id', $request->id)
                ->leftJoin('users', 'users.id', '=', 'fee_structure.created_by')
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
                    // 'admission_mode.id as admission',
                    'fee_structure.admission_fee',
                    'fee_structure.tuition_fee',
                    'fee_structure.special_fee',
                    'fee_structure.hostel_fee',
                    'fee_structure.other_fee'
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
        if (isset($request->batch) && isset($request->ay)) {
            $getCount = AcademicFee::where(['batch' => $request->batch, 'ay' => $request->ay])->count();
            if ($getCount <= 0) {
                $getData = FeeStructure::with('courses', 'admissions')->where(['batch_id' => $request->batch, 'academic_year_id' => $request->ay])->select('admission_id', 'course_id', 'tuition_fee', 'special_fee', 'admission_fee', 'hostel_fee', 'other_fee', 'id')->get();
                if (count($getData) > 0) {
                    $theAy = AcademicYear::where(['id' => $request->ay])->value('name');
                    $theBatch = Batch::where(['id' => $request->batch])->value('name');
                    if ($theAy != null && $theBatch != null) {
                        foreach ($getData as $data) {
                            if ($data->courses != null && $data->admissions != null) {
                                $courseEnrollMaster = CourseEnrollMaster::where('enroll_master_number', 'LIKE', $theBatch . '/' . $data->courses->name . '/' . $theAy . '/%/%')->select('id')->get();
                                $theEnrolls = [];
                                if (count($courseEnrollMaster) > 0) {
                                    foreach ($courseEnrollMaster as $enroll) {
                                        $theEnrolls[] = $enroll->id;
                                    }
                                } else {
                                    return response()->json(['status' => false, 'data' => 'Classes Not Found']);
                                }
                                $getStudents = AcademicDetail::whereIn('enroll_master_number_id', $theEnrolls)->where(['admitted_mode' => $data->admissions->name, 'scholarship' => '0', 'first_graduate' => '0', 'gqg' => '0'])->select('user_name_id', 'enroll_master_number_id', 'hosteler')->get();
                                if (count($getStudents) > 0) {
                                    foreach ($getStudents as $students) {
                                        $academicFee = AcademicFee::create([
                                            'user_name_id' => $students->user_name_id,
                                            'batch' => $request->batch,
                                            'ay' => $request->ay,
                                            'course' => $data->course_id,
                                            'enroll_master_id' => $students->enroll_master_number_id,
                                            'tuition_fee' => $data->tuition_fee,
                                            'special_fee' => $data->special_fee,
                                            'admission_fee' => $data->admission_fee,
                                            'hostel_fee' => $students->hosteler == '1' ? $data->hostel_fee : 0,
                                            'other_fee' => $data->other_fee,
                                        ]);
                                    }
                                    FeeStructure::where(['id' => $data->id])->update(['status' => 1]);
                                } else {
                                    return response()->json(['status' => false, 'data' => 'Students Not Found']);
                                }
                            }
                        }
                        return response()->json(['status' => true, 'data' => 'Fees Generated Successfully']);
                    } else {
                        return response()->json(['status' => false, 'data' => 'Batch / AY Not Found']);
                    }
                } else {
                    return response()->json(['status' => false, 'data' => 'Fees Structure Not Created Yet']);
                }
            } else {
                return response()->json(['status' => false, 'data' => 'The Fees Dues was already generated for the selected Batch and Academic Year']);
            }

        } else {
            return response()->json(['status' => false, 'data' => 'Fees Not Generated']);
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

        // dd($request);

        $shiftSelect = $request->shiftSelect;
        $fetchCourses = ToolsCourse::where('shift_id', $shiftSelect)
        ->pluck('short_form','id');
        if($fetchCourses)
        {
            return response()->json(['status' => true, 'data' => $fetchCourses]);
        }
        else
        {
            return response()->json(['status' => false, 'data' => 'Not able to Fetch Courses']);
        }
        


    }

}
