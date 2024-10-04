<?php

namespace App\Http\Requests;

use App\Models\OdMaster;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyOdMasterRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('od_master_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:od_masters,id',
        ];
    }
}
