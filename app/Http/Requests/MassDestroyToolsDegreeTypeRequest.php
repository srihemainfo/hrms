<?php

namespace App\Http\Requests;

use App\Models\ToolsDegreeType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyToolsDegreeTypeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('tools_degree_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:tools_degree_types,id',
        ];
    }
}
