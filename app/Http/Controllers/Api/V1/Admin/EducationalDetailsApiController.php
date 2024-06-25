<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEducationalDetailRequest;
use App\Http\Requests\UpdateEducationalDetailRequest;
use App\Http\Resources\Admin\EducationalDetailResource;
use App\Models\EducationalDetail;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EducationalDetailsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('educational_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EducationalDetailResource(EducationalDetail::with(['education_type', 'medium'])->get());
    }

    public function store(StoreEducationalDetailRequest $request)
    {
        $educationalDetail = EducationalDetail::create($request->all());

        return (new EducationalDetailResource($educationalDetail))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(EducationalDetail $educationalDetail)
    {
        abort_if(Gate::denies('educational_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EducationalDetailResource($educationalDetail->load(['education_type', 'medium']));
    }

    public function update(UpdateEducationalDetailRequest $request, EducationalDetail $educationalDetail)
    {
        $educationalDetail->update($request->all());

        return (new EducationalDetailResource($educationalDetail))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(EducationalDetail $educationalDetail)
    {
        abort_if(Gate::denies('educational_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $educationalDetail->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
