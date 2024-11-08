<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveImplement;
use App\Models\NonTeachingStaff;
use App\Models\StaffBiometric;
use App\Models\TeachingStaff;
use App\Models\TeachingType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LeaveImplementController extends Controller
{
    public function index(Request $request)
    {

        // $type = TeachingType::whereNot('id', 6)->pluck('name', 'id');

        if ($request->ajax()) {
            $query = LeaveImplement::query()->select(sprintf('%s.*', (new LeaveImplement)->table));
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                return $row;
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('date', function ($row) {
                return $row->date ? $row->date : '';
            });
            $table->editColumn('staff_type', function ($row) {
                if ($row->staff_type == 'tech,non-tech') {
                    return $row->staff_type ? "Teaching Staff & Non Teaching Staff" : '';
                } elseif ($row->staff_type == 'non-tech,tech') {
                    return $row->staff_type ? "Teaching Staff & Non Teaching Staff" : '';
                } elseif ($row->staff_type == 'tech') {
                    return $row->staff_type ? "Teaching Staff" : '';
                } elseif ($row->staff_type == 'non-tech') {
                    return $row->staff_type ? "Non Teaching Staff" : '';
                } else {
                    return $row->staff_type ? $row->staff_type : '';
                }
            });
            $table->editColumn('leave_type', function ($row) {
                return $row->leave_type ? $row->leave_type : '';
            });
            $table->editColumn('half_day', function ($row) {
                return $row->noon ? $row->noon : '';
            });
            $table->editColumn('remark', function ($row) {
                return $row->reason ? $row->reason : '';
            });

            $table->rawColumns(['placeholder']);

            return $table->make(true);
        }
        return view('admin.leaveImplement.index');
    }

    public function store(Request $request)
    {

        if (isset($request->date) && isset($request->start_time) && isset($request->end_time) && isset($request->reason)) {
            $date = $request->date;
            $start_time = $request->start_time;
            $end_time = $request->end_time;
            $reason = $request->reason;

            $store = LeaveImplement::create([
                'date' => $date,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'reason' =>  $reason,

            ]);

            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }
    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = LeaveImplement::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }
}
