<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicDetail;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\CourseEnrollMaster;
use App\Models\CustomsFee;
use App\Models\FeeCollection;
use App\Models\FeeCycle;
use App\Models\FeeList;
use App\Models\FeeStructure;
use App\Models\PaymentMode;
use App\Models\Scholarship;
use App\Models\ScholarStudents;
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
        $payment_mode = PaymentMode::pluck('name', 'id');
        return view('admin.feeCollection.index', compact('students', 'feeCycles', 'payment_mode'));
    }
    public function fetch_detils(Request $request)
    {
        $reg_no = $request->reg_no;
        $student = Student::where('register_no', $reg_no)->first();
        $academic_details = AcademicDetail::where('register_number', $reg_no)->first();

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

            $scholar = $academic_details ? ($academic_details->scholarship == 1 ? 'Yes' : 'No') : 'No';

            $toolcourse = ToolsCourse::where('name', $course)->first();
            $short_form = $toolcourse ? $toolcourse->short_form : null;

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

            foreach ($feeStructures as $feeStructure) {
                $fee_component = $feeStructure->fee_component;
                if ($fee_component) {
                    $fee_component_array = json_decode($fee_component, true);

                    foreach ($fee_component_array as $component) {
                        if (isset($component['name']) && $component['name'] === 'Total') {
                            $key = $feeStructure->semester_id ?? $feeStructure->academic_year_id;

                            if ($key === null) {
                                continue;
                            }

                            if ($feeLists->contains($feeStructure->id)) {
                                $customs_id = $feeStructure->customs_id ? $feeStructure->customs_id : null;
                                $fee_name = null;

                                if ($customs_id) {
                                    $customsFee = CustomsFee::where('id', $customs_id)->first();
                                    $fee_name = $customsFee ? $customsFee->fee_name : 'Unknown';
                                }

                                $fee_semester = $feeStructure->semester_id ?? 'Unknown';

                                $academic_year_name = $feeStructure->academicYear ? $feeStructure->academicYear->name : 'Unknown';

                                // Concatenate fee_name and academic_year_name in the desired format
                                $concatenated_name = $fee_name . ' (' . $academic_year_name . ')';

                                $total_amounts[] = [
                                    'id' => $feeStructure->id,
                                    'amount' => $component['amount'],
                                    'academic_year_name' => $feeStructure->academicYear ? $feeStructure->academicYear->name : 'Unknown',
                                    'customs_id' => $customs_id,
                                    'semester' => $fee_semester,
                                    'fee_name' => $concatenated_name, // Add the fee_name to the response
                                ];
                            }
                        }
                    }
                }
            }

            $academicYearName = $feeStructures->isNotEmpty()
            ? ($feeStructures->first()->academicYear ? $feeStructures->first()->academicYear->name : '')
            : 'No fee structures found';

            return response()->json([
                'status' => true,
                'name' => $name,
                'short_form' => $short_form,
                'batch' => $batch,
                'semester_id' => $semester_id,
                'semester' => $semester,
                'section' => $section,
                'phone_no' => $phone_no,
                'fee_details' => $total_amounts,
                'register_no' => $register_no,
                'student_id' => $student_id,
                'scholar' => $scholar,
                'academic_year' => $academicYearName,
            ]);
        } else {
            return response()->json(['status' => false, 'data' => 'Please Enter Correct Number']);
        }
    }

    public function fetch_scholarship(Request $request)
    {

        $feeCycles = FeeCycle::pluck('cycle_name');
        // dd($feeCycles);
        if ($feeCycles->contains('SemesterWise')) {
            // dd($request);

            $student_reg_no = $request->register_no_scholar;
            $semesters = $request->semesters_no;

            $get_scholarship_details = ScholarStudents::where('stu_reg_no', $student_reg_no)->first();

            if ($get_scholarship_details) {

                $paymentTypes = [];

                $get_payment_Details = FeeCollection::where('register_no', $student_reg_no)
                    ->where('semester', $semesters)
                    ->where('status', 'Paid')
                    ->get();

                foreach ($get_payment_Details as $paymentDetail) {
                    $paymentTypes[] = $paymentDetail->payment_type;
                }

                if (in_array('Scholarship', $paymentTypes)) {
                    return response()->json([
                        'status' => false,
                        'data' => 'Scholarship fees Already Paid',
                    ]);
                }

                // dd($paymentTypes);

                $get_scholarship = $get_scholarship_details->scholar_details;

                return response()->json([
                    'status' => true,
                    'data' => $get_scholarship,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'data' => 'Scholorship Not Found for this Student.',
                ]);
            }

        } elseif ($feeCycles->contains('YearlyWise')) {
            // dd($request);

            $student_reg_no = $request->register_no_scholar;
            $academic_year_name = $request->academic_year_no;

            $academic_year_id = AcademicYear::where('name', $academic_year_name)->pluck('id')->first();

            // dd($academic_year_id);
            // $semesters = $request->semesters_no;

            $get_scholarship_details = ScholarStudents::where('stu_reg_no', $student_reg_no)->first();

            if ($get_scholarship_details) {

                $paymentTypes = [];

                $get_payment_Details = FeeCollection::where('register_no', $student_reg_no)
                    ->where('academic_year_id', $academic_year_id)
                    ->where('status', 'Paid')
                    ->get();

                foreach ($get_payment_Details as $paymentDetail) {
                    $paymentTypes[] = $paymentDetail->payment_type;
                }

                if (in_array('Scholarship', $paymentTypes)) {
                    return response()->json([
                        'status' => false,
                        'data' => 'Scholarship fees Already Paid',
                    ]);
                }

                // dd($paymentTypes);

                $get_scholarship = $get_scholarship_details->scholar_details;

                return response()->json([
                    'status' => true,
                    'data' => $get_scholarship,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'data' => 'Scholorship Not Found for this Student.',
                ]);
            }

        } elseif ($feeCycles->contains('CustomsWise')) {
            // dd($request);

            $student_reg_no = $request->register_no_scholar;
            $customs_idss = $request->customs_idss;
            $academic_year_no = $request->academic_year_no;

            $academic_year_id = AcademicYear::where('name', $academic_year_no)->pluck('id')->first();

            $cleaned_customs_idss = preg_replace("/\s*\(.*?\)/", '', $customs_idss);

            $customs_name = CustomsFee::where('fee_name', $cleaned_customs_idss)->pluck('id')->first();

            // dd($academic_year_id);
            // $semesters = $request->semesters_no;

            $get_scholarship_details = ScholarStudents::where('stu_reg_no', $student_reg_no)->first();

            if ($get_scholarship_details) {

                $paymentTypes = [];

                $get_payment_Details = FeeCollection::where('register_no', $student_reg_no)
                    ->where('customs_id', $customs_name)
                    ->where('academic_year_id', $academic_year_id)
                    ->where('status', 'Paid')
                    ->get();

                foreach ($get_payment_Details as $paymentDetail) {
                    $paymentTypes[] = $paymentDetail->payment_type;
                }

                if (in_array('Scholarship', $paymentTypes)) {
                    return response()->json([
                        'status' => false,
                        'data' => 'Scholarship fees Already Paid',
                    ]);
                }

                // dd($paymentTypes);

                $get_scholarship = $get_scholarship_details->scholar_details;
                $scholar_id = $get_scholarship_details->scholar_id;

                $scholarship = Scholarship::where('id', $scholar_id)->select('foundation_name')->first();

                return response()->json([
                    'status' => true,
                    'scholarship' => $scholarship,
                    'data' => $get_scholarship,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'data' => 'Scholorship Not Found for this Student.',
                ]);
            }

        }

    }

    public function fee_payment(Request $request)
    {
        // dd($request);

        if (isset($request->register_number)) {

            $academicYearId = AcademicYear::where('name', $request->aca)->pluck('id')->first();
            $cusfeename = CustomsFee::where('fee_name', $request->customs_idss)->pluck('id')->first();

            $lastReceipt = FeeCollection::orderBy('id', 'desc')->first();
            $nextReceiptNumber = $lastReceipt ? $lastReceipt->receipt_no + 1 : 100001;

            $transactionId = Str::random(6) . $nextReceiptNumber . Str::random(6);

            if ($request->paid_amount !== null) {
                $paidAmount = $request->paid_amount;
                $paymentType = 'Amount';
            } elseif ($request->payable_amount !== null) {
                $paidAmount = $request->payable_amount;
                $paymentType = 'Scholarship';
            } elseif ($request->discount_amount !== null) {
                $paidAmount = $request->discount_amount;
                $paymentType = 'Discount';
            } else {
                $paidAmount = 0;
                $paymentType = 'Unknown';
            }

            if ($request->remark_details !== null) {
                $remarks = $request->remark_details;
            } elseif ($request->dd !== null) {
                $remarks = $request->dd;
            } elseif ($request->cheque_number !== null) {
                $remarks = $request->cheque_number;
            } elseif ($request->reference_number !== null) {
                $remarks = $request->reference_number;
            } else {
                $remarks = 'Unknown';
            }

            $store = FeeCollection::create([
                'paid_amount' => $paidAmount,
                'paid_date' => Carbon::now(),
                'student_name' => $request->student_name,
                'student_id' => $request->student_id,
                'total_amount' => $request->tot_amount,
                'status' => 'Paid',
                'applicable_fee' => $request->applicable_fee,
                'gateway_type' => 'Offline',
                'payment_type' => $paymentType,
                'remarks' => $remarks,
                'register_no' => $request->register_number,
                'semester' => $request->sem,
                'customs_id' => $cusfeename,
                'academic_year_id' => $academicYearId,
                'receipt_no' => $nextReceiptNumber,
                'transaction_id' => $transactionId,
                'fees_id' => $request->fee_idis,
                'payment_mode' => $request->payment_mode,

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

            $academicYearName = AcademicYear::where('id', $fee->academic_year_id)->pluck('name')->first();

            $paid_amount = FeeCollection::where('student_id', $fee->student_id)
                ->where('fees_id', $fee->fees_id)
                ->where('semester', $fee->semester)
                ->where('total_amount', $fee->total_amount)
                ->where('student_name', $fee->student_name)
                ->where('status', 'Paid')
                ->sum('paid_amount');

            $fee_name = null;
            if ($fee->customs_id) {
                $customsFee = CustomsFee::where('id', $fee->customs_id)->first();
                $fee_name = $customsFee ? $customsFee->fee_name : 'Unknown';
            }

            $concatenated_name = $fee_name . ' (' . $academicYearName . ')';

            $response_data[] = [
                'register_no' => $reg_no,
                'receipt_no' => $fee->receipt_no,
                'student_name' => $fee->student_name,
                'paid_date' => Carbon::parse($fee->paid_date)->format('d-m-Y'),
                'semester' => $fee->semester,
                'paid_amount' => $fee->paid_amount,
                'status' => $fee->status,
                'customs_id' => $fee->customs_id,
                'fee_name' => $concatenated_name,
                'academic_year_id' => $academicYearName,
                'fees_id' => $fee->fees_id,
                'payment_type' => $fee->payment_type,
                'student_id' => $fee->student_id,
                'transaction_id' => $fee->transaction_id,
                'total_paid_amount' => $paid_amount,

            ];
        }
        return response()->json(['status' => true, 'data' => $response_data]);

    }

    public function fetch_hostel_fee(Request $request)
    {
        dd($request);
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

        } else if ($feeCycless == 'CustomsWise') {
            $fee_collections = FeeCollection::where([
                ['register_no', $trans_id->register_no],
                ['customs_id', $trans_id->customs_id],
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
        $cus = $trans_id->customs_id;

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

        } else if ($feeCycless == 'CustomsWise') {

            $fee_structure = FeeStructure::where([
                ['batch_id', $batch_id],
                ['course_id', $course_id],
                ['customs_id', $cus],
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
            'customs_id' => $trans_id->customs_id,
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
