<?php

namespace App\Http\Requests;

use App\Models\EmailSetting;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateEmailSettingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('email_setting_edit');
    }

    public function rules()
    {
        return [
            'host_name' => [
                'string',
                'nullable',
            ],
            'user_name' => [
                'string',
                'nullable',
            ],
            'password' => [
                'string',
                'nullable',
            ],
            'smtp_secure' => [
                'string',
                'nullable',
            ],
            'port_no' => [
                'string',
                'nullable',
            ],
            'from' => [
                'string',
                'nullable',
            ],
            'to' => [
                'string',
                'nullable',
            ],
            'cc' => [
                'string',
                'nullable',
            ],
            'bcc' => [
                'string',
                'nullable',
            ],
        ];
    }
}
