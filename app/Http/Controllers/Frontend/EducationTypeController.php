<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEducationTypeRequest;
use App\Http\Requests\StoreEducationTypeRequest;
use App\Http\Requests\UpdateEducationTypeRequest;
use App\Models\EducationType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EducationTypeController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('education_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $educationTypes = EducationType::all();

        return view('frontend.educationTypes.index', compact('educationTypes'));
    }

    public function create()
    {
        abort_if(Gate::denies('education_type_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.educationTypes.create');
    }

    public function store(StoreEducationTypeRequest $request)
    {
        $educationType = EducationType::create($request->all());

        return redirect()->route('frontend.education-types.index');
    }

    public function edit(EducationType $educationType)
    {
        abort_if(Gate::denies('education_type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.educationTypes.edit', compact('educationType'));
    }

    public function update(UpdateEducationTypeRequest $request, EducationType $educationType)
    {
        $educationType->update($request->all());

        return redirect()->route('frontend.education-types.index');
    }

    public function show(EducationType $educationType)
    {
        abort_if(Gate::denies('education_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.educationTypes.show', compact('educationType'));
    }

    public function destroy(EducationType $educationType)
    {
        abort_if(Gate::denies('education_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $educationType->delete();

        return back();
    }

    public function massDestroy(MassDestroyEducationTypeRequest $request)
    {
        $educationTypes = EducationType::find(request('ids'));

        foreach ($educationTypes as $educationType) {
            $educationType->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
