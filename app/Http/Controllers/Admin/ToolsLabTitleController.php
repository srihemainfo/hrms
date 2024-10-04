<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLabExamTitleRequest;
use App\Models\Tools_Labmark_Title;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;

class ToolsLabTitleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Tools_Labmark_Title::query()->select(sprintf('%s.*', (new Tools_Labmark_Title)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewLab';
                $editFunct = 'editLab';
                $deleteFunct = 'deleteLab';
                $viewGate      = 'lab_title_show';
                $editGate      = 'lab_title_edit';
                $deleteGate    = 'lab_title_delete';
                $crudRoutePart = 'lab_title';

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

        return view('admin.toolsLabTitles.index');
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
                $store = Tools_Labmark_Title::create([
                    'name' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'Lab Title Created']);
            } else {
                $update = Tools_Labmark_Title::where(['id' => $request->id])->update([
                    'name' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'Lab Title Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Lab Title Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = Tools_Labmark_Title::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = Tools_Labmark_Title::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(UpdateReligionRequest $request, $Tools_Labmark_Title)
    {
        $Tools_Labmark_Title->update($request->all());

        return redirect()->route('admin.religions.index');
    }

    public function show($Tools_Labmark_Title)
    {
        abort_if(Gate::denies('religion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.religions.show', compact('Tools_Labmark_Title'));
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = Tools_Labmark_Title::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Lab Title Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $Lab= Tools_Labmark_Title::find(request('ids'));

        foreach ($Lab as $r) {
            $r->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'Lab Title Deleted Successfully']);
    }
}
