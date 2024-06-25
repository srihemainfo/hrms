<?php

namespace App\Http\Requests;

use App\Models\TeachingStaff;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTeachingStaffRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('teaching_staff_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'StaffCode' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'BiometricID' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'Gender' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'Designation' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'Dept' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'Qualification' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'DOJ' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'DOR' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'OtherEnggCollegeExperience' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'TotalExperience' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'ContactNo' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'EmailIDOffical' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'Religion' => [
                'string',
                'nullable',
            ],
            'Community' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'PanNo' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'PassportNo' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'AadharNo' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'COECode' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'AICTE' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'DOB' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'HighestDegree' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'TotalSalary' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'basicPay' => [
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
            'specialFee' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'phdAllowance' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'otherAllowence' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'da' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'hra' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ]
        ];
    }
}
