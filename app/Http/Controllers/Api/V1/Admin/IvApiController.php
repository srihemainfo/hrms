<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIvRequest;
use App\Http\Requests\UpdateIvRequest;
use App\Http\Resources\Admin\IvResource;
use App\Models\Iv;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IvApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('iv_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IvResource(Iv::with(['name'])->get());
    }

    public function store(StoreIvRequest $request)
    {
        $iv = Iv::create($request->all());

        return (new IvResource($iv))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Iv $iv)
    {
        abort_if(Gate::denies('iv_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IvResource($iv->load(['name']));
    }

    public function update(UpdateIvRequest $request, Iv $iv)
    {
        $iv->update($request->all());

        return (new IvResource($iv))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Iv $iv)
    {
        abort_if(Gate::denies('iv_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $iv->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
