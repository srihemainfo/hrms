<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNonTeachingStaffRequest;
use App\Http\Requests\UpdateNonTeachingStaffRequest;
use App\Http\Resources\Admin\NonTeachingStaffResource;
use App\Models\NonTeachingStaff;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NonTeachingStaffApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('non_teaching_staff_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NonTeachingStaffResource(NonTeachingStaff::with(['working_as'])->get());
    }

    public function store(StoreNonTeachingStaffRequest $request)
    {
        $nonTeachingStaff = NonTeachingStaff::create($request->all());

        return (new NonTeachingStaffResource($nonTeachingStaff))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(NonTeachingStaff $nonTeachingStaff)
    {
        abort_if(Gate::denies('non_teaching_staff_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NonTeachingStaffResource($nonTeachingStaff->load(['working_as']));
    }

    public function update(UpdateNonTeachingStaffRequest $request, NonTeachingStaff $nonTeachingStaff)
    {
        $nonTeachingStaff->update($request->all());

        return (new NonTeachingStaffResource($nonTeachingStaff))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(NonTeachingStaff $nonTeachingStaff)
    {
        abort_if(Gate::denies('non_teaching_staff_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $nonTeachingStaff->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
