<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PayslipRequest extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'Pending'); // Default to 'Pending' if no status is provided

        if ($request->ajax()) {
            $query = DB::table('payslip_request')
                ->leftJoin('users', 'payslip_request.user_name_id', '=', 'users.id')
                ->select('payslip_request.*', 'users.name as user_name')
                ->where('payslip_request.status', $status); // Filter by status

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });

            $table->editColumn('user_name', function ($row) {
                return $row->user_name ? $row->user_name : '';
            });

            $table->editColumn('month', function ($row) {
                return $row->month ? $row->month : '';
            });

            $table->editColumn('year', function ($row) {
                return $row->year ? $row->year : '';
            });

            $table->editColumn('reason', function ($row) {
                return $row->reason ? $row->reason : '';
            });

            $table->addColumn('actions', function ($row) {
                if ($row->status === 'Approved') {
                    return '<span class="text-success">Approved</span>';
                } elseif ($row->status === 'Rejected') {
                    return '<span class="text-danger">Rejected</span>';
                } else {
                    $approveBtn = '<a href="#" class="btn btn-sm btn-success" onclick="approveRequest(' . $row->id . ')">Approve</a>';
                    $rejectBtn = '<a href="#" class="btn btn-sm btn-danger" onclick="rejectRequest(' . $row->id . ')">Reject</a>';
                    return $approveBtn . ' ' . $rejectBtn;
                }
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.payslipRequest.index', compact('status'));
    }

    public function approve(Request $request)
    {
        if (isset($request->id)) {
            // Update the status in the database
            DB::table('payslip_request')
                ->where('id', $request->id)
                ->update(['status' => $request->status]);

            return response()->json(['status' => true, 'data' => 'Payslip Request Updated']);
        } else {
            // Return failure response
            return response()->json(['status' => false, 'data' => 'Payslip Request Not Updated']);
        }
    }

    public function reject(Request $request)
    {
        if (isset($request->id)) {
            // Update the status in the database
            DB::table('payslip_request')
                ->where('id', $request->id)
                ->update(['status' => $request->status]);

            return response()->json(['status' => true, 'data' => 'Payslip Request Updated']);
        } else {
            // Return failure response
            return response()->json(['status' => false, 'data' => 'Payslip Request Not Updated']);
        }
    }

}
