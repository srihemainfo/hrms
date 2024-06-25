<?php

namespace App\Http\Requests;

use App\Models\AcademicDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyAcademicDetailRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('academic_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:academic_details,id',
        ];
    }
}
