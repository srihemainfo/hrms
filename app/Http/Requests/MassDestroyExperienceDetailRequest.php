<?php

namespace App\Http\Requests;

use App\Models\ExperienceDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyExperienceDetailRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('experience_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:experience_details,id',
        ];
    }
}
