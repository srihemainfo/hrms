<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ClassTimeTableTwo;
use App\Models\CollegeCalender;
use App\Models\CourseEnrollMaster;
use App\Models\LessonPlans;
use App\Models\Subject;
use App\Models\TeachingStaff;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;

class StaffSubjectsController extends Controller
{
    public function index(Request $request)
    {

        $user_name_id = auth()->user()->id;

        $subjects = [];

        $currentClasses = Session::get('currentClasses');

        $getAys = AcademicYear::pluck('name', 'id');

        $timetable = ClassTimeTableTwo::whereIn('class_name', $currentClasses)->where(['staff' => $user_name_id, 'status' => 1])->groupby('class_name', 'subject')->select('class_name', 'subject')->get();

        if (count($timetable) > 0) {
            foreach ($timetable as $data) {
                array_push($subjects, [$data->subject, $data->class_name]);
            }
        }

        $got_subjects = [];

        for ($i = 0; $i < count($subjects); $i++) {

            $get_enroll = CourseEnrollMaster::where(['id' => $subjects[$i][1]])->first();

            $get_subjects = Subject::where(['id' => $subjects[$i][0]])->first();

            $get_course = explode('/', $get_enroll->enroll_master_number);
            $get_short_form = ToolsCourse::where('name', $get_course[1])->select('short_form')->first();

            if ($get_short_form) {
                $get_course[1] = $get_short_form->short_form;
                $subjects[$i][1] = $get_course[1] . ' / ' . $get_course[3] . ' / ' . $get_course[4];
            }
            if ($subjects[$i][0] != 'Library') {
                if ($get_subjects) {
                    array_push($got_subjects, [$get_subjects->id, $subjects[$i][1], $get_enroll->id]);
                }
            } else {
                array_push($got_subjects, ['Library', $subjects[$i][1], $get_enroll->id]);
            }

        }

        $subjects = Subject::get();
        return view('admin.staffAcademicManage.subjects', compact('got_subjects', 'subjects', 'getAys'));
    }

    public function lesson_plan(Request $request)
    {

        $user_name_id = auth()->user()->id;

        $subjects = [];
        $currentClasses = Session::get('currentClasses');
        $timetable = ClassTimeTableTwo::whereIn('class_name', $currentClasses)->where(['status' => 1, 'staff' => $user_name_id])->get();

        if (count($timetable) > 0) {
            foreach ($timetable as $data) {
                if (!in_array([$data->subject, $data->class_name], $subjects)) {
                    array_push($subjects, [$data->subject, $data->class_name]);
                }
            }
        }

        $got_subjects = [];
        for ($i = 0; $i < count($subjects); $i++) {
            // dd($subjects[$i][1]);
            $get_enroll = CourseEnrollMaster::where(['id' => $subjects[$i][1]])->first();
            $get_subjects = Subject::where(['id' => $subjects[$i][0]])->first();
            $get_course = explode('/', $get_enroll->enroll_master_number);
            $get_short_form = ToolsCourse::where('name', $get_course[1])->select('short_form')->first();

            if ($get_short_form) {
                $get_course[1] = $get_short_form->short_form;
                $subjects[$i][1] = $get_course[1] . ' / ' . $get_course[3] . ' / ' . $get_course[4];
            }
            if ($get_subjects) {
                array_push($got_subjects, [$get_subjects->id, $subjects[$i][1], $get_enroll->id]);
            }

        }

        $get_lessons = [];

        foreach ($got_subjects as $class_name) {

            $data = '';

            $subject = $class_name[0];
            $subjects = isset($class_name[0]) ? $class_name[0] : '';
            $subject_details = Subject::where('id', $subject)->first();

            $lessonPlan = LessonPlans::where(['class' => $class_name[2], 'subject' => $subject])
                ->select('status')
                ->first();

            $lessonStatus = $lessonPlan ? $lessonPlan->status : '';

            $get_lessons[] = [
                'class_name' => $class_name[2],
                'subject_name' => $subject_details->name,
                'subject_code' => $subject_details->subject_code,
                'subject_id' => $subject,
                'short_form' => $class_name[1],
                'status' => $lessonStatus,
            ];
        }
        $getAys = AcademicYear::pluck('name', 'id');

        return view('admin.staffLessonPlan.index', compact('get_lessons', 'getAys'));

    }

