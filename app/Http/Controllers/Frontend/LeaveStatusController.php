<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\StoreLeaveStatusRequest;
use App\Http\Requests\UpdateLeaveStatusRequest;
use App\Models\LeaveStatus;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LeaveStatusController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('leave_status_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $leaveStatuses = LeaveStatus::all();

        return view('frontend.leaveStatuses.index', compact('leaveStatuses'));
    }

    public function create()
    {
        abort_if(Gate::denies('leave_status_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.leaveStatuses.create');
    }

    public function store(StoreLeaveStatusRequest $request)
    {
        $leaveStatus = LeaveStatus::create($request->all());

        return redirect()->route('frontend.leave-statuses.index');
    }

    public function edit(LeaveStatus $leaveStatus)
    {
        abort_if(Gate::denies('leave_status_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.leaveStatuses.edit', compact('leaveStatus'));
    }

    public function update(UpdateLeaveStatusRequest $request, LeaveStatus $leaveStatus)
    {
        $leaveStatus->update($request->all());

        return redirect()->route('frontend.leave-statuses.index');
    }

    public function show(LeaveStatus $leaveStatus)
    {
        abort_if(Gate::denies('leave_status_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.leaveStatuses.show', compact('leaveStatus'));
    }
}
