<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\SubjectType;
use Illuminate\Http\Request;
use App\Models\ToolssyllabusYear;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\MassDestroySubjectTypeRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubjectTypeController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query= DB::table('subject_types')
                    ->leftJoin('toolssyllabus_years', 'toolssyllabus_years.id','=', 'subject_types.regulation_id')
                    ->whereNull('subject_types.deleted_at')
                    ->select('subject_types.name', 'toolssyllabus_years.name as regulation_id', 'subject_types.id')
                    ->get();
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewSub_type';
                $editFunct = 'editSub_type';
                $deleteFunct = 'deleteSub_type';
                $viewGate      = 'subject_type_show';
                $editGate      = 'subject_type_edit';
                $deleteGate    = 'subject_type_delete';
                $crudRoutePart = 'subject_types';

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
            $table->editColumn('regulation', function ($row) {
                return $row->regulation_id ? $row->regulation_id : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }

        $reg= ToolssyllabusYear::pluck('name', 'id');
        return view('admin.subjectTypes.index', compact('reg'));
    }

    public function create()
    {

        return view('admin.motherTongues.index');
    }

    public function store(Request $request)
    {
        // dd($request);
        if (isset($request->name)) {
            if ($request->id == '') {
                $store = SubjectType::create([
                    'regulation_id' => $request->regulation,
                    'name' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'SubjectType Created']);
            } else {
                $update = SubjectType::where(['id' => $request->id])->update([
                    'regulation_id' => $request->regulation,
                    'name' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'SubjectType Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'SubjectType Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = SubjectType::where(['id' => $request->id])->select('id', 'regulation_id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = SubjectType::where(['id' => $request->id])->select('id', 'regulation_id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(UpdateCommunityRequest $request, SubjectType $SubjectType)
    {
        $SubjectType->update($request->all());

        return redirect()->route('admin.communities.index');
    }

    public function show(SubjectType $SubjectType)
    {
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = SubjectType::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'SubjectType Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $SubjectType = SubjectType::find(request('ids'));

        foreach ($SubjectType as $g) {
            $g->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'SubjectTypes are Deleted Successfully']);
    }

}
