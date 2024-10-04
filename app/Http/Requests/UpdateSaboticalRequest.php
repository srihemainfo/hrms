<?php

namespace App\Http\Requests;

use App\Models\Sabotical;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSaboticalRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('sabotical_edit');
    }

    public function rules()
    {
        return [
            'topic' => [
                'string',
                'nullable',
            ],
            // 'eligiblity_approve' => [
            //     'string',
            //     'nullable',
            // ],
            'from' => [
                'date_format:Y-m-d',
                'nullable',
            ],
            'to' => [
                'date_format:Y-m-d',
                'nullable',
            ],
        ];
    }
}
