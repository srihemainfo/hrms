<?php

namespace App\Http\Requests;

use App\Models\HrmRequestLeaf;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyHrmRequestLeafRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('hrm_request_leaf_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:hrm_request_leaves,id',
        ];
    }
}
