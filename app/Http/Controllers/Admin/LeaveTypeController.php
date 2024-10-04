<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyLeaveTypeRequest;
use App\Http\Requests\StoreLeaveTypeRequest;
use App\Http\Requests\UpdateLeaveTypeRequest;
use App\Models\LeaveType;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class LeaveTypeController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = LeaveType::query()->select(sprintf('%s.*', (new LeaveType)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewLeaveType';
                $editFunct = 'editLeaveType';
                $deleteFunct = 'deleteLeaveType';
                $viewGate      = 'staff_biometric_show';
                $editGate      = 'staff_biometric_edit';
                $deleteGate    = 'staff_biometric_delete';
                $crudRoutePart = 'leave-types';

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

        return view('admin.leaveTypes.index');
    }

    public function create()
    {
        abort_if(Gate::denies('community_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.motherTongues.index');
    }

    public function store(Request $request)
    {
        if (isset($request->name)) {
            if ($request->id == '') {
                $store = LeaveType::create([
                    'name' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'LeaveType Created']);
            } else {
                $update = LeaveType::where(['id' => $request->id])->update([
                    'name' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'LeaveType Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'LeaveType Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = LeaveType::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = LeaveType::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(UpdateCommunityRequest $request, LeaveType $LeaveType)
    {
        $LeaveType->update($request->all());

        return redirect()->route('admin.communities.index');
    }

    public function show(LeaveType $LeaveType)
    {
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = LeaveType::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'LeaveType Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $LeaveType = LeaveType::find(request('ids'));

        foreach ($LeaveType as $l) {
            $l->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'LeaveType Deleted Successfully']);
    }
}
