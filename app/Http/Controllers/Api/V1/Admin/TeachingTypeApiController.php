<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeachingTypeRequest;
use App\Http\Requests\UpdateTeachingTypeRequest;
use App\Http\Resources\Admin\TeachingTypeResource;
use App\Models\TeachingType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeachingTypeApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('teaching_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TeachingTypeResource(TeachingType::all());
    }

    public function store(StoreTeachingTypeRequest $request)
    {
        $teachingType = TeachingType::create($request->all());

        return (new TeachingTypeResource($teachingType))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(TeachingType $teachingType)
    {
        abort_if(Gate::denies('teaching_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TeachingTypeResource($teachingType);
    }

    public function update(UpdateTeachingTypeRequest $request, TeachingType $teachingType)
    {
        $teachingType->update($request->all());

        return (new TeachingTypeResource($teachingType))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(TeachingType $teachingType)
    {
        abort_if(Gate::denies('teaching_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $teachingType->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
