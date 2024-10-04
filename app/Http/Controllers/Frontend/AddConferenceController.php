<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyAddConferenceRequest;
use App\Http\Requests\StoreAddConferenceRequest;
use App\Http\Requests\UpdateAddConferenceRequest;
use App\Models\AddConference;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddConferenceController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('add_conference_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addConferences = AddConference::with(['user_name'])->get();

        return view('frontend.addConferences.index', compact('addConferences'));
    }

    public function create()
    {
        abort_if(Gate::denies('add_conference_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.addConferences.create', compact('user_names'));
    }

    public function store(StoreAddConferenceRequest $request)
    {
        $addConference = AddConference::create($request->all());

        return redirect()->route('frontend.add-conferences.index');
    }

    public function edit(AddConference $addConference)
    {
        abort_if(Gate::denies('add_conference_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $addConference->load('user_name');

        return view('frontend.addConferences.edit', compact('addConference', 'user_names'));
    }

    public function update(UpdateAddConferenceRequest $request, AddConference $addConference)
    {
        $addConference->update($request->all());

        return redirect()->route('frontend.add-conferences.index');
    }

    public function show(AddConference $addConference)
    {
        abort_if(Gate::denies('add_conference_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addConference->load('user_name');

        return view('frontend.addConferences.show', compact('addConference'));
    }

    public function destroy(AddConference $addConference)
    {
        abort_if(Gate::denies('add_conference_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addConference->delete();

        return back();
    }

    public function massDestroy(MassDestroyAddConferenceRequest $request)
    {
        $addConferences = AddConference::find(request('ids'));

        foreach ($addConferences as $addConference) {
            $addConference->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
