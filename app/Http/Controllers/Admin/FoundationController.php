<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Models\Foundation;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class FoundationController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('foundation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Foundation::query()->select(sprintf('%s.*', (new Foundation)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'foundation_show';
                $editGate      = 'foundation_edit';
                $deleteGate    = 'foundation_delete';
                $crudRoutePart = 'foundations';

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

        return view('admin.foundations.index');
    }

    public function create()
    {
        abort_if(Gate::denies('foundation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.foundations.create');
    }

    public function store(Request $request)
    {
        $store = Foundation::create($request->all());

        return redirect()->route('admin.foundations.index');
    }

    public function edit(Foundation $foundation)
    {
        abort_if(Gate::denies('foundation_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.foundations.edit', compact('foundation'));
    }

    public function update(Foundation $foundation, Request $request)
    {
        // dd($foundation,$request);
        $update = Foundation::where(['id' => $foundation->id])->update(['name' => $request->name]);
        return redirect()->route('admin.foundations.index');
    }

    public function show(Foundation $foundation)
    {
        abort_if(Gate::denies('foundation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.foundations.show', compact('foundation'));
    }

    public function destroy(Foundation $foundation, Request $request)
    {
        abort_if(Gate::denies('foundation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $delete = Foundation::where(['id' => $foundation->id])->delete();
        return back();
    }

    public function massDestroy(Request $request)
    {
        $foundations = Foundation::find(request('ids'));

        foreach ($foundations as $Foundation) {
            $Foundation->delete();
        }
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
