<?php

namespace App\Http\Requests;

use App\Models\ToolsDepartment;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyToolsDepartmentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('tools_department_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:tools_departments,id',
        ];
    }
}
