<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HostelBlock;
use App\Models\HostelWardenModel;
use App\Models\NonTeachingStaff;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class HostelWardenController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $query = HostelWardenModel::with('non_teaching', 'hostel')->get();
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'hostel_warden_show';
                $editGate = 'hostel_warden_edit';
                $deleteGate = 'hostel_warden_delete';
                $crudRoutePart = 'hostel-warden';
                $viewFunct = 'viewHostelWarden';
                $editFunct = 'editHostelWarden';
                $deleteFunct = 'deleteHostelWarden';

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
            $table->editColumn('warden', function ($row) {
                return $row->non_teaching->name ? $row->non_teaching->name : '';
            });
            $table->editColumn('hostel', function ($row) {
                return $row->hostel->name ? $row->hostel->name : '';
            });
            $table->editColumn('phone', function ($row) {
                return $row->non_teaching->phone ? $row->non_teaching->phone : '';
            });
            $table->editColumn('staff_code', function ($row) {
                return $row->non_teaching->StaffCode ? $row->non_teaching->StaffCode : '';
            });

            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }

        $warden = NonTeachingStaff::where(['role_type'=> 2, 'Designation'=> 'Hostel Warden'])->pluck('name', 'user_name_id');
        $hostel = HostelBlock::pluck('name', 'id');

        return view('admin.hostel_warden.index', compact('warden', 'hostel'));
    }

    public function create()
    {
        abort_if(Gate::denies('nationality_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.nationalities.create');
    }

    public function store(Request $request)
    {
        // dd($request);
        if (isset($request->warden) && isset($request->hostel)) {
            if ($request->id == '') {
                $store = HostelWardenModel::create([
                    'warden_id' => $request->warden,
                    'hostel_id' => $request->hostel
                ]);
                return response()->json(['status' => true, 'data' => 'Hostel Warden Is Alloted.']);
            } else {
                $update = HostelWardenModel::where(['id' => $request->id])->update([
                    'warden_id' => $request->warden,
                    'hostel_id' => $request->hostel
                ]);
                return response()->json(['status' => true, 'data' => 'Hostel Warden Updated.']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Hostel Warden Not Alloted.']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = HostelWardenModel::where(['id' => $request->id])->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = HostelWardenModel::where(['id' => $request->id])->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(UpdateHostelWardenModelRequest $request, HostelWardenModel $nationality)
    {
        $nationality->update($request->all());

        return redirect()->route('admin.nationalities.index');
    }

    public function show(HostelWardenModel $nationality)
    {
        abort_if(Gate::denies('nationality_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.nationalities.show', compact('nationality'));
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = HostelWardenModel::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Hostel Warden Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $warden = HostelWardenModel::find(request('ids'));

        foreach ($warden as $n) {
            $n->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'Hostel Warden Deleted Successfully']);
    }
}
