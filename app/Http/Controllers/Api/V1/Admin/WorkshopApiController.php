<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkshopRequest;
use App\Http\Requests\UpdateWorkshopRequest;
use App\Http\Resources\Admin\WorkshopResource;
use App\Models\Workshop;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkshopApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('workshop_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WorkshopResource(Workshop::with(['user_name'])->get());
    }

    public function store(StoreWorkshopRequest $request)
    {
        $workshop = Workshop::create($request->all());

        return (new WorkshopResource($workshop))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Workshop $workshop)
    {
        abort_if(Gate::denies('workshop_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WorkshopResource($workshop->load(['user_name']));
    }

    public function update(UpdateWorkshopRequest $request, Workshop $workshop)
    {
        $workshop->update($request->all());

        return (new WorkshopResource($workshop))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Workshop $workshop)
    {
        abort_if(Gate::denies('workshop_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workshop->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
