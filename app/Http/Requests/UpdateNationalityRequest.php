<?php

namespace App\Http\Requests;

use App\Models\Nationality;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateNationalityRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('nationality_edit');
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
