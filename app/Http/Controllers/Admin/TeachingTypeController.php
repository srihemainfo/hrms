<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTeachingTypeRequest;
use App\Http\Requests\StoreTeachingTypeRequest;
use App\Http\Requests\UpdateTeachingTypeRequest;
use App\Models\TeachingType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TeachingTypeController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('teaching_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = TeachingType::query()->select(sprintf('%s.*', (new TeachingType)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'teaching_type_show';
                $editGate      = 'teaching_type_edit';
                $deleteGate    = 'teaching_type_delete';
                $crudRoutePart = 'teaching-types';

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

        return view('admin.teachingTypes.index');
    }

    public function create()
    {
        abort_if(Gate::denies('teaching_type_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.teachingTypes.create');
    }

    public function store(StoreTeachingTypeRequest $request)
    {
        $teachingType = TeachingType::create($request->all());

        return redirect()->route('admin.teaching-types.index');
    }

    public function edit(TeachingType $teachingType)
    {
        abort_if(Gate::denies('teaching_type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.teachingTypes.edit', compact('teachingType'));
    }

    public function update(UpdateTeachingTypeRequest $request, TeachingType $teachingType)
    {
        $teachingType->update($request->all());

        return redirect()->route('admin.teaching-types.index');
    }

    public function show(TeachingType $teachingType)
    {
        abort_if(Gate::denies('teaching_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.teachingTypes.show', compact('teachingType'));
    }

    public function destroy(TeachingType $teachingType)
    {
        abort_if(Gate::denies('teaching_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $teachingType->delete();

        return back();
    }

    public function massDestroy(MassDestroyTeachingTypeRequest $request)
    {
        $teachingTypes = TeachingType::find(request('ids'));

        foreach ($teachingTypes as $teachingType) {
            $teachingType->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
