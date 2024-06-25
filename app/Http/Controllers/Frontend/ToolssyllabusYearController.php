<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyToolssyllabusYearRequest;
use App\Http\Requests\StoreToolssyllabusYearRequest;
use App\Http\Requests\UpdateToolssyllabusYearRequest;
use App\Models\ToolssyllabusYear;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ToolssyllabusYearController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('toolssyllabus_year_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $toolssyllabusYears = ToolssyllabusYear::all();

        return view('frontend.toolssyllabusYears.index', compact('toolssyllabusYears'));
    }

    public function create()
    {
        abort_if(Gate::denies('toolssyllabus_year_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.toolssyllabusYears.create');
    }

    public function store(StoreToolssyllabusYearRequest $request)
    {
        $toolssyllabusYear = ToolssyllabusYear::create($request->all());

        return redirect()->route('frontend.toolssyllabus-years.index');
    }

    public function edit(ToolssyllabusYear $toolssyllabusYear)
    {
        abort_if(Gate::denies('toolssyllabus_year_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.toolssyllabusYears.edit', compact('toolssyllabusYear'));
    }

    public function update(UpdateToolssyllabusYearRequest $request, ToolssyllabusYear $toolssyllabusYear)
    {
        $toolssyllabusYear->update($request->all());

        return redirect()->route('frontend.toolssyllabus-years.index');
    }

    public function show(ToolssyllabusYear $toolssyllabusYear)
    {
        abort_if(Gate::denies('toolssyllabus_year_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.toolssyllabusYears.show', compact('toolssyllabusYear'));
    }

    public function destroy(ToolssyllabusYear $toolssyllabusYear)
    {
        abort_if(Gate::denies('toolssyllabus_year_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $toolssyllabusYear->delete();

        return back();
    }

    public function massDestroy(MassDestroyToolssyllabusYearRequest $request)
    {
        $toolssyllabusYears = ToolssyllabusYear::find(request('ids'));

        foreach ($toolssyllabusYears as $toolssyllabusYear) {
            $toolssyllabusYear->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
