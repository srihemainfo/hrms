<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTeachingStaffRequest;
use App\Http\Requests\StoreTeachingStaffRequest;
use App\Http\Requests\UpdateTeachingStaffRequest;
use App\Models\CourseEnrollMaster;
use App\Models\Role;
use App\Models\Subject;
use App\Models\TeachingStaff;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeachingStaffController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('teaching_staff_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $teachingStaffs = TeachingStaff::with(['subject', 'enroll_master', 'working_as'])->get();

        return view('frontend.teachingStaffs.index', compact('teachingStaffs'));
    }

    public function create()
    {
        abort_if(Gate::denies('teaching_staff_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subjects = Subject::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $enroll_masters = CourseEnrollMaster::pluck('enroll_master_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $working_as = Role::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.teachingStaffs.create', compact('enroll_masters', 'subjects', 'working_as'));
    }

    public function store(StoreTeachingStaffRequest $request)
    {
        $teachingStaff = TeachingStaff::create($request->all());

        return redirect()->route('frontend.teaching-staffs.index');
    }

    public function edit(TeachingStaff $teachingStaff)
    {
        abort_if(Gate::denies('teaching_staff_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subjects = Subject::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $enroll_masters = CourseEnrollMaster::pluck('enroll_master_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $working_as = Role::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $teachingStaff->load('subject', 'enroll_master', 'working_as');

        return view('frontend.teachingStaffs.edit', compact('enroll_masters', 'subjects', 'teachingStaff', 'working_as'));
    }

    public function update(UpdateTeachingStaffRequest $request, TeachingStaff $teachingStaff)
    {
        $teachingStaff->update($request->all());

        return redirect()->route('frontend.teaching-staffs.index');
    }

    public function show(TeachingStaff $teachingStaff)
    {
        abort_if(Gate::denies('teaching_staff_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $teachingStaff->load('subject', 'enroll_master', 'working_as');

        return view('frontend.teachingStaffs.show', compact('teachingStaff'));
    }

    public function destroy(TeachingStaff $teachingStaff)
    {
        abort_if(Gate::denies('teaching_staff_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $teachingStaff->delete();

        return back();
    }

    public function massDestroy(MassDestroyTeachingStaffRequest $request)
    {
        $teachingStaffs = TeachingStaff::find(request('ids'));

        foreach ($teachingStaffs as $teachingStaff) {
            $teachingStaff->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
