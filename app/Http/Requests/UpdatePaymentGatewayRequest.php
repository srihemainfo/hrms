<?php

namespace App\Http\Requests;

use App\Models\PaymentGateway;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePaymentGatewayRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('payment_gateway_edit');
    }

    public function rules()
    {
        return [
            'gateway_type' => [
                'string',
                'nullable',
            ],
            'prefix' => [
                'string',
                'nullable',
            ],
            'url' => [
                'string',
                'nullable',
            ],
            'username' => [
                'string',
                'nullable',
            ],
            'password' => [
                'string',
                'nullable',
            ],
            'merchand' => [
                'string',
                'nullable',
            ],
        ];
    }
}
