<?php

namespace App\Http\Requests;

use App\Models\CollegeCalender;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyCollegeCalenderRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('college_calender_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:college_calenders,id',
        ];
    }
}
