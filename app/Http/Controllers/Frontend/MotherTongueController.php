<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyMotherTongueRequest;
use App\Http\Requests\StoreMotherTongueRequest;
use App\Http\Requests\UpdateMotherTongueRequest;
use App\Models\MotherTongue;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MotherTongueController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('mother_tongue_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $motherTongues = MotherTongue::all();

        return view('frontend.motherTongues.index', compact('motherTongues'));
    }

    public function create()
    {
        abort_if(Gate::denies('mother_tongue_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.motherTongues.create');
    }

    public function store(StoreMotherTongueRequest $request)
    {
        $motherTongue = MotherTongue::create($request->all());

        return redirect()->route('frontend.mother-tongues.index');
    }

    public function edit(MotherTongue $motherTongue)
    {
        abort_if(Gate::denies('mother_tongue_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.motherTongues.edit', compact('motherTongue'));
    }

    public function update(UpdateMotherTongueRequest $request, MotherTongue $motherTongue)
    {
        $motherTongue->update($request->all());

        return redirect()->route('frontend.mother-tongues.index');
    }

    public function show(MotherTongue $motherTongue)
    {
        abort_if(Gate::denies('mother_tongue_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.motherTongues.show', compact('motherTongue'));
    }

    public function destroy(MotherTongue $motherTongue)
    {
        abort_if(Gate::denies('mother_tongue_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $motherTongue->delete();

        return back();
    }

    public function massDestroy(MassDestroyMotherTongueRequest $request)
    {
        $motherTongues = MotherTongue::find(request('ids'));

        foreach ($motherTongues as $motherTongue) {
            $motherTongue->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
