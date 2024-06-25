<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyToolsCourseRequest;
use App\Http\Requests\StoreToolsCourseRequest;
use App\Http\Requests\UpdateToolsCourseRequest;
use App\Models\ToolsCourse;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ToolsCourseController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('tools_course_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $toolsCourses = ToolsCourse::all();

        return view('frontend.toolsCourses.index', compact('toolsCourses'));
    }

    public function create()
    {
        abort_if(Gate::denies('tools_course_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.toolsCourses.create');
    }

    public function store(StoreToolsCourseRequest $request)
    {
        $toolsCourse = ToolsCourse::create($request->all());

        return redirect()->route('frontend.tools-courses.index');
    }

    public function edit(ToolsCourse $toolsCourse)
    {
        abort_if(Gate::denies('tools_course_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.toolsCourses.edit', compact('toolsCourse'));
    }

    public function update(UpdateToolsCourseRequest $request, ToolsCourse $toolsCourse)
    {
        $toolsCourse->update($request->all());

        return redirect()->route('frontend.tools-courses.index');
    }

    public function show(ToolsCourse $toolsCourse)
    {
        abort_if(Gate::denies('tools_course_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.toolsCourses.show', compact('toolsCourse'));
    }

    public function destroy(ToolsCourse $toolsCourse)
    {
        abort_if(Gate::denies('tools_course_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $toolsCourse->delete();

        return back();
    }

    public function massDestroy(MassDestroyToolsCourseRequest $request)
    {
        $toolsCourses = ToolsCourse::find(request('ids'));

        foreach ($toolsCourses as $toolsCourse) {
            $toolsCourse->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
