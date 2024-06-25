<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeachingStaffRequest;
use App\Http\Requests\UpdateTeachingStaffRequest;
use App\Http\Resources\Admin\TeachingStaffResource;
use App\Models\TeachingStaff;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeachingStaffApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('teaching_staff_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TeachingStaffResource(TeachingStaff::with(['subject', 'enroll_master', 'working_as'])->get());
    }

    public function store(StoreTeachingStaffRequest $request)
    {
        $teachingStaff = TeachingStaff::create($request->all());

        return (new TeachingStaffResource($teachingStaff))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(TeachingStaff $teachingStaff)
    {
        abort_if(Gate::denies('teaching_staff_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TeachingStaffResource($teachingStaff->load(['subject', 'enroll_master', 'working_as']));
    }

    public function update(UpdateTeachingStaffRequest $request, TeachingStaff $teachingStaff)
    {
        $teachingStaff->update($request->all());

        return (new TeachingStaffResource($teachingStaff))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(TeachingStaff $teachingStaff)
    {
        abort_if(Gate::denies('teaching_staff_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $teachingStaff->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
