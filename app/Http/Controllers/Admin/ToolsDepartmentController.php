<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyToolsDepartmentRequest;
use App\Http\Requests\StoreToolsDepartmentRequest;
use App\Http\Requests\UpdateToolsDepartmentRequest;
use App\Models\ToolsDepartment;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ToolsDepartmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ToolsDepartment::query()->select(sprintf('%s.*', (new ToolsDepartment)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewDepart';
                $editFunct = 'editDepart';
                $deleteFunct = 'deleteDepart';
                $viewGate      = 'tools_department_show';
                $editGate      = 'tools_department_edit';
                $deleteGate    = 'tools_department_delete';
                $crudRoutePart = 'tools-departments';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }

        return view('admin.toolsDepartments.index');
    }

    public function create()
    {
        abort_if(Gate::denies('religion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.religions.create');
    }

    public function store(Request $request)
    {
        if (isset($request->name)) {
            if ($request->id == '') {
                $store = ToolsDepartment::create([
                    'name' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'Department Created']);
            } else {
                $update = ToolsDepartment::where(['id' => $request->id])->update([
                    'name' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'Department Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'ToolsDepartment Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = ToolsDepartment::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = ToolsDepartment::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(UpdateReligionRequest $request, ToolsDepartment $ToolsDepartment)
    {
        $ToolsDepartment->update($request->all());

        return redirect()->route('admin.religions.index');
    }

    public function show(ToolsDepartment $ToolsDepartment)
    {
        abort_if(Gate::denies('religion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.religions.show', compact('ToolsDepartment'));
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = ToolsDepartment::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Department Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $ToolsDepartment = ToolsDepartment::find(request('ids'));

        foreach ($ToolsDepartment as $r) {
            $r->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'Departments Deleted Successfully']);
    }
}
