<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyAcademicDetailRequest;
use App\Http\Requests\StoreAcademicDetailRequest;
use App\Http\Requests\UpdateAcademicDetailRequest;
use App\Models\AcademicDetail;
use App\Models\CourseEnrollMaster;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AcademicDetailsController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('academic_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $academicDetails = AcademicDetail::with(['enroll_master_number'])->get();

        return view('frontend.academicDetails.index', compact('academicDetails'));
    }

    public function create()
    {
        abort_if(Gate::denies('academic_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $enroll_master_numbers = CourseEnrollMaster::pluck('enroll_master_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.academicDetails.create', compact('enroll_master_numbers'));
    }

    public function store(StoreAcademicDetailRequest $request)
    {
        $academicDetail = AcademicDetail::create($request->all());

        return redirect()->route('frontend.academic-details.index');
    }

    public function edit(AcademicDetail $academicDetail)
    {
        abort_if(Gate::denies('academic_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $enroll_master_numbers = CourseEnrollMaster::pluck('enroll_master_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $academicDetail->load('enroll_master_number');

        return view('frontend.academicDetails.edit', compact('academicDetail', 'enroll_master_numbers'));
    }

    public function update(UpdateAcademicDetailRequest $request, AcademicDetail $academicDetail)
    {
        $academicDetail->update($request->all());

        return redirect()->route('frontend.academic-details.index');
    }

    public function show(AcademicDetail $academicDetail)
    {
        abort_if(Gate::denies('academic_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $academicDetail->load('enroll_master_number');

        return view('frontend.academicDetails.show', compact('academicDetail'));
    }

    public function destroy(AcademicDetail $academicDetail)
    {
        abort_if(Gate::denies('academic_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $academicDetail->delete();

        return back();
    }

    public function massDestroy(MassDestroyAcademicDetailRequest $request)
    {
        $academicDetails = AcademicDetail::find(request('ids'));

        foreach ($academicDetails as $academicDetail) {
            $academicDetail->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
