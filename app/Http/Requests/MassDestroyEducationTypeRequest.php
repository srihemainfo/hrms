<?php

namespace App\Http\Requests;

use App\Models\EducationType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyEducationTypeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('education_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:education_types,id',
        ];
    }
}
