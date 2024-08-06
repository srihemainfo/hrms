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
use DB;
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
            if (count($get_feed) > 0) {

                foreach ($get_feed as $key => $value) {
                    $rates = [];
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
                    $fiveStarCount = array_count_values($rates)[5] ?? 0;
                    $fourStarCount = array_count_values($rates)[4] ?? 0;
                    $threeStarCount = array_count_values($rates)[3] ?? 0;
                    $twoStarCount = array_count_values($rates)[2] ?? 0;
                    $oneStarCount = array_count_values($rates)[1] ?? 0;
                    $five_scale = ($fiveStarCount * 5 + $fourStarCount * 4 + $threeStarCount * 3 + $twoStarCount * 2 + $oneStarCount * 1) /
                        ($fiveStarCount + $fourStarCount + $threeStarCount + $twoStarCount + $oneStarCount);
                    $value->submitted = $request->submitted;
                    $value->five_star = $fiveStarCount;
                    $value->four_star = $fourStarCount;
                    $value->three_star = $threeStarCount;
                    $value->two_star = $twoStarCount;
                    $value->one_star = $oneStarCount;
                    $value->star_percent = (int) $request->total_student / $count;
                    $value->five_scale = $five_scale;
                    $datas[] = $value->five_scale;
                }
                $data = [
                    'labels' => $label,
                    'data' => $datas,
                ];
                // dd($get_feed);
                return view('admin.feedReportTraining.view', compact('get_feed', 'data'));
            } else {
                return back()->with('error', 'No one Submitted the FeedBack.');

            }
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
            if (count($get_feed) > 0) {

                foreach ($get_feed as $key => $value) {
                    $rates = [];
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
                    $fiveStarCount = array_count_values($rates)[5] ?? 0;
                    $fourStarCount = array_count_values($rates)[4] ?? 0;
                    $threeStarCount = array_count_values($rates)[3] ?? 0;
                    $twoStarCount = array_count_values($rates)[2] ?? 0;
                    $oneStarCount = array_count_values($rates)[1] ?? 0;
                    $five_scale = ($fiveStarCount * 5 + $fourStarCount * 4 + $threeStarCount * 3 + $twoStarCount * 2 + $oneStarCount * 1) /
                        ($fiveStarCount + $fourStarCount + $threeStarCount + $twoStarCount + $oneStarCount);
                    $value->submitted = $request->submitted;
                    $value->five_star = $fiveStarCount;
                    $value->four_star = $fourStarCount;
                    $value->three_star = $threeStarCount;
                    $value->two_star = $twoStarCount;
                    $value->one_star = $oneStarCount;
                    $value->star_percent = (int) $request->total_student / $count;
                    $value->five_scale = $five_scale;
                    $datas[] = $value->five_scale;
                    $question[] = $value->question_name;
                }
                $data = [
                    'labels' => $label,
                    'data' => $datas,
                    'question' => $question,
                ];
                // dd($request->total_student);
                if ($request->file_type == 'pdf') {
                    $pdf = Pdf::loadView('admin.feedReportTraining.pdf', compact('get_feed'));
                    $pdf->setPaper('A4');
                    return $pdf->stream($get_feed[0]->feedback->name . '.pdf');
                } elseif ($request->file_type == 'excel') {
                    return Excel::download(new BarChartExport($data['labels'], $data['data'], $data['question'], $get_feed), $get_feed[0]->feedback->name . '.xlsx');
                }
            } else {
                return back()->with('error', 'No one Submitted the FeedBack.');

            }

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

        $schedule = FeedbackSchedule::with('feedback', 'overall_feedbacks')
            ->where([
                'feedback_type' => 'Course',
                'batch_id' => $request->batch,
                'academic_id' => $request->ay
            ])
            ->where(function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->whereJsonContains('course_id', $request->course)
                        ->orWhereJsonContains('course_id', 'All');
                })
                    ->where(function ($subQuery) use ($request) {
                        $subQuery->where('semester', $request->sem)
                            ->orWhere('semester', 'All');
                    })
                    ->where(function ($subQuery) use ($request) {
                        $subQuery->where('section', $request->section)
                            ->orWhere('section', 'All');
                    });
            })
            ->get();



        // dd($schedule);
        if (!empty($schedule)) {
            $data = [];
            $feed_ids = [];
            foreach ($schedule as $key => $value) {
                $decode_course = json_decode($value->course_id);
                $get_sem = $value->semester;
                $get_sec = $value->section;
                $course = null;
                $sem = Semester::where('semester', $request->sem)->pluck('semester', 'id')->toArray();
                $sec = Section::where(['course_id' => $request->course, 'section' => $request->section])->pluck('section', 'id')->toArray();
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
                    }
                    // dd($sec, $sem);
                    $get_report = DB::table('course_enroll_masters')
                        ->where([
                            'course_enroll_masters.batch_id' => $request->batch,
                            'course_enroll_masters.academic_id' => $request->ay,
                            'course_enroll_masters.course_id' => $course
                        ])
                        ->whereIn('course_enroll_masters.semester_id', $sem)
                        ->whereIn('course_enroll_masters.section', $sec)
                        ->join('batches', 'batches.id', '=', 'course_enroll_masters.batch_id')
                        ->join('academic_years', 'academic_years.id', '=', 'course_enroll_masters.academic_id')
                        ->join('students', 'students.enroll_master_id', '=', 'course_enroll_masters.id')
                        ->leftJoin('class_time_table_two', 'class_time_table_two.class_name', '=', 'course_enroll_masters.id')
                        ->leftJoin('teaching_staffs', 'teaching_staffs.user_name_id', '=', 'class_time_table_two.staff')
                        ->leftJoin('subjects', 'subjects.id', '=', 'class_time_table_two.subject')
                        ->leftJoin('feedback_schedule', 'feedback_schedule.academic_id', '=', 'course_enroll_masters.academic_id')
                        ->leftJoin('overall_feedbacks', function ($join) use ($value) {
                            $join->on('overall_feedbacks.feed_schedule_id', '=', 'feedback_schedule.id')
                                ->where('overall_feedbacks.feedback_participant', 'Student')
                                ->where('overall_feedbacks.feedback_type', 'Course')
                                ->whereColumn('overall_feedbacks.staff_id', 'class_time_table_two.staff');
                        })
                        ->where('feedback_schedule.id', $value->id)
                        ->whereNull('class_time_table_two.deleted_at')
                        ->whereNull('overall_feedbacks.deleted_at')
                        ->select(
                            'subjects.subject_code',
                            'overall_feedbacks.feed_schedule_id',
                            'subjects.name as subject_name',
                            'course_enroll_masters.id as enroll_id',
                            'teaching_staffs.name as staff_name',
                            'class_time_table_two.staff as staff_id',
                            DB::raw('COUNT(DISTINCT students.user_name_id) as total_students'),
                        )
                        ->groupBy(
                            'class_time_table_two.staff',
                            'subjects.subject_code',
                            'subjects.name',
                            'course_enroll_masters.id',
                            'teaching_staffs.name',
                            'overall_feedbacks.feed_schedule_id'
                        )
                        ->orderBy('subjects.subject_code')
                        ->get();



                    foreach ($get_report as $id => $val) {
                        $get_student = Student::where('enroll_master_id', $val->enroll_id)->get();
                        $submitted_student = 0;
                        foreach ($get_student as $i => $stu) {
                            // dd($stu);
                            $get_feed = OverAllFeedbacksModel::where(['feed_schedule_id' => $val->feed_schedule_id, 'feedback_participant' => 'Student', 'feedback_type' => 'Course', 'staff_id' => $val->staff_id])->whereJsonContains('users', (string) $stu->user_name_id)->get();
                            // dd($get_feed);
                            if (count($get_feed) > 0) {
                                $submitted_student += 1;
                            }
                        }
                        $val->feed_schedule_id = $val->feed_schedule_id ?? $value->id;
                        $val->submitted = $submitted_student;
                        $val->not_submitted = count($get_student) - $submitted_student;

                    }
                    $data[$value->id] = $get_report;
                }
            }
            // dd($data[1][0]);
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Feedback Not Yes Created']);
        }

    }

    public function courseView(Request $request)
    {
        if ($request->feedback_id != '') {
            $explode = explode(',', $request->staff_id);
            $get_feed = OverAllFeedbacksModel::with('feedback', 'feedback_schedule', 'teaching')->where(['feed_schedule_id' => $request->feedback_id, 'staff_id' => $request->staff_id])->get();
            $rates = [];
            $label = [];
            $datas = [];
            if (count($get_feed) > 0) {
                foreach ($get_feed as $key => $value) {
                    $rates = [];
                    $rating = json_decode($value->ratings);
                    $label[] = 'Q' . ($key + 1);
                    foreach ($rating as $id => $item) {
                        $check = Student::where(['user_name_id' => $id, 'enroll_master_id' => $request->enroll_id])->first();
                        if ($check) {
                            $rates[] = $item;
                            $value->enroll = $check->enroll_master->enroll_master_number;
                            $value->subject_code = $explode[1];
                            $value->subject_name = $explode[2];
                        } else {
                            $value->enroll = '';
                        }
                    }
                    $count = count($rates) != 0 ? count($rates) : 1;
                    $fiveStarCount = array_count_values($rates)[5] ?? 0;
                    $fourStarCount = array_count_values($rates)[4] ?? 0;
                    $threeStarCount = array_count_values($rates)[3] ?? 0;
                    $twoStarCount = array_count_values($rates)[2] ?? 0;
                    $oneStarCount = array_count_values($rates)[1] ?? 0;
                    $five_scale = ($fiveStarCount * 5 + $fourStarCount * 4 + $threeStarCount * 3 + $twoStarCount * 2 + $oneStarCount * 1) /
                        ($fiveStarCount + $fourStarCount + $threeStarCount + $twoStarCount + $oneStarCount);
                    $value->submitted = $request->submitted;
                    $value->five_star = $fiveStarCount;
                    $value->four_star = $fourStarCount;
                    $value->three_star = $threeStarCount;
                    $value->two_star = $twoStarCount;
                    $value->one_star = $oneStarCount;
                    $value->star_percent = (int) $request->total_students / $count;
                    $value->five_scale = $five_scale;
                    $datas[] = $value->five_scale;
                }
                $data = [
                    'labels' => $label,
                    'data' => $datas,
                ];
                // dd($data, $get_feed);
                return view('admin.feedReportCourse.view', compact('get_feed', 'data'));
            } else {
                return back()->with('error', 'No one Submitted the FeedBack.');
            }
        }
    }
    public function courseDownload(Request $request)
    {
        if ($request->feedback_id != '') {
            $get_feed = OverAllFeedbacksModel::with('feedback', 'feedback_schedule', 'teaching')->where(['feed_schedule_id' => $request->feedback_id, 'staff_id' => $request->staff_id])->get();
            $rates = [];
            $label = [];
            $datas = [];
            $question = [];
            $explode = explode(',', $request->staff_id);

            if (count($get_feed) > 0) {
                foreach ($get_feed as $key => $value) {
                    $rates = [];
                    $rating = json_decode($value->ratings);
                    $label[] = 'Q' . ($key + 1);
                    foreach ($rating as $id => $item) {
                        $check = Student::where(['user_name_id' => $id, 'enroll_master_id' => $request->enroll_id])->first();
                        if ($check) {
                            $rates[] = $item;
                            $value->enroll = $check->enroll_master->enroll_master_number;
                            $value->subject_code = $explode[1];
                            $value->subject_name = $explode[2];
                        } else {
                            $value->enroll = '';
                        }
                    }
                    $count = count($rates) != 0 ? count($rates) : 1;
                    $fiveStarCount = array_count_values($rates)[5] ?? 0;
                    $fourStarCount = array_count_values($rates)[4] ?? 0;
                    $threeStarCount = array_count_values($rates)[3] ?? 0;
                    $twoStarCount = array_count_values($rates)[2] ?? 0;
                    $oneStarCount = array_count_values($rates)[1] ?? 0;
                    $five_scale = ($fiveStarCount * 5 + $fourStarCount * 4 + $threeStarCount * 3 + $twoStarCount * 2 + $oneStarCount * 1) /
                        ($fiveStarCount + $fourStarCount + $threeStarCount + $twoStarCount + $oneStarCount);
                    $value->submitted = $request->submitted;
                    $value->five_star = $fiveStarCount;
                    $value->four_star = $fourStarCount;
                    $value->three_star = $threeStarCount;
                    $value->two_star = $twoStarCount;
                    $value->one_star = $oneStarCount;
                    $value->star_percent = (int) $request->total_students / $count;
                    $value->five_scale = $five_scale;
                    $datas[] = $value->five_scale;
                    $question[] = $value->question_name;
                }
                $data = [
                    'labels' => $label,
                    'data' => $datas,
                    'question' => $question,
                ];
                // dd($get_feed);
                if ($request->file_type == 'pdf') {
                    $pdf = Pdf::loadView('admin.feedReportCourse.pdf', compact('get_feed'));
                    $pdf->setPaper('A4');
                    return $pdf->stream($get_feed[0]->feedback->name . '.pdf');
                } elseif ($request->file_type == 'excel') {
                    return Excel::download(new BarChartExport($data['labels'], $data['data'], $data['question'], $get_feed), $get_feed[0]->feedback->name . '.xlsx');
                }
            } else {

                return back()->with('error', 'No one Submitted the FeedBack.');
            }

        }

    }


}
