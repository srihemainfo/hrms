<?php

namespace App\Http\Requests;

use App\Models\ParentDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateParentDetailRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('parent_detail_edit');
    }

    public function rules()
    {
        return [
            'father_name' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'father_mobile_no' => [
                'string',
                'min:1',
                'max:20',
                'nullable',
            ],
            'fathers_occupation' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'mother_name' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'mother_mobile_no' => [
                'string',
                'min:1',
                'max:20',
                'nullable',
            ],
            'mothers_occupation' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'guardian_name' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'guardian_mobile_no' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'gaurdian_occupation' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
        ];
    }
}
