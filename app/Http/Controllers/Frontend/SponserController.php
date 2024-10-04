<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySponserRequest;
use App\Http\Requests\StoreSponserRequest;
use App\Http\Requests\UpdateSponserRequest;
use App\Models\Sponser;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SponserController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('sponser_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sponsers = Sponser::with(['user_name'])->get();

        return view('frontend.sponsers.index', compact('sponsers'));
    }

    public function create()
    {
        abort_if(Gate::denies('sponser_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.sponsers.create', compact('user_names'));
    }

    public function store(StoreSponserRequest $request)
    {
        $sponser = Sponser::create($request->all());

        return redirect()->route('frontend.sponsers.index');
    }

    public function edit(Sponser $sponser)
    {
        abort_if(Gate::denies('sponser_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sponser->load('user_name');

        return view('frontend.sponsers.edit', compact('sponser', 'user_names'));
    }

    public function update(UpdateSponserRequest $request, Sponser $sponser)
    {
        $sponser->update($request->all());

        return redirect()->route('frontend.sponsers.index');
    }

    public function show(Sponser $sponser)
    {
        abort_if(Gate::denies('sponser_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sponser->load('user_name');

        return view('frontend.sponsers.show', compact('sponser'));
    }

    public function destroy(Sponser $sponser)
    {
        abort_if(Gate::denies('sponser_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sponser->delete();

        return back();
    }

    public function massDestroy(MassDestroySponserRequest $request)
    {
        $sponsers = Sponser::find(request('ids'));

        foreach ($sponsers as $sponser) {
            $sponser->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