    public function lessonPlanAdd(Request $request)
    {

        $details = ['class' => $request->class, 'short_form' => $request->short_form, 'subject_name' => $request->subject_name, 'subject_code' => $request->subject_code, 'subject' => $request->subject];

        $get_enroll = CourseEnrollMaster::where(['id' => $request->class])->first();

        $get_course = explode('/', $get_enroll->enroll_master_number);

        $odd_array = [1, 3, 5, 7];
        $even_array = [2, 4, 6, 8];

        if (in_array($get_course[3], $odd_array)) {
            $semester_type = 'ODD';
        } elseif (in_array($get_course[3], $even_array)) {
            $semester_type = 'EVEN';
        }
        if ($get_course[3] == 1 || $get_course[3] == 2) {
            $batch = 01;
        } else if ($get_course[3] == 3 || $get_course[3] == 4) {
            $batch = 02;
        } else if ($get_course[3] == 5 || $get_course[3] == 6) {
            $batch = 03;
        } else {
            $batch = 04;
        }

        $check_calendar = CollegeCalender::where(['academic_year' => $get_course[2], 'semester_type' => $semester_type, 'batch' => $batch])->get();
        // dd($check_calendar);
        if (count($check_calendar) > 0) {
            $from_date = $check_calendar[0]->from_date;
            $to_date = $check_calendar[0]->to_date;
        } else {
            $from_date = null;
            $to_date = null;
        }

        return view('admin.staffLessonPlan.create', compact('details', 'from_date', 'to_date'));
    }

    public function lessonPlanView(Request $request)
    {
        if ($request->enroll != '' && $request->subject != '') {
            $enroll = $request->enroll;
            $subject = $request->subject;
            $status = $request->status;
            $user_name_id = auth()->user()->id;
            $name = auth()->user()->name;
            $lesson_plans = LessonPlans::where(['class' => $request->enroll, 'subject' => $request->subject, 'status' => $request->status])->get();

            $lessons = [];

            foreach ($lesson_plans as $lesson_plan) {
                if ($lesson_plan->unit_no != '') {
                    if (!in_array($lesson_plan->unit_no, $lessons)) {
                        $lessons[$lesson_plan->unit_no] = [];
                        // array_push($lessons, $lesson_plan->unit_no);
                    }
                }
            }
            foreach ($lesson_plans as $lesson_plan) {
                if ($lesson_plan->unit_no != '') {
                    array_push($lessons[$lesson_plan->unit_no], $lesson_plan);
                }
            }
            $get_subject = Subject::where(['id' => $request->subject])->first();
            $get_enroll = CourseEnrollMaster::where(['id' => $request->enroll])->first();
            $get_course = explode('/', $get_enroll->enroll_master_number);
            $get_short_form = ToolsCourse::where('name', $get_course[1])->select('short_form')->first();

            if ($get_short_form) {
                $get_course[1] = $get_short_form->short_form;
                $short_form = $get_course[1] . ' / ' . $get_course[3] . ' / ' . $get_course[4];

            } else {
                $short_form = '';
            }
            // dd($lessons);
        }
        return view('admin.staffLessonPlan.show', compact('lessons', 'short_form', 'get_subject', 'user_name_id', 'name', 'enroll', 'subject', 'status'));
    }

