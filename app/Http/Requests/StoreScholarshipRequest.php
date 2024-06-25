<?php

namespace App\Http\Requests;

use App\Models\Scholarship;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreScholarshipRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('scholarship_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'min:1',
                'max:100',
                'nullable',
            ],
        ];
    }
}
