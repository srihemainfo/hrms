<?php

namespace App\Http\Requests;

use App\Models\IndustrialTraining;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreIndustrialTrainingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('industrial_training_create');
    }

    public function rules()
    {
        return [
            'topic' => [
                'string',
                'nullable',
            ],
            'location' => [
                'string',
                'nullable',
            ],
            'remarks' => [
                'string',
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
