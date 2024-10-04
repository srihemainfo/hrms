<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCollegeCalenderRequest;
use App\Http\Requests\UpdateCollegeCalenderRequest;
use App\Http\Resources\Admin\CollegeCalenderResource;
use App\Models\CollegeCalender;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CollegeCalenderApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('college_calender_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CollegeCalenderResource(CollegeCalender::all());
    }

    public function store(StoreCollegeCalenderRequest $request)
    {
        $collegeCalender = CollegeCalender::create($request->all());

        return (new CollegeCalenderResource($collegeCalender))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CollegeCalender $collegeCalender)
    {
        abort_if(Gate::denies('college_calender_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CollegeCalenderResource($collegeCalender);
    }

    public function update(UpdateCollegeCalenderRequest $request, CollegeCalender $collegeCalender)
    {
        $collegeCalender->update($request->all());

        return (new CollegeCalenderResource($collegeCalender))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CollegeCalender $collegeCalender)
    {
        abort_if(Gate::denies('college_calender_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $collegeCalender->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
