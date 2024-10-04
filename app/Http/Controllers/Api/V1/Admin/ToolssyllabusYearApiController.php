<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreToolssyllabusYearRequest;
use App\Http\Requests\UpdateToolssyllabusYearRequest;
use App\Http\Resources\Admin\ToolssyllabusYearResource;
use App\Models\ToolssyllabusYear;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ToolssyllabusYearApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('toolssyllabus_year_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ToolssyllabusYearResource(ToolssyllabusYear::all());
    }

    public function store(StoreToolssyllabusYearRequest $request)
    {
        $toolssyllabusYear = ToolssyllabusYear::create($request->all());

        return (new ToolssyllabusYearResource($toolssyllabusYear))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ToolssyllabusYear $toolssyllabusYear)
    {
        abort_if(Gate::denies('toolssyllabus_year_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ToolssyllabusYearResource($toolssyllabusYear);
    }

    public function update(UpdateToolssyllabusYearRequest $request, ToolssyllabusYear $toolssyllabusYear)
    {
        $toolssyllabusYear->update($request->all());

        return (new ToolssyllabusYearResource($toolssyllabusYear))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ToolssyllabusYear $toolssyllabusYear)
    {
        abort_if(Gate::denies('toolssyllabus_year_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $toolssyllabusYear->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
