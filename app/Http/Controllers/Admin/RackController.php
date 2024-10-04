<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RackModel;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

class RackController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('library_rack_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = RackModel::select(sprintf('%s.*', (new RackModel)->table))->get();
            $table = DataTables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewRack';
                $editFunct = 'editRack';
                $deleteFunct = 'deleteRack';
                $viewGate = 'library_rack_show';
                $editGate = 'library_rack_edit';
                $deleteGate = 'library_rack_delete';
                $crudRoutePart = 'rack';

                return view(
                    'partials.ajaxTableActions',
                    compact(
                        'viewGate',
                        'editGate',
                        'deleteGate',
                        'crudRoutePart',
                        'viewFunct',
                        'editFunct',
                        'deleteFunct',
                        'row'
                    )
                );
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('rack', function ($row) {
                return $row->rack_no ? $row->rack_no : '';
            });
            $table->editColumn('row', function ($row) {
                return $row->row_no ? $row->row_no : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.rack.index');


    }
    public function store(Request $request)
    {
        abort_if(Gate::denies('library_rack_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->id == '') {
            $rack = new RackModel();
            $rack = RackModel::where('rack_no', 'RACK ' . $request->rack)->get();
            if ($rack) {
                $concat_rack = 'RACK ';
                $concat_row = 'ROW  ';
                $row = count($rack) + 1;
                $create_room = new RackModel();
                if ($request->row != '') {
                    for ($i = 1; $i <= $request->row; $i++) {
                        $create_room->create([
                            'rack_no' => $concat_rack . $request->rack,
                            'row_no' => $concat_row . $row,
                        ]);
                        $row += 1;
                    }
                    return response()->json(['status' => true, 'data' => 'Library Rack and Rows Created']);
                } else {
                    return response()->json(['status' => false, 'data' => 'Row Not Entered']);
                }

            }
        } else {
            $count = RackModel::where(['id' => $request->id])->count();
            if ($count > 0) {
                $concat_rack = 'RACK ';
                $concat_row = 'ROW  ';
                $update = RackModel::where('id', '=', $request->id)->update([
                    'rack_no' => $concat_rack . $request->rack,
                    'row_no' => $concat_row . $request->row,
                ]);
                return response()->json(['status' => true, 'data' => "Rack and Row Updated"]);
            } else {
                return response()->json(['status' => false, 'data' => 'Rack is Not Available']);
            }
        }

    }
    public function view(Request $request)
    {
        abort_if(Gate::denies('library_rack_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $data = RackModel::where(['id' => $request->id])->select('id', 'rack_no', 'row_no')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }
    public function edit(Request $request)
    {
        abort_if(Gate::denies('library_rack_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $data = RackModel::where(['id' => $request->id])->select('id', 'rack_no', 'row_no')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }
    public function destroy(Request $request)
    {
        abort_if(Gate::denies('library_rack_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $delete = RackModel::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => "Rack's Row Successfully"]);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }
    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('library_rack_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $RackModel = RackModel::find(request('ids'));

        foreach ($RackModel as $r) {
            $r->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'Hostels Deleted Successfully']);
    }
}
