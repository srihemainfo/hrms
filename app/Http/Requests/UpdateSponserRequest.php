<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSponserRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('sponser_edit');
    }

    public function rules()
    {
        return [
            'sponser_name' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'project_title' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'project_duration' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'application_date' => [
                'date_format:Y-m-d',
                'nullable',
            ],
            'application_status' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'investigator_level' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'funding_amount' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'received_date' => [
                'date_format:Y-m-d',
                'nullable',
            ],
        ];
    }
}
