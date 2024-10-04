<?php

namespace App\Http\Requests;

use App\Models\TakeAttentanceStudent;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTakeAttentanceStudentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('take_attentance_student_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:take_attentance_students,id',
        ];
    }
}
