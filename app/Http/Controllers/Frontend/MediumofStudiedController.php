<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyMediumofStudiedRequest;
use App\Http\Requests\StoreMediumofStudiedRequest;
use App\Http\Requests\UpdateMediumofStudiedRequest;
use App\Models\MediumofStudied;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MediumofStudiedController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('mediumof_studied_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mediumofStudieds = MediumofStudied::all();

        return view('frontend.mediumofStudieds.index', compact('mediumofStudieds'));
    }

    public function create()
    {
        abort_if(Gate::denies('mediumof_studied_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.mediumofStudieds.create');
    }

    public function store(StoreMediumofStudiedRequest $request)
    {
        $mediumofStudied = MediumofStudied::create($request->all());

        return redirect()->route('frontend.mediumof-studieds.index');
    }

    public function edit(MediumofStudied $mediumofStudied)
    {
        abort_if(Gate::denies('mediumof_studied_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.mediumofStudieds.edit', compact('mediumofStudied'));
    }

    public function update(UpdateMediumofStudiedRequest $request, MediumofStudied $mediumofStudied)
    {
        $mediumofStudied->update($request->all());

        return redirect()->route('frontend.mediumof-studieds.index');
    }

    public function show(MediumofStudied $mediumofStudied)
    {
        abort_if(Gate::denies('mediumof_studied_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.mediumofStudieds.show', compact('mediumofStudied'));
    }

    public function destroy(MediumofStudied $mediumofStudied)
    {
        abort_if(Gate::denies('mediumof_studied_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mediumofStudied->delete();

        return back();
    }

    public function massDestroy(MassDestroyMediumofStudiedRequest $request)
    {
        $mediumofStudieds = MediumofStudied::find(request('ids'));

        foreach ($mediumofStudieds as $mediumofStudied) {
            $mediumofStudied->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
