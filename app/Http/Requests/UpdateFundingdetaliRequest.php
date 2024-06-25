<?php

namespace App\Http\Requests;

use App\Models\Fundingdetali;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateFundingdetaliRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('fundingdetali_edit');
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
