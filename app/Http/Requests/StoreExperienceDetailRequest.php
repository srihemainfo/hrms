<?php

namespace App\Http\Requests;

use App\Models\ExperienceDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreExperienceDetailRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('experience_detail_create');
    }

    public function rules()
    {
        return [
            'designation' => [
                'required',
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'department' => [
                'required',
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'last_drawn_salary' => [
                'required',
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'name_of_organisation' => [
                'required',
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'taken_subjects' => [
                'string',
                'min:1',
                'max:1000',
                'nullable',
            ],
            'doj' => [
                'required',
                'date_format:Y-m-d',
                'nullable'
            ],
            'dor' => [
                'required',
                'date_format:Y-m-d',
                'nullable'
            ],
            'responsibilities' => [
                'string',
                'min:1',
                'max:1999',
                'nullable',
            ],
            'leaving_reason' => [
                'string',
                'min:1',
                'max:1999',
                'nullable',
            ],
            'address' => [
                'string',
                'min:1',
                'max:1999',
                'nullable',
            ],
        ];
    }
}
