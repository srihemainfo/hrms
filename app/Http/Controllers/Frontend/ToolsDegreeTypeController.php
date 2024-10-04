<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyToolsDegreeTypeRequest;
use App\Http\Requests\StoreToolsDegreeTypeRequest;
use App\Http\Requests\UpdateToolsDegreeTypeRequest;
use App\Models\ToolsDegreeType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ToolsDegreeTypeController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('tools_degree_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $toolsDegreeTypes = ToolsDegreeType::all();

        return view('frontend.toolsDegreeTypes.index', compact('toolsDegreeTypes'));
    }

    public function create()
    {
        abort_if(Gate::denies('tools_degree_type_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.toolsDegreeTypes.create');
    }

    public function store(StoreToolsDegreeTypeRequest $request)
    {
        $toolsDegreeType = ToolsDegreeType::create($request->all());

        return redirect()->route('frontend.tools-degree-types.index');
    }

    public function edit(ToolsDegreeType $toolsDegreeType)
    {
        abort_if(Gate::denies('tools_degree_type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.toolsDegreeTypes.edit', compact('toolsDegreeType'));
    }

    public function update(UpdateToolsDegreeTypeRequest $request, ToolsDegreeType $toolsDegreeType)
    {
        $toolsDegreeType->update($request->all());

        return redirect()->route('frontend.tools-degree-types.index');
    }

    public function show(ToolsDegreeType $toolsDegreeType)
    {
        abort_if(Gate::denies('tools_degree_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $toolsDegreeType->load('degreetypeCourseEnrollMasters');

        return view('frontend.toolsDegreeTypes.show', compact('toolsDegreeType'));
    }

    public function destroy(ToolsDegreeType $toolsDegreeType)
    {
        abort_if(Gate::denies('tools_degree_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $toolsDegreeType->delete();

        return back();
    }

    public function massDestroy(MassDestroyToolsDegreeTypeRequest $request)
    {
        $toolsDegreeTypes = ToolsDegreeType::find(request('ids'));

        foreach ($toolsDegreeTypes as $toolsDegreeType) {
            $toolsDegreeType->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
