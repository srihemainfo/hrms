<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyLeaveTypeRequest;
use App\Http\Requests\StoreLeaveTypeRequest;
use App\Http\Requests\UpdateLeaveTypeRequest;
use App\Models\LeaveType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LeaveTypeController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('leave_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $leaveTypes = LeaveType::all();

        return view('frontend.leaveTypes.index', compact('leaveTypes'));
    }

    public function create()
    {
        abort_if(Gate::denies('leave_type_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.leaveTypes.create');
    }

    public function store(StoreLeaveTypeRequest $request)
    {
        $leaveType = LeaveType::create($request->all());

        return redirect()->route('frontend.leave-types.index');
    }

    public function edit(LeaveType $leaveType)
    {
        abort_if(Gate::denies('leave_type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.leaveTypes.edit', compact('leaveType'));
    }

    public function update(UpdateLeaveTypeRequest $request, LeaveType $leaveType)
    {
        $leaveType->update($request->all());

        return redirect()->route('frontend.leave-types.index');
    }

    public function show(LeaveType $leaveType)
    {
        abort_if(Gate::denies('leave_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.leaveTypes.show', compact('leaveType'));
    }

    public function destroy(LeaveType $leaveType)
    {
        abort_if(Gate::denies('leave_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $leaveType->delete();

        return back();
    }

    public function massDestroy(MassDestroyLeaveTypeRequest $request)
    {
        $leaveTypes = LeaveType::find(request('ids'));

        foreach ($leaveTypes as $leaveType) {
            $leaveType->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
