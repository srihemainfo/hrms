<?php

namespace App\Http\Requests;

use App\Models\EventOrganized;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateEventOrganizedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('event_organized_access');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
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
            'funding_support' =>[
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'coordinated_sjfc' =>[
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'audience_category' =>[
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'participants' =>[
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
            ],
            'chiefguest_information' =>[
                'string',
                'min:1',
                'max:99999',
                'nullable',
            ],
            'total_participants' =>[
                'integer',
                'nullable',
            ]
        ];
    }
}
