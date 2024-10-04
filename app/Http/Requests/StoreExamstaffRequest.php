<?php

namespace App\Http\Requests;

use App\Models\Examstaff;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreExamstaffRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('examstaff_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'nullable',
            ],
        ];
    }
}