    public function lessonPlanEdit(Request $request)
    {
        if ($request->enroll != '' && $request->subject != '') {
            $user_name_id = auth()->user()->id;
            $name = auth()->user()->name;
            $lesson_plans = LessonPlans::where(['class' => $request->enroll, 'subject' => $request->subject, 'status' => $request->status])->get();

            $lessons = [];
            foreach ($lesson_plans as $lesson_plan) {
                if ($lesson_plan->unit_no != '') {
                    if (!in_array($lesson_plan->unit_no, $lessons)) {
                        $lessons[$lesson_plan->unit_no] = [];
                        // array_push($lessons, $lesson_plan->unit_no);
                    }
                }
            }
            foreach ($lesson_plans as $lesson_plan) {
                if ($lesson_plan->unit_no != '') {
                    array_push($lessons[$lesson_plan->unit_no], $lesson_plan);
                }
            }
            $get_subject = Subject::where(['id' => $request->subject])->first();
            $get_enroll = CourseEnrollMaster::where(['id' => $request->enroll])->first();
            $get_course = explode('/', $get_enroll->enroll_master_number);
            $get_short_form = ToolsCourse::where('name', $get_course[1])->select('short_form')->first();

            if ($get_short_form) {
                $get_course[1] = $get_short_form->short_form;
                $short_form = $get_course[1] . ' / ' . $get_course[3] . ' / ' . $get_course[4];

            } else {
                $short_form = '';
            }
        }

        return view('admin.staffLessonPlan.edit', compact('lessons', 'short_form', 'get_subject', 'user_name_id', 'name'));
    }

    public function lessonPlanComplete(Request $request)
    {
        if ($request->enroll != '' && $request->subject != '') {
            $user_name_id = auth()->user()->id;
            $name = auth()->user()->name;
            $lesson_plans = LessonPlans::where(['class' => $request->enroll, 'subject' => $request->subject, 'status' => $request->status])->get();

            $lessons = [];
            foreach ($lesson_plans as $lesson_plan) {
                if ($lesson_plan->unit_no != '') {
                    if (!in_array($lesson_plan->unit_no, $lessons)) {
                        $lessons[$lesson_plan->unit_no] = [];
                        // array_push($lessons, $lesson_plan->unit_no);
                    }
                }
            }
            foreach ($lesson_plans as $lesson_plan) {
                if ($lesson_plan->unit_no != '') {
                    array_push($lessons[$lesson_plan->unit_no], $lesson_plan);
                }
            }
            $get_subject = Subject::where(['id' => $request->subject])->first();
            $get_enroll = CourseEnrollMaster::where(['id' => $request->enroll])->first();
            $get_course = explode('/', $get_enroll->enroll_master_number);
            $get_short_form = ToolsCourse::where('name', $get_course[1])->select('short_form')->first();

            if ($get_short_form) {
                $get_course[1] = $get_short_form->short_form;
                $short_form = $get_course[1] . ' / ' . $get_course[3] . ' / ' . $get_course[4];

            } else {
                $short_form = '';
            }
        }

        return view('admin.staffLessonPlan.complete', compact('lessons', 'short_form', 'get_subject', 'user_name_id', 'name'));
    }

    public function lessonPlanPdf(Request $request)
    {

        if ($request->enroll != '' && $request->subject != '') {
            $lesson_plans = LessonPlans::where(['class' => $request->enroll, 'subject' => $request->subject, 'status' => $request->status])->get();

            $lessons = [];

            foreach ($lesson_plans as $lesson_plan) {
                if ($lesson_plan->unit_no != '') {
                    if (!in_array($lesson_plan->unit_no, $lessons)) {
                        $lessons[$lesson_plan->unit_no] = [];
                    }
                }
            }
            foreach ($lesson_plans as $lesson_plan) {
                if ($lesson_plan->unit_no != '') {
                    array_push($lessons[$lesson_plan->unit_no], $lesson_plan);
                }
            }
            $get_subject = Subject::where(['id' => $request->subject])->first();
            $get_enroll = CourseEnrollMaster::where(['id' => $request->enroll])->first();
            $get_course = explode('/', $get_enroll->enroll_master_number);
            $get_short_form = ToolsCourse::where('name', $get_course[1])->select('short_form')->first();

            if ($get_short_form) {
                $get_course[1] = $get_short_form->short_form;
                $short_form = $get_course[1] . ' / ' . $get_course[3] . ' / ' . $get_course[4];

            } else {
                $short_form = '';
            }

            $final_data = ['lessons' => $lessons, 'short_form' => $short_form, 'get_subject' => $get_subject];

            $pdf = PDF::loadView('admin.staffLessonPlan.pdf', $final_data);

            // $pdf->setPaper('A4', 'landscape');

            return $pdf->stream('Lessonplan.pdf');
            // dd($lessons);
        } else {
            return back();
        }
    }

