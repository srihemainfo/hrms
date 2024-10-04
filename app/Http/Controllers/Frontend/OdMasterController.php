<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyOdMasterRequest;
use App\Http\Requests\StoreOdMasterRequest;
use App\Http\Requests\UpdateOdMasterRequest;
use App\Models\OdMaster;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OdMasterController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('od_master_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $odMasters = OdMaster::all();

        return view('frontend.odMasters.index', compact('odMasters'));
    }

    public function create()
    {
        abort_if(Gate::denies('od_master_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.odMasters.create');
    }

    public function store(StoreOdMasterRequest $request)
    {
        $odMaster = OdMaster::create($request->all());

        return redirect()->route('frontend.od-masters.index');
    }

    public function edit(OdMaster $odMaster)
    {
        abort_if(Gate::denies('od_master_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.odMasters.edit', compact('odMaster'));
    }

    public function update(UpdateOdMasterRequest $request, OdMaster $odMaster)
    {
        $odMaster->update($request->all());

        return redirect()->route('frontend.od-masters.index');
    }

    public function show(OdMaster $odMaster)
    {
        abort_if(Gate::denies('od_master_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.odMasters.show', compact('odMaster'));
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
