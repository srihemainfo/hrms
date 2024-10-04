<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBatchRequest;
use App\Http\Requests\UpdateBatchRequest;
use App\Http\Resources\Admin\BatchResource;
use App\Models\Batch;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BatchApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('batch_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new BatchResource(Batch::all());
    }

    public function store(StoreBatchRequest $request)
    {
        $batch = Batch::create($request->all());

        return (new BatchResource($batch))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Batch $batch)
    {
        abort_if(Gate::denies('batch_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new BatchResource($batch);
    }

    public function update(UpdateBatchRequest $request, Batch $batch)
    {
        $batch->update($request->all());

        return (new BatchResource($batch))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Batch $batch)
    {
        abort_if(Gate::denies('batch_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $batch->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
