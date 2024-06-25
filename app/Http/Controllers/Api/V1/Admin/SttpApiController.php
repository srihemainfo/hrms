<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSttpRequest;
use App\Http\Requests\UpdateSttpRequest;
use App\Http\Resources\Admin\SttpResource;
use App\Models\Sttp;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SttpApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('sttp_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SttpResource(Sttp::with(['name'])->get());
    }

    public function store(StoreSttpRequest $request)
    {
        $sttp = Sttp::create($request->all());

        return (new SttpResource($sttp))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Sttp $sttp)
    {
        abort_if(Gate::denies('sttp_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SttpResource($sttp->load(['name']));
    }

    public function update(UpdateSttpRequest $request, Sttp $sttp)
    {
        $sttp->update($request->all());

        return (new SttpResource($sttp))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Sttp $sttp)
    {
        abort_if(Gate::denies('sttp_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sttp->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
