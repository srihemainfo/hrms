<?php

namespace App\Http\Requests;

use App\Models\GuestLecture;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateGuestLectureRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('guest_lecture_edit');
    }

    public function rules()
    {
        return [
            'topic' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'remarks' => [
                'string',
                'min:1',
                'max:1000',
                'nullable',
            ],
            'location' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'from_date_and_time' => [
                'date_format:Y-m-d H:i:s',
                'nullable',
            ],
            'to_date_and_time' => [
                'date_format:Y-m-d H:i:s',
                'nullable',
            ],
        ];
    }
}
