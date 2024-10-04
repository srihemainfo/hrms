<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPermissionRequest;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Permission;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class PermissionsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('permission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::all();

        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        abort_if(Gate::denies('permission_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.permissions.create');
    }

    public function store(StorePermissionRequest $request)
    {
        $existingRecord = Permission::where('title', $request->title)
        ->first();
        if($existingRecord){
            return back()->withInput()->with('message_error', 'Combintion Already Exists');
        }
        else{
            $Permissions = Permission::create($request->all());
            return redirect()->route('admin.permissions.index')->with('message', 'Permissions created successfully');

        // $permission = Permission::create($request->all());

        // return redirect()->route('admin.permissions.index');
    }
    }

    public function edit(Permission $permission)
    {
        abort_if(Gate::denies('permission_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(UpdatePermissionRequest $request, Permission $permission)
    {

        $id = $permission -> id;
        $record = Permission::findOrFail($id);
        $permission_title = $record -> title;

        if($permission_title == $request -> title){
            return redirect()->route('admin.permissions.index');
        }
        else {
            $existingRecord = Permission::where('title', $request -> title)->where('id', '!=', $id)-> first();
            if($existingRecord){
                return back()->withInput()->with('error', 'Combination already exists.');
            }
            else {
                $permission->update($request->all());

                return redirect()->route('admin.permissions.index')->with('message', 'Permission Updated successfully');
            }
        }


    }

    public function show(Permission $permission)
    {
        abort_if(Gate::denies('permission_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.permissions.show', compact('permission'));
    }

    public function destroy(Permission $permission)
    {
        abort_if(Gate::denies('permission_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permission->delete();

        return back()->with('message', 'Permissions Deleted successfully');;
    }

    public function massDestroy(MassDestroyPermissionRequest $request)
    {
        $permissions = Permission::find(request('ids'));

        foreach ($permissions as $permission) {
            $permission->delete();
        }

        Session::flash('message', 'Permissions deleted successfully.');

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
