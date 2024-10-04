<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEducationalDetailRequest;
use App\Http\Requests\StoreEducationalDetailRequest;
use App\Http\Requests\UpdateEducationalDetailRequest;
use App\Models\EducationalDetail;
use App\Models\EducationType;
use App\Models\MediumofStudied;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EducationalDetailsController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('educational_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $educationalDetails = EducationalDetail::with(['education_type', 'medium'])->get();

        return view('frontend.educationalDetails.index', compact('educationalDetails'));
    }

    public function create()
    {
        abort_if(Gate::denies('educational_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $education_types = EducationType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $media = MediumofStudied::pluck('medium', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.educationalDetails.create', compact('education_types', 'media'));
    }

    public function store(StoreEducationalDetailRequest $request)
    {
        $educationalDetail = EducationalDetail::create($request->all());

        return redirect()->route('frontend.educational-details.index');
    }

    public function edit(EducationalDetail $educationalDetail)
    {
        abort_if(Gate::denies('educational_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $education_types = EducationType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $media = MediumofStudied::pluck('medium', 'id')->prepend(trans('global.pleaseSelect'), '');

        $educationalDetail->load('education_type', 'medium');

        return view('frontend.educationalDetails.edit', compact('education_types', 'educationalDetail', 'media'));
    }

    public function update(UpdateEducationalDetailRequest $request, EducationalDetail $educationalDetail)
    {
        $educationalDetail->update($request->all());

        return redirect()->route('frontend.educational-details.index');
    }

    public function show(EducationalDetail $educationalDetail)
    {
        abort_if(Gate::denies('educational_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $educationalDetail->load('education_type', 'medium');

        return view('frontend.educationalDetails.show', compact('educationalDetail'));
    }

    public function destroy(EducationalDetail $educationalDetail)
    {
        abort_if(Gate::denies('educational_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $educationalDetail->delete();

        return back();
    }

    public function massDestroy(MassDestroyEducationalDetailRequest $request)
    {
        $educationalDetails = EducationalDetail::find(request('ids'));

        foreach ($educationalDetails as $educationalDetail) {
            $educationalDetail->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
