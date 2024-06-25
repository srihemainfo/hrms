<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOdMasterRequest;
use App\Http\Requests\UpdateOdMasterRequest;
use App\Http\Resources\Admin\OdMasterResource;
use App\Models\OdMaster;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OdMasterApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('od_master_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OdMasterResource(OdMaster::all());
    }

    public function store(StoreOdMasterRequest $request)
    {
        $odMaster = OdMaster::create($request->all());

        return (new OdMasterResource($odMaster))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(OdMaster $odMaster)
    {
        abort_if(Gate::denies('od_master_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OdMasterResource($odMaster);
    }

    public function update(UpdateOdMasterRequest $request, OdMaster $odMaster)
    {
        $odMaster->update($request->all());

        return (new OdMasterResource($odMaster))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(OdMaster $odMaster)
    {
        abort_if(Gate::denies('od_master_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $odMaster->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
