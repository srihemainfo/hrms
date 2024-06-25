<?php

namespace App\Http\Requests;

use App\Models\StaffSalary;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateStaffSalaryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('staff_salary_edit');
    }

    public function rules()
    {
        return [
            'staff_code' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'biometric' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'staff_name' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'department' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'designation' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'date_of_joining' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'total_working_days' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'loss_of_pay' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'no_of_day_payable' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'actual_gross_salary' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'basic_pay' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'agp' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'da' => [
                'string',
                'nullable',
            ],
            'hra' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'arrears' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'special_pay' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'other_allowances' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'appraisal_based_increment' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'phd_allowance' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'gross_salary' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'it' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'pt' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'salary_advance' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'other_deduction' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'esi' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'epf' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'total_deductions' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'net_salary' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'remarks' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
        ];
    }
}
