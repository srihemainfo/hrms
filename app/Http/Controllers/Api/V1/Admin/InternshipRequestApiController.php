<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInternshipRequestRequest;
use App\Http\Requests\UpdateInternshipRequestRequest;
use App\Http\Resources\Admin\InternshipRequestResource;
use App\Models\InternshipRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InternshipRequestApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('internship_request_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new InternshipRequestResource(InternshipRequest::all());
    }

    public function store(StoreInternshipRequestRequest $request)
    {
        $internshipRequest = InternshipRequest::create($request->all());

        return (new InternshipRequestResource($internshipRequest))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(InternshipRequest $internshipRequest)
    {
        abort_if(Gate::denies('internship_request_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new InternshipRequestResource($internshipRequest);
    }

    public function update(UpdateInternshipRequestRequest $request, InternshipRequest $internshipRequest)
    {
        $internshipRequest->update($request->all());

        return (new InternshipRequestResource($internshipRequest))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(InternshipRequest $internshipRequest)
    {
        abort_if(Gate::denies('internship_request_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $internshipRequest->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
