<?php

namespace App\Http\Controllers\Admin;

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
use Yajra\DataTables\Facades\DataTables;

class TakeAttentanceStudentController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('take_attentance_student_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = TakeAttentanceStudent::with(['enroll_master'])->select(sprintf('%s.*', (new TakeAttentanceStudent)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'take_attentance_student_show';
                $editGate      = 'take_attentance_student_edit';
                $deleteGate    = 'take_attentance_student_delete';
                $crudRoutePart = 'take-attentance-students';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->addColumn('enroll_master_enroll_master_number', function ($row) {
                return $row->enroll_master ? $row->enroll_master->enroll_master_number : '';
            });

            $table->editColumn('enroll_master.deletes', function ($row) {
                return $row->enroll_master ? (is_string($row->enroll_master) ? $row->enroll_master : $row->enroll_master->deletes) : '';
            });
            $table->editColumn('period', function ($row) {
                return $row->period ? $row->period : '';
            });
            $table->editColumn('taken_from', function ($row) {
                return $row->taken_from ? $row->taken_from : '';
            });
            $table->editColumn('approved_by', function ($row) {
                return $row->approved_by ? $row->approved_by : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'enroll_master']);

            return $table->make(true);
        }

        return view('admin.takeAttentanceStudents.index');
    }

    public function create()
    {
        abort_if(Gate::denies('take_attentance_student_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $enroll_masters = CourseEnrollMaster::pluck('enroll_master_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.takeAttentanceStudents.create', compact('enroll_masters'));
    }

    public function store(StoreTakeAttentanceStudentRequest $request)
    {
        $takeAttentanceStudent = TakeAttentanceStudent::create($request->all());

        return redirect()->route('admin.take-attentance-students.index');
    }

    public function edit(TakeAttentanceStudent $takeAttentanceStudent)
    {
        abort_if(Gate::denies('take_attentance_student_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $enroll_masters = CourseEnrollMaster::pluck('enroll_master_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $takeAttentanceStudent->load('enroll_master');

        return view('admin.takeAttentanceStudents.edit', compact('enroll_masters', 'takeAttentanceStudent'));
    }

    public function update(UpdateTakeAttentanceStudentRequest $request, TakeAttentanceStudent $takeAttentanceStudent)
    {
        $takeAttentanceStudent->update($request->all());

        return redirect()->route('admin.take-attentance-students.index');
    }

    public function show(TakeAttentanceStudent $takeAttentanceStudent)
    {
        abort_if(Gate::denies('take_attentance_student_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $takeAttentanceStudent->load('enroll_master');

        return view('admin.takeAttentanceStudents.show', compact('takeAttentanceStudent'));
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
