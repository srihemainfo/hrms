<?php

namespace App\Http\Requests;

use App\Models\Sponser;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySponserRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('sponser_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:sponsers,id',
        ];
    }
}
