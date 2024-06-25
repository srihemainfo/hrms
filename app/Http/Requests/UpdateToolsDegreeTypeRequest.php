<?php

namespace App\Http\Requests;

use App\Models\ToolsDegreeType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateToolsDegreeTypeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('tools_degree_type_edit');
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
