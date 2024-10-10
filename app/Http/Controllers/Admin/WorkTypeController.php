<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WorkTypeController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $query = WorkType::select(sprintf('%s.*', (new WorkType)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'worktype_show';
                $editGate = 'worktype_edit';
                $deleteGate = 'worktype_delete';
                $editFunct = 'editWorktype';
                $viewFunct = 'viewWorktype';
                $deleteFunct = 'deleteWorktype';
                $crudRoutePart = 'Worktype';

                return view('partials.ajaxTableActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'editFunct',
                    'viewFunct',
                    'deleteFunct',
                    'crudRoutePart',
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

        return view('admin.worktype.index');
    }

    public function store(Request $request)
    {

        if (isset($request->worktype)) {
            if ($request->id == '') {
                $count = WorkType::where(['name' => $request->worktype])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Worktype Already Exist.']);
                } else {
                    $store = WorkType::create([
                        'name' => $request->worktype,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Worktype Created']);
            } else {
                $count = WorkType::whereNotIn('id', [$request->id])->where(['name' => $request->worktype])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Worktype Already Exist.']);
                } else {
                    $update = WorkType::where(['id' => $request->id])->update([
                        'name' => $request->worktype,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Worktype Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Worktype Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = WorkType::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = WorkType::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = WorkType::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Worktype Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $sections = WorkType::find(request('ids'));

        foreach ($sections as $section) {
            $section->delete();
        }

        return response()->json(['status' => 'success', 'data' => 'Worktype Deleted Successfully']);
    }

}
