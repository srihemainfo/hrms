<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomsFee;
use App\Models\FeeCycle;
use Illuminate\Http\Request;

class feeCycleController extends Controller
{
    public function index(Request $request)
    {
        $feeCycles = FeeCycle::select('cycle_name')->get();
        return view('admin.feeCycle.index', compact('feeCycles'));
    }
    public function store(Request $request)
    {

        if (isset($request->selecetedCycle)) {
            $existingCycles = FeeCycle::count();
            if ($existingCycles == 0) {
                FeeCycle::create(['cycle_name' => $request->selecetedCycle]);
                return response()->json(['status' => true, 'data' => 'Fee Cycle Created']);

            } else {
                FeeCycle::first()->update(['cycle_name' => $request->selecetedCycle]);
                return response()->json(['status' => true, 'data' => 'Fee Cycle Updated']);
            }

        }

    }

    public function customs(Request $request)
    {
        // dd($request);

        if (isset($request->selecetedCycle)) {

            $inputValues = $request->inputValues;

            $existingCycles = FeeCycle::count();
            if ($existingCycles == 0) {
                FeeCycle::create(['cycle_name' => $request->selecetedCycle]);

                foreach ($inputValues as $inputValue) {
                    CustomsFee::create(['fee_name' => $inputValue]);
                }

                return response()->json(['status' => true, 'data' => 'Fee Cycle Created']);

            } else {
                FeeCycle::first()->update(['cycle_name' => $request->selecetedCycle]);
                foreach ($inputValues as $inputValue) {
                    CustomsFee::create(['fee_name' => $inputValue]);
                }

                return response()->json(['status' => true, 'data' => 'Fee Cycle Updated']);
            }

        }

    }

    public function getCustomsFeeNames()
    {
        $feeNames = CustomsFee::pluck('fee_name');// Adjust based on your table structure
        return response()->json(['status' => true, 'data' => $feeNames]);
    }

}
