<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeaveTypeRequest;
use App\Http\Requests\UpdateLeaveTypeRequest;
use App\Http\Resources\Admin\LeaveTypeResource;
use App\Models\LeaveType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LeaveTypeApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('leave_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LeaveTypeResource(LeaveType::all());
    }

    public function store(StoreLeaveTypeRequest $request)
    {
        $leaveType = LeaveType::create($request->all());

        return (new LeaveTypeResource($leaveType))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(LeaveType $leaveType)
    {
        abort_if(Gate::denies('leave_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LeaveTypeResource($leaveType);
    }

    public function update(UpdateLeaveTypeRequest $request, LeaveType $leaveType)
    {
        $leaveType->update($request->all());

        return (new LeaveTypeResource($leaveType))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(LeaveType $leaveType)
    {
        abort_if(Gate::denies('leave_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $leaveType->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
