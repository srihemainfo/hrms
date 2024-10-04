<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyToolsDegreeTypeRequest;
use App\Http\Requests\StoreToolsDegreeTypeRequest;
use App\Http\Requests\UpdateToolsDegreeTypeRequest;
use App\Models\ToolsDegreeType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;
use App\Models\Year;
use Carbon\Carbon;

class ToolsDegreeTypeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ToolsDegreeType::query()->select(sprintf('%s.*', (new ToolsDegreeType)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewDegreeType';
                $editFunct = 'editDegreeType';
                $deleteFunct = 'deleteDegreeType';
                $viewGate      = 'tools_degree_type_show';
                $editGate      = 'tools_degree_type_edit';
                $deleteGate    = 'tools_degree_type_delete';
                $crudRoutePart = 'tools-degree-types';

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

        return view('admin.toolsDegreeTypes.index');
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
                $store = ToolsDegreeType::create([
                    'name' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'DegreeType Created']);
            } else {
                $update = ToolsDegreeType::where(['id' => $request->id])->update([
                    'name' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'DegreeType Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'DegreeType Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = ToolsDegreeType::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = ToolsDegreeType::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(UpdateReligionRequest $request, ToolsDegreeType $ToolsDegreeType)
    {
        $ToolsDegreeType->update($request->all());

        return redirect()->route('admin.religions.index');
    }

    public function show(ToolsDegreeType $ToolsDegreeType)
    {
        abort_if(Gate::denies('religion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.religions.show', compact('ToolsDegreeType'));
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = ToolsDegreeType::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'DegreeType Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $ToolsDegreeType = ToolsDegreeType::find(request('ids'));

        foreach ($ToolsDegreeType as $r) {
            $r->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'DegreeType Deleted Successfully']);
    }
}
