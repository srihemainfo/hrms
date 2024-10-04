<?php

namespace App\Http\Requests;

use App\Models\InternshipRequest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateInternshipRequestRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('internship_request_edit');
    }

    public function rules()
    {
        return [
            'user' => [
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
            'to_date' => [
                'string',
                'min:0',
                'max:15',
                'nullable',
            ],
            'level_1_userid' => [
                'string',
                'min:0',
                'max:15',
                'nullable',
            ],
            'level_2_userid' => [
                'string',
                'min:0',
                'max:15',
                'nullable',
            ],
            'level_3_userid' => [
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
