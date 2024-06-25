<?php

namespace App\Http\Requests;

use App\Models\Fundingdetali;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreFundingdetaliRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('fundingdetali_create');
    }

    public function rules()
    {
        return [
            'topic' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'remark' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
        ];
    }
}
