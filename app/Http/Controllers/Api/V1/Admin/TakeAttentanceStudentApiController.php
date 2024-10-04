<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTakeAttentanceStudentRequest;
use App\Http\Requests\UpdateTakeAttentanceStudentRequest;
use App\Http\Resources\Admin\TakeAttentanceStudentResource;
use App\Models\TakeAttentanceStudent;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TakeAttentanceStudentApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('take_attentance_student_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TakeAttentanceStudentResource(TakeAttentanceStudent::with(['enroll_master'])->get());
    }

    public function store(StoreTakeAttentanceStudentRequest $request)
    {
        $takeAttentanceStudent = TakeAttentanceStudent::create($request->all());

        return (new TakeAttentanceStudentResource($takeAttentanceStudent))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(TakeAttentanceStudent $takeAttentanceStudent)
    {
        abort_if(Gate::denies('take_attentance_student_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TakeAttentanceStudentResource($takeAttentanceStudent->load(['enroll_master']));
    }

    public function update(UpdateTakeAttentanceStudentRequest $request, TakeAttentanceStudent $takeAttentanceStudent)
    {
        $takeAttentanceStudent->update($request->all());

        return (new TakeAttentanceStudentResource($takeAttentanceStudent))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(TakeAttentanceStudent $takeAttentanceStudent)
    {
        abort_if(Gate::denies('take_attentance_student_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $takeAttentanceStudent->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
