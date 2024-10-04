<?php

namespace App\Http\Requests;

use App\Models\Setting;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSettingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('setting_create');
    }

    public function rules()
    {
        return [
            'no_of_periods' => [
                'string',
                'nullable',
            ],
            'no_of_semester' => [
                'string',
                'nullable',
            ],
            'semester_type' => [
                'string',
                'nullable',
            ],
        ];
    }
}
