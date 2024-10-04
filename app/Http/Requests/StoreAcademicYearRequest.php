<?php

namespace App\Http\Requests;

use App\Models\AcademicYear;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAcademicYearRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('academic_year_create');
    }

    public function rules()
    {
        return [
            'from' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'to' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'name' => [
                'string',
                'min:1',
                'max:191',
                'required',
            ],
        ];
    }
}
