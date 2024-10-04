<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyNonTeachingStaffRequest;
use App\Http\Requests\StoreNonTeachingStaffRequest;
use App\Http\Requests\UpdateNonTeachingStaffRequest;
use App\Models\NonTeachingStaff;
use App\Models\Role;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NonTeachingStaffController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('non_teaching_staff_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $nonTeachingStaffs = NonTeachingStaff::with(['working_as'])->get();

        return view('frontend.nonTeachingStaffs.index', compact('nonTeachingStaffs'));
    }

    public function create()
    {
        abort_if(Gate::denies('non_teaching_staff_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $working_as = Role::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.nonTeachingStaffs.create', compact('working_as'));
    }

    public function store(StoreNonTeachingStaffRequest $request)
    {
        $nonTeachingStaff = NonTeachingStaff::create($request->all());

        return redirect()->route('frontend.non-teaching-staffs.index');
    }

    public function edit(NonTeachingStaff $nonTeachingStaff)
    {
        abort_if(Gate::denies('non_teaching_staff_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $working_as = Role::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $nonTeachingStaff->load('working_as');

        return view('frontend.nonTeachingStaffs.edit', compact('nonTeachingStaff', 'working_as'));
    }

    public function update(UpdateNonTeachingStaffRequest $request, NonTeachingStaff $nonTeachingStaff)
    {
        $nonTeachingStaff->update($request->all());

        return redirect()->route('frontend.non-teaching-staffs.index');
    }

    public function show(NonTeachingStaff $nonTeachingStaff)
    {
        abort_if(Gate::denies('non_teaching_staff_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $nonTeachingStaff->load('working_as');

        return view('frontend.nonTeachingStaffs.show', compact('nonTeachingStaff'));
    }

    public function destroy(NonTeachingStaff $nonTeachingStaff)
    {
        abort_if(Gate::denies('non_teaching_staff_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $nonTeachingStaff->delete();

        return back();
    }

    public function massDestroy(MassDestroyNonTeachingStaffRequest $request)
    {
        $nonTeachingStaffs = NonTeachingStaff::find(request('ids'));

        foreach ($nonTeachingStaffs as $nonTeachingStaff) {
            $nonTeachingStaff->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
