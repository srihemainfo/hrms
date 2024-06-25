<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCollegeCalenderRequest;
use App\Http\Requests\StoreCollegeCalenderRequest;
use App\Http\Requests\UpdateCollegeCalenderRequest;
use App\Models\CollegeCalender;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CollegeCalenderController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('college_calender_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $collegeCalenders = CollegeCalender::all();

        return view('frontend.collegeCalenders.index', compact('collegeCalenders'));
    }

    public function create()
    {
        abort_if(Gate::denies('college_calender_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.collegeCalenders.create');
    }

    public function store(StoreCollegeCalenderRequest $request)
    {
        $collegeCalender = CollegeCalender::create($request->all());

        return redirect()->route('frontend.college-calenders.index');
    }

    public function edit(CollegeCalender $collegeCalender)
    {
        abort_if(Gate::denies('college_calender_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.collegeCalenders.edit', compact('collegeCalender'));
    }

    public function update(UpdateCollegeCalenderRequest $request, CollegeCalender $collegeCalender)
    {
        $collegeCalender->update($request->all());

        return redirect()->route('frontend.college-calenders.index');
    }

    public function show(CollegeCalender $collegeCalender)
    {
        abort_if(Gate::denies('college_calender_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.collegeCalenders.show', compact('collegeCalender'));
    }

    public function destroy(CollegeCalender $collegeCalender)
    {
        abort_if(Gate::denies('college_calender_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $collegeCalender->delete();

        return back();
    }

    public function massDestroy(MassDestroyCollegeCalenderRequest $request)
    {
        $collegeCalenders = CollegeCalender::find(request('ids'));

        foreach ($collegeCalenders as $collegeCalender) {
            $collegeCalender->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
