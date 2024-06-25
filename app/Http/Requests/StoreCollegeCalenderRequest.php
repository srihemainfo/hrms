<?php

namespace App\Http\Requests;

use App\Models\CollegeCalender;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCollegeCalenderRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('college_calender_create');
    }

    public function rules()
    {
        return [
            // 'type' => [
            //     'required',
            //     'string',
            //     'nullable',
            // ],
            'academic_year' => [
                'required',
                'string',
                'nullable',
            ],
            // 'shift' => [
            //     'required',
            //     'string',
            //     'nullable',
            // ],
            'semester_type' => [
                'required',
                'string',
                'nullable',
            ],
            'from_date' => [
                'required',

                'nullable',
            ],
            'to_date' => [
                'required',

                'nullable',
            ],
        ];
    }
}
