<?php

namespace App\Http\Requests;

use App\Models\TakeAttentanceStudent;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTakeAttentanceStudentRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('take_attentance_student_edit');
    }

    public function rules()
    {
        return [
            'enroll_master_id' => [
                'required',
                'integer',
            ],
            'period' => [
                'string',
                'min:0',
                'max:15',
                'nullable',
            ],
            'taken_from' => [
                'string',
                'min:0',
                'max:15',
                'nullable',
            ],
            'approved_by' => [
                'string',
                'min:0',
                'max:15',
                'required',
            ],
        ];
    }
}
