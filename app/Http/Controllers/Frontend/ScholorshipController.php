<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyScholorshipRequest;
use App\Http\Requests\StoreScholorshipRequest;
use App\Http\Requests\UpdateScholorshipRequest;
use App\Models\Scholorship;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ScholorshipController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('scholorship_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $scholorships = Scholorship::all();

        return view('frontend.scholorships.index', compact('scholorships'));
    }

    public function create()
    {
        abort_if(Gate::denies('scholorship_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.scholorships.create');
    }

    public function store(StoreScholorshipRequest $request)
    {
        $scholorship = Scholorship::create($request->all());

        return redirect()->route('frontend.scholorships.index');
    }

    public function edit(Scholorship $scholorship)
    {
        abort_if(Gate::denies('scholorship_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.scholorships.edit', compact('scholorship'));
    }

    public function update(UpdateScholorshipRequest $request, Scholorship $scholorship)
    {
        $scholorship->update($request->all());

        return redirect()->route('frontend.scholorships.index');
    }

    public function show(Scholorship $scholorship)
    {
        abort_if(Gate::denies('scholorship_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.scholorships.show', compact('scholorship'));
    }

    public function destroy(Scholorship $scholorship)
    {
        abort_if(Gate::denies('scholorship_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $scholorship->delete();

        return back();
    }

    public function massDestroy(MassDestroyScholorshipRequest $request)
    {
        $scholorships = Scholorship::find(request('ids'));

        foreach ($scholorships as $scholorship) {
            $scholorship->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
