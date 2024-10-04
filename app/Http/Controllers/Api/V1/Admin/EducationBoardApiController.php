<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEducationBoardRequest;
use App\Http\Requests\UpdateEducationBoardRequest;
use App\Http\Resources\Admin\EducationBoardResource;
use App\Models\EducationBoard;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EducationBoardApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('education_board_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EducationBoardResource(EducationBoard::all());
    }

    public function store(StoreEducationBoardRequest $request)
    {
        $educationBoard = EducationBoard::create($request->all());

        return (new EducationBoardResource($educationBoard))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(EducationBoard $educationBoard)
    {
        abort_if(Gate::denies('education_board_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EducationBoardResource($educationBoard);
    }

    public function update(UpdateEducationBoardRequest $request, EducationBoard $educationBoard)
    {
        $educationBoard->update($request->all());

        return (new EducationBoardResource($educationBoard))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(EducationBoard $educationBoard)
    {
        abort_if(Gate::denies('education_board_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $educationBoard->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
