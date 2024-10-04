<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HostelBlock;
use App\Models\HostelRoom;
use Carbon\Carbon;
use DB;
use FontLib\Table\Type\name;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class HostelBlockController extends Controller
{
    public function index(Request $request)
    {

        abort_if(Gate::denies('hostel_block_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = HostelBlock::query()->select(sprintf('%s.*', (new HostelBlock)->table));
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewHostel';
                $editFunct = 'editHostel';
                $deleteFunct = 'deleteHostel';
                $viewGate = 'hostel_block_show';
                $editGate = 'hostel_block_edit';
                $deleteGate = 'hostel_block_delete';
                $crudRoutePart = 'hostelBlock';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        return view('admin.hostel.index');
    }

    public function create()
    {
        abort_if(Gate::denies('hostel_block_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.hostel.index');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('hostel_block_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->name)) {
            if ($request->id == '') {
                $store = HostelBlock::create([
                    'name' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'Hostel Created']);
            } else {
                $update = HostelBlock::where(['id' => $request->id])->update([
                    'name' => strtoupper($request->name),
                ]);
                return response()->json(['status' => true, 'data' => 'Hostel Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Hostel Not Created']);
        }
    }

    public function view(Request $request)
    {
        abort_if(Gate::denies('hostel_block_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $data = HostelBlock::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        abort_if(Gate::denies('hostel_block_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $data = HostelBlock::where(['id' => $request->id])->select('id', 'name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(UpdateReligionRequest $request, HostelBlock $HostelBlock)
    {
        $HostelBlock->update($request->all());

        return redirect()->route('admin.religions.index');
    }

    public function show(HostelBlock $HostelBlock)
    {
        abort_if(Gate::denies('religion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.religions.show', compact('HostelBlock'));
    }

    public function destroy(Request $request)
    {
        abort_if(Gate::denies('hostel_block_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $delete = HostelBlock::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now()
            ]);
            return response()->json(['status' => 'success', 'data' => 'Hostel Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('hostel_block_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $HostelBlock = HostelBlock::find(request('ids'));

        foreach ($HostelBlock as $r) {
            $r->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'Hostels Deleted Successfully']);
    }

    public function roomIndex(Request $request)
    {

        abort_if(Gate::denies('hostel_room_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = HostelRoom::with('hostel:id,name')->select(sprintf('%s.*', (new HostelRoom)->table))->get();
            $table = DataTables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewHostelRoom';
                $editFunct = 'editHostelRoom';
                $deleteFunct = 'deleteHostelRoom';
                $viewGate = 'hostel_room_show';
                $editGate = 'hostel_room_edit';
                $deleteGate = 'hostel_room_delete';
                $crudRoutePart = 'hostelRoom';

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
            $table->editColumn('hostel', function ($row) {
                return $row->hostel->name ? $row->hostel->name : '';
            });
            $table->editColumn('HostelRoom_No', function ($row) {
                return $row->room_no ? $row->room_no : '';
            });
            $table->editColumn('total_seat', function ($row) {
                return $row->total_slots != null ? $row->total_slots : 0;
            });
            $table->editColumn('available_seat', function ($row) {
                return $row->available_slots != null ? $row->available_slots : 0;
            });
            $table->editColumn('filled_seat', function ($row) {
                return $row->filled_slots != null ? $row->filled_slots : 0;
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $hostel = HostelBlock::pluck('name', 'id');
        return view('admin.hostelRoom.index', compact('hostel'));
    }

    public function roomStore(Request $request)
    {
        abort_if(Gate::denies('hostel_room_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // dd($request);
        if (isset($request->hostel)) {
            if ($request->id == '') {
                $room = HostelRoom::where('hostel_id', $request->hostel)->get();
                if ($room) {
                    if ($request->hostel == 1) {
                        $concat = 'BH';
                    } else {
                        $concat = 'GH';
                    }
                    $room_no = count($room) + 1;
                    if ($room_no) {
                        $seat = $request->seat != null ? $request->seat : '4';

                        $create_room = new HostelRoom();
                        for ($i = 1; $i <= $request->hostel_room; $i++) {
                            $create_room->create([
                                'room_no' => $concat . $room_no,
                                'hostel_id' => $request->hostel,
                                'total_slots' => $seat,
                                'available_slots' => $seat,
                            ]);
                            $room_no += 1;
                        }
                    }
                    return response()->json(['status' => true, 'data' => 'HostelRoom Created']);
                }
            } else {
                // dd($request);
                $count = HostelRoom::where(['id' => $request->id])->count();
                if ($count > 0) {

                    $check = HostelRoom::whereNotIn('id', [$request->id])->where('room_no', $request->hostel_room)->count();
                    if ($check <= 0) {
                        $update = HostelRoom::where('id', '=', $request->id)->update([
                            'room_no' => $request->hostel_room,
                            'total_slots' => $request->total,
                            'available_slots' => $request->available,
                            'filled_slots' => $request->filled,
                        ]);
                        return response()->json(['status' => true, 'data' => 'HostelRoom Updated']);
                    } else {
                        return response()->json(['status' => false, 'data' => 'HostelRoom Already Exists']);

                    }

                } else {
                    return response()->json(['status' => false, 'data' => 'HostelRoom is Not Available']);
                }
            }
        } else {
            return response()->json(['status' => false, 'data' => 'HostelRoom Not Created']);
        }
    }

    public function roomView(Request $request)
    {
        abort_if(Gate::denies('hostel_room_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $data = HostelRoom::where(['id' => $request->id])->select('id', 'room_no', 'hostel_id', 'total_slots', 'available_slots', 'filled_slots')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function roomEdit(Request $request)
    {
        abort_if(Gate::denies('hostel_room_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $data = HostelRoom::where(['id' => $request->id])->select('id', 'room_no', 'hostel_id', 'total_slots', 'available_slots', 'filled_slots')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function roomDestroy(Request $request)
    {
        abort_if(Gate::denies('hostel_room_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $delete = HostelRoom::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Hostel Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function roomMassDestroy(Request $request)
    {
        abort_if(Gate::denies('hostel_room_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $HostelBlock = HostelRoom::find(request('ids'));

        foreach ($HostelBlock as $r) {
            $r->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'Hostels Deleted Successfully']);
    }

    public function roomStaffIndex(Request $request)
    {
        $role_id = auth()->user()->roles[0]->id;
        $hostel_id = auth()->user()->hostel_id;
        if ($role_id == 9) {
            $room = HostelRoom::where('hostel_id', $hostel_id)->select('id', 'room_no', 'total_slots', 'filled_slots', 'available_slots', 'hostel_id')->get();
            $slots = HostelRoom::where('hostel_id', $hostel_id)
                ->selectRaw('SUM(available_slots) as available_slots, SUM(filled_slots) as filled_slots')
                ->first();

            $available = $slots->available_slots;
            $filled = $slots->filled_slots;

            // dd($available, $filled);
            $hostel_name = $room[0]->hostel->name;
            $room_count = count($room);
            if ($request->ajax()) {
                $table = DataTables::of($room);
                $table->addColumn('placeholder', '&nbsp;');
                $table->addColumn('actions', '&nbsp;');

                $table->editColumn('actions', function ($row) {
                    $viewFunct = 'viewHostelRoom';
                    $editFunct = 'editHostelRoom';
                    $deleteFunct = 'deleteHostelRoom';
                    $viewGate = 'hostel_room_show';
                    $editGate = 'hostel_room_edit';
                    $deleteGate = 'hostel_room_delete';
                    $crudRoutePart = 'hostelRoom';

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
                $table->editColumn('hostel', function ($row) {
                    // dd($row->hostel->name);
                    return $row->hostel->name ? $row->hostel->name : '';
                });
                $table->editColumn('HostelRoom_No', function ($row) {
                    return $row->room_no ? $row->room_no : '';
                });
                $table->editColumn('total_seat', function ($row) {
                    return $row->total_slots != null ? $row->total_slots : 0;
                });
                $table->editColumn('available_seat', function ($row) {
                    return $row->available_slots != null ? $row->available_slots : 0;
                });
                $table->editColumn('filled_seat', function ($row) {
                    return $row->filled_slots != null ? $row->filled_slots : 0;
                });

                $table->rawColumns(['actions', 'placeholder']);

                return $table->make(true);
            }
        }

        return view('admin.hostelRoom.staffIndex', compact('hostel_name', 'room_count', 'available', 'filled'));
    }

}
