<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCollegeBlockRequest;
use App\Http\Requests\StoreCollegeBlockRequest;
use App\Http\Requests\UpdateCollegeBlockRequest;
use App\Models\CollegeBlock;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CollegeBlockController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('college_block_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $collegeBlocks = CollegeBlock::all();

        return view('frontend.collegeBlocks.index', compact('collegeBlocks'));
    }

    public function create()
    {
        abort_if(Gate::denies('college_block_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.collegeBlocks.create');
    }

    public function store(StoreCollegeBlockRequest $request)
    {
        $collegeBlock = CollegeBlock::create($request->all());

        return redirect()->route('frontend.college-blocks.index');
    }

    public function edit(CollegeBlock $collegeBlock)
    {
        abort_if(Gate::denies('college_block_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.collegeBlocks.edit', compact('collegeBlock'));
    }

    public function update(UpdateCollegeBlockRequest $request, CollegeBlock $collegeBlock)
    {
        $collegeBlock->update($request->all());

        return redirect()->route('frontend.college-blocks.index');
    }

    public function show(CollegeBlock $collegeBlock)
    {
        abort_if(Gate::denies('college_block_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.collegeBlocks.show', compact('collegeBlock'));
    }

    public function destroy(CollegeBlock $collegeBlock)
    {
        abort_if(Gate::denies('college_block_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $collegeBlock->delete();

        return back();
    }

    public function massDestroy(MassDestroyCollegeBlockRequest $request)
    {
        $collegeBlocks = CollegeBlock::find(request('ids'));

        foreach ($collegeBlocks as $collegeBlock) {
            $collegeBlock->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
