<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Projects;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = Projects::select(sprintf('%s.*', (new Projects)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'projects_show';
                $editGate = 'projects_edit';
                $deleteGate = 'projects_delete';
                $editFunct = 'editProjects';
                $viewFunct = 'viewProjects';
                $deleteFunct = 'deleteProjects';
                $crudRoutePart = 'Projects';

                return view('partials.ajaxTableActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'editFunct',
                    'viewFunct',
                    'deleteFunct',
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
        return view('admin.projects.index');
    }

    public function store(Request $request)
    {

        if (isset($request->projects)) {
            if ($request->id == '') {
                $count = Projects::where(['name' => $request->projects])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Project Already Exist.']);
                } else {
                    $store = Projects::create([
                        'name' => $request->projects,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Project Created']);
            } else {
                $count = Projects::whereNotIn('id', [$request->id])->where(['name' => $request->projects])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Project Already Exist.']);
                } else {
                    $update = Projects::where(['id' => $request->id])->update([
                        'name' => $request->projects,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Project Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Project Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = Projects::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = Projects::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = Projects::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Project Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $sections = Projects::find(request('ids'));

        foreach ($sections as $section) {
            $section->delete();
        }

        return response()->json(['status' => 'success', 'data' => 'Project Deleted Successfully']);
    }
}
