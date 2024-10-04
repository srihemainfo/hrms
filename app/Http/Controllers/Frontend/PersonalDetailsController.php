<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyPersonalDetailRequest;
use App\Http\Requests\StorePersonalDetailRequest;
use App\Http\Requests\UpdatePersonalDetailRequest;
use App\Models\BloodGroup;
use App\Models\Community;
use App\Models\MotherTongue;
use App\Models\PersonalDetail;
use App\Models\Religion;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PersonalDetailsController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('personal_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $personalDetails = PersonalDetail::with(['user_name', 'blood_group', 'mother_tongue', 'religion', 'community'])->get();

        return view('frontend.personalDetails.index', compact('personalDetails'));
    }

    public function create()
    {
        abort_if(Gate::denies('personal_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $blood_groups = BloodGroup::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mother_tongues = MotherTongue::pluck('mother_tongue', 'id')->prepend(trans('global.pleaseSelect'), '');

        $religions = Religion::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $communities = Community::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.personalDetails.create', compact('blood_groups', 'communities', 'mother_tongues', 'religions', 'user_names'));
    }

    public function store(StorePersonalDetailRequest $request)
    {
        $personalDetail = PersonalDetail::create($request->all());

        return redirect()->route('frontend.personal-details.index');
    }

    public function edit(PersonalDetail $personalDetail)
    {
        abort_if(Gate::denies('personal_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $blood_groups = BloodGroup::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mother_tongues = MotherTongue::pluck('mother_tongue', 'id')->prepend(trans('global.pleaseSelect'), '');

        $religions = Religion::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $communities = Community::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $personalDetail->load('user_name', 'blood_group', 'mother_tongue', 'religion', 'community');

        return view('frontend.personalDetails.edit', compact('blood_groups', 'communities', 'mother_tongues', 'personalDetail', 'religions', 'user_names'));
    }

    public function update(UpdatePersonalDetailRequest $request, PersonalDetail $personalDetail)
    {
        $personalDetail->update($request->all());

        return redirect()->route('frontend.personal-details.index');
    }

    public function show(PersonalDetail $personalDetail)
    {
        abort_if(Gate::denies('personal_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $personalDetail->load('user_name', 'blood_group', 'mother_tongue', 'religion', 'community');

        return view('frontend.personalDetails.show', compact('personalDetail'));
    }

    public function destroy(PersonalDetail $personalDetail)
    {
        abort_if(Gate::denies('personal_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $personalDetail->delete();

        return back();
    }

    public function massDestroy(MassDestroyPersonalDetailRequest $request)
    {
        $personalDetails = PersonalDetail::find(request('ids'));

        foreach ($personalDetails as $personalDetail) {
            $personalDetail->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
