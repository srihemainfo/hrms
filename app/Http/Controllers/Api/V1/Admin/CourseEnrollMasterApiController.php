<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseEnrollMasterRequest;
use App\Http\Requests\UpdateCourseEnrollMasterRequest;
use App\Http\Resources\Admin\CourseEnrollMasterResource;
use App\Models\CourseEnrollMaster;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CourseEnrollMasterApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('course_enroll_master_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CourseEnrollMasterResource(CourseEnrollMaster::with(['degreetype', 'batch', 'academic', 'course', 'department', 'semester', 'section'])->get());
    }

    public function store(StoreCourseEnrollMasterRequest $request)
    {
        $courseEnrollMaster = CourseEnrollMaster::create($request->all());

        return (new CourseEnrollMasterResource($courseEnrollMaster))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CourseEnrollMaster $courseEnrollMaster)
    {
        abort_if(Gate::denies('course_enroll_master_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CourseEnrollMasterResource($courseEnrollMaster->load(['degreetype', 'batch', 'academic', 'course', 'department', 'semester', 'section']));
    }

    public function update(UpdateCourseEnrollMasterRequest $request, CourseEnrollMaster $courseEnrollMaster)
    {
        $courseEnrollMaster->update($request->all());

        return (new CourseEnrollMasterResource($courseEnrollMaster))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CourseEnrollMaster $courseEnrollMaster)
    {
        abort_if(Gate::denies('course_enroll_master_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $courseEnrollMaster->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
