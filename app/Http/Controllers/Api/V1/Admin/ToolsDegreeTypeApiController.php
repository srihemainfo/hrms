<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreToolsDegreeTypeRequest;
use App\Http\Requests\UpdateToolsDegreeTypeRequest;
use App\Http\Resources\Admin\ToolsDegreeTypeResource;
use App\Models\ToolsDegreeType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ToolsDegreeTypeApiController extends Controller
{
    public function index()
    {
        dd('hello');
        abort_if(Gate::denies('tools_degree_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ToolsDegreeTypeResource(ToolsDegreeType::all());
    }

    public function store(StoreToolsDegreeTypeRequest $request)
    {
        $toolsDegreeType = ToolsDegreeType::create($request->all());

        return (new ToolsDegreeTypeResource($toolsDegreeType))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ToolsDegreeType $toolsDegreeType)
    {
        abort_if(Gate::denies('tools_degree_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ToolsDegreeTypeResource($toolsDegreeType);
    }

    public function update(UpdateToolsDegreeTypeRequest $request, ToolsDegreeType $toolsDegreeType)
    {
        $toolsDegreeType->update($request->all());

        return (new ToolsDegreeTypeResource($toolsDegreeType))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ToolsDegreeType $toolsDegreeType)
    {
        abort_if(Gate::denies('tools_degree_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $toolsDegreeType->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
