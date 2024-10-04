<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResultMaster;
use Illuminate\Http\Request;

class ResultMasterController extends Controller
{
    public function index(Request $request)
    {

        $resultMaster = ResultMaster::select('id', 'result_type')->get();
        return view('admin.resultMaster.index', compact('resultMaster'));
    }

    public function store(Request $request)
    {
        if (isset($request->result)) {
            if ($request->result_id == '') {
                $store = ResultMaster::create([
                    'result_type' => strtoupper($request->result),
                ]);
                return response()->json(['status' => true, 'data' => 'Result Master Created']);
            } else {
                $update = ResultMaster::where(['id' => $request->result_id])->update([
                    'result_type' => strtoupper($request->result),
                ]);
                return response()->json(['status' => true, 'data' => 'Result Master Updated']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Result Master Not Created']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id) && $request->id != '') {
            $get = ResultMaster::where('id', $request->id)->select('id', 'result_type')->first();
            if ($get != '') {
                return response()->json(['status' => true, 'data' => $get]);
            } else {
                return response()->json(['status' => false, 'data' => 'Data Not Found']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Technical Error']);
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->result) && $request->result != '') {
            $delete = ResultMaster::where('id', $request->result)->delete();
        }
        return redirect()->route('admin.result-master.index');
    }
}
