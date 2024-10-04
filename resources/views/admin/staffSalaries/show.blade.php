@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} Staff Salary
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.staff-salaries.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                           ID
                        </th>
                        <td>
                            {{ $staffSalary->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Staff Code
                        </th>
                        <td>
                            {{ $staffSalary->staff_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Biometric ID
                        </th>
                        <td>
                            {{ $staffSalary->biometric }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Staff Name
                        </th>
                        <td>
                            {{ $staffSalary->staff_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Department
                        </th>
                        <td>
                            {{ $staffSalary->department }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                          Designation
                        </th>
                        <td>
                            {{ $staffSalary->designation }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Date of Joining
                        </th>
                        <td>
                            {{ $staffSalary->date_of_joining }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Total Working Days
                        </th>
                        <td>
                            {{ $staffSalary->total_working_days }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Loss of Pay
                        </th>
                        <td>
                            {{ $staffSalary->loss_of_pay }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           No of Day (Payable)
                        </th>
                        <td>
                            {{ $staffSalary->no_of_day_payable }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Actual Gross Salary
                        </th>
                        <td>
                            {{ $staffSalary->actual_gross_salary }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Basic Pay
                        </th>
                        <td>
                            {{ $staffSalary->basic_pay }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           AGP
                        </th>
                        <td>
                            {{ $staffSalary->agp }}
                        </td>
                    </tr>
                    {{-- <tr>
                        <th>
                           DA
                        </th>
                        <td>
                            {{ $staffSalary->da }}
                        </td>
                    </tr> --}}
                    <tr>
                        <th>
                           HRA
                        </th>
                        <td>
                            {{ $staffSalary->hra }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Arrears
                        </th>
                        <td>
                            {{ $staffSalary->arrears }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Special Pay
                        </th>
                        <td>
                            {{ $staffSalary->special_pay }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Other Allowances
                        </th>
                        <td>
                            {{ $staffSalary->other_allowances }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Appraisal Based Increment
                        </th>
                        <td>
                            {{ $staffSalary->appraisal_based_increment }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Phd Allowance
                        </th>
                        <td>
                            {{ $staffSalary->phd_allowance }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Gross Salary
                        </th>
                        <td>
                            {{ $staffSalary->gross_salary }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           IT
                        </th>
                        <td>
                            {{ $staffSalary->it }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           PT
                        </th>
                        <td>
                            {{ $staffSalary->pt }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Salary Advance
                        </th>
                        <td>
                            {{ $staffSalary->salary_advance }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Other Deduction
                        </th>
                        <td>
                            {{ $staffSalary->other_deduction }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           ESI
                        </th>
                        <td>
                            {{ $staffSalary->esi }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           EPF
                        </th>
                        <td>
                            {{ $staffSalary->epf }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Total Deductions
                        </th>
                        <td>
                            {{ $staffSalary->total_deductions }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Net Salary
                        </th>
                        <td>
                            {{ $staffSalary->net_salary }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Remarks
                        </th>
                        <td>
                            {{ $staffSalary->remarks }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.staff-salaries.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
