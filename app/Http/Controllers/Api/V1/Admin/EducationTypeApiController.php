<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEducationTypeRequest;
use App\Http\Requests\UpdateEducationTypeRequest;
use App\Http\Resources\Admin\EducationTypeResource;
use App\Models\EducationType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EducationTypeApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('education_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EducationTypeResource(EducationType::all());
    }

    public function store(StoreEducationTypeRequest $request)
    {
        $educationType = EducationType::create($request->all());

        return (new EducationTypeResource($educationType))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(EducationType $educationType)
    {
        abort_if(Gate::denies('education_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EducationTypeResource($educationType);
    }

    public function update(UpdateEducationTypeRequest $request, EducationType $educationType)
    {
        $educationType->update($request->all());

        return (new EducationTypeResource($educationType))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(EducationType $educationType)
    {
        abort_if(Gate::denies('education_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $educationType->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
