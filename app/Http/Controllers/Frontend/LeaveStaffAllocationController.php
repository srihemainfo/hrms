<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyLeaveStaffAllocationRequest;
use App\Http\Requests\StoreLeaveStaffAllocationRequest;
use App\Http\Requests\UpdateLeaveStaffAllocationRequest;
use App\Models\AcademicYear;
use App\Models\LeaveStaffAllocation;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LeaveStaffAllocationController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('leave_staff_allocation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $leaveStaffAllocations = LeaveStaffAllocation::with(['user', 'academic_year'])->get();

        return view('frontend.leaveStaffAllocations.index', compact('leaveStaffAllocations'));
    }

    public function create()
    {
        abort_if(Gate::denies('leave_staff_allocation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $academic_years = AcademicYear::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.leaveStaffAllocations.create', compact('academic_years', 'users'));
    }

    public function store(StoreLeaveStaffAllocationRequest $request)
    {
        $leaveStaffAllocation = LeaveStaffAllocation::create($request->all());

        return redirect()->route('frontend.leave-staff-allocations.index');
    }

    public function edit(LeaveStaffAllocation $leaveStaffAllocation)
    {
        abort_if(Gate::denies('leave_staff_allocation_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $academic_years = AcademicYear::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $leaveStaffAllocation->load('user', 'academic_year');

        return view('frontend.leaveStaffAllocations.edit', compact('academic_years', 'leaveStaffAllocation', 'users'));
    }

    public function update(UpdateLeaveStaffAllocationRequest $request, LeaveStaffAllocation $leaveStaffAllocation)
    {
        $leaveStaffAllocation->update($request->all());

        return redirect()->route('frontend.leave-staff-allocations.index');
    }

    public function show(LeaveStaffAllocation $leaveStaffAllocation)
    {
        abort_if(Gate::denies('leave_staff_allocation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $leaveStaffAllocation->load('user', 'academic_year');

        return view('frontend.leaveStaffAllocations.show', compact('leaveStaffAllocation'));
    }

    public function destroy(LeaveStaffAllocation $leaveStaffAllocation)
    {
        abort_if(Gate::denies('leave_staff_allocation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $leaveStaffAllocation->delete();

        return back();
    }

    public function massDestroy(MassDestroyLeaveStaffAllocationRequest $request)
    {
        $leaveStaffAllocations = LeaveStaffAllocation::find(request('ids'));

        foreach ($leaveStaffAllocations as $leaveStaffAllocation) {
            $leaveStaffAllocation->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