    public function get_subjects(Request $request)
    {

        $class = $request->class_name;
        $user_name_id = auth()->user()->id;
        $staff = TeachingStaff::where(['user_name_id' => $user_name_id])->select('user_name_id')->first();
        $subjects = [];

        $timetable = ClassTimeTableTwo::where(['status' => 1, 'staff' => $staff->user_name_id, 'class_name' => $class])->get();

        if (count($timetable) > 0) {
            foreach ($timetable as $data) {

                if (!in_array($data->subject, $subjects)) {
                    array_push($subjects, $data->subject);
                }
            }
        }

        $get_subjects = Subject::select('id', 'name', 'subject_code')->get();
        $final_subjects = [];
        foreach ($subjects as $data) {
            foreach ($get_subjects as $subject) {
                if ($data == $subject->id) {
                    $let_check = LessonPlans::where(['class' => $class, 'subject' => $id])->whereIn('status', [0, 1])->get();
                    // dd($let_check);
                    if (count($let_check) <= 0) {
                        array_push($final_subjects, [$subject]);
                    }

                }
            }
        }

        // dd($final_subjects);

        return response()->json(['subjects' => $final_subjects]);
    }

    public function lessonPlanSubmit(Request $request)
    {
        // dd($request);
        if (isset($request->class) && isset($request->subject) && isset($request->form)) {
            $user_name_id = auth()->user()->id;

            // dd($request);
            $lessons = [];

            $class = $request->class;
            $subject = $request->subject;
            $no = 1;
            foreach ($request->form as $data) {
                // dd($data);
                $unit = $data[0]['value'];
                $j = 1;
                for ($i = 1; $i < count($data) - 1; $i += 4) {
                    $date = $data[$i]['value'];
                    $topic_no = $j;
                    $topic = $data[$i + 1]['value'];
                    $text_book = $data[$i + 2]['value'];
                    $delivery = $data[$i + 3]['value'];
                    $lesson_plan = ['user' => $user_name_id, 'class' => $class, 'subject' => $subject, 'unit' => $unit, 'unit_no' => $no, 'topic' => $topic, 'topic_no' => $topic_no, 'text_book' => $text_book, 'delivery' => $delivery, 'date' => $date];
                    array_push($lessons, $lesson_plan);
                    $j++;
                }
                $no++;
            }
            // dd($lessons);

            foreach ($lessons as $id => $plan) {

                if ($id < 1) {
                    $update = DB::table('lesson_plans')->where(['class' => $plan['class'], 'subject' => $plan['subject']])->delete();
                }
                // dd($plan);
                $insert = new LessonPlans;
                $insert->user_name_id = $plan['user'];
                $insert->class = $plan['class'];
                $insert->subject = $plan['subject'];
                $insert->unit = $plan['unit'];
                $insert->unit_no = $plan['unit_no'];
                $insert->topic = $plan['topic'];
                $insert->topic_no = $plan['topic_no'];
                $insert->text_book = $plan['text_book'];
                $insert->delivery_method = $plan['delivery'];
                $insert->proposed_date = $plan['date'];
                $insert->status = 0;
                $insert->save();

            }
            return response()->json(['status' => true]);
        }
    }

