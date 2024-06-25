<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMediumofStudiedRequest;
use App\Http\Requests\UpdateMediumofStudiedRequest;
use App\Http\Resources\Admin\MediumofStudiedResource;
use App\Models\MediumofStudied;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MediumofStudiedApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('mediumof_studied_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MediumofStudiedResource(MediumofStudied::all());
    }

    public function store(StoreMediumofStudiedRequest $request)
    {
        $mediumofStudied = MediumofStudied::create($request->all());

        return (new MediumofStudiedResource($mediumofStudied))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(MediumofStudied $mediumofStudied)
    {
        abort_if(Gate::denies('mediumof_studied_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MediumofStudiedResource($mediumofStudied);
    }

    public function update(UpdateMediumofStudiedRequest $request, MediumofStudied $mediumofStudied)
    {
        $mediumofStudied->update($request->all());

        return (new MediumofStudiedResource($mediumofStudied))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(MediumofStudied $mediumofStudied)
    {
        abort_if(Gate::denies('mediumof_studied_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mediumofStudied->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
