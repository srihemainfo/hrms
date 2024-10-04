<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusRoute;
use App\Models\BusStudent;
use App\Models\CourseEnrollMaster;
use App\Models\Student;
use Carbon\Carbon;
use DB;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

class BusStudentController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('bus_student_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        if ($request->ajax()) {
            $query = DB::table('bus_student')
                ->whereNull('bus_student.deleted_at')
                ->leftJoin('route_allocation', 'route_allocation.designation_id', '=', 'bus_student.designation_id')
                ->whereNull('route_allocation.deleted_at')
                ->leftJoin('bus_route', 'bus_route.id', '=', 'bus_student.designation_id')
                ->whereNull('bus_route.deleted_at')
                ->leftJoin('bus', 'bus.id', '=', 'route_allocation.bus_id')
                ->whereNull('bus.deleted_at')
                ->select('bus_student.id', 'bus.bus_no', 'bus_route.designation', 'bus_student.stop_name', 'bus_student.student_id')
                ->get();

            // dd($query);
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewBusStudent';
                $editFunct = 'editBusStudent';
                $deleteFunct = 'deleteBusStudent';
                $viewGate = 'bus_student_show';
                $editGate = 'bus_student_edit';
                $deleteGate = 'bus_student_delete';
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
            $table->editColumn('bus', function ($row) {
                return $row->bus_no ? $row->bus_no : '';
            });
            $table->editColumn('student_count', function ($row) {
                $student = json_decode($row->student_id);
                $count = count($student);
                return $count ? $count : '';
            });
            $table->editColumn('stops', function ($row) {
                return $row->stop_name ? $row->stop_name : '';
            });
            $table->editColumn('designation', function ($row) {
                return $row->designation ? $row->designation : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        $student = [];

        $bus_student = BusStudent::select('student_id')->get();
        foreach ($bus_student as $row) {
            $data = json_decode($row->student_id);
            foreach ($data as $key => $value) {
                $student[] = $value;
            }
        }
        $allStudent = DB::table('course_enroll_masters')
            ->join('students', 'students.enroll_master_id', '=', 'course_enroll_masters.id')
            ->whereNull('students.deleted_at')
            ->select('students.name', 'students.user_name_id')
            ->get();

        // dd($student);
        $student = DB::table('course_enroll_masters')
            ->whereNull('course_enroll_masters.deleted_at')
            ->join('students', 'students.enroll_master_id', '=', 'course_enroll_masters.id')
            ->whereNotIn('students.user_name_id', $student)
            ->whereNull('students.deleted_at')
            ->select('students.name', 'students.user_name_id')
            ->get();


        // dd(count($allStudent));
        $designation = BusRoute::pluck('designation', 'id');
        return view('admin.bus_student.index', compact('student', 'designation', 'allStudent'));
    }
    public function store(Request $request)
    {
        abort_if(Gate::denies('bus_student_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->id == '') {
            $check = BusStudent::where(['stop_id' => $request->stops, 'designation_id' => $request->designation])->first();
            if ($check != null) {
                return response()->json(['status' => false, 'data' => 'Stop for the Students Already Created']);
            } else {
                $student = json_encode($request->student);
                $isBusFull = DB::table('route_allocation')
                    ->where('designation_id', $request->designation)
                    ->leftJoin('bus', 'route_allocation.bus_id', '=', 'bus.id')
                    ->whereColumn('bus.total_seats', '=', 'bus.filled_seats')
                    ->get();
                if (count($isBusFull) > 0) {
                    return response()->json(['status' => false, 'data' => 'Bus Full']);

                } else {
                    $data = BusStudent::create(['designation_id' => $request->designation, 'stop_id' => $request->stops, 'stop_name' => $request->stop_name, 'student_id' => $student]);
                    if (count($request->student) > 0) {

                        $available_seats = DB::table('route_allocation')
                            ->where('designation_id', $request->designation)
                            ->leftJoin('bus', 'route_allocation.bus_id', '=', 'bus.id')
                            ->select('bus.available_seats', 'bus.filled_seats')
                            ->first();

                        $available_seats_count = $available_seats->available_seats == 0 ? 0 : $available_seats->available_seats;
                        $filled_seats_count = $available_seats->filled_seats == null ? 0 : $available_seats->filled_seats;

                        $bus = DB::table('route_allocation')
                            ->where('designation_id', $request->designation)
                            ->leftJoin('bus', 'route_allocation.bus_id', '=', 'bus.id')
                            ->update([
                                'bus.available_seats' => DB::raw($available_seats_count - count($request->student)),
                                'bus.filled_seats' => DB::raw($filled_seats_count + count($request->student)),
                            ]);
                    }
                    return response()->json(['status' => true, 'data' => 'Student Assigned Successfully']);

                }

            }
        } else {

            $check = BusStudent::where('stop_id', $request->stops)->first();
            if ($check == null) {
                return response()->json(['status' => false, 'data' => 'Stop for the Students Not Created']);
            } else {
                $check = BusStudent::where('id', $request->id)->first();
                $previous_student = 0;
                if ($check != null) {
                    $previous_student = json_decode($check->student_id);
                    // dd($previous_student);
                    $student = json_encode($request->student);
                }

                if (count($request->student) > 0) {
                    $count = count($request->student);
                    $available_seats = DB::table('route_allocation')
                        ->where('designation_id', $request->designation)
                        ->leftJoin('bus', 'route_allocation.bus_id', '=', 'bus.id')
                        ->select('bus.available_seats', 'bus.filled_seats', 'bus.total_seats')
                        ->first();

                    $get_student = [];
                    $total_student = BusStudent::where('bus_student.designation_id', $request->designation)->select('student_id')->get()->toArray();
                    foreach ($total_student as $key => $value) {
                        // dd($value);
                        $decode = json_decode($value['student_id']);
                        foreach ($decode as $k => $dec) {
                            $get_student[] = $dec;
                        }
                    }

                    $total_student_count = count($get_student);

                    $available_seats_count = $count > count($previous_student) ? $count - count($previous_student) : count($previous_student) - $count;
                    $total_seats_count = $available_seats->total_seats == null ? 0 : $available_seats->total_seats;
                    // dd($count > count($previous_student), $count, count($previous_student));
                    // $filled_seats_count = $available_seats->filled_seats == null ? 0 : $available_seats->filled_seats;
                    if ($count > count($previous_student)) {
                        $bus = DB::table('route_allocation')
                            ->where('designation_id', $request->designation)
                            ->leftJoin('bus', 'route_allocation.bus_id', '=', 'bus.id')
                            ->update([
                                'bus.filled_seats' => $total_student_count + $available_seats_count,
                                'bus.available_seats' => $total_seats_count - ($total_student_count + $available_seats_count),
                            ]);
                    } elseif ($count == count($previous_student)) {
                        $bus = DB::table('route_allocation')
                            ->where('designation_id', $request->designation)
                            ->leftJoin('bus', 'route_allocation.bus_id', '=', 'bus.id')
                            ->update([
                                'bus.filled_seats' => $total_student_count,
                                'bus.available_seats' => $total_seats_count,
                            ]);
                    } else {
                        $bus1 = DB::table('route_allocation')
                            ->where('designation_id', $request->designation)
                            ->leftJoin('bus', 'route_allocation.bus_id', '=', 'bus.id')
                            ->update([
                                'bus.filled_seats' => $total_student_count - $available_seats_count,
                                'bus.available_seats' => $total_seats_count - ($total_student_count - $available_seats_count),
                            ]);
                    }
                    $data = BusStudent::where('id', $request->id)->update(['designation_id' => $request->designation, 'stop_id' => $request->stops, 'stop_name' => $request->stop_name, 'student_id' => $student]);
                }
                return response()->json(['status' => true, 'data' => 'Student Updated Successfully']);
            }

        }
    }
    public function view(Request $request)
    {
        abort_if(Gate::denies('bus_student_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = DB::table('bus_student')
            ->where('bus_student.id', '=', $request->id)
            ->leftJoin('bus_route', 'bus_route.id', '=', 'bus_student.designation_id')
            ->leftJoin('route_allocation', 'route_allocation.designation_id', '=', 'bus_student.designation_id')
            ->leftJoin('bus', 'route_allocation.bus_id', '=', 'bus.id')
            ->leftJoin('non_teaching_staffs', 'route_allocation.driver_id', '=', 'non_teaching_staffs.user_name_id')
            ->select('bus_student.id', 'bus_student.designation_id', 'bus_route.total_km', 'non_teaching_staffs.name', 'non_teaching_staffs.user_name_id', 'bus.bus_no', 'bus.id as bus_id', 'bus.available_seats', 'bus_student.student_id', 'bus_student.stop_id', 'bus_student.stop_name')
            ->first();

        $stu = json_decode($data->student_id);
        $student = Student::whereIn('user_name_id', $stu)->select('name', 'register_no')->get()->toArray();
        // dd($data);
        return response()->json(['status' => true, 'data' => $data, 'student' => $student]);

    }
    public function edit(Request $request)
    {
        abort_if(Gate::denies('bus_student_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = DB::table('bus_student')
            ->where('bus_student.id', '=', $request->id)
            ->leftJoin('bus_route', 'bus_route.id', '=', 'bus_student.designation_id')
            ->leftJoin('route_allocation', 'route_allocation.designation_id', '=', 'bus_student.designation_id')
            ->leftJoin('bus', 'route_allocation.bus_id', '=', 'bus.id')
            ->leftJoin('non_teaching_staffs', 'route_allocation.driver_id', '=', 'non_teaching_staffs.user_name_id')
            ->select('bus_student.id', 'bus_student.designation_id', 'bus_route.total_km', 'non_teaching_staffs.name', 'non_teaching_staffs.user_name_id', 'bus.bus_no', 'bus.id as bus_id', 'bus.available_seats', 'bus_student.student_id', 'bus_student.stop_id', 'bus_student.stop_name')
            ->first();

        $stu = json_decode($data->student_id);
        $student = Student::whereIn('user_name_id', $stu)->select('name', 'user_name_id')->get()->toArray();
        // dd($data);
        return response()->json(['status' => true, 'data' => $data, 'student' => $student]);
    }
    public function destroy(Request $request)
    {
        abort_if(Gate::denies('bus_student_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (isset($request->id)) {
            $check = BusStudent::where('id', $request->id)->first();
            $previous_student = json_decode($check->student_id);
            $available_seats = DB::table('route_allocation')
                ->where('designation_id', $check->designation_id)
                ->leftJoin('bus', 'route_allocation.bus_id', '=', 'bus.id')
                ->select('bus.available_seats', 'bus.filled_seats', 'bus.total_seats')
                ->first();
            $total_seats_count = $available_seats->total_seats == null ? 0 : $available_seats->total_seats;
            $total_filled_count = $available_seats->filled_seats == null ? 0 : $available_seats->filled_seats;

            $bus1 = DB::table('route_allocation')
                ->where('designation_id', $check->designation_id)
                ->leftJoin('bus', 'route_allocation.bus_id', '=', 'bus.id')
                ->update([
                    'bus.filled_seats' => $total_filled_count - count($previous_student),
                    'bus.available_seats' => $total_seats_count + ($total_filled_count - count($previous_student)),
                ]);

            $delete = BusStudent::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Data Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }
    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('bus_student_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $RouteAllocation = BusStudent::find(request('ids'));

        $get_student = [];
        foreach ($RouteAllocation as $key => $value) {
            // dd($value['student_id']);
            $decode = json_decode($value['student_id']);
            foreach ($decode as $k => $dec) {
                $get_student[] = $dec;
            }
        }
        dd($RouteAllocation[0]['designation_id']);
        $check = BusStudent::where('id', $request->id)->first();
        $previous_student = json_decode($check->student_id);
        $available_seats = DB::table('route_allocation')
            ->where('designation_id', $check->designation_id)
            ->leftJoin('bus', 'route_allocation.bus_id', '=', 'bus.id')
            ->select('bus.available_seats', 'bus.filled_seats', 'bus.total_seats')
            ->first();
        $total_seats_count = $available_seats->total_seats == null ? 0 : $available_seats->total_seats;
        $total_filled_count = $available_seats->filled_seats == null ? 0 : $available_seats->filled_seats;

        $bus1 = DB::table('route_allocation')
            ->where('designation_id', $check->designation_id)
            ->leftJoin('bus', 'route_allocation.bus_id', '=', 'bus.id')
            ->update([
                'bus.filled_seats' => $total_filled_count - count($previous_student),
                'bus.available_seats' => $total_seats_count + ($total_filled_count - count($previous_student)),
            ]);
        dd($get_student);

        foreach ($RouteAllocation as $route) {
            $route->delete();
            return response()->json(['status' => 'success', 'data' => 'Data Deleted Successfully']);
        }
    }

    public function checkDesignation(Request $request)
    {
        // dd($request);
        $data = DB::table('route_allocation')
            ->where('route_allocation.designation_id', '=', $request->id)
            ->leftJoin('bus_route', 'bus_route.id', '=', 'route_allocation.designation_id')
            ->leftJoin('bus', 'route_allocation.bus_id', '=', 'bus.id')
            ->leftJoin('non_teaching_staffs', 'route_allocation.driver_id', '=', 'non_teaching_staffs.user_name_id')
            ->select('bus_route.designation', 'bus_route.total_km', 'bus_route.stops', 'non_teaching_staffs.name', 'non_teaching_staffs.user_name_id', 'bus.bus_no', 'bus.available_seats')
            ->get();

        // dd($data);
        $student = [];

        $bus_student = BusStudent::select('student_id')->get();
        foreach ($bus_student as $row) {
            $d = json_decode($row->student_id);
            foreach ($d as $key => $value) {
                $student[] = $value;
            }
        }

        // dd($student);
        $student = DB::table('course_enroll_masters')
            ->join('students', 'students.enroll_master_id', '=', 'course_enroll_masters.id')
            ->whereNotIn('students.user_name_id', $student)
            ->whereNull('students.deleted_at')
            ->select('students.name', 'students.user_name_id')
            ->get();

        return response()->json(['status' => true, 'data' => $data, 'student' => $student]);
    }

    public function reportIndex(Request $request)
    {
        abort_if(Gate::denies('transport_report_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = DB::table('route_allocation')
            ->where('route_allocation.designation_id', '=', $request->id)
            ->leftJoin('bus_route', 'bus_route.id', '=', 'route_allocation.designation_id')
            ->leftJoin('bus', 'route_allocation.bus_id', '=', 'bus.id')
            ->leftJoin('non_teaching_staffs', 'route_allocation.driver_id', '=', 'non_teaching_staffs.user_name_id')
            ->select('bus_route.designation', 'bus_route.total_km', 'bus_route.stops', 'non_teaching_staffs.name', 'non_teaching_staffs.user_name_id', 'bus.bus_no', 'bus.total_seats')
            ->get();
        $designation = BusRoute::pluck('designation', 'id');

        return view('admin.transport_report.index', compact('designation'));
    }
    public function report(Request $request)
    {
        abort_if(Gate::denies('transport_report_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = DB::table('bus_student')
            ->where('bus_student.designation_id', '=', $request->designation)
            ->leftJoin('route_allocation', 'bus_student.designation_id', '=', 'route_allocation.designation_id')
            ->leftJoin('bus_route', 'bus_route.id', '=', 'route_allocation.designation_id')
            ->leftJoin('bus', 'route_allocation.bus_id', '=', 'bus.id')
            ->leftJoin('non_teaching_staffs', 'route_allocation.driver_id', '=', 'non_teaching_staffs.user_name_id')
            ->select('bus_student.stop_name', 'bus_student.student_id', 'bus.bus_no')
            ->get();

        foreach ($data as $value) {
            $studentId = [];
            $get_id = json_decode($value->student_id);
            $stu = Student::whereIn('user_name_id', $get_id)->select('name', 'enroll_master_id', 'register_no')->get();
            foreach ($stu as $s) {
                $enroll = CourseEnrollMaster::where('id', $s->enroll_master_id)->select('enroll_master_number')->first();
                $studentId[] = [$s->name, $enroll->enroll_master_number, $s->register_no];
                $value->student = $studentId;
            }
        }
        // dd($data);

        return response()->json(['status' => true, 'data' => $data]);
    }

}
