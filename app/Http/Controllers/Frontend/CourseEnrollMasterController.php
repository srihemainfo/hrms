<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCourseEnrollMasterRequest;
use App\Http\Requests\StoreCourseEnrollMasterRequest;
use App\Http\Requests\UpdateCourseEnrollMasterRequest;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\CourseEnrollMaster;
use App\Models\Section;
use App\Models\Semester;
use App\Models\ToolsCourse;
use App\Models\ToolsDegreeType;
use App\Models\ToolsDepartment;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CourseEnrollMasterController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('course_enroll_master_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $courseEnrollMasters = CourseEnrollMaster::with(['degreetype', 'batch', 'academic', 'course', 'department', 'semester', 'section'])->get();

        return view('frontend.courseEnrollMasters.index', compact('courseEnrollMasters'));
    }

    public function create()
    {
        abort_if(Gate::denies('course_enroll_master_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $degreetypes = ToolsDegreeType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $batches = Batch::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $academics = AcademicYear::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $courses = ToolsCourse::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $departments = ToolsDepartment::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $semesters = Semester::pluck('semester', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sections = Section::pluck('section', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.courseEnrollMasters.create', compact('academics', 'batches', 'courses', 'degreetypes', 'departments', 'sections', 'semesters'));
    }

    public function store(StoreCourseEnrollMasterRequest $request)
    {
        $courseEnrollMaster = CourseEnrollMaster::create($request->all());

        return redirect()->route('frontend.course-enroll-masters.index');
    }

    public function edit(CourseEnrollMaster $courseEnrollMaster)
    {
        abort_if(Gate::denies('course_enroll_master_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $degreetypes = ToolsDegreeType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $batches = Batch::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $academics = AcademicYear::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $courses = ToolsCourse::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $departments = ToolsDepartment::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $semesters = Semester::pluck('semester', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sections = Section::pluck('section', 'id')->prepend(trans('global.pleaseSelect'), '');

        $courseEnrollMaster->load('degreetype', 'batch', 'academic', 'course', 'department', 'semester', 'section');

        return view('frontend.courseEnrollMasters.edit', compact('academics', 'batches', 'courseEnrollMaster', 'courses', 'degreetypes', 'departments', 'sections', 'semesters'));
    }

    public function update(UpdateCourseEnrollMasterRequest $request, CourseEnrollMaster $courseEnrollMaster)
    {
        $courseEnrollMaster->update($request->all());

        return redirect()->route('frontend.course-enroll-masters.index');
    }

    public function show(CourseEnrollMaster $courseEnrollMaster)
    {
        abort_if(Gate::denies('course_enroll_master_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $courseEnrollMaster->load('degreetype', 'batch', 'academic', 'course', 'department', 'semester', 'section');

        return view('frontend.courseEnrollMasters.show', compact('courseEnrollMaster'));
    }

    public function destroy(CourseEnrollMaster $courseEnrollMaster)
    {
        abort_if(Gate::denies('course_enroll_master_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $courseEnrollMaster->delete();

        return back();
    }

    public function massDestroy(MassDestroyCourseEnrollMasterRequest $request)
    {
        $courseEnrollMasters = CourseEnrollMaster::find(request('ids'));

        foreach ($courseEnrollMasters as $courseEnrollMaster) {
            $courseEnrollMaster->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
