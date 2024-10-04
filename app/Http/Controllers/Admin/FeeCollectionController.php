<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\CourseEnrollMaster;
use App\Models\FeeCollection;
use App\Models\FeeCycle;
use App\Models\FeeList;
use App\Models\FeeStructure;
use App\Models\Student;
use App\Models\ToolsCourse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PDF;

class FeeCollectionController extends Controller
{
    public function index()
    {
        $feeCycles = FeeCycle::pluck('cycle_name');

        $students = Student::select('register_no', 'name')->get();
        return view('admin.feeCollection.index', compact('students', 'feeCycles'));
    }
    public function fetch_detils(Request $request)
    {

        $reg_no = $request->reg_no;
        $student = Student::where('register_no', $reg_no)->first();

        if ($student) {
            $register_no = $student->register_no;
            $student_id = $student->id;
            $name = $student->name;
            $course = $student->admitted_course;
            $batch = $student->student_batch;
            $semester = $student->current_semester;
            $section = $student->section;
            $phone_no = $student->student_phone_no;
            $enroll_master_id = $student->enroll_master_id;

            $toolcourse = ToolsCourse::where('name', $course)->first();
            $short_form = $toolcourse ? $toolcourse->short_form : null;
            // dd($short_form);

            $course_enroll_masters = CourseEnrollMaster::where('id', $enroll_master_id)->first();
            $batch_id = $course_enroll_masters ? $course_enroll_masters->batch_id : null;
            $course_id = $course_enroll_masters ? $course_enroll_masters->course_id : null;

            $semester_id = $course_enroll_masters ? $course_enroll_masters->semester_id : null;

            $total_amounts = [];
            $feeStructures = FeeStructure::where('batch_id', $batch_id)
                ->where('course_id', $course_id)
                ->with('academicYear')
                ->get();

 

            $ids = $feeStructures->pluck('id');
            $feeLists = FeeList::whereIn('fee_id', $ids)->pluck('fee_id');

            $total_amounts = [];
            foreach ($feeStructures as $feeStructure) {
                $fee_component = $feeStructure->fee_component;
                if ($fee_component) {
                    $fee_component_array = json_decode($fee_component, true);

                    foreach ($fee_component_array as $component) {
                        if (isset($component['name']) && $component['name'] === 'Total') {
                            // Use semester_id if it's not null; otherwise, fall back to academic_year_id
                            $key = $feeStructure->semester_id ?? $feeStructure->academic_year_id;

                            // If both are null, you might need to handle this case as needed
                            if ($key === null) {
                                continue; // Skip this iteration if both are null
                            }

                            if ($feeLists->contains($feeStructure->id)) {
                                $total_amounts[$key] = [
                                    'id' => $feeStructure->id,
                                    'amount' => $component['amount'],

                                ];
                            }
                        }
                    }
                }
            }

            return response()->json([
                'status' => true,
                'name' => $name,
                'short_form' => $short_form,
                'batch' => $batch,
                'semester' => $semester,
                'section' => $section,
                'phone_no' => $phone_no,
                'fee_details' => $total_amounts,
                'register_no' => $register_no,
                'student_id' => $student_id,

                // 'academic_year' => $feeStructure->academic_year_id ?? 'Unknown',
            ]);
        } else {
            return response()->json(['status' => false, 'data' => 'Please Enter Correct Number']);
        }
    }

    public function fee_payment(Request $request)
    {
        // dd($request);
        if (isset($request->paid_amount)) {

            $lastReceipt = FeeCollection::orderBy('id', 'desc')->first();
            $nextReceiptNumber = $lastReceipt ? $lastReceipt->receipt_no + 1 : 100001;

            $transactionId = Str::random(6) . $nextReceiptNumber . Str::random(6);

            $store = FeeCollection::create([
                'paid_amount' => $request->paid_amount,
                'paid_date' => Carbon::now(),
                'student_name' => $request->student_name,
                'student_id' => $request->student_id,
                'total_amount' => $request->tot_amount,
                'status' => 'Paid',
                'payment_type' => 'Offline',
                'remarks' => $request->remark_details,
                'register_no' => $request->register_number,
                'semester' => $request->sem,
                'academic_year_id' => $request->aca,
                'receipt_no' => $nextReceiptNumber,
                'transaction_id' => $transactionId,
                'fees_id' => $request->fee_idis,
            ]);
            return response()->json(['status' => true, 'data' => 'Payment Successful']);
        } else {
            return response()->json(['status' => false, 'data' => 'Payment Failed']);
        }
    }

