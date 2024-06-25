<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAcademicDetailRequest;
use App\Http\Requests\UpdateAcademicDetailRequest;
use App\Http\Resources\Admin\AcademicDetailResource;
use App\Models\AcademicDetail;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AcademicDetailsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('academic_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AcademicDetailResource(AcademicDetail::with(['enroll_master_number'])->get());
    }

    public function store(StoreAcademicDetailRequest $request)
    {
        $academicDetail = AcademicDetail::create($request->all());

        return (new AcademicDetailResource($academicDetail))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(AcademicDetail $academicDetail)
    {
        abort_if(Gate::denies('academic_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AcademicDetailResource($academicDetail->load(['enroll_master_number']));
    }

    public function update(UpdateAcademicDetailRequest $request, AcademicDetail $academicDetail)
    {
        $academicDetail->update($request->all());

        return (new AcademicDetailResource($academicDetail))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(AcademicDetail $academicDetail)
    {
        abort_if(Gate::denies('academic_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $academicDetail->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
