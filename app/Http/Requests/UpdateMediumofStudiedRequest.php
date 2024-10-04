<?php

namespace App\Http\Requests;

use App\Models\MediumofStudied;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateMediumofStudiedRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('mediumof_studied_edit');
    }

    public function rules()
    {
        return [
            'medium' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
        ];
    }
}
