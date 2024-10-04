<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicDetail;
use App\Models\FeeCollection;
use App\Models\Student;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use NumberFormatter;
use PDF;

class FeePaymentController extends Controller
{
    public function collectIndex(Request $request)
    {
        $students = Student::select('user_name_id', 'register_no', 'name')->get();
        return view('admin.feePayment.index', compact('students'));
    }

    public function getFee(Request $request)
    {
        if (isset($request->user_name_id) && $request->user_name_id != '') {
            $student = Student::with('enroll_master:id,enroll_master_number')->where(['user_name_id' => $request->user_name_id])->select('enroll_master_id', 'register_no', 'student_batch','name')->first();
            $academicDetail = AcademicDetail::where(['user_name_id' => $request->user_name_id])->select('admitted_mode', 'admitted_category', 'scholarship_type', 'from_gov_fee', 'scholarship_amt', 'admitted_course')->first();
            // $perosnalDetail = PersonalDetail::where(['user_name_id' => $request->user_name_id])->select('day_scholar_hosteler')->first();
            if ($student != '' && $academicDetail != '') {

                $batch = $student->student_batch;
                $course = ToolsCourse::where(['id' => $academicDetail->admitted_course])->value('short_form');
                $theEnroll = $student->enroll_master != null ? $student->enroll_master->enroll_master_number : null;
                $ay = null;
                $semester = null;
                $year = null;
                if ($theEnroll != null) {
                    $explode = explode('/', $theEnroll);
                    $ay = $explode[2];
                    $semester = $explode[3];
                    if ($semester == 1 || $semester == 2) {
                        $year = 'I';
                    } else if ($semester == 3 || $semester == 4) {
                        $year = 'II';
                    } else if ($semester == 5 || $semester == 6) {
                        $year = 'III';
                    } else if ($semester == 7 || $semester == 8) {
                        $year = 'IV';
                    }
                }
                // if ($batch != '' && $course != '') {
                //     $dept = $course->department_id;
                //     $getFee = FeeStructure::where(['batch' => $batch->id, 'department' => $course->department_id])->select('id', 'name', 'year', 'mq_total_amt', 'gq_total_amt', 'mqh_total_amt', 'gqh_total_amt', 'mq_tuition_fee', 'gq_tuition_fee', 'hostel_fee', 'others')->get();
                //     if (count($getFee) > 0) {
                //         $hosteler = false;
                //         if ($perosnalDetail->day_scholar_hosteler == 'HOSTELER') {
                //             $hosteler = true;
                //         }
                //         $explode = explode('/', $student->enroll_master->enroll_master_number);
                //         $sem = $explode[3];

                //         if ($sem == 1 || $sem == 2) {
                //             $activeYear = 1;
                //         } else if ($sem == 3 || $sem == 4) {
                //             $activeYear = 2;
                //         } else if ($sem == 5 || $sem == 6) {
                //             $activeYear = 3;
                //         } else {
                //             $activeYear = 4;
                //         }
                //         if ($dept == 1 || $dept == 2) {
                //             $fg = 27500;
                //         } else {
                //             $fg = 25000;
                //         }

                //         $admitArray = [];
                //         // $academicDetail->admitted_mode = '';
                //         if ($academicDetail->admitted_mode == 'General Quota') {
                //             if ($academicDetail->admitted_category == 'FG') {
                //                 $admitArray['admitted_category'] = 'FG';
                //                 $admitArray['fg'] = $fg;
                //                 $admitArray['scholar'] = $academicDetail->scholarship_type;
                //                 $admitArray['from_gov_fee'] = null;
                //                 $admitArray['foundation_percentage'] = null;
                //             } else if ($academicDetail->admitted_category == 'GQG') {
                //                 $admitArray['admitted_category'] = 'GQG';
                //                 $admitArray['fg'] = null;
                //                 $admitArray['scholar'] = $academicDetail->scholarship_type;
                //                 $admitArray['from_gov_fee'] = $academicDetail->from_gov_fee;
                //                 $admitArray['foundation_percentage'] = $academicDetail->scholarship_amt;
                //             } else if ($academicDetail->admitted_category == 'Scholarship') {
                //                 $admitArray['admitted_category'] = 'Scholarship';
                //                 $admitArray['fg'] = null;
                //                 $admitArray['scholar'] = $academicDetail->scholarship_type;
                //                 $admitArray['from_gov_fee'] = null;
                //                 $admitArray['foundation_percentage'] = $academicDetail->scholarship_amt;
                //             } else {
                //                 $admitArray['admitted_category'] = null;
                //                 $admitArray['fg'] = null;
                //                 $admitArray['scholar'] = null;
                //                 $admitArray['from_gov_fee'] = null;
                //                 $admitArray['foundation_percentage'] = null;
                //             }
                //         }

                //         foreach ($getFee as $fee) {
                //             $FeeCollection = FeeCollection::where(['user_name_id' => $request->user_name_id, 'fee_id' => $fee->id])->orderBy('id', 'desc')->first();
                //             $fee->feeCollect = $FeeCollection;
                //             $fee->hosteler = $hosteler;
                //             $fee->activeYear = $activeYear;
                //         }
                //         $fee = $getFee->toArray();
                //         array_push($fee, $admitArray);

                //         return response()->json(['status' => true, 'data' => $fee]);
                //     } else {
                //         return response()->json(['status' => false, 'data' => 'Fee Not Generated Yet']);
                //     }
                // } else {
                //     return response()->json(['status' => false, 'data' => 'Batch / Course Not Found For This Student']);
                // }
                $data = ['student' => $student, 'academicDetail' => $academicDetail, 'batch' => $batch, 'course' => $course, 'ay' => $ay, 'semester' => $semester, 'year' => $year];

                return response()->json(['status' => true, 'data' => $data]);
            } else {
                return response()->json(['status' => false, 'data' => 'Student Not Found']);
            }

        } else {
            return response()->json(['status' => false, 'data' => 'Couldn\'t Get The Mandatory Details']);
        }

    }

