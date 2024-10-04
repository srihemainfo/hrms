<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeaveStatusRequest;
use App\Http\Requests\UpdateLeaveStatusRequest;
use App\Http\Resources\Admin\LeaveStatusResource;
use App\Models\LeaveStatus;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LeaveStatusApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('leave_status_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LeaveStatusResource(LeaveStatus::all());
    }

    public function store(StoreLeaveStatusRequest $request)
    {
        $leaveStatus = LeaveStatus::create($request->all());

        return (new LeaveStatusResource($leaveStatus))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(LeaveStatus $leaveStatus)
    {
        abort_if(Gate::denies('leave_status_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LeaveStatusResource($leaveStatus);
    }

    public function update(UpdateLeaveStatusRequest $request, LeaveStatus $leaveStatus)
    {
        $leaveStatus->update($request->all());

        return (new LeaveStatusResource($leaveStatus))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
