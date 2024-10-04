<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
                return response()->json(['status' => true, 'data' => 'Fee Components Created']);

            } else {
                FeeCycle::first()->update(['cycle_name' => $request->selecetedCycle]);
                return response()->json(['status' => true, 'data' => 'Fee Components Updated']);
            }

        }

    }

}
