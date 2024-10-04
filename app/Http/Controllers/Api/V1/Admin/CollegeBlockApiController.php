<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCollegeBlockRequest;
use App\Http\Requests\UpdateCollegeBlockRequest;
use App\Http\Resources\Admin\CollegeBlockResource;
use App\Models\CollegeBlock;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CollegeBlockApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('college_block_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CollegeBlockResource(CollegeBlock::all());
    }

    public function store(StoreCollegeBlockRequest $request)
    {
        $collegeBlock = CollegeBlock::create($request->all());

        return (new CollegeBlockResource($collegeBlock))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CollegeBlock $collegeBlock)
    {
        abort_if(Gate::denies('college_block_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CollegeBlockResource($collegeBlock);
    }

    public function update(UpdateCollegeBlockRequest $request, CollegeBlock $collegeBlock)
    {
        $collegeBlock->update($request->all());

        return (new CollegeBlockResource($collegeBlock))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CollegeBlock $collegeBlock)
    {
        abort_if(Gate::denies('college_block_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $collegeBlock->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
