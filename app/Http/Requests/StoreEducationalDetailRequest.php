<?php

namespace App\Http\Requests;

use App\Models\EducationalDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreEducationalDetailRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('educational_detail_create');
    }

    public function rules()
    {
        return [
            'institute_name' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'institute_location' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'board_or_university' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'marks' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'marks_in_percentage' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'subject_1' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'mark_1' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'subject_2' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'mark_2' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'subject_3' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'mark_3' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'subject_4' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'mark_4' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'subject_5' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'mark_5' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'subject_6' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'mark_6' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
        ];
    }
}
