<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateToolssyllabusYearRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('toolssyllabus_year_edit');
    }

    public function rules()
    {
        return true;
    }
}
