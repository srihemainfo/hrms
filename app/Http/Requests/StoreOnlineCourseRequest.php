<?php

namespace App\Http\Requests;

use App\Models\OnlineCourse;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreOnlineCourseRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('online_course_create');
    }

    public function rules()
    {
        return [
            'course_name' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'remark' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'from_date' => [
                'date_format:Y-m-d',
                'nullable',
            ],
            'to_date' => [
                'date_format:Y-m-d',
                'nullable',
            ],
        ];
    }
}
