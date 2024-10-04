<?php

namespace App\Http\Requests;

use App\Models\Religion;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreReligionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('religion_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'nullable',
            ],
        ];
    }
}
