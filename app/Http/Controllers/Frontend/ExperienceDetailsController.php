<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyExperienceDetailRequest;
use App\Http\Requests\StoreExperienceDetailRequest;
use App\Http\Requests\UpdateExperienceDetailRequest;
use App\Models\ExperienceDetail;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExperienceDetailsController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('experience_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $experienceDetails = ExperienceDetail::with(['name'])->get();

        return view('frontend.experienceDetails.index', compact('experienceDetails'));
    }

    public function create()
    {
        abort_if(Gate::denies('experience_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.experienceDetails.create', compact('names'));
    }

    public function store(StoreExperienceDetailRequest $request)
    {
        $experienceDetail = ExperienceDetail::create($request->all());

        return redirect()->route('frontend.experience-details.index');
    }

    public function edit(ExperienceDetail $experienceDetail)
    {
        abort_if(Gate::denies('experience_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $experienceDetail->load('name');

        return view('frontend.experienceDetails.edit', compact('experienceDetail', 'names'));
    }

    public function update(UpdateExperienceDetailRequest $request, ExperienceDetail $experienceDetail)
    {
        $experienceDetail->update($request->all());

        return redirect()->route('frontend.experience-details.index');
    }

    public function show(ExperienceDetail $experienceDetail)
    {
        abort_if(Gate::denies('experience_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $experienceDetail->load('name');

        return view('frontend.experienceDetails.show', compact('experienceDetail'));
    }

    public function destroy(ExperienceDetail $experienceDetail)
    {
        abort_if(Gate::denies('experience_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $experienceDetail->delete();

        return back();
    }

    public function massDestroy(MassDestroyExperienceDetailRequest $request)
    {
        $experienceDetails = ExperienceDetail::find(request('ids'));

        foreach ($experienceDetails as $experienceDetail) {
            $experienceDetail->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
