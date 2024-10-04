<?php

namespace App\Http\Requests;

use App\Models\Sttp;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySttpRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('sttp_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:sttps,id',
        ];
    }
}
