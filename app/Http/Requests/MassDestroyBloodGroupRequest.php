<?php

namespace App\Http\Requests;

use App\Models\BloodGroup;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyBloodGroupRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('blood_group_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:blood_groups,id',
        ];
    }
}
