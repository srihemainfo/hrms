<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\StoreLeaveStatusRequest;
use App\Http\Requests\UpdateLeaveStatusRequest;
use App\Models\LeaveStatus;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class LeaveStatusController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('leave_status_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = LeaveStatus::query()->select(sprintf('%s.*', (new LeaveStatus)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'leave_status_show';
                $editGate      = 'leave_status_edit';
                $deleteGate    = 'leave_status_delete';
                $crudRoutePart = 'leave-statuses';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
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

        return view('admin.leaveStatuses.index');
    }

    public function create()
    {
        abort_if(Gate::denies('leave_status_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.leaveStatuses.create');
    }

    public function store(StoreLeaveStatusRequest $request)
    {
        $leaveStatus = LeaveStatus::create($request->all());

        return redirect()->route('admin.leave-statuses.index');
    }

    public function edit(LeaveStatus $leaveStatus)
    {
        abort_if(Gate::denies('leave_status_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.leaveStatuses.edit', compact('leaveStatus'));
    }

    public function update(UpdateLeaveStatusRequest $request, LeaveStatus $leaveStatus)
    {
        $leaveStatus->update($request->all());

        return redirect()->route('admin.leave-statuses.index');
    }

    public function show(LeaveStatus $leaveStatus)
    {
        abort_if(Gate::denies('leave_status_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.leaveStatuses.show', compact('leaveStatus'));
    }
}
