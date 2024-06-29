<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HostelBlock;
use App\Models\HostelRoom;
use App\Models\RoomAllocationModel;
use App\Models\Student;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RoomAllocationController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('room_allot_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $allotedStudent = RoomAllocationModel::select('id', 'student', 'room_id')->get();

        $roomId = [];
        $stuWhereIn = [];
        $StuRoomId = [];
        if ($allotedStudent != null) {
            foreach ($allotedStudent as $value) {
                $decode = json_decode($value->student);
                $studentId = [];
                foreach ($decode as $val) {
                    $studentId['id'] = $value->id;
                    $studentId[] = $val->user_name_id;
                    $stuWhereIn[] = (string) $val->user_name_id;
                }
                $StuRoomId[$value->room_id] = $studentId;
            }
        } else {
            $studentId = [];
        }

        if ($request->ajax()) {

            $test = [];
            foreach ($StuRoomId as $idd => $stu) {
                foreach ($stu as $key => $value) {
                    if ($key == 'id') {
                        $id = $value;
                    }
                }
                $room = HostelRoom::where('id', $idd)->select('id', 'room_no', 'available_slots')->first();
                $query = Student::whereIn('user_name_id', $stu)->select('name', 'register_no')->get()->toArray();
                $names = array_map(function ($item) {
                    return $item['name'] . ' (' . $item['register_no'] . ')';
                }, $query);
                $stu_name = implode(',', $names);
                $test[] = [
                    'room_no' => $room->room_no,
                    'available_slots' => $room->available_slots,
                    'name' => $stu_name,
                    'id' => $id,
                ];
            }
            // dd($test);
            $table = DataTables::of($test);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewRoomAllot';
                $editFunct = 'editRoomAllot';
                $deleteFunct = 'deleteRoomAllot';
                $viewGate = 'room_allot_show';
                $editGate = 'room_allot_edit';
                $deleteGate = 'room_allot_delete';
                $crudRoutePart = 'room-allot';

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
                return $row['id'] ?? '';
            });
            $table->editColumn('room_no', function ($row) {
                return $row['room_no'] ? $row['room_no'] : '';
            });
            $table->editColumn('available_slots', function ($row) {
                return $row['available_slots'] ? $row['available_slots'] : 0;
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $student = DB::table('course_enroll_masters')
            // ->where('academic_years.status', '=', '1')
            // ->whereNull('academic_years.deleted_at')
            // ->leftJoin('course_enroll_masters', 'academic_years.id', '=', 'course_enroll_masters.academic_id')
            ->whereNull('course_enroll_masters.deleted_at')
            ->join('students', 'students.enroll_master_id', '=', 'course_enroll_masters.id')
            ->whereNull('students.deleted_at')
            ->whereNotIn('students.user_name_id', $stuWhereIn)
            ->select('students.name', 'students.user_name_id')
            ->get();

        $rooms = HostelRoom::where('filled_slots', '=', null)->orWhere('filled_slots', 0)->pluck('room_no', 'id');

        return view('admin.hostelRoomAllocation.index', compact('student', 'rooms'));
    }

    public function create()
    {
        abort_if(Gate::denies('hostel_block_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.hostel.index');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('room_allot_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // dd($request);
        if (isset($request->student)) {
            if ($request->id == '' || $request->id == null) {

                $student = Student::whereIn('user_name_id', $request->student)->select('enroll_master_id', 'user_name_id')->get()->toArray();
                if (count($student) > 0) {
                    $data = json_encode($student);
                    $hostel = HostelRoom::where('id', $request->hostel_room)->first();
                    $allot_room = RoomAllocationModel::where('room_id', $request->hostel_room)->count();
                    if ($allot_room <= 0) {
                        $allotment = RoomAllocationModel::create([
                            'room_id' => $request->hostel_room,
                            'student' => $data,
                            'hostel_id' => $hostel->hostel_id,
                        ]);
                    } else {
                        return response()->json(['status' => false, 'data' => 'Room Already Alloted']);
                    }

                    $count = count($request->student);
                    if ($count > 0) {
                        $update_room = HostelRoom::where('id', $request->hostel_room)->update([
                            'available_slots' => (int) $hostel->total_slots - $count,
                            'filled_slots' => $count,
                        ]);
                    }

                    $checking = DB::table('hostel_room')
                        ->rightJoin('room_allocation', 'room_allocation.room_id', '=', 'hostel_room.id')
                        ->select('room_allocation.room_id')
                        ->get();

                    return response()->json(['status' => true, 'data' => 'Room Alloted For Students']);

                } else {
                    return response()->json(['status' => false, 'data' => 'Students Not found']);

                }

            } else {
                $student = Student::whereIn('user_name_id', $request->student)->select('enroll_master_id', 'user_name_id')->get()->toArray();
                if (count($student) > 0) {
                    $data = json_encode($student);
                    $hostel = HostelRoom::where('id', $request->hostel_room)->first();
                    $allot_room = RoomAllocationModel::where('room_id', $request->hostel_room)->count();
                    if ($allot_room > 0) {
                        $check = RoomAllocationModel::whereNotIn('id', [$request->id])->where('room_id', $request->hostel_room)->count();
                        if ($check <= 0) {
                            $allotment = RoomAllocationModel::where('id', $request->id)->update([
                                'room_id' => $request->hostel_room,
                                'student' => $data,
                                'hostel_id' => $hostel->hostel_id,
                            ]);
                        } else {
                            return response()->json(['status' => false, 'data' => 'Room Already Alloted']);
                        }

                    } else {
                        return response()->json(['status' => false, 'data' => 'Room Already Alloted']);
                    }

                    $count = count($request->student);
                    if ($count > 0) {
                        $update_room = HostelRoom::where('id', $request->hostel_room)->update([
                            'available_slots' => (int) $hostel->total_slots - $count,
                            'filled_slots' => $count,
                        ]);
                    }

                    $checking = DB::table('hostel_room')
                        ->rightJoin('room_allocation', 'room_allocation.room_id', '=', 'hostel_room.id')
                        ->select('room_allocation.room_id')
                        ->get();

                    return response()->json(['status' => true, 'data' => 'Room Alloted For Students']);

                } else {
                    return response()->json(['status' => false, 'data' => 'Students Not found']);

                }
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Hostel Not Created']);
        }
    }

    public function view(Request $request)
    {
        abort_if(Gate::denies('room_allot_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $data = RoomAllocationModel::with('hostelRoom:id,room_no,available_slots,filled_slots,total_slots')->where(['id' => $request->id])->select('id', 'room_id', 'hostel_id', 'student', )->first();
            if ($data != null) {
                $user_name_id = [];
                $decoded = json_decode($data->student);
                foreach ($decoded as $key => $value) {
                    $user_name_id[] = $value->user_name_id;
                }

                $student = DB::table('course_enroll_masters')
                    // ->where('academic_years.status', '=', '1')
                    // ->whereNull('academic_years.deleted_at')
                    // ->leftJoin('course_enroll_masters', 'academic_years.id', '=', 'course_enroll_masters.academic_id')
                    ->whereNull('course_enroll_masters.deleted_at')
                    ->join('students', 'students.enroll_master_id', '=', 'course_enroll_masters.id')
                    ->whereNull('students.deleted_at')
                    ->select('students.name', 'students.user_name_id')
                    ->get();
                $hostel = HostelRoom::all();
                // dd($data->hostelRoom);
                return response()->json(['status' => true, 'data' => $data, 'user_name_id' => $user_name_id, 'student' => $student, 'hostel' => $hostel]);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        abort_if(Gate::denies('room_allot_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $data = RoomAllocationModel::with('hostelRoom:id,room_no,available_slots,filled_slots,total_slots')->where(['id' => $request->id])->select('id', 'room_id', 'hostel_id', 'student', )->first();
            if ($data != null) {
                $user_name_id = [];
                $decoded = json_decode($data->student);
                foreach ($decoded as $key => $value) {
                    $user_name_id[] = $value->user_name_id;
                }

                $student = DB::table('course_enroll_masters')
                    // ->where('academic_years.status', '=', '1')
                    // ->whereNull('academic_years.deleted_at')
                    // ->leftJoin('course_enroll_masters', 'academic_years.id', '=', 'course_enroll_masters.academic_id')
                    ->whereNull('course_enroll_masters.deleted_at')
                    ->join('students', 'students.enroll_master_id', '=', 'course_enroll_masters.id')
                    ->whereNull('students.deleted_at')
                    ->select('students.name', 'students.user_name_id')
                    ->get();
                $hostel = HostelRoom::all();
                // dd($hostel);
                return response()->json(['status' => true, 'data' => $data, 'user_name_id' => $user_name_id, 'student' => $student, 'hostel' => $hostel]);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function update(UpdateReligionRequest $request, RoomAllocationModel $RoomAllocationModel)
    {
        $RoomAllocationModel->update($request->all());

        return redirect()->route('admin.religions.index');
    }

    public function show(RoomAllocationModel $RoomAllocationModel)
    {
        abort_if(Gate::denies('religion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.religions.show', compact('RoomAllocationModel'));
    }

    public function destroy(Request $request)
    {
        abort_if(Gate::denies('room_allot_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $delete = RoomAllocationModel::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Hostel Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('room_allot_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $RoomAllocationModel = RoomAllocationModel::find(request('ids'));

        foreach ($RoomAllocationModel as $r) {
            $r->delete();
        }
        return response()->json(['status' => 'success', 'data' => 'Hostels Deleted Successfully']);
    }

    public function checkRoom(Request $request)
    {
        abort_if(Gate::denies('room_allot_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = HostelRoom::where('id', $request->room_id)->first()->toArray();
        if ($data != null) {
            // dd($data);
            return response()->json(['status' => true, 'data' => $data]);

        } else {
            return response()->json(['status' => false, 'data' => 'Hostel Room Not Found']);

        }
    }

    public function staffIndex(Request $request)
    {
        $role_id = auth()->user()->roles[0]->id;
        $hostel_id = auth()->user()->hostel_id;
        if ($role_id == 9) {
            $query = [];
            $hostel = HostelBlock::find($hostel_id);
            foreach ($hostel->roomAllot as $allote) {
                $decode = json_decode($allote->student);
                foreach ($decode as $value) {
                    $student = Student::where('user_name_id', $value->user_name_id)->select('user_name_id', 'name', 'Student_Phone_No')->first();
                    $student->room_no = HostelRoom::where('id', $allote->room_id)->first()->room_no;
                    $student->hostel_name = $hostel->name;
                    $student->id = $allote->id;
                    $query[] = $student;
                }
            }
            $count = count($query);
            $hostel_name = $hostel->name;
            if ($request->ajax()) {
                // dd($query);

                $table = DataTables::of($query);

                $table->addColumn('placeholder', '&nbsp;');
                $i = 0;
                $table->editColumn('id', function ($row) use (&$i) {
                    $i++;
                    return $i;
                });
                $table->editColumn('hostel', function ($row) {
                    return $row->hostel_name ? $row->hostel_name : '';
                });
                $table->editColumn('room_no', function ($row) {
                    return $row->room_no ? $row->room_no : '';
                });
                $table->editColumn('name', function ($row) {
                    return $row->name ? $row->name : '';
                });
                $table->editColumn('phone', function ($row) {
                    return $row->Student_Phone_No ? $row->Student_Phone_No : '';
                });

                $table->rawColumns(['actions', 'placeholder']);

                return $table->make(true);
            }
        }

        return view('admin.hostelRoomAllocation.staffIndex', compact('hostel_name', 'count'));

    }
}
