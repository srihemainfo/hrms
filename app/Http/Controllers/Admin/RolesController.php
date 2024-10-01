<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\TeachingType;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolesController extends Controller
{
    public function index()
    {
        // abort_if(Gate::denies('role_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::with(['permissions'])->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        // abort_if(Gate::denies('role_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $types = TeachingType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $permissions = Permission::pluck('title', 'id');

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {

        $existingRecord = Role::where('title', $request->title)->first();

        if ($existingRecord) {
            return back()->withInput()->with('error', 'Combination already exists.');
        } else {

            $role = Role::create($request->all());
            $role->permissions()->sync($request->input('permissions', []));
            return redirect()->route('admin.roles.index')->with('message', 'Role created successfully');
        }

    }

    public function edit(Role $role)
    {
        // abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $types = TeachingType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $permissions = Permission::pluck('title', 'id');

        $role->load( 'permissions');

        return view('admin.roles.edit', compact('permissions', 'role'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $id = $role->id;
        $record = Role::findOrFail($id);
        $permissions = $record->permissions;

        // Get the Permissions Id
        $title = [];
        foreach ($permissions as $permission) {
            $title[] = $permission->id;
        }

        if (($record->type_id == $request->type_id && $record->title === $request->title && $title == $request->permissions)) {

            return redirect()->route('admin.roles.index');
        } else {
            $existingRecord = Role::where('title', $request->title)
                ->where('id', '!=', $id) // Exclude the current ID
                ->first();

            if ($existingRecord) {
                return back()->withInput()->with('error', 'Combination already exists.');
            } else {

                $role->update($request->all());
                $role->permissions()->sync($request->input('permissions', []));
                return redirect()->route('admin.roles.index')->with('message', 'Role Updated successfully');

            }

        }

    }

    public function show(Role $role)
    {
        // abort_if(Gate::denies('role_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->load( 'permissions');

        return view('admin.roles.show', compact('role'));
    }

    public function destroy(Role $role)
    {
        // abort_if(Gate::denies('role_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->delete();

        return back()->with('message', 'Role Deleted successfully');
    }

    public function massDestroy(MassDestroyRoleRequest $request)
    {
        $roles = Role::find(request('ids'));

        foreach ($roles as $role) {
            $role->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
