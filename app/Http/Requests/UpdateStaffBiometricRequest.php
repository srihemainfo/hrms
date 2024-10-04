<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffBiometricRequest extends FormRequest
{
    // public function authorize()
    // {
    //     return Gate::allows('leave_type_edit');
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
                'boolean',
            ],
        ];
    }
}
