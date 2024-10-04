@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} Staff Salary
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.staff-salaries.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="staff_code">Staff Code</label>
                <input class="form-control {{ $errors->has('staff_code') ? 'is-invalid' : '' }}" type="text" name="staff_code" id="staff_code" value="{{ old('staff_code', '') }}">
                @if($errors->has('staff_code'))
                    <span class="text-danger">{{ $errors->first('staff_code') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.staff_code_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="biometric">Biometric ID</label>
                <input class="form-control {{ $errors->has('biometric') ? 'is-invalid' : '' }}" type="text" name="biometric" id="biometric" value="{{ old('biometric', '') }}">
                @if($errors->has('biometric'))
                    <span class="text-danger">{{ $errors->first('biometric') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.biometric_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="staff_name">Staff Name</label>
                <input class="form-control {{ $errors->has('staff_name') ? 'is-invalid' : '' }}" type="text" name="staff_name" id="staff_name" value="{{ old('staff_name', '') }}">
                @if($errors->has('staff_name'))
                    <span class="text-danger">{{ $errors->first('staff_name') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.staff_name_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="department">Department</label>
                <input class="form-control {{ $errors->has('department') ? 'is-invalid' : '' }}" type="text" name="department" id="department" value="{{ old('department', '') }}">
                @if($errors->has('department'))
                    <span class="text-danger">{{ $errors->first('department') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.department_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="designation">Designation</label>
                <input class="form-control {{ $errors->has('designation') ? 'is-invalid' : '' }}" type="text" name="designation" id="designation" value="{{ old('designation', '') }}">
                @if($errors->has('designation'))
                    <span class="text-danger">{{ $errors->first('designation') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.designation_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="date_of_joining">Date of Joining</label>
                <input class="form-control date {{ $errors->has('date_of_joining') ? 'is-invalid' : '' }}" type="text" name="date_of_joining" id="date_of_joining" value="{{ old('date_of_joining') }}">
                @if($errors->has('date_of_joining'))
                    <span class="text-danger">{{ $errors->first('date_of_joining') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.date_of_joining_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="total_working_days">Total Working Days</label>
                <input class="form-control {{ $errors->has('total_working_days') ? 'is-invalid' : '' }}" type="text" name="total_working_days" id="total_working_days" value="{{ old('total_working_days', '') }}">
                @if($errors->has('total_working_days'))
                    <span class="text-danger">{{ $errors->first('total_working_days') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.total_working_days_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="loss_of_pay">Loss of Pay (Days)</label>
                <input class="form-control {{ $errors->has('loss_of_pay') ? 'is-invalid' : '' }}" type="text" name="loss_of_pay" id="loss_of_pay" value="{{ old('loss_of_pay', '') }}">
                @if($errors->has('loss_of_pay'))
                    <span class="text-danger">{{ $errors->first('loss_of_pay') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.loss_of_pay_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="no_of_day_payable">No of Days (Payable)</label>
                <input class="form-control {{ $errors->has('no_of_day_payable') ? 'is-invalid' : '' }}" type="text" name="no_of_day_payable" id="no_of_day_payable" value="{{ old('no_of_day_payable', '') }}">
                @if($errors->has('no_of_day_payable'))
                    <span class="text-danger">{{ $errors->first('no_of_day_payable') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.no_of_day_payable_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="actual_gross_salary">Actual Gross Salary</label>
                <input class="form-control {{ $errors->has('actual_gross_salary') ? 'is-invalid' : '' }}" type="text" name="actual_gross_salary" id="actual_gross_salary" value="{{ old('actual_gross_salary', '') }}">
                @if($errors->has('actual_gross_salary'))
                    <span class="text-danger">{{ $errors->first('actual_gross_salary') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.actual_gross_salary_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="basic_pay">Basic Pay</label>
                <input class="form-control {{ $errors->has('basic_pay') ? 'is-invalid' : '' }}" type="text" name="basic_pay" id="basic_pay" value="{{ old('basic_pay', '') }}">
                @if($errors->has('basic_pay'))
                    <span class="text-danger">{{ $errors->first('basic_pay') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.basic_pay_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="agp">AGP</label>
                <input class="form-control {{ $errors->has('agp') ? 'is-invalid' : '' }}" type="text" name="agp" id="agp" value="{{ old('agp', '') }}">
                @if($errors->has('agp'))
                    <span class="text-danger">{{ $errors->first('agp') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.agp_helper') }}</span> --}}
            </div>
            {{-- <div class="form-group">
                <label for="da">DA</label>
                <input class="form-control {{ $errors->has('da') ? 'is-invalid' : '' }}" type="text" name="da" id="da" value="{{ old('da', '') }}">
                @if($errors->has('da'))
                    <span class="text-danger">{{ $errors->first('da') }}</span>
                @endif --}}
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.da_helper') }}</span> --}}
            {{-- </div> --}}
            <div class="form-group">
                <label for="hra">HRA</label>
                <input class="form-control {{ $errors->has('hra') ? 'is-invalid' : '' }}" type="number" name="hra" id="hra" value="{{ old('hra', '') }}" min="0" max="100">
                @if($errors->has('hra'))
                    <span class="text-danger">{{ $errors->first('hra') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.hra_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="arrears">Arrears</label>
                <input class="form-control {{ $errors->has('arrears') ? 'is-invalid' : '' }}" type="text" name="arrears" id="arrears" value="{{ old('arrears', '') }}">
                @if($errors->has('arrears'))
                    <span class="text-danger">{{ $errors->first('arrears') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.arrears_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="special_pay">Special Pay</label>
                <input class="form-control {{ $errors->has('special_pay') ? 'is-invalid' : '' }}" type="text" name="special_pay" id="special_pay" value="{{ old('special_pay', '') }}">
                @if($errors->has('special_pay'))
                    <span class="text-danger">{{ $errors->first('special_pay') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.special_pay_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="other_allowances">Other Allowances</label>
                <input class="form-control {{ $errors->has('other_allowances') ? 'is-invalid' : '' }}" type="text" name="other_allowances" id="other_allowances" value="{{ old('other_allowances', '') }}">
                @if($errors->has('other_allowances'))
                    <span class="text-danger">{{ $errors->first('other_allowances') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.other_allowances_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="appraisal_based_increment">Appraisal Based Increment</label>
                <input class="form-control {{ $errors->has('appraisal_based_increment') ? 'is-invalid' : '' }}" type="text" name="appraisal_based_increment" id="appraisal_based_increment" value="{{ old('appraisal_based_increment', '') }}">
                @if($errors->has('appraisal_based_increment'))
                    <span class="text-danger">{{ $errors->first('appraisal_based_increment') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.appraisal_based_increment_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="phd_allowance">Phd Allowance</label>
                <input class="form-control {{ $errors->has('phd_allowance') ? 'is-invalid' : '' }}" type="text" name="phd_allowance" id="phd_allowance" value="{{ old('phd_allowance', '') }}">
                @if($errors->has('phd_allowance'))
                    <span class="text-danger">{{ $errors->first('phd_allowance') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.phd_allowance_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="gross_salary">Gross Salary</label>
                <input class="form-control {{ $errors->has('gross_salary') ? 'is-invalid' : '' }}" type="text" name="gross_salary" id="gross_salary" value="{{ old('gross_salary', '') }}">
                @if($errors->has('gross_salary'))
                    <span class="text-danger">{{ $errors->first('gross_salary') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.gross_salary_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="it">IT</label>
                <input class="form-control {{ $errors->has('it') ? 'is-invalid' : '' }}" type="text" name="it" id="it" value="{{ old('it', '') }}">
                @if($errors->has('it'))
                    <span class="text-danger">{{ $errors->first('it') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.it_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="pt">PT</label>
                <input class="form-control {{ $errors->has('pt') ? 'is-invalid' : '' }}" type="text" name="pt" id="pt" value="{{ old('pt', '') }}">
                @if($errors->has('pt'))
                    <span class="text-danger">{{ $errors->first('pt') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.pt_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="salary_advance">Salary in Advance</label>
                <input class="form-control {{ $errors->has('salary_advance') ? 'is-invalid' : '' }}" type="text" name="salary_advance" id="salary_advance" value="{{ old('salary_advance', '') }}">
                @if($errors->has('salary_advance'))
                    <span class="text-danger">{{ $errors->first('salary_advance') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.salary_advance_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="other_deduction">Other Deduction</label>
                <input class="form-control {{ $errors->has('other_deduction') ? 'is-invalid' : '' }}" type="text" name="other_deduction" id="other_deduction" value="{{ old('other_deduction', '') }}">
                @if($errors->has('other_deduction'))
                    <span class="text-danger">{{ $errors->first('other_deduction') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.other_deduction_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="esi">ESI</label>
                <input class="form-control {{ $errors->has('esi') ? 'is-invalid' : '' }}" type="text" name="esi" id="esi" value="{{ old('esi', '') }}">
                @if($errors->has('esi'))
                    <span class="text-danger">{{ $errors->first('esi') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.esi_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="epf">EPF</label>
                <input class="form-control {{ $errors->has('epf') ? 'is-invalid' : '' }}" type="text" name="epf" id="epf" value="{{ old('epf', '') }}">
                @if($errors->has('epf'))
                    <span class="text-danger">{{ $errors->first('epf') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.epf_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="total_deductions">Total Deductions</label>
                <input class="form-control {{ $errors->has('total_deductions') ? 'is-invalid' : '' }}" type="text" name="total_deductions" id="total_deductions" value="{{ old('total_deductions', '') }}">
                @if($errors->has('total_deductions'))
                    <span class="text-danger">{{ $errors->first('total_deductions') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.total_deductions_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="net_salary">Net Salary</label>
                <input class="form-control {{ $errors->has('net_salary') ? 'is-invalid' : '' }}" type="text" name="net_salary" id="net_salary" value="{{ old('net_salary', '') }}">
                @if($errors->has('net_salary'))
                    <span class="text-danger">{{ $errors->first('net_salary') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.net_salary_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label for="remarks">Remarks</label>
                <input class="form-control {{ $errors->has('remarks') ? 'is-invalid' : '' }}" type="text" name="remarks" id="remarks" value="{{ old('remarks', '') }}">
                @if($errors->has('remarks'))
                    <span class="text-danger">{{ $errors->first('remarks') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.staffSalary.fields.remarks_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
