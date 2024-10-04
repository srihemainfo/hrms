<?php

namespace App\Http\Requests;

use App\Models\Intern;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyInternRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('intern_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:interns,id',
        ];
    }
}
