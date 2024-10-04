<?php

namespace App\Http\Requests;

use App\Models\BankAccountDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyBankAccountDetailRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('bank_account_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:bank_account_details,id',
        ];
    }
}
