<?php

namespace App\Http\Requests;

use App\Models\InternshipRequest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyInternshipRequestRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('internship_request_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:internship_requests,id',
        ];
    }
}
