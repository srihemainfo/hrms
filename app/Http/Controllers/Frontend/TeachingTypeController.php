<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTeachingTypeRequest;
use App\Http\Requests\StoreTeachingTypeRequest;
use App\Http\Requests\UpdateTeachingTypeRequest;
use App\Models\TeachingType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeachingTypeController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('teaching_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $teachingTypes = TeachingType::all();

        return view('frontend.teachingTypes.index', compact('teachingTypes'));
    }

    public function create()
    {
        abort_if(Gate::denies('teaching_type_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.teachingTypes.create');
    }

    public function store(StoreTeachingTypeRequest $request)
    {
        $teachingType = TeachingType::create($request->all());

        return redirect()->route('frontend.teaching-types.index');
    }

    public function edit(TeachingType $teachingType)
    {
        abort_if(Gate::denies('teaching_type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.teachingTypes.edit', compact('teachingType'));
    }

    public function update(UpdateTeachingTypeRequest $request, TeachingType $teachingType)
    {
        $teachingType->update($request->all());

        return redirect()->route('frontend.teaching-types.index');
    }

    public function show(TeachingType $teachingType)
    {
        abort_if(Gate::denies('teaching_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.teachingTypes.show', compact('teachingType'));
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
