<?php

namespace App\Http\Requests;

use App\Models\Sponser;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSponserRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('sponser_create');
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
            'application_date'=>[
                'date_format:Y-m-d',
                'nullable'
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
            'received_date'=>[
                'date_format:Y-m-d',
                'nullable'
            ],
        ];
    }
}
