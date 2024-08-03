<?php

namespace App\Http\Controllers\Admin;

use App\Exports\FeedbackExport;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\ClassTimeTableTwo;
use App\Models\CourseEnrollMaster;
use App\Models\FeedbackSchedule;
use App\Models\OverAllFeedbacksModel;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Student;
use App\Models\ToolsCourse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BarChartExport;
use Validator;

class FeedbackReportController extends Controller
{
    public function trainingIndex(Request $request)
    {
        $batch = Batch::pluck('name', 'id');
        $ay = AcademicYear::pluck('name', 'id');
        $course = ToolsCourse::pluck('short_form', 'id');

        return view('admin.feedReportTraining.index', compact('batch', 'ay', 'course'));
    }
    public function trainingReport(Request $request)
    {
        // dd($request);
        $rules = [
            'feedback_type' => 'required',
            'batch' => 'required',
            'ay' => 'required',
            'course' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'data' => $validator->errors()]);
        }

        $schedule = FeedbackSchedule::with('feedback', 'overall_feedbacks')->where([
            'feedback_type' => 'Training',
            'batch_id' => $request->batch,
            'academic_id' => $request->ay
        ])
            ->where('training->type_training', $request->feedback_type)->get();

        if (!empty($schedule)) {
            $data = [];
            $feed_ids = [];
            foreach ($schedule as $key => $value) {
                $decode_course = json_decode($value->course_id);
                $get_sem = $value->semester;
                $get_sec = $value->section;
                $course = null;
                $sem = Semester::pluck('semester', 'id')->toArray();
                $sec = Section::where('course_id', $request->course)->pluck('section', 'id')->toArray();
                if (in_array('All', $decode_course) || in_array($request->course, $decode_course)) {
                    $course = $request->course;
                }
                if ($course) {
                    if (($get_sem == 'All' || in_array($get_sem, $sem)) || ($get_sec == 'All' || in_array($get_sec, $sec))) {
                        if (in_array($get_sem, $sem)) {
                            $sem = [$get_sem];
                        }

                        if (in_array($get_sec, $sec)) {
                            $sec = [$get_sec];
                        }
                        $get_enroll = CourseEnrollMaster::where(['batch_id' => $request->batch, 'academic_id' => $request->ay, 'course_id' => $course])
                            // ->whereIn('semester_id', $sem)
                            // ->whereIn('section', $sec)
                            ->get();
                        // dd($get_enroll);
                        foreach ($get_enroll as $id => $val) {
                            $get_student = Student::where('enroll_master_id', $val->id)->get();
                            $submitted_student = 0;
                            foreach ($get_student as $i => $stu) {
                                $get_feed = OverAllFeedbacksModel::where(['feed_schedule_id' => $value->id])->whereJsonContains('users', (string) $stu->user_name_id)->exists();
                                if ($get_feed) {
                                    $submitted_student += 1;
                                }
                            }
                            if (!empty($get_student)) {
                                $data[$val->id] = [
                                    'feedback_id' => $value->id,
                                    'ay' => $val->academic->name,
                                    'sem' => $val->semester_id,
                                    'sec' => $val->section,
                                    'enroll' => $val->id,
                                    'total_student' => count($get_student),
                                    'not_submitted' => count($get_student) - $submitted_student,
                                    'submitted' => $submitted_student,
                                ];
                            }
                            
                        }

                    }
                }
            }
            // dd($data);
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Feedback Not Yes Created']);
        }

    }

    public function trainingView(Request $request)
    {
        if ($request->feedback_id != '') {
            $get_feed = OverAllFeedbacksModel::with('feedback', 'feedback_schedule')->where('feed_schedule_id', $request->feedback_id)->get();
            $rates = [];
            $label = [];
            $datas = [];
            foreach ($get_feed as $key => $value) {
                $rating = json_decode($value->ratings);
                $label[] = 'Q' . ($key + 1);
                foreach ($rating as $id => $item) {
                    $check = Student::where(['user_name_id' => $id, 'enroll_master_id' => $request->enroll_id])->first();
                    if ($check) {
                        $rates[] = $item;
                        $value->enroll = $check->enroll_master->enroll_master_number;
                    } else {
                        $value->enroll = '';
                    }
                }
                $count = count($rates) != 0 ? count($rates) : 1;
                $fiveStarCount = array_count_values($rates)[$value->overall_rating] ?? 0;
                $percentageFiveStar = ((int) $fiveStarCount / (int) $count) * 100;
                $value->star_percent = number_format($percentageFiveStar, 2) . "%";
                $value->submitted = $request->submitted;
                $datas[] = $value->star_percent;
            }
            $data = [
                'labels' => $label,
                'data' => $datas,
            ];
            // dd($get_feed);
            return view('admin.feedReportTraining.view', compact('get_feed', 'data'));
        }
    }
    public function trainingDownload(Request $request)
    {
        if ($request->feedback_id != '') {
            $get_feed = OverAllFeedbacksModel::with('feedback', 'feedback_schedule')->where('feed_schedule_id', $request->feedback_id)->get();
            $rates = [];
            $label = [];
            $datas = [];
            $question = [];
            foreach ($get_feed as $key => $value) {
                $rating = json_decode($value->ratings);
                $label[] = 'Q' . ($key + 1);
                foreach ($rating as $id => $item) {
                    $check = Student::where(['user_name_id' => $id, 'enroll_master_id' => $request->enroll_id])->first();
                    if ($check) {
                        $rates[] = $item;
                        $value->enroll = $check->enroll_master->enroll_master_number;
                    } else {
                        $value->enroll = '';
                    }
                }
                $count = count($rates) != 0 ? count($rates) : 1;
                $fiveStarCount = array_count_values($rates)[$value->overall_rating] ?? 0;
                $percentageFiveStar = ((int) $fiveStarCount / (int) $count) * 100;
                $value->star_percent = number_format($percentageFiveStar, 2);
                $value->submitted = $request->submitted;
                $datas[] = $value->star_percent;
                $question[] = $value->question_name;
            }
            $data = [
                'labels' => $label,
                'data' => $datas,
                'question' => $question,
            ];

        }

        if ($request->file_type == 'pdf') {
            $pdf = Pdf::loadView('admin.feedReportTraining.pdf', compact('get_feed'));
            $pdf->setPaper('A4');
            return $pdf->stream($get_feed[0]->feedback->name . '.pdf');
        } elseif ($request->file_type == 'excel') {
            return Excel::download(new BarChartExport($data['labels'], $data['data'], $data['question'], $get_feed), $get_feed[0]->feedback->name . '.xlsx');
        }

    }

    public function courseIndex(Request $request)
    {
        $batch = Batch::pluck('name', 'id');
        $ay = AcademicYear::pluck('name', 'id');
        $course = ToolsCourse::pluck('short_form', 'id');
        $section = Section::pluck('section', 'id')->unique();
        $sem = Semester::pluck('semester', 'id');
        $feedback = FeedbackSchedule::with('feedback')->where('feedback_type', 'Course')->get();

        return view('admin.feedReportCourse.index', compact('batch', 'ay', 'course', 'section', 'sem', 'feedback'));
    }
    public function courseReport(Request $request)
    {
        // dd($request);
        $rules = [
            'feedback' => 'required',
            'batch' => 'required',
            'ay' => 'required',
            'course' => 'required',
            'sem' => 'required',
            'section' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'data' => $validator->errors()]);
        }

        $schedule = FeedbackSchedule::with('feedback', 'overall_feedbacks')->where([
            'feedback_type' => 'Course',
            'batch_id' => $request->batch,
            'academic_id' => $request->ay
        ])
            ->get();

        if (!empty($schedule)) {
            $data = [];
            $feed_ids = [];
            foreach ($schedule as $key => $value) {
                $decode_course = json_decode($value->course_id);
                $get_sem = $value->semester;
                $get_sec = $value->section;
                $course = null;
                $sem = Semester::where('semester', $request->sem)->pluck('semester', 'id')->toArray();
                $sec = Section::where('course_id', $request->course)->pluck('section', 'id')->toArray();
                if (in_array('All', $decode_course) || in_array($request->course, $decode_course)) {
                    $course = $request->course;
                }
                if ($course) {
                    if (($get_sem == 'All' || in_array($get_sem, $sem)) || ($get_sec == 'All' || in_array($get_sec, $sec))) {
                        if (in_array($get_sem, $sem)) {
                            $sem = [$get_sem];
                        }

                        if (in_array($get_sec, $sec)) {
                            $sec = [$get_sec];
                        }
                        $get_enroll = CourseEnrollMaster::where(['batch_id' => $request->batch, 'academic_id' => $request->ay, 'course_id' => $course])
                            ->whereIn('semester_id', $sem)
                            ->whereIn('section', $sec)
                            ->get();
                        foreach ($get_enroll as $id => $val) {
                            $get_student = Student::where('enroll_master_id', $val->id)->get();
                            $timetable = ClassTimeTableTwo::where(['class_name'=> $val->id])->pluck('staff', 'id')->toArray();
                            // dd($timetable);
                            $submitted_student = 0;
                            foreach ($get_student as $i => $stu) {
                                $get_feed = OverAllFeedbacksModel::with('teaching')->where(['feed_schedule_id' => $value->id])->whereJsonContains('users', (string) $stu->user_name_id)->get();
                                dd($get_feed);
                                if ($get_feed) {
                                    $submitted_student += 1;
                                }
                            }
                            // $feed_ids[] = $value->id;
                            $data[$value->id] = [
                                'ay' => $val->academic->name,
                                'sem' => $val->semester_id,
                                'sec' => $val->section,
                                'enroll' => $val->id,
                                'total_student' => count($get_student),
                                'not_submitted' => count($get_student) - $submitted_student,
                                'submitted' => $submitted_student,
                            ];
                        }

                    }
                }
            }
            // dd($data);
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Feedback Not Yes Created']);
        }

    }

    public function courseView(Request $request)
    {
        if ($request->feedback_id != '') {
            $get_feed = OverAllFeedbacksModel::with('feedback', 'feedback_schedule')->where('feed_schedule_id', $request->feedback_id)->get();
            $rates = [];
            $label = [];
            $datas = [];
            foreach ($get_feed as $key => $value) {
                $rating = json_decode($value->ratings);
                $label[] = 'Q' . ($key + 1);
                foreach ($rating as $id => $item) {
                    $check = Student::where(['user_name_id' => $id, 'enroll_master_id' => $request->enroll_id])->first();
                    if ($check) {
                        $rates[] = $item;
                        $value->enroll = $check->enroll_master->enroll_master_number;
                    } else {
                        $value->enroll = '';
                    }
                }
                $count = count($rates) != 0 ? count($rates) : 1;
                $fiveStarCount = array_count_values($rates)[$value->overall_rating] ?? 0;
                $percentageFiveStar = ((int) $fiveStarCount / (int) $count) * 100;
                $value->star_percent = number_format($percentageFiveStar, 2) . "%";
                $value->submitted = $request->submitted;
                $datas[] = $value->star_percent;
            }
            $data = [
                'labels' => $label,
                'data' => $datas,
            ];
            // dd($get_feed);
            return view('admin.feedReportTraining.view', compact('get_feed', 'data'));
        }
    }
    public function courseDownload(Request $request)
    {
        if ($request->feedback_id != '') {
            $get_feed = OverAllFeedbacksModel::with('feedback', 'feedback_schedule')->where('feed_schedule_id', $request->feedback_id)->get();
            $rates = [];
            $label = [];
            $datas = [];
            $question = [];
            foreach ($get_feed as $key => $value) {
                $rating = json_decode($value->ratings);
                $label[] = 'Q' . ($key + 1);
                foreach ($rating as $id => $item) {
                    $check = Student::where(['user_name_id' => $id, 'enroll_master_id' => $request->enroll_id])->first();
                    if ($check) {
                        $rates[] = $item;
                        $value->enroll = $check->enroll_master->enroll_master_number;
                    } else {
                        $value->enroll = '';
                    }
                }
                $count = count($rates) != 0 ? count($rates) : 1;
                $fiveStarCount = array_count_values($rates)[$value->overall_rating] ?? 0;
                $percentageFiveStar = ((int) $fiveStarCount / (int) $count) * 100;
                $value->star_percent = number_format($percentageFiveStar, 2);
                $value->submitted = $request->submitted;
                $datas[] = $value->star_percent;
                $question[] = $value->question_name;
            }
            $data = [
                'labels' => $label,
                'data' => $datas,
                'question' => $question,
            ];

        }

        if ($request->file_type == 'pdf') {
            $pdf = Pdf::loadView('admin.feedReportTraining.pdf', compact('get_feed'));
            $pdf->setPaper('A4');
            return $pdf->stream($get_feed[0]->feedback->name . '.pdf');
        } elseif ($request->file_type == 'excel') {
            return Excel::download(new BarChartExport($data['labels'], $data['data'], $data['question'], $get_feed), $get_feed[0]->feedback->name . '.xlsx');
        }

    }


}
