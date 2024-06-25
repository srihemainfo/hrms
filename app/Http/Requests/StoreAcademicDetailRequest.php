<?php

namespace App\Http\Requests;

use App\Models\AcademicDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAcademicDetailRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('academic_detail_create');
    }

    public function rules()
    {
        return [
            'register_number' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'emis_number' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
        ];
    }
}
