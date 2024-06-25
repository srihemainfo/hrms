<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGuestLectureRequest;
use App\Http\Requests\UpdateGuestLectureRequest;
use App\Http\Resources\Admin\GuestLectureResource;
use App\Models\GuestLecture;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestLectureApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('guest_lecture_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new GuestLectureResource(GuestLecture::with(['user_name'])->get());
    }

    public function store(StoreGuestLectureRequest $request)
    {
        $guestLecture = GuestLecture::create($request->all());

        return (new GuestLectureResource($guestLecture))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(GuestLecture $guestLecture)
    {
        abort_if(Gate::denies('guest_lecture_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new GuestLectureResource($guestLecture->load(['user_name']));
    }

    public function update(UpdateGuestLectureRequest $request, GuestLecture $guestLecture)
    {
        $guestLecture->update($request->all());

        return (new GuestLectureResource($guestLecture))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(GuestLecture $guestLecture)
    {
        abort_if(Gate::denies('guest_lecture_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guestLecture->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
