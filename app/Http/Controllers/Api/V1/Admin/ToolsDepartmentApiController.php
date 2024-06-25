<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreToolsDepartmentRequest;
use App\Http\Requests\UpdateToolsDepartmentRequest;
use App\Http\Resources\Admin\ToolsDepartmentResource;
use App\Models\ToolsDepartment;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ToolsDepartmentApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('tools_department_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ToolsDepartmentResource(ToolsDepartment::all());
    }

    public function store(StoreToolsDepartmentRequest $request)
    {
        $toolsDepartment = ToolsDepartment::create($request->all());

        return (new ToolsDepartmentResource($toolsDepartment))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ToolsDepartment $toolsDepartment)
    {
        abort_if(Gate::denies('tools_department_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ToolsDepartmentResource($toolsDepartment);
    }

    public function update(UpdateToolsDepartmentRequest $request, ToolsDepartment $toolsDepartment)
    {
        $toolsDepartment->update($request->all());

        return (new ToolsDepartmentResource($toolsDepartment))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ToolsDepartment $toolsDepartment)
    {
        abort_if(Gate::denies('tools_department_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $toolsDepartment->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
