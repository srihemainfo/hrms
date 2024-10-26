<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NoleaveAward;
use App\Models\Staffs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NoleaveAwardController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $query = NoleaveAward::select(sprintf('%s.*', (new NoleaveAward)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'noleave_award_show';
                $editGate = 'noleave_awardedit';
                $deleteGate = 'noleave_award_delete';
                $editFunct = 'editnoleave_award';
                $viewFunct = 'viewnoleave_award';
                $deleteFunct = 'deletenoleave_award';
                $crudRoutePart = 'noleave_award';

                return view('partials.ajaxTableActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
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

            $table->editColumn('staff_name', function ($row) {
                return $row->staff_name ? $row->staff_name : '';
            });

            $table->editColumn('year', function ($row) {
                return $row->year ? $row->year : '';
            });

            $table->editColumn('month', function ($row) {
                return $row->month ? $row->month : '';
            });

            $table->editColumn('amount', function ($row) {
                return $row->amount ? $row->amount : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $staffs = Staffs::where('deleted_at', null)->get();
        return view('admin.noleave_award.index', compact('staffs'));
    }

    public function store(Request $request)
    {

        $user_name_id = Staffs::where('name', $request->staff_name)->value('user_name_id');
        if (isset($request->staff_name)) {
            if ($request->id == '') {
                $count = NoleaveAward::where([
                    'staff_name' => $request->staff_name, 'year' => $request->year, 'month' => $request->month])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Award Already Exist.']);
                } else {

                    $store = NoleaveAward::create([
                        'staff_name' => $request->staff_name,
                        'year' => $request->year,
                        'month' => $request->month,
                        'amount' => $request->amount,
                        'user_name_id' => $user_name_id,
                        'created_by' => auth()->user()->name,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Award Created']);
            } else {
                $count = NoleaveAward::whereNotIn('id', [$request->id])->where(['staff_name' => $request->staff_name, 'year' => $request->year, 'month' => $request->month])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Award Already Exist.']);
                } else {
                    $update = NoleaveAward::where(['id' => $request->id])->update([
                        'staff_name' => $request->staff_name,
                        'year' => $request->year,
                        'month' => $request->month,
                        'amount' => $request->amount,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Award Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Award Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = NoleaveAward::where(['id' => $request->id])->select('id', 'staff_name', 'amount', 'year', 'month')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = NoleaveAward::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Award Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = NoleaveAward::where(['id' => $request->id])->select('id', 'staff_name','amount','year','month')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function massDestroy(Request $request)
    {
        $sections = NoleaveAward::find(request('ids'));

        foreach ($sections as $section) {
            $section->delete();
        }

        return response()->json(['status' => 'success', 'data' => 'Award Deleted Successfully']);
    }
}
