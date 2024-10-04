<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AssignmentAttendances;
use App\Models\AssignmentData;
use App\Models\Batch;
use App\Models\CourseEnrollMaster;
use App\Models\Examattendance;
use App\Models\ExamattendanceData;
use App\Models\InternalWeightage;
use App\Models\LabExamAttendance;
use App\Models\LabExamAttendanceData;
use App\Models\MarksData;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\SubjectAllotment;
use App\Models\SubjectType;
use App\Models\ToolsCourse;
use App\Models\ToolssyllabusYear;
use Illuminate\Http\Request;

class InternalMarkGenerationController extends Controller
{
    public function is_serialized($data)
    {
        $data = trim($data);
        if ('N;' === $data) {
            return true;
        }
        if (!preg_match('/^([adObis]):/', $data, $badions)) {
            return false;
        }
        switch ($badions[1]) {
            case 'a':
            case 'O':
            case 's':
                if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data)) {
                    return true;
                }
                break;
            case 'b':
            case 'i':
            case 'd':
                if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data)) {
                    return true;
                }
                break;
        }
        return false;
    }

    public function index()
    {
        $course = ToolsCourse::pluck('short_form', 'id');
        $reg = ToolssyllabusYear::pluck('name', 'id');
        $ay = AcademicYear::pluck('name', 'id');
        $batch = Batch::pluck('name', 'id');
        $sem = Semester::pluck('semester', 'id');

        return view('admin.internal_marks_generation.index', compact('course', 'reg', 'ay', 'batch', 'sem'));
    }
    public function weightage(Request $request)
    {

        $reg = $request->reg;
        $internal = SubjectType::Where('regulation_id', $reg)->pluck('name', 'id');
        return response()->json($internal);
    }
    public function fetch_subject(Request $request)
    {

        $reg = $request->reg;
        $ay = $request->ay;
        $course = $request->course;
        $batch = $request->batch;
        $sem = $request->sem;
        $internal = $request->internal;
        $getAy = AcademicYear::where(['id' => $ay])->select('name')->first();
        $getBatch = Batch::where(['id' => $batch])->select('name')->first();
        if ($getAy != '' && $getBatch != '') {
            $make_enroll = $getBatch->name . '/' . '%/' . $getAy->name . '/' . $sem . '/%';
            $checkData = CourseEnrollMaster::where('enroll_master_number', 'LIKE', $make_enroll)->select('id')->get();

            if (count($checkData) <= 0) {
                return response()->json(['status' => false, 'data' => 'Given Datas Are Invalid']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
        $weightageData = [];
        if ($internal == 'THEORY') {
            $subTypes = ['THEORY', 'LAB ORIENTED THEORY'];
        } elseif ($internal == 'LABORATORY') {
            $subTypes = ['LABORATORY', 'LAB ORIENTED THEORY'];
        } else {
            $subTypes = ['PROJECT'];
        }
        $subjectTypes = SubjectType::where('regulation_id', $reg)->whereIn('name', $subTypes)->select('id')->get();
        $subjectTypes = $subjectTypes->toArray();
        $subject = SubjectAllotment::where('subject_allotment.regulation', '=', $reg)->where('subject_allotment.course', '=', $course)->where('subject_allotment.academic_year', '=', $ay)->where('subject_allotment.semester', '=', $sem)->whereIn('subjects.subject_type_id', $subjectTypes)->join('subjects', 'subject_allotment.subject_id', '=', 'subjects.id')->select('subjects.id', 'subjects.name', 'subjects.subject_code')->get();
        $weightage = InternalWeightage::Where(['regulation' => $reg, 'academic_year' => $ay, 'subject_type' => $internal, 'semester' => $request->sem])->select('internal_weightage', 'total')->first();
        if ($weightage != '') {
            if ($weightage->internal_weightage != null) {
                $weightageData = json_decode($weightage->internal_weightage);
            }
            if (count($subject) > 0) {
                foreach ($subject as $data) {
                    $getCount = MarksData::where(['academic_year' => $ay, 'course' => $course, 'semester' => $sem, 'subject_id' => $data->id, 'subject_type' => $internal])->count();
                    if ($getCount > 0) {
                        $data->generated = true;
                    } else {
                        $data->generated = false;
                    }
                }
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Internal Weightage Not Found']);
        }
        return response()->json(['status' => true, 'subject' => $subject, 'weightage' => $weightage, 'weightageData' => $weightageData]);
    }
    public function generate(Request $request)
    {

        $reg = $request->reg;
        $ay = $request->ay;
        $course = $request->course;
        $sem = $request->sem;
        $batch = $request->batch;
        $sub_id = $request->sub_id;
        $subject_type = $request->subject_type;
        $getCount = MarksData::where(['batch' => $batch, 'academic_year' => $ay, 'course' => $course, 'semester' => $sem, 'subject_id' => $sub_id, 'subject_type' => $subject_type])->count();
        if ($getCount <= 0) {
            $total = json_decode($request->totals);
            $theWeights = json_decode($request->theWeights);
            $exam_name = json_decode($request->exam_names);
            $weightage = [];
            $respective_weightage = [];
            foreach ($theWeights as $weights) {
                $explode = explode('|', $weights);
                $weightage[$explode[0]] = $explode[1];
            }
            if ($subject_type == 'THEORY') {

                $records = [];
                $assignRecords = [];
                $co_mark = [];
                $assign_mark = [];

                foreach ($exam_name as $exam) {
                    $exam_id = [];
                    if ($exam != 'Assignment') {
                        $exams_id = Examattendance::where(['course' => $course, 'subject' => $sub_id, 'examename' => $exam, 'sem' => $sem])->select('id', 'examename', 'co_mark')->get();

                        foreach ($exams_id as $data) {
                            array_push($exam_id, $data->id);

                            if ($this->is_serialized($data->co_mark)) {
                                $getCoArray = unserialize($data->co_mark);
                                if (is_array($getCoArray)) {
                                    $i = 0;
                                    foreach ($getCoArray as $id => $arr) {
                                        if ($i == 0) {
                                            $co_mark[$data->id] = $arr;
                                        }
                                        $i++;
                                    }
                                } else {
                                    $co_mark[$data->id] = $data->co_mark;
                                }
                            } else {
                                $co_mark[$data->id] = $data->co_mark;
                            }

                            if (array_key_exists($data->examename, $weightage)) {
                                $respective_weightage[$data->id] = $weightage[$data->examename];
                            }
                        }
                        $record = ExamattendanceData::whereIn('examename', $exam_id)->where(['attendance' => 'Present'])->select('student_id', 'examename', 'co_1', 'co_2', 'co_3', 'co_4', 'co_5')->get();
                        array_push($records, $record);
                    } else {
                        $exams_id = AssignmentAttendances::where(['academic_year' => $ay, 'semester' => $sem, 'course' => $course, 'subject' => $sub_id])->select('id', 'assignment_mark')->get();
                        $assign_ids = [];
                        foreach ($exams_id as $i => $exam) {
                            array_push($assign_ids, $exam->id);
                            $assign_mark[$exam->id] = $exam->assignment_mark;
                        }
                        $record = AssignmentData::whereIn('assignment_name_id', $assign_ids)->select('student_id', 'assignment_name_id', 'assignment_mark_1', 'assignment_mark_2', 'assignment_mark_3', 'assignment_mark_4', 'assignment_mark_5')->get();
                        array_push($assignRecords, $record);
                    }
                }
                $tempMark_1 = 0;
                $tempMark_2 = 0;
                $tempMark_3 = 0;

                $tempWeightage = 0;
                $stuArray = [];

                foreach ($records as $gotData) {
                    foreach ($gotData as $i => $data) {
                        if ($i == 0) {

                            if (array_key_exists($data->examename, $co_mark) && array_key_exists($data->examename, $respective_weightage)) {
                                if ($data->co_1 != null) {
                                    $tempMark_1 += (int) $co_mark[$data->examename];
                                }
                                if ($data->co_2 != null) {
                                    $tempMark_1 += (int) $co_mark[$data->examename];
                                }
                                if ($data->co_3 != null) {
                                    $tempMark_2 += (int) $co_mark[$data->examename];
                                }
                                if ($data->co_4 != null) {
                                    $tempMark_3 += (int) $co_mark[$data->examename];
                                }
                                if ($data->co_5 != null) {
                                    $tempMark_3 += (int) $co_mark[$data->examename];
                                }

                            }
                        }
                        if (!array_key_exists($data->student_id, $stuArray)) {
                            $stuArray[$data->student_id] = ['tco_1' => null, 'tco_2' => null, 'tco_3' => null, 'co_1' => null, 'co_2' => null, 'co_3' => null, 'tassignment_mark_1' => null, 'assignment_mark_1' => null];
                        }
                        $tempWeightage = (int) $respective_weightage[$data->examename];

                        $tempCo_1 = null;
                        $tempCo_2 = null;
                        $tempCo_3 = null;

                        $co1 = 0;
                        $co2 = 0;
                        $co3 = 0;

                        $co_total = 0;

                        if ($data->co_1 != null && $data->co_2 != null && $tempMark_1 > 0) {

                            $tempCo_1 = ((int) $data->co_1 + (int) $data->co_2);

                            $co1 = $tempCo_1 / $tempMark_1 * $tempWeightage;

                        }
                        if ($data->co_3 != null && $data->co_3 > 0 && $tempMark_2 > 0) {
                            $tempCo_2 = (int) $data->co_3;
                            $co2 = $tempCo_2 / $tempMark_2 * $tempWeightage;
                        }
                        if ($data->co_4 != null && $data->co_5 != null && $tempMark_3 > 0) {
                            $tempCo_3 = ((int) $data->co_4 + (int) $data->co_5);

                            $co3 = $tempCo_3 / $tempMark_3 * $tempWeightage;

                        }

                        if (array_key_exists($data->student_id, $stuArray)) {

                            if ($co1 != 0) {

                                $stuArray[$data->student_id]['tco_1'] = $tempWeightage;
                                $stuArray[$data->student_id]['co_1'] = $co1;

                            }
                            if ($co2 != 0) {

                                $stuArray[$data->student_id]['tco_2'] = $tempWeightage;
                                $stuArray[$data->student_id]['co_2'] = $co2;

                            }
                            if ($co3 != 0) {

                                $stuArray[$data->student_id]['tco_3'] = $tempWeightage;
                                $stuArray[$data->student_id]['co_3'] = $co3;

                            }
                        }
                        $tempWeightage = 0;
                    }

                }
                $tempWeightage = 0;
                if (array_key_exists('Assignment', $weightage)) {
                    $tempWeightage = (int) $weightage['Assignment'];
                }
                foreach ($assignRecords as $gotData) {
                    $tempMark = 0;
                    foreach ($gotData as $i => $data) {

                        if ($i == 0) {
                            if (array_key_exists($data->assignment_name_id, $assign_mark)) {
                                $tempMark = $assign_mark[$data->assignment_name_id];
                            }
                        }
                        // dd($tempMark,$data->assignment_name_id,$assign_mark);
                        $tempAs_1 = null;
                        $tempAs_2 = null;
                        $tempAs_3 = null;
                        $tempAs_4 = null;
                        $tempAs_5 = null;

                        $as1 = 0;
                        $as2 = 0;
                        $as3 = 0;
                        $as4 = 0;
                        $as5 = 0;
                        $as = 0;

                        if ($tempMark != 0) {
                            if ($data->assignment_mark_1 != null && $data->assignment_mark_1 != 0) {
                                $tempAs_1 = (int) $data->assignment_mark_1;
                                $as1 = $tempAs_1 / $tempMark * $tempWeightage;
                            }
                            if ($data->assignment_mark_2 != null && $data->assignment_mark_2 != 0) {
                                $tempAs_2 = (int) $data->assignment_mark_2;
                                $as2 = $tempAs_2 / $tempMark * $tempWeightage;
                            }
                            if ($data->assignment_mark_3 != null && $data->assignment_mark_3 != 0) {
                                $tempAs_3 = (int) $data->assignment_mark_3;
                                $as3 = $tempAs_3 / $tempMark * $tempWeightage;
                            }
                            if ($data->assignment_mark_4 != null && $data->assignment_mark_4 != 0) {
                                $tempAs_4 = (int) $data->assignment_mark_4;
                                $as4 = $tempAs_4 / $tempMark * $tempWeightage;
                            }
                            if ($data->assignment_mark_5 != null && $data->assignment_mark_5 != 0) {
                                $tempAs_5 = (int) $data->assignment_mark_5;
                                $as5 = $tempAs_5 / $tempMark * $tempWeightage;
                            }
                        }

                        if (array_key_exists($data->student_id, $stuArray)) {
                            $as = $as1 + $as2 + $as3 + $as4 + $as5;
                            $stuArray[$data->student_id]['tassignment_mark_1'] = $tempWeightage;
                            $stuArray[$data->student_id]['assignment_mark_1'] = $as;
                        } else {
                            $stuArray[$data->student_id] = ['tco_1' => null, 'tco_2' => null, 'tco_3' => null, 'co_1' => null, 'co_2' => null, 'co_3' => null, 'tassignment_mark_1' => $tempWeightage, 'assignment_mark_1' => $as];
                        }
                    }
                }

                foreach ($stuArray as $stuId => $stuData) {
                    MarksData::create([
                        'user_name_id' => $stuId,
                        'batch' => $batch,
                        'academic_year' => $ay,
                        'course' => $course,
                        'semester' => $sem,
                        'subject_id' => $sub_id,
                        'subject_type' => $subject_type,
                        'tco_1' => $stuData['tco_1'],
                        'tco_2' => $stuData['tco_2'],
                        'tco_3' => $stuData['tco_3'],
                        'co_1' => ceil($stuData['co_1']),
                        'co_2' => ceil($stuData['co_2']),
                        'co_3' => ceil($stuData['co_3']),
                        'tas' => $stuData['tassignment_mark_1'],
                        'as' => ceil($stuData['assignment_mark_1']),

                    ]);
                }
            } elseif ($subject_type == 'LABORATORY') {

                $records = [];
                $assignRecords = [];
                $lab_mark = [];
                $stuArray = [];
                $tempExamArray = [];

                foreach ($exam_name as $i => $exam) {
                    $exam_id = [];

                    $exams_id = LabExamAttendance::where(['course' => $course, 'subject' => $sub_id, 'examename' => $exam, 'sem' => $sem, 'acyear' => $ay])->select('id', 'examename', 'cycle_exam_mark')->get();
                    if (count($exams_id) <= 0) {
                        return response()->json(['status' => false, 'data' => 'Lab Exam Not Found For This Subject']);
                    }
                    foreach ($exams_id as $data) {
                        array_push($exam_id, $data->id);

                        $tempExamArray[$data->id] = $i;
                        $lab_mark[$data->id] = $data->cycle_exam_mark;

                        if (array_key_exists($data->examename, $weightage)) {
                            $respective_weightage[$data->id] = $weightage[$data->examename];
                        }
                    }
                    $record = LabExamAttendanceData::whereIn('lab_exam_name', $exam_id)->select('student_id', 'lab_exam_name', 'cycle_mark')->get();
                    array_push($records, $record);

                }
                $tempCy_1 = 0;

                $tempWeightage = 0;

                foreach ($records as $gotData) {
                    foreach ($gotData as $i => $data) {

                        // if ($i == 0) {

                        if (array_key_exists($data->lab_exam_name, $lab_mark) && array_key_exists($data->lab_exam_name, $respective_weightage)) {
                            if ($data->cycle_mark != null) {
                                $tempCy_1 = (int) $lab_mark[$data->lab_exam_name];
                            }

                        }
                        // }
                        $findIndex = $tempExamArray[$data->lab_exam_name];

                        if (!array_key_exists($data->student_id, $stuArray)) {
                            $stuArray[$data->student_id] = ['tcy_1' => null, 'tcy_2' => null, 'cy_1' => null, 'cy_2' => null, 'tmod_1' => null, 'mod_1' => null];
                        }

                        $tempWeightage = (int) $respective_weightage[$data->lab_exam_name];

                        $cy1 = 0;
                        $cy2 = 0;
                        $mod = 0;

                        if ($data->cycle_mark != null && $data->cycle_mark != 0 && $tempCy_1 != 0) {
                            if ($findIndex == 0) {
                                $Cy_1 = (int) $data->cycle_mark;
                                $cy1 = $Cy_1 / $tempCy_1 * $tempWeightage;
                            } else if ($findIndex == 1) {
                                $Cy_2 = (int) $data->cycle_mark;
                                $cy2 = $Cy_2 / $tempCy_1 * $tempWeightage;
                            } else if ($findIndex == 2) {
                                $Mod = (int) $data->cycle_mark;
                                $mod = $Mod / $tempCy_1 * $tempWeightage;
                            }
                        }

                        if (array_key_exists($data->student_id, $stuArray)) {

                            if ($cy1 != 0) {
                                $stuArray[$data->student_id]['tcy_1'] = $tempWeightage;
                                $stuArray[$data->student_id]['cy_1'] = $cy1;
                            }
                            if ($cy2 != 0) {
                                $stuArray[$data->student_id]['tcy_2'] = $tempWeightage;
                                $stuArray[$data->student_id]['cy_2'] = $cy2;
                            }
                            if ($findIndex == 2) {
                                $stuArray[$data->student_id]['tmod_1'] = $tempWeightage;
                            }
                            if ($mod != 0) {
                                $stuArray[$data->student_id]['mod_1'] = $mod;
                            }
                        }
                        $tempWeightage = 0;
                        $tempCy_1 = 0;
                    }

                }
                foreach ($stuArray as $stuId => $stuData) {
                    MarksData::create([
                        'user_name_id' => $stuId,
                        'batch' => $batch,
                        'academic_year' => $ay,
                        'course' => $course,
                        'semester' => $sem,
                        'subject_id' => $sub_id,
                        'subject_type' => $subject_type,
                        'tcy_1' => $stuData['tcy_1'],
                        'tcy_2' => $stuData['tcy_2'],
                        'cy_1' => ceil($stuData['cy_1']),
                        'cy_2' => ceil($stuData['cy_2']),
                        'tmod_1' => $stuData['tmod_1'],
                        'mod_1' => ceil($stuData['mod_1']),

                    ]);
                }
            } else if ($subject_type == 'PROJECT') {
                $records = [];
                $assignRecords = [];
                $lab_mark = [];
                $stuArray = [];
                $tempExamArray = [];

                foreach ($exam_name as $i => $exam) {
                    $exam_id = [];

                    $exams_id = LabExamAttendance::where(['course' => $course, 'subject' => $sub_id, 'examename' => $exam, 'sem' => $sem, 'acyear' => $ay])->select('id', 'examename', 'cycle_exam_mark')->get();
                    if (count($exams_id) <= 0) {
                        return response()->json(['status' => false, 'data' => 'Lab Exam Not Found For This Subject']);
                    }

                    foreach ($exams_id as $data) {
                        array_push($exam_id, $data->id);

                        $tempExamArray[$data->id] = $i;
                        $lab_mark[$data->id] = $data->cycle_exam_mark;

                        if (array_key_exists($data->examename, $weightage)) {
                            $respective_weightage[$data->id] = $weightage[$data->examename];
                        }
                    }
                    $record = LabExamAttendanceData::whereIn('lab_exam_name', $exam_id)->select('student_id', 'lab_exam_name', 'cycle_mark')->get();
                    array_push($records, $record);

                }

                $tempP_1 = 0;

                $tempWeightage = 0;

                foreach ($records as $gotData) {
                    foreach ($gotData as $i => $data) {

                        // if ($i == 0) {

                        if (array_key_exists($data->lab_exam_name, $lab_mark) && array_key_exists($data->lab_exam_name, $respective_weightage)) {
                            if ($data->cycle_mark != null) {
                                $tempP_1 = (int) $lab_mark[$data->lab_exam_name];
                            }
                        }
                        // }
                        $findIndex = $tempExamArray[$data->lab_exam_name];

                        if (!array_key_exists($data->student_id, $stuArray)) {
                            $stuArray[$data->student_id] = ['tp_1' => null, 'tp_2' => null, 'tp_3' => null, 'p_1' => null, 'p_2' => null, 'p_3' => null];
                        }

                        $tempWeightage = (int) $respective_weightage[$data->lab_exam_name];

                        $p1 = 0;
                        $p2 = 0;
                        $p3 = 0;

                        if ($data->cycle_mark != null && $data->cycle_mark != 0 && $tempP_1 != 0) {
                            if ($findIndex == 0) {
                                $P_1 = (int) $data->cycle_mark;
                                $p1 = $P_1 / $tempP_1 * $tempWeightage;
                            } else if ($findIndex == 1) {
                                $P_2 = (int) $data->cycle_mark;
                                $p2 = $P_2 / $tempP_1 * $tempWeightage;
                            } else if ($findIndex == 2) {
                                $P_3 = (int) $data->cycle_mark;
                                $p3 = $P_3 / $tempP_1 * $tempWeightage;
                            }
                        }

                        if (array_key_exists($data->student_id, $stuArray)) {

                            if ($p1 != 0) {
                                $stuArray[$data->student_id]['tp_1'] = $tempWeightage;
                                $stuArray[$data->student_id]['p_1'] = $p1;
                            }
                            if ($p2 != 0) {
                                $stuArray[$data->student_id]['tp_2'] = $tempWeightage;
                                $stuArray[$data->student_id]['p_2'] = $p2;
                            }
                            if ($p3 != 0) {
                                $stuArray[$data->student_id]['tp_3'] = $tempWeightage;
                                $stuArray[$data->student_id]['p_3'] = $p3;
                            }
                        }
                        $tempWeightage = 0;
                        $tempP_1 = 0;
                    }

                }
                foreach ($stuArray as $stuId => $stuData) {
                    MarksData::create([
                        'user_name_id' => $stuId,
                        'batch' => $batch,
                        'academic_year' => $ay,
                        'course' => $course,
                        'semester' => $sem,
                        'subject_id' => $sub_id,
                        'subject_type' => $subject_type,
                        'tp_1' => $stuData['tp_1'],
                        'tp_2' => $stuData['tp_2'],
                        'tp_3' => $stuData['tp_3'],
                        'p_1' => ceil($stuData['p_1']),
                        'p_2' => ceil($stuData['p_2']),
                        'p_3' => ceil($stuData['p_3']),

                    ]);
                }
            }
            return response()->json(['status' => true, 'data' => 'Internal Mark Generated Successfully']);
        } else {
            return response()->json(['status' => true, 'data' => 'Internal Mark Already Generated']);
        }
    }
    public function download(Request $request)
    {
        $reg = $request->regulation;
        $batch = $request->batch;
        $ay = $request->ay;
        $course = $request->course;
        $sem = $request->semester;
        $subject = $request->subject;
        $subject_type = $request->subject_type;

        $details = MarksData::with('getStudent:user_name_id,register_no,name', 'getAy:id,name', 'getBatch:id,name', 'getCourse:id,short_form')->where(['batch' => $batch, 'academic_year' => $ay, 'course' => $course, 'semester' => $sem, 'subject_id' => $subject, 'subject_type' => $subject_type])->get();
        $sub = Subject::where('id', $subject)->select('name', 'subject_code')->first();
        $stuArray = [];
        $co1 = 0;
        $co2 = 0;
        $co3 = 0;
        $as = 0;

        $cy1 = 0;
        $cy2 = 0;
        $mod = 0;

        $p_1 = 0;
        $p_2 = 0;
        $p_3 = 0;

        $theTotal = 0;

        $theAy = null;
        $theBatch = null;
        $theCourse = null;
        $theSemester = null;
        if ($subject_type == 'THEORY') {
            foreach ($details as $i => $data) {
                if ($i == 0) {
                    $theAy = $data->getAy->name;
                    $theBatch = $data->getBatch->name;
                    $theCourse = $data->getCourse->short_form;
                    $theSemester = $data->semester;
                }
                if ($theTotal == 0) {
                    if ($data->tco_1 != null && $data->tco_2 != null && $data->tco_3 != null) {
                        $co1 = (int) $data->tco_1;
                        $co2 = (int) $data->tco_2;
                        $co3 = (int) $data->tco_3;

                        $as = (int) $data->tas;

                        $theTotal = $co1 + $co2 + $co3 + $as;
                    }
                }
                if ($data->tco_1 != null && $data->co_1 != null && $data->co_1 != 0) {
                    $co_1 = ((float) $data->co_1 / (int) $data->tco_1) * (int) $data->tco_1;
                } else {
                    $co_1 = 0;
                }
                if ($data->tco_2 != null && $data->co_2 != null && $data->co_2 != 0) {
                    $co_2 = ((float) $data->co_2 / (int) $data->tco_2) * (int) $data->tco_2;
                } else {
                    $co_2 = 0;
                }
                if ($data->tco_3 != null && $data->co_3 != null && $data->co_3 != 0) {
                    $co_3 = ((float) $data->co_3 / (int) $data->tco_3) * (int) $data->tco_3;
                } else {
                    $co_3 = 0;
                }
                if ($data->tas != null && $data->tas != 0 && $data->as != null && $data->as != 0) {
                    $as1 = ((float) $data->as / (int) $data->tas) * (int) $data->tas;
                } else {
                    $as1 = 0;
                }
                $total = (int) $co_1 + (int) $co_2 + (int) $co_3 + (int) $as1;
                if ($data->getStudent != null) {
                    array_push($stuArray, ['register_no' => $data->getStudent->register_no, 'name' => $data->getStudent->name, 'co1' => $co_1, 'co2' => $co_2, 'co3' => $co_3, 'as' => $as1, 'total' => $total]);
                }
            }
        } else if ($subject_type == 'LABORATORY') {
            foreach ($details as $i => $data) {
                if ($i == 0) {
                    $theAy = $data->getAy->name;
                    $theBatch = $data->getBatch->name;
                    $theCourse = $data->getCourse->short_form;
                    $theSemester = $data->semester;
                }
                if ($theTotal == 0) {
                    if ($data->tcy_1 != null && $data->tcy_2 != null && $data->tmod_1 != null) {
                        $cy1 = (int) $data->tcy_1;
                        $cy2 = (int) $data->tcy_2;
                        $mod = (int) $data->tmod_1;

                        $theTotal = $cy1 + $cy2 + $mod;
                    }
                }
                if ($data->tcy_1 != null && $data->cy_1 != null && $data->tcy_1 != 0 && $data->cy_1 != 0) {
                    $cy_1 = ((float) $data->cy_1 / (int) $data->tcy_1) * (int) $data->tcy_1;
                } else {
                    $cy_1 = 0;
                }
                if ($data->tcy_2 != null && $data->cy_2 != null && $data->tcy_2 != 0 && $data->cy_2 != 0) {
                    $cy_2 = ((float) $data->cy_2 / (int) $data->tcy_2) * (int) $data->tcy_2;
                } else {
                    $cy_2 = 0;
                }
                if ($data->tmod_1 != null && $data->mod_1 != null && $data->tmod_1 != 0 && $data->mod_1 != 0) {
                    $mod_1 = ((float) $data->mod_1 / (int) $data->tmod_1) * (int) $data->tmod_1;
                } else {
                    $mod_1 = 0;
                }

                $total = (int) $cy_1 + (int) $cy_2 + (int) $mod_1;
                if ($data->getStudent != null) {
                    array_push($stuArray, ['register_no' => $data->getStudent->register_no, 'name' => $data->getStudent->name, 'cy1' => $cy_1, 'cy2' => $cy_2, 'mod' => $mod_1, 'total' => $total]);
                }
            }
        } else if ($subject_type == 'PROJECT') {
            foreach ($details as $i => $data) {
                if ($i == 0) {
                    $theAy = $data->getAy->name;
                    $theBatch = $data->getBatch->name;
                    $theCourse = $data->getCourse->short_form;
                    $theSemester = $data->semester;
                }
                if ($theTotal == 0) {
                    if ($data->tp_1 != null && $data->tp_2 != null && $data->tp_3 != null) {
                        $p_1 = (int) $data->tp_1;
                        $p_2 = (int) $data->tp_2;
                        $p_3 = (int) $data->tp_3;
                        $theTotal = $p_1 + $p_2 + $p_3;
                    }
                }
                if ($data->tp_1 != null && $data->p_1 != null && $data->tp_1 != 0 && $data->p_1 != 0) {
                    $p1 = ((float) $data->p_1 / (int) $data->tp_1) * (int) $data->tp_1;
                } else {
                    $p1 = 0;
                }
                if ($data->tp_2 != null && $data->p_2 != null && $data->tp_2 != 0 && $data->p_2 != 0) {
                    $p2 = ((float) $data->p_2 / (int) $data->tp_2) * (int) $data->tp_2;
                } else {
                    $p2 = 0;
                }
                if ($data->tp_3 != null && $data->p_3 != null && $data->tp_3 != 0 && $data->p_3 != 0) {
                    $p3 = ((float) $data->p_3 / (int) $data->tp_3) * (int) $data->tp_3;
                } else {
                    $p3 = 0;
                }

                $total = (int) $p1 + (int) $p2 + (int) $p3;
                if ($data->getStudent != null) {
                    array_push($stuArray, ['register_no' => $data->getStudent->register_no, 'name' => $data->getStudent->name, 'p1' => $p1, 'p2' => $p2, 'p3' => $p3, 'total' => $total]);
                }
            }
        }

        return view('admin.internal_marks_generation.downloadExcel', compact('subject_type', 'stuArray', 'theTotal', 'theAy', 'theBatch', 'theCourse', 'theSemester', 'co1', 'co2', 'co3', 'as', 'sub', 'cy1', 'cy2', 'mod','p_1','p_2','p_3'));
    }

    public function delete(Request $request)
    {

        $deletedrecord = MarksData::where(['batch' => $request->batch, 'academic_year' => $request->ay, 'course' => $request->course, 'semester' => $request->sem, 'subject_id' => $request->sub_id, 'subject_type' => $request->subject_type])->select('id')->get();

        foreach ($deletedrecord as $record) {
            $record->delete();
        }
        return response()->json(['status' => true]);
    }

    public function reportIndex()
    {
        $course = ToolsCourse::pluck('short_form', 'id');
        $reg = ToolssyllabusYear::pluck('name', 'id');
        $ay = AcademicYear::pluck('name', 'id');
        $batch = Batch::pluck('name', 'id');
        $sem = Semester::pluck('semester', 'id');

        return view('admin.internal_mark_report.index', compact('course', 'reg', 'ay', 'batch', 'sem'));
    }
    public function report(Request $request)
    {

        if (!isset($request->sub_type)) {
            $getAy = AcademicYear::where(['id' => $request->ay])->select('name')->first();
            $getBatch = Batch::where(['id' => $request->batch])->select('name')->first();
            if ($getAy != '' && $getBatch != '') {
                $make_enroll = $getBatch->name . '/' . '%/' . $getAy->name . '/' . $request->sem . '/%';
                $checkData = CourseEnrollMaster::where('enroll_master_number', 'LIKE', $make_enroll)->select('id')->get();

                if (count($checkData) <= 0) {
                    return response()->json(['status' => false, 'data' => 'Given Datas Are Invalid']);
                }
            } else {
                return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
            }
            $subject = SubjectAllotment::where(['regulation' => $request->reg, 'semester' => $request->sem, 'academic_year' => $request->ay, 'course' => $request->course])->select('subject_id')->get();
            // dd($subject);
            $subject_type = [];
            foreach ($subject as $r) {
                $subject = Subject::where('id', $r->subject_id)->select('subject_code', 'name', 'subject_type_id', 'id')->get();
                foreach ($subject as $sub) {
                    $sub_type = SubjectType::where('id', $sub->subject_type_id)->select('name')->first();
                    $batch = Batch::where('id', $request->batch)->select('name')->first();
                    $course = ToolsCourse::where('id', $request->course)->select('short_form')->first();
                    // dd($sub_type);
                    $status = 0;
                    $status1 = 0;
                    $status2 = 0;
                    if ($sub_type->name == 'LAB ORIENTED THEORY') {
                        $generate1 = MarksData::where(['batch' => $request->batch, 'course' => $request->course, 'semester' => $request->sem, 'academic_year' => $request->ay, 'subject_id' => $sub->id, 'subject_type' => 'THEORY'])->select('subject_id')->first();
                        $generate2 = MarksData::where(['batch' => $request->batch, 'course' => $request->course, 'semester' => $request->sem, 'academic_year' => $request->ay, 'subject_id' => $sub->id, 'subject_type' => 'LABORATORY'])->select('subject_id')->first();
                        if ($generate1 != null) {
                            $status1 = 1;
                        }
                        if ($generate2 != null) {
                            $status2 = 1;
                        }
                    } else {
                        $generate = MarksData::where(['batch' => $request->batch, 'course' => $request->course, 'semester' => $request->sem, 'academic_year' => $request->ay, 'subject_id' => $sub->id])->select('subject_id')->first();
                        if ($generate != null) {
                            $status = 1;
                        }
                    }
                    if ($sub_type->name == 'LAB ORIENTED THEORY') {

                        array_push($subject_type, [$sub->subject_code, $sub->name, 'LABORATORY', $batch->name, $course->short_form, $sub->id, $status2]);
                        array_push($subject_type, [$sub->subject_code, $sub->name, 'THEORY', $batch->name, $course->short_form, $sub->id, $status1]);
                    } else {
                        array_push($subject_type, [$sub->subject_code, $sub->name, $sub_type->name, $batch->name, $course->short_form, $sub->id, $status]);
                    }

                    $status = 0;
                }
            }
            // dd($subject_type);
            return response()->json(['status' => true, $subject_type]);
        }

        if (isset($request->sub_type)) {
            $reg = $request->reg;
            $ay = $request->ay;
            $course = $request->course;
            $sem = $request->sem;
            $internal = $request->sub_type;

            $getAy = AcademicYear::where(['id' => $ay])->select('name')->first();
            $getBatch = Batch::where(['id' => $request->batch])->select('name')->first();
            if ($getAy != '' && $getBatch != '') {
                $make_enroll = $getBatch->name . '/' . '%/' . $getAy->name . '/' . $sem . '/%';
                $checkData = CourseEnrollMaster::where('enroll_master_number', 'LIKE', $make_enroll)->select('id')->get();

                if (count($checkData) <= 0) {
                    return response()->json(['status' => false, 'data' => 'Given Datas Are Invalid']);
                }
            } else {
                return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
            }

            $weightageData = [];
            if ($internal == 'THEORY') {
                $subTypes = ['THEORY', 'LAB ORIENTED THEORY'];
            } elseif ($internal == 'LABORATORY') {
                $subTypes = ['LABORATORY', 'LAB ORIENTED THEORY'];
            } else {
                $subTypes = ['PROJECT'];
            }
            $subjectTypes = SubjectType::where('regulation_id', $reg)->whereIn('name', $subTypes)->select('id')->get();
            $subjectTypes = $subjectTypes->toArray();
            $subjects = SubjectAllotment::where('subject_allotment.regulation', '=', $reg)->where('subject_allotment.course', '=', $course)->where('subject_allotment.academic_year', '=', $ay)->where('subject_allotment.semester', '=', $sem)->whereIn('subjects.subject_type_id', $subjectTypes)->join('subjects', 'subject_allotment.subject_id', '=', 'subjects.id')->select('subjects.id', 'subjects.name', 'subjects.subject_code', 'subject_allotment.subject_id')->get();
            // dd($subjects);
            $subject_type = [];
            foreach ($subjects as $r) {
                $subject = Subject::where('id', $r->subject_id)->select('subject_code', 'name', 'subject_type_id', 'id')->get();
                foreach ($subject as $sub) {
                    $sub_type = SubjectType::where('id', $sub->subject_type_id)->select('name')->first();
                    $batch = Batch::where('id', $request->batch)->select('name')->first();
                    $course = ToolsCourse::where('id', $request->course)->select('short_form')->first();
                    // dd($sub_type);
                    $status = 0;
                    $generate = MarksData::where(['batch' => $request->batch, 'course' => $request->course, 'semester' => $request->sem, 'academic_year' => $request->ay, 'subject_id' => $sub->id, 'subject_type' => $request->sub_type])->select('subject_id')->first();
                    // dd($generate);
                    if ($generate != null) {
                        $status = 1;
                    } else {
                        $status = 0;
                    }

                    array_push($subject_type, [$sub->subject_code, $sub->name, $internal, $batch->name, $course->short_form, $sub->id, $status]);

                    $status = 0;
                }
            }
            // dd($subject_type);
            return response()->json(['status' => true, $subject_type]);
            // dd($subject);
        }
    }
}