    public function fee_history(Request $request)
    {

        $reg_no = $request->input('reg_no');
        $fee_history = FeeCollection::where('register_no', $reg_no)->get();

        $response_data = [];

        foreach ($fee_history as $fee) {

            $paid_amount = FeeCollection::where('student_id', $fee->student_id)
                ->where('fees_id', $fee->fees_id)
                ->where('semester', $fee->semester)
                ->where('total_amount', $fee->total_amount)
                ->where('student_name', $fee->student_name)
                ->where('status', 'Paid')
                ->sum('paid_amount');

            $response_data[] = [
                'register_no' => $reg_no,
                'receipt_no' => $fee->receipt_no,
                'student_name' => $fee->student_name,
                'paid_date' => Carbon::parse($fee->paid_date)->format('d-m-Y'),
                'semester' => $fee->semester,
                'paid_amount' => $fee->paid_amount,
                'status' => $fee->status,
                'academic_year_id' => $fee->academic_year_id,
                'fees_id' => $fee->fees_id,
                'student_id' => $fee->student_id,
                'transaction_id' => $fee->transaction_id,
                'total_paid_amount' => $paid_amount,

            ];
        }
        return response()->json(['status' => true, 'data' => $response_data]);

    }

    public function fee_delete(Request $request)
    {
        // dd($request);
        $transaction_Id = $request->transaction_Id;
        $status_update = FeeCollection::where('transaction_id', $transaction_Id)->first();

        if ($status_update) {
            $status_update->status = 'deleted';
            $status_update->deleted_by = auth()->id();
            $status_update->save();

            $user_name = $status_update->User->name;

            return response()->json(['status' => true, 'data' => 'Deleted Successfully..!', 'deleted_by' => $user_name]);
        } else {
            return response()->json(['status' => false, 'message' => 'Data Not Fount']);
        }

    }

    public function generatePDF(Request $request)
    {

        $feeCycless = FeeCycle::pluck('cycle_name')->first();
        // $feeCyclesArray = json_decode($feeCycless, true);

        $transaction_id = $request->query('transaction_id');

        $trans_id = FeeCollection::where('transaction_id', $transaction_id)->first();
        $student = Student::where('id', $trans_id->student_id)->first();
        $tool_Course = ToolsCourse::where('name', $student->admitted_course)->first();
        $degree_type = Batch::where('name', $student->student_batch)->first();
        $enroll_id = CourseEnrollMaster::where('id', $student->enroll_master_id)->first();
        // Retrieve the name of the academic year
        $acaName = AcademicYear::where('id', $trans_id->academic_year_id)->pluck('name')->first();

        if ($feeCycless == 'SemesterWise') {

            $fee_collections = FeeCollection::where([
                ['register_no', $trans_id->register_no],
                ['semester', $trans_id->semester],
                ['status', 'Paid'],
            ])
                ->where('transaction_id', '!=', $trans_id->transaction_id)->get();

            $total_paid_amount = 0;
            foreach ($fee_collections as $fee_collect) {
                $total_paid_amount += $fee_collect->paid_amount;
            }

        } else if ($feeCycless == 'YearlyWise') {
            $fee_collections = FeeCollection::where([
                ['register_no', $trans_id->register_no],
                ['academic_year_id', $trans_id->academic_year_id],
                ['status', 'Paid'],
            ])
                ->where('transaction_id', '!=', $trans_id->transaction_id)->get();

            $total_paid_amount = 0;
            foreach ($fee_collections as $fee_collect) {
                $total_paid_amount += $fee_collect->paid_amount;
            }

        }

        $batch_id = $enroll_id->batch_id;
        $course_id = $enroll_id->course_id;
        $sem = $trans_id->semester;
        $aca = $trans_id->academic_year_id;

        if ($feeCycless == 'SemesterWise') {

            $fee_structure = FeeStructure::where([
                ['batch_id', $batch_id],
                ['course_id', $course_id],
                ['semester_id', $sem],
            ])->first();

        } else if ($feeCycless == 'YearlyWise') {

            $fee_structure = FeeStructure::where([
                ['batch_id', $batch_id],
                ['course_id', $course_id],
                ['academic_year_id', $aca],
            ])->first();

        }

        $full_amount = $trans_id->total_amount;

        $balance_due = $full_amount - ($total_paid_amount + $trans_id->paid_amount);

        $fee_components = json_decode($fee_structure->fee_component, true);

        $data = [
            'receiptNo' => $trans_id->receipt_no,
            'paid_amount' => $trans_id->paid_amount,
            'name' => $trans_id->student_name,
            'amount' => $trans_id->paid_amount,
            'register_no' => $trans_id->register_no,
            'paid_date' => Carbon::parse($trans_id->paid_date)->format('d-m-Y'),
            'student_batch' => $student->student_batch,
            'enroll_id' => $student->enroll_master_id,
            'section' => $student->section,
            'semester' => $trans_id->semester,
            'academic_year' => $acaName,
            'short_form' => $tool_Course->short_form,
            'degree_type' => $degree_type->degree_type ?? 'N/A',
            'fee_component' => $fee_components,
            'total_paid_amount' => $total_paid_amount,
            'balance_due' => $balance_due,
            'feeCycles' => $feeCycless,
        ];

        // dd($data);

        $pdf = PDF::loadView('admin.feeCollection.receipt', compact('data'));

        return $pdf->stream('receipt.pdf');
    }

}
