<?php

namespace App\Http\Requests;

use App\Models\Batch;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreBatchRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('batch_create');
    }

    public function rules()
    {
        return [
            'from' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'to' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'name' => [
                'string',
                'min:1',
                'max:20',
                'required',
            ],
        ];
    }
}
