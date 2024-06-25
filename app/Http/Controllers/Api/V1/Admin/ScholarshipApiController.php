<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScholarshipRequest;
use App\Http\Requests\UpdateScholarshipRequest;
use App\Http\Resources\Admin\ScholarshipResource;
use App\Models\Scholarship;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ScholarshipApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('scholarship_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ScholarshipResource(Scholarship::all());
    }

    public function store(StoreScholarshipRequest $request)
    {
        $scholarship = Scholarship::create($request->all());

        return (new ScholarshipResource($scholarship))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Scholarship $scholarship)
    {
        abort_if(Gate::denies('scholarship_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ScholarshipResource($scholarship);
    }

    public function update(UpdateScholarshipRequest $request, Scholarship $scholarship)
    {
        $scholarship->update($request->all());

        return (new ScholarshipResource($scholarship))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Scholarship $scholarship)
    {
        abort_if(Gate::denies('scholarship_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $scholarship->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
