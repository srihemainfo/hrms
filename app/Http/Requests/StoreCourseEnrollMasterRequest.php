<?php

namespace App\Http\Requests;

use App\Models\CourseEnrollMaster;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCourseEnrollMasterRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('course_enroll_master_create');
    }

    public function rules()
    {
        return [
            'enroll_master_number' => [
                'string',
                'min:1',
                'max:191',
                'required',
            ],
            'degreetype_id' => [
                'required',
                'integer',
            ],
            'batch_id' => [
                'required',
                'integer',
            ],
            'course_id' => [
                'required',
                'integer',
            ],
            'department_id' => [
                'required',
                'integer',
            ],
            'semester_id' => [
                'required',
                'integer',
            ],
            'section_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
