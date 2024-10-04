<?php

namespace App\Http\Requests;

use App\Models\ToolssyllabusYear;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyToolssyllabusYearRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('toolssyllabus_year_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:toolssyllabus_years,id',
        ];
    }
}
