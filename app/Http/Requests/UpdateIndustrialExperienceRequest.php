<?php

namespace App\Http\Requests;

use App\Models\IndustrialExperience;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateIndustrialExperienceRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('industrial_experience_edit');
    }

    public function rules()
    {
        return [
            'work_experience' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'designation' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'from' => [
                'date_format:Y-m-d',
                'nullable',
            ],
            'to' => [
                'date_format:Y-m-d',
                'nullable',
            ],
            'work_type' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
        ];
    }
}
