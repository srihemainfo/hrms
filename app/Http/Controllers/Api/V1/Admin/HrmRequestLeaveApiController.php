<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHrmRequestLeafRequest;
use App\Http\Requests\UpdateHrmRequestLeafRequest;
use App\Http\Resources\Admin\HrmRequestLeafResource;
use App\Models\HrmRequestLeaf;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HrmRequestLeaveApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('hrm_request_leaf_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new HrmRequestLeafResource(HrmRequestLeaf::with(['user'])->get());
    }

    public function store(StoreHrmRequestLeafRequest $request)
    {
        $hrmRequestLeaf = HrmRequestLeaf::create($request->all());

        return (new HrmRequestLeafResource($hrmRequestLeaf))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(HrmRequestLeaf $hrmRequestLeaf)
    {
        abort_if(Gate::denies('hrm_request_leaf_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new HrmRequestLeafResource($hrmRequestLeaf->load(['user']));
    }

    public function update(UpdateHrmRequestLeafRequest $request, HrmRequestLeaf $hrmRequestLeaf)
    {
        $hrmRequestLeaf->update($request->all());

        return (new HrmRequestLeafResource($hrmRequestLeaf))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(HrmRequestLeaf $hrmRequestLeaf)
    {
        abort_if(Gate::denies('hrm_request_leaf_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hrmRequestLeaf->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
