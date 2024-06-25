<?php

namespace App\Http\Requests;

use App\Models\Iv;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreIvRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('iv_create');
    }

    public function rules()
    {
        return [
            'topic' => [
                'string',
                'nullable',
            ],
            'location' => [
                'string',
                'nullable',
            ],
            'from_date' => [
                'date_format:Y-m-d',
                'nullable',
            ],
            'to_date' => [
                'date_format:Y-m-d',
                'nullable',
            ],
            'remarks' => [
                'string',
                'nullable',
            ],
        ];
    }
}
