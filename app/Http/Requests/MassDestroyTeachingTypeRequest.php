<?php

namespace App\Http\Requests;

use App\Models\TeachingType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTeachingTypeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('teaching_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:teaching_types,id',
        ];
    }
}
