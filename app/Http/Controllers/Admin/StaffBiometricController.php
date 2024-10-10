<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyStaffBiometricRequest;
use App\Http\Requests\StoreStaffBiometricRequest;
use App\Http\Requests\UpdateStaffBiometricRequest;
use App\Models\NonTeachingStaff;
use App\Models\StaffBiometric;
use App\Models\Staffs;
use App\Models\TeachingStaff;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class StaffBiometricController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('staff_biometric_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staff = StaffBiometric::distinct('staff_code')->pluck('employee_name', 'staff_code');
        // dd($staff);
        if (request()->has('staff_code') || request()->has('start_date') || request()->has('end_date')) {

            if (request()->has('staff_code') && $request->staff_code != '') {
                $get_staff = Staffs::where(['employee_id' => request()->input('staff_code')])->first();
                if ($get_staff != '') {
                    $user_name_id = $get_staff->user_name_id;
                }

            } else {
                $user_name_id = null;
            }
            $start_date = request()->input('start_date');
            $end_date = request()->input('end_date');
            // dd($user_name_id,$end_date);
            if ($user_name_id == '' && $start_date == null) {
                // dd('dfd');
                $query = StaffBiometric::get();
            } elseif ($user_name_id != '' && $start_date == null) {
                $query = StaffBiometric::where(['user_name_id' => $user_name_id])->get();
            } elseif ($user_name_id == '' && $start_date != null) {
                // dd('hi');
                $query = StaffBiometric::whereBetween('date', [$start_date, $end_date])->get();
            } elseif ($user_name_id != '' && $start_date != null) {
                $query = StaffBiometric::where(['user_name_id' => $user_name_id])->whereBetween('date', [$start_date, $end_date])->get();
            }
        } else {
            // $current_month = Carbon::now()->format('Y-m');
            // $to_date = $current_month . '-26';
            // $previous_month = Carbon::now()->subMonth()->format('Y-m');
            // $from_date = $previous_month . '-25';

            $from_date = Carbon::now()->startOfMonth()->format('Y-m-d'); // Start of current month
            $to_date = Carbon::now()->endOfMonth()->format('Y-m-d');

            $query = StaffBiometric::whereBetween('date', [$from_date, $to_date])->take(100)->get();

        }

        if ($request->ajax()) {

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'leave_type_show';
                $editGate = 'leave_type_edit';
                $deleteGate = 'leave_type_delete';
                $crudRoutePart = 'staff-biometrics';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $counter = 0;

            $table->editColumn('id', function ($row) use (&$counter) {
                $counter++;
                return $counter;
            });
            $table->editColumn('date', function ($row) {
                if (isset($row->date)) {
                    $date = explode('-', $row->date);
                    return $final_date = $date[2] . '-' . $date[1] . '-' . $date[0];
                }
                return '';
            });
            $table->editColumn('day', function ($row) {
                return $row->day ? $row->day : '';
            });
            $table->editColumn('staff_code', function ($row) {
                return $row->staff_code ? $row->staff_code : '';
            });
            $table->editColumn('employee_name', function ($row) {
                return $row->employee_name ? $row->employee_name : '';
            });
            $table->editColumn('day_punches', function ($row) {
                return $row->day_punches ? $row->day_punches : '';
            });
            $table->editColumn('in_time', function ($row) {
                return $row->in_time ? $row->in_time : '';
            });
            $table->editColumn('out_time', function ($row) {
                return $row->out_time ? $row->out_time : '';
            });
            $table->editColumn('total_hours', function ($row) {
                return $row->total_hours ? $row->total_hours : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? $row->status : '';
            });
            $table->editColumn('permission', function ($row) {
                return $row->permission ? $row->permission : '';
            });
            $table->editColumn('details', function ($row) {
                if ($row->details == 'Casual Leave') {
                    $row->details = 'Casual Leave (NA)';
                } elseif ($row->details == 'After Noon Casual Leave') {
                    $row->details = 'After Noon Casual Leave (NA)';
                } elseif ($row->details == 'Fore Noon Casual Leave') {
                    $row->details = 'Fore Noon Casual Leave (NA)';
                }
                // $late = '';
                if ($row->isLate == 1) {
                    if ($row->details != '') {
                        $row->details .= ', Late';
                    } else {
                        $row->details .= 'Late';
                    }
                }
                if ($row->earlyOut == 1) {
                    if ($row->details != '') {
                        $row->details .= ', EarlyOut';
                    } else {
                        $row->details .= 'EarlyOut';
                    }
                }

                return $row->details;
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.staffBiometrics.index', compact('staff'));
    }

    public function create()
    {
        // abort_if(Gate::denies('leave_type_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.staffBiometrics.create');
    }

    public function store(StoreStaffBiometricRequest $request)
    {

        $year = $request->year;
        $month = $request->month;

        $numDays = Carbon::createFromDate($year, $month, 1)->daysInMonth;

        $check = DB::table('staff_biometrics')->where('date', 'like', $year . '-' . $month . '%')->get();

        if ($check->count() <= 0) {
            $teach_staffs = DB::table('teaching_staffs')->get();

            $count = $numDays;

            foreach ($teach_staffs as $value) {

                for ($i = 01; $i <= $count; $i++) {

                    $get_day = \Carbon\Carbon::parse($year . '-' . $month . '-' . $i);

                    $dayOfWeek = $get_day->format('l');

                    if ($dayOfWeek == 'Sunday') {
                        $details = 'Holiday';
                    } else {
                        $details = null;
                    }

                    DB::table('staff_biometrics')->insert([
                        'date' => $year . '-' . $month . '-' . $i,
                        'day' => $dayOfWeek,
                        'user_name_id' => $value->user_name_id,
                        'employee_name' => $value->name,
                        'employee_code' => $value->BiometricID,
                        'staff_code' => $value->StaffCode,
                        'shift' => 1,
                        'details' => $details,
                    ]);
                }
            }
            $non_teach_staffs = DB::table('non_teaching_staffs')->get();

            foreach ($non_teach_staffs as $value) {

                for ($i = 01; $i <= $count; $i++) {

                    $get_day = \Carbon\Carbon::parse($year . '-' . $month . '-' . $i);

                    $dayOfWeek = $get_day->format('l');

                    if ($dayOfWeek == 'Sunday') {
                        $details = 'Holiday';
                    } else {
                        $details = null;
                    }

                    DB::table('staff_biometrics')->insert([
                        'date' => $year . '-' . $month . '-' . $i,
                        'day' => $dayOfWeek,
                        'user_name_id' => $value->user_name_id,
                        'employee_name' => $value->name,
                        'staff_code' => $value->StaffCode,
                        'shift' => 2,
                        'details' => $details,
                    ]);
                }
            }
        }

        return redirect()->route('admin.staff-biometrics.index');
    }

    public function updater(Request $request, StaffBiometric $staffBiometric)
    {
        // dd($request->input('data'));
        foreach ($request->input('data') as $row) {

            if (isset($row['date']) && isset($row['user_name_id'])) {

                if ($row['date'] != '' && $row['user_name_id'] != '') {

                    if ($row['in_time'] == '') {
                        $row['in_time'] = '00:00:00';
                    }
                    if ($row['out_time'] == '') {
                        $row['out_time'] = '00:00:00';
                    }

                    // tester
                    // $row['in_time'] = '07:52:15';
                    // $row['out_time'] = '15:52:17';

                    if ($row['in_time'] != '00:00:00' && $row['out_time'] != '00:00:00') {
                        $in = strtotime($row['in_time']);
                        $out = strtotime($row['out_time']);

                        $duration_seconds = $out - $in;

                        $total_hours = gmdate('H:i:s', $duration_seconds);
                    } else {
                        $total_hours = null;
                    }

                    if (strtotime($row['in_time']) > strtotime('08:00:00') && strtotime($row['in_time']) < strtotime('08:15:00')) {
                        $details = 'Late';
                    } else if (strtotime($row['in_time']) > strtotime('08:15:00')) {
                        $details = 'Too Late';
                    } else {
                        $details = null;
                    }

                    // dd($total_hours);
                    $query_one = StaffBiometric::where(['user_name_id' => $row['user_name_id'], 'date' => $row['date']])->get();
                    $query_two = StaffBiometric::where(['user_name_id' => $row['user_name_id']])->first();
                    // dd($query_two->user_name_id);
                    if ($query_one->count() <= 0) {
                        $insert = new StaffBiometric;

                        $insert->date = $row['date'];
                        $insert->user_name_id = $row['user_name_id'];
                        $insert->in_time = $row['in_time'];
                        $insert->out_time = $row['out_time'];
                        $insert->total_hours = $total_hours;
                        // $insert->shift = $row['shift'];
                        $insert->status = $row['status'];
                        // $insert->permission = null;
                        $insert->updated_by = $row['up_status'];
                        $insert->employee_code = $query_two->employee_code;
                        $insert->staff_code = $query_two->staff_code;
                        $insert->employee_name = $query_two->employee_name;
                        $insert->save();
                    } else {

                        $biometric = $staffBiometric->where(['user_name_id' => $row['user_name_id'], 'date' => $row['date']])->update([
                            'in_time' => $row['in_time'],
                            'out_time' => $row['out_time'],
                            'total_hours' => $total_hours,
                            'status' => $row['status'],
                            'updated_by' => $row['up_status'],
                        ]);
                    }

                    // dd($biometric);

                }
            }
        }
        return response()->json(['status' => 'true']);
    }
    public function edit(StaffBiometric $staffBiometric)
    {
        // abort_if(Gate::denies('leave_type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.staffBiometrics.edit', compact('staffBiometric'));
    }

    public function update(UpdateStaffBiometricRequest $request, StaffBiometric $staffBiometric)
    {
        if ($request->in_time != '' && $request->out_time != '') {
            $in = strtotime($request->in_time);
            $out = strtotime($request->out_time);

            $duration_seconds = $out - $in;

            $total_hours = gmdate('H:i:s', $duration_seconds);
        } else {
            $total_hours = null;
        }

        if (strtotime($request->in_time) > strtotime('08:00:00') && strtotime($request->in_time) < strtotime('08:15:00')) {
            $details = 'Late';
        } else if (strtotime($request->in_time) > strtotime('08:15:00')) {
            $details = 'Too Late';
        } else {
            $details = null;
        }

        if ($request->in_time != '' && $request->out_time != '') {
            $status = 'Present';
        } else {
            $status = 'Absent';
        }
        if ($details != null) {
            $staffBiometric->update([
                'in_time' => $request->in_time,
                'out_time' => $request->out_time,
                'total_hours' => $total_hours,
                'details' => $details,
                'status' => $status,
            ]);
        } else {
            $staffBiometric->update([
                'in_time' => $request->in_time,
                'out_time' => $request->out_time,
                'total_hours' => $total_hours,
                'status' => $status,
            ]);
        }

        return redirect()->route('admin.staff-biometrics.index');
    }

    public function show(StaffBiometric $staffBiometric)
    {
        // abort_if(Gate::denies('leave_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.staffBiometrics.show', compact('staffBiometric'));
    }

    public function destroy(StaffBiometric $staffBiometric)
    {
        // abort_if(Gate::denies('leave_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staffBiometric->delete();

        return back();
    }

    public function massDestroy(MassDestroyStaffBiometricRequest $request)
    {
        $staffBiometric = StaffBiometric::find(request('ids'));

        foreach ($staffBiometric as $leaveType) {
            $leaveType->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function modificationRun(Request $request)
    {
        $get_biometric = StaffBiometric::where(['update_status' => null, 'status' => 'Present'])->where('in_time', '!=', null)->get();
        // dd($get_biometric);
        foreach ($get_biometric as $biometric) {
            if (strtotime($biometric->in_time) > strtotime('08:00:00') && strtotime($biometric->in_time) < strtotime('08:15:00')) {
                $details = 'Late';
            } else if (strtotime($biometric->in_time) > strtotime('08:15:00')) {
                $details = 'Too Late';
            } else {
                $details = $biometric->details;
            }

            $update = StaffBiometric::where(['id' => $biometric->id])->update([
                'details' => $details,
            ]);
        }
        return true;
    }

    public function balanceCl(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('users')
                ->whereNotNull('users.employee_id')
                ->whereNull('users.deleted_at')
                ->leftJoin('staffs', 'staffs.user_name_id', '=', 'users.id')
                ->where('staffs.role_id', '=', 2)
                ->whereNull('staffs.deleted_at')
                ->select('users.id', 'users.name', 'staffs.employee_id', 'staffs.casual_leave', 'staffs.personal_permission', 'staffs.sick_leave')
                ->get();
            // dd($query[0]);
            $table = DataTables::of($query);

            $table->editColumn('sno', function ($row) {
                return $row->id ? $row->id : '';
            });

            $table->editColumn('staff_name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->editColumn('staff_code', function ($row) {
                return $row->employee_id ? $row->employee_id : '';
            });
            $table->editColumn('casual_leave', function ($row) {
                return $row->casual_leave ? $row->casual_leave : 0;
            });
            $table->editColumn('permission', function ($row) {
                return $row->personal_permission ? $row->personal_permission : 0;
            });
            $table->editColumn('sick', function ($row) {
                return $row->sick_leave ? $row->sick_leave : 0;
            });

            return $table->make(true);
        }
        $status = 'Balance';

        return view('admin.staffBalance.index', compact('status'));
    }
}
