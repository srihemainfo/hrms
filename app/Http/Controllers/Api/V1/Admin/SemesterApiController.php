<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSemesterRequest;
use App\Http\Requests\UpdateSemesterRequest;
use App\Http\Resources\Admin\SemesterResource;
use App\Models\Semester;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SemesterApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('semester_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SemesterResource(Semester::all());
    }

    public function store(StoreSemesterRequest $request)
    {
        $semester = Semester::create($request->all());

        return (new SemesterResource($semester))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Semester $semester)
    {
        abort_if(Gate::denies('semester_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SemesterResource($semester);
    }

    public function update(UpdateSemesterRequest $request, Semester $semester)
    {
        $semester->update($request->all());

        return (new SemesterResource($semester))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Semester $semester)
    {
        abort_if(Gate::denies('semester_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $semester->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
