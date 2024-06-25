<?php

namespace App\Http\Requests;

use App\Models\Patent;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePatentRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('patent_create');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'nullable',
            ],
            'application_no' => [
                'string',
                'nullable',
            ],
            'application_date' => [
                'date_format:Y-m-d',
                'nullable'
            ],
            'application_status' => [
                'string',
                'nullable',
            ],
        ];
    }
}
