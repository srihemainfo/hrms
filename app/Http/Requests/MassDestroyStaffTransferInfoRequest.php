<?php

namespace App\Http\Requests;

use App\Models\StaffTransferInfo;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyStaffTransferInfoRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('staff_transfer_info_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:staff_transfer_infos,id',
        ];
    }
}
