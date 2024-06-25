<?php

namespace App\Http\Requests;

use App\Models\ToolsCourse;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateToolsCourseRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('tools_course_edit');
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
            ]
        ];
    }
}
