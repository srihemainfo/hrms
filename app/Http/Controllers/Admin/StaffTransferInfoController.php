<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyStaffTransferInfoRequest;
use App\Http\Requests\StoreStaffTransferInfoRequest;
use App\Http\Requests\UpdateStaffTransferInfoRequest;
use App\Models\CourseEnrollMaster;
use App\Models\StaffTransferInfo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class StaffTransferInfoController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('staff_transfer_info_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = StaffTransferInfo::with(['enroll_master'])->select(sprintf('%s.*', (new StaffTransferInfo)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'staff_transfer_info_show';
                $editGate      = 'staff_transfer_info_edit';
                $deleteGate    = 'staff_transfer_info_delete';
                $crudRoutePart = 'staff-transfer-infos';

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
            $table->addColumn('enroll_master_enroll_master_number', function ($row) {
                return $row->enroll_master ? $row->enroll_master->enroll_master_number : '';
            });

            $table->editColumn('enroll_master.deletes', function ($row) {
                return $row->enroll_master ? (is_string($row->enroll_master) ? $row->enroll_master : $row->enroll_master->deletes) : '';
            });
            $table->editColumn('period', function ($row) {
                return $row->period ? $row->period : '';
            });
            $table->editColumn('from_user', function ($row) {
                return $row->from_user ? $row->from_user : '';
            });
            $table->editColumn('to_user', function ($row) {
                return $row->to_user ? $row->to_user : '';
            });
            $table->editColumn('transfer_date', function ($row) {
                return $row->transfer_date ? $row->transfer_date : '';
            });
            $table->editColumn('approved_by_user', function ($row) {
                return $row->approved_by_user ? $row->approved_by_user : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'enroll_master']);

            return $table->make(true);
        }

        return view('admin.staffTransferInfos.index');
    }

    public function create()
    {
        abort_if(Gate::denies('staff_transfer_info_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $enroll_masters = CourseEnrollMaster::pluck('enroll_master_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.staffTransferInfos.create', compact('enroll_masters'));
    }

    public function store(StoreStaffTransferInfoRequest $request)
    {
        $staffTransferInfo = StaffTransferInfo::create($request->all());

        return redirect()->route('admin.staff-transfer-infos.index');
    }

    public function edit(StaffTransferInfo $staffTransferInfo)
    {
        abort_if(Gate::denies('staff_transfer_info_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $enroll_masters = CourseEnrollMaster::pluck('enroll_master_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $staffTransferInfo->load('enroll_master');

        return view('admin.staffTransferInfos.edit', compact('enroll_masters', 'staffTransferInfo'));
    }

    public function update(UpdateStaffTransferInfoRequest $request, StaffTransferInfo $staffTransferInfo)
    {
        $staffTransferInfo->update($request->all());

        return redirect()->route('admin.staff-transfer-infos.index');
    }

    public function show(StaffTransferInfo $staffTransferInfo)
    {
        abort_if(Gate::denies('staff_transfer_info_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staffTransferInfo->load('enroll_master');

        return view('admin.staffTransferInfos.show', compact('staffTransferInfo'));
    }

    public function destroy(StaffTransferInfo $staffTransferInfo)
    {
        abort_if(Gate::denies('staff_transfer_info_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staffTransferInfo->delete();

        return back();
    }

    public function massDestroy(MassDestroyStaffTransferInfoRequest $request)
    {
        $staffTransferInfos = StaffTransferInfo::find(request('ids'));

        foreach ($staffTransferInfos as $staffTransferInfo) {
            $staffTransferInfo->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
