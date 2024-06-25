<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSponserRequest;
use App\Http\Requests\UpdateSponserRequest;
use App\Http\Resources\Admin\SponserResource;
use App\Models\Sponser;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SponserApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('sponser_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SponserResource(Sponser::with(['user_name'])->get());
    }

    public function store(StoreSponserRequest $request)
    {
        $sponser = Sponser::create($request->all());

        return (new SponserResource($sponser))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Sponser $sponser)
    {
        abort_if(Gate::denies('sponser_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SponserResource($sponser->load(['user_name']));
    }

    public function update(UpdateSponserRequest $request, Sponser $sponser)
    {
        $sponser->update($request->all());

        return (new SponserResource($sponser))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Sponser $sponser)
    {
        abort_if(Gate::denies('sponser_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sponser->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
