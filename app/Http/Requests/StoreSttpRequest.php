<?php

namespace App\Http\Requests;

use App\Models\Sttp;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSttpRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('sttp_create');
    }

    public function rules()
    {
        return [
            'topic' => [
                'string',
                'nullable',
            ],
            'remarks' => [
                'string',
                'nullable',
            ],
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
