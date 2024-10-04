<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradeMaster;
use App\Models\ToolssyllabusYear;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GradeMasterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = GradeMaster::query()->select(sprintf('%s.*', (new GradeMaster)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewGrade';
                $editFunct = 'editGrade';
                $deleteFunct = 'deleteGrade';
                $viewGate      = 'grade_master_show';
                $editGate      = 'grade_master_edit';
                $deleteGate    = 'grade_master_delete';
                $crudRoutePart = 'grade_master';

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
            $table->editColumn('grade', function ($row) {
                return $row->grade_letter ? $row->grade_letter : '';
            });
            $table->editColumn('grade_point', function ($row) {
                return $row->grade_point ? $row->grade_point : '';
            });
            $table->editColumn('result', function ($row) {
                return $row->result ? $row->result : '';
            });

            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }

        return view('admin.gradeMaster.index');
    }

    public function create()
    {

        return view('admin.motherTongues.index');
    }

    public function store(Request $request)
    {
        if (isset($request->grade)) {
            if ($request->id == '') {
                $store = GradeMaster::create([
                    'grade_letter' => strtoupper($request->grade),
                    'grade_point' => strtoupper($request->point),
                    'result' => strtoupper($request->result),
                ]);
                return response()->json(['status' => true, 'data' => 'GradeMaster Created']);
            } else {
                $update = GradeMaster::where(['id' => $request->id])->update([
                    'grade_letter' => strtoupper($request->grade),
                    'grade_point' => strtoupper($request->point),
                    'result' => strtoupper($request->result),
                ]);
                return response()->json(['status' => true, 'data' => 'GradeMaster Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'GradeMaster Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = GradeMaster::where(['id' => $request->id])->select('id', 'grade_letter', 'grade_point', 'result')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = GradeMaster::where(['id' => $request->id])->select('id', 'grade_letter', 'grade_point', 'result')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(UpdateCommunityRequest $request, GradeMaster $GradeMaster)
    {
        $GradeMaster->update($request->all());

        return redirect()->route('admin.communities.index');
    }

    public function show(GradeMaster $GradeMaster)
    {
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = GradeMaster::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'GradeMaster Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $GradeMaster = GradeMaster::find(request('ids'));

        foreach ($GradeMaster as $g) {
            $g->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'GradeMaster Deleted Successfully']);
    }

}
