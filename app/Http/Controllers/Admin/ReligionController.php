<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyReligionRequest;
use App\Http\Requests\StoreReligionRequest;
use App\Http\Requests\UpdateReligionRequest;
use App\Models\Religion;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ReligionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Religion::query()->select(sprintf('%s.*', (new Religion)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewReligion';
                $editFunct = 'editReligion';
                $deleteFunct = 'deleteReligion';
                $viewGate      = 'religion_show';
                $editGate      = 'religion_edit';
                $deleteGate    = 'religion_delete';
                $crudRoutePart = 'religions';

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

        return view('admin.religions.index');
    }

    public function create()
    {
        abort_if(Gate::denies('religion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.religions.create');
    }

    public function store(Request $request)
    {
        if (isset($request->name)) {
            if ($request->id == '') {
                $store = Religion::create([
                    'name' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'Religion Created']);
            } else {
                $update = Religion::where(['id' => $request->id])->update([
                    'name' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'Religion Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Religion Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = Religion::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = Religion::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(UpdateReligionRequest $request, Religion $religion)
    {
        $religion->update($request->all());

        return redirect()->route('admin.religions.index');
    }

    public function show(Religion $religion)
    {
        abort_if(Gate::denies('religion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.religions.show', compact('religion'));
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = Religion::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Religion Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $religion = Religion::find(request('ids'));

        foreach ($religion as $r) {
            $r->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'Religions Deleted Successfully']);
    }
}
