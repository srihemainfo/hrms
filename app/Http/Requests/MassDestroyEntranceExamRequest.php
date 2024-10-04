<?php

namespace App\Http\Requests;

use App\Models\EntranceExam;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyEntranceExamRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('entrance_exam_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:entrance_exams,id',
        ];
    }
}
