<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatentRequest;
use App\Http\Requests\UpdatePatentRequest;
use App\Http\Resources\Admin\PatentResource;
use App\Models\Patent;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PatentsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('patent_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PatentResource(Patent::with(['name'])->get());
    }

    public function store(StorePatentRequest $request)
    {
        $patent = Patent::create($request->all());

        return (new PatentResource($patent))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Patent $patent)
    {
        abort_if(Gate::denies('patent_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PatentResource($patent->load(['name']));
    }

    public function update(UpdatePatentRequest $request, Patent $patent)
    {
        $patent->update($request->all());

        return (new PatentResource($patent))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Patent $patent)
    {
        abort_if(Gate::denies('patent_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patent->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
