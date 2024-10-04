<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClassRoomRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('class_room_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'nullable',
            ],
            'block_id' => [
                'integer',
                'nullable',
            ],
            'department_id' => [
                'integer',
                'nullable',
            ],
            'class_incharge' => [
                'integer',
                'nullable',
            ],
            'room_no' => [
                'string',
                'nullable',

            ],
            'short_form' => [
                'string',
                'nullable',

            ],
            'no_of_seat' => [
                'integer',
                'nullable',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
