<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\StaffSalary;
use Illuminate\Http\Request;
use App\Models\TeachingStaff;
use App\Models\NonTeachingStaff;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffSalaryRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\UpdateStaffSalaryRequest;
use App\Http\Requests\MassDestroyStaffSalaryRequest;

class StaffSalaryController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('staff_salary_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staffSalaries = StaffSalary::all();

        return view('admin.staffSalaries.index', compact('staffSalaries'));
    }

    public function staff_index(Request $request)
    {

        abort_if(Gate::denies('staff_salary_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request);
        if (!$request->updater) {

            $query = StaffSalary::where(['user_name_id' => $request->user_name_id])->get();

            // $salary = TeachingStaff::where(['user_name_id' => $request->user_name_id])->first();

            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->TotalSalary = '';
                $query->basic_pay = '';
                $query->phd_allowance = '';
                $query->agp = '';
                $query->hra = '';
                $query->da = '';
                $query->special_pay = '';
                $query->other_allowances = '';
                $query->add = 'Add';

                $staff = $query;
                $staff_edit = $query;
                $list = [];

            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                $staff = $query[0];

                $list = $query;

                $staff_edit = new StaffSalary;
                $staff_edit->add = 'Add';
                $staff_edit->id = '';
                $staff_edit->TotalSalary = '';
                $staff_edit->basic_pay = '';
                $staff_edit->phd_allowance = '';
                $staff_edit->agp = '';
                $staff_edit->hra = '';
                $staff_edit->da = '';
                $staff_edit->special_pay = '';
                $staff_edit->other_allowances = '';

            }

        } else {

            // dd($request);

            $query_one = StaffSalary::where(['user_name_id' => $request->user_name_id])->get();

            $query_two = StaffSalary::where(['id' => $request->id])->get();

            if (!$query_two->count() <= 0) {

                $query_one[0]['user_name_id'] = $request->user_name_id;

                $query_one[0]['name'] = $request->name;

                $query_two[0]['add'] = 'Update';

                $staff = $query_one[0];

                $list = $query_one;
                // dd($query_two);

                $staff_edit = $query_two[0];
            } else {
                dd('Error');
            }
        }

        $get_salary_detail = StaffSalary::where('user_name_id', $request->user_name_id)->get();
        // dd($get_salary_detail);
        if (!$get_salary_detail->count() <= 0) {


            $Basic_pay = 0;
            $Agp = 0;
            $Da = 0;
            $Hra = 0;
            $Hra_amount = 0;
            $Special_pay = 0;
            $Other_allowances = 0;
            $Phd_allowance = 0;

            for ($i = 0; $i < count($get_salary_detail); $i++) {

                $Basic_pay += (int) $get_salary_detail[$i]->basic_pay;
                $Agp += (int) $get_salary_detail[$i]->agp;
                $Da += (int) $get_salary_detail[$i]->da;
                $Hra += (int) $get_salary_detail[$i]->hra;
                $Hra_amount += (int) $get_salary_detail[$i]->hra_amount;
                $Special_pay += (int) $get_salary_detail[$i]->special_pay;
                $Other_allowances += (int) $get_salary_detail[$i]->other_allowances;
                $Phd_allowance += (int) $get_salary_detail[$i]->phd_allowance;


            }
            $TotalSalary = $Basic_pay + $Agp + $Special_pay + $Other_allowances + $Phd_allowance + $Da + $Hra_amount;
            // dd($Basic_pay);
            $salary = TeachingStaff::where('user_name_id', $request->user_name_id)
                ->update([
                    'TotalSalary' => $TotalSalary,
                    'basicPay' => $Basic_pay,
                    'agp' => $Agp,
                    'specialFee' => $Special_pay,
                    'phdAllowance' => $Phd_allowance,
                    'otherAllowence' => $Other_allowances,
                    'da' => $Da,
                    'hra' => $Hra,
                    'hra_amount' => $Hra_amount,
                ]);
        } else {

            $salary = TeachingStaff::where('user_name_id', $request->user_name_id)
                ->update([
                    'TotalSalary' => 0,
                    'basicPay' => 0,
                    'agp' => 0,
                    'specialFee' => 0,
                    'phdAllowance' => 0,
                    'otherAllowence' => 0,
                    'da' => 0,
                    'hra' => 0,
                    'hra_amount' => 0,
                ]);
        }

        $check = 'staff-salery';
        $check_staff_1 = TeachingStaff::where(['user_name_id' => $request->user_name_id])->get();

        if (count($check_staff_1) > 0) {
            return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit'));
        } else {
            $check_staff_2 = NonTeachingStaff::where(['user_name_id' => $request->user_name_id])->get();

            if (count($check_staff_2) > 0) {
                return view('admin.StaffProfile(non_tech).staff', compact('staff', 'check', 'list', 'staff_edit'));
            }
        }

    }

    public function staff_update(UpdateStaffSalaryRequest $request, StaffSalary $staffSalary)
    {
        // dd($request);
        if ($request) {
            $totalWorkingDays = 31;
            $losOfpay = 0;
            $nodaysPayable = 31;
            $basicPay = $request->basic_pay;
            $agp = $request->agp;
            $da = ($basicPay + $agp) * (55 / 100);
            $hra = $request->hra;
            // dd($agp,$da,$request->hra,$request);
            $hra_amount = ($agp + $da) * ($request->hra/100);
            $arrears = 0;
            $specialPay = $request->special_pay == '' ? 0 : $request->special_pay;
            $otherAllowence = $request->other_allowances;
            // dd($specialPay);
            // $appricalBasedincrement = $request->appraisal_based_increment == '' ? 0 : $request->appraisal_based_increment;
            $phdAllowance = $request->phd_allowance == '' ? 0 : $request->phd_allowance;
            $salaryAdvance = 0;
            $it = 0;
            $pt = 0;
            $esi = 0;
            $epf = 0;
            $otherDeduction = 0;
            $grossSalary = $basicPay + $agp + $specialPay + $otherAllowence + $arrears + $phdAllowance + (int)$da + $hra_amount;
            $totalDeduction = 0;
            $netSalary = $grossSalary;

            if ($request->submit == 'Add') {
                $staffSalary = new StaffSalary;
                $staffSalary->user_name_id = $request->user_name_id;
                $staffSalary->basic_pay = $request->basic_pay;
                $staffSalary->agp = $request->agp;
                $staffSalary->hra = $request->hra;
                $staffSalary->hra_amount = $hra_amount;
                $staffSalary->da = (int)$da;
                $staffSalary->special_pay = $request->special_pay;
                $staffSalary->other_allowances = $request->other_allowances;
                $staffSalary->phd_allowance = $request->phd_allowance;
                $staffSalary->gross_salary = $grossSalary;
                $staffSalary->net_salary = $grossSalary;
                $staffSalary->save();
            } elseif ($request->submit == 'Update') {
                $get_salary_detail = StaffSalary::where(['user_name_id' => $request->user_name_id, 'id' => $request->id])->update([
                    'basic_pay' => $request->basic_pay,
                    'agp' => $request->agp,
                    'hra' => $request->hra,
                    'hra_amount' =>$hra_amount,
                    'da' => (int)$da,
                    'special_pay' => $request->special_pay,
                    'other_allowances' => $request->other_allowances,
                    'phd_allowance' => $request->phd_allowance,
                    'gross_salary' => $grossSalary,
                    'net_salary' => $grossSalary,
                ]);
                
            }
            $get_salary_detail = StaffSalary::where('user_name_id', $request->user_name_id)->get();

            if (!$get_salary_detail->count() <= 0) {


                $Basic_pay = 0;
                $Agp = 0;
                $Da = 0;
                $Hra = 0;
                $Hra_amount = 0;
                $Special_pay = 0;
                $Other_allowances = 0;
                $Phd_allowance = 0;

                for ($i = 0; $i < count($get_salary_detail); $i++) {

                    $Basic_pay += (int) $get_salary_detail[$i]->basic_pay;
                    $Agp += (int) $get_salary_detail[$i]->agp;
                    $Da += (int) $get_salary_detail[$i]->da;
                    $Hra += (int) $get_salary_detail[$i]->hra;
                    $Hra_amount += (int) $get_salary_detail[$i]->hra_amount;
                    $Special_pay += (int) $get_salary_detail[$i]->special_pay;
                    $Other_allowances += (int) $get_salary_detail[$i]->other_allowances;
                    $Phd_allowance += (int) $get_salary_detail[$i]->phd_allowance;


                }
                // $Da += (int) ($Basic_pay + $Agp) * (55 / 100);
                $TotalSalary = $Basic_pay + $Agp + $Hra_amount + $Special_pay + $Other_allowances + $Phd_allowance + $Da;
                // dd($Basic_pay,$Agp,$Da);
                $salary = TeachingStaff::where('user_name_id', $request->user_name_id)
                    ->update([
                        'TotalSalary' => $TotalSalary,
                        'basicPay' => $Basic_pay,
                        'agp' => $Agp,
                        'da' => $Da,
                        'specialFee' => $Special_pay,
                        'phdAllowance' => $Phd_allowance,
                        'otherAllowence' => $Other_allowances,
                        'hra_amount' => $Hra_amount,
                        'hra' => $Hra,
                    ]);

            }

        } else {

            // dd($request);
        }
        $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        return redirect()->route('admin.staff-salaries.staff_index', $staff);
    }

    public function create()
    {
        abort_if(Gate::denies('staff_salary_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.staffSalaries.create');
    }

    public function store(StoreStaffSalaryRequest $request)
    {
        $staffSalary = StaffSalary::create($request->all());

        return redirect()->route('admin.staff-salaries.index');
    }

    public function edit(StaffSalary $staffSalary)
    {
        abort_if(Gate::denies('staff_salary_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.staffSalaries.edit', compact('staffSalary'));
    }

    public function update(UpdateStaffSalaryRequest $request, StaffSalary $staffSalary)
    {
        $staffSalary->update($request->all());

        return redirect()->route('admin.staff-salaries.index');
    }

    public function show(StaffSalary $staffSalary)
    {
        abort_if(Gate::denies('staff_salary_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.staffSalaries.show', compact('staffSalary'));
    }

    public function destroy(StaffSalary $staffSalary)
    {
        abort_if(Gate::denies('staff_salary_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staffSalary->delete();

        return back();
    }

    public function massDestroy(MassDestroyStaffSalaryRequest $request)
    {
        $staffSalaries = StaffSalary::find(request('ids'));

        foreach ($staffSalaries as $staffSalary) {
            $staffSalary->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
