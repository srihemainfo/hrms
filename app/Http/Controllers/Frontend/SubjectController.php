<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySubjectRequest;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\Subject;
use App\Models\ToolssyllabusYear;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubjectController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('subject_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subjects = Subject::with(['syllabus'])->get();

        return view('frontend.subjects.index', compact('subjects'));
    }

    public function create()
    {
        abort_if(Gate::denies('subject_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $syllabi = ToolssyllabusYear::pluck('year', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.subjects.create', compact('syllabi'));
    }

    public function store(StoreSubjectRequest $request)
    {
        $subject = Subject::create($request->all());

        return redirect()->route('frontend.subjects.index');
    }

    public function edit(Subject $subject)
    {
        abort_if(Gate::denies('subject_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $syllabi = ToolssyllabusYear::pluck('year', 'id')->prepend(trans('global.pleaseSelect'), '');

        $subject->load('syllabus');

        return view('frontend.subjects.edit', compact('subject', 'syllabi'));
    }

    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        $subject->update($request->all());

        return redirect()->route('frontend.subjects.index');
    }

    public function show(Subject $subject)
    {
        abort_if(Gate::denies('subject_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subject->load('syllabus');

        return view('frontend.subjects.show', compact('subject'));
    }

    public function destroy(Subject $subject)
    {
        abort_if(Gate::denies('subject_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subject->delete();

        return back();
    }

    public function massDestroy(MassDestroySubjectRequest $request)
    {
        $subjects = Subject::find(request('ids'));

        foreach ($subjects as $subject) {
            $subject->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
