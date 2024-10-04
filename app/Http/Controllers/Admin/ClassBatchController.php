<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBatchRequest;
use App\Http\Requests\UpdateBatchRequest;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\CourseEnrollMaster;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentPeriodAllocate;
use App\Models\ToolsCourse;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class classBatchController extends Controller
{
    public function index(Request $request)
    {
        // abort_if(Gate::denies('class_batch_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $currentClasses = Session::get('currentClasses');
            $query = DB::table('students_period_allocation')->whereIn('class', $currentClasses)->whereNull('deleted_at')->selectRaw('class,batch,COUNT(student) AS count')->groupBy('class', 'batch')->get();
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'class_batch_show';
                $editGate = 'class_batch_edit';
                $deleteGate = 'class_batch_delete';
                $viewFunct = 'viewBatch';
                $editFunct = 'editBatch';
                $deleteFunct = 'deleteBatch';
                $crudRoutePart = 'class_batch';

                $row->id = $row->class . ',' . '"' . $row->batch . '"';

                return view('partials.ajaxTableActions', compact(
                    'viewGate',
                    'viewFunct',
                    'editGate',
                    'editFunct',
                    'deleteGate',
                    'deleteFunct',
                    'crudRoutePart',
                    'row'
                ));
            });
            $i = 0;
            $table->editColumn('id', function ($row) use (&$i) {
                $i++;
                return $i;
            });
            $table->editColumn('class', function ($row) {
                $class = null;
                $theClass = CourseEnrollMaster::where('id', $row->class)->select('enroll_master_number')->first();
                if ($theClass != null) {
                    $explode = explode('/', $theClass->enroll_master_number);
                    $getCourse = ToolsCourse::where('short_form', $explode[1])->value('short_form');
                    $class = $getCourse . ' / ' . $explode[3] . ' / ' . $explode[4];
                }
                return $class;
            });
            $table->editColumn('batch', function ($row) {
                return $row->batch ? $row->batch : '';
            });

            $table->editColumn('count', function ($row) {
                return $row->count ? $row->count : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $batches = Batch::pluck('name', 'id');
        $courses = ToolsCourse::pluck('short_form', 'id');
        $ays = AcademicYear::pluck('name', 'id');

        return view('admin.classBatches.index', compact('ays', 'courses', 'batches'));
    }

    public function store(Request $request)
    {
        if (isset($request->class) && isset($request->batch) && isset($request->form_data) && isset($request->action)) {

            $count = StudentPeriodAllocate::where(['class' => $request->class, 'batch' => $request->batch])->count();
            if ($count > 0) {
                if ($request->action == 'Update') {
                    StudentPeriodAllocate::where(['class' => $request->class, 'batch' => $request->batch])->delete();
                } else {
                    return response()->json(['status' => false, 'data' => 'Batch Already Exist For This Class.']);
                }
            }
            $data = $request->form_data;
            foreach ($data as $id => $got_data) {
                StudentPeriodAllocate::insert([
                    'class' => $request->class,
                    'batch' => strtoupper($request->batch),
                    'student' => $got_data[0]['value'],
                    'created_at' => Carbon::now(),
                ]);
            }
            return response()->json(['status' => true, 'data' => 'Batch Created']);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->class) && isset($request->batch)) {
            $data = StudentPeriodAllocate::with('user_name')->where(['class' => $request->class, 'batch' => $request->batch])->select('student')->get();
            $getEnroll = CourseEnrollMaster::where('id', $request->class)->value('enroll_master_number');
            $batch = null;
            $course = null;
            $ay = null;
            $semester = null;
            $section = null;
            if ($getEnroll != null) {
                $explode = explode('/', $getEnroll);
                $batch = $explode[0];
                $ay = $explode[2];
                $semester = $explode[3];
                $section = $explode[4];
                $course = ToolsCourse::where('short_form', $explode[1])->value('id');
            }
            return response()->json(['status' => true, 'data' => $data, 'class' => $request->class, 'batchName' => $request->batch, 'batch' => $batch, 'course' => $course, 'ay' => $ay, 'semester' => $semester, 'section' => $section]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->class) && isset($request->batch)) {
            $data = Student::where(['enroll_master_id' => $request->class])->select('user_name_id', 'register_no', 'name')->get();
            $allocatedStudents = StudentPeriodAllocate::where(['class' => $request->class, 'batch' => $request->batch])->select('student')->get();
            $allotedList = [];
            if (count($allocatedStudents) > 0) {
                foreach ($allocatedStudents as $stu) {
                    $allotedList[] = (int) $stu->student;
                }
            }
            $getEnroll = CourseEnrollMaster::where('id', $request->class)->value('enroll_master_number');
            $batch = null;
            $course = null;
            $ay = null;
            $semester = null;
            $section = null;
            if ($getEnroll != null) {
                $explode = explode('/', $getEnroll);
                $batch = $explode[0];
                $ay = $explode[2];
                $semester = $explode[3];
                $section = $explode[4];
                $course = ToolsCourse::where('short_form', $explode[1])->value('id');
            }
            return response()->json(['status' => true, 'data' => $data, 'class' => $request->class, 'batchName' => $request->batch, 'batch' => $batch, 'course' => $course, 'ay' => $ay, 'semester' => $semester, 'section' => $section, 'allotedList' => $allotedList]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->class) && isset($request->batch)) {
            $delete = StudentPeriodAllocate::where(['class' => $request->class, 'batch' => $request->batch])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Batch Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(MassDestroyBatchRequest $request)
    {
        $batches = Batch::find(request('ids'));
        foreach ($batches as $batch) {
            $batch->delete();
        }

        return response()->json(['status' => 'success', 'data' => 'Batch Deleted Successfully']);
    }

    public function update(UpdateBatchRequest $request, Batch $batch)
    {
        $batch->update($request->all());

        return redirect()->route('admin.batches.index');
    }

    public function show(Batch $batch)
    {
        abort_if(Gate::denies('batch_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.batches.show', compact('batch'));
    }

    public function getStudents(Request $request)
    {
        if (isset($request->batch) && isset($request->course) && isset($request->ay) && isset($request->semester) && isset($request->section)) {
            $course = ToolsCourse::where(['id' => $request->course])->value('short_form');
            if ($course != null) {
                $currentClasses = Session::get('currentClasses');
                $make_enroll = $request->batch . '/' . $course . '/' . $request->ay . '/' . $request->semester . '/' . $request->section;
                $getEnroll = CourseEnrollMaster::whereIn('id', $currentClasses)->where('enroll_master_number', $make_enroll)->value('id');
                // dd($getEnroll);
                if ($getEnroll != null) {
                    $students = Student::where(['enroll_master_id' => $getEnroll])->select('user_name_id', 'name', 'register_no')->get();
                    return response()->json(['data' => $students, 'class' => $getEnroll, 'status' => true]);
                } else {
                    return response()->json(['data' => 'Class Details Not Found', 'status' => false]);
                }
            } else {
                return response()->json(['data' => 'Course Details Not Found', 'status' => false]);
            }

        } else {
            return response()->json(['data' => 'Required Details Not Found', 'status' => false]);
        }
    }

    public function getSections(Request $request)
    {
        if (isset($request->course)) {
            $sections = Section::where(['course_id' => $request->course])->select('id', 'section')->get();
            return response()->json(['data' => $sections, 'status' => true]);
        } else {
            return response()->json(['data' => 'Required Details Not Found', 'status' => false]);
        }
    }

}
