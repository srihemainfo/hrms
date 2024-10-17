<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Models\BankAccountDetail;
use App\Models\NonTeachingStaff;
use App\Models\PersonalDetail;
use App\Models\salarystatement;
use App\Models\StaffBiometric;
use App\Models\Staffs;
use App\Models\ToolsDepartment;
use App\Models\User;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SalarystatementController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        // $department = ToolsDepartment::pluck('name', 'id')->prepend('All Departments', '');

        // $year = Year::select('id', 'year')->get();

        $show = null;

        return view('admin.salarystatement.index', compact('show'));
    }

    public function get_report(Request $request)
    {
        // dd($request);

        // $department = $request->department;
        $month = $request->month;
        $year = $request->year;

        if ( $month != '' && $year != '') {

            $statements = salarystatement::where([ 'month' => $month, 'year' => $year])->get();

        } elseif ( $month != '') {

            $statements = salarystatement::where(['month' => $month, 'year' => $year])->get();

        } elseif ( $month == '') {

            $statements = salarystatement::where([ 'year' => $year])->get();

        } elseif ( $month == '') {

            $statements = salarystatement::where(['year' => $year])->get();

        }

        // dd($statements);

        if ($request->ajax()) {

            $table = datatables()->of($statements);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = '';
                $editGate = 'salary_statement_edit';
                $deleteGate = '';
                $crudRoutePart = 'salary-statement';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                )
                );
            });

            $table->editColumn('month', function ($row) {
                return $row->month ? $row->month : '';
            });

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->editColumn('staff_code', function ($row) {
                if (isset($row->user_name_id)) {
                    $get_staff = Staffs::where(['user_name_id' => $row->user_name_id])->select('employee_id')->first();
                    if ($get_staff != '') {
                        return $get_staff->employee_id;
                    }

                }
                return '';
            });

            $table->editColumn('department', function ($row) {
                return $row->department ? $row->department : '';
            });
            $table->editColumn('designation', function ($row) {
                return $row->designation ? $row->designation : '';
            });
            $table->editColumn('doj', function ($row) {
                return $row->doj ? date('d-m-Y', strtotime($row->doj)) : 0;
            });
            $table->editColumn('total_working_days', function ($row) {
                return $row->total_working_days ? (float) $row->total_working_days : 0;
            });
            $table->editColumn('total_payable_days', function ($row) {
                return $row->total_payable_days ? (float) $row->total_payable_days : 0;
            });
            $table->editColumn('total_lop_days', function ($row) {
                return $row->total_lop_days ? (float) $row->total_lop_days : 0;
            });
            $table->editColumn('basicpay', function ($row) {
                return $row->basicpay ? (float) $row->basicpay : 0;
            });
            $table->editColumn('late', function ($row) {
                return $row->late_amt ? (float) $row->late_amt : 0;
            });
            $table->editColumn('agp', function ($row) {
                return $row->agp ? (float) $row->agp : 0;
            });
            $table->editColumn('da', function ($row) {
                return $row->da ? (float) $row->da : 0;
            });
            $table->editColumn('hra', function ($row) {
                return $row->hra ? (float) $row->hra : 0;
            });
            $table->editColumn('specialpay', function ($row) {
                return $row->specialpay ? (float) $row->specialpay : 0;
            });
            $table->editColumn('arrears', function ($row) {
                return $row->arrears ? (float) $row->arrears : 0;
            });
            $table->editColumn('otherall', function ($row) {
                return $row->otherall ? (float) $row->otherall : 0;
            });
            $table->editColumn('abi', function ($row) {
                return $row->abi ? (float) $row->abi : 0;
            });
            $table->editColumn('phdallowance', function ($row) {
                return $row->phdallowance ? (float) $row->phdallowance : 0;
            });
            $table->editColumn('earnings', function ($row) {
                return $row->earnings ? (float) $row->earnings : 0;
            });
            $table->editColumn('gross_salary', function ($row) {
                return $row->gross_salary ? (float) $row->gross_salary : 0;
            });
            $table->editColumn('it', function ($row) {
                return $row->it ? (float) $row->it : 0;
            });
            $table->editColumn('pt', function ($row) {
                return $row->pt ? (float) $row->pt : 0;
            });
            $table->editColumn('salaryadvance', function ($row) {
                return $row->salaryadvance ? (float) $row->salaryadvance : 0;
            });
            $table->editColumn('epf', function ($row) {
                return $row->epf ? (float) $row->epf : 0;
            });
            $table->editColumn('esi', function ($row) {
                return $row->esi ? (float) $row->esi : 0;
            });
            $table->editColumn('lop', function ($row) {
                return $row->lop ? (float) $row->lop : 0;
            });
            $table->editColumn('otherdeduction', function ($row) {
                return $row->otherdeduction ? (float) $row->otherdeduction : 0;

            });
            $table->editColumn('totaldeductions', function ($row) {
                return $row->totaldeductions ? (float) $row->totaldeductions : 0;
            });
            $table->editColumn('netpay', function ($row) {
                return $row->netpay ? (float) $row->netpay : 0;
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $department = ToolsDepartment::pluck('name', 'id')->prepend('All Departments', '');

        $show = 'data';
        // dd($statements);
        return view('admin.salarystatement.index', compact('department', 'show'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        if ($request->user_name_id != '' && $request->month != '' && $request->year != '') {
            $check = salarystatement::where(['user_name_id' => $request->user_name_id, 'month' => $request->month, 'year' => $request->year])->get();
            // dd($check);
            $DOJ = null;
            if ($request->doj != '') {
                $DOJ = date('Y-m-d', strtotime($request->doj));
            }
            // dd($DOJ);
            if (count($check) > 0) {
                $update = salarystatement::where(['user_name_id' => $request->user_name_id, 'month' => $request->month, 'year' => $request->year])->update([
                    'name' => $request->name,
                    // 'department' => $request->department,
                    // 'chequeno' => $request->chequeno,
                    'designation' => $request->designation,
                    'bankname' => $request->bankname,
                    'doj' => $DOJ,
                    'total_working_days' => $request->total_days,
                    'total_payable_days' => $request->paid_days,
                    'total_lop_days' => $request->leave,
                    'basicpay' => $request->basicpay,
                    'gross_salary' => $request->basicpay,
                    'salaryadvance' => $request->salaryadvance,
                    'epf' => $request->epf,
                    'esi' => $request->esi,
                    'lop' => $request->lop,
                    'late_amt' => $request->late_amt,
                    'otherdeduction' => $request->otherdeduction,
                    'totaldeductions' => $request->totaldeductions,
                    'netpay' => $request->netpay,
                    'updatedby' => auth()->user()->name,
                    'updated_at' => now(),
                    // 'agp' => $request->agp,
                    // 'hra' => $request->hra,
                    // 'da' => $request->da,
                    // 'conveyance' => $request->conveyance,
                    // 'specialpay' => $request->specialpay,
                    // 'arrears' => $request->arrears,
                    // 'otherall' => $request->otherAllowence,
                    // 'abi' => $request->abi,
                    // 'earnings' => $request->earnings,
                    // 'phdallowance' => $request->phdallowance,
                    // 'it' => $request->it,
                    // 'pt' => $request->pt,
                ]);
            } else {
                // dd($request);
                // $insert = new salarystatement;
                // $insert->month = $request->month;
                // $insert->year = $request->year;
                // $insert->user_name_id = $request->user_name_id;
                // $insert->name = $request->name;
                // $insert->department = $request->department;
                // $insert->designation = $request->designation;
                // $insert->doj = $DOJ;
                // $insert->total_working_days = $request->total_days;
                // $insert->total_payable_days = $request->paid_days;
                // $insert->total_lop_days = $request->leave;
                // $insert->chequeno = $request->chequeno;
                // $insert->bankname = $request->bankname;
                // $insert->basicpay = $request->basicpay;
                // $insert->agp = $request->agp;
                // $insert->hra = $request->hra;
                // $insert->da = $request->da;
                // $insert->conveyance = $request->conveyance;
                // $insert->specialpay = $request->specialpay;
                // $insert->gross_salary = $request->gross_salary;
                // $insert->arrears = $request->arrears;
                // $insert->otherall = $request->otherAllowence;
                // $insert->abi = $request->abi;
                // $insert->earnings = $request->earnings;
                // $insert->phdallowance = $request->phdallowance;
                // $insert->it = $request->it;
                // $insert->pt = $request->pt;
                // $insert->salaryadvance = $request->salaryadvance;
                // $insert->epf = $request->epf;
                // $insert->esi = $request->esi;
                // $insert->lop = $request->lop;
                // $insert->otherdeduction = $request->otherdeduction;
                // $insert->totaldeductions = $request->totaldeductions;
                // $insert->netpay = $request->netpay;
                // $insert->updatedby = auth()->user()->name;
                // $insert->created_at = now();
                // $insert->save();

                $create = salarystatement::create([
                    'name' => $request->name,
                    'user_name_id' => $request->user_name_id,
                    'designation' => $request->designation,
                    'bankname' => $request->bankname,
                    'month' => $request->month,
                    'year' => $request->year,
                    'doj' => $DOJ,
                    'total_working_days' => $request->total_days,
                    'total_payable_days' => $request->paid_days,
                    'total_lop_days' => $request->leave,
                    'basicpay' => $request->basicpay,
                    'gross_salary' => $request->basicpay,
                    'salaryadvance' => $request->salaryadvance,
                    'epf' => $request->epf,
                    'esi' => $request->esi,
                    'lop' => $request->lop,
                    'late_amt' => $request->late_amt,
                    'otherdeduction' => $request->otherdeduction,
                    'totaldeductions' => $request->totaldeductions,
                    'netpay' => $request->netpay,
                    'updatedby' => auth()->user()->name,
                ]);
            }
        }
        return redirect()->route('admin.employee-salary.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\salarystatement  $salarystatement
     * @return \Illuminate\Http\Response
     */
    public function show(salarystatement $salarystatement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\salarystatement  $salarystatement
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $get = salarystatement::where(['id' => $id])->first();
        if ($get != '' && $get != null) {
            $get_staff_code = Staffs::where(['user_name_id' => $get->user_name_id])->first();
            if ($get_staff_code == '') {
                $get_staff_code = NonTeachingStaff::where(['user_name_id' => $get->user_name_id])->first();
            }
            $get_month = $monthNumber = Carbon::createFromFormat('F', $get->month)->format('n');
            $get_year = $get->year;
            $salary = '';

            if ($get_staff_code->StaffCode != '' && $get_month != '' && $get_year != '') {
                $salary = $get_staff_code;

                $month = $get_month;

                $year = $get_year;

                $staff_code = $get_staff_code->StaffCode;

                $day_array = [];

                $previousMonth = Carbon::createFromDate($year, $month, 26)->subMonth();

                if ($previousMonth->month < 10) {
                    $previousmonth = '0' . $previousMonth->month;
                } else {
                    $previousmonth = $previousMonth->month;
                }
                if ($month == 01 || $month == 1) {
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
                    $bank = '';
                    $doj = '';
                }

                $staff = StaffBiometric::distinct('staff_code')->pluck('employee_name', 'staff_code');

                $leave = 0;
                $too_late = 0;
                $half_day_leave = 0;
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
            $year = Year::select('id', 'year')->get();
            return view('admin.employeeSalary.index', compact('attend_rep', 'staff', 'day_array', 'salary', 'bank', 'doj', 'half_day_leave', 'leave', 'too_late', 'year'));

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\salarystatement  $salarystatement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, salarystatement $salarystatement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\salarystatement  $salarystatement
     * @return \Illuminate\Http\Response
     */
    public function destroy(salarystatement $salarystatement)
    {
        //
    }
}
