<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExamstaffRequest;
use App\Http\Requests\UpdateExamstaffRequest;
use App\Http\Resources\Admin\ExamstaffResource;
use App\Models\Examstaff;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExamstaffApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('examstaff_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ExamstaffResource(Examstaff::all());
    }

    public function store(StoreExamstaffRequest $request)
    {
        $examstaff = Examstaff::create($request->all());

        return (new ExamstaffResource($examstaff))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Examstaff $examstaff)
    {
        abort_if(Gate::denies('examstaff_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ExamstaffResource($examstaff);
    }

    public function update(UpdateExamstaffRequest $request, Examstaff $examstaff)
    {
        $examstaff->update($request->all());

        return (new ExamstaffResource($examstaff))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Examstaff $examstaff)
    {
        abort_if(Gate::denies('examstaff_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $examstaff->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
