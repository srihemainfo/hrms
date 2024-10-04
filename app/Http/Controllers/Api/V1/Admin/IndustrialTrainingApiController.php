<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIndustrialTrainingRequest;
use App\Http\Requests\UpdateIndustrialTrainingRequest;
use App\Http\Resources\Admin\IndustrialTrainingResource;
use App\Models\IndustrialTraining;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IndustrialTrainingApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('industrial_training_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IndustrialTrainingResource(IndustrialTraining::with(['name'])->get());
    }

    public function store(StoreIndustrialTrainingRequest $request)
    {
        $industrialTraining = IndustrialTraining::create($request->all());

        return (new IndustrialTrainingResource($industrialTraining))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(IndustrialTraining $industrialTraining)
    {
        abort_if(Gate::denies('industrial_training_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IndustrialTrainingResource($industrialTraining->load(['name']));
    }

    public function update(UpdateIndustrialTrainingRequest $request, IndustrialTraining $industrialTraining)
    {
        $industrialTraining->update($request->all());

        return (new IndustrialTrainingResource($industrialTraining))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(IndustrialTraining $industrialTraining)
    {
        abort_if(Gate::denies('industrial_training_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $industrialTraining->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