    public function collectStore(Request $request)
    {

        if (isset($request->user_name_id) && $request->user_name_id != '' && isset($request->year) && $request->year != '') {

            if ($request->tuitionPayingFee == '') {
                $request->tuitionPayingFee = 0;
            }
            if ($request->hostelPayingFee == '') {
                $request->hostelPayingFee = 0;
            }
            if ($request->otherPayingFee == '') {
                $request->otherPayingFee = 0;
            }

            if ((int) $request->totalBalanceFee == 0 && (int) $request->totalPayingFee > 0) {
                $status = 'PAID';
            } elseif ((int) $request->totalPayingFee <= 0 && (int) $request->totalPaidFee <= 0) {
                $status = 'UNPAID';
            } else {
                $status = 'HALF PAID';
            }
            if ($request->hostel == 'true') {
                $hosteler = 1;
            } else {
                $hosteler = 0;
            }

            $store = FeeCollection::create([
                'user_name_id' => $request->user_name_id,
                'year' => $request->year,
                'fee_id' => $request->fee_id,
                'date' => Carbon::now()->toDateString(),
                'payment_mode' => 'OFFLINE',
                'status' => $status,
                'total_fee' => (int) $request->totalFee,
                'total_paying' => (int) $request->totalPayingFee,
                'total_paid' => (int) $request->totalPaidFee + (int) $request->totalPayingFee,
                'last_paid' => (int) $request->totalPaidFee,
                'total_balance' => (int) $request->totalBalanceFee,
                'tuition_paying' => (int) $request->tuitionPayingFee,
                'tuition_paid' => (int) $request->tuitionPaidFee + $request->tuitionPayingFee,
                'tuition_last_paid' => (int) $request->tuitionPaidFee,
                'tuition_balance' => (int) $request->tuitionBalanceFee,
                'hostel_paying' => (int) $request->hostelPayingFee,
                'hostel_paid' => (int) $request->hostelPaidFee + $request->hostelPayingFee,
                'hostel_last_paid' => (int) $request->hostelPaidFee,
                'hostel_balance' => (int) $request->hostelBalanceFee,
                'other_paying' => (int) $request->otherPayingFee,
                'other_paid' => (int) $request->otherPaidFee + $request->otherPayingFee,
                'other_last_paid' => (int) $request->otherPaidFee,
                'other_balance' => (int) $request->otherBalanceFee,
                'sponser_amt' => (int) $request->scholar_amt,
                'fg_deduction' => (int) $request->fg,
                'hosteler' => $hosteler,
                'created_by' => auth()->user()->id,
            ]);
            return response()->json(['status' => true, 'data' => 'Fee Paid Successfully']);
        } else {
            return response()->json(['status' => false, 'data' => 'Couldn\'t Get The Mandatory Details']);
        }
    }

    public function paymentHistory(Request $request)
    {
        if ($request->user_name_id != '' && $request->id != '' && $request->year != '') {
            $data = FeeCollection::with('Student:name,register_no,user_name_id')->where(['user_name_id' => $request->user_name_id, 'year' => $request->year, 'fee_id' => $request->id])->select('id', 'user_name_id', 'date', 'payment_mode', 'total_fee', 'total_paid', 'total_paying', 'total_balance', 'status')->orderBy('id', 'desc')->get();
            if ($request->year == 1) {
                $theYear = 'First Year';
            } elseif ($request->year == 2) {
                $theYear = 'Second Year';
            } elseif ($request->year == 3) {
                $theYear = 'Third Year';
            } elseif ($request->year == 4) {
                $theYear = 'Final Year';
            }
            return view('admin.feePayment.history', compact('data', 'theYear'));
        } else {
            return back();
        }
    }

