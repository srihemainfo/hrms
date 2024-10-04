<?php

namespace App\Http\Requests;

use App\Models\OdRequest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyOdRequestRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('od_request_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:od_requests,id',
        ];
    }
}
