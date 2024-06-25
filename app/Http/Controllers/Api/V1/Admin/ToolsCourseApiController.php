<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreToolsCourseRequest;
use App\Http\Requests\UpdateToolsCourseRequest;
use App\Http\Resources\Admin\ToolsCourseResource;
use App\Models\ToolsCourse;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ToolsCourseApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('tools_course_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ToolsCourseResource(ToolsCourse::all());
    }

    public function store(StoreToolsCourseRequest $request)
    {
        $toolsCourse = ToolsCourse::create($request->all());

        return (new ToolsCourseResource($toolsCourse))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ToolsCourse $toolsCourse)
    {
        abort_if(Gate::denies('tools_course_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ToolsCourseResource($toolsCourse);
    }

    public function update(UpdateToolsCourseRequest $request, ToolsCourse $toolsCourse)
    {
        $toolsCourse->update($request->all());

        return (new ToolsCourseResource($toolsCourse))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ToolsCourse $toolsCourse)
    {
        abort_if(Gate::denies('tools_course_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $toolsCourse->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
