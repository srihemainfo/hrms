<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HostelAttendance;
use App\Models\HostelBlock;
use App\Models\HostelRoom;
use App\Models\RoomAllocationModel;
use App\Models\Student;
use DateTime;
use Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class HostelAttendanceController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('hostel_attendance_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hostel = HostelBlock::pluck('name', 'id');
        $todayDate = date("Y-m-d");
        $attend = [];
        $check = HostelAttendance::where('date', 'LIKE', '%' . $todayDate . '%')->distinct()->pluck('hostel_id')->toArray();
        foreach ($check as $c) {
            $attend[] = $c;
        }
        return view('admin.hostelAttendance.index', compact('hostel', 'attend'));
    }

    public function get_student(Request $request)
    {
        abort_if(Gate::denies('hostel_attendance_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hostel_name = HostelBlock::where('id', $request->id)->select('name', 'id')->first();
        $allotedStudent = RoomAllocationModel::where('hostel_id', $request->id)->select('id', 'student', 'room_id')->get();
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
                    $stuWhereIn[] = $val->user_name_id;
                }
                $StuRoomId[$value->room_id] = $studentId;
            }
        } else {
            $studentId = [];
        }
        $studentName = [];
        $studentUserId = [];
        $studentEnroll = [];
        $studentRoomId = [];
        $studentRoomName = [];
        if (true) {
            foreach ($StuRoomId as $idd => $stu) {
                foreach ($stu as $key => $value) {
                    if ($key == 'id') {
                        $id = $value;
                    }
                }
                $room = HostelRoom::where('id', $idd)->select('id', 'room_no', 'available_slots')->first();
                // dd($room);
                if ($room != null) {
                    $query = Student::whereIn('user_name_id', $stu)->select('user_name_id', 'name', 'register_no', 'enroll_master_id')->get()->toArray();
                    foreach ($query as $key => $value) {
                        $studentName[] = $value['name'] . ' (' . $value['register_no'] . ')';
                        $studentUserId[] = $value['user_name_id'];
                        $studentEnroll[] = $value['enroll_master_id'];
                        $studentRoomId[] = $idd;
                        $studentRoomName[] = $room->room_no;
                    }
                }
            }
        }

        // dd($studentName, $studentUserId, $studentEnroll, $studentRoomId, $studentRoomName);
        return response()->json(['status' => true, 'studentName' => $studentName, 'studentUserId' => $studentUserId, 'studentEnroll' => $studentEnroll, 'studentRoomId' => $studentRoomId, 'hostel_name' => $hostel_name, 'studentRoomName' => $studentRoomName]);
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('hostel_attendance_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = json_decode($request['request']);
        // dd($data);
        if ($data->hostel_id == null) {
            if ((int) $data->row_count > 0) {
                // dd('hii');
                $hostelRoom = new HostelRoom();
                $id = $hostelRoom->where('id', $data->room_id0)->select('hostel_id')->first();
                $check = HostelAttendance::where(['date' => $data->date, 'day_type' => $data->day_type, 'hostel_id' => $id->hostel_id])->count();
                // dd($check);
                if ($check <= 0) {
                    for ($i = 0; (int) $data->row_count > $i; $i++) {
                        $date = $data->date;
                        $d = new DateTime($date);
                        $day = $d->format('l');
                        $user_id = 'user_id' . $i;
                        $room_id = 'room_id' . $i;
                        $mainBox = 'mainBox' . $i;
                        $enroll_id = 'enroll_id' . $i;
                        $attendance = new HostelAttendance();
                        // dd($enroll_id);
                        if ($check > 0) {
                            $user_id = '';
                            $room_id = '';
                            $mainBox = '';
                        } else {
                            $hostel_id = $hostelRoom->where('id', $data->$room_id)->select('hostel_id')->first();
                            $attendance->day = $day ?? null;
                            $attendance->day_type = $data->day_type ?? null;
                            $attendance->date = $data->date ?? null;
                            $attendance->user_name_id = $data->$user_id ?? null;
                            $attendance->attendance = $data->$mainBox ?? null;
                            $attendance->room_id = $data->$room_id ?? null;
                            $attendance->enroll_master_id = $data->$enroll_id ?? null;
                            $attendance->hostel_id = $hostel_id->hostel_id ?? null;
                            $attendance->save();
                        }

                        $user_id = '';
                        $room_id = '';
                        $mainBox = '';

                    }

                    return response()->json(['status' => true, 'data' => 'Attendance Taken Successfully']);
                } else {
                    return response()->json(['status' => false, 'data' => 'Already Attendance Taken']);
                }

            } else {
                return response()->json(['status' => false, 'data' => 'Students Not Selected']);
            }
        } else {
            if ((int) $data->row_count > 0) {
                for ($i = 0; (int) $data->row_count > $i; $i++) {
                    $date = $data->date;
                    $d = new DateTime($date);
                    $day = $d->format('l');
                    $user_id = 'user_id' . $i;
                    $room_id = 'room_id' . $i;
                    $mainBox = 'mainBox' . $i;
                    $enroll_id = 'enroll_id' . $i;
                    $id = 'id' . $i;
                    $attendance = new HostelAttendance();
                    $hostelRoom = new HostelRoom();
                    $check = $attendance->where(['user_name_id' => $data->$user_id, 'day_type' => $day])->count();
                    if ($check > 0) {
                        $user_id = '';
                        $room_id = '';
                        $mainBox = '';
                    } else {
                        // dd($user_id, $room_id, $mainBox, $enroll_id, $id);
                        $attendance->where('id', $data->$id)->update([
                            'day' => $day,
                            'day_type' => $data->day_type,
                            'date' => $data->date,
                            'user_name_id' => $data->$user_id,
                            'attendance' => $data->$mainBox,
                            'room_id' => $data->$room_id,
                            'enroll_master_id' => $data->$enroll_id,
                            'hostel_id' => $data->hostel_id
                        ]);
                    }
                    $user_id = '';
                    $room_id = '';
                    $mainBox = '';
                }
                return response()->json(['status' => true, 'data' => 'Attendance Updated Successfully']);
            } else {
                return response()->json(['status' => false, 'data' => 'Attendance Not Yet To Taken']);
            }
        }
    }

    public function view_attendance(Request $request)
    {
        abort_if(Gate::denies('hostel_attendance_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->date != '' && $request->day != '') {
            $hostel = HostelAttendance::where(['date' => $request->date, 'day_type' => $request->day, 'hostel_id' => $request->hostel_id])->get();
            $studentName = [];
            $roomNo = [];
            $count = count($hostel);
            if ($hostel != null && $count > 0) {
                // dd('hii');
                foreach ($hostel as $value) {
                    $student = Student::where('user_name_id', $value->user_name_id)->first();
                    $room = HostelRoom::where('id', $value->room_id)->first();
                    $value->student_name = $student->name;
                    $value->room_no = $room->room_no;
                }
                return response()->json(['status' => true, 'data' => $hostel]);
            } else {
                return response()->json(['status' => false, 'data' => 'Attendance Not Taken For this Date or Day']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Enter Day and Date']);
        }
    }

    public function reportIndex(Request $request)
    {
        abort_if(Gate::denies('hostel_attendance_report_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $role = auth()->user()->roles[0]->id;
        $hostel_id = auth()->user()->hostel_id;
        if ($role == 20) {
            $hostel = HostelBlock::where('id', $hostel_id)->pluck('name', 'id');
        } else {
            $hostel = HostelBlock::pluck('name', 'id');
        }
        return view('admin.hostel_attendance_report.index', compact('hostel'));
    }
    public function get_report(Request $request)
    {
        abort_if(Gate::denies('hostel_attendance_report_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request);
        $data = DB::table('hostel_attendance')
            ->where('hostel_attendance.date', $request->date)
            ->where('hostel_attendance.day_type', $request->day)
            ->where('hostel_attendance.hostel_id', $request->hostel_id)
            ->whereNull('hostel_attendance.deleted_at')
            ->leftJoin('students', 'students.user_name_id', '=', 'hostel_attendance.user_name_id')
            ->leftJoin('hostel_room', 'hostel_room.id', '=', 'hostel_attendance.room_id')
            ->leftJoin('hostel_block', 'hostel_block.id', '=', 'hostel_attendance.hostel_id')
            ->select('hostel_attendance.id', 'students.name', 'students.register_no', 'hostel_attendance.attendance', 'hostel_room.room_no', 'hostel_block.name as hostel_name')
            ->get();
        // dd($data);
        return response()->json(['status' => true, 'data' => $data]);
    }
}
