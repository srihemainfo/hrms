<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusRoute;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class BusRouteController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('bus_route_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = BusRoute::query()->select(sprintf('%s.*', (new BusRoute)->table));
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewBusRoute';
                $editFunct = 'editBusRoute';
                $deleteFunct = 'deleteBusRoute';
                $viewGate = 'bus_route_show';
                $editGate = 'bus_route_edit';
                $deleteGate = 'bus_route_delete';
                $crudRoutePart = 'bus-route';

                return view(
                    'partials.ajaxTableActions',
                    compact(
                        'viewGate',
                        'editGate',
                        'deleteGate',
                        'crudRoutePart',
                        'viewFunct',
                        'editFunct',
                        'deleteFunct',
                        'row'
                    )
                );
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('designation', function ($row) {
                return $row->designation ? $row->designation : '';
            });
            $table->editColumn('km', function ($row) {
                return $row->total_km ? $row->total_km : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.bus_route.index');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('bus_route_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request = json_decode($request['request']);
        // dd($request->bus_route_id);
        if ($request->bus_route_id == '' || $request->bus_route_id == null) {
            if ($request->count != '') {
                $record = [];
                for ($i = 1; (int) $request->count >= $i; $i++) {
                    $stop = 'stops' . $i;
                    $km = 'km' . $i;
                    $record[$i] = [$request->$stop => $request->$km];
                }
                $encoded_data = json_encode($record);
                $route = BusRoute::where(['designation' => $request->designation])->count();

                if ($route <= 0) {
                    $store = BusRoute::create(['designation' => $request->designation, 'total_km' => $request->km, 'stops' => $encoded_data]);
                    return response()->Json(['status' => true, 'data' => 'Route Created successfully']);

                } else {
                    return response()->json(['status' => false, 'data' => 'Route Already Exists']);
                }
            } else {
                return response()->json(['status' => false, 'data' => 'Technical Error']);

            }
        } else {
            // dd('hii', $request);
            if ($request->count != '') {
                $data = [];
                for ($i = 1; $request->count >= $i; $i++) {
                    $stop = 'stops' . $i;
                    $km = 'km' . $i;
                    $data[$i] = [$request->$stop => $request->$km];
                }
                $encoded_data = json_encode($data);

                $route = BusRoute::where(['id' => $request->bus_route_id])->count();
                if ($route > 0) {
                    $check = BusRoute::whereNotIn('id', [$request->bus_route_id])->where(['designation' => $request->designation])->count();
                    // dd($check);
                    if ($check <= 0) {
                        $store = BusRoute::where('id', $request->bus_route_id)->update(['designation' => $request->designation, 'total_km' => $request->km, 'stops' => $encoded_data]);
                        return response()->Json(['status' => true, 'data' => 'Route Updated successfully']);
                    } else {
                        return response()->Json(['status' => false, 'data' => 'Designation Already Exists']);
                    }

                } else {
                    return response()->Json(['status' => false, 'data' => 'Bus Route Not Available']);
                }
            } else {
                return response()->Json(['status' => false, 'data' => 'Technical Error']);
            }
        }
    }

    public function show(Request $request)
    {
        abort_if(Gate::denies('bus_route_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->id != '') {
            $data = BusRoute::where('id', $request->id)->first();
            if ($data != null) {
                $stop = json_decode($data->stops);
                $stopNo = [];
                $stops = [];
                foreach ($stop as $key => $value) {
                    foreach ($value as $subkey => $val) {
                        $stopNo[] = $key;
                        $stops[$subkey] = $val;
                    }
                }
                // dd($stopNo, $stops);

                return response()->json(['status' => true, 'stopNo' => $stopNo, 'stops' => $stops, 'data' => $data]);
            }
        }
    }

    public function edit(Request $request)
    {
        abort_if(Gate::denies('bus_route_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->id != '') {
            $data = BusRoute::where('id', $request->id)->first();
            if ($data != null) {
                $stop = json_decode($data->stops);
                $stopNo = [];
                $stops = [];
                foreach ($stop as $key => $value) {
                    foreach ($value as $subkey => $val) {
                        $stopNo[] = $key;
                        $stops[$subkey] = $val;
                    }
                }
                // dd($stopNo, $stops);

                return response()->json(['status' => true, 'stopNo' => $stopNo, 'stops' => $stops, 'data' => $data]);
            }
        }
    }

    public function destroy(Request $request)
    {
        abort_if(Gate::denies('bus_route_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $delete = BusRoute::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Route Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('bus_route_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $BusRoute = BusRoute::find(request('ids'));

        foreach ($BusRoute as $route) {
            $route->delete();

            return response()->json(['status' => 'success', 'data' => 'Routes Deleted Successfully']);
        }
    }
}
