<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\FeeComponents;
use App\Models\Semester;
use App\Models\ToolsCourse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class feeComponentsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = FeeComponents::query()
                ->leftJoin('batches', 'batches.id', '=', 'fee_components.batch_id')
                ->leftJoin('semesters', 'semesters.id', '=', 'fee_components.semester_id')
                ->leftJoin('tools_courses', 'tools_courses.id', '=', 'fee_components.course_id')
                ->whereNull('fee_components.deleted_at')
                ->select([
                    'fee_components.*',
                    'batches.name as batch',
                    'tools_courses.short_form as course',
                    'semesters.semester as sem',
                ]);

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'year_show';
                $viewFunct = 'viewFeeComp';
                $editGate = 'year_edit';
                $editFunct = 'editFeeComp';
                $deleteGate = 'year_delete';
                $deleteFunct = 'deleteFeeComp';
                $crudRoutePart = 'fee-components';

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

            $table->editColumn('batch', function ($row) {
                return $row->batch ? $row->batch : '';
            });

            $table->editColumn('course', function ($row) {
                return $row->course ? $row->course : '';
            });

            $table->editColumn('semester', function ($row) {
                return $row->sem ? $row->sem : '';
            });

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            return $table->make(true);
        }

        $course = ToolsCourse::pluck('short_form', 'id');
        $batch = Batch::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');

        return view('admin.feeComponents.index', compact('course', 'batch', 'semester'));
    }

    public function store(Request $request)
    {

        if (isset($request->fee_components)) {
            if ($request->id == '') {
                $store = FeeComponents::create([
                    'name' => $request->fee_components,
                    'course_id' => $request->course,
                    'batch_id' => $request->applied_batch,
                    'semester_id' => $request->semester,

                ]);
                return response()->json(['status' => true, 'data' => 'Fee Components Created']);
            } else {
                $update = FeeComponents::where(['id' => $request->id])->update([
                    'name' => $request->fee_components,
                    'course_id' => $request->course,
                    'batch_id' => $request->applied_batch,
                    'semester_id' => $request->semester,
                ]);
                return response()->json(['status' => true, 'data' => 'Fee Components Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Fee Components Not Updated']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = FeeComponents::where('fee_components.id', $request->id)->first();
            
            return response()->json(['status' => true, 'data' => $data]);

        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = FeeComponents::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Fees Components Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function edit(Request $request)
    {

        if (isset($request->id)) {
            $data = FeeComponents::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

}
