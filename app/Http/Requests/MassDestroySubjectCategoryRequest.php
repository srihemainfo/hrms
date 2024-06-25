<?php

namespace App\Http\Requests;

use App\Models\SubjectType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySubjectCategoryRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('subject_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:subject_category,id',
        ];
    }
}