    public function lessonPlanSave(Request $request)
    {
        // dd($request);
        if (isset($request->class) && isset($request->subject) && isset($request->form)) {
            $user_name_id = auth()->user()->id;

            // dd($request);
            $lessons = [];

            $class = $request->class;
            $subject = $request->subject;
            $no = 1;
            foreach ($request->form as $data) {
                // dd($data);
                $unit = $data[0]['value'];
                $j = 1;
                for ($i = 1; $i < count($data) - 1; $i += 4) {
                    $date = $data[$i]['value'];
                    $topic_no = $j;
                    $topic = $data[$i + 1]['value'];
                    $text_book = $data[$i + 2]['value'];
                    $delivery = $data[$i + 3]['value'];
                    $lesson_plan = ['user' => $user_name_id, 'class' => $class, 'subject' => $subject, 'unit' => $unit, 'unit_no' => $no, 'topic' => $topic, 'topic_no' => $topic_no, 'text_book' => $text_book, 'delivery' => $delivery, 'date' => $date];
                    array_push($lessons, $lesson_plan);
                    $j++;
                }
                $no++;
            }
            // dd($lessons);

            foreach ($lessons as $id => $plan) {

                if ($id < 1) {
                    $update = DB::table('lesson_plans')->where(['class' => $plan['class'], 'subject' => $plan['subject']])->delete();
                }
                // dd($plan);
                $insert = new LessonPlans;
                $insert->user_name_id = $plan['user'];
                $insert->class = $plan['class'];
                $insert->subject = $plan['subject'];
                $insert->unit = $plan['unit'];
                $insert->unit_no = $plan['unit_no'];
                $insert->topic = $plan['topic'];
                $insert->topic_no = $plan['topic_no'];
                $insert->text_book = $plan['text_book'];
                $insert->delivery_method = $plan['delivery'];
                $insert->proposed_date = $plan['date'];
                $insert->status = 99;
                $insert->save();

            }
            return response()->json(['status' => true]);
        }
    }

    public function lessonPlanUpdate(Request $request)
    {
        // dd($request);
        if (isset($request->class) && isset($request->subject) && isset($request->form)) {
            $user_name_id = auth()->user()->id;

            // dd($request);
            $lessons = [];

            $class = $request->class;
            $subject = $request->subject;
            $no = 1;
            foreach ($request->form as $data) {
                // dd($data);
                $unit = $data[0]['value'];
                $j = 1;
                for ($i = 1; $i < count($data) - 1; $i += 4) {
                    $date = $data[$i]['value'];
                    $topic_no = $j;
                    $topic = $data[$i + 1]['value'];
                    $text_book = $data[$i + 2]['value'];
                    $delivery = $data[$i + 3]['value'];
                    $lesson_plan = ['class' => $class, 'subject' => $subject, 'unit' => $unit, 'unit_no' => $no, 'topic' => $topic, 'topic_no' => $topic_no, 'text_book' => $text_book, 'delivery' => $delivery, 'date' => $date];
                    array_push($lessons, $lesson_plan);
                    $j++;
                }
                $no++;
            }

            foreach ($lessons as $id => $plan) {
                if ($id < 1) {
                    $update = DB::table('lesson_plans')->where(['class' => $plan['class'], 'subject' => $plan['subject']])->delete();
                }
                // if ($check != '') {
                //     $id = $check->id;

                //     $update = LessonPlans::where(['id' => $id])->update([
                //         'unit' => $plan['unit'],
                //         'topic' => $plan['topic'],
                //         'text_book' => $plan['text_book'],
                //         'delivery_method' => $plan['delivery'],
                //         'proposed_date' => $plan['date'],
                //         'status' => 2,
                //     ]);

                //     $check_any = LessonPlans::where('id', '!=', $id)->where(['class' => $plan['class'], 'subject' => $plan['subject'], 'unit_no' => $plan['unit_no'], 'topic_no' => $plan['topic_no']])->update([
                //         'deleted_at' => Carbon::now(),
                //     ]);

                // } else {
                $insert = new LessonPlans;
                $insert->user_name_id = $user_name_id;
                $insert->class = $plan['class'];
                $insert->subject = $plan['subject'];
                $insert->unit = $plan['unit'];
                $insert->unit_no = $plan['unit_no'];
                $insert->topic = $plan['topic'];
                $insert->topic_no = $plan['topic_no'];
                $insert->text_book = $plan['text_book'];
                $insert->delivery_method = $plan['delivery'];
                $insert->proposed_date = $plan['date'];
                $insert->status = 0;
                $insert->save();
                // }
            }
            return response()->json(['status' => true]);
        }
    }

