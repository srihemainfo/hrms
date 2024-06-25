<?php

namespace App\Http\Requests;

use App\Models\StaffTransferInfo;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreStaffTransferInfoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('staff_transfer_info_create');
    }

    public function rules()
    {
        return [
            'enroll_master_id' => [
                'required',
                'integer',
            ],
            'period' => [
                'string',
                'min:0',
                'max:15',
                'nullable',
            ],
            'from_user' => [
                'string',
                'min:0',
                'max:15',
                'nullable',
            ],
            'to_user' => [
                'string',
                'min:0',
                'max:15',
                'nullable',
            ],
            'transfer_date' => [
                'string',
                'min:0',
                'max:15',
                'nullable',
            ],
            'approved_by_user' => [
                'string',
                'min:0',
                'max:15',
                'nullable',
            ],
        ];
    }
}
