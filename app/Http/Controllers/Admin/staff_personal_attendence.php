<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Carbon\Carbon;
use App\Models\Year;
use Illuminate\Http\Request;
use App\Models\TeachingStaff;
use App\Models\StaffBiometric;
use App\Models\NonTeachingStaff;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class staff_personal_attendence extends Controller
{
    public function index(Request $request){

        if ($request) {
            $years = Year::pluck('year', 'id');
            $staff = StaffBiometric::distinct('employee_code')->pluck('employee_name', 'staff_code');

        } else {
            return back();
        }
        return view('admin.staff_personal_attendence.index ', compact('staff','years'));
    }

    public function search(Request $request)
    {
        // abort_if(Gate::denies('employee_salary_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request) {

            $month = $request->month;

            $fromyear = $request->year;
            $toyear = $request->year;

            $staff_code = $request->staff_code;

            $staff = TeachingStaff::where('user_name_id', $request->staff_code)->first();
            if($staff == ''){
                $staff = NonTeachingStaff::where('user_name_id', $request->staff_code)->first();
            }
            // dd($staff);
            $day_array = [];

            $matching = [];

            $previousMonth = Carbon::createFromDate($toyear, $month, 26)->subMonth();

            if ($previousMonth->month < 10) {
                $previousmonth = '0' . $previousMonth->month;
            } else {
                $previousmonth = $previousMonth->month;
            }
            // dd((int)$previousmonth);

            // $previousMonthEnd = Carbon::createFromDate($year, $month, 1)->subMonth()->endOfMonth();
            // dd($previousMonthEnd);
            // $startDate = Carbon::createFromDate($year, $month, 1);
            // dd($startDate);
            // $endDate = Carbon::createFromDate($year, $month, 25);
            // dd($endDate);
            // for ($date = $startDate; $date->lte($endDate); $date->addDay()) {

            //     $dayOfWeek = $date->format('l');

            //     array_push($day_array, [$date->toDateString(), $dayOfWeek]);

            // }

            if($month == 1){
                $fromyear-=1;
            }

            $tempFromDate = $fromyear . '-' . $previousmonth . '-26';
            $tempToDate = $toyear . '-' . $month . '-25';
            // dd($tempFromDate,$tempToDate);

            $query = StaffBiometric::where('user_name_id', $staff_code)
                // ->where('updated_at', '!=', null)
                ->whereBetween('date', [$tempFromDate, $tempToDate])
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

                // for ($s = 0; $s < count($day_array); $s++) {

                //     for ($j = 0; $j < count($attend_rep); $j++) {

                //         if ($day_array[$s][0] == $attend_rep[$j]->date) {
                //             array_push($matching, $day_array[$s][0]);
                //         }
                //     }

                // }

            } else {
                $attend_rep = '';
                $shift = '';
                // dd('one');
            }

        }
        $final_array = [];
        // if (count($day_array) > 0) {
            if ($attend_rep != '') {
                for ($i = 0; $i < count($attend_rep); $i++) {
                    // dd($item[0]);
                    // foreach ($attend_rep as $attend_rep[$i]) {
                        // dd($attend_rep[$i]);
                        // if ($day_array[$i][0] == $attend_rep[$i]['date']) {

                            if ($attend_rep[$i]['in_time'] == null) {
                                $attend_rep[$i]['in_time'] = '-';
                            }
                            if ($attend_rep[$i]['out_time'] == null) {
                                $attend_rep[$i]['out_time'] = '-';
                            }
                            if ($attend_rep[$i]['total_hours'] == null || $attend_rep[$i]['total_hours'] == '00:00:00') {
                                $attend_rep[$i]['total_hours'] = '-';
                            }

                            $one = new StaffBiometric;
                            // $one->empty = '';
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
                            if ($attend_rep[$i]['details'] == 'Late') {
                                $one->details = "<span style=\"color:red;\"> Late </span>";
                            } else {
                                $one->details = $attend_rep[$i]['details'];
                            }
                            array_push($final_array, $one);
                            // }

                    // }
                }
            }

        // dd($final_array);
        return response()->json(['data' => $final_array, 'shift' => $shift, 'staff' => $staff]);

        // return response()->json(['attend_rep' => $attend_rep, 'day_array' => $day_array, 'shift' => $shift, 'staff' => $staff]);
    }
}