    public function lessonPlanDelete(Request $request)
    {
        // dd($request);
        if ($request->enroll != '' && $request->subject != '') {
            $user_name_id = auth()->user()->id;
            $name = auth()->user()->name;
            $lesson_plans = LessonPlans::where(['class' => $request->enroll, 'subject' => $request->subject])->get();

            foreach ($lesson_plans as $lesson_plan) {
                $find = LessonPlans::find($lesson_plan->id);
                $find->delete();
            }

        }
        $data = ['user_name_id' => $user_name_id, 'name' => $name, 'status' => 0];
        return redirect()->route('admin.staff-subjects.lesson-plan', $data);
    }

    public function lessonPlanHOD(Request $request)
    {

        $user_name_id = auth()->user()->id;

        $get_hod = User::where(['id' => $user_name_id])->get();

        if (count($get_hod) > 0) {
            $get_dept = $get_hod[0]->dept;
            $got_dept = ToolsDepartment::where(['name' => $get_dept])->first();
            // dd($got_dept);
            if ($got_dept != '') {
                $Dept = $got_dept->id;
            } else {
                $Dept = null;
            }
        } else {
            $get_dept = null;
            $got_dept = null;
            $Dept = null;
        }
        // dd($get_dept);
        $currentClasses = Session::get('currentClasses');
        $get_lesson_plans = LessonPlans::whereIn('class', $currentClasses)->where(['status' => $request->status])->groupBy('class', 'subject')->select('class', 'subject')->get();
        // dd($Dept);
        $lesson_plan_req = [];
        if (count($get_lesson_plans) > 0) {
            for ($i = 0; $i < count($get_lesson_plans); $i++) {

                $get_enroll = CourseEnrollMaster::where(['id' => $get_lesson_plans[$i]['class']])->first();
                $get_course = explode('/', $get_enroll->enroll_master_number);
                if ($Dept != null) {
                    if ($Dept != 5 && $get_course[3] != 1 && $get_course[3] != 2) {
                        $get_short_form = ToolsCourse::where('name', 'LIKE', $get_course[1])->where(['department_id' => $Dept])->select('short_form')->first();
                    } else if ($Dept == 5 && ($get_course[3] == 1 || $get_course[3] == 2)) {
                        $get_short_form = ToolsCourse::where(['name' => $get_course[1]])->select('short_form')->first();
                    } else {
                        $get_short_form = false;
                    }
                } else {
                    $get_short_form = ToolsCourse::where(['name' => $get_course[1]])->select('short_form')->first();
                }

                if ($get_short_form) {
                    $get_course[1] = $get_short_form->short_form;
                    $get_lesson_plans[$i]['short_form'] = $get_course[1] . ' / ' . $get_course[3] . ' / ' . $get_course[4];

                    $get_staff_id = ClassTimeTableTwo::where(['class_name' => $get_lesson_plans[$i]['class'], 'subject' => $get_lesson_plans[$i]['subject'], 'status' => 1])->select('staff')->first();
                    if ($get_staff_id != '') {
                        $subject_staff = $get_staff_id->staff;
                    } else {
                        $subject_staff = null;
                    }
                    $get_staff = User::where(['id' => $subject_staff])->first();
                    if ($get_staff != '') {
                        $staff = $get_staff->name . '  ( ' . $get_staff->employID . '  )';
                    } else {
                        $staff = null;
                    }
                    $get_lesson_plans[$i]['name'] = $staff;
                    $get_subject = Subject::where(['id' => $get_lesson_plans[$i]['subject']])->select('subject_code', 'name')->first();
                    if ($get_subject != '') {
                        $got_subject = $get_subject->name . '  ( ' . $get_subject->subject_code . '  )';
                    } else {
                        $got_subject = null;
                    }
                    $get_lesson_plans[$i]['got_subject'] = $got_subject;
                    array_push($lesson_plan_req, $get_lesson_plans[$i]);
                }
            }
        }

        $status = $request->status;

        // dd($lesson_plan_req);
        $subjects = Subject::get();
        return view('admin.staffLessonPlan.hodindex', compact('lesson_plan_req', 'subjects', 'status'));

    }

