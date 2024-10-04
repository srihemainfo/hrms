<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseEnrollMaster;
use App\Models\HostelBlock;
use App\Models\HostelRoom;
use App\Models\HostelWardenModel;
use App\Models\RoomAllocationModel;
use App\Models\Student;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class HostelStudentController extends Controller
{
    public function index(Request $request)
    {
        $id = auth()->user()->id;
        $hostel_name = '';
        $stu_count = 0;
        $check = HostelWardenModel::with('hostel')->where('warden_id', $id)->first();
        $room = $check->hostel->hostelRoom;
        $data = [];
        foreach ($check->hostel->roomAllot as $stu) {
            $decode = json_decode($stu->student);
            foreach ($decode as $code) {
                $get_name = Student::where('user_name_id', $code->user_name_id)->value('name');
                $enroll = CourseEnrollMaster::where('id', $code->enroll_master_id)->value('enroll_master_number');
                $get_room = HostelRoom::where('id', $stu->room_id)->value('room_no');
                $hostel_name = $check->hostel->name;
                $data[] = [
                    'name' => $get_name,
                    'enroll' => $enroll,
                    'hostel_name' => $check->hostel->name,
                    'room_no' => $get_room
                ];

            }
        }
        $stu_count = count($data);
        if ($request->ajax()) {
            // dd($test);
            $table = DataTables::of($data);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            // $table->editColumn('actions', function ($row) {
            //     $viewFunct = 'viewRoomAllot';
            //     $editFunct = 'editRoomAllot';
            //     $deleteFunct = 'deleteRoomAllot';
            //     $viewGate = 'room_allot_show';
            //     $editGate = 'room_allot_edit';
            //     $deleteGate = 'room_allot_delete';
            //     $crudRoutePart = 'room-allot';

            //     return view(
            //         'partials.ajaxTableActions',
            //         compact(
            //             'viewGate',
            //             'editGate',
            //             'deleteGate',
            //             'crudRoutePart',
            //             'viewFunct',
            //             'editFunct',
            //             'deleteFunct',
            //             'row'
            //         )
            //     );
            // });
            $i = 0;
            $table->editColumn('sno', function ($row) use (&$i) {
                return $i += 1;
            });

            $table->editColumn('room_no', function ($row) {
                return $row['room_no'] ? $row['room_no'] : '';
            });
            $table->editColumn('student', function ($row) {
                return $row['name'] ? $row['name'] : '';
            });
            $table->editColumn('hostel_name', function ($row) {
                return $row['hostel_name'] ? $row['hostel_name'] : 0;
            });
            $table->editColumn('enroll', function ($row) {
                return $row['enroll'] ? $row['enroll'] : 0;
            });

            $table->rawColumns(['placeholder']);

            return $table->make(true);
        }



        return view('admin.hostel_student.index', compact('hostel_name', 'stu_count', 'room'));
    }

    // public function getRoom(Request $request)
    // {
    //     if ($request->hostel) {
    //         $get_data = HostelRoom::where('hostel_id', $request->hostel)->pluck('room_no', 'id');
    //         if ($get_data) {
    //             return response()->json(['status' => true, 'data' => $get_data]);
    //         } else {
    //             return response()->json(['status' => false, 'data' => 'Room Not Available.']);
    //         }
    //     }
    // }


    public function search(Request $request)
    {

        $id = auth()->user()->id;
        $check = HostelWardenModel::where('warden_id', $id)->first();
        // $room = $check->hostel->hostelRoom;

        if ($request->room) {
            $get_data = RoomAllocationModel::where(['hostel_id' => $check->hostel->id, 'room_id' => $request->room])->get();
            $datas = [];
            if ($get_data) {
                foreach ($get_data as $key => $data) {
                    $decode = json_decode($data->student);
                    foreach ($decode as $code) {
                        $get_name = Student::where('user_name_id', $code->user_name_id)->value('name');
                        $enroll = CourseEnrollMaster::where('id', $code->enroll_master_id)->value('enroll_master_number');
                        $get_room = HostelRoom::where('id', $data->room_id)->first();
                        $datas[] = [
                            // 'id' => $k + 1,
                            'name' => $get_name,
                            'enroll' => $enroll,
                            'hostel_name' => $get_room->hostel->name,
                            'room_no' => $get_room->room_no
                        ];

                    }
                }
            }
            // dd($datas);
            if ($datas) {
                return response()->json(['status' => true, 'data' => $datas]);
            } else {
                return response()->json(['status' => false, 'data' => 'Students Not Available.']);
            }
        }
    }

    public function takeAttendance(Request $request)
    {
        return view('admin.hostelAttendance.staffIndex');
    }
}
