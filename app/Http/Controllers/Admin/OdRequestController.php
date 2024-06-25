<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyOdRequestRequest;
use App\Http\Requests\StoreOdRequestRequest;
use App\Http\Requests\UpdateOdRequestRequest;
use App\Models\OdRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class OdRequestController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('od_request_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = OdRequest::query()->select(sprintf('%s.*', (new OdRequest)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'od_request_show';
                $editGate      = 'od_request_edit';
                $deleteGate    = 'od_request_delete';
                $crudRoutePart = 'od-requests';

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

        return view('admin.odRequests.index');
    }

    public function create()
    {
        abort_if(Gate::denies('od_request_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.odRequests.create');
    }

    public function store(StoreOdRequestRequest $request)
    {
        $odRequest = OdRequest::create($request->all());

        return redirect()->route('admin.od-requests.index');
    }

    public function edit(OdRequest $odRequest)
    {
        abort_if(Gate::denies('od_request_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.odRequests.edit', compact('odRequest'));
    }

    public function update(UpdateOdRequestRequest $request, OdRequest $odRequest)
    {
        $odRequest->update($request->all());

        return redirect()->route('admin.od-requests.index');
    }

    public function show(OdRequest $odRequest)
    {
        abort_if(Gate::denies('od_request_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.odRequests.show', compact('odRequest'));
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
