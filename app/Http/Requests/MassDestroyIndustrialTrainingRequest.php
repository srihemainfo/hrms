<?php

namespace App\Http\Requests;

use App\Models\IndustrialTraining;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyIndustrialTrainingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('industrial_training_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:industrial_trainings,id',
        ];
    }
}
