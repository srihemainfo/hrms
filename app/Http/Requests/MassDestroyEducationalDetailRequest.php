<?php

namespace App\Http\Requests;

use App\Models\EducationalDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyEducationalDetailRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('educational_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:educational_details,id',
        ];
    }
}
