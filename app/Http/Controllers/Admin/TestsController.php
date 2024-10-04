<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTestRequest;
use App\Http\Requests\StoreTestRequest;
use App\Http\Requests\UpdateTestRequest;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Test;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TestsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('test_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Test::with(['course', 'lesson'])->select(sprintf('%s.*', (new Test)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'test_show';
                $editGate      = 'test_edit';
                $deleteGate    = 'test_delete';
                $crudRoutePart = 'tests';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->addColumn('course_title', function ($row) {
                return $row->course ? $row->course->title : '';
            });

            $table->addColumn('lesson_title', function ($row) {
                return $row->lesson ? $row->lesson->title : '';
            });

            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });
            $table->editColumn('is_published', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->is_published ? 'checked' : null) . '>';
            });

            $table->rawColumns(['actions', 'placeholder', 'course', 'lesson', 'is_published']);

            return $table->make(true);
        }

        return view('admin.tests.index');
    }

    public function create()
    {
        abort_if(Gate::denies('test_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $courses = Course::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $lessons = Lesson::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.tests.create', compact('courses', 'lessons'));
    }

    public function store(StoreTestRequest $request)
    {
        $test = Test::create($request->all());

        return redirect()->route('admin.tests.index');
    }

    public function edit(Test $test)
    {
        abort_if(Gate::denies('test_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $courses = Course::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $lessons = Lesson::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $test->load('course', 'lesson');

        return view('admin.tests.edit', compact('courses', 'lessons', 'test'));
    }

    public function update(UpdateTestRequest $request, Test $test)
    {
        $test->update($request->all());

        return redirect()->route('admin.tests.index');
    }

    public function show(Test $test)
    {
        abort_if(Gate::denies('test_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $test->load('course', 'lesson');

        return view('admin.tests.show', compact('test'));
    }

    public function destroy(Test $test)
    {
        abort_if(Gate::denies('test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $test->delete();

        return back();
    }

    public function massDestroy(MassDestroyTestRequest $request)
    {
        $tests = Test::find(request('ids'));

        foreach ($tests as $test) {
            $test->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
