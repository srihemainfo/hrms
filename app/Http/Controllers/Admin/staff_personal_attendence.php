<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffBiometric;
use App\Models\Staffs;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class staff_personal_attendence extends Controller
{
    public function index(Request $request)
    {

        if ($request) {
            // $years = Year::pluck('year', 'id');
            $staff = StaffBiometric::distinct('employee_code')->pluck('employee_name', 'staff_code');

        } else {
            return back();
        }
        return view('admin.staff_personal_attendence.index ', compact('staff'));
    }

    public function search(Request $request)
    {
        // abort_if(Gate::denies('employee_salary_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request);
        if ($request) {

            $month = $request->month;

            $fromyear = $request->year;
            $toyear = $request->year;

            $staff_code = $request->staff_code;

            $staff = Staffs::where('user_name_id', $request->staff_code)->first();
            // if($staff == ''){
            //     $staff = NonTeachingStaff::where('user_name_id', $request->staff_code)->first();
            // }
            // dd($staff);
            $day_array = [];

            $matching = [];

            $givenMonth = Carbon::createFromDate($fromyear, $month, 1);
            $startOfMonth = $givenMonth->startOfMonth()->format('Y-m-d');
            $endOfMonth = $givenMonth->endOfMonth()->format('Y-m-d');

            $query = StaffBiometric::where('user_name_id', $staff_code)

                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->get();
            // dd($query);
            if (!$query->count() <= 0) {

                $attend_rep = $query;

                for ($a = 0; $a < count($attend_rep); $a++) {
                    if ($attend_rep[$a]->shift != '' || $attend_rep[$a]->shift != null) {
                        $shift = $attend_rep[$a]->shift;
                        break;
                    } else {
                        $shift = '';
                    }
                }
            } else {
                $attend_rep = '';
                $shift = '';
            }

        }
        $final_array = [];
        if ($attend_rep != '') {
            for ($i = 0; $i < count($attend_rep); $i++) {

                if ($attend_rep[$i]['in_time'] == null) {
                    $attend_rep[$i]['in_time'] = '-';
                }
                if ($attend_rep[$i]['out_time'] == null) {
                    $attend_rep[$i]['out_time'] = '-';
                }
                if ($attend_rep[$i]['status'] == null) {
                    $attend_rep[$i]['status'] = '-';
                }
                if ($attend_rep[$i]['total_hours'] == null || $attend_rep[$i]['total_hours'] == '00:00:00') {
                    $attend_rep[$i]['total_hours'] = '-';
                }

                $one = new StaffBiometric;
                $one->SNo = $i + 1;
                $one->date = $attend_rep[$i]['date'];
                if ($attend_rep[$i]['day'] == 'Sunday') {
                    $one->day = "<span style=\"color:red;\"> Sunday </span>";
                } else {
                    $one->day = $attend_rep[$i]['day'];
                }
                $one->permission = $attend_rep[$i]['permission'];
                $one->day_punches = $attend_rep[$i]['day_punches'];
                $one->in_time = $attend_rep[$i]['in_time'];
                $one->out_time = $attend_rep[$i]['out_time'];
                $one->total_hours = $attend_rep[$i]['total_hours'];
                $one->status = $attend_rep[$i]['status'];
                if ($attend_rep[$i]['isLate'] == 1) {
                    if ($one->details != '') {
                        $one->details .= ', <span style="color:red;"> Late </span>';
                    } else {
                        $one->details .= '<span style="color:red;"> Late </span>';
                    }
                    // dd($one->details);
                }

                if ($attend_rep[$i]['earlyOut'] == 1) {
                    if ($one->details != '') {
                        $one->details .= ', <span style="color:red;"> EarlyOut </span>';
                    } else {
                        $one->details .= '<span style="color:red;"> EarlyOut </span>';
                    }
                }
                if ($attend_rep[$i]['details'] == 'Sunday' || $attend_rep[$i]['details'] == 'Holiday') {
                    $one->details .= '<span style="color:green;">' . $attend_rep[$i]['details'] . '</span>';
                } else {
                    $one->details .= $attend_rep[$i]['details'];
                }
                array_push($final_array, $one);
            }

        }

        // dd($final_array);
        return response()->json(['data' => $final_array, 'shift' => $shift, 'staff' => $staff]);

        // return response()->json(['attend_rep' => $attend_rep, 'day_array' => $day_array, 'shift' => $shift, 'staff' => $staff]);
    }
}
