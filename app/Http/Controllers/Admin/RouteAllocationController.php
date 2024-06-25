<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Driver;
use App\Models\NonTeachingStaff;
use App\Models\RouteAllocation;
use Carbon\Carbon;
use DB;
use Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RouteAllocationController extends Controller
{

    public function index(Request $request)
    {
        abort_if(Gate::denies('route_allot_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DB::table('route_allocation')
                ->whereNull('route_allocation.deleted_at')
                ->leftJoin('non_teaching_staffs', 'non_teaching_staffs.user_name_id', '=', 'route_allocation.driver_id')
                ->whereNull('non_teaching_staffs.deleted_at')
                ->leftJoin('bus_route', 'bus_route.id', '=', 'route_allocation.designation_id')
                ->whereNull('bus_route.deleted_at')
                ->leftJoin('bus', 'bus.id', '=', 'route_allocation.bus_id')
                ->whereNull('bus.deleted_at')
                ->select('route_allocation.id', 'non_teaching_staffs.user_name_id', 'non_teaching_staffs.StaffCode', 'non_teaching_staffs.phone', 'non_teaching_staffs.name', 'bus.bus_no', 'bus_route.designation')
                ->get();

            // dd($query);
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewRouteAllot';
                $editFunct = 'editRouteAllot';
                $deleteFunct = 'deleteRouteAllot';
                $viewGate = 'route_allot_show';
                $editGate = 'route_allot_edit';
                $deleteGate = 'route_allot_delete';
                $crudRoutePart = 'route-allot';

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
            $table->editColumn('bus', function ($row) {
                return $row->bus_no ? $row->bus_no : '';
            });
            $table->editColumn('driver', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('phone', function ($row) {
                return $row->phone ? $row->phone : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        $bus = Bus::pluck('bus_no', 'id');
        $bus_route = BusRoute::pluck('designation', 'id');
        $driver = NonTeachingStaff::where(['Designation'=> 'Driver', 'role_type'=> '5'])->select('user_name_id','StaffCode', 'phone', 'name')->get();
        return view('admin.route_allot.index', compact('bus', 'bus_route', 'driver'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('route_allot_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->route_allot_id == '' || $request->route_allot_id == null) {
            $route = RouteAllocation::where(['bus_id' => $request->bus, 'driver_id' => $request->driver])->orWhere(['bus_id' => $request->bus, 'designation_id' => $request->designation])->count();
            if ($route <= 0) {
                $store = RouteAllocation::create(['designation_id' => $request->designation, 'bus_id' => $request->bus, 'driver_id' => $request->driver]);
                return response()->json(['status' => true, 'data' => "Driver Assigned Successfully"]);
            } else {
                return response()->json(['status' => false, 'data' => "Driver Already Assigned For Another Route"]);
            }
        } else {
            if ($request->count != '') {
                $route = RouteAllocation::where(['id' => $request->route_allot_id])->count();
                if ($route > 0) {
                    $check = RouteAllocation::whereNotIn('id', [$request->route_allot_id])->where(['designation' => $request->designation, 'driver_id' => $request->driver])->count();
                    // dd($check);
                    if ($check <= 0) {
                        $store = RouteAllocation::where('id', $request->route_allot_id)->update(['designation' => $request->designation, 'bus_id' => $request->bus, 'driver_id' => $request->driver]);
                        return response()->json(['status' => true, 'data' => "Data Updated Successfully"]);

                    } else {
                        return response()->json(['status' => false, 'data' => "Driver Already Exists For Another Route"]);
                    }

                } else {
                    return redirect()->route('admin.bus-route.index');
                }
            } else {
                return redirect()->route('admin.bus-route.index')->with('error', 'Technical Error');
            }
        }
    }

    public function view(Request $request)
    {
        abort_if(Gate::denies('route_allot_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        if ($request->id != '') {
            $data = RouteAllocation::where('id', $request->id)->first();
            if ($data != null) {
                return response()->json(['status' => true, 'data' => $data]);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Technical Error']);
        }
    }

    public function edit(Request $request)
    {
        abort_if(Gate::denies('route_allot_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->id != '') {
            $data = RouteAllocation::where('id', $request->id)->first();
            if ($data != null) {
                return response()->json(['status' => true, 'data' => $data]);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Technical Error']);
        }
    }

    public function destroy(Request $request)
    {
        abort_if(Gate::denies('route_allot_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $delete = RouteAllocation::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Data Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('route_allot_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $RouteAllocation = RouteAllocation::find(request('ids'));

        foreach ($RouteAllocation as $route) {
            $route->delete();

            return response()->json(['status' => 'success', 'data' => 'Data Deleted Successfully']);
        }
    }
}