    public function lessonPlanHODView(Request $request)
    {
        if ($request->enroll != '' && $request->subject != '') {
            // dd($request->enroll,$request->subject);
            $lesson_plans = LessonPlans::where(['class' => $request->enroll, 'subject' => $request->subject, 'status' => $request->status])->get();

            $get_staff_id = ClassTimeTableTwo::where(['class_name' => $request->enroll, 'subject' => $request->subject, 'status' => 1])->select('staff')->first();
            if ($get_staff_id != '') {
                $user_name_id = $get_staff_id->staff;
            } else {
                $user_name_id = null;
            }

            $get_staff = User::where(['id' => $user_name_id])->first();
            if ($get_staff != '') {
                $name = $get_staff->name;
                $staff_code = $get_staff->employID;
            } else {
                $name = null;
                $staff_code = null;
            }
            $lessons = [];
            // dd($lesson_plans);
            foreach ($lesson_plans as $lesson_plan) {
                if ($lesson_plan->unit_no != '') {
                    if (!in_array($lesson_plan->unit_no, $lessons)) {
                        $lessons[$lesson_plan->unit_no] = [];
                        // array_push($lessons, $lesson_plan->unit_no);
                    }
                }
            }
            foreach ($lesson_plans as $lesson_plan) {
                if ($lesson_plan->unit_no != '') {
                    array_push($lessons[$lesson_plan->unit_no], $lesson_plan);
                }
            }
            $get_subject = Subject::where(['id' => $request->subject])->first();
            $get_enroll = CourseEnrollMaster::where(['id' => $request->enroll])->first();
            $get_course = explode('/', $get_enroll->enroll_master_number);
            $get_short_form = ToolsCourse::where('name', $get_course[1])->select('short_form')->first();

            if ($get_short_form) {
                $get_course[1] = $get_short_form->short_form;
                $short_form = $get_course[1] . ' / ' . $get_course[3] . ' / ' . $get_course[4];

            } else {
                $short_form = '';
            }
            // dd($lessons);
        }
        return view('admin.staffLessonPlan.hodshow', compact('lessons', 'short_form', 'get_subject', 'user_name_id', 'name', 'staff_code'));
    }

    public function lessonPlanAction(Request $request)
    {
        if ($request->data != '') {
            $data = $request->data;
            $lesson_plans = LessonPlans::where(['class' => $data['class'], 'subject' => $data['subject']])->whereIn('status', [0, 1])->get();
            if (!isset($data['rejected_reason'])) {
                $data['rejected_reason'] = null;
            }
            if ($lesson_plans->count() > 0) {
                foreach ($lesson_plans as $plan) {
                    $update = LessonPlans::where(['id' => $plan->id])->update(['status' => $data['status'], 'rejected_reason' => $data['rejected_reason']]);
                }
            }
            return response()->json(['status' => true]);
        }
    }

