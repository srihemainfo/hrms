<?php

namespace App\Http\Requests;

use App\Models\ToolsCourse;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyToolsCourseRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('tools_course_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:tools_courses,id',
        ];
    }
}
