<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreParentDetailRequest;
use App\Http\Requests\UpdateParentDetailRequest;
use App\Http\Resources\Admin\ParentDetailResource;
use App\Models\ParentDetail;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ParentDetailsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('parent_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ParentDetailResource(ParentDetail::all());
    }

    public function store(StoreParentDetailRequest $request)
    {
        $parentDetail = ParentDetail::create($request->all());

        return (new ParentDetailResource($parentDetail))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ParentDetail $parentDetail)
    {
        abort_if(Gate::denies('parent_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ParentDetailResource($parentDetail);
    }

    public function update(UpdateParentDetailRequest $request, ParentDetail $parentDetail)
    {
        $parentDetail->update($request->all());

        return (new ParentDetailResource($parentDetail))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ParentDetail $parentDetail)
    {
        abort_if(Gate::denies('parent_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $parentDetail->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
