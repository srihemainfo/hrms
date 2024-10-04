<?php

namespace App\Http\Requests;

use App\Models\Workshop;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreWorkshopRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('workshop_create');
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
            'remarks' => [
                'string',
                'min:1',
                'max:191',
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
        ];
    }
}
