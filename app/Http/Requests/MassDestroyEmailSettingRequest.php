<?php

namespace App\Http\Requests;

use App\Models\EmailSetting;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyEmailSettingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('email_setting_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:email_settings,id',
        ];
    }
}
