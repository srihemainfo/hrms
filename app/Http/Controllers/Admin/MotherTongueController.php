<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyMotherTongueRequest;
use App\Http\Requests\StoreMotherTongueRequest;
use App\Http\Requests\UpdateMotherTongueRequest;
use App\Models\MotherTongue;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MotherTongueController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = MotherTongue::query()->select(sprintf('%s.*', (new MotherTongue)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewMotherTongue';
                $editFunct = 'editMotherTongue';
                $deleteFunct = 'deleteMotherTongue';
                $viewGate      = 'mother_tongue_show';
                $editGate      = 'mother_tongue_edit';
                $deleteGate    = 'mother_tongue_delete';
                $crudRoutePart = 'mother-tongues';

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
            $table->editColumn('mother_tongue', function ($row) {
                return $row->mother_tongue ? $row->mother_tongue : '';
            });

            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }

        return view('admin.motherTongues.index');
    }

    public function create()
    {
        abort_if(Gate::denies('community_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.motherTongues.index');
    }

    public function store(Request $request)
    {
        if (isset($request->name)) {
            if ($request->id == '') {
                $store = MotherTongue::create([
                    'mother_tongue' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'MotherTongue Created']);
            } else {
                $update = MotherTongue::where(['id' => $request->id])->update([
                    'mother_tongue' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'MotherTongue Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'MotherTongue Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = MotherTongue::where(['id' => $request->id])->select('id', 'mother_tongue')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = MotherTongue::where(['id' => $request->id])->select('id', 'mother_tongue')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(UpdateCommunityRequest $request, MotherTongue $MotherTongue)
    {
        $MotherTongue->update($request->all());

        return redirect()->route('admin.communities.index');
    }

    public function show(MotherTongue $MotherTongue)
    {
        abort_if(Gate::denies('community_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.motherTongues.index', compact('MotherTongue'));
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = MotherTongue::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'MotherTongue Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $religion = MotherTongue::find(request('ids'));

        foreach ($religion as $r) {
            $r->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'MotherTongue Deleted Successfully']);
    }
}
