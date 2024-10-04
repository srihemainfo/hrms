<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyHrmRequestLeafRequest;
use App\Http\Requests\StoreHrmRequestLeafRequest;
use App\Http\Requests\UpdateHrmRequestLeafRequest;
use App\Models\HrmRequestLeaf;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HrmRequestLeaveController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('hrm_request_leaf_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hrmRequestLeaves = HrmRequestLeaf::with(['user'])->get();

        return view('frontend.hrmRequestLeaves.index', compact('hrmRequestLeaves'));
    }

    public function create()
    {
        abort_if(Gate::denies('hrm_request_leaf_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.hrmRequestLeaves.create', compact('users'));
    }

    public function store(StoreHrmRequestLeafRequest $request)
    {
        $hrmRequestLeaf = HrmRequestLeaf::create($request->all());

        return redirect()->route('frontend.hrm-request-leaves.index');
    }

    public function edit(HrmRequestLeaf $hrmRequestLeaf)
    {
        abort_if(Gate::denies('hrm_request_leaf_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $hrmRequestLeaf->load('user');

        return view('frontend.hrmRequestLeaves.edit', compact('hrmRequestLeaf', 'users'));
    }

    public function update(UpdateHrmRequestLeafRequest $request, HrmRequestLeaf $hrmRequestLeaf)
    {
        $hrmRequestLeaf->update($request->all());

        return redirect()->route('frontend.hrm-request-leaves.index');
    }

    public function show(HrmRequestLeaf $hrmRequestLeaf)
    {
        abort_if(Gate::denies('hrm_request_leaf_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hrmRequestLeaf->load('user');

        return view('frontend.hrmRequestLeaves.show', compact('hrmRequestLeaf'));
    }

    public function destroy(HrmRequestLeaf $hrmRequestLeaf)
    {
        abort_if(Gate::denies('hrm_request_leaf_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hrmRequestLeaf->delete();

        return back();
    }

    public function massDestroy(MassDestroyHrmRequestLeafRequest $request)
    {
        $hrmRequestLeaves = HrmRequestLeaf::find(request('ids'));

        foreach ($hrmRequestLeaves as $hrmRequestLeaf) {
            $hrmRequestLeaf->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
