<?php

namespace App\Http\Requests;

use App\Models\NonTeachingStaff;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreNonTeachingStaffRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('non_teaching_staff_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
        ];
    }
}
