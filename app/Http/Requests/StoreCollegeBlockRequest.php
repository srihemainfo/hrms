<?php

namespace App\Http\Requests;

use App\Models\CollegeBlock;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCollegeBlockRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('college_block_create');
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
