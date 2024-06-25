<?php

namespace App\Http\Requests;

use App\Models\ToolsDepartment;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreToolsDepartmentRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('tools_department_create');
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
