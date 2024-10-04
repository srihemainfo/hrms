<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreditLimitMaster;
use App\Models\ToolssyllabusYear;
use Carbon\Carbon;
use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CreditLimitController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $query = CreditLimitMaster::with('regulations:id,name')->select('id', 'regulation_id', 'credit_limit')->get();
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'section_show';
                $editGate = 'section_edit';
                $deleteGate = 'section_delete';
                $editFunct = 'editCredit';
                $viewFunct = 'viewCredit';
                $deleteFunct = 'deleteCredit';
                $crudRoutePart = 'credit-limit-master';

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
            $table->editColumn('regulation', function ($row) {
                return $row->regulations->name ? $row->regulations->name : '';
            });
            $table->addColumn('creditLimit', function ($row) {
                return $row->credit_limit ? $row->credit_limit : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $regulation = ToolssyllabusYear::pluck('name', 'id');

        return view('admin.creditLimitMaster.index', compact('regulation'));
    }
    public function store(Request $request)
    {
        // dd($request);
        if (isset($request->creditLimit)) {
            if ($request->id == '') {
                $count = CreditLimitMaster::where(['regulation_id' => $request->regulation])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'CreditLimitMaster Already Exist.']);
                } else {
                    $store = CreditLimitMaster::create([
                        'regulation_id' => $request->regulation,
                        'credit_limit' => strtoupper($request->creditLimit),
                    ]);
                    return response()->json(['status' => true, 'data' => 'CreditLimitMaster Created']);
                }
            } else {
                $count = CreditLimitMaster::where(['id'=>$request->id,'regulation_id' => $request->regulation, 'credit_limit' => $request->creditLimit])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'CreditLimitMaster Already Exist.']);
                } else {
                    $update = CreditLimitMaster::where(['id' => $request->id])->update([
                        'regulation_id' => $request->regulation,
                        'credit_limit' => $request->creditLimit,
                    ]);
                    return response()->json(['status' => true, 'data' => 'CreditLimitMaster Updated']);
                }
            }
        } else {
            return response()->json(['status' => false, 'data' => 'CreditLimitMaster Not Created']);
        }
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = CreditLimitMaster::with('regulations:id,name')->where('id', $request->id)->select('id', 'regulation_id', 'credit_limit')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            // $data = CreditLimitMaster::with('course')->where(['id' => $request->id])->select('id', 'CreditLimitMaster', 'course_id')->first();
            $data = CreditLimitMaster::with('regulations:id,name')->where('id', $request->id)->select('id', 'regulation_id', 'credit_limit')->first();

            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = CreditLimitMaster::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'CreditLimitMaster Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy(Request $request)
    {
        $sections = CreditLimitMaster::find(request('ids'));

        foreach ($sections as $CreditLimitMaster) {
            $CreditLimitMaster->delete();
        }

        return response()->json(['status' => 'success', 'data' => 'CreditLimitMaster Deleted Successfully']);
    }
}
