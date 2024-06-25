<?php

namespace App\Http\Requests;

use App\Models\StaffBiometric;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class StoreStaffBiometricRequest extends FormRequest
{
    // public function authorize()
    // {
    //     return Gate::allows('leave_type_create');
    // }

    public function rules()
    {
        return [
            'date' => [
                'date',
                'min:0',
                'max:20',
                'nullable',
            ],
            'employee_code' => [
                'string',
                'min:0',
                'max:20',
                'nullable',
            ],
            'staff_code' => [
                'string',
                'min:0',
                'max:20',
                'nullable',
            ],
            'employee_name' => [
                'string',
                'min:0',
                'max:20',
                'nullable',
            ],
            'in_time' => [
                'string',
                'nullable',
            ],
            'out_time' => [
                'string',
                'nullable',
            ],
            'update_status' => [
                'boolean'
            ]
        ];
    }
}
