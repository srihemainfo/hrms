<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayslipRequest;
use App\Models\Staffs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StaffPayslip extends Controller
{
    public function index(Request $request)
    {

        $user = auth()->user();
        // dd($user);
        if ($user) {
            $userId = $user->id;
            $employeeName = $user->name;
            $employeeID = $user->employee_id;

            // Query the biometric record
            $biometric = Staffs::where('user_name_id', $userId)->first();
            if ($biometric) {
                $biometricId = $biometric->biometric;
            } else {
                // Handle case where no biometric record is found
                $biometricId = null;  // or set a default value if needed
            }
        }


        $previousMonth = Carbon::now()->subMonth()->format('F');
        $currentYear = Carbon::now()->year;
        // $updated_at = DB::table('payslip')
        //     ->where('user_name_id', $userId)
        //     ->where('month', $previousMonth)
        //     ->where('year', $currentYear)
        //     ->value('updated_at');

        // $updated_at_carbon = Carbon::parse($updated_at);
        // $add_one_day = $updated_at_carbon->addHours(24);

        if ($request->ajax()) {

            // if ($add_one_day === null || $add_one_day->lessThan(Carbon::now())) {
            //     return Datatables::of(collect([]))->make(true);
            // } else {
            $query = DB::table('payslip')
                ->where('user_name_id', $userId)
                ->where('month', $previousMonth)
                ->where('year', $currentYear)
                ->select('*');

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->editColumn('month', function ($row) {
                return $row->month ? $row->month : '';
            });

            $table->editColumn('year', function ($row) {
                return $row->year ? $row->year : '';
            });

            $table->editColumn('netpay', function ($row) {
                return $row->netpay ? $row->netpay : '';
            });

            $table->addColumn('actions', function ($row) {
                $editLink = '<a href="' . url('admin/PaySlip/edit/' . $row->id) . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i>Preview</a>';
                return $editLink;
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
            // }

        }

        return view('admin.staffPayslip.index', compact('employeeName', 'employeeID', 'biometricId'));
    }

    public function reqs(Request $request)
    {
        // dd($request);
        if (isset($request->user_name_id)) {
            $store = PayslipRequest::create([
                'user_name_id' => $request->user_name_id,
                'year' => $request->year,
                'month' => $request->json_month,
                'reason' => $request->reason,
                'status' => 'Pending',
            ]);
            return response()->json(['status' => true, 'data' => 'Request Sent Successfully']);
        } else {
            return response()->json(['status' => false, 'data' => 'Request Failed!']);
        }
    }

    // public function prereq(Request $request)
    // {
    //     $user = auth()->user();
    //     if ($request->ajax())
    //     {

    //     }
    // }
}
