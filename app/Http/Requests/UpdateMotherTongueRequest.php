<?php

namespace App\Http\Requests;

use App\Models\MotherTongue;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateMotherTongueRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('mother_tongue_edit');
    }

    public function rules()
    {
        return [
            'mother_tongue' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
        ];
    }
}
