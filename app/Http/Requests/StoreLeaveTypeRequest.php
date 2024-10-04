<?php

namespace App\Http\Requests;

use App\Models\LeaveType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreLeaveTypeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('leave_type_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'min:0',
                'max:191',
                'nullable',
            ],
        ];
    }
}
