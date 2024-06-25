<?php

namespace App\Http\Requests;

use App\Models\HrmRequestPermission;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreHrmRequestPermissionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('hrm_request_permission_create');
    }

    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'integer',
            ],
            'no_of_hours' => [
                'string',
                'min:0',
                'max:15',
                'nullable',
            ],
            'from_date' => [
                'string',
                'min:0',
                'max:15',
                'nullable',
            ],
            'reason' => [
                'string',
                'min:0',
                'max:15',
                'nullable',
            ],
            'approved_by' => [
                'string',
                'min:0',
                'max:15',
                'nullable',
            ],
        ];
    }
}
