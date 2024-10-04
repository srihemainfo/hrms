<?php

namespace App\Http\Requests;

use App\Models\Address;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAddressRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('address_create');
    }

    public function rules()
    {
        return [
            'room_no_and_street' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'area_name' => [
                'string',
                'min:1',
                'max:1000',
                'nullable',
            ],
            'district' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'pincode' => [
                'string',
                'min:1',
                'max:10',
                'nullable',
            ],
            'state' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
            'country' => [
                'string',
                'min:1',
                'max:191',
                'nullable',
            ],
        ];
    }
}
