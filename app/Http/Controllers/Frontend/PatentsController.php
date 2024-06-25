<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPatentRequest;
use App\Http\Requests\StorePatentRequest;
use App\Http\Requests\UpdatePatentRequest;
use App\Models\Patent;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PatentsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('patent_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patents = Patent::with(['name'])->get();

        return view('frontend.patents.index', compact('patents'));
    }

    public function create()
    {
        abort_if(Gate::denies('patent_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.patents.create', compact('names'));
    }

    public function store(StorePatentRequest $request)
    {
        $patent = Patent::create($request->all());

        return redirect()->route('frontend.patents.index');
    }

    public function edit(Patent $patent)
    {
        abort_if(Gate::denies('patent_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $patent->load('name');

        return view('frontend.patents.edit', compact('names', 'patent'));
    }

    public function update(UpdatePatentRequest $request, Patent $patent)
    {
        $patent->update($request->all());

        return redirect()->route('frontend.patents.index');
    }

    public function show(Patent $patent)
    {
        abort_if(Gate::denies('patent_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patent->load('name');

        return view('frontend.patents.show', compact('patent'));
    }

    public function destroy(Patent $patent)
    {
        abort_if(Gate::denies('patent_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patent->delete();

        return back();
    }

    public function massDestroy(MassDestroyPatentRequest $request)
    {
        $patents = Patent::find(request('ids'));

        foreach ($patents as $patent) {
            $patent->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
