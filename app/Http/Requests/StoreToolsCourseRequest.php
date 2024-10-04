<?php

namespace App\Http\Requests;

use App\Models\ToolsCourse;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreToolsCourseRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('tools_course_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'short_form' => [
                'string',
                'required',
            ],
        ];
    }
}
