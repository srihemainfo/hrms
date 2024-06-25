<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSeminarRequest;
use App\Http\Requests\UpdateSeminarRequest;
use App\Http\Resources\Admin\SeminarResource;
use App\Models\Seminar;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SeminarApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('seminar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SeminarResource(Seminar::with(['user_name'])->get());
    }

    public function store(StoreSeminarRequest $request)
    {
        $seminar = Seminar::create($request->all());

        return (new SeminarResource($seminar))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Seminar $seminar)
    {
        abort_if(Gate::denies('seminar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SeminarResource($seminar->load(['user_name']));
    }

    public function update(UpdateSeminarRequest $request, Seminar $seminar)
    {
        $seminar->update($request->all());

        return (new SeminarResource($seminar))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Seminar $seminar)
    {
        abort_if(Gate::denies('seminar_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $seminar->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
