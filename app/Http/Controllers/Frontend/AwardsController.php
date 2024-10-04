<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAwardRequest;
use App\Http\Requests\StoreAwardRequest;
use App\Http\Requests\UpdateAwardRequest;
use App\Models\Award;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AwardsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('award_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $awards = Award::with(['user_name'])->get();

        return view('frontend.awards.index', compact('awards'));
    }

    public function create()
    {
        abort_if(Gate::denies('award_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.awards.create', compact('user_names'));
    }

    public function store(StoreAwardRequest $request)
    {
        $award = Award::create($request->all());

        return redirect()->route('frontend.awards.index');
    }

    public function edit(Award $award)
    {
        abort_if(Gate::denies('award_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $award->load('user_name');

        return view('frontend.awards.edit', compact('award', 'user_names'));
    }

    public function update(UpdateAwardRequest $request, Award $award)
    {
        $award->update($request->all());

        return redirect()->route('frontend.awards.index');
    }

    public function show(Award $award)
    {
        abort_if(Gate::denies('award_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $award->load('user_name');

        return view('frontend.awards.show', compact('award'));
    }

    public function destroy(Award $award)
    {
        abort_if(Gate::denies('award_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $award->delete();

        return back();
    }

    public function massDestroy(MassDestroyAwardRequest $request)
    {
        $awards = Award::find(request('ids'));

        foreach ($awards as $award) {
            $award->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
