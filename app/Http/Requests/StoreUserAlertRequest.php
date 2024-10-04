<?php

namespace App\Http\Requests;

use App\Models\UserAlert;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreUserAlertRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('user_alert_create');
    }

    public function rules()
    {
        return [
            'alert_text' => [
                'text',
                'required',
            ],
            'alert_link' => [
                'text',
                'nullable',
            ],
            'users.*' => [
                'integer',
            ],
            'users' => [
                'array',
            ],
        ];
    }
}
