<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMotherTongueRequest;
use App\Http\Requests\UpdateMotherTongueRequest;
use App\Http\Resources\Admin\MotherTongueResource;
use App\Models\MotherTongue;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MotherTongueApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('mother_tongue_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MotherTongueResource(MotherTongue::all());
    }

    public function store(StoreMotherTongueRequest $request)
    {
        $motherTongue = MotherTongue::create($request->all());

        return (new MotherTongueResource($motherTongue))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(MotherTongue $motherTongue)
    {
        abort_if(Gate::denies('mother_tongue_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MotherTongueResource($motherTongue);
    }

    public function update(UpdateMotherTongueRequest $request, MotherTongue $motherTongue)
    {
        $motherTongue->update($request->all());

        return (new MotherTongueResource($motherTongue))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(MotherTongue $motherTongue)
    {
        abort_if(Gate::denies('mother_tongue_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $motherTongue->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
