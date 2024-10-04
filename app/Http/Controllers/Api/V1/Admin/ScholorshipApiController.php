<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScholorshipRequest;
use App\Http\Requests\UpdateScholorshipRequest;
use App\Http\Resources\Admin\ScholorshipResource;
use App\Models\Scholorship;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ScholorshipApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('scholorship_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ScholorshipResource(Scholorship::all());
    }

    public function store(StoreScholorshipRequest $request)
    {
        $scholorship = Scholorship::create($request->all());

        return (new ScholorshipResource($scholorship))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Scholorship $scholorship)
    {
        abort_if(Gate::denies('scholorship_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ScholorshipResource($scholorship);
    }

    public function update(UpdateScholorshipRequest $request, Scholorship $scholorship)
    {
        $scholorship->update($request->all());

        return (new ScholorshipResource($scholorship))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Scholorship $scholorship)
    {
        abort_if(Gate::denies('scholorship_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $scholorship->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
