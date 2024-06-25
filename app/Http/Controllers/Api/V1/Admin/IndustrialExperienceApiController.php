<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIndustrialExperienceRequest;
use App\Http\Requests\UpdateIndustrialExperienceRequest;
use App\Http\Resources\Admin\IndustrialExperienceResource;
use App\Models\IndustrialExperience;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IndustrialExperienceApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('industrial_experience_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IndustrialExperienceResource(IndustrialExperience::with(['user_name'])->get());
    }

    public function store(StoreIndustrialExperienceRequest $request)
    {
        $industrialExperience = IndustrialExperience::create($request->all());

        return (new IndustrialExperienceResource($industrialExperience))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(IndustrialExperience $industrialExperience)
    {
        abort_if(Gate::denies('industrial_experience_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IndustrialExperienceResource($industrialExperience->load(['user_name']));
    }

    public function update(UpdateIndustrialExperienceRequest $request, IndustrialExperience $industrialExperience)
    {
        $industrialExperience->update($request->all());

        return (new IndustrialExperienceResource($industrialExperience))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(IndustrialExperience $industrialExperience)
    {
        abort_if(Gate::denies('industrial_experience_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $industrialExperience->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