    public function collectShow(Request $request)
    {
        if (isset($request->id)) {
            $id = $request->id;
            if ($request->ajax()) {
                $history = FeeCollection::with('Student:user_name_id,name,register_no', 'Fee', 'AY:user_name_id,admitted_mode,admitted_category')->where(['id' => $request->id])->first();
                if ($history != '') {
                    $history->date = Carbon::createFromFormat('Y-m-d', $history->date)->format('d-m-Y');
                    $Quota = $history->AY != null ? $history->AY->admitted_mode : '';
                    $AdmitCat = $history->AY != null ? $history->AY->admitted_category : '';
                    $history->hostel_fee = '';
                    $history->tuition_fee = '';
                    $history->others = $history->Fee->others;
                    if ($Quota != '') {
                        if ($Quota == 'General Quota') {
                            $history->tuition_fee = $history->Fee->gq_tuition_fee;
                            if ($history->hosteler != 0) {
                                $history->hostel_fee = $history->Fee->hostel_fee;
                                // $history->total_fee = $history->Fee->gqh_total_amt;
                            } else {
                                $history->hostel_fee = 0;
                                // $history->total_fee = $history->Fee->gq_total_amt;
                            }

                        } else {
                            $history->tuition_fee = $history->Fee->mq_tuition_fee;
                            if ($history->hosteler != 0) {
                                $history->hostel_fee = $history->Fee->hostel_fee;
                                // $history->total_fee = $history->Fee->mqh_total_amt;
                            } else {
                                $history->hostel_fee = 0;
                                // $history->total_fee = $history->Fee->mq_total_amt;
                            }
                        }
                    }
                    $history->Fee = null;
                    $history->AY = null;
                }
                return response()->json(['history' => $history]);
            }
            return view('admin.feePayment.show', compact('id'));
        } else {
            return back();
        }
    }

    public function collectPDF(Request $request)
    {
        // dd($request);
        if (isset($request->id)) {
            $id = $request->id;
            $history = null;
            $history = FeeCollection::with('Student:user_name_id,name,register_no,roll_no', 'Fee', 'AY:user_name_id,admitted_course,admitted_mode,admitted_category')->where(['id' => $request->id])->first();
            if ($history != '') {
                $date = Carbon::createFromFormat('Y-m-d', $history->date)->format('d-m-Y');
                if ($history->year == '4') {
                    $year = 'IV';
                } elseif ($history->year == '3') {
                    $year = 'III';
                } elseif ($history->year == '2') {
                    $year = 'II';
                } elseif ($history->year == '1') {
                    $year = 'I';
                }
                if ($history->Student != null) {
                    $name = $history->Student->name;
                    $register_no = $history->Student->register_no;
                    $roll_no = $history->Student->roll_no;
                } else {
                    $name = null;
                    $register_no = null;
                    $roll_no = null;
                }
                $Quota = $history->AY != null ? $history->AY->admitted_mode : '';
                $AdmitCat = $history->AY != null ? $history->AY->admitted_category : '';
                $AdmitCourse = $history->AY != null ? $history->AY->admitted_course : '';
                $history->course = '';
                if ($AdmitCourse != '') {
                    $getDeptId = ToolsCourse::where(['id' => $AdmitCourse])->select('department_id')->first();
                    if ($getDeptId != '') {
                        $getDept = ToolsDepartment::where(['id' => $getDeptId->department_id])->select('name')->first();
                        if ($getDept != '') {
                            $history->course = $getDept->name;
                        }
                    }
                }
                $history->hostel_fee = '';
                $history->tuition_fee = '';
                $history->others = $history->Fee->others;
                if ($Quota != '') {
                    if ($Quota == 'General Quota') {
                        $history->tuition_fee = $history->Fee->gq_tuition_fee;
                        if ($history->hosteler != 0) {
                            $history->hostel_fee = $history->Fee->hostel_fee;
                            // $history->total_fee = $history->Fee->gqh_total_amt;
                        } else {
                            $history->hostel_fee = 0;
                            // $history->total_fee = $history->Fee->gq_total_amt;
                        }

                    } else {
                        $history->tuition_fee = $history->Fee->mq_tuition_fee;
                        if ($history->hosteler != 0) {
                            $history->hostel_fee = $history->Fee->hostel_fee;
                            // $history->total_fee = $history->Fee->mqh_total_amt;
                        } else {
                            $history->hostel_fee = 0;
                            // $history->total_fee = $history->Fee->mq_total_amt;
                        }
                    }
                }
                $history->Fee = null;
                $history->AY = null;
                $history->Student = null;
                $history->date = $date;
                $history->year = $year;
                $history->name = $name;
                $history->register_no = $register_no;
                $history->roll_no = $roll_no;

                $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                $history->amtWord = $digit->format($history->total_paid);
                $explode = explode(' ', $history->amtWord);
                if ($explode[1] == 'hundred') {
                    $explode[1] = 'lakh';
                }
                $history->amtWord = strtoupper(implode(' ', $explode));
                $final_data = ['history' => $history];
                $pdf = PDF::loadView('admin.feePayment.historyPDF', $final_data);

                $pdf->setPaper('A5', 'portrait');

                return $pdf->stream('PaymentHistory.pdf');
            }

        } else {
            return back();
        }
    }

    public function StudentIndex(Request $request)
    {
        
        return view('admin.feePayment.studentindex');
    }
}
