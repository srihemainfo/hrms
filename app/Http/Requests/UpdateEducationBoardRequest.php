<?php

namespace App\Http\Requests;

use App\Models\EducationBoard;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateEducationBoardRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('education_board_edit');
    }

    public function rules()
    {
        return [
            'education_board' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
        ];
    }
}
