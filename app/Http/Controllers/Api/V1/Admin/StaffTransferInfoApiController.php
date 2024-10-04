<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffTransferInfoRequest;
use App\Http\Requests\UpdateStaffTransferInfoRequest;
use App\Http\Resources\Admin\StaffTransferInfoResource;
use App\Models\StaffTransferInfo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffTransferInfoApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('staff_transfer_info_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new StaffTransferInfoResource(StaffTransferInfo::with(['enroll_master'])->get());
    }

    public function store(StoreStaffTransferInfoRequest $request)
    {
        $staffTransferInfo = StaffTransferInfo::create($request->all());

        return (new StaffTransferInfoResource($staffTransferInfo))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(StaffTransferInfo $staffTransferInfo)
    {
        abort_if(Gate::denies('staff_transfer_info_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new StaffTransferInfoResource($staffTransferInfo->load(['enroll_master']));
    }

    public function update(UpdateStaffTransferInfoRequest $request, StaffTransferInfo $staffTransferInfo)
    {
        $staffTransferInfo->update($request->all());

        return (new StaffTransferInfoResource($staffTransferInfo))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(StaffTransferInfo $staffTransferInfo)
    {
        abort_if(Gate::denies('staff_transfer_info_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staffTransferInfo->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
