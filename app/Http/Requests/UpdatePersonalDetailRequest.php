<?php

namespace App\Http\Requests;

use App\Models\PersonalDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePersonalDetailRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('personal_detail_edit');
    }

    public function rules()
    {
        return [
            'age' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'dob' => [
                'date_format:Y-m-d',
                'nullable',
            ],
            'mobile_number' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'aadhar_number' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'state' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'country' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
        ];
    }
}
