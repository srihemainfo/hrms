<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOnlineCourseRequest;
use App\Http\Requests\UpdateOnlineCourseRequest;
use App\Http\Resources\Admin\OnlineCourseResource;
use App\Models\OnlineCourse;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnlineCourseApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('online_course_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OnlineCourseResource(OnlineCourse::with(['user_name'])->get());
    }

    public function store(StoreOnlineCourseRequest $request)
    {
        $onlineCourse = OnlineCourse::create($request->all());

        return (new OnlineCourseResource($onlineCourse))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(OnlineCourse $onlineCourse)
    {
        abort_if(Gate::denies('online_course_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OnlineCourseResource($onlineCourse->load(['user_name']));
    }

    public function update(UpdateOnlineCourseRequest $request, OnlineCourse $onlineCourse)
    {
        $onlineCourse->update($request->all());

        return (new OnlineCourseResource($onlineCourse))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(OnlineCourse $onlineCourse)
    {
        abort_if(Gate::denies('online_course_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $onlineCourse->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
