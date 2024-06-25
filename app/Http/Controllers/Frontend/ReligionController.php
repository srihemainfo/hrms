<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyReligionRequest;
use App\Http\Requests\StoreReligionRequest;
use App\Http\Requests\UpdateReligionRequest;
use App\Models\Religion;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReligionController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('religion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $religions = Religion::all();

        return view('frontend.religions.index', compact('religions'));
    }

    public function create()
    {
        abort_if(Gate::denies('religion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.religions.create');
    }

    public function store(StoreReligionRequest $request)
    {
        $religion = Religion::create($request->all());

        return redirect()->route('frontend.religions.index');
    }

    public function edit(Religion $religion)
    {
        abort_if(Gate::denies('religion_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.religions.edit', compact('religion'));
    }

    public function update(UpdateReligionRequest $request, Religion $religion)
    {
        $religion->update($request->all());

        return redirect()->route('frontend.religions.index');
    }

    public function show(Religion $religion)
    {
        abort_if(Gate::denies('religion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.religions.show', compact('religion'));
    }

    public function destroy(Religion $religion)
    {
        abort_if(Gate::denies('religion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $religion->delete();

        return back();
    }

    public function massDestroy(MassDestroyReligionRequest $request)
    {
        $religions = Religion::find(request('ids'));

        foreach ($religions as $religion) {
            $religion->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
