<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBloodGroupRequest;
use App\Http\Requests\StoreBloodGroupRequest;
use App\Http\Requests\UpdateBloodGroupRequest;
use App\Models\BloodGroup;
use Carbon\Carbon;
use Faker\Core\Blood;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class BloodGroupController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = BloodGroup::query()->select(sprintf('%s.*', (new BloodGroup)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewBlood';
                $editFunct = 'editBlood';
                $deleteFunct = 'deleteBlood';
                $viewGate      = 'blood_group_show';
                $editGate      = 'blood_group_edit';
                $deleteGate    = 'blood_group_delete';
                $crudRoutePart = 'blood-groups';

                return view('partials.ajaxTableActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'viewFunct',
                    'editFunct',
                    'deleteFunct',
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

        return view('admin.bloodGroups.index');
    }

    public function create()
    {
        // abort_if(Gate::denies('blood_group_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.bloodGroups.create');
    }

    public function store(Request $request)
    {
        if (isset($request->name)) {
            if ($request->id == '') {
                $store = BloodGroup::create([
                    'name' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'BloodGroup Created']);
            } else {
                $update = BloodGroup::where(['id' => $request->id])->update([
                    'name' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'BloodGroup Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'BloodGroup Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = BloodGroup::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = BloodGroup::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(UpdateBloodGroupRequest $request, BloodGroup $bloodGroup)
    {
        $bloodGroup->update($request->all());

        return redirect()->route('admin.blood-groups.index');
    }

    public function show(BloodGroup $bloodGroup)
    {
        // abort_if(Gate::denies('blood_group_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.bloodGroups.show', compact('bloodGroup'));
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = BloodGroup::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'BloodGroup Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $religion = BloodGroup::find(request('ids'));

        foreach ($religion as $r) {
            $r->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'BloodGroup Deleted Successfully']);
    }
}
