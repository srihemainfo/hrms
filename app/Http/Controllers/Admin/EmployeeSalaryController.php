<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccountDetail;
use App\Models\NonTeachingStaff;
use App\Models\PersonalDetail;
use App\Models\salarystatement;
use App\Models\StaffBiometric;
use App\Models\Staffs;
use App\Models\ToolsDepartment;
use App\Models\Year;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeSalaryController extends Controller
{

    public function index(Request $request)
    {
        abort_if(Gate::denies('employee_salary_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request) {

            $staff = StaffBiometric::distinct('staff_code')->pluck('employee_name', 'staff_code');
            // $year = Year::select('id', 'year')->get();

        } else {
            return back();
        }
        $attend_rep = '';

        return view('admin.employeeSalary.index', compact('staff', 'attend_rep'));
    }

    public function search(Request $request)
    {
        // abort_if(Gate::denies('employee_salary_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request) {

            $month = $request->month;

            $year = $request->year;

            $staff_code = $request->staff_code;

            $day_array = [];

            $previousMonth = Carbon::createFromDate($year, $month, 26)->subMonth();

            if ($previousMonth->month < 10) {
                $previousmonth = '0' . $previousMonth->month;
            } else {
                $previousmonth = $previousMonth->month;
            }
            if ($month == 01) {
                $previousYear = (int) $year - 1;
            } else {
                $previousYear = $year;
            }

            $previousMonthEnd = Carbon::createFromDate($year, $month, 1)->subMonth()->endOfMonth();
            for ($date = $previousMonth; $date->lte($previousMonthEnd); $date->addDay()) {

                $dayOfWeek = $date->format('l');

                array_push($day_array, [$date->toDateString(), $dayOfWeek]);

            }

            $startDate = Carbon::createFromDate($year, $month, 1);
            $endDate = Carbon::createFromDate($year, $month, 25);

            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {

                $dayOfWeek = $date->format('l');

                array_push($day_array, [$date->toDateString(), $dayOfWeek]);

            }

            $query = StaffBiometric::where('staff_code', $staff_code)
                ->whereBetween('date', [$previousYear . '-' . $previousmonth . '-26', $year . '-' . $month . '-25'])
                ->get();

            if (!$query->count() <= 0) {

                $attend_rep = $query;

                $salary_query = Staffs::where('user_name_id', $query[0]->user_name_id)->first();

                if ($salary_query != '') {

                    $salary = $salary_query;

                }
                //  else {

                //     $salary_query = NonTeachingStaff::where('user_name_id', $query[0]->user_name_id)->first();
                //     if ($salary_query != '') {

                //         $salary = $salary_query;

                //     } else {
                //         $salary = '';
                //     }

                // }

                $doj_query = PersonalDetail::where('user_name_id', $query[0]->user_name_id)->get();
                if ($doj_query) {
                    if (!$doj_query->count() <= 0) {

                        $doj = $doj_query[0];

                    } else {

                        $doj = $doj_query;

                    }
                } else {
                    $doj = $doj_query;
                }

                $bank_details = BankAccountDetail::where(['user_name_id' => $query[0]->user_name_id, 'account_type' => 'Salary Account'])->get();

                if ($bank_details) {
                    if (!$bank_details->count() <= 0) {

                        $bank = $bank_details[0];

                    } else {

                        $bank_details_1 = BankAccountDetail::where('user_name_id', $query[0]->user_name_id)->first();
                        if ($bank_details_1) {

                            if (!$bank_details_1->count() <= 0) {
                                $bank = $bank_details_1[0];

                            } else {

                                $bank = '';
                            }
                        } else {
                            $bank = '';
                        }

                    }
                } else {

                    $bank = '';
                }

            } else {
                $attend_rep = '';
                $salary = '';
                $bank = '';
                $doj = '';
            }

            $staff = StaffBiometric::distinct('staff_code')->pluck('employee_name', 'staff_code');

            $leave = 0;
            $half_day_leave = 0;
            $too_late = 0;
            if ($attend_rep != '') {
                $len = count($attend_rep);
                if ($len > 0) {
                    for ($i = 0; $i < $len; $i++) {
                        if (strpos($attend_rep[$i]->details, '(CL Provided)') === false && (strpos($attend_rep[$i]->details, 'Fore Noon Casual Leave') !== false || strpos($attend_rep[$i]->details, 'After Noon Casual Leave') !== false)) {
                            $half_day_leave += 0.5;
                        }
                        if (strpos($attend_rep[$i]->details, 'Early Out') !== false) {
                            if ($attend_rep[$i]->shift == 1 && strtotime($attend_rep[$i]->out_time) < strtotime('11:00:00')) {
                                $leave += 1;
                            } else if ($attend_rep[$i]->shift == 1 && strtotime($attend_rep[$i]->out_time) < strtotime('16:00:00')) {
                                $half_day_leave += 0.5;
                            }
                            if ($attend_rep[$i]->shift == 2 && strtotime($attend_rep[$i]->out_time) < strtotime('11:00:00')) {
                                $leave += 1;
                            } else if ($attend_rep[$i]->shift == 2 && strtotime($attend_rep[$i]->out_time) < strtotime('17:00:00')) {
                                $half_day_leave += 0.5;
                            }
                        }
                    }

                    for ($j = 0; $j < $len; $j++) {

                        //Casual Leave
                        if ($attend_rep[$j]->day != 'Sunday' && ($attend_rep[$j]->status == 'Absent' || $attend_rep[$j]->status == null) && (strpos($attend_rep[$j]->details, 'Holiday') === false && strpos($attend_rep[$j]->details, '(CL Provided)') === false && strpos($attend_rep[$j]->details, 'Admin OD') === false && strpos($attend_rep[$j]->details, 'Exam OD') === false && strpos($attend_rep[$j]->details, 'Training OD') === false && strpos($attend_rep[$j]->details, 'Compensation Leave') === false && strpos($attend_rep[$j]->details, 'Winter Vacation') === false && strpos($attend_rep[$j]->details, 'Summer Vacation') === false) && (strpos($attend_rep[$j]->details, 'Casual Leave') !== false || $attend_rep[$j]->details == null)) {
                            $leave++;
                        }

                        if ($attend_rep[$j]->day != 'Sunday' && ($attend_rep[$j]->status == 'Absent' || $attend_rep[$j]->status == 'Present') && strpos($attend_rep[$j]->details, 'Too Late') !== false) {
                            $too_late += 0.5;
                        }
                        //Sunday
                        $temStatus = false;

                        if ($attend_rep[$j]->day == 'Sunday' && ($attend_rep[$j]->status == 'Absent' || $attend_rep[$j]->status == null) && strpos($attend_rep[$j]->details, 'Holiday') === false && strpos($attend_rep[$j]->details, '(CL Provided)') === false && strpos($attend_rep[$j]->details, 'Admin OD') === false && strpos($attend_rep[$j]->details, 'Exam OD') === false && strpos($attend_rep[$j]->details, 'Training OD') === false && strpos($attend_rep[$j]->details, 'Compensation Leave') === false && strpos($attend_rep[$j]->details, 'Winter Vacation') === false && strpos($attend_rep[$j]->details, 'Summer Vacation') === false && strpos($attend_rep[$j]->details, 'Too Late') === false && strpos($attend_rep[$j]->details, 'Casual Leave') === false) {

                            if ($j > 0 && $j < $len) {

                                if ($attend_rep[$j - 1]->day != 'Sunday' && ($attend_rep[$j - 1]->status == 'Absent' || $attend_rep[$j - 1]->status == null) && strpos($attend_rep[$j - 1]->details, 'Holiday') === false && strpos($attend_rep[$j - 1]->details, '(CL Provided)') === false && strpos($attend_rep[$j - 1]->details, 'Admin OD') === false && strpos($attend_rep[$j - 1]->details, 'Exam OD') === false && strpos($attend_rep[$j - 1]->details, 'Training OD') === false && strpos($attend_rep[$j - 1]->details, 'Compensation Leave') === false && strpos($attend_rep[$j - 1]->details, 'Winter Vacation') === false && strpos($attend_rep[$j - 1]->details, 'Summer Vacation') === false) {
                                    for ($m = ($j + 1); $m < $len; $m++) {
                                        if ($attend_rep[$m]->status == 'Present') {
                                            break;
                                        } elseif (($attend_rep[$m]->status == 'Absent' || $attend_rep[$m]->status == null) && strpos($attend_rep[$m]->details, 'Holiday') === false && strpos($attend_rep[$m]->details, '(CL Provided)') === false && strpos($attend_rep[$m]->details, 'Admin OD') === false && strpos($attend_rep[$m]->details, 'Exam OD') === false && strpos($attend_rep[$m]->details, 'Training OD') === false && strpos($attend_rep[$m]->details, 'Compensation Leave') === false && strpos($attend_rep[$m]->details, 'Winter Vacation') === false && strpos($attend_rep[$m]->details, 'Summer Vacation') === false) {
                                            $leave++;
                                            break;
                                        }
                                    }
                                } else if (strpos($attend_rep[$j - 1]->details, 'Holiday') !== false) {
                                    for ($k = ($j - 2); $k > 0; $k--) {
                                        if ($attend_rep[$k]->status == 'Present') {
                                            break;
                                        } elseif (($attend_rep[$k]->status == 'Absent' || $attend_rep[$k]->status == null) && strpos($attend_rep[$k]->details, 'Holiday') === false && strpos($attend_rep[$k]->details, '(CL Provided)') === false && strpos($attend_rep[$k]->details, 'Admin OD') === false && strpos($attend_rep[$k]->details, 'Exam OD') === false && strpos($attend_rep[$k]->details, 'Training OD') === false && strpos($attend_rep[$k]->details, 'Compensation Leave') === false && strpos($attend_rep[$k]->details, 'Winter Vacation') === false && strpos($attend_rep[$k]->details, 'Summer Vacation') === false) {
                                            for ($m = ($j + 1); $m < $len; $m++) {
                                                if ($attend_rep[$m]->status == 'Present') {
                                                    break;
                                                } elseif (($attend_rep[$m]->status == 'Absent' || $attend_rep[$m]->status == null) && strpos($attend_rep[$m]->details, 'Holiday') === false && strpos($attend_rep[$m]->details, '(CL Provided)') === false && strpos($attend_rep[$m]->details, 'Admin OD') === false && strpos($attend_rep[$m]->details, 'Exam OD') === false && strpos($attend_rep[$m]->details, 'Training OD') === false && strpos($attend_rep[$m]->details, 'Compensation Leave') === false && strpos($attend_rep[$m]->details, 'Winter Vacation') === false && strpos($attend_rep[$m]->details, 'Summer Vacation') === false) {
                                                    $leave++;
                                                    $temStatus = true;
                                                    break;
                                                }
                                            }
                                        }
                                        if ($temStatus == true) {
                                            break;
                                        }
                                    }
                                    // if ($temStatus == true) {
                                    //     break;
                                    // }
                                }
                            }
                        }

                        // Holiday
                        $temStatus = false;
                        if ($attend_rep[$j]->day != 'Sunday' && ($attend_rep[$j]->status == 'Absent' || $attend_rep[$j]->status == null) && strpos($attend_rep[$j]->details, 'Holiday') !== false) {
                            if ($j > 0 && $j < $len) {

                                if ($attend_rep[$j - 1]->day != 'Sunday' && ($attend_rep[$j - 1]->status == 'Absent' || $attend_rep[$j - 1]->status == null) && strpos($attend_rep[$j - 1]->details, 'Holiday') === false && strpos($attend_rep[$j - 1]->details, '(CL Provided)') === false && strpos($attend_rep[$j - 1]->details, 'Admin OD') === false && strpos($attend_rep[$j - 1]->details, 'Exam OD') === false && strpos($attend_rep[$j - 1]->details, 'Training OD') === false && strpos($attend_rep[$j - 1]->details, 'Compensation Leave') === false && strpos($attend_rep[$j - 1]->details, 'Winter Vacation') === false && strpos($attend_rep[$j - 1]->details, 'Summer Vacation') === false) {
                                    for ($m = ($j + 1); $m < $len; $m++) {
                                        if ($attend_rep[$m]->status == 'Present') {
                                            break;
                                        } elseif (($attend_rep[$m]->status == 'Absent' || $attend_rep[$m]->status == null) && strpos($attend_rep[$m]->details, 'Holiday') === false && strpos($attend_rep[$m]->details, '(CL Provided)') === false && strpos($attend_rep[$m]->details, 'Admin OD') === false && strpos($attend_rep[$m]->details, 'Exam OD') === false && strpos($attend_rep[$m]->details, 'Training OD') === false && strpos($attend_rep[$m]->details, 'Compensation Leave') === false && strpos($attend_rep[$m]->details, 'Winter Vacation') === false && strpos($attend_rep[$m]->details, 'Summer Vacation') === false) {
                                            $leave++;
                                            break;
                                        }
                                    }
                                    if ($j == ($len - 1)) {
                                        $takeDate = Carbon::parse($attend_rep[$j]->date);
                                        $nextDate = $takeDate->addDay();

                                        $getNextDay = StaffBiometric::where(['user_name_id' => $attend_rep[$j]->user_name_id, 'date' => $nextDate->toDateString()])->select('day', 'details', 'status')->first();
                                        if (($getNextDay->day != 'Sunday' || strpos($getNextDay->details, 'Holiday') === false)) {
                                            if (($getNextDay->status == 'Absent' || $getNextDay->status == null) && strpos($getNextDay->details, 'Holiday') === false && strpos($getNextDay->details, '(CL Provided)') === false && strpos($getNextDay->details, 'Admin OD') === false && strpos($getNextDay->details, 'Exam OD') === false && strpos($getNextDay->details, 'Training OD') === false && strpos($getNextDay->details, 'Compensation Leave') === false && strpos($getNextDay->details, 'Winter Vacation') === false && strpos($getNextDay->details, 'Summer Vacation') === false) {
                                                $leave++;
                                            }
                                        } else {
                                            $leave++;
                                        }
                                    }
                                } else if (strpos($attend_rep[$j - 1]->details, 'Holiday') !== false || $attend_rep[$j - 1]->day == 'Sunday') {

                                    for ($k = ($j - 2); $k > 0; $k--) {
                                        if ($attend_rep[$k]->status == 'Present') {
                                            break;
                                        } elseif (($attend_rep[$k]->status == 'Absent' || $attend_rep[$k]->status == null) && $attend_rep[$k]->day != 'Sunday' && strpos($attend_rep[$k]->details, 'Holiday') === false && strpos($attend_rep[$k]->details, '(CL Provided)') === false && strpos($attend_rep[$k]->details, 'Admin OD') === false && strpos($attend_rep[$k]->details, 'Exam OD') === false && strpos($attend_rep[$k]->details, 'Training OD') === false && strpos($attend_rep[$k]->details, 'Compensation Leave') === false && strpos($attend_rep[$k]->details, 'Winter Vacation') === false && strpos($attend_rep[$k]->details, 'Summer Vacation') === false) {
                                            for ($m = ($j + 1); $m < $len; $m++) {
                                                if ($attend_rep[$m]->status == 'Present') {
                                                    break;
                                                } elseif (($attend_rep[$m]->status == 'Absent' || $attend_rep[$m]->status == null) && strpos($attend_rep[$m]->details, 'Holiday') === false && strpos($attend_rep[$m]->details, '(CL Provided)') === false && strpos($attend_rep[$m]->details, 'Admin OD') === false && strpos($attend_rep[$m]->details, 'Exam OD') === false && strpos($attend_rep[$m]->details, 'Training OD') === false && strpos($attend_rep[$m]->details, 'Compensation Leave') === false && strpos($attend_rep[$m]->details, 'Winter Vacation') === false && strpos($attend_rep[$m]->details, 'Summer Vacation') === false) {
                                                    $leave++;
                                                    $temStatus = true;
                                                    break;
                                                }
                                            }
                                            if ($temStatus == true) {
                                                break;
                                            }
                                        }
                                        if ($temStatus == true) {
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        // $year = Year::select('id', 'year')->get();
        return view('admin.employeeSalary.index', compact('attend_rep', 'staff', 'day_array', 'salary', 'bank', 'doj', 'half_day_leave', 'leave', 'too_late'));
    }

    public function salary_stmt_gen(Request $request)
    {

        $month = $request->month;
        $monthName = date('F', strtotime("2000-$month-01"));

        $year = $request->year;

        $day_array = [];

        $previousMonth = Carbon::createFromDate($year, $month, 26)->subMonth();

        if ($previousMonth->month < 10) {
            $previousmonth = '0' . $previousMonth->month;
        } else {
            $previousmonth = $previousMonth->month;
        }
        if ($month == 01) {
            $previousYear = (int) $year - 1;
        } else {
            $previousYear = $year;
        }
        $previousMonthEnd = Carbon::createFromDate($year, $month, 1)->subMonth()->endOfMonth();

        for ($date = $previousMonth; $date->lte($previousMonthEnd); $date->addDay()) {

            $dayOfWeek = $date->format('l');

            array_push($day_array, [$date->toDateString(), $dayOfWeek]);

        }

        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate = Carbon::createFromDate($year, $month, 25);

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {

            $dayOfWeek = $date->format('l');

            array_push($day_array, [$date->toDateString(), $dayOfWeek]);

        }

        $teaching_staff = TeachingStaff::get();
        $non_teaching_staff = NonTeachingStaff::get();

        foreach ($teaching_staff as $staff) {

            $query = StaffBiometric::where('staff_code', $staff->StaffCode)
                ->whereBetween('date', [$previousYear . '-' . $previousmonth . '-26', $year . '-' . $month . '-25'])
                ->get();

            if (!$query->count() <= 0) {

                $attend_rep = $query;

                $leave = 0;
                $half_day_leave = 0;
                $too_late = 0;
                if ($attend_rep != '') {
                    $len = count($attend_rep);
                    if ($len > 0) {
                        for ($i = 0; $i < $len; $i++) {
                            if (strpos($attend_rep[$i]->details, '(CL Provided)') === false && (strpos($attend_rep[$i]->details, 'Fore Noon Casual Leave') !== false || strpos($attend_rep[$i]->details, 'After Noon Casual Leave') !== false)) {
                                $half_day_leave += 0.5;
                            }
                            if (strpos($attend_rep[$i]->details, 'Early Out') !== false) {
                                if ($attend_rep[$i]->shift == 1 && strtotime($attend_rep[$i]->out_time) < strtotime('11:00:00')) {
                                    $leave += 1;
                                } else if ($attend_rep[$i]->shift == 1 && strtotime($attend_rep[$i]->out_time) < strtotime('16:00:00')) {
                                    $half_day_leave += 0.5;
                                }
                                if ($attend_rep[$i]->shift == 2 && strtotime($attend_rep[$i]->out_time) < strtotime('11:00:00')) {
                                    $leave += 1;
                                } else if ($attend_rep[$i]->shift == 2 && strtotime($attend_rep[$i]->out_time) < strtotime('17:00:00')) {
                                    $half_day_leave += 0.5;
                                }
                            }
                        }

                        for ($j = 0; $j < $len; $j++) {

                            //Casual Leave
                            if ($attend_rep[$j]->day != 'Sunday' && ($attend_rep[$j]->status == 'Absent' || $attend_rep[$j]->status == null) && (strpos($attend_rep[$j]->details, 'Holiday') === false && strpos($attend_rep[$j]->details, '(CL Provided)') === false && strpos($attend_rep[$j]->details, 'Admin OD') === false && strpos($attend_rep[$j]->details, 'Exam OD') === false && strpos($attend_rep[$j]->details, 'Training OD') === false && strpos($attend_rep[$j]->details, 'Compensation Leave') === false && strpos($attend_rep[$j]->details, 'Winter Vacation') === false && strpos($attend_rep[$j]->details, 'Summer Vacation') === false) && (strpos($attend_rep[$j]->details, 'Casual Leave') !== false || $attend_rep[$j]->details == null)) {
                                $leave++;
                            }

                            if ($attend_rep[$j]->day != 'Sunday' && ($attend_rep[$j]->status == 'Absent' || $attend_rep[$j]->status == 'Present') && strpos($attend_rep[$j]->details, 'Too Late') !== false) {
                                $too_late += 0.5;
                            }
                            //Sunday
                            $temStatus = false;

                            if ($attend_rep[$j]->day == 'Sunday' && ($attend_rep[$j]->status == 'Absent' || $attend_rep[$j]->status == null) && strpos($attend_rep[$j]->details, 'Holiday') === false && strpos($attend_rep[$j]->details, '(CL Provided)') === false && strpos($attend_rep[$j]->details, 'Admin OD') === false && strpos($attend_rep[$j]->details, 'Exam OD') === false && strpos($attend_rep[$j]->details, 'Training OD') === false && strpos($attend_rep[$j]->details, 'Compensation Leave') === false && strpos($attend_rep[$j]->details, 'Winter Vacation') === false && strpos($attend_rep[$j]->details, 'Summer Vacation') === false && strpos($attend_rep[$j]->details, 'Too Late') === false && strpos($attend_rep[$j]->details, 'Casual Leave') === false) {

                                if ($j > 0 && $j < $len) {

                                    if ($attend_rep[$j - 1]->day != 'Sunday' && ($attend_rep[$j - 1]->status == 'Absent' || $attend_rep[$j - 1]->status == null) && strpos($attend_rep[$j - 1]->details, 'Holiday') === false && strpos($attend_rep[$j - 1]->details, '(CL Provided)') === false && strpos($attend_rep[$j - 1]->details, 'Admin OD') === false && strpos($attend_rep[$j - 1]->details, 'Exam OD') === false && strpos($attend_rep[$j - 1]->details, 'Training OD') === false && strpos($attend_rep[$j - 1]->details, 'Compensation Leave') === false && strpos($attend_rep[$j - 1]->details, 'Winter Vacation') === false && strpos($attend_rep[$j - 1]->details, 'Summer Vacation') === false) {
                                        for ($m = ($j + 1); $m < $len; $m++) {
                                            if ($attend_rep[$m]->status == 'Present') {
                                                break;
                                            } elseif (($attend_rep[$m]->status == 'Absent' || $attend_rep[$m]->status == null) && strpos($attend_rep[$m]->details, 'Holiday') === false && strpos($attend_rep[$m]->details, '(CL Provided)') === false && strpos($attend_rep[$m]->details, 'Admin OD') === false && strpos($attend_rep[$m]->details, 'Exam OD') === false && strpos($attend_rep[$m]->details, 'Training OD') === false && strpos($attend_rep[$m]->details, 'Compensation Leave') === false && strpos($attend_rep[$m]->details, 'Winter Vacation') === false && strpos($attend_rep[$m]->details, 'Summer Vacation') === false) {
                                                $leave++;
                                                break;
                                            }
                                        }
                                    } else if (strpos($attend_rep[$j - 1]->details, 'Holiday') !== false) {
                                        for ($k = ($j - 2); $k > 0; $k--) {
                                            if ($attend_rep[$k]->status == 'Present') {
                                                break;
                                            } elseif (($attend_rep[$k]->status == 'Absent' || $attend_rep[$k]->status == null) && strpos($attend_rep[$k]->details, 'Holiday') === false && strpos($attend_rep[$k]->details, '(CL Provided)') === false && strpos($attend_rep[$k]->details, 'Admin OD') === false && strpos($attend_rep[$k]->details, 'Exam OD') === false && strpos($attend_rep[$k]->details, 'Training OD') === false && strpos($attend_rep[$k]->details, 'Compensation Leave') === false && strpos($attend_rep[$k]->details, 'Winter Vacation') === false && strpos($attend_rep[$k]->details, 'Summer Vacation') === false) {
                                                for ($m = ($j + 1); $m < $len; $m++) {
                                                    if ($attend_rep[$m]->status == 'Present') {
                                                        break;
                                                    } elseif (($attend_rep[$m]->status == 'Absent' || $attend_rep[$m]->status == null) && strpos($attend_rep[$m]->details, 'Holiday') === false && strpos($attend_rep[$m]->details, '(CL Provided)') === false && strpos($attend_rep[$m]->details, 'Admin OD') === false && strpos($attend_rep[$m]->details, 'Exam OD') === false && strpos($attend_rep[$m]->details, 'Training OD') === false && strpos($attend_rep[$m]->details, 'Compensation Leave') === false && strpos($attend_rep[$m]->details, 'Winter Vacation') === false && strpos($attend_rep[$m]->details, 'Summer Vacation') === false) {
                                                        $leave++;
                                                        $temStatus = true;
                                                        break;
                                                    }
                                                }
                                            }
                                            if ($temStatus == true) {
                                                break;
                                            }
                                        }
                                        // if ($temStatus == true) {
                                        //     break;
                                        // }
                                    }
                                }
                            }

                            // Holiday
                            $temStatus = false;
                            if ($attend_rep[$j]->day != 'Sunday' && ($attend_rep[$j]->status == 'Absent' || $attend_rep[$j]->status == null) && strpos($attend_rep[$j]->details, 'Holiday') !== false) {
                                if ($j > 0 && $j < $len) {

                                    if ($attend_rep[$j - 1]->day != 'Sunday' && ($attend_rep[$j - 1]->status == 'Absent' || $attend_rep[$j - 1]->status == null) && strpos($attend_rep[$j - 1]->details, 'Holiday') === false && strpos($attend_rep[$j - 1]->details, '(CL Provided)') === false && strpos($attend_rep[$j - 1]->details, 'Admin OD') === false && strpos($attend_rep[$j - 1]->details, 'Exam OD') === false && strpos($attend_rep[$j - 1]->details, 'Training OD') === false && strpos($attend_rep[$j - 1]->details, 'Compensation Leave') === false && strpos($attend_rep[$j - 1]->details, 'Winter Vacation') === false && strpos($attend_rep[$j - 1]->details, 'Summer Vacation') === false) {
                                        for ($m = ($j + 1); $m < $len; $m++) {
                                            if ($attend_rep[$m]->status == 'Present') {
                                                break;
                                            } elseif (($attend_rep[$m]->status == 'Absent' || $attend_rep[$m]->status == null) && strpos($attend_rep[$m]->details, 'Holiday') === false && strpos($attend_rep[$m]->details, '(CL Provided)') === false && strpos($attend_rep[$m]->details, 'Admin OD') === false && strpos($attend_rep[$m]->details, 'Exam OD') === false && strpos($attend_rep[$m]->details, 'Training OD') === false && strpos($attend_rep[$m]->details, 'Compensation Leave') === false && strpos($attend_rep[$m]->details, 'Winter Vacation') === false && strpos($attend_rep[$m]->details, 'Summer Vacation') === false) {
                                                $leave++;
                                                break;
                                            }
                                        }
                                        if ($j == ($len - 1)) {
                                            $takeDate = Carbon::parse($attend_rep[$j]->date);
                                            $nextDate = $takeDate->addDay();

                                            $getNextDay = StaffBiometric::where(['user_name_id' => $attend_rep[$j]->user_name_id, 'date' => $nextDate->toDateString()])->select('day', 'details', 'status')->first();
                                            if (($getNextDay->day != 'Sunday' || strpos($getNextDay->details, 'Holiday') === false)) {
                                                if (($getNextDay->status == 'Absent' || $getNextDay->status == null) && strpos($getNextDay->details, 'Holiday') === false && strpos($getNextDay->details, '(CL Provided)') === false && strpos($getNextDay->details, 'Admin OD') === false && strpos($getNextDay->details, 'Exam OD') === false && strpos($getNextDay->details, 'Training OD') === false && strpos($getNextDay->details, 'Compensation Leave') === false && strpos($getNextDay->details, 'Winter Vacation') === false && strpos($getNextDay->details, 'Summer Vacation') === false) {
                                                    $leave++;
                                                }
                                            } else {
                                                $leave++;
                                            }
                                        }
                                    } else if (strpos($attend_rep[$j - 1]->details, 'Holiday') !== false || $attend_rep[$j - 1]->day == 'Sunday') {

                                        for ($k = ($j - 2); $k > 0; $k--) {
                                            if ($attend_rep[$k]->status == 'Present') {
                                                break;
                                            } elseif (($attend_rep[$k]->status == 'Absent' || $attend_rep[$k]->status == null) && $attend_rep[$k]->day != 'Sunday' && strpos($attend_rep[$k]->details, 'Holiday') === false && strpos($attend_rep[$k]->details, '(CL Provided)') === false && strpos($attend_rep[$k]->details, 'Admin OD') === false && strpos($attend_rep[$k]->details, 'Exam OD') === false && strpos($attend_rep[$k]->details, 'Training OD') === false && strpos($attend_rep[$k]->details, 'Compensation Leave') === false && strpos($attend_rep[$k]->details, 'Winter Vacation') === false && strpos($attend_rep[$k]->details, 'Summer Vacation') === false) {
                                                for ($m = ($j + 1); $m < $len; $m++) {
                                                    if ($attend_rep[$m]->status == 'Present') {
                                                        break;
                                                    } elseif (($attend_rep[$m]->status == 'Absent' || $attend_rep[$m]->status == null) && strpos($attend_rep[$m]->details, 'Holiday') === false && strpos($attend_rep[$m]->details, '(CL Provided)') === false && strpos($attend_rep[$m]->details, 'Admin OD') === false && strpos($attend_rep[$m]->details, 'Exam OD') === false && strpos($attend_rep[$m]->details, 'Training OD') === false && strpos($attend_rep[$m]->details, 'Compensation Leave') === false && strpos($attend_rep[$m]->details, 'Winter Vacation') === false && strpos($attend_rep[$m]->details, 'Summer Vacation') === false) {
                                                        $leave++;
                                                        $temStatus = true;
                                                        break;
                                                    }
                                                }
                                                if ($temStatus == true) {
                                                    break;
                                                }
                                            }
                                            if ($temStatus == true) {
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $doj_query = PersonalDetail::where('user_name_id', $staff->user_name_id)->get();
                if ($doj_query) {
                    if (!$doj_query->count() <= 0) {

                        $doj = $doj_query[0]->DOJ;

                    } else {

                        $doj = null;

                    }
                } else {
                    $doj = null;
                }

                $late = 0;
                $permission_shift_1 = 0;
                $permission_shift_2 = 0;

                foreach ($attend_rep as $day) {
                    if ($attendance = $day) {
                        if (strpos($attendance->details, 'Late') !== false && strpos($attendance->details, 'Too Late') === false) {
                            $late++;
                        }

                        if ($attendance->shift == '1') {
                            if ($attendance->permission == 'FN Permission' && $attendance->permission == 'AN Permission') {
                                $permission_shift_1 += 2;
                            } elseif ($attendance->permission == 'FN Permission') {
                                $permission_shift_1++;
                            } elseif ($attendance->permission == 'AN Permission') {
                                $permission_shift_1++;
                            }
                        } elseif ($attendance->shift == '2') {
                            if ($attendance->permission == 'FN Permission' && $attendance->permission == 'AN Permission') {
                                $permission_shift_2 += 2;
                            } elseif ($attendance->permission == 'FN Permission') {
                                $permission_shift_2++;
                            } elseif ($attendance->permission == 'AN Permission') {
                                $permission_shift_2++;
                            }
                        }

                    }
                }
                if ($late > 3) {
                    $late_lop = 0.5;
                } else {
                    $late_lop = 0;
                }

                $m_total_paid_days = count($day_array) - ($leave + $too_late + $late_lop);

                $m_total_working_days = count($day_array);

                $m_leave = $leave + $late_lop + $too_late;

                $salary = $staff;
                // Basic Pay Calculation
                if (isset($salary->basicPay) && !empty($salary->basicPay && !is_nan($salary->basicPay))) {
                    $m_per_day_basic_pay = $salary->basicPay / $m_total_working_days;

                    $m_half_day_basic_pay = $m_per_day_basic_pay / 2;
                    if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                        $basic_pay_permis_deduct = ($m_per_day_basic_pay / 7) * ($permission_shift_1 - 2);
                    } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                        $basic_pay_permis_deduct = ($m_per_day_basic_pay / 9) * ($permission_shift_2 - 2);
                    } else {
                        $basic_pay_permis_deduct = 0;
                    }

                    if ($late > 3) {
                        $deduct_basic_pay = $late - 3;

                        $late_deduct_basic_pay = $m_half_day_basic_pay * $deduct_basic_pay;
                    } else {
                        $late_deduct_basic_pay = 0;
                    }

                    if ($too_late > 0) {

                        $too_late_deduct_basic_pay = $m_half_day_basic_pay * $too_late;
                    } else {
                        $too_late_deduct_basic_pay = 0;
                    }

                    $m_basic_pay = round($salary->basicPay * ($m_total_paid_days / $m_total_working_days) - ($basic_pay_permis_deduct + $late_deduct_basic_pay + $too_late_deduct_basic_pay), 2);
                    $m_basic_pay_loss = $salary->basicPay - $m_basic_pay;
                } else {
                    $m_basic_pay = 0;
                    $m_basic_pay_loss = 0;
                }

                // AGP Calculation
                if (isset($salary->agp) && !empty($salary->agp && !is_nan($salary->agp))) {
                    $m_per_day_agp = $salary->agp / $m_total_working_days;

                    $m_half_day_agp = $m_per_day_agp / 2;
                    if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                        $agp_permis_deduct = ($m_per_day_agp / 7) * ($permission_shift_1 - 2);
                    } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                        $agp_permis_deduct = ($m_per_day_agp / 9) * ($permission_shift_2 - 2);
                    } else {
                        $agp_permis_deduct = 0;
                    }

                    if ($late > 3) {
                        $deduct_agp = $late - 3;

                        $late_deduct_agp = $m_half_day_agp * $deduct_agp;
                    } else {
                        $late_deduct_agp = 0;
                    }

                    if ($too_late > 0) {

                        $too_late_deduct_agp = $m_half_day_agp * $too_late;
                    } else {
                        $too_late_deduct_agp = 0;
                    }

                    $m_agp = round($salary->agp * ($m_total_paid_days / $m_total_working_days) - ($agp_permis_deduct + $late_deduct_agp + $too_late_deduct_agp), 2);
                    $m_agp_loss = $salary->agp - $m_agp;
                } else {
                    $m_agp = 0;
                    $m_agp_loss = 0;
                }

                // DA Calculation
                $m_da = round(($m_basic_pay + $m_agp) * 0.55, 2);
                $m_da_loss = $salary->da - $m_da;

                // HRA Calculation
                if ($salary->hra == '' || $salary->hra == null) {
                    $salary_hra = 0;
                } else {
                    $salary_hra = $salary->hra;
                }
                $m_hra = round(($m_agp + $m_da) * ($salary_hra / 100), 2);

                $m_hra_loss = $salary->hra_amount - $m_hra;

                // SpecialFee Calculation
                if (isset($salary->specialFee) && !empty($salary->specialFee && !is_nan($salary->specialFee))) {
                    $m_per_day_specialFee = $salary->specialFee / $m_total_working_days;

                    $m_half_day_specialFee = $m_per_day_specialFee / 2;
                    if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                        $specialFee_permis_deduct = ($m_per_day_specialFee / 7) * ($permission_shift_1 - 2);
                    } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                        $specialFee_permis_deduct = ($m_per_day_specialFee / 9) * ($permission_shift_2 - 2);
                    } else {
                        $specialFee_permis_deduct = 0;
                    }

                    if ($late > 3) {
                        $deduct_specialFee = $late - 3;

                        $late_deduct_specialFee = $m_half_day_specialFee * $deduct_specialFee;
                    } else {
                        $late_deduct_specialFee = 0;
                    }

                    if ($too_late > 0) {

                        $too_late_deduct_specialFee = $m_half_day_specialFee * $too_late;
                    } else {
                        $too_late_deduct_specialFee = 0;
                    }

                    $m_specialFee = round($salary->specialFee * ($m_total_paid_days / $m_total_working_days) - ($specialFee_permis_deduct + $late_deduct_specialFee + $too_late_deduct_specialFee), 2);
                    $m_specialFee_loss = $salary->specialFee - $m_specialFee;
                } else {
                    $m_specialFee = 0;
                    $m_specialFee_loss = 0;
                }

                // Phd Allowance Calculation
                if (isset($salary->phdAllowance) && !empty($salary->phdAllowance && !is_nan($salary->phdAllowance))) {
                    $m_per_day_phdAllowance = $salary->phdAllowance / $m_total_working_days;

                    $m_half_day_phdAllowance = $m_per_day_phdAllowance / 2;
                    if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                        $phdAllowance_permis_deduct = ($m_per_day_phdAllowance / 7) * ($permission_shift_1 - 2);
                    } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                        $phdAllowance_permis_deduct = ($m_per_day_phdAllowance / 9) * ($permission_shift_2 - 2);
                    } else {
                        $phdAllowance_permis_deduct = 0;
                    }

                    if ($late > 3) {
                        $deduct_phdAllowance = $late - 3;

                        $late_deduct_phdAllowance = $m_half_day_phdAllowance * $deduct_phdAllowance;
                    } else {
                        $late_deduct_phdAllowance = 0;
                    }

                    if ($too_late > 0) {

                        $too_late_deduct_phdAllowance = $m_half_day_phdAllowance * $too_late;
                    } else {
                        $too_late_deduct_phdAllowance = 0;
                    }

                    $m_phdAllowance = round($salary->phdAllowance * ($m_total_paid_days / $m_total_working_days) - ($phdAllowance_permis_deduct + $late_deduct_phdAllowance + $too_late_deduct_phdAllowance), 2);
                    $m_phdAllowance_loss = $salary->phdAllowance - $m_phdAllowance;
                } else {
                    $m_phdAllowance = 0;
                    $m_phdAllowance_loss = 0;
                }

                // Other Allowance Calculation
                if (isset($salary->otherAllowence) && !empty($salary->otherAllowence && !is_nan($salary->otherAllowence))) {
                    $m_per_day_otherAllowence = $salary->otherAllowence / $m_total_working_days;

                    $m_half_day_otherAllowence = $m_per_day_otherAllowence / 2;
                    if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                        $otherAllowence_permis_deduct = ($m_per_day_otherAllowence / 7) * ($permission_shift_1 - 2);
                    } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                        $otherAllowence_permis_deduct = ($m_per_day_otherAllowence / 9) * ($permission_shift_2 - 2);
                    } else {
                        $otherAllowence_permis_deduct = 0;
                    }

                    if ($late > 3) {
                        $deduct_otherAllowence = $late - 3;

                        $late_deduct_otherAllowence = $m_half_day_otherAllowence * $deduct_otherAllowence;
                    } else {
                        $late_deduct_otherAllowence = 0;
                    }

                    if ($too_late > 0) {

                        $too_late_deduct_otherAllowence = $m_half_day_otherAllowence * $too_late;
                    } else {
                        $too_late_deduct_otherAllowence = 0;
                    }

                    $m_otherAllowence = round($salary->otherAllowence * ($m_total_paid_days / $m_total_working_days) - ($otherAllowence_permis_deduct + $late_deduct_otherAllowence + $too_late_deduct_otherAllowence), 2);
                    $m_otherAllowence_loss = $salary->otherAllowence - $m_otherAllowence;
                } else {
                    $m_otherAllowence = 0;
                    $m_otherAllowence_loss = 0;
                }

                $deduction = round($m_basic_pay_loss + $m_agp_loss + $m_da_loss + $m_hra_loss + $m_specialFee_loss + $m_phdAllowance_loss + $m_otherAllowence_loss, 2);
                $gross_salary = round($m_basic_pay + $m_agp + $m_da + $m_hra + $m_specialFee + $m_phdAllowance + $m_otherAllowence, 2);
                $net_salary = round($gross_salary - $deduction, 2);
                if ($net_salary <= 0) {
                    $net_salary = 0;
                }

                $checker = salarystatement::where(['user_name_id' => $staff->user_name_id, 'month' => $monthName, 'year' => $year])->get();

                if (count($checker) <= 0) {
                    $salary_statement = new salarystatement;
                    $salary_statement->basicpay = $m_basic_pay;
                    $salary_statement->agp = $m_agp;
                    $salary_statement->da = $m_da;
                    $salary_statement->hra = $m_hra;
                    $salary_statement->specialpay = $m_specialFee;
                    $salary_statement->phdallowance = $m_phdAllowance;
                    $salary_statement->otherall = $m_otherAllowence;
                    $salary_statement->gross_salary = $gross_salary;
                    $salary_statement->netpay = $net_salary;
                    $salary_statement->lop = $deduction;
                    $salary_statement->month = $monthName;
                    $salary_statement->year = $year;
                    $salary_statement->department = $staff->Dept;
                    $salary_statement->name = $staff->name;
                    $salary_statement->user_name_id = $staff->user_name_id;
                    $salary_statement->doj = $doj;
                    $salary_statement->total_working_days = $m_total_working_days;
                    $salary_statement->total_payable_days = $m_total_paid_days;
                    $salary_statement->total_lop_days = $m_leave;
                    $salary_statement->save();
                }

            }
        }

        foreach ($non_teaching_staff as $staff) {

            $query = StaffBiometric::where('staff_code', $staff->StaffCode)
                ->whereBetween('date', [$previousYear . '-' . $previousmonth . '-26', $year . '-' . $month . '-25'])
                ->get();

            if (!$query->count() <= 0) {

                $attend_rep = $query;

                $doj_query = PersonalDetail::where('user_name_id', $staff->user_name_id)->get();
                if ($doj_query) {
                    if (!$doj_query->count() <= 0) {

                        $doj = $doj_query[0]->DOJ;

                    } else {

                        $doj = null;

                    }
                } else {
                    $doj = null;
                }

                $leave = 0;
                $half_day_leave = 0;
                $too_late = 0;
                if ($attend_rep != '') {
                    $len = count($attend_rep);
                    if ($len > 0) {
                        for ($i = 0; $i < $len; $i++) {
                            if (strpos($attend_rep[$i]->details, '(CL Provided)') === false && (strpos($attend_rep[$i]->details, 'Fore Noon Casual Leave') !== false || strpos($attend_rep[$i]->details, 'After Noon Casual Leave') !== false)) {
                                $half_day_leave += 0.5;
                            }
                            if (strpos($attend_rep[$i]->details, 'Early Out') !== false) {
                                if ($attend_rep[$i]->shift == 1 && strtotime($attend_rep[$i]->out_time) < strtotime('11:00:00')) {
                                    $leave += 1;
                                } else if ($attend_rep[$i]->shift == 1 && strtotime($attend_rep[$i]->out_time) < strtotime('16:00:00')) {
                                    $half_day_leave += 0.5;
                                }
                                if ($attend_rep[$i]->shift == 2 && strtotime($attend_rep[$i]->out_time) < strtotime('11:00:00')) {
                                    $leave += 1;
                                } else if ($attend_rep[$i]->shift == 2 && strtotime($attend_rep[$i]->out_time) < strtotime('17:00:00')) {
                                    $half_day_leave += 0.5;
                                }
                            }
                        }

                        for ($j = 0; $j < $len; $j++) {

                            //Casual Leave
                            if ($attend_rep[$j]->day != 'Sunday' && ($attend_rep[$j]->status == 'Absent' || $attend_rep[$j]->status == null) && (strpos($attend_rep[$j]->details, 'Holiday') === false && strpos($attend_rep[$j]->details, '(CL Provided)') === false && strpos($attend_rep[$j]->details, 'Admin OD') === false && strpos($attend_rep[$j]->details, 'Exam OD') === false && strpos($attend_rep[$j]->details, 'Training OD') === false && strpos($attend_rep[$j]->details, 'Compensation Leave') === false && strpos($attend_rep[$j]->details, 'Winter Vacation') === false && strpos($attend_rep[$j]->details, 'Summer Vacation') === false) && (strpos($attend_rep[$j]->details, 'Casual Leave') !== false || $attend_rep[$j]->details == null)) {
                                $leave++;
                            }

                            if ($attend_rep[$j]->day != 'Sunday' && ($attend_rep[$j]->status == 'Absent' || $attend_rep[$j]->status == 'Present') && strpos($attend_rep[$j]->details, 'Too Late') !== false) {
                                $too_late += 0.5;
                            }
                            //Sunday
                            $temStatus = false;

                            if ($attend_rep[$j]->day == 'Sunday' && ($attend_rep[$j]->status == 'Absent' || $attend_rep[$j]->status == null) && strpos($attend_rep[$j]->details, 'Holiday') === false && strpos($attend_rep[$j]->details, '(CL Provided)') === false && strpos($attend_rep[$j]->details, 'Admin OD') === false && strpos($attend_rep[$j]->details, 'Exam OD') === false && strpos($attend_rep[$j]->details, 'Training OD') === false && strpos($attend_rep[$j]->details, 'Compensation Leave') === false && strpos($attend_rep[$j]->details, 'Winter Vacation') === false && strpos($attend_rep[$j]->details, 'Summer Vacation') === false && strpos($attend_rep[$j]->details, 'Too Late') === false && strpos($attend_rep[$j]->details, 'Casual Leave') === false) {

                                if ($j > 0 && $j < $len) {

                                    if ($attend_rep[$j - 1]->day != 'Sunday' && ($attend_rep[$j - 1]->status == 'Absent' || $attend_rep[$j - 1]->status == null) && strpos($attend_rep[$j - 1]->details, 'Holiday') === false && strpos($attend_rep[$j - 1]->details, '(CL Provided)') === false && strpos($attend_rep[$j - 1]->details, 'Admin OD') === false && strpos($attend_rep[$j - 1]->details, 'Exam OD') === false && strpos($attend_rep[$j - 1]->details, 'Training OD') === false && strpos($attend_rep[$j - 1]->details, 'Compensation Leave') === false && strpos($attend_rep[$j - 1]->details, 'Winter Vacation') === false && strpos($attend_rep[$j - 1]->details, 'Summer Vacation') === false) {
                                        for ($m = ($j + 1); $m < $len; $m++) {
                                            if ($attend_rep[$m]->status == 'Present') {
                                                break;
                                            } elseif (($attend_rep[$m]->status == 'Absent' || $attend_rep[$m]->status == null) && strpos($attend_rep[$m]->details, 'Holiday') === false && strpos($attend_rep[$m]->details, '(CL Provided)') === false && strpos($attend_rep[$m]->details, 'Admin OD') === false && strpos($attend_rep[$m]->details, 'Exam OD') === false && strpos($attend_rep[$m]->details, 'Training OD') === false && strpos($attend_rep[$m]->details, 'Compensation Leave') === false && strpos($attend_rep[$m]->details, 'Winter Vacation') === false && strpos($attend_rep[$m]->details, 'Summer Vacation') === false) {
                                                $leave++;
                                                break;
                                            }
                                        }
                                    } else if (strpos($attend_rep[$j - 1]->details, 'Holiday') !== false) {
                                        for ($k = ($j - 2); $k > 0; $k--) {
                                            if ($attend_rep[$k]->status == 'Present') {
                                                break;
                                            } elseif (($attend_rep[$k]->status == 'Absent' || $attend_rep[$k]->status == null) && strpos($attend_rep[$k]->details, 'Holiday') === false && strpos($attend_rep[$k]->details, '(CL Provided)') === false && strpos($attend_rep[$k]->details, 'Admin OD') === false && strpos($attend_rep[$k]->details, 'Exam OD') === false && strpos($attend_rep[$k]->details, 'Training OD') === false && strpos($attend_rep[$k]->details, 'Compensation Leave') === false && strpos($attend_rep[$k]->details, 'Winter Vacation') === false && strpos($attend_rep[$k]->details, 'Summer Vacation') === false) {
                                                for ($m = ($j + 1); $m < $len; $m++) {
                                                    if ($attend_rep[$m]->status == 'Present') {
                                                        break;
                                                    } elseif (($attend_rep[$m]->status == 'Absent' || $attend_rep[$m]->status == null) && strpos($attend_rep[$m]->details, 'Holiday') === false && strpos($attend_rep[$m]->details, '(CL Provided)') === false && strpos($attend_rep[$m]->details, 'Admin OD') === false && strpos($attend_rep[$m]->details, 'Exam OD') === false && strpos($attend_rep[$m]->details, 'Training OD') === false && strpos($attend_rep[$m]->details, 'Compensation Leave') === false && strpos($attend_rep[$m]->details, 'Winter Vacation') === false && strpos($attend_rep[$m]->details, 'Summer Vacation') === false) {
                                                        $leave++;
                                                        $temStatus = true;
                                                        break;
                                                    }
                                                }
                                            }
                                            if ($temStatus == true) {
                                                break;
                                            }
                                        }
                                        // if ($temStatus == true) {
                                        //     break;
                                        // }
                                    }
                                }
                            }

                            // Holiday
                            $temStatus = false;
                            if ($attend_rep[$j]->day != 'Sunday' && ($attend_rep[$j]->status == 'Absent' || $attend_rep[$j]->status == null) && strpos($attend_rep[$j]->details, 'Holiday') !== false) {
                                if ($j > 0 && $j < $len) {

                                    if ($attend_rep[$j - 1]->day != 'Sunday' && ($attend_rep[$j - 1]->status == 'Absent' || $attend_rep[$j - 1]->status == null) && strpos($attend_rep[$j - 1]->details, 'Holiday') === false && strpos($attend_rep[$j - 1]->details, '(CL Provided)') === false && strpos($attend_rep[$j - 1]->details, 'Admin OD') === false && strpos($attend_rep[$j - 1]->details, 'Exam OD') === false && strpos($attend_rep[$j - 1]->details, 'Training OD') === false && strpos($attend_rep[$j - 1]->details, 'Compensation Leave') === false && strpos($attend_rep[$j - 1]->details, 'Winter Vacation') === false && strpos($attend_rep[$j - 1]->details, 'Summer Vacation') === false) {
                                        for ($m = ($j + 1); $m < $len; $m++) {
                                            if ($attend_rep[$m]->status == 'Present') {
                                                break;
                                            } elseif (($attend_rep[$m]->status == 'Absent' || $attend_rep[$m]->status == null) && strpos($attend_rep[$m]->details, 'Holiday') === false && strpos($attend_rep[$m]->details, '(CL Provided)') === false && strpos($attend_rep[$m]->details, 'Admin OD') === false && strpos($attend_rep[$m]->details, 'Exam OD') === false && strpos($attend_rep[$m]->details, 'Training OD') === false && strpos($attend_rep[$m]->details, 'Compensation Leave') === false && strpos($attend_rep[$m]->details, 'Winter Vacation') === false && strpos($attend_rep[$m]->details, 'Summer Vacation') === false) {
                                                $leave++;
                                                break;
                                            }
                                        }
                                        if ($j == ($len - 1)) {
                                            $takeDate = Carbon::parse($attend_rep[$j]->date);
                                            $nextDate = $takeDate->addDay();

                                            $getNextDay = StaffBiometric::where(['user_name_id' => $attend_rep[$j]->user_name_id, 'date' => $nextDate->toDateString()])->select('day', 'details', 'status')->first();
                                            if (($getNextDay->day != 'Sunday' || strpos($getNextDay->details, 'Holiday') === false)) {
                                                if (($getNextDay->status == 'Absent' || $getNextDay->status == null) && strpos($getNextDay->details, 'Holiday') === false && strpos($getNextDay->details, '(CL Provided)') === false && strpos($getNextDay->details, 'Admin OD') === false && strpos($getNextDay->details, 'Exam OD') === false && strpos($getNextDay->details, 'Training OD') === false && strpos($getNextDay->details, 'Compensation Leave') === false && strpos($getNextDay->details, 'Winter Vacation') === false && strpos($getNextDay->details, 'Summer Vacation') === false) {
                                                    $leave++;
                                                }
                                            } else {
                                                $leave++;
                                            }
                                        }
                                    } else if (strpos($attend_rep[$j - 1]->details, 'Holiday') !== false || $attend_rep[$j - 1]->day == 'Sunday') {

                                        for ($k = ($j - 2); $k > 0; $k--) {
                                            if ($attend_rep[$k]->status == 'Present') {
                                                break;
                                            } elseif (($attend_rep[$k]->status == 'Absent' || $attend_rep[$k]->status == null) && $attend_rep[$k]->day != 'Sunday' && strpos($attend_rep[$k]->details, 'Holiday') === false && strpos($attend_rep[$k]->details, '(CL Provided)') === false && strpos($attend_rep[$k]->details, 'Admin OD') === false && strpos($attend_rep[$k]->details, 'Exam OD') === false && strpos($attend_rep[$k]->details, 'Training OD') === false && strpos($attend_rep[$k]->details, 'Compensation Leave') === false && strpos($attend_rep[$k]->details, 'Winter Vacation') === false && strpos($attend_rep[$k]->details, 'Summer Vacation') === false) {
                                                for ($m = ($j + 1); $m < $len; $m++) {
                                                    if ($attend_rep[$m]->status == 'Present') {
                                                        break;
                                                    } elseif (($attend_rep[$m]->status == 'Absent' || $attend_rep[$m]->status == null) && strpos($attend_rep[$m]->details, 'Holiday') === false && strpos($attend_rep[$m]->details, '(CL Provided)') === false && strpos($attend_rep[$m]->details, 'Admin OD') === false && strpos($attend_rep[$m]->details, 'Exam OD') === false && strpos($attend_rep[$m]->details, 'Training OD') === false && strpos($attend_rep[$m]->details, 'Compensation Leave') === false && strpos($attend_rep[$m]->details, 'Winter Vacation') === false && strpos($attend_rep[$m]->details, 'Summer Vacation') === false) {
                                                        $leave++;
                                                        $temStatus = true;
                                                        break;
                                                    }
                                                }
                                                if ($temStatus == true) {
                                                    break;
                                                }
                                            }
                                            if ($temStatus == true) {
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $late = 0;
                $permission_shift_1 = 0;
                $permission_shift_2 = 0;

                foreach ($attend_rep as $day) {
                    if ($attendance = $day) {
                        if (strpos($attendance->details, 'Late') !== false && strpos($attendance->details, 'Too Late') === false) {
                            $late++;
                        }

                        if ($attendance->shift == '1') {
                            if ($attendance->permission == 'FN Permission' && $attendance->permission == 'AN Permission') {
                                $permission_shift_1 += 2;
                            } elseif ($attendance->permission == 'FN Permission') {
                                $permission_shift_1++;
                            } elseif ($attendance->permission == 'AN Permission') {
                                $permission_shift_1++;
                            }
                        } elseif ($attendance->shift == '2') {
                            if ($attendance->permission == 'FN Permission' && $attendance->permission == 'AN Permission') {
                                $permission_shift_2 += 2;
                            } elseif ($attendance->permission == 'FN Permission') {
                                $permission_shift_2++;
                            } elseif ($attendance->permission == 'AN Permission') {
                                $permission_shift_2++;
                            }
                        }

                    }
                }

                if ($late > 3) {
                    $late_lop = 0.5;
                } else {
                    $late_lop = 0;
                }

                $m_total_paid_days = count($day_array) - ($leave + $too_late + $late_lop);

                $m_total_working_days = count($day_array);

                $m_leave = $leave + $late_lop + $too_late;

                $salary = $staff;

                // Basic Pay Calculation
                if (isset($salary->basicPay) && !empty($salary->basicPay && !is_nan($salary->basicPay))) {
                    $m_per_day_basic_pay = $salary->basicPay / $m_total_working_days;

                    $m_half_day_basic_pay = $m_per_day_basic_pay / 2;
                    if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                        $basic_pay_permis_deduct = ($m_per_day_basic_pay / 7) * ($permission_shift_1 - 2);
                    } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                        $basic_pay_permis_deduct = ($m_per_day_basic_pay / 9) * ($permission_shift_2 - 2);
                    } else {
                        $basic_pay_permis_deduct = 0;
                    }

                    if ($late > 3) {
                        $deduct_basic_pay = $late - 3;

                        $late_deduct_basic_pay = $m_half_day_basic_pay * $deduct_basic_pay;
                    } else {
                        $late_deduct_basic_pay = 0;
                    }

                    if ($too_late > 0) {

                        $too_late_deduct_basic_pay = $m_half_day_basic_pay * $too_late;
                    } else {
                        $too_late_deduct_basic_pay = 0;
                    }

                    $m_basic_pay = round($salary->basicPay * ($m_total_paid_days / $m_total_working_days) - ($basic_pay_permis_deduct + $late_deduct_basic_pay + $too_late_deduct_basic_pay), 2);
                    $m_basic_pay_loss = $salary->basicPay - $m_basic_pay;
                } else {
                    $m_basic_pay = 0;
                    $m_basic_pay_loss = 0;
                }

                // AGP Calculation
                if (isset($salary->agp) && !empty($salary->agp && !is_nan($salary->agp))) {
                    $m_per_day_agp = $salary->agp / $m_total_working_days;

                    $m_half_day_agp = $m_per_day_agp / 2;
                    if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                        $agp_permis_deduct = ($m_per_day_agp / 7) * ($permission_shift_1 - 2);
                    } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                        $agp_permis_deduct = ($m_per_day_agp / 9) * ($permission_shift_2 - 2);
                    } else {
                        $agp_permis_deduct = 0;
                    }

                    if ($late > 3) {
                        $deduct_agp = $late - 3;

                        $late_deduct_agp = $m_half_day_agp * $deduct_agp;
                    } else {
                        $late_deduct_agp = 0;
                    }

                    if ($too_late > 0) {

                        $too_late_deduct_agp = $m_half_day_agp * $too_late;
                    } else {
                        $too_late_deduct_agp = 0;
                    }

                    $m_agp = round($salary->agp * ($m_total_paid_days / $m_total_working_days) - ($agp_permis_deduct + $late_deduct_agp + $too_late_deduct_agp), 2);
                    $m_agp_loss = $salary->agp - $m_agp;
                } else {
                    $m_agp = 0;
                    $m_agp_loss = 0;
                }

                // DA Calculation
                $m_da = round(($m_basic_pay + $m_agp) * 0.55, 2);
                $m_da_loss = $salary->da - $m_da;

                // HRA Calculation
                if ($salary->hra == '' || $salary->hra == null) {
                    $salary_hra = 0;
                } else {
                    $salary_hra = $salary->hra;
                }
                $m_hra = round(($m_agp + $m_da) * ($salary_hra / 100), 2);

                $m_hra_loss = $salary->hra_amount - $m_hra;

                // SpecialFee Calculation
                if (isset($salary->specialFee) && !empty($salary->specialFee && !is_nan($salary->specialFee))) {
                    $m_per_day_specialFee = $salary->specialFee / $m_total_working_days;

                    $m_half_day_specialFee = $m_per_day_specialFee / 2;
                    if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                        $specialFee_permis_deduct = ($m_per_day_specialFee / 7) * ($permission_shift_1 - 2);
                    } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                        $specialFee_permis_deduct = ($m_per_day_specialFee / 9) * ($permission_shift_2 - 2);
                    } else {
                        $specialFee_permis_deduct = 0;
                    }

                    if ($late > 3) {
                        $deduct_specialFee = $late - 3;

                        $late_deduct_specialFee = $m_half_day_specialFee * $deduct_specialFee;
                    } else {
                        $late_deduct_specialFee = 0;
                    }

                    if ($too_late > 0) {

                        $too_late_deduct_specialFee = $m_half_day_specialFee * $too_late;
                    } else {
                        $too_late_deduct_specialFee = 0;
                    }

                    $m_specialFee = round($salary->specialFee * ($m_total_paid_days / $m_total_working_days) - ($specialFee_permis_deduct + $late_deduct_specialFee + $too_late_deduct_specialFee), 2);
                    $m_specialFee_loss = $salary->specialFee - $m_specialFee;
                } else {
                    $m_specialFee = 0;
                    $m_specialFee_loss = 0;
                }

                // Phd Allowance Calculation
                if (isset($salary->phdAllowance) && !empty($salary->phdAllowance && !is_nan($salary->phdAllowance))) {
                    $m_per_day_phdAllowance = $salary->phdAllowance / $m_total_working_days;

                    $m_half_day_phdAllowance = $m_per_day_phdAllowance / 2;
                    if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                        $phdAllowance_permis_deduct = ($m_per_day_phdAllowance / 7) * ($permission_shift_1 - 2);
                    } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                        $phdAllowance_permis_deduct = ($m_per_day_phdAllowance / 9) * ($permission_shift_2 - 2);
                    } else {
                        $phdAllowance_permis_deduct = 0;
                    }

                    if ($late > 3) {
                        $deduct_phdAllowance = $late - 3;

                        $late_deduct_phdAllowance = $m_half_day_phdAllowance * $deduct_phdAllowance;
                    } else {
                        $late_deduct_phdAllowance = 0;
                    }

                    if ($too_late > 0) {

                        $too_late_deduct_phdAllowance = $m_half_day_phdAllowance * $too_late;
                    } else {
                        $too_late_deduct_phdAllowance = 0;
                    }

                    $m_phdAllowance = round($salary->phdAllowance * ($m_total_paid_days / $m_total_working_days) - ($phdAllowance_permis_deduct + $late_deduct_phdAllowance + $too_late_deduct_phdAllowance), 2);
                    $m_phdAllowance_loss = $salary->phdAllowance - $m_phdAllowance;
                } else {
                    $m_phdAllowance = 0;
                    $m_phdAllowance_loss = 0;
                }

                // Other Allowance Calculation
                if (isset($salary->otherAllowence) && !empty($salary->otherAllowence && !is_nan($salary->otherAllowence))) {
                    $m_per_day_otherAllowence = $salary->otherAllowence / $m_total_working_days;

                    $m_half_day_otherAllowence = $m_per_day_otherAllowence / 2;
                    if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                        $otherAllowence_permis_deduct = ($m_per_day_otherAllowence / 7) * ($permission_shift_1 - 2);
                    } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                        $otherAllowence_permis_deduct = ($m_per_day_otherAllowence / 9) * ($permission_shift_2 - 2);
                    } else {
                        $otherAllowence_permis_deduct = 0;
                    }

                    if ($late > 3) {
                        $deduct_otherAllowence = $late - 3;

                        $late_deduct_otherAllowence = $m_half_day_otherAllowence * $deduct_otherAllowence;
                    } else {
                        $late_deduct_otherAllowence = 0;
                    }

                    if ($too_late > 0) {

                        $too_late_deduct_otherAllowence = $m_half_day_otherAllowence * $too_late;
                    } else {
                        $too_late_deduct_otherAllowence = 0;
                    }

                    $m_otherAllowence = round($salary->otherAllowence * ($m_total_paid_days / $m_total_working_days) - ($otherAllowence_permis_deduct + $late_deduct_otherAllowence + $too_late_deduct_otherAllowence), 2);
                    $m_otherAllowence_loss = $salary->otherAllowence - $m_otherAllowence;
                } else {
                    $m_otherAllowence = 0;
                    $m_otherAllowence_loss = 0;
                }

                $deduction = round($m_basic_pay_loss + $m_agp_loss + $m_da_loss + $m_hra_loss + $m_specialFee_loss + $m_phdAllowance_loss + $m_otherAllowence_loss, 2);
                $gross_salary = round($m_basic_pay + $m_agp + $m_da + $m_hra + $m_specialFee + $m_phdAllowance + $m_otherAllowence, 2);
                $net_salary = round($gross_salary - $deduction, 2);
                if ($net_salary <= 0) {
                    $net_salary = 0;
                }

                $checker = salarystatement::where(['user_name_id' => $staff->user_name_id, 'month' => $monthName, 'year' => $year])->get();

                if (count($checker) <= 0) {
                    $salary_statement = new salarystatement;
                    $salary_statement->basicpay = $m_basic_pay;
                    $salary_statement->agp = $m_agp;
                    $salary_statement->da = $m_da;
                    $salary_statement->hra = $m_hra;
                    $salary_statement->specialpay = $m_specialFee;
                    $salary_statement->phdallowance = $m_phdAllowance;
                    $salary_statement->otherall = $m_otherAllowence;
                    $salary_statement->gross_salary = $salary->gross_salary;
                    $salary_statement->earnings = $gross_salary;
                    $salary_statement->netpay = $net_salary;
                    $salary_statement->lop = $deduction;
                    $salary_statement->month = $monthName;
                    $salary_statement->year = $year;
                    $salary_statement->department = $staff->Dept;
                    $salary_statement->name = $staff->name;
                    $salary_statement->user_name_id = $staff->user_name_id;
                    $salary_statement->doj = $doj;
                    $salary_statement->total_working_days = $m_total_working_days;
                    $salary_statement->total_payable_days = $m_total_paid_days;
                    $salary_statement->total_lop_days = $m_leave;
                    $salary_statement->save();
                }

            }
        }

        $department = ToolsDepartment::pluck('name', 'id')->prepend('All Departments', '');

        $show = null;

        $year = Year::select('id', 'year')->get();

        return view('admin.salarystatement.index', compact('department', 'show', 'year'));
    }

    public function show()
    {

    }
    public function edit()
    {

    }
}
