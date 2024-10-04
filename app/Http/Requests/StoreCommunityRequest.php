<?php

namespace App\Http\Requests;

use App\Models\Community;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCommunityRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('community_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'nullable',
            ],
        ];
    }
}
