<?php

namespace App\Http\Requests;

use App\Models\BankAccountDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreBankAccountDetailRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('bank_account_detail_create');
    }

    public function rules()
    {
        return [
            'account_type' => [
                'string',
                'min:1',
                'max:100',
                'nullable',
            ],
            'account_no' => [
                'string',
                'min:1',
                'max:25',
                'nullable',
            ],
            'ifsc_code' => [
                'string',
                'min:1',
                'max:25',
                'nullable',
            ],
            'bank_name' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'branch_name' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'bank_location' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
        ];
    }
}
