<?php

namespace App\Http\Requests;

use App\Models\CollegeBlock;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyCollegeBlockRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('college_block_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:college_blocks,id',
        ];
    }
}
