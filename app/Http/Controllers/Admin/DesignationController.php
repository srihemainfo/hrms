<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DesignationController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $query = Designation::select(sprintf('%s.*', (new Designation)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'designation_show';
                $editGate = 'designation_edit';
                $deleteGate = 'designation_delete';
                $editFunct = 'editDesignation';
                $viewFunct = 'viewDesignation';
                $deleteFunct = 'deleteDesignation';
                $crudRoutePart = 'Designation';

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

        return view('admin.designation.index');
    }

    public function store(Request $request)
    {

        if (isset($request->designation)) {
            if ($request->id == '') {
                $count = Designation::where(['name' => $request->designation])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Designation Already Exist.']);
                } else {
                    $store = Designation::create([
                        'name' => $request->designation,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Designation Created']);
            } else {
                $count = Designation::whereNotIn('id', [$request->id])->where(['name' => $request->designation])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Designation Already Exist.']);
                } else {
                    $update = Designation::where(['id' => $request->id])->update([
                        'name' => $request->designation,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Designation Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Designation Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = Designation::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = Designation::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = Designation::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Designation Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $sections = Designation::find(request('ids'));

        foreach ($sections as $section) {
            $section->delete();
        }

        return response()->json(['status' => 'success', 'data' => 'Designation Deleted Successfully']);
    }
}
