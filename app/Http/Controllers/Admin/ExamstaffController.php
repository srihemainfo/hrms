<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyExamstaffRequest;
use App\Http\Requests\StoreExamstaffRequest;
use App\Http\Requests\UpdateExamstaffRequest;
use App\Models\Examstaff;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ExamstaffController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('examstaff_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Examstaff::query()->select(sprintf('%s.*', (new Examstaff)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'examstaff_show';
                $editGate      = 'examstaff_edit';
                $deleteGate    = 'examstaff_delete';
                $crudRoutePart = 'examstaffs';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.examstaffs.index');
    }

    public function create()
    {
        abort_if(Gate::denies('examstaff_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.examstaffs.create');
    }

    public function store(StoreExamstaffRequest $request)
    {
        $examstaff = Examstaff::create($request->all());

        return redirect()->route('admin.examstaffs.index');
    }

    public function edit(Examstaff $examstaff)
    {
        abort_if(Gate::denies('examstaff_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.examstaffs.edit', compact('examstaff'));
    }

    public function update(UpdateExamstaffRequest $request, Examstaff $examstaff)
    {
        $examstaff->update($request->all());

        return redirect()->route('admin.examstaffs.index');
    }

    public function show(Examstaff $examstaff)
    {
        abort_if(Gate::denies('examstaff_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.examstaffs.show', compact('examstaff'));
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
