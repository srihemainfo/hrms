<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCollegeBlockRequest;
use App\Http\Requests\StoreCollegeBlockRequest;
use App\Http\Requests\UpdateCollegeBlockRequest;
use App\Models\CollegeBlock;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CollegeBlockController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('college_block_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = CollegeBlock::query()->select(sprintf('%s.*', (new CollegeBlock)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'college_block_show';
                $editGate      = 'college_block_edit';
                $deleteGate    = 'college_block_delete';
                $crudRoutePart = 'college-blocks';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.collegeBlocks.index');
    }

    public function create()
    {
        abort_if(Gate::denies('college_block_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.collegeBlocks.create');
    }

    public function store(StoreCollegeBlockRequest $request)
    {
        $collegeBlock = CollegeBlock::create($request->all());

        return redirect()->route('admin.college-blocks.index');
    }

    public function edit(CollegeBlock $collegeBlock)
    {
        abort_if(Gate::denies('college_block_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.collegeBlocks.edit', compact('collegeBlock'));
    }

    public function update(UpdateCollegeBlockRequest $request, CollegeBlock $collegeBlock)
    {
        $collegeBlock->update($request->all());

        return redirect()->route('admin.college-blocks.index');
    }

    public function show(CollegeBlock $collegeBlock)
    {
        abort_if(Gate::denies('college_block_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.collegeBlocks.show', compact('collegeBlock'));
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
