<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyExamstaffRequest;
use App\Http\Requests\StoreExamstaffRequest;
use App\Http\Requests\UpdateExamstaffRequest;
use App\Models\Examstaff;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExamstaffController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('examstaff_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $examstaffs = Examstaff::all();

        return view('frontend.examstaffs.index', compact('examstaffs'));
    }

    public function create()
    {
        abort_if(Gate::denies('examstaff_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.examstaffs.create');
    }

    public function store(StoreExamstaffRequest $request)
    {
        $examstaff = Examstaff::create($request->all());

        return redirect()->route('frontend.examstaffs.index');
    }

    public function edit(Examstaff $examstaff)
    {
        abort_if(Gate::denies('examstaff_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.examstaffs.edit', compact('examstaff'));
    }

    public function update(UpdateExamstaffRequest $request, Examstaff $examstaff)
    {
        $examstaff->update($request->all());

        return redirect()->route('frontend.examstaffs.index');
    }

    public function show(Examstaff $examstaff)
    {
        abort_if(Gate::denies('examstaff_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.examstaffs.show', compact('examstaff'));
    }

    public function destroy(Examstaff $examstaff)
    {
        abort_if(Gate::denies('examstaff_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $examstaff->delete();

        return back();
    }

    public function massDestroy(MassDestroyExamstaffRequest $request)
    {
        $examstaffs = Examstaff::find(request('ids'));

        foreach ($examstaffs as $examstaff) {
            $examstaff->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
