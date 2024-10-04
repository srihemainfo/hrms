<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommunityRequest;
use App\Http\Requests\UpdateCommunityRequest;
use App\Http\Resources\Admin\CommunityResource;
use App\Models\Community;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CommunityApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('community_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CommunityResource(Community::all());
    }

    public function store(StoreCommunityRequest $request)
    {
        $community = Community::create($request->all());

        return (new CommunityResource($community))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Community $community)
    {
        abort_if(Gate::denies('community_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CommunityResource($community);
    }

    public function update(UpdateCommunityRequest $request, Community $community)
    {
        $community->update($request->all());

        return (new CommunityResource($community))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Community $community)
    {
        abort_if(Gate::denies('community_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $community->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
