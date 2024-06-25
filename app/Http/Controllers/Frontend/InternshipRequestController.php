<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyInternshipRequestRequest;
use App\Http\Requests\StoreInternshipRequestRequest;
use App\Http\Requests\UpdateInternshipRequestRequest;
use App\Models\InternshipRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InternshipRequestController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('internship_request_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $internshipRequests = InternshipRequest::all();

        return view('frontend.internshipRequests.index', compact('internshipRequests'));
    }

    public function create()
    {
        abort_if(Gate::denies('internship_request_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.internshipRequests.create');
    }

    public function store(StoreInternshipRequestRequest $request)
    {
        $internshipRequest = InternshipRequest::create($request->all());

        return redirect()->route('frontend.internship-requests.index');
    }

    public function edit(InternshipRequest $internshipRequest)
    {
        abort_if(Gate::denies('internship_request_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.internshipRequests.edit', compact('internshipRequest'));
    }

    public function update(UpdateInternshipRequestRequest $request, InternshipRequest $internshipRequest)
    {
        $internshipRequest->update($request->all());

        return redirect()->route('frontend.internship-requests.index');
    }

    public function show(InternshipRequest $internshipRequest)
    {
        abort_if(Gate::denies('internship_request_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.internshipRequests.show', compact('internshipRequest'));
    }

    public function destroy(InternshipRequest $internshipRequest)
    {
        abort_if(Gate::denies('internship_request_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $internshipRequest->delete();

        return back();
    }

    public function massDestroy(MassDestroyInternshipRequestRequest $request)
    {
        $internshipRequests = InternshipRequest::find(request('ids'));

        foreach ($internshipRequests as $internshipRequest) {
            $internshipRequest->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
