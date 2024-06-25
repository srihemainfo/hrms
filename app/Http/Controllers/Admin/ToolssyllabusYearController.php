<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyToolssyllabusYearRequest;
use App\Models\ToolssyllabusYear;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Year;
use Carbon\Carbon;

class ToolssyllabusYearController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ToolssyllabusYear::query()->select(sprintf('%s.*', (new ToolssyllabusYear)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewRegulation';
                $editFunct = 'editRegulation';
                $deleteFunct = 'deleteRegulation';
                $viewGate      = 'toolssyllabus_year_show';
                $editGate      = 'toolssyllabus_year_edit';
                $deleteGate    = 'toolssyllabus_year_delete';
                $crudRoutePart = 'toolssyllabus-years';

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
            $table->editColumn('year', function ($row) {
                return $row->year ? $row->year : '';
            });
            $table->editColumn('frame_by', function ($row) {
                return $row->frame_by ? $row->frame_by : '';
            });

            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }

        return view('admin.toolssyllabusYears.index');
    }

    public function create()
    {

        return view('admin.motherTongues.index');
    }

    public function store(Request $request)
    {
        if (isset($request->regulation)) {
            if ($request->id == '') {
                $store = ToolssyllabusYear::create([
                    'name' => strtoupper($request->regulation),
                    'year' => strtoupper($request->from),
                    'frame_by' => strtoupper($request->frame_by),
                ]);
                return response()->json(['status' => true, 'data' => 'Regulation Created']);
            } else {
                $update = ToolssyllabusYear::where(['id' => $request->id])->update([
                    'name' => strtoupper($request->regulation),
                    'year' => strtoupper($request->from),
                    'frame_by' => strtoupper($request->frame_by),
                ]);
                return response()->json(['status' => true, 'data' => 'Regulation Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Regulation Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = ToolssyllabusYear::where(['id' => $request->id])->select('id', 'name', 'year', 'frame_by')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = ToolssyllabusYear::where(['id' => $request->id])->select('id', 'name', 'year', 'frame_by')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(UpdateCommunityRequest $request, ToolssyllabusYear $ToolssyllabusYear)
    {
        $ToolssyllabusYear->update($request->all());

        return redirect()->route('admin.communities.index');
    }

    public function show(ToolssyllabusYear $ToolssyllabusYear)
    {
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = ToolssyllabusYear::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Regulation Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $ToolssyllabusYear = ToolssyllabusYear::find(request('ids'));

        foreach ($ToolssyllabusYear as $t) {
            $t->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'Regulation Deleted Successfully']);
    }


}
