<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHrmRequestPermissionRequest;
use App\Http\Requests\UpdateHrmRequestPermissionRequest;
use App\Http\Resources\Admin\HrmRequestPermissionResource;
use App\Models\HrmRequestPermission;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HrmRequestPermissionApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('hrm_request_permission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new HrmRequestPermissionResource(HrmRequestPermission::with(['user'])->get());
    }

    public function store(StoreHrmRequestPermissionRequest $request)
    {
        $hrmRequestPermission = HrmRequestPermission::create($request->all());

        return (new HrmRequestPermissionResource($hrmRequestPermission))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(HrmRequestPermission $hrmRequestPermission)
    {
        abort_if(Gate::denies('hrm_request_permission_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new HrmRequestPermissionResource($hrmRequestPermission->load(['user']));
    }

    public function update(UpdateHrmRequestPermissionRequest $request, HrmRequestPermission $hrmRequestPermission)
    {
        $hrmRequestPermission->update($request->all());

        return (new HrmRequestPermissionResource($hrmRequestPermission))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(HrmRequestPermission $hrmRequestPermission)
    {
        abort_if(Gate::denies('hrm_request_permission_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hrmRequestPermission->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
