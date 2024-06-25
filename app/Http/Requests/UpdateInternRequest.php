<?php

namespace App\Http\Requests;

use App\Models\Intern;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateInternRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('intern_edit');
    }

    public function rules()
    {
        return [
            'topic' => [
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
            'progress_report' => [
                'string',
                'nullable',
            ],
        ];
    }
}
