<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePersonalDetailRequest;
use App\Http\Requests\UpdatePersonalDetailRequest;
use App\Http\Resources\Admin\PersonalDetailResource;
use App\Models\PersonalDetail;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PersonalDetailsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('personal_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PersonalDetailResource(PersonalDetail::with(['user_name', 'blood_group', 'mother_tongue', 'religion', 'community'])->get());
    }

    public function store(StorePersonalDetailRequest $request)
    {
        $personalDetail = PersonalDetail::create($request->all());

        return (new PersonalDetailResource($personalDetail))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(PersonalDetail $personalDetail)
    {
        abort_if(Gate::denies('personal_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PersonalDetailResource($personalDetail->load(['user_name', 'blood_group', 'mother_tongue', 'religion', 'community']));
    }

    public function update(UpdatePersonalDetailRequest $request, PersonalDetail $personalDetail)
    {
        $personalDetail->update($request->all());

        return (new PersonalDetailResource($personalDetail))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(PersonalDetail $personalDetail)
    {
        abort_if(Gate::denies('personal_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $personalDetail->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
