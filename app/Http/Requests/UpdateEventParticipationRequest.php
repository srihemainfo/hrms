<?php

namespace App\Http\Requests;

use App\Models\EventParticipation;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateEventParticipationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('event_participation_access');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'event_category' =>[
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'event_type' =>[
                'required',
                'integer',
                'nullable',
            ],
            'title' =>[
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'organized_by' =>[
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'event_location' =>[
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'event_duration' =>[
                'integer',
                'nullable',
            ],
            'start_date' =>[
                'date-format:Y-m-d',
                'nullable',
            ],
            'end_date' =>[
                'date-format:Y-m-d',
                'nullable',
            ]
        ];
    }
}
