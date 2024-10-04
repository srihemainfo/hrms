<?php

namespace App\Http\Requests;

use App\Models\LeaveStaffAllocation;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyLeaveStaffAllocationRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('leave_staff_allocation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:leave_staff_allocations,id',
        ];
    }
}
