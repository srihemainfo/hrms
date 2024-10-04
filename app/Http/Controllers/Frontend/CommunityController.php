<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCommunityRequest;
use App\Http\Requests\StoreCommunityRequest;
use App\Http\Requests\UpdateCommunityRequest;
use App\Models\Community;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CommunityController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('community_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $communities = Community::all();

        return view('frontend.communities.index', compact('communities'));
    }

    public function create()
    {
        abort_if(Gate::denies('community_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.communities.create');
    }

    public function store(StoreCommunityRequest $request)
    {
        $community = Community::create($request->all());

        return redirect()->route('frontend.communities.index');
    }

    public function edit(Community $community)
    {
        abort_if(Gate::denies('community_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.communities.edit', compact('community'));
    }

    public function update(UpdateCommunityRequest $request, Community $community)
    {
        $community->update($request->all());

        return redirect()->route('frontend.communities.index');
    }

    public function show(Community $community)
    {
        abort_if(Gate::denies('community_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.communities.show', compact('community'));
    }

    public function destroy(Community $community)
    {
        abort_if(Gate::denies('community_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $community->delete();

        return back();
    }

    public function massDestroy(MassDestroyCommunityRequest $request)
    {
        $communities = Community::find(request('ids'));

        foreach ($communities as $community) {
            $community->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
