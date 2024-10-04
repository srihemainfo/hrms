<?php

namespace App\Http\Requests;

use App\Models\EducationType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreEducationTypeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('education_type_create');
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
