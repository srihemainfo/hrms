<?php

namespace App\Http\Requests;

use App\Models\ToolsDegreeType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreToolsDegreeTypeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('tools_degree_type_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
        ];
    }
}
