<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicDetail;
use App\Models\AcademicFee;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\CourseEnrollMaster;
use App\Models\FeeCollection;
use App\Models\FeeStructure;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use Illuminate\Http\Request;

class FeeReportController extends Controller
{
    public function yearWiseRep(Request $request)
    {
        $department = ToolsDepartment::pluck('name', 'id');
        $batch = Batch::pluck('name', 'id');
        $academic_year = AcademicYear::pluck('name', 'id');
        $course = ToolsCourse::pluck('short_form', 'id');

        return view('admin.feeManagement.yearWiseRep', compact('department', 'batch', 'academic_year', 'course'));
    }

    public function yearWiseRepData(Request $request)
    {

        if ($request->batch != '' && $request->dept != '' && $request->year != '') {
            if ($request->status != null) {
                $status = $request->status;
            } else {
                $status = 'PAID';
            }

            $getFeeStructure = FeeStructure::where(['batch' => $request->batch, 'department' => $request->dept, 'year' => $request->year])->select('id')->first();
            if ($getFeeStructure != '') {

                $finalData = [];
                if ($status != 'HALF PAID') {
                    $getDetails = FeeCollection::with('Student:user_name_id,name,register_no')->where(['fee_id' => $getFeeStructure->id, 'status' => $status])->select('id', 'user_name_id', 'date', 'payment_mode', 'total_fee', 'total_paid', 'total_paying', 'total_balance')->orderBy('id', 'desc')->first();
                    if ($getDetails != '') {
                        array_push($finalData, $getDetails);
                    }
                } else {
                    $getData = FeeCollection::where(['fee_id' => $getFeeStructure->id, 'status' => $status])->select('user_name_id')->groupBy('user_name_id')->get();
                    foreach ($getData as $data) {
                        $check = FeeCollection::where(['fee_id' => $getFeeStructure->id, 'user_name_id' => $data->user_name_id, 'status' => 'PAID'])->select('id')->get();
                        if (count($check) <= 0) {
                            $getDetails = FeeCollection::with('Student:user_name_id,name,register_no')->where(['fee_id' => $getFeeStructure->id, 'status' => $status, 'user_name_id' => $data->user_name_id])->select('id', 'user_name_id', 'date', 'payment_mode', 'total_fee', 'total_paid', 'total_paying', 'total_balance')->orderBy('id', 'desc')->first();
                            if ($getDetails != '') {
                                array_push($finalData, $getDetails);
                            }
                        }
                    }
                }

                return response()->json(['status' => true, 'data' => $finalData]);

            } else {
                return response()->json(['status' => false, 'data' => 'Fee Structure Not Generated Yet.']);
            }

        } else {
            return response()->json(['status' => false, 'data' => 'Couldn\'t Get The Mandatory Details']);
        }

    }

