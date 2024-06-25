<?php

namespace App\Http\Requests;

use App\Models\Seminar;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSeminarRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('seminar_edit');
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
            'seminar_date' => [
                'date_format:Y-m-d',
                'nullable',
            ],
        ];
    }
}
