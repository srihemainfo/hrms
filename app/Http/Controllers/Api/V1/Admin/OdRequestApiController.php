<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOdRequestRequest;
use App\Http\Requests\UpdateOdRequestRequest;
use App\Http\Resources\Admin\OdRequestResource;
use App\Models\OdRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OdRequestApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('od_request_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OdRequestResource(OdRequest::all());
    }

    public function store(StoreOdRequestRequest $request)
    {
        $odRequest = OdRequest::create($request->all());

        return (new OdRequestResource($odRequest))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(OdRequest $odRequest)
    {
        abort_if(Gate::denies('od_request_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OdRequestResource($odRequest);
    }

    public function update(UpdateOdRequestRequest $request, OdRequest $odRequest)
    {
        $odRequest->update($request->all());

        return (new OdRequestResource($odRequest))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(OdRequest $odRequest)
    {
        abort_if(Gate::denies('od_request_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $odRequest->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
