<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyNationalityRequest;
use App\Http\Requests\StoreNationalityRequest;
use App\Http\Requests\UpdateNationalityRequest;
use App\Models\Nationality;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class NationalityController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $query = Nationality::query()->select(sprintf('%s.*', (new Nationality)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'nationality_show';
                $editGate = 'nationality_edit';
                $deleteGate = 'nationality_delete';
                $crudRoutePart = 'nationality';
                $viewFunct = 'viewNationality';
                $editFunct = 'editNationality';
                $deleteFunct = 'deleteNationality';

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

        return view('admin.nationalities.index');
    }

    public function create()
    {
        abort_if(Gate::denies('nationality_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.nationalities.create');
    }

    public function store(Request $request)
    {
        if (isset($request->nation)) {
            if ($request->id == '') {
                $store = Nationality::create([
                    'name' => strtoupper($request->nation),
                ]);
                return response()->json(['status' => true, 'data' => 'Nationality Created']);
            } else {
                $update = Nationality::where(['id' => $request->id])->update([
                    'name' => strtoupper($request->nation),
                ]);
                return response()->json(['status' => true, 'data' => 'Nationality Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Nationality Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = Nationality::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = Nationality::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(UpdateNationalityRequest $request, Nationality $nationality)
    {
        $nationality->update($request->all());

        return redirect()->route('admin.nationalities.index');
    }

    public function show(Nationality $nationality)
    {
        abort_if(Gate::denies('nationality_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.nationalities.show', compact('nationality'));
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = Nationality::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Nationality Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $nation = Nationality::find(request('ids'));

        foreach ($nation as $n) {
            $n->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'Nationality Deleted Successfully']);
    }
}
