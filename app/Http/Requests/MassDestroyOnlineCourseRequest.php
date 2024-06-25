<?php

namespace App\Http\Requests;

use App\Models\OnlineCourse;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyOnlineCourseRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('online_course_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:online_courses,id',
        ];
    }
}
