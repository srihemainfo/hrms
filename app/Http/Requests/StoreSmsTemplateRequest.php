<?php

namespace App\Http\Requests;

use App\Models\SmsTemplate;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSmsTemplateRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('sms_template_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'nullable',
            ],
            'sender' => [
                'string',
                'nullable',
            ],
            'template' => [
                'string',
                'nullable',
            ],
            'type' => [
                'string',
                'nullable',
            ],
            'content' => [
                'string',
                'nullable',
            ],
        ];
    }
}
