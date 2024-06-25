<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTakeAttentanceStudentRequest;
use App\Http\Requests\StoreTakeAttentanceStudentRequest;
use App\Http\Requests\UpdateTakeAttentanceStudentRequest;
use App\Models\CourseEnrollMaster;
use App\Models\TakeAttentanceStudent;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TakeAttentanceStudentController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('take_attentance_student_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $takeAttentanceStudents = TakeAttentanceStudent::with(['enroll_master'])->get();

        return view('frontend.takeAttentanceStudents.index', compact('takeAttentanceStudents'));
    }

    public function create()
    {
        abort_if(Gate::denies('take_attentance_student_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $enroll_masters = CourseEnrollMaster::pluck('enroll_master_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.takeAttentanceStudents.create', compact('enroll_masters'));
    }

    public function store(StoreTakeAttentanceStudentRequest $request)
    {
        $takeAttentanceStudent = TakeAttentanceStudent::create($request->all());

        return redirect()->route('frontend.take-attentance-students.index');
    }

    public function edit(TakeAttentanceStudent $takeAttentanceStudent)
    {
        abort_if(Gate::denies('take_attentance_student_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $enroll_masters = CourseEnrollMaster::pluck('enroll_master_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $takeAttentanceStudent->load('enroll_master');

        return view('frontend.takeAttentanceStudents.edit', compact('enroll_masters', 'takeAttentanceStudent'));
    }

    public function update(UpdateTakeAttentanceStudentRequest $request, TakeAttentanceStudent $takeAttentanceStudent)
    {
        $takeAttentanceStudent->update($request->all());

        return redirect()->route('frontend.take-attentance-students.index');
    }

    public function show(TakeAttentanceStudent $takeAttentanceStudent)
    {
        abort_if(Gate::denies('take_attentance_student_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $takeAttentanceStudent->load('enroll_master');

        return view('frontend.takeAttentanceStudents.show', compact('takeAttentanceStudent'));
    }

    public function destroy(TakeAttentanceStudent $takeAttentanceStudent)
    {
        abort_if(Gate::denies('take_attentance_student_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $takeAttentanceStudent->delete();

        return back();
    }

    public function massDestroy(MassDestroyTakeAttentanceStudentRequest $request)
    {
        $takeAttentanceStudents = TakeAttentanceStudent::find(request('ids'));

        foreach ($takeAttentanceStudents as $takeAttentanceStudent) {
            $takeAttentanceStudent->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
