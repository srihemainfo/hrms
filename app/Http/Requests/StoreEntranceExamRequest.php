<?php

namespace App\Http\Requests;

use App\Models\EntranceExam;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreEntranceExamRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('entrance_exam_create');
    }

    public function rules()
    {
        return [
            'passing_year' => [
                'date_format:Y-m-d',
                'nullable',
            ],
            'scored_mark' => [
                'string',
                'nullable',
            ],
            'total_mark' => [
                'string',
                'nullable',
            ],
            'rank' => [
                'string',
                'nullable',
            ],
        ];
    }
}
