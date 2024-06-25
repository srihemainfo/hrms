<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySttpRequest;
use App\Http\Requests\StoreSttpRequest;
use App\Http\Requests\UpdateSttpRequest;
use App\Models\Sttp;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SttpController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('sttp_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sttps = Sttp::with(['name'])->get();

        return view('frontend.sttps.index', compact('sttps'));
    }

    public function create()
    {
        abort_if(Gate::denies('sttp_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.sttps.create', compact('names'));
    }

    public function store(StoreSttpRequest $request)
    {
        $sttp = Sttp::create($request->all());

        return redirect()->route('frontend.sttps.index');
    }

    public function edit(Sttp $sttp)
    {
        abort_if(Gate::denies('sttp_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sttp->load('name');

        return view('frontend.sttps.edit', compact('names', 'sttp'));
    }

    public function update(UpdateSttpRequest $request, Sttp $sttp)
    {
        $sttp->update($request->all());

        return redirect()->route('frontend.sttps.index');
    }

    public function show(Sttp $sttp)
    {
        abort_if(Gate::denies('sttp_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sttp->load('name');

        return view('frontend.sttps.show', compact('sttp'));
    }

    public function destroy(Sttp $sttp)
    {
        abort_if(Gate::denies('sttp_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sttp->delete();

        return back();
    }

    public function massDestroy(MassDestroySttpRequest $request)
    {
        $sttps = Sttp::find(request('ids'));

        foreach ($sttps as $sttp) {
            $sttp->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
