<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\FeeStructure;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function structureIndex(Request $request)
    {
        $department = ToolsDepartment::pluck('name', 'id');
        $batch = Batch::pluck('name', 'id');
        // $course = ToolsCourse::pluck('short_form', 'id');

        return view('admin.feeManagement.structureIndex', compact('department', 'batch'));
    }

    public function index()
    {
        $feeStructure = FeeStructure::with('Batch','Department')->select('id', 'batch','name','department', 'year','mq_total_amt','mqh_total_amt','gq_total_amt','gqh_total_amt')->get();
        return response()->json(['data' => $feeStructure]);
    }

    public function structureSearch(Request $request)
    {
        $feeStructure = FeeStructure::with('Batch','Department')->where(['batch' => $request->batch, 'department' => $request->dept])->select('id', 'batch', 'name','department', 'year','mq_total_amt','mqh_total_amt','gq_total_amt','gqh_total_amt')->get();
        return response()->json(['data' => $feeStructure]);
    }

    public function structureCreate(Request $request)
    {
        $department = ToolsDepartment::pluck('name', 'id');
        $batch = Batch::pluck('name', 'id');
        // $course = ToolsCourse::pluck('short_form', 'id');

        return view('admin.feeManagement.structureCreate', compact('department', 'batch',));
    }

    public function structureCheck(Request $request)
    {
        if ($request->batch != '' && $request->dept != '') {

             $checkFee = FeeStructure::where(['batch' => $request->batch, 'department' => $request->dept])->get();
            if ($checkFee->count() > 0) {
                return response()->json(['status' => false, 'data' => 'Already Fee Structure Created']);
            } else {
                return response()->json(['status' => true, 'data' => true]);
            }
        } else {

            return response()->json(['status' => false, 'data' => 'Couldn\'t Get The Mandotary Details']);
        }

    }

    public function structureStore(Request $request)
    {

        if ($request->batch != ''  && $request->dept != '') {
            $batch = Batch::where(['id' => $request->batch])->select('from','to','name')->first();
            $dept = ToolsDepartment::where(['id' => $request->dept])->select('name')->first();
            $year = [1, 2, 3, 4];
            if ($batch != '' && $dept != '') {
                $academy = [];
                $name = [];

                for ($a = (int)$batch->from; $a < (int)$batch->to + 1; $a++) {
                    array_push($academy, $a);
                }

                $academy_len = count($academy) - 1;

                for ($b = 0; $b < $academy_len; $b++) {
                    array_push($name, $year[$b].' '. $dept->name .' ('.$academy[$b] . '-' . $academy[$b + 1].')');
                }
                // dd($name);
                foreach ($name as $i => $name) {
                    $store = FeeStructure::create([
                        'batch' => $request->batch,
                        'department' => $request->dept,
                        // 'course' => $request->course,
                        'name' => $name,
                        'year' => $i + 1,
                        'mq_tuition_fee' => $request->mq_tuition_fee,
                        'gq_tuition_fee' => $request->gq_tuition_fee,
                        'hostel_fee' => $request->hostel_fee,
                        'others' => $request->others,
                        'mq_total_amt' => $request->mq_total,
                        'mqh_total_amt' => $request->mqh_total,
                        'gq_total_amt' => $request->gq_total,
                        'gqh_total_amt' => $request->gqh_total,
                        'created_by' => auth()->user()->id,
                    ]);
                }
               return response()->json(['status' => true]);
            }else{
                return response()->json(['status' => false,'data' => 'Batch / Department Not Found']);
            }

        } else {
            return response()->json(['status' => false,'data' => 'Fee Structure Creation Failed']);
        }

    }

    public function structureShow(Request $request)
    {
        // dd($request->id);
        $show = FeeStructure::with('Batch', 'Department')->where(['id' => $request->id])->first();

        return view('admin.feeManagement.structureShow', compact('show'));
    }

    public function structureEdit(Request $request)
    {
        // dd($request->id);
        $edit = FeeStructure::with('Batch', 'Department')->where(['id' => $request->id])->first();

        return view('admin.feeManagement.structureEdit', compact('edit'));
    }

    public function structureUpdate(Request $request)
    {
        if ($request->id != '') {
            $store = FeeStructure::where(['id' => $request->id])->update([
                'mq_tuition_fee' => $request->mq_tuition_fee,
                'gq_tuition_fee' => $request->gq_tuition_fee,
                'hostel_fee' => $request->hostel_fee,
                'others' => $request->others,
                'mq_total_amt' => $request->mq_total,
                'mqh_total_amt' => $request->mqh_total,
                'gq_total_amt' => $request->gq_total,
                'gqh_total_amt' => $request->gqh_total,
                'created_by' => auth()->user()->id,
            ]);
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }

    }

    public function structureDelete(Request $request)
    {
        $delete = FeeStructure::find($request->id);
        $delete->delete();
        if ($delete != '') {
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }
}
