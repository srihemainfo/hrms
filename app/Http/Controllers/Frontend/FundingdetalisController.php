<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyFundingdetaliRequest;
use App\Http\Requests\StoreFundingdetaliRequest;
use App\Http\Requests\UpdateFundingdetaliRequest;
use App\Models\Fundingdetali;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FundingdetalisController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('fundingdetali_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $fundingdetalis = Fundingdetali::with(['user_name'])->get();

        return view('frontend.fundingdetalis.index', compact('fundingdetalis'));
    }

    public function create()
    {
        abort_if(Gate::denies('fundingdetali_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.fundingdetalis.create', compact('user_names'));
    }

    public function store(StoreFundingdetaliRequest $request)
    {
        $fundingdetali = Fundingdetali::create($request->all());

        return redirect()->route('frontend.fundingdetalis.index');
    }

    public function edit(Fundingdetali $fundingdetali)
    {
        abort_if(Gate::denies('fundingdetali_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $fundingdetali->load('user_name');

        return view('frontend.fundingdetalis.edit', compact('fundingdetali', 'user_names'));
    }

    public function update(UpdateFundingdetaliRequest $request, Fundingdetali $fundingdetali)
    {
        $fundingdetali->update($request->all());

        return redirect()->route('frontend.fundingdetalis.index');
    }

    public function show(Fundingdetali $fundingdetali)
    {
        abort_if(Gate::denies('fundingdetali_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $fundingdetali->load('user_name');

        return view('frontend.fundingdetalis.show', compact('fundingdetali'));
    }

    public function destroy(Fundingdetali $fundingdetali)
    {
        abort_if(Gate::denies('fundingdetali_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $fundingdetali->delete();

        return back();
    }

    public function massDestroy(MassDestroyFundingdetaliRequest $request)
    {
        $fundingdetalis = Fundingdetali::find(request('ids'));

        foreach ($fundingdetalis as $fundingdetali) {
            $fundingdetali->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
