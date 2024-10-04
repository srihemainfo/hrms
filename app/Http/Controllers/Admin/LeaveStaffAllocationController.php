<?php

namespace App\Http\Controllers\Admin;

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
use Yajra\DataTables\Facades\DataTables;

class LeaveStaffAllocationController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('leave_staff_allocation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = LeaveStaffAllocation::with(['user', 'academic_year'])->select(sprintf('%s.*', (new LeaveStaffAllocation)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'leave_staff_allocation_show';
                $editGate      = 'leave_staff_allocation_edit';
                $deleteGate    = 'leave_staff_allocation_delete';
                $crudRoutePart = 'leave-staff-allocations';

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
            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->editColumn('user.email', function ($row) {
                return $row->user ? (is_string($row->user) ? $row->user : $row->user->email) : '';
            });
            $table->editColumn('user.email_verified_at', function ($row) {
                return $row->user ? (is_string($row->user) ? $row->user : $row->user->email_verified_at) : '';
            });
            $table->addColumn('academic_year_name', function ($row) {
                return $row->academic_year ? $row->academic_year->name : '';
            });

            $table->editColumn('academic_year.from', function ($row) {
                return $row->academic_year ? (is_string($row->academic_year) ? $row->academic_year : $row->academic_year->from) : '';
            });
            $table->editColumn('academic_year.to', function ($row) {
                return $row->academic_year ? (is_string($row->academic_year) ? $row->academic_year : $row->academic_year->to) : '';
            });
            $table->editColumn('no_of_leave', function ($row) {
                return $row->no_of_leave ? $row->no_of_leave : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user', 'academic_year']);

            return $table->make(true);
        }

        return view('admin.leaveStaffAllocations.index');
    }

    public function create()
    {
        abort_if(Gate::denies('leave_staff_allocation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $academic_years = AcademicYear::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.leaveStaffAllocations.create', compact('academic_years', 'users'));
    }

    public function store(StoreLeaveStaffAllocationRequest $request)
    {
        $leaveStaffAllocation = LeaveStaffAllocation::create($request->all());

        return redirect()->route('admin.leave-staff-allocations.index');
    }

    public function edit(LeaveStaffAllocation $leaveStaffAllocation)
    {
        abort_if(Gate::denies('leave_staff_allocation_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $academic_years = AcademicYear::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $leaveStaffAllocation->load('user', 'academic_year');

        return view('admin.leaveStaffAllocations.edit', compact('academic_years', 'leaveStaffAllocation', 'users'));
    }

    public function update(UpdateLeaveStaffAllocationRequest $request, LeaveStaffAllocation $leaveStaffAllocation)
    {
        $leaveStaffAllocation->update($request->all());

        return redirect()->route('admin.leave-staff-allocations.index');
    }

    public function show(LeaveStaffAllocation $leaveStaffAllocation)
    {
        abort_if(Gate::denies('leave_staff_allocation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $leaveStaffAllocation->load('user', 'academic_year');

        return view('admin.leaveStaffAllocations.show', compact('leaveStaffAllocation'));
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
