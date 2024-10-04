<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyNationalityRequest;
use App\Http\Requests\StoreNationalityRequest;
use App\Http\Requests\UpdateNationalityRequest;
use App\Models\Nationality;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NationalityController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('nationality_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $nationalities = Nationality::all();

        return view('frontend.nationalities.index', compact('nationalities'));
    }

    public function create()
    {
        abort_if(Gate::denies('nationality_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.nationalities.create');
    }

    public function store(StoreNationalityRequest $request)
    {
        $nationality = Nationality::create($request->all());

        return redirect()->route('frontend.nationalities.index');
    }

    public function edit(Nationality $nationality)
    {
        abort_if(Gate::denies('nationality_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.nationalities.edit', compact('nationality'));
    }

    public function update(UpdateNationalityRequest $request, Nationality $nationality)
    {
        $nationality->update($request->all());

        return redirect()->route('frontend.nationalities.index');
    }

    public function show(Nationality $nationality)
    {
        abort_if(Gate::denies('nationality_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.nationalities.show', compact('nationality'));
    }

    public function destroy(Nationality $nationality)
    {
        abort_if(Gate::denies('nationality_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $nationality->delete();

        return back();
    }

    public function massDestroy(MassDestroyNationalityRequest $request)
    {
        $nationalities = Nationality::find(request('ids'));

        foreach ($nationalities as $nationality) {
            $nationality->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
