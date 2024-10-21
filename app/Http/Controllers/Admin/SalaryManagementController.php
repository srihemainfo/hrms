<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staffs;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SalaryManagementController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $query = Staffs::select(sprintf('%s.*', (new Staffs)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'salary_management_show';
                $editGate = 'salary_management_edit';
                // $deleteGate = 'salary_management_delete';
                $editFunct = 'editSalaryManagement';
                $viewFunct = 'viewSalaryManagement';
                $deleteFunct = 'deleteSalaryManagement';
                $crudRoutePart = 'SalaryManagement';

                return view('partials.ajaxTableActions', compact(
                    'viewGate',
                    'editGate',
                    // 'deleteGate',
                    'editFunct',
                    'viewFunct',
                    'deleteFunct',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->editColumn('emplopyee_id', function ($row) {
                return $row->emplopyee_id ? $row->emplopyee_id : '';
            });

            $table->editColumn('basicPay', function ($row) {
                return $row->basicPay ? $row->basicPay : 0;
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.salaryManagement.index');
    }

    public function store(Request $request)
    {
        if (isset($request->basicPay)) {
            if ($request->id != '') {
                $count = Staffs::whereNotIn('id', [$request->id])->where(['basicPay' => $request->basicPay])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Monthly Salary Already Exist.']);
                } else {

                    $update = Staffs::where(['id' => $request->id])->update([
                        'basicPay' => $request->basicPay,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Monthly Salary Updated']);

            }
        } else {
            return response()->json(['status' => false, 'data' => 'Monthly Salary Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = Staffs::where(['id' => $request->id])->select('id', 'basicPay')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = Staffs::where(['id' => $request->id])->select('id', 'basicPay')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

}
