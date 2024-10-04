<?php

namespace App\Http\Requests;

use App\Models\TeachingType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTeachingTypeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('teaching_type_edit');
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
