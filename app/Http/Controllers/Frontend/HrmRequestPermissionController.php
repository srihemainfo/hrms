<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyHrmRequestPermissionRequest;
use App\Http\Requests\StoreHrmRequestPermissionRequest;
use App\Http\Requests\UpdateHrmRequestPermissionRequest;
use App\Models\HrmRequestPermission;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HrmRequestPermissionController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('hrm_request_permission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hrmRequestPermissions = HrmRequestPermission::with(['user'])->get();

        return view('frontend.hrmRequestPermissions.index', compact('hrmRequestPermissions'));
    }

    public function create()
    {
        abort_if(Gate::denies('hrm_request_permission_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.hrmRequestPermissions.create', compact('users'));
    }

    public function store(StoreHrmRequestPermissionRequest $request)
    {
        $hrmRequestPermission = HrmRequestPermission::create($request->all());

        return redirect()->route('frontend.hrm-request-permissions.index');
    }

    public function edit(HrmRequestPermission $hrmRequestPermission)
    {
        abort_if(Gate::denies('hrm_request_permission_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $hrmRequestPermission->load('user');

        return view('frontend.hrmRequestPermissions.edit', compact('hrmRequestPermission', 'users'));
    }

    public function update(UpdateHrmRequestPermissionRequest $request, HrmRequestPermission $hrmRequestPermission)
    {
        $hrmRequestPermission->update($request->all());

        return redirect()->route('frontend.hrm-request-permissions.index');
    }

    public function show(HrmRequestPermission $hrmRequestPermission)
    {
        abort_if(Gate::denies('hrm_request_permission_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hrmRequestPermission->load('user');

        return view('frontend.hrmRequestPermissions.show', compact('hrmRequestPermission'));
    }

    public function destroy(HrmRequestPermission $hrmRequestPermission)
    {
        abort_if(Gate::denies('hrm_request_permission_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hrmRequestPermission->delete();

        return back();
    }

    public function massDestroy(MassDestroyHrmRequestPermissionRequest $request)
    {
        $hrmRequestPermissions = HrmRequestPermission::find(request('ids'));

        foreach ($hrmRequestPermissions as $hrmRequestPermission) {
            $hrmRequestPermission->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
