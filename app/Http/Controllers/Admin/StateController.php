<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class StateController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $query = State::select(sprintf('%s.*', (new State)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'state_show';
                $editGate = 'state_edit';
                $deleteGate = 'state_delete';
                $editFunct = 'editState';
                $viewFunct = 'viewState';
                $deleteFunct = 'deleteState';
                $crudRoutePart = 'State';

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

        return view('admin.state.index');
    }

    public function store(Request $request)
    {

        if (isset($request->state)) {
            if ($request->id == '') {
                $count = State::where(['name' => $request->state])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'State Already Exist.']);
                } else {
                    $store = State::create([
                        'name' => $request->state,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'State Created']);
            } else {
                $count = State::whereNotIn('id', [$request->id])->where(['name' => $request->state])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'State Already Exist.']);
                } else {
                    $update = State::where(['id' => $request->id])->update([
                        'name' => $request->state,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'State Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'State Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = State::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }


    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = State::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = State::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'State Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $sections = State::find(request('ids'));

        foreach ($sections as $section) {
            $section->delete();
        }

        return response()->json(['status' => 'success', 'data' => 'State Deleted Successfully']);
    }

}
