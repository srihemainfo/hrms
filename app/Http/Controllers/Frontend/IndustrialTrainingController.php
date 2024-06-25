<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyIndustrialTrainingRequest;
use App\Http\Requests\StoreIndustrialTrainingRequest;
use App\Http\Requests\UpdateIndustrialTrainingRequest;
use App\Models\IndustrialTraining;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IndustrialTrainingController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('industrial_training_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $industrialTrainings = IndustrialTraining::with(['name'])->get();

        return view('frontend.industrialTrainings.index', compact('industrialTrainings'));
    }

    public function create()
    {
        abort_if(Gate::denies('industrial_training_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.industrialTrainings.create', compact('names'));
    }

    public function store(StoreIndustrialTrainingRequest $request)
    {
        $industrialTraining = IndustrialTraining::create($request->all());

        return redirect()->route('frontend.industrial-trainings.index');
    }

    public function edit(IndustrialTraining $industrialTraining)
    {
        abort_if(Gate::denies('industrial_training_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $industrialTraining->load('name');

        return view('frontend.industrialTrainings.edit', compact('industrialTraining', 'names'));
    }

    public function update(UpdateIndustrialTrainingRequest $request, IndustrialTraining $industrialTraining)
    {
        $industrialTraining->update($request->all());

        return redirect()->route('frontend.industrial-trainings.index');
    }

    public function show(IndustrialTraining $industrialTraining)
    {
        abort_if(Gate::denies('industrial_training_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $industrialTraining->load('name');

        return view('frontend.industrialTrainings.show', compact('industrialTraining'));
    }

    public function destroy(IndustrialTraining $industrialTraining)
    {
        abort_if(Gate::denies('industrial_training_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $industrialTraining->delete();

        return back();
    }

    public function massDestroy(MassDestroyIndustrialTrainingRequest $request)
    {
        $industrialTrainings = IndustrialTraining::find(request('ids'));

        foreach ($industrialTrainings as $industrialTraining) {
            $industrialTraining->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
