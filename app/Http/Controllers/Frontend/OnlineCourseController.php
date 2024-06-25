<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyOnlineCourseRequest;
use App\Http\Requests\StoreOnlineCourseRequest;
use App\Http\Requests\UpdateOnlineCourseRequest;
use App\Models\OnlineCourse;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnlineCourseController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('online_course_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $onlineCourses = OnlineCourse::with(['user_name'])->get();

        return view('frontend.onlineCourses.index', compact('onlineCourses'));
    }

    public function create()
    {
        abort_if(Gate::denies('online_course_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.onlineCourses.create', compact('user_names'));
    }

    public function store(StoreOnlineCourseRequest $request)
    {
        $onlineCourse = OnlineCourse::create($request->all());

        return redirect()->route('frontend.online-courses.index');
    }

    public function edit(OnlineCourse $onlineCourse)
    {
        abort_if(Gate::denies('online_course_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $onlineCourse->load('user_name');

        return view('frontend.onlineCourses.edit', compact('onlineCourse', 'user_names'));
    }

    public function update(UpdateOnlineCourseRequest $request, OnlineCourse $onlineCourse)
    {
        $onlineCourse->update($request->all());

        return redirect()->route('frontend.online-courses.index');
    }

    public function show(OnlineCourse $onlineCourse)
    {
        abort_if(Gate::denies('online_course_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $onlineCourse->load('user_name');

        return view('frontend.onlineCourses.show', compact('onlineCourse'));
    }

    public function destroy(OnlineCourse $onlineCourse)
    {
        abort_if(Gate::denies('online_course_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $onlineCourse->delete();

        return back();
    }

    public function massDestroy(MassDestroyOnlineCourseRequest $request)
    {
        $onlineCourses = OnlineCourse::find(request('ids'));

        foreach ($onlineCourses as $onlineCourse) {
            $onlineCourse->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
