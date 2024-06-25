<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyInternshipRequestRequest;
use App\Http\Requests\StoreInternshipRequestRequest;
use App\Http\Requests\UpdateInternshipRequestRequest;
use App\Models\InternshipRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class InternshipRequestController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('internship_request_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = InternshipRequest::query()->select(sprintf('%s.*', (new InternshipRequest)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'internship_request_show';
                $editGate      = 'internship_request_edit';
                $deleteGate    = 'internship_request_delete';
                $crudRoutePart = 'internship-requests';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('user', function ($row) {
                return $row->user ? $row->user : '';
            });
            $table->editColumn('from_date', function ($row) {
                return $row->from_date ? $row->from_date : '';
            });
            $table->editColumn('to_date', function ($row) {
                return $row->to_date ? $row->to_date : '';
            });
            $table->editColumn('level_1_userid', function ($row) {
                return $row->level_1_userid ? $row->level_1_userid : '';
            });
            $table->editColumn('level_2_userid', function ($row) {
                return $row->level_2_userid ? $row->level_2_userid : '';
            });
            $table->editColumn('level_3_userid', function ($row) {
                return $row->level_3_userid ? $row->level_3_userid : '';
            });
            $table->editColumn('approved_by', function ($row) {
                return $row->approved_by ? $row->approved_by : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.internshipRequests.index');
    }

    public function create()
    {
        abort_if(Gate::denies('internship_request_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.internshipRequests.create');
    }

    public function store(StoreInternshipRequestRequest $request)
    {
        $internshipRequest = InternshipRequest::create($request->all());

        return redirect()->route('admin.internship-requests.index');
    }

    public function edit(InternshipRequest $internshipRequest)
    {
        abort_if(Gate::denies('internship_request_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.internshipRequests.edit', compact('internshipRequest'));
    }

    public function update(UpdateInternshipRequestRequest $request, InternshipRequest $internshipRequest)
    {
        $internshipRequest->update($request->all());

        return redirect()->route('admin.internship-requests.index');
    }

    public function show(InternshipRequest $internshipRequest)
    {
        abort_if(Gate::denies('internship_request_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.internshipRequests.show', compact('internshipRequest'));
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
