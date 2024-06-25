<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBloodGroupRequest;
use App\Http\Requests\UpdateBloodGroupRequest;
use App\Http\Resources\Admin\BloodGroupResource;
use App\Models\BloodGroup;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BloodGroupApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('blood_group_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new BloodGroupResource(BloodGroup::all());
    }

    public function store(StoreBloodGroupRequest $request)
    {
        $bloodGroup = BloodGroup::create($request->all());

        return (new BloodGroupResource($bloodGroup))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(BloodGroup $bloodGroup)
    {
        abort_if(Gate::denies('blood_group_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new BloodGroupResource($bloodGroup);
    }

    public function update(UpdateBloodGroupRequest $request, BloodGroup $bloodGroup)
    {
        $bloodGroup->update($request->all());

        return (new BloodGroupResource($bloodGroup))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(BloodGroup $bloodGroup)
    {
        abort_if(Gate::denies('blood_group_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bloodGroup->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
