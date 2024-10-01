<?php

namespace App\Http\Requests;

use App\Models\Permission;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePermissionRequest extends FormRequest
{


    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
            ],
        ];
    }
}
