<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

class BusController extends Controller
{

    public function index(Request $request)
    {
        abort_if(Gate::denies('bus_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Bus::query()->select(sprintf('%s.*', (new Bus)->table));
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'bus_show';
                $viewFunct = 'viewBus';
                $editGate      = 'bus_edit';
                $editFunct = 'editBus';
                $deleteGate    = 'bus_delete';
                $deleteFunct = 'deleteBus';
                $crudRoutePart = 'bus';

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
            $table->editColumn('bus', function ($row) {
                return $row->bus_no ? $row->bus_no: '';
            });
            $table->editColumn('seats', function ($row) {
                return $row->total_seats ? $row->total_seats: '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.bus.index');
    }

    public function create()
    {
        return view('admin.Bus.create');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('bus_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->bus)) {
            if ($request->id == '') {
                $store = Bus::create([
                    'bus_no' => strtoupper($request->bus),
                    'total_seats' => $request->seats,
                    'available_seats' => $request->seats,
                ]);
                return response()->json(['status' => true, 'data' => 'Bus Created']);
            } else {
                $update = Bus::where(['id' => $request->id])->update([
                    'bus_no' => strtoupper($request->bus),
                    'total_seats' => $request->seats,
                    'available_seats' => $request->seats,
                ]);
                return response()->json(['status' => true, 'data' => 'Bus Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Bus Not Created']);
        }
    }


    public function edit(Request $request)
    {
        abort_if(Gate::denies('bus_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $data = Bus::where(['id' => $request->id])->select('id', 'bus_no', 'total_seats')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(Request $request, Bus $Bus)
    {

        
    }

    public function view(Request $request)
    {
        abort_if(Gate::denies('bus_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $data = Bus::where(['id' => $request->id])->select('id', 'bus_no', 'total_seats')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function show(Bus $Bus)
    {
        abort_if(Gate::denies('Bus_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.Bus.show', compact('Bus'));
    }

    public function destroy(Request $request)
    {
        abort_if(Gate::denies('bus_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $delete = Bus::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Bus Deleted Successfully']);
        } else {
            return response()->json(['status' => 'success', 'data' => 'Bus Deleted Successfully']);
        }
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('bus_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $Buss = Bus::find(request('ids'));

        foreach ($Buss as $Bus) {
            $Bus->delete();
        }

        return response()->json(['status' => 'success', 'data' => 'Buses Deleted Successfully']);
    }

}
