<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyIvRequest;
use App\Http\Requests\StoreIvRequest;
use App\Http\Requests\UpdateIvRequest;
use App\Models\Iv;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IvController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('iv_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ivs = Iv::with(['name'])->get();

        return view('frontend.ivs.index', compact('ivs'));
    }

    public function create()
    {
        abort_if(Gate::denies('iv_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.ivs.create', compact('names'));
    }

    public function store(StoreIvRequest $request)
    {
        $iv = Iv::create($request->all());

        return redirect()->route('frontend.ivs.index');
    }

    public function edit(Iv $iv)
    {
        abort_if(Gate::denies('iv_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $iv->load('name');

        return view('frontend.ivs.edit', compact('iv', 'names'));
    }

    public function update(UpdateIvRequest $request, Iv $iv)
    {
        $iv->update($request->all());

        return redirect()->route('frontend.ivs.index');
    }

    public function show(Iv $iv)
    {
        abort_if(Gate::denies('iv_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $iv->load('name');

        return view('frontend.ivs.show', compact('iv'));
    }

    public function destroy(Iv $iv)
    {
        abort_if(Gate::denies('iv_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $iv->delete();

        return back();
    }

    public function massDestroy(MassDestroyIvRequest $request)
    {
        $ivs = Iv::find(request('ids'));

        foreach ($ivs as $iv) {
            $iv->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
