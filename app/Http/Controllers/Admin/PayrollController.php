<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Designation;
use Illuminate\Support\Facades\DB;


class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $designations = Designation::pluck('name', 'id');
        return view('admin.payroll.index',compact('designations'));
    }

    public function payrollData(Request $request)
    {
        // dd($request);
        if (isset($request->year) && $request->month != 'All' && $request->designation != 'All')
        {
            $datas = DB::table('salarystatements')
            ->where('designation', $request->designation)
            ->where('month', $request->month)
            ->where('year', $request->year)->get();

            if($datas->count()  > 0)
            {
                return response()->json(['status' => true , 'data'  => $datas]);
            }
            else
            {
                return response()->json(['status'  => false , 'data'  => 'No Records Found']);
            }
        }
        elseif (isset($request->year) && $request->month != 'All' && $request->designation == 'All')
        {

            $datas = DB::table('salarystatements')
            ->where('month', $request->month)
            ->where('year', $request->year)->get();
            if($datas->count()  > 0)
            {
                return response()->json(['status' => true , 'data'  => $datas]);
            }
            else
            {
                return response()->json(['status'  => false , 'data'  => 'No Records Found']);
            }

        }
        elseif (isset($request->year) && $request->month == 'All' && $request->designation != 'All')
        {
            $datas = DB::table('salarystatements')
            ->where('designation', $request->designation)
            ->where('year', $request->year)->get();
            if($datas->count()  > 0)
            {
                return response()->json(['status' => true , 'data'  => $datas]);
            }
            else
            {
                return response()->json(['status'  => false , 'data'  => 'No Records Found']);
            }

        }
        elseif (isset($request->year) && $request->month == 'All' && $request->designation == 'All')
        {
            $datas = DB::table('salarystatements')
            ->where('year', $request->year)->get();
            if($datas->count()  > 0)
            {
                return response()->json(['status' => true , 'data'  => $datas]);
            }
            else
            {
                return response()->json(['status'  => false , 'data'  => 'No Records Found']);
            }

        }
        else {
            return response()->json(['status' => false, 'data' => 'Couldn\'t Get The Mandatory Data']);
        }
    }
}
