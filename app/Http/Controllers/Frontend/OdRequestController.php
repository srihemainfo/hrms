<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyOdRequestRequest;
use App\Http\Requests\StoreOdRequestRequest;
use App\Http\Requests\UpdateOdRequestRequest;
use App\Models\OdRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OdRequestController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('od_request_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $odRequests = OdRequest::all();

        return view('frontend.odRequests.index', compact('odRequests'));
    }

    public function create()
    {
        abort_if(Gate::denies('od_request_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.odRequests.create');
    }

    public function store(StoreOdRequestRequest $request)
    {
        $odRequest = OdRequest::create($request->all());

        return redirect()->route('frontend.od-requests.index');
    }

    public function edit(OdRequest $odRequest)
    {
        abort_if(Gate::denies('od_request_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.odRequests.edit', compact('odRequest'));
    }

    public function update(UpdateOdRequestRequest $request, OdRequest $odRequest)
    {
        $odRequest->update($request->all());

        return redirect()->route('frontend.od-requests.index');
    }

    public function show(OdRequest $odRequest)
    {
        abort_if(Gate::denies('od_request_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.odRequests.show', compact('odRequest'));
    }

    public function destroy(OdRequest $odRequest)
    {
        abort_if(Gate::denies('od_request_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $odRequest->delete();

        return back();
    }

    public function massDestroy(MassDestroyOdRequestRequest $request)
    {
        $odRequests = OdRequest::find(request('ids'));

        foreach ($odRequests as $odRequest) {
            $odRequest->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
