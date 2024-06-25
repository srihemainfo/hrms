<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyParentDetailRequest;
use App\Http\Requests\StoreParentDetailRequest;
use App\Http\Requests\UpdateParentDetailRequest;
use App\Models\ParentDetail;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ParentDetailsController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('parent_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $parentDetails = ParentDetail::all();

        return view('frontend.parentDetails.index', compact('parentDetails'));
    }

    public function create()
    {
        abort_if(Gate::denies('parent_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.parentDetails.create');
    }

    public function store(StoreParentDetailRequest $request)
    {
        $parentDetail = ParentDetail::create($request->all());

        return redirect()->route('frontend.parent-details.index');
    }

    public function edit(ParentDetail $parentDetail)
    {
        abort_if(Gate::denies('parent_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.parentDetails.edit', compact('parentDetail'));
    }

    public function update(UpdateParentDetailRequest $request, ParentDetail $parentDetail)
    {
        $parentDetail->update($request->all());

        return redirect()->route('frontend.parent-details.index');
    }

    public function show(ParentDetail $parentDetail)
    {
        abort_if(Gate::denies('parent_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.parentDetails.show', compact('parentDetail'));
    }

    public function destroy(ParentDetail $parentDetail)
    {
        abort_if(Gate::denies('parent_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $parentDetail->delete();

        return back();
    }

    public function massDestroy(MassDestroyParentDetailRequest $request)
    {
        $parentDetails = ParentDetail::find(request('ids'));

        foreach ($parentDetails as $parentDetail) {
            $parentDetail->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
