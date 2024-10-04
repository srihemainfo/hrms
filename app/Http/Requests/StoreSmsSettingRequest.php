<?php

namespace App\Http\Requests;

use App\Models\SmsSetting;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSmsSettingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('sms_setting_create');
    }

    public function rules()
    {
        return [
            'key' => [
                'string',
                'nullable',
            ],
            'url' => [
                'string',
                'nullable',
            ],
        ];
    }
}
