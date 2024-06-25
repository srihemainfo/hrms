<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExperienceDetailRequest;
use App\Http\Requests\UpdateExperienceDetailRequest;
use App\Http\Resources\Admin\ExperienceDetailResource;
use App\Models\ExperienceDetail;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExperienceDetailsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('experience_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ExperienceDetailResource(ExperienceDetail::with(['name'])->get());
    }

    public function store(StoreExperienceDetailRequest $request)
    {
        $experienceDetail = ExperienceDetail::create($request->all());

        return (new ExperienceDetailResource($experienceDetail))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ExperienceDetail $experienceDetail)
    {
        abort_if(Gate::denies('experience_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ExperienceDetailResource($experienceDetail->load(['name']));
    }

    public function update(UpdateExperienceDetailRequest $request, ExperienceDetail $experienceDetail)
    {
        $experienceDetail->update($request->all());

        return (new ExperienceDetailResource($experienceDetail))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ExperienceDetail $experienceDetail)
    {
        abort_if(Gate::denies('experience_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $experienceDetail->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
