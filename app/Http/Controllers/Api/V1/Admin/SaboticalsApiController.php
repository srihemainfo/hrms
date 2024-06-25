<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaboticalRequest;
use App\Http\Requests\UpdateSaboticalRequest;
use App\Http\Resources\Admin\SaboticalResource;
use App\Models\Sabotical;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SaboticalsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('sabotical_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SaboticalResource(Sabotical::with(['name'])->get());
    }

    public function store(StoreSaboticalRequest $request)
    {
        $sabotical = Sabotical::create($request->all());

        return (new SaboticalResource($sabotical))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Sabotical $sabotical)
    {
        abort_if(Gate::denies('sabotical_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SaboticalResource($sabotical->load(['name']));
    }

    public function update(UpdateSaboticalRequest $request, Sabotical $sabotical)
    {
        $sabotical->update($request->all());

        return (new SaboticalResource($sabotical))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Sabotical $sabotical)
    {
        abort_if(Gate::denies('sabotical_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sabotical->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
