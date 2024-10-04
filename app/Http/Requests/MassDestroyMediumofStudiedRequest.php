<?php

namespace App\Http\Requests;

use App\Models\MediumofStudied;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyMediumofStudiedRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('mediumof_studied_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:mediumof_studieds,id',
        ];
    }
}
