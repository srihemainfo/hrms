<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyWorkshopRequest;
use App\Http\Requests\StoreWorkshopRequest;
use App\Http\Requests\UpdateWorkshopRequest;
use App\Models\User;
use App\Models\Workshop;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkshopController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('workshop_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workshops = Workshop::with(['user_name'])->get();

        return view('frontend.workshops.index', compact('workshops'));
    }

    public function create()
    {
        abort_if(Gate::denies('workshop_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.workshops.create', compact('user_names'));
    }

    public function store(StoreWorkshopRequest $request)
    {
        $workshop = Workshop::create($request->all());

        return redirect()->route('frontend.workshops.index');
    }

    public function edit(Workshop $workshop)
    {
        abort_if(Gate::denies('workshop_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $workshop->load('user_name');

        return view('frontend.workshops.edit', compact('user_names', 'workshop'));
    }

    public function update(UpdateWorkshopRequest $request, Workshop $workshop)
    {
        $workshop->update($request->all());

        return redirect()->route('frontend.workshops.index');
    }

    public function show(Workshop $workshop)
    {
        abort_if(Gate::denies('workshop_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workshop->load('user_name');

        return view('frontend.workshops.show', compact('workshop'));
    }

    public function destroy(Workshop $workshop)
    {
        abort_if(Gate::denies('workshop_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workshop->delete();

        return back();
    }

    public function massDestroy(MassDestroyWorkshopRequest $request)
    {
        $workshops = Workshop::find(request('ids'));

        foreach ($workshops as $workshop) {
            $workshop->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
