<?php

namespace App\Http\Requests;

use App\Models\LeaveStatus;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreLeaveStatusRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('leave_status_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'nullable',
            ],
        ];
    }
}
