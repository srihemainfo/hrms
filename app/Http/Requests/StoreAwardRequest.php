<?php

namespace App\Http\Requests;

use App\Models\Award;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAwardRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('award_create');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'organizer_name' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'awarded_date' => [
                'date_format:Y-m-d',
                'nullable'
            ],
            'venue' => [
                'string',
                'min:1',
                'max:1999',
                'nullable',
            ],
        ];
    }
}
