<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeaveStaffAllocationRequest;
use App\Http\Requests\UpdateLeaveStaffAllocationRequest;
use App\Http\Resources\Admin\LeaveStaffAllocationResource;
use App\Models\LeaveStaffAllocation;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LeaveStaffAllocationApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('leave_staff_allocation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LeaveStaffAllocationResource(LeaveStaffAllocation::with(['user', 'academic_year'])->get());
    }

    public function store(StoreLeaveStaffAllocationRequest $request)
    {
        $leaveStaffAllocation = LeaveStaffAllocation::create($request->all());

        return (new LeaveStaffAllocationResource($leaveStaffAllocation))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(LeaveStaffAllocation $leaveStaffAllocation)
    {
        abort_if(Gate::denies('leave_staff_allocation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LeaveStaffAllocationResource($leaveStaffAllocation->load(['user', 'academic_year']));
    }

    public function update(UpdateLeaveStaffAllocationRequest $request, LeaveStaffAllocation $leaveStaffAllocation)
    {
        $leaveStaffAllocation->update($request->all());

        return (new LeaveStaffAllocationResource($leaveStaffAllocation))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(LeaveStaffAllocation $leaveStaffAllocation)
    {
        abort_if(Gate::denies('leave_staff_allocation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $leaveStaffAllocation->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
