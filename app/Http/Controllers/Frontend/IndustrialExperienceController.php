<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyIndustrialExperienceRequest;
use App\Http\Requests\StoreIndustrialExperienceRequest;
use App\Http\Requests\UpdateIndustrialExperienceRequest;
use App\Models\IndustrialExperience;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IndustrialExperienceController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('industrial_experience_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $industrialExperiences = IndustrialExperience::with(['user_name'])->get();

        return view('frontend.industrialExperiences.index', compact('industrialExperiences'));
    }

    public function create()
    {
        abort_if(Gate::denies('industrial_experience_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.industrialExperiences.create', compact('user_names'));
    }

    public function store(StoreIndustrialExperienceRequest $request)
    {
        $industrialExperience = IndustrialExperience::create($request->all());

        return redirect()->route('frontend.industrial-experiences.index');
    }

    public function edit(IndustrialExperience $industrialExperience)
    {
        abort_if(Gate::denies('industrial_experience_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $industrialExperience->load('user_name');

        return view('frontend.industrialExperiences.edit', compact('industrialExperience', 'user_names'));
    }

    public function update(UpdateIndustrialExperienceRequest $request, IndustrialExperience $industrialExperience)
    {
        $industrialExperience->update($request->all());

        return redirect()->route('frontend.industrial-experiences.index');
    }

    public function show(IndustrialExperience $industrialExperience)
    {
        abort_if(Gate::denies('industrial_experience_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $industrialExperience->load('user_name');

        return view('frontend.industrialExperiences.show', compact('industrialExperience'));
    }

    public function destroy(IndustrialExperience $industrialExperience)
    {
        abort_if(Gate::denies('industrial_experience_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $industrialExperience->delete();

        return back();
    }

    public function massDestroy(MassDestroyIndustrialExperienceRequest $request)
    {
        $industrialExperiences = IndustrialExperience::find(request('ids'));

        foreach ($industrialExperiences as $industrialExperience) {
            $industrialExperience->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
