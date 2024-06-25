<?php

namespace App\Http\Requests;

use App\Models\IndustrialExperience;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyIndustrialExperienceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('industrial_experience_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:industrial_experiences,id',
        ];
    }
}
