<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\salarystatement;
use App\Models\StaffBiometric;
use App\Models\Staffs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class paySlipcontroller extends Controller
{

    public function index(Request $request)
    {

        $staff = StaffBiometric::distinct('staff_code')->pluck('employee_name', 'staff_code');
        // $dept = ToolsDepartment::pluck('name', 'id');
        // dd($staff);
        // dd($request);
        $attend_rep = null;
        if (request()->has('staff_code') || request()->has('month') || request()->has('year')) {

            // if ($request->dept != 'null') {
            //     $get_dept = ToolsDepartment::where(['id' => $request->dept])->first();
            //     if (!empty($get_dept)) {
            //         $got_dept = $get_dept->name;
            //     } else {
            //         $got_dept = 'null';
            //     }
            // } else {
            //     $got_dept = 'null';
            // }
            if (request()->has('staff_code') && $request->staff_code != 'null') {
                $get_staff = Staffs::where(['employee_id' => request()->input('staff_code')])->first();
                if (!empty($get_staff)) {
                    $user_name_id = $get_staff->user_name_id;
                }

            } else {
                $user_name_id = 'null';
            }
            $month = request()->input('month');
            $year = request()->input('year');
            // dd($got_dept, $month, $user_name_id);
            if ($user_name_id == 'error') {
                $data = [];
            } elseif ($user_name_id == 'null' && $month == 'null') {
                $data = DB::table('payslip')->where(['year' => $year])->get();
            } elseif ($user_name_id == 'null' && $month == 'null') {
                $tech_staff = Staffs::get();
                $data = [];
                if (count($tech_staff) > 0) {
                    foreach ($tech_staff as $staff) {
                        $get_payslip = DB::table('payslip')->where(['user_name_id' => $staff->user_name_id, 'year' => $year])->get();
                        if (count($get_payslip) > 0) {
                            foreach ($get_payslip as $slip) {
                                if ($staff->user_name_id == $slip->user_name_id) {
                                    array_push($data, $slip);
                                }
                            }
                        }
                    }
                }
                // $data = DB::table('payslip')->select('*');
            } elseif ($user_name_id != 'null' && $month == 'null') {
                $data = DB::table('payslip')->select('*')->where(['user_name_id' => $user_name_id, 'year' => $year])->get();
            } elseif ($user_name_id == 'null' && $month != 'null') {
                $data = DB::table('payslip')->select('*')->where(['month' => $month, 'year' => $year])->get();
            } elseif ($user_name_id != 'null' && $month == 'null') {

                $data = DB::table('payslip')->where(['user_name_id' => $user_name_id, 'year' => $year])->get();

            } elseif ($user_name_id != 'null' && $month != 'null') {
                $data = DB::table('payslip')->select('*')->where(['user_name_id' => $user_name_id, 'month' => $month, 'year' => $year])->get();
            } elseif ($user_name_id == 'null' && $month != 'null') {
                $tech_staff = Staffs::get();
                $data = [];
                if (count($tech_staff) > 0) {
                    foreach ($tech_staff as $staff) {
                        $get_payslip = DB::table('payslip')->where(['month' => $month, 'year' => $year])->get();
                        if (count($get_payslip) > 0) {
                            foreach ($get_payslip as $slip) {
                                if ($staff->user_name_id == $slip->user_name_id) {
                                    array_push($data, $slip);
                                }
                            }
                        }
                    }
                }
            } elseif ($user_name_id != 'null' && $month != 'null') {
                $tech_staff = Staffs::get();
                $data = [];
                if (count($tech_staff) > 0) {
                    foreach ($tech_staff as $staff) {
                        $get_payslip = DB::table('payslip')->where(['user_name_id' => $user_name_id, 'month' => $month, 'year' => $year])->get();
                        if (count($get_payslip) > 0) {
                            foreach ($get_payslip as $slip) {
                                if ($staff->user_name_id == $slip->user_name_id) {
                                    array_push($data, $slip);
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $data = DB::table('payslip')->get();
        }
        // dd($data);
        // $data = DB::table('payslip')->select('*');
        if (request()->ajax()) {
            $dataTable = datatables()->of($data);

            $dataTable->addColumn('placeholder', '&nbsp;');
            $dataTable->addColumn('actions', function ($row) {
                $editLink = '<a href="' . url('admin/PaySlip/edit/' . $row->id) . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i>Preview</a>';
                // $previewLink = '<a href="/admin/PaySlip/preview/'.$user->id.'" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-eye-open"></i> Edit</a>';
                return $editLink;
            });

            $dataTable->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });

            $dataTable->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $dataTable->editColumn('staff_code', function ($row) {
                if (isset($row->user_name_id)) {
                    $get_staff = Staffs::where(['user_name_id' => $row->user_name_id])->select('employee_id')->first();
                    if ($get_staff != '') {
                        return $get_staff->employee_id;
                    }
                }
                return '';
            });

            $dataTable->editColumn('month', function ($row) {
                return $row->month ? $row->month : '';
            });
            $dataTable->editColumn('netpay', function ($row) {
                return $row->netpay ? $row->netpay : '';
            });
            $dataTable->rawColumns(['actions', 'placeholder']);

            return $dataTable->make(true);

        }
        $cse = [];
        $ece = [];
        $mech = [];
        $sh = [];
        $ai = [];
        $cce = [];
        $csbs = [];
        $aiml = [];
        $admin = [];
        $civil = [];

        $tech_staff_details = Staffs::get();
        // $non_tech_staff_details = NonTeachingStaff::get();

        // if ($tech_staff_details->count() > 0) {

        //     foreach ($tech_staff_details as $data) {

        //         if ($data->Dept == 'CSE') {
        //             array_push($cse, [$data->employee_id => $data->name]);
        //         } else if ($data->Dept == 'ECE') {
        //             array_push($ece, [$data->employee_id => $data->name]);
        //         } else if ($data->Dept == 'MECH') {
        //             array_push($mech, [$data->employee_id => $data->name]);
        //         } else if ($data->Dept == 'AI & DA') {
        //             array_push($ai, [$data->employee_id => $data->name]);
        //         } else if ($data->Dept == 'S & H') {
        //             array_push($sh, [$data->employee_id => $data->name]);
        //         } else if ($data->Dept == 'CCE') {
        //             array_push($cce, [$data->employee_id => $data->name]);
        //         } else if ($data->Dept == 'CSBS') {
        //             array_push($csbs, [$data->employee_id => $data->name]);
        //         } else if ($data->Dept == 'AI & ML') {
        //             array_push($aiml, [$data->employee_id => $data->name]);
        //         }
        //     }

        // }

        return view('admin.paySlip.index', compact('data', 'staff', 'cse', 'mech', 'ece', 'sh', 'ai', 'cce', 'csbs', 'aiml', 'admin', 'civil'));

    }

    public function slip(Request $request)
    {

        return view('admin.paySlip.paySlipindex');
    }

    public function edit($id)
    {
        $results = DB::table('payslip')->where(['id' => $id])->first();
        if (!empty($results)) {
            $staff = Staffs::where(['user_name_id' => $results->user_name_id])->first();
            // dd($results);
            $results->employee_id = $staff->employee_id;
        }
        return view('admin.paySlip.paySlipindex', compact('results'));
    }
    public function update(Request $request, $id)
    {

        $results = DB::table('payslip')->where('user_name_id', $request->id)->get();
        $data = DB::table('staff_biometrics')->where('user_name_id', $request->id)->get();

        $data = $request->except('_token', '_method', 'id');
        DB::table('payslip')
            ->where('id', $id)
            ->update($data);

        //  $datas = [
        //     'results' =>$results,
        //     'data' => $data
        // ];

        // $pdf = PDF::loadView('admin.paySlip.pdf', $datas);

        // return $pdf->stream('itsolutionstuff.pdf');

        return redirect()->route('admin.PaySlip.index');
    }
    public function pdf($id)
    {
        // dd($id);
        $results = DB::table('payslip')
            ->where('payslip.id', $id)
            ->leftJoin('salarystatements', function ($join) {
                $join->on('salarystatements.user_name_id', '=', 'payslip.user_name_id')
                    ->where('salarystatements.month', '=', DB::raw('payslip.month'))
                    ->where('salarystatements.year', '=', DB::raw('payslip.year'));
            })
            ->leftJoin('staffs', 'staffs.user_name_id', 'payslip.user_name_id')
            ->leftJoin('designation', 'staffs.designation_id', 'designation.id')
            ->select('payslip.*', 'salarystatements.total_lop_days', 'salarystatements.total_working_days', 'salarystatements.total_payable_days', 'staffs.DOJ', 'designation.name as designation')
            ->get();

        // dd($results);
        if (count($results) > 0) {
            $staff = Staffs::where(['user_name_id' => $results[0]->user_name_id])->select('employee_id')->first();

            // exit;
            $results[0]->employee_id = $staff->employee_id;
            $final_data = ['data' => $results];
            // dd($results);
            $pdf = PDF::loadView('admin.paySlip.pdf', $final_data);

            // $pdf->setPaper('A4', 'landscape');

            return $pdf->stream('payslip.pdf');
        } else {
            return back();
        }
    }

    public function store(Request $request)
    {
        $date = Carbon::now()->format('Y-m-d');
        // dd($request,$time);
        $search = DB::table('payslip')->where(['month' => $request->month, 'year' => $request->year, 'user_name_id' => $request->user_name_id])->get();

        if ($search->count() <= 0) {
            // dd('in');
            $insert = DB::table('payslip')->insert([
                'month' => $request->month,
                'year' => $request->year,
                'basicpay' => $request->basicpay == '' ? 0 : $request->basicpay,
                'agp' => $request->agp == '' ? 0 : $request->agp,
                'da' => $request->da == '' ? 0 : $request->da,
                'conveyance' => 0,
                'specialpay' => $request->specialpay == '' ? 0 : $request->specialpay,
                'arrears' => 0,
                'otherall' => 0,
                'abi' => 0,
                'earnings' => $request->gross_salary == '' ? 0 : $request->gross_salary,
                'phdallowance' => $request->phdallowance == '' ? 0 : $request->phdallowance,
                'name' => $request->name,
                'department' => $request->department,
                'designation' => $request->designation,
                'bankname' => $request->bankname,
                'lic' => 0,
                'it' => $request->it == '' ? 0 : $request->it,
                'pt' => $request->pt == '' ? 0 : $request->pt,
                'salaryadvance' => 0,
                'epf' => $request->epf == '' ? 0 : $request->epf,
                'llp' => $request->llp == '' ? 0 : $request->llp,
                'otherdeduction' => 0,
                'totaldeductions' => 0,
                'netpay' => $request->netpay == '' ? 0 : $request->netpay,
                'date' => $date,
                'updatedby' => $request->updatedby,
                'created_at' => now(),
                'user_name_id' => $request->user_name_id,
            ]);

            $salarystatement = new salarystatement;
            $salarystatement->basicpay = $request->basicpay == '' ? 0 : $request->basicpay;
            $salarystatement->month = $request->month == '' ? 0 : $request->month;
            $salarystatement->year = $request->year;
            $salarystatement->agp = $request->agp == '' ? 0 : $request->agp;
            $salarystatement->da = $request->da == '' ? 0 : $request->da;
            // $salarystatement->hra = $hra == '' ? 0 : $hra;
            $salarystatement->specialpay = $request->specialpay == '' ? 0 : $request->specialpay;
            // $salarystatement->arrears = $monthName;
            // $salarystatement->otherall = $otherall == '' ? 0 : $otherall;
            // $salarystatement->abi = $monthName;
            $salarystatement->phdallowance = $request->phdallowance == '' ? 0 : $request->phdallowance;
            // $salarystatement->grosssalary = $gross == '' ? 0 : $gross;
            // $salarystatement->it = $Staffs->TotalSalary;
            // $salarystatement->pt =$Staffs->TotalSalary;
            // $salarystatement->salaryadvance = $Staffs->TotalSalary;
            // $salarystatement->epf = $Staffs->TotalSalary;
            //ESI
            // $salarystatement->llp = $monthName;
            // $salarystatement->otherdeduction = $Staffs->TotalSalary;
            // $salarystatement->totaldeductions = $deduction == '' ? 0 : $deduction;
            $salarystatement->netpay = $request->netpay == '' ? 0 : $request->netpay;
            $salarystatement->user_name_id = $request->user_name_id;
            $salarystatement->save();

        } else {
            // dd('up');
            DB::table('payslip')->where(['month' => $request->month, 'year' => $request->year, 'user_name_id' => $request->user_name_id])->update([

                'basicpay' => $request->basicpay == '' ? 0 : $request->basicpay,
                'agp' => $request->agp == '' ? 0 : $request->agp,
                'da' => $request->da == '' ? 0 : $request->da,
                'conveyance' => 0,
                'specialpay' => $request->specialpay == '' ? 0 : $request->specialpay,
                'arrears' => 0,
                'otherall' => 0,
                'abi' => 0,
                'earnings' => $request->gross_salary == '' ? 0 : $request->gross_salary,
                'phdallowance' => $request->phdallowance == '' ? 0 : $request->phdallowance,
                'name' => $request->name,
                'department' => $request->department,
                'designation' => $request->designation,
                'bankname' => $request->bankname,
                'lic' => 0,
                'it' => $request->it == '' ? 0 : $request->it,
                'pt' => $request->pt == '' ? 0 : $request->pt,
                'salaryadvance' => $request->salaryadvance == '' ? 0 : $request->salaryadvance,
                'epf' => $request->epf == '' ? 0 : $request->epf,
                'llp' => $request->llp == '' ? 0 : $request->llp,
                'otherdeduction' => 0,
                'totaldeductions' => $request->totaldeductions == '' ? 0 : $request->totaldeductions,
                'netpay' => $request->netpay == '' ? 0 : $request->netpay,
                'updatedby' => $request->updatedby,
                'updated_at' => now(),

            ]);

            // $salarystatement = new salarystatement;
            DB::table('salarystatements')->where(['month' => $request->month, 'year' => $request->year, 'user_name_id' => $request->user_name_id])->update([
                'basicpay' => $request->basicpay == '' ? 0 : $request->basicpay,
                'agp' => $request->agp == '' ? 0 : $request->agp,
                'da' => $request->da == '' ? 0 : $request->da,
                // $salarystatement->hra = $hra == '' ? 0 : $hra;
                'specialpay' => $request->specialpay == '' ? 0 : $request->specialpay,
                // $salarystatement->arrears = $monthName;
                // $salarystatement->otherall = $otherall == '' ? 0 : $otherall;
                // $salarystatement->abi = $monthName;
                // $salarystatement->phdallowance =  $request->phdallowance == '' ? 0 : $request->phdallowance;
                'phdallowance' => $request->phdallowance == '' ? 0 : $request->phdallowance,

                // $salarystatement->grosssalary = $gross == '' ? 0 : $gross;
                'grosssalary' => $request->gross_salary == '' ? 0 : $request->gross_salary,
                // $salarystatement->it = $Staffs->TotalSalary;
                // $salarystatement->pt =$Staffs->TotalSalary;
                'it' => $request->it == '' ? 0 : $request->it,
                'pt' => $request->pt == '' ? 0 : $request->pt,
                // $salarystatement->salaryadvance = $Staffs->TotalSalary;
                'salaryadvance' => $request->salaryadvance == '' ? 0 : $request->salaryadvance,
                // $salarystatement->epf = $Staffs->TotalSalary;
                //ESI
                // $salarystatement->llp = $monthName;
                'epf' => $request->epf == '' ? 0 : $request->epf,
                'llp' => $request->llp == '' ? 0 : $request->llp,
                // $salarystatement->otherdeduction = $Staffs->TotalSalary;
                // $salarystatement->totaldeductions = $deduction == '' ? 0 : $deduction;
                'totaldeductions' => $request->totaldeductions == '' ? 0 : $request->totaldeductions,
                // $salarystatement->netpay = $request->netpay == '' ? 0 : $request->netpay;
                'netpay' => $request->netpay == '' ? 0 : $request->netpay,
                // $salarystatement->user_name_id =$request->user_name_id,
                // $salarystatement->save(),
            ]);
        }
        $id = $request->user_name_id;
        $month = $request->month;
        $year = $request->year;
        return redirect()->route('admin.payslip.edit', compact('id', 'month', 'year'));
    }

    public function slip_generation(Request $request)
    {
        $date = Carbon::now()->format('Y-m-d');

        if (isset($request->ids)) {
            if (count($request->ids) > 0) {
                $find = $request->ids;
                foreach ($find as $data) {
                    $get_statements = salarystatement::where(['id' => $data])->first();

                    if ($get_statements != '') {

                        $check = DB::table('payslip')->where(['user_name_id' => $get_statements->user_name_id, 'month' => $get_statements->month, 'year' => $get_statements->year])->get();

                        if (count($check) > 0) {

                            $slip_generation = DB::table('payslip')->where(['user_name_id' => $get_statements->user_name_id, 'month' => $get_statements->month, 'year' => $get_statements->year])->update([
                                'name' => $get_statements->name,
                                'department' => $get_statements->department,
                                'designation' => $get_statements->designation,
                                'chequeno' => $get_statements->chequeno,
                                'bankname' => $get_statements->bankname,
                                'basicpay' => $get_statements->basicpay,
                                'agp' => $get_statements->agp,
                                'da' => $get_statements->da,
                                'conveyance' => $get_statements->conveyance,
                                'specialpay' => $get_statements->specialpay,
                                'arrears' => $get_statements->arrears,
                                'otherall' => $get_statements->otherall,
                                'abi' => $get_statements->abi,
                                'earnings' => $get_statements->earnings,
                                'phdallowance' => $get_statements->phdallowance,
                                'it' => $get_statements->it,
                                'pt' => $get_statements->pt,
                                'salaryadvance' => $get_statements->salaryadvance,
                                'epf' => $get_statements->epf,
                                'esi' => $get_statements->esi,
                                'lop' => $get_statements->lop,
                                'gross_salary' => $get_statements->gross_salary,
                                'late_amt' => $get_statements->late_amt,
                                'otherdeduction' => $get_statements->otherdeduction,
                                'totaldeductions' => $get_statements->totaldeductions,
                                'netpay' => $get_statements->netpay,
                                'date' => $date,
                                'updatedby' => auth()->user()->name,
                                'updated_at' => now(),
                            ]);

                            $response = 'Pay Slip Updated';

                        } else {

                            $slip_generation = DB::table('payslip')->insert([
                                'month' => $get_statements->month,
                                'year' => $get_statements->year,
                                'user_name_id' => $get_statements->user_name_id,
                                'name' => $get_statements->name,
                                'department' => $get_statements->department,
                                'designation' => $get_statements->designation,
                                'chequeno' => $get_statements->chequeno,
                                'bankname' => $get_statements->bankname,
                                'basicpay' => $get_statements->basicpay,
                                'agp' => $get_statements->agp,
                                'da' => $get_statements->da,
                                'conveyance' => $get_statements->conveyance,
                                'specialpay' => $get_statements->specialpay,
                                'arrears' => $get_statements->arrears,
                                'otherall' => $get_statements->otherall,
                                'abi' => $get_statements->abi,
                                'earnings' => $get_statements->earnings,
                                'phdallowance' => $get_statements->phdallowance,
                                'it' => $get_statements->it,
                                'pt' => $get_statements->pt,
                                'salaryadvance' => $get_statements->salaryadvance,
                                'epf' => $get_statements->epf,
                                'esi' => $get_statements->esi,
                                'lop' => $get_statements->lop,
                                'gross_salary' => $get_statements->gross_salary,
                                'late_amt' => $get_statements->late_amt,
                                'otherdeduction' => $get_statements->otherdeduction,
                                'totaldeductions' => $get_statements->totaldeductions,
                                'netpay' => $get_statements->netpay,
                                'date' => $date,
                                'updatedby' => auth()->user()->name,
                                'created_at' => now(),
                            ]);

                            $response = 'Pay Slip Generated';
                        }
                    } else {
                        $response = 'Statement Not Found';
                    }
                }
            } else {
                $response = 'Statement Not Found';
            }
        } else {
            $response = 'Statement Not Found';
        }
        return response()->json(['status' => $response]);
    }

    public function bulk_pdf(Request $request)
    {
        // dd($request);
        if (isset($request->staff_code) && isset($request->month) && isset($request->year)) {

            $dept = $request->dept;
            $staff_code = $request->staff_code;
            $month = $request->month;
            $year = $request->year;

            if ($staff_code != 'null') {
                $get_staff = Staffs::where(['employee_id' => $staff_code])->first();
                $user_name_id = $get_staff->user_name_id;
            } else {
                $user_name_id = 'null';
            }

            if ($user_name_id == 'error') {
                $data = [];
            } elseif ($user_name_id == 'null' && $month == 'null') {
                $data = DB::table('payslip')->where(['year' => $year])->get();
            } elseif ($user_name_id == 'null' && $month == 'null') {
                $tech_staff = Staffs::get();
                $data = [];
                if (count($tech_staff) > 0) {
                    foreach ($tech_staff as $staff) {
                        $get_payslip = DB::table('payslip')->where(['user_name_id' => $staff->user_name_id, 'year' => $year])->get();
                        if (count($get_payslip) > 0) {
                            foreach ($get_payslip as $slip) {
                                if ($staff->user_name_id == $slip->user_name_id) {
                                    array_push($data, $slip);
                                }
                            }
                        }
                    }
                }
                // $data = DB::table('payslip')->select('*');
            } elseif ($user_name_id != 'null' && $month == 'null') {
                $data = DB::table('payslip')->select('*')->where(['user_name_id' => $user_name_id, 'year' => $year])->get();
            } elseif ($user_name_id == 'null' && $month != 'null') {
                $data = DB::table('payslip')->select('*')->where(['month' => $month, 'year' => $year])->get();
            } elseif ($user_name_id != 'null' && $month == 'null') {

                $data = DB::table('payslip')->where(['user_name_id' => $user_name_id, 'year' => $year])->get();

            } elseif ($user_name_id != 'null' && $month != 'null') {
                $data = DB::table('payslip')->select('*')->where(['user_name_id' => $user_name_id, 'month' => $month, 'year' => $year])->get();
            } elseif ($user_name_id == 'null' && $month != 'null') {
                $tech_staff = Staffs::get();
                $data = [];
                if (count($tech_staff) > 0) {
                    foreach ($tech_staff as $staff) {
                        $get_payslip = DB::table('payslip')->where(['month' => $month, 'year' => $year])->get();
                        if (count($get_payslip) > 0) {
                            foreach ($get_payslip as $slip) {
                                if ($staff->user_name_id == $slip->user_name_id) {
                                    array_push($data, $slip);
                                }
                            }
                        }
                    }
                }
            } elseif ($user_name_id != 'null' && $month != 'null') {
                $tech_staff = Staffs::get();
                $data = [];
                if (count($tech_staff) > 0) {
                    foreach ($tech_staff as $staff) {
                        $get_payslip = DB::table('payslip')->where(['user_name_id' => $user_name_id, 'month' => $month, 'year' => $year])->get();
                        if (count($get_payslip) > 0) {
                            foreach ($get_payslip as $slip) {
                                if ($staff->user_name_id == $slip->user_name_id) {
                                    array_push($data, $slip);
                                }
                            }
                        }
                    }
                }
            }

            if (count($data) > 0) {
                foreach ($data as $details) {
                    $get_code = Staffs::where(['user_name_id' => $details->user_name_id])->first();
                    if (!empty($get_code)) {
                        $details->employee_id = $get_code->employee_id;
                    }
                }
                $final_data = ['data' => $data];
                // dd($data);
                $pdf = PDF::loadView('admin.paySlip.pdf', $final_data);

                $pdf->setPaper('A4');

                return $pdf->stream('payslip.pdf');
            } else {
                return view('admin.paySlip.noSlip');
            }
            // dd($data);
        }
    }
}
