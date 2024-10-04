<?php

namespace App\Http\Requests;

use App\Models\AddConference;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyAddConferenceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('add_conference_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:add_conferences,id',
        ];
    }
}