    public function getPastSubjectRecords(Request $request)
    {
        if (isset($request->past_ay) && isset($request->past_semester)) {

            $user_name_id = auth()->user()->id;
            // $request->past_ay = '2023-2024'; //test

            $enroll = '%/%/' . $request->past_ay . '/' . $request->past_semester . '/%';

            $getClass = CourseEnrollMaster::where('enroll_master_number', "LIKE", $enroll)->select('id', 'enroll_master_number')->get();

            $theClass = [];

            $subjects = [];

            if (count($getClass) > 0) {
                foreach ($getClass as $enrolledClass) {
                    array_push($theClass, $enrolledClass->id);
                }
            }

            $timetable = ClassTimeTableTwo::whereIn('class_name', $theClass)->where(['status' => 1, 'staff' => $user_name_id])->get();

            if (count($timetable) > 0) {
                foreach ($timetable as $data) {
                    if (!in_array([$data->subject, $data->class_name], $subjects)) {
                        array_push($subjects, [$data->subject, $data->class_name]);
                    }
                }
            }

            $got_subjects = [];

            for ($i = 0; $i < count($subjects); $i++) {

                $get_enroll = CourseEnrollMaster::where(['id' => $subjects[$i][1]])->first();

                $get_subjects = Subject::where(['id' => $subjects[$i][0]])->first();

                $get_course = explode('/', $get_enroll->enroll_master_number);
                $get_short_form = ToolsCourse::where('name', $get_course[1])->select('short_form')->first();

                if ($get_short_form) {
                    $get_course[1] = $get_short_form->short_form;
                    $subjects[$i][1] = $get_course[1] . ' / ' . $get_course[3] . ' / ' . $get_course[4];
                }
                if ($subjects[$i][0] != 'Library') {
                    if ($get_subjects) {
                        array_push($got_subjects, [$subjects[$i][1], $get_subjects->name, $get_subjects->subject_code]);
                    }
                } else {
                    array_push($got_subjects, [$subjects[$i][1], 'Library']);
                }
            }
            return response()->json(['status' => true, 'data' => $got_subjects]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function getPastLessonPlanRecords(Request $request)
    {
        if (isset($request->past_ay) && isset($request->past_semester)) {

            $user_name_id = auth()->user()->id;
            // $request->past_ay = '2023-2024'; //test

            $enroll = '%/%/' . $request->past_ay . '/' . $request->past_semester . '/%';

            $getClass = CourseEnrollMaster::where('enroll_master_number', "LIKE", $enroll)->select('id', 'enroll_master_number')->get();

            $theClass = [];

            $subjects = [];

            if (count($getClass) > 0) {
                foreach ($getClass as $enrolledClass) {
                    array_push($theClass, $enrolledClass->id);
                }
            }

            $timetable = ClassTimeTableTwo::whereIn('class_name', $theClass)->where(['status' => 1, 'staff' => $user_name_id])->get();

            if (count($timetable) > 0) {
                foreach ($timetable as $data) {
                    if (!in_array([$data->subject, $data->class_name], $subjects)) {
                        array_push($subjects, [$data->subject, $data->class_name]);
                    }
                }
            }

            $got_subjects = [];
            for ($i = 0; $i < count($subjects); $i++) {
                $get_enroll = CourseEnrollMaster::where(['id' => $subjects[$i][1]])->first();
                $get_subjects = Subject::where(['id' => $subjects[$i][0]])->first();
                $get_course = explode('/', $get_enroll->enroll_master_number);
                $get_short_form = ToolsCourse::where('name', $get_course[1])->select('short_form')->first();

                if ($get_short_form) {
                    $get_course[1] = $get_short_form->short_form;
                    $subjects[$i][1] = $get_course[1] . ' / ' . $get_course[3] . ' / ' . $get_course[4];
                }
                if ($get_subjects) {
                    array_push($got_subjects, [$get_subjects->id, $subjects[$i][1], $get_enroll->id]);
                }

            }

            $got_lessons = [];

            foreach ($got_subjects as $class_name) {

                $data = '';

                $subject = $class_name[0];
                $subjects = isset($class_name[0]) ? $class_name[0] : '';
                $subject_details = Subject::where('id', $subject)->first();

                $lessonPlan = LessonPlans::where(['class' => $class_name[2], 'subject' => $subject])
                    ->select('status')
                    ->first();

                $lessonStatus = $lessonPlan ? $lessonPlan->status : '';

                $got_lessons[] = [
                    'class_name' => $class_name[2],
                    'subject_name' => $subject_details->name,
                    'subject_code' => $subject_details->subject_code,
                    'subject_id' => $subject,
                    'short_form' => $class_name[1],
                    'status' => $lessonStatus,
                ];
            }
            return response()->json(['status' => true, 'data' => $got_lessons]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }
}
