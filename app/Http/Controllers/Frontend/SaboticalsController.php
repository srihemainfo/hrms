<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySaboticalRequest;
use App\Http\Requests\StoreSaboticalRequest;
use App\Http\Requests\UpdateSaboticalRequest;
use App\Models\Sabotical;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SaboticalsController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('sabotical_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $saboticals = Sabotical::with(['name'])->get();

        return view('frontend.saboticals.index', compact('saboticals'));
    }

    public function create()
    {
        abort_if(Gate::denies('sabotical_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.saboticals.create', compact('names'));
    }

    public function store(StoreSaboticalRequest $request)
    {
        $sabotical = Sabotical::create($request->all());

        return redirect()->route('frontend.saboticals.index');
    }

    public function edit(Sabotical $sabotical)
    {
        abort_if(Gate::denies('sabotical_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sabotical->load('name');

        return view('frontend.saboticals.edit', compact('names', 'sabotical'));
    }

    public function update(UpdateSaboticalRequest $request, Sabotical $sabotical)
    {
        $sabotical->update($request->all());

        return redirect()->route('frontend.saboticals.index');
    }

    public function show(Sabotical $sabotical)
    {
        abort_if(Gate::denies('sabotical_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sabotical->load('name');

        return view('frontend.saboticals.show', compact('sabotical'));
    }

    public function destroy(Sabotical $sabotical)
    {
        abort_if(Gate::denies('sabotical_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sabotical->delete();

        return back();
    }

    public function massDestroy(MassDestroySaboticalRequest $request)
    {
        $saboticals = Sabotical::find(request('ids'));

        foreach ($saboticals as $sabotical) {
            $sabotical->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
