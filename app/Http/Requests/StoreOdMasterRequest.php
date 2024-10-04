<?php

namespace App\Http\Requests;

use App\Models\OdMaster;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreOdMasterRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('od_master_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'min:0',
                'max:15',
                'required',
            ],
            'level_1_role' => [
                'string',
                'min:0',
                'max:15',
                'required',
            ],
            'level_2_role' => [
                'string',
                'min:0',
                'max:15',
                'required',
            ],
            'level_3_role' => [
                'string',
                'min:0',
                'max:15',
                'required',
            ],
            'approved_by' => [
                'string',
                'min:0',
                'max:15',
                'required',
            ],
        ];
    }
}
