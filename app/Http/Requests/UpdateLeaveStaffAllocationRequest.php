<?php

namespace App\Http\Requests;

use App\Models\LeaveStaffAllocation;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateLeaveStaffAllocationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('leave_staff_allocation_edit');
    }

    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'integer',
            ],
            'academic_year_id' => [
                'required',
                'integer',
            ],
            'no_of_leave' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
