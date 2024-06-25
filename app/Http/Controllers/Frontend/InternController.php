<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyInternRequest;
use App\Http\Requests\StoreInternRequest;
use App\Http\Requests\UpdateInternRequest;
use App\Models\Intern;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InternController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('intern_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $interns = Intern::with(['name'])->get();

        return view('frontend.interns.index', compact('interns'));
    }

    public function create()
    {
        abort_if(Gate::denies('intern_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.interns.create', compact('names'));
    }

    public function store(StoreInternRequest $request)
    {
        $intern = Intern::create($request->all());

        return redirect()->route('frontend.interns.index');
    }

    public function edit(Intern $intern)
    {
        abort_if(Gate::denies('intern_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $intern->load('name');

        return view('frontend.interns.edit', compact('intern', 'names'));
    }

    public function update(UpdateInternRequest $request, Intern $intern)
    {
        $intern->update($request->all());

        return redirect()->route('frontend.interns.index');
    }

    public function show(Intern $intern)
    {
        abort_if(Gate::denies('intern_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $intern->load('name');

        return view('frontend.interns.show', compact('intern'));
    }

    public function destroy(Intern $intern)
    {
        abort_if(Gate::denies('intern_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $intern->delete();

        return back();
    }

    public function massDestroy(MassDestroyInternRequest $request)
    {
        $interns = Intern::find(request('ids'));

        foreach ($interns as $intern) {
            $intern->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
