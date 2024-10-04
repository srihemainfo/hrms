<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NonTeachingStaff;
use App\Models\salarystatement;
use App\Models\StaffBiometric;
use App\Models\TeachingStaff;
use App\Models\ToolsDepartment;
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
        $dept = ToolsDepartment::pluck('name', 'id');
        // dd($staff);
        // dd($request);
        $attend_rep = null;
        if (request()->has('staff_code') || request()->has('month') || request()->has('year')) {

            if ($request->dept != 'null') {
                $get_dept = ToolsDepartment::where(['id' => $request->dept])->first();
                if (!empty($get_dept)) {
                    $got_dept = $get_dept->name;
                } else {
                    $got_dept = 'null';
                }
            } else {
                $got_dept = 'null';
            }
            if (request()->has('staff_code') && $request->staff_code != 'null') {
                if ($got_dept != 'null') {
                    $get_staff = TeachingStaff::where(['StaffCode' => request()->input('staff_code'), 'Dept' => $got_dept])->first();
                    if (!empty($get_staff)) {
                        $user_name_id = $get_staff->user_name_id;
                    } else {
                        $get_staff = NonTeachingStaff::where(['StaffCode' => request()->input('staff_code'), 'Dept' => $got_dept])->first();
                        if (!empty($get_staff)) {
                            $user_name_id = $get_staff->user_name_id;
                        } else {
                            $user_name_id = 'error';
                        }
                    }
                    // dd($user_name_id);
                } else {
                    $get_staff = TeachingStaff::where(['StaffCode' => request()->input('staff_code')])->first();
                    if (!empty($get_staff)) {
                        $user_name_id = $get_staff->user_name_id;
                    } else {
                        $get_staff = NonTeachingStaff::where(['StaffCode' => request()->input('staff_code')])->first();
                        if (!empty($get_staff)) {
                            $user_name_id = $get_staff->user_name_id;
                        } else {
                            $user_name_id = 'null';
                        }
                    }
                }

            } else {
                $user_name_id = 'null';
            }
            $month = request()->input('month');
            $year = request()->input('year');
            // dd($got_dept, $month, $user_name_id);
            if ($user_name_id == 'error') {
                $data = [];
            } elseif ($got_dept == 'null' && $user_name_id == 'null' && $month == 'null') {
                $data = DB::table('payslip')->where(['year' => $year])->get();
            } elseif ($got_dept != 'null' && $user_name_id == 'null' && $month == 'null') {
                $tech_staff = TeachingStaff::where(['Dept' => $got_dept])->get();
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
                } else {
                    $non_tech_staff = NonTeachingStaff::where(['Dept' => $got_dept])->get();
                    foreach ($non_tech_staff as $staff) {
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
            } elseif ($got_dept == 'null' && $user_name_id != 'null' && $month == 'null') {
                $data = DB::table('payslip')->select('*')->where(['user_name_id' => $user_name_id, 'year' => $year])->get();
            } elseif ($got_dept == 'null' && $user_name_id == 'null' && $month != 'null') {
                $data = DB::table('payslip')->select('*')->where(['month' => $month, 'year' => $year])->get();
            } elseif ($got_dept != 'null' && $user_name_id != 'null' && $month == 'null') {

                $data = DB::table('payslip')->where(['user_name_id' => $user_name_id, 'year' => $year])->get();

            } elseif ($got_dept == 'null' && $user_name_id != 'null' && $month != 'null') {
                $data = DB::table('payslip')->select('*')->where(['user_name_id' => $user_name_id, 'month' => $month, 'year' => $year])->get();
            } elseif ($got_dept != 'null' && $user_name_id == 'null' && $month != 'null') {
                $tech_staff = TeachingStaff::where(['Dept' => $got_dept])->get();
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
                } else {
                    $non_tech_staff = NonTeachingStaff::where(['Dept' => $got_dept])->get();

                    foreach ($non_tech_staff as $staff) {
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
            } elseif ($got_dept != 'null' && $user_name_id != 'null' && $month != 'null') {
                $tech_staff = TeachingStaff::where(['Dept' => $got_dept])->get();
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
                } else {
                    $non_tech_staff = NonTeachingStaff::where(['Dept' => $got_dept])->get();

                    foreach ($non_tech_staff as $staff) {
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
                    $get_staff = TeachingStaff::where(['user_name_id' => $row->user_name_id])->select('StaffCode')->first();
                    if ($get_staff != '') {
                        return $get_staff->StaffCode;
                    } else {
                        $get_staff = NonTeachingStaff::where(['user_name_id' => $row->user_name_id])->select('StaffCode')->first();
                        return $get_staff->StaffCode;
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

        $tech_staff_details = TeachingStaff::get();
        $non_tech_staff_details = NonTeachingStaff::get();

        if ($tech_staff_details->count() > 0) {

            foreach ($tech_staff_details as $data) {

                if ($data->Dept == 'CSE') {
                    array_push($cse, [$data->StaffCode => $data->name]);
                } else if ($data->Dept == 'ECE') {
                    array_push($ece, [$data->StaffCode => $data->name]);
                } else if ($data->Dept == 'MECH') {
                    array_push($mech, [$data->StaffCode => $data->name]);
                } else if ($data->Dept == 'AI & DA') {
                    array_push($ai, [$data->StaffCode => $data->name]);
                } else if ($data->Dept == 'S & H') {
                    array_push($sh, [$data->StaffCode => $data->name]);
                } else if ($data->Dept == 'CCE') {
                    array_push($cce, [$data->StaffCode => $data->name]);
                } else if ($data->Dept == 'CSBS') {
                    array_push($csbs, [$data->StaffCode => $data->name]);
                } else if ($data->Dept == 'AI & ML') {
                    array_push($aiml, [$data->StaffCode => $data->name]);
                }

            }

        }

        if ($non_tech_staff_details->count() > 0) {

            foreach ($non_tech_staff_details as $data) {

                if ($data->Dept == 'ADMIN') {
                    array_push($admin, [$data->StaffCode => $data->name]);
                } else if ($data->Dept == 'CIVIL') {
                    array_push($civil, [$data->StaffCode => $data->name]);
                }

            }

        }

        return view('admin.paySlip.index', compact('data', 'staff', 'dept', 'cse', 'mech', 'ece', 'sh', 'ai', 'cce', 'csbs', 'aiml', 'admin', 'civil'));

    }

    public function slip(Request $request)
    {

        return view('admin.paySlip.paySlipindex');
    }

    public function edit($id)
    {
        $results = DB::table('payslip')->where(['id' => $id])->first();
        if (!empty($results)) {
            $staff = TeachingStaff::where(['user_name_id' => $results->user_name_id])->select('StaffCode')->first();
            if ($staff == '') {
                $staff = NonTeachingStaff::where(['user_name_id' => $results->user_name_id])->select('StaffCode')->first();
                $results->StaffCode = $staff->StaffCode;
            } else {
                $results->StaffCode = $staff->StaffCode;
            }
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
        $results = DB::table('payslip')->where(['id' => $id])->get();
        if (count($results) > 0) {
            $staff = TeachingStaff::where(['user_name_id' => $results[0]->user_name_id])->select('StaffCode')->first();
            if ($staff == '') {
                $staff = NonTeachingStaff::where(['user_name_id' => $results[0]->user_name_id])->select('StaffCode')->first();
            }
            // exit;
            $results[0]->StaffCode = $staff->StaffCode;
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
            // $salarystatement->it = $teachingStaff->TotalSalary;
            // $salarystatement->pt =$teachingStaff->TotalSalary;
            // $salarystatement->salaryadvance = $teachingStaff->TotalSalary;
            // $salarystatement->epf = $teachingStaff->TotalSalary;
            //ESI
            // $salarystatement->llp = $monthName;
            // $salarystatement->otherdeduction = $teachingStaff->TotalSalary;
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
                // $salarystatement->it = $teachingStaff->TotalSalary;
                // $salarystatement->pt =$teachingStaff->TotalSalary;
                'it' => $request->it == '' ? 0 : $request->it,
                'pt' => $request->pt == '' ? 0 : $request->pt,
                // $salarystatement->salaryadvance = $teachingStaff->TotalSalary;
                'salaryadvance' => $request->salaryadvance == '' ? 0 : $request->salaryadvance,
                // $salarystatement->epf = $teachingStaff->TotalSalary;
                //ESI
                // $salarystatement->llp = $monthName;
                'epf' => $request->epf == '' ? 0 : $request->epf,
                'llp' => $request->llp == '' ? 0 : $request->llp,
                // $salarystatement->otherdeduction = $teachingStaff->TotalSalary;
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
        if (isset($request->dept) && isset($request->staff_code) && isset($request->month) && isset($request->year)) {

            $dept = $request->dept;
            $staff_code = $request->staff_code;
            $month = $request->month;
            $year = $request->year;

            if ($dept != 'null') {
                $get_dept = ToolsDepartment::where(['id' => $dept])->first();
                if (!empty($get_dept)) {
                    $got_dept = $get_dept->name;
                } else {
                    $got_dept = 'null';
                }
            } else {
                $got_dept = 'null';
            }
            if ($staff_code != 'null') {
                if ($got_dept != 'null') {
                    $get_staff = TeachingStaff::where(['StaffCode' => $staff_code, 'Dept' => $got_dept])->first();
                    if (!empty($get_staff)) {
                        $user_name_id = $get_staff->user_name_id;
                    } else {
                        $get_staff = NonTeachingStaff::where(['StaffCode' => $staff_code, 'Dept' => $got_dept])->first();
                        if ($get_staff != '') {
                            $user_name_id = $get_staff->user_name_id;
                        } else {
                            $user_name_id = 'error';
                        }
                    }
                    // dd($user_name_id);
                } else {
                    $get_staff = TeachingStaff::where(['StaffCode' => $staff_code])->first();
                    if (!empty($get_staff)) {
                        $user_name_id = $get_staff->user_name_id;
                    } else {
                        $get_staff = NonTeachingStaff::where(['StaffCode' => $staff_code])->first();
                        if (!empty($get_staff)) {
                            $user_name_id = $get_staff->user_name_id;
                        } else {
                            $user_name_id = 'null';
                        }
                    }
                }

            } else {
                $user_name_id = 'null';
            }

            if ($user_name_id == 'error') {
                $data = [];
            } elseif ($got_dept == 'null' && $user_name_id == 'null' && $month == 'null') {
                $data = DB::table('payslip')->where(['year' => $year])->get();
            } elseif ($got_dept != 'null' && $user_name_id == 'null' && $month == 'null') {
                $tech_staff = TeachingStaff::where(['Dept' => $got_dept])->get();
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
                } else {
                    $non_tech_staff = NonTeachingStaff::where(['Dept' => $got_dept])->get();

                    foreach ($non_tech_staff as $staff) {
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
            } elseif ($got_dept == 'null' && $user_name_id != 'null' && $month == 'null') {
                $data = DB::table('payslip')->select('*')->where(['user_name_id' => $user_name_id, 'year' => $year])->get();
            } elseif ($got_dept == 'null' && $user_name_id == 'null' && $month != 'null') {
                $data = DB::table('payslip')->select('*')->where(['month' => $month, 'year' => $year])->get();
            } elseif ($got_dept != 'null' && $user_name_id != 'null' && $month == 'null') {

                $data = DB::table('payslip')->where(['user_name_id' => $user_name_id, 'year' => $year])->get();

            } elseif ($got_dept == 'null' && $user_name_id != 'null' && $month != 'null') {
                $data = DB::table('payslip')->select('*')->where(['user_name_id' => $user_name_id, 'month' => $month, 'year' => $year])->get();
            } elseif ($got_dept != 'null' && $user_name_id == 'null' && $month != 'null') {
                $tech_staff = TeachingStaff::where(['Dept' => $got_dept])->get();
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
                } else {
                    $non_tech_staff = NonTeachingStaff::where(['Dept' => $got_dept])->get();
                    foreach ($non_tech_staff as $staff) {
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
            } elseif ($got_dept != 'null' && $user_name_id != 'null' && $month != 'null') {
                $tech_staff = TeachingStaff::where(['Dept' => $got_dept])->get();
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
                } else {
                    $non_tech_staff = NonTeachingStaff::where(['Dept' => $got_dept])->get();
                    foreach ($non_tech_staff as $staff) {
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
                    $get_code = TeachingStaff::where(['user_name_id' => $details->user_name_id])->first();
                    if (!empty($get_code)) {
                        $details->StaffCode = $get_code->StaffCode;
                    } else {
                        $get_code = NonTeachingStaff::where(['user_name_id' => $details->user_name_id])->first();
                        if (!empty($get_code)) {
                            $details->StaffCode = $get_code->StaffCode;
                        } else {
                            $details->StaffCode = null;
                        }
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
