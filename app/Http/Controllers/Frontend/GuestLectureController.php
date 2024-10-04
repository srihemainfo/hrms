<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyGuestLectureRequest;
use App\Http\Requests\StoreGuestLectureRequest;
use App\Http\Requests\UpdateGuestLectureRequest;
use App\Models\GuestLecture;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestLectureController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('guest_lecture_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guestLectures = GuestLecture::with(['user_name'])->get();

        return view('frontend.guestLectures.index', compact('guestLectures'));
    }

    public function create()
    {
        abort_if(Gate::denies('guest_lecture_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.guestLectures.create', compact('user_names'));
    }

    public function store(StoreGuestLectureRequest $request)
    {
        $guestLecture = GuestLecture::create($request->all());

        return redirect()->route('frontend.guest-lectures.index');
    }

    public function edit(GuestLecture $guestLecture)
    {
        abort_if(Gate::denies('guest_lecture_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $guestLecture->load('user_name');

        return view('frontend.guestLectures.edit', compact('guestLecture', 'user_names'));
    }

    public function update(UpdateGuestLectureRequest $request, GuestLecture $guestLecture)
    {
        $guestLecture->update($request->all());

        return redirect()->route('frontend.guest-lectures.index');
    }

    public function show(GuestLecture $guestLecture)
    {
        abort_if(Gate::denies('guest_lecture_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guestLecture->load('user_name');

        return view('frontend.guestLectures.show', compact('guestLecture'));
    }

    public function destroy(GuestLecture $guestLecture)
    {
        abort_if(Gate::denies('guest_lecture_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guestLecture->delete();

        return back();
    }

    public function massDestroy(MassDestroyGuestLectureRequest $request)
    {
        $guestLectures = GuestLecture::find(request('ids'));

        foreach ($guestLectures as $guestLecture) {
            $guestLecture->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
