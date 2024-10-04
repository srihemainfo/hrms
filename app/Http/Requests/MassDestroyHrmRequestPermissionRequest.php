<?php

namespace App\Http\Requests;

use App\Models\HrmRequestPermission;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyHrmRequestPermissionRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('hrm_request_permission_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:hrm_request_permissions,id',
        ];
    }
}
