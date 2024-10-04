<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyToolsDepartmentRequest;
use App\Http\Requests\StoreToolsDepartmentRequest;
use App\Http\Requests\UpdateToolsDepartmentRequest;
use App\Models\ToolsDepartment;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ToolsDepartmentController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('tools_department_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $toolsDepartments = ToolsDepartment::all();

        return view('frontend.toolsDepartments.index', compact('toolsDepartments'));
    }

    public function create()
    {
        abort_if(Gate::denies('tools_department_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.toolsDepartments.create');
    }

    public function store(StoreToolsDepartmentRequest $request)
    {
        $toolsDepartment = ToolsDepartment::create($request->all());

        return redirect()->route('frontend.tools-departments.index');
    }

    public function edit(ToolsDepartment $toolsDepartment)
    {
        abort_if(Gate::denies('tools_department_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.toolsDepartments.edit', compact('toolsDepartment'));
    }

    public function update(UpdateToolsDepartmentRequest $request, ToolsDepartment $toolsDepartment)
    {
        $toolsDepartment->update($request->all());

        return redirect()->route('frontend.tools-departments.index');
    }

    public function show(ToolsDepartment $toolsDepartment)
    {
        abort_if(Gate::denies('tools_department_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.toolsDepartments.show', compact('toolsDepartment'));
    }

    public function destroy(ToolsDepartment $toolsDepartment)
    {
        abort_if(Gate::denies('tools_department_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $toolsDepartment->delete();

        return back();
    }

    public function massDestroy(MassDestroyToolsDepartmentRequest $request)
    {
        $toolsDepartments = ToolsDepartment::find(request('ids'));

        foreach ($toolsDepartments as $toolsDepartment) {
            $toolsDepartment->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
