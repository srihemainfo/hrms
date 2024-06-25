<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyStaffTransferInfoRequest;
use App\Http\Requests\StoreStaffTransferInfoRequest;
use App\Http\Requests\UpdateStaffTransferInfoRequest;
use App\Models\CourseEnrollMaster;
use App\Models\StaffTransferInfo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffTransferInfoController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('staff_transfer_info_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staffTransferInfos = StaffTransferInfo::with(['enroll_master'])->get();

        return view('frontend.staffTransferInfos.index', compact('staffTransferInfos'));
    }

    public function create()
    {
        abort_if(Gate::denies('staff_transfer_info_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $enroll_masters = CourseEnrollMaster::pluck('enroll_master_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.staffTransferInfos.create', compact('enroll_masters'));
    }

    public function store(StoreStaffTransferInfoRequest $request)
    {
        $staffTransferInfo = StaffTransferInfo::create($request->all());

        return redirect()->route('frontend.staff-transfer-infos.index');
    }

    public function edit(StaffTransferInfo $staffTransferInfo)
    {
        abort_if(Gate::denies('staff_transfer_info_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $enroll_masters = CourseEnrollMaster::pluck('enroll_master_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $staffTransferInfo->load('enroll_master');

        return view('frontend.staffTransferInfos.edit', compact('enroll_masters', 'staffTransferInfo'));
    }

    public function update(UpdateStaffTransferInfoRequest $request, StaffTransferInfo $staffTransferInfo)
    {
        $staffTransferInfo->update($request->all());

        return redirect()->route('frontend.staff-transfer-infos.index');
    }

    public function show(StaffTransferInfo $staffTransferInfo)
    {
        abort_if(Gate::denies('staff_transfer_info_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staffTransferInfo->load('enroll_master');

        return view('frontend.staffTransferInfos.show', compact('staffTransferInfo'));
    }

    public function destroy(StaffTransferInfo $staffTransferInfo)
    {
        abort_if(Gate::denies('staff_transfer_info_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staffTransferInfo->delete();

        return back();
    }

    public function massDestroy(MassDestroyStaffTransferInfoRequest $request)
    {
        $staffTransferInfos = StaffTransferInfo::find(request('ids'));

        foreach ($staffTransferInfos as $staffTransferInfo) {
            $staffTransferInfo->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
