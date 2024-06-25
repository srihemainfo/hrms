<?php

namespace App\Http\Requests;

use App\Models\SmsSetting;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSmsSettingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('sms_setting_edit');
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
