<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEntranceExamRequest;
use App\Http\Requests\UpdateEntranceExamRequest;
use App\Http\Resources\Admin\EntranceExamResource;
use App\Models\EntranceExam;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EntranceExamsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('entrance_exam_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EntranceExamResource(EntranceExam::with(['name', 'exam_type'])->get());
    }

    public function store(StoreEntranceExamRequest $request)
    {
        $entranceExam = EntranceExam::create($request->all());

        return (new EntranceExamResource($entranceExam))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(EntranceExam $entranceExam)
    {
        abort_if(Gate::denies('entrance_exam_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EntranceExamResource($entranceExam->load(['name', 'exam_type']));
    }

    public function update(UpdateEntranceExamRequest $request, EntranceExam $entranceExam)
    {
        $entranceExam->update($request->all());

        return (new EntranceExamResource($entranceExam))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(EntranceExam $entranceExam)
    {
        abort_if(Gate::denies('entrance_exam_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entranceExam->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
