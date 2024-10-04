<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyOdMasterRequest;
use App\Http\Requests\StoreOdMasterRequest;
use App\Http\Requests\UpdateOdMasterRequest;
use App\Models\OdMaster;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class OdMasterController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('od_master_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = OdMaster::query()->select(sprintf('%s.*', (new OdMaster)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'od_master_show';
                $editGate      = 'od_master_edit';
                $deleteGate    = 'od_master_delete';
                $crudRoutePart = 'od-masters';

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
            $table->editColumn('level_1_role', function ($row) {
                return $row->level_1_role ? $row->level_1_role : '';
            });
            $table->editColumn('level_2_role', function ($row) {
                return $row->level_2_role ? $row->level_2_role : '';
            });
            $table->editColumn('level_3_role', function ($row) {
                return $row->level_3_role ? $row->level_3_role : '';
            });
            $table->editColumn('approved_by', function ($row) {
                return $row->approved_by ? $row->approved_by : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.odMasters.index');
    }

    public function create()
    {
        abort_if(Gate::denies('od_master_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.odMasters.create');
    }

    public function store(StoreOdMasterRequest $request)
    {
        $odMaster = OdMaster::create($request->all());

        return redirect()->route('admin.od-masters.index');
    }

    public function edit(OdMaster $odMaster)
    {
        abort_if(Gate::denies('od_master_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.odMasters.edit', compact('odMaster'));
    }

    public function update(UpdateOdMasterRequest $request, OdMaster $odMaster)
    {
        $odMaster->update($request->all());

        return redirect()->route('admin.od-masters.index');
    }

    public function show(OdMaster $odMaster)
    {
        abort_if(Gate::denies('od_master_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.odMasters.show', compact('odMaster'));
    }

    public function destroy(OdMaster $odMaster)
    {
        abort_if(Gate::denies('od_master_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $odMaster->delete();

        return back();
    }

    public function massDestroy(MassDestroyOdMasterRequest $request)
    {
        $odMasters = OdMaster::find(request('ids'));

        foreach ($odMasters as $odMaster) {
            $odMaster->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
