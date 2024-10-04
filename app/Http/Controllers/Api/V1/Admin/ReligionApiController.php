<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReligionRequest;
use App\Http\Requests\UpdateReligionRequest;
use App\Http\Resources\Admin\ReligionResource;
use App\Models\Religion;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReligionApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('religion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ReligionResource(Religion::all());
    }

    public function store(StoreReligionRequest $request)
    {
        $religion = Religion::create($request->all());

        return (new ReligionResource($religion))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Religion $religion)
    {
        abort_if(Gate::denies('religion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ReligionResource($religion);
    }

    public function update(UpdateReligionRequest $request, Religion $religion)
    {
        $religion->update($request->all());

        return (new ReligionResource($religion))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Religion $religion)
    {
        abort_if(Gate::denies('religion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $religion->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
