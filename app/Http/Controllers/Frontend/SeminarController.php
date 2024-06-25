<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySeminarRequest;
use App\Http\Requests\StoreSeminarRequest;
use App\Http\Requests\UpdateSeminarRequest;
use App\Models\Seminar;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SeminarController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('seminar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $seminars = Seminar::with(['user_name'])->get();

        return view('frontend.seminars.index', compact('seminars'));
    }

    public function create()
    {
        abort_if(Gate::denies('seminar_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.seminars.create', compact('user_names'));
    }

    public function store(StoreSeminarRequest $request)
    {
        $seminar = Seminar::create($request->all());

        return redirect()->route('frontend.seminars.index');
    }

    public function edit(Seminar $seminar)
    {
        abort_if(Gate::denies('seminar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $seminar->load('user_name');

        return view('frontend.seminars.edit', compact('seminar', 'user_names'));
    }

    public function update(UpdateSeminarRequest $request, Seminar $seminar)
    {
        $seminar->update($request->all());

        return redirect()->route('frontend.seminars.index');
    }

    public function show(Seminar $seminar)
    {
        abort_if(Gate::denies('seminar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $seminar->load('user_name');

        return view('frontend.seminars.show', compact('seminar'));
    }

    public function destroy(Seminar $seminar)
    {
        abort_if(Gate::denies('seminar_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $seminar->delete();

        return back();
    }

    public function massDestroy(MassDestroySeminarRequest $request)
    {
        $seminars = Seminar::find(request('ids'));

        foreach ($seminars as $seminar) {
            $seminar->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
