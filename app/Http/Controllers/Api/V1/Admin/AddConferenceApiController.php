<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddConferenceRequest;
use App\Http\Requests\UpdateAddConferenceRequest;
use App\Http\Resources\Admin\AddConferenceResource;
use App\Models\AddConference;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddConferenceApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('add_conference_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AddConferenceResource(AddConference::with(['user_name'])->get());
    }

    public function store(StoreAddConferenceRequest $request)
    {
        $addConference = AddConference::create($request->all());

        return (new AddConferenceResource($addConference))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(AddConference $addConference)
    {
        abort_if(Gate::denies('add_conference_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AddConferenceResource($addConference->load(['user_name']));
    }

    public function update(UpdateAddConferenceRequest $request, AddConference $addConference)
    {
        $addConference->update($request->all());

        return (new AddConferenceResource($addConference))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(AddConference $addConference)
    {
        abort_if(Gate::denies('add_conference_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addConference->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
