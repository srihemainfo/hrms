<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInternRequest;
use App\Http\Requests\UpdateInternRequest;
use App\Http\Resources\Admin\InternResource;
use App\Models\Intern;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InternApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('intern_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new InternResource(Intern::with(['name'])->get());
    }

    public function store(StoreInternRequest $request)
    {
        $intern = Intern::create($request->all());

        return (new InternResource($intern))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Intern $intern)
    {
        abort_if(Gate::denies('intern_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new InternResource($intern->load(['name']));
    }

    public function update(UpdateInternRequest $request, Intern $intern)
    {
        $intern->update($request->all());

        return (new InternResource($intern))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Intern $intern)
    {
        abort_if(Gate::denies('intern_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $intern->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