    public function feeDetails(Request $request)
    {
        return view('admin.feeDetails.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $explode = explode('(', $request->user_name_id);
            $register_number = trim(substr($explode[1], 0, -1));
            $student = AcademicDetail::with('course')->where('register_number', $register_number)->select('user_name_id', 'admitted_mode', 'admitted_course', 'first_graduate', 'gqg', 'scholarship', 'hosteler')->first();

            if ($student != null) {
                $getFee = AcademicFee::with('getAy')->where(['user_name_id' => $student->user_name_id,'status' => 1])->select('ay', 'tuition_fee', 'hostel_fee', 'other_fee', 'fine', 'scholarship_amt', 'gqg_amt', 'fg_amt', 'paid_amt', 'paid_date')->get();
                if ($getFee != null) {
                    $student->feeData = $getFee;
                } else {
                    $student->feeData = [];
                }
                return response(['status' => true, 'data' => $student]);
            } else {
                return response(['status' => false, 'data' => 'Student Not Found']);
            }
        }
        return view('admin.feeDetails.index');
    }

    public function getDataForStudent(Request $request)
    {
        if ($request->ajax()) {
            $student = AcademicDetail::with('course')->where('user_name_id', auth()->user()->id)->select('user_name_id', 'admitted_mode', 'admitted_course', 'first_graduate', 'gqg', 'scholarship', 'hosteler')->first();

            if ($student != null) {
                $getFee = AcademicFee::with('getAy')->where(['user_name_id' => $student->user_name_id,'status' => 1])->select('ay', 'tuition_fee', 'hostel_fee', 'other_fee', 'fine', 'scholarship_amt', 'gqg_amt', 'fg_amt', 'paid_amt', 'paid_date')->get();
                if ($getFee != null) {
                    $student->feeData = $getFee;
                } else {
                    $student->feeData = [];
                }
                return response(['status' => true, 'data' => $student]);
            } else {
                return response(['status' => false, 'data' => 'Student Not Found']);
            }
        }
        return view('admin.feeDetails.stuIndex');
    }

    public function summaryReport(Request $request)
    {
        $ays = AcademicYear::pluck('name', 'id');
        $courses = ToolsCourse::pluck('short_form', 'id');

        return view('admin.feeReports.summaryRep', compact('ays', 'courses'));
    }
    public function defaultersReport(Request $request)
    {
        $ays = AcademicYear::pluck('name', 'id');
        $courses = ToolsCourse::pluck('short_form', 'id');

        return view('admin.feeReports.defaultersRep', compact('ays', 'courses'));
    }
    public function scholarshipReport(Request $request)
    {
        $ays = AcademicYear::pluck('name', 'id');
        $courses = ToolsCourse::pluck('short_form', 'id');

        return view('admin.feeReports.scholarshipRep', compact('ays', 'courses'));
    }
    public function categoryReport(Request $request)
    {
        $ays = AcademicYear::pluck('name', 'id');
        $courses = ToolsCourse::pluck('short_form', 'id');

        return view('admin.feeReports.categoryRep', compact('ays', 'courses'));
    }

    public function summaryData(Request $request)
    {
        if (isset($request->ay)) {
            $theEnrolls = [];
            if (isset($request->course) && isset($request->year)) {
                $theCourse = ToolsCourse::find($request->course)->value('name');
                $theAy = AcademicYear::where('id', $request->ay)->value('name');
                if ($request->year == 1) {
                    $semOne = 1;
                    $semTwo = 2;
                } elseif ($request->year == 2) {
                    $semOne = 3;
                    $semTwo = 4;
                } elseif ($request->year == 3) {
                    $semOne = 5;
                    $semTwo = 6;
                } elseif ($request->year == 4) {
                    $semOne = 7;
                    $semTwo = 8;
                }
                $courseEnrollMaster1 = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%/' . $theCourse . '/' . $theAy . '/' . $semOne . '/%')->select('id')->get();
                $courseEnrollMaster2 = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%/' . $theCourse . '/' . $theAy . '/' . $semTwo . '/%')->select('id')->get();

                if (count($courseEnrollMaster1) > 0) {
                    foreach ($courseEnrollMaster1 as $enroll) {
                        $theEnrolls[] = $enroll->id;
                    }
                }
                if (count($courseEnrollMaster2) > 0) {
                    foreach ($courseEnrollMaster2 as $enroll) {
                        $theEnrolls[] = $enroll->id;
                    }
                }
                $where = ['course' => $request->course, 'ay' => $request->ay];
            } elseif (!isset($request->course) && isset($request->year)) {
                $theAy = AcademicYear::where('id', $request->ay)->value('name');

                if ($request->year == 1) {
                    $semOne = 1;
                    $semTwo = 2;
                } elseif ($request->year == 2) {
                    $semOne = 3;
                    $semTwo = 4;
                } elseif ($request->year == 3) {
                    $semOne = 5;
                    $semTwo = 6;
                } elseif ($request->year == 4) {
                    $semOne = 7;
                    $semTwo = 8;
                }
                $courseEnrollMaster1 = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%/%/' . $theAy . '/' . $semOne . '/%')->select('id')->get();
                $courseEnrollMaster2 = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%/%/' . $theAy . '/' . $semTwo . '/%')->select('id')->get();

                if (count($courseEnrollMaster1) > 0) {
                    foreach ($courseEnrollMaster1 as $enroll) {
                        $theEnrolls[] = $enroll->id;
                    }
                }
                if (count($courseEnrollMaster2) > 0) {
                    foreach ($courseEnrollMaster2 as $enroll) {
                        $theEnrolls[] = $enroll->id;
                    }
                }
                $where = ['ay' => $request->ay];
            } elseif (isset($request->course) && !isset($request->year)) {
                $where = ['course' => $request->course, 'ay' => $request->ay];
            } elseif (!isset($request->course) && !isset($request->year)) {
                $where = ['ay' => $request->ay];
            }
            if (count($theEnrolls) > 0) {
                $getData = AcademicFee::with('user_name')->where(['status' => 1])->where($where)->whereIn('enroll_master_id', $theEnrolls)->select('tuition_fee', 'hostel_fee', 'other_fee', 'fine', 'scholarship_amt', 'fg_amt', 'gqg_amt', 'paid_amt', 'user_name_id')->get();
            } else {
                $getData = AcademicFee::with('user_name')->where(['status' => 1])->where($where)->select('tuition_fee', 'hostel_fee', 'other_fee', 'fine', 'scholarship_amt', 'fg_amt', 'gqg_amt', 'paid_amt', 'user_name_id')->get();
            }
            if (count($getData) > 0) {
                foreach ($getData as $data) {
                    $data->total_fee = $data->tuition_fee + $data->hostel_fee + $data->other_fee + $data->fine;
                    $data->balance_fee = $data->total_fee - ($data->paid_amt + $data->scholarship_amt + $data->fg_amt + $data->gqg_amt);
                    $data->name = $data->user_name ? $data->user_name->name . ' (' . $data->user_name->register_no . ')' : null;
                }
            }
            return response()->json(['status' => true, 'data' => $getData]);

        } else {
            return response()->json(['stauts' => false, 'data' => 'Required Details Not Found']);
        }
    }
    public function defaultersData(Request $request)
    {
        if (isset($request->ay)) {
            $theEnrolls = [];
            if (isset($request->course) && isset($request->year)) {
                $theCourse = ToolsCourse::find($request->course)->value('name');
                $theAy = AcademicYear::where('id', $request->ay)->value('name');
                if ($request->year == 1) {
                    $semOne = 1;
                    $semTwo = 2;
                } elseif ($request->year == 2) {
                    $semOne = 3;
                    $semTwo = 4;
                } elseif ($request->year == 3) {
                    $semOne = 5;
                    $semTwo = 6;
                } elseif ($request->year == 4) {
                    $semOne = 7;
                    $semTwo = 8;
                }
                $courseEnrollMaster1 = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%/' . $theCourse . '/' . $theAy . '/' . $semOne . '/%')->select('id')->get();
                $courseEnrollMaster2 = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%/' . $theCourse . '/' . $theAy . '/' . $semTwo . '/%')->select('id')->get();

                if (count($courseEnrollMaster1) > 0) {
                    foreach ($courseEnrollMaster1 as $enroll) {
                        $theEnrolls[] = $enroll->id;
                    }
                }
                if (count($courseEnrollMaster2) > 0) {
                    foreach ($courseEnrollMaster2 as $enroll) {
                        $theEnrolls[] = $enroll->id;
                    }
                }
                $where = ['course' => $request->course, 'ay' => $request->ay];
            } elseif (!isset($request->course) && isset($request->year)) {
                $theAy = AcademicYear::where('id', $request->ay)->value('name');
                if ($request->year == 1) {
                    $semOne = 1;
                    $semTwo = 2;
                } elseif ($request->year == 2) {
                    $semOne = 3;
                    $semTwo = 4;
                } elseif ($request->year == 3) {
                    $semOne = 5;
                    $semTwo = 6;
                } elseif ($request->year == 4) {
                    $semOne = 7;
                    $semTwo = 8;
                }
                $courseEnrollMaster1 = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%/%/' . $theAy . '/' . $semOne . '/%')->select('id')->get();
                $courseEnrollMaster2 = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%/%/' . $theAy . '/' . $semTwo . '/%')->select('id')->get();

                if (count($courseEnrollMaster1) > 0) {
                    foreach ($courseEnrollMaster1 as $enroll) {
                        $theEnrolls[] = $enroll->id;
                    }
                }
                if (count($courseEnrollMaster2) > 0) {
                    foreach ($courseEnrollMaster2 as $enroll) {
                        $theEnrolls[] = $enroll->id;
                    }
                }
                $where = ['ay' => $request->ay];
            } elseif (isset($request->course) && !isset($request->year)) {
                $where = ['course' => $request->course, 'ay' => $request->ay];
            } elseif (!isset($request->course) && !isset($request->year)) {
                $where = ['ay' => $request->ay];
            }
            if (count($theEnrolls) > 0) {
                $getData = AcademicFee::with('user_name')->where($where)->where(['status' => 1])->whereIn('enroll_master_id', $theEnrolls)->select('tuition_fee', 'hostel_fee', 'other_fee', 'fine', 'scholarship_amt', 'fg_amt', 'gqg_amt', 'paid_amt', 'user_name_id')->get();
            } else {
                $getData = AcademicFee::with('user_name')->where($where)->where(['status' => 1])->select('tuition_fee', 'hostel_fee', 'other_fee', 'fine', 'scholarship_amt', 'fg_amt', 'gqg_amt', 'paid_amt', 'user_name_id')->get();
            }
            $theData = [];
            if (count($getData) > 0) {
                foreach ($getData as $data) {
                    $total_fee = $data->tuition_fee + $data->hostel_fee + $data->other_fee + $data->fine;
                    $data->balance_fee = $total_fee - ($data->paid_amt + $data->scholarship_amt + $data->fg_amt + $data->gqg_amt);
                    if ($data->balance_fee > 0) {
                        $data->name = $data->user_name ? $data->user_name->name . ' (' . $data->user_name->register_no . ')' : null;
                        array_push($theData, $data);
                    }
                }
            }
            return response()->json(['status' => true, 'data' => $theData]);

        } else {
            return response()->json(['stauts' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function categoryRepData(Request $request)
    {
        if (isset($request->ay)) {
            $theEnrolls = [];
            if (isset($request->course) && isset($request->year)) {
                $theCourse = ToolsCourse::find($request->course)->value('name');
                $theAy = AcademicYear::where('id', $request->ay)->value('name');
                if ($request->year == 1) {
                    $semOne = 1;
                    $semTwo = 2;
                } elseif ($request->year == 2) {
                    $semOne = 3;
                    $semTwo = 4;
                } elseif ($request->year == 3) {
                    $semOne = 5;
                    $semTwo = 6;
                } elseif ($request->year == 4) {
                    $semOne = 7;
                    $semTwo = 8;
                }
                $courseEnrollMaster1 = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%/' . $theCourse . '/' . $theAy . '/' . $semOne . '/%')->select('id')->get();
                $courseEnrollMaster2 = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%/' . $theCourse . '/' . $theAy . '/' . $semTwo . '/%')->select('id')->get();

                if (count($courseEnrollMaster1) > 0) {
                    foreach ($courseEnrollMaster1 as $enroll) {
                        $theEnrolls[] = $enroll->id;
                    }
                }
                if (count($courseEnrollMaster2) > 0) {
                    foreach ($courseEnrollMaster2 as $enroll) {
                        $theEnrolls[] = $enroll->id;
                    }
                }
                $where = ['course' => $request->course, 'ay' => $request->ay];
            } elseif (!isset($request->course) && isset($request->year)) {
                $theAy = AcademicYear::where('id', $request->ay)->value('name');
                if ($request->year == 1) {
                    $semOne = 1;
                    $semTwo = 2;
                } elseif ($request->year == 2) {
                    $semOne = 3;
                    $semTwo = 4;
                } elseif ($request->year == 3) {
                    $semOne = 5;
                    $semTwo = 6;
                } elseif ($request->year == 4) {
                    $semOne = 7;
                    $semTwo = 8;
                }
                $courseEnrollMaster1 = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%/%/' . $theAy . '/' . $semOne . '/%')->select('id')->get();
                $courseEnrollMaster2 = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%/%/' . $theAy . '/' . $semTwo . '/%')->select('id')->get();

                if (count($courseEnrollMaster1) > 0) {
                    foreach ($courseEnrollMaster1 as $enroll) {
                        $theEnrolls[] = $enroll->id;
                    }
                }
                if (count($courseEnrollMaster2) > 0) {
                    foreach ($courseEnrollMaster2 as $enroll) {
                        $theEnrolls[] = $enroll->id;
                    }
                }
                $where = ['ay' => $request->ay];
            } elseif (isset($request->course) && !isset($request->year)) {
                $where = ['course' => $request->course, 'ay' => $request->ay];
            } elseif (!isset($request->course) && !isset($request->year)) {
                $where = ['ay' => $request->ay];
            }
            $students = [];
            $academicWhere = [];
            if (isset($request->admitted_mode)) {
                $academicWhere['admitted_mode'] = $request->admitted_mode;
            }
            if (isset($request->scholarship)) {
                $academicWhere['scholarship'] = '1';
            }
            if (isset($request->fg)) {
                $academicWhere['first_graduate'] = '1';
            }
            if (isset($request->gqg)) {
                $academicWhere['gqg'] = '1';
            }

            if (count($theEnrolls) > 0) {
                $getStudents = AcademicDetail::where($academicWhere)->whereIn('enroll_master_number_id', $theEnrolls)->select('user_name_id')->get();
                if (count($getStudents) > 0) {
                    foreach ($getStudents as $student) {
                        array_push($students, $student->user_name_id);
                    }
                }
            } else {
                $getStudents = AcademicDetail::where($academicWhere)->select('user_name_id')->get();
                if (count($getStudents) > 0) {
                    foreach ($getStudents as $student) {
                        array_push($students, $student->user_name_id);
                    }
                }
            }
            $getData = [];
            if (count($students) > 0) {
                $getData = AcademicFee::with('user_name')->where($where)->where(['status' => 1])->whereIn('user_name_id', $students)->select('tuition_fee', 'hostel_fee', 'other_fee', 'fine', 'scholarship_amt', 'fg_amt', 'gqg_amt', 'paid_amt', 'user_name_id')->get();
            }
            if (count($getData) > 0) {
                foreach ($getData as $data) {
                    $data->total_fee = $data->tuition_fee + $data->hostel_fee + $data->other_fee + $data->fine;
                    $data->name = $data->user_name ? $data->user_name->name . ' (' . $data->user_name->register_no . ')' : null;
                    $data->admitted_mode = $data->academicDetail ? $data->academicDetail->admitted_mode : null;
                }
            }
            return response()->json(['status' => true, 'data' => $getData]);

        } else {
            return response()->json(['stauts' => false, 'data' => 'Required Details Not Found']);
        }
    }

}
