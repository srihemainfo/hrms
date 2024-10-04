<?php

namespace App\Http\Requests;

use App\Models\StaffSalary;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyStaffSalaryRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('staff_salary_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:staff_salaries,id',
        ];
    }
}
