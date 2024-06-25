<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEducationBoardRequest;
use App\Http\Requests\StoreEducationBoardRequest;
use App\Http\Requests\UpdateEducationBoardRequest;
use App\Models\EducationBoard;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EducationBoardController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('education_board_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $educationBoards = EducationBoard::all();

        return view('frontend.educationBoards.index', compact('educationBoards'));
    }

    public function create()
    {
        abort_if(Gate::denies('education_board_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.educationBoards.create');
    }

    public function store(StoreEducationBoardRequest $request)
    {
        $educationBoard = EducationBoard::create($request->all());

        return redirect()->route('frontend.education-boards.index');
    }

    public function edit(EducationBoard $educationBoard)
    {
        abort_if(Gate::denies('education_board_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.educationBoards.edit', compact('educationBoard'));
    }

    public function update(UpdateEducationBoardRequest $request, EducationBoard $educationBoard)
    {
        $educationBoard->update($request->all());

        return redirect()->route('frontend.education-boards.index');
    }

    public function show(EducationBoard $educationBoard)
    {
        abort_if(Gate::denies('education_board_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.educationBoards.show', compact('educationBoard'));
    }

    public function destroy(EducationBoard $educationBoard)
    {
        abort_if(Gate::denies('education_board_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $educationBoard->delete();

        return back();
    }

    public function massDestroy(MassDestroyEducationBoardRequest $request)
    {
        $educationBoards = EducationBoard::find(request('ids'));

        foreach ($educationBoards as $educationBoard) {
            $educationBoard->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
