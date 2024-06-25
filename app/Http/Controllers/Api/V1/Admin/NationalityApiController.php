<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNationalityRequest;
use App\Http\Requests\UpdateNationalityRequest;
use App\Http\Resources\Admin\NationalityResource;
use App\Models\Nationality;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NationalityApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('nationality_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NationalityResource(Nationality::all());
    }

    public function store(StoreNationalityRequest $request)
    {
        $nationality = Nationality::create($request->all());

        return (new NationalityResource($nationality))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Nationality $nationality)
    {
        abort_if(Gate::denies('nationality_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NationalityResource($nationality);
    }

    public function update(UpdateNationalityRequest $request, Nationality $nationality)
    {
        $nationality->update($request->all());

        return (new NationalityResource($nationality))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Nationality $nationality)
    {
        abort_if(Gate::denies('nationality_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $nationality->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
