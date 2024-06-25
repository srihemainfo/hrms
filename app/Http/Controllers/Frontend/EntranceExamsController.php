<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEntranceExamRequest;
use App\Http\Requests\StoreEntranceExamRequest;
use App\Http\Requests\UpdateEntranceExamRequest;
use App\Models\EntranceExam;
use App\Models\Examstaff;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EntranceExamsController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('entrance_exam_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entranceExams = EntranceExam::with(['name', 'exam_type'])->get();

        return view('frontend.entranceExams.index', compact('entranceExams'));
    }

    public function create()
    {
        abort_if(Gate::denies('entrance_exam_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $exam_types = Examstaff::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.entranceExams.create', compact('exam_types', 'names'));
    }

    public function store(StoreEntranceExamRequest $request)
    {
        $entranceExam = EntranceExam::create($request->all());

        return redirect()->route('frontend.entrance-exams.index');
    }

    public function edit(EntranceExam $entranceExam)
    {
        abort_if(Gate::denies('entrance_exam_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $exam_types = Examstaff::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $entranceExam->load('name', 'exam_type');

        return view('frontend.entranceExams.edit', compact('entranceExam', 'exam_types', 'names'));
    }

    public function update(UpdateEntranceExamRequest $request, EntranceExam $entranceExam)
    {
        $entranceExam->update($request->all());

        return redirect()->route('frontend.entrance-exams.index');
    }

    public function show(EntranceExam $entranceExam)
    {
        abort_if(Gate::denies('entrance_exam_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entranceExam->load('name', 'exam_type');

        return view('frontend.entranceExams.show', compact('entranceExam'));
    }

    public function destroy(EntranceExam $entranceExam)
    {
        abort_if(Gate::denies('entrance_exam_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entranceExam->delete();

        return back();
    }

    public function massDestroy(MassDestroyEntranceExamRequest $request)
    {
        $entranceExams = EntranceExam::find(request('ids'));

        foreach ($entranceExams as $entranceExam) {
            $entranceExam->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
