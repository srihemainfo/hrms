<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySemesterRequest;
use App\Http\Requests\StoreSemesterRequest;
use App\Http\Requests\UpdateSemesterRequest;
use App\Models\Semester;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SemesterController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('semester_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $semesters = Semester::all();

        return view('frontend.semesters.index', compact('semesters'));
    }

    public function create()
    {
        abort_if(Gate::denies('semester_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.semesters.create');
    }

    public function store(StoreSemesterRequest $request)
    {
        $semester = Semester::create($request->all());

        return redirect()->route('frontend.semesters.index');
    }

    public function edit(Semester $semester)
    {
        abort_if(Gate::denies('semester_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.semesters.edit', compact('semester'));
    }

    public function update(UpdateSemesterRequest $request, Semester $semester)
    {
        $semester->update($request->all());

        return redirect()->route('frontend.semesters.index');
    }

    public function show(Semester $semester)
    {
        abort_if(Gate::denies('semester_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.semesters.show', compact('semester'));
    }

    public function destroy(Semester $semester)
    {
        abort_if(Gate::denies('semester_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $semester->delete();

        return back();
    }

    public function massDestroy(MassDestroySemesterRequest $request)
    {
        $semesters = Semester::find(request('ids'));

        foreach ($semesters as $semester) {
            $semester->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
