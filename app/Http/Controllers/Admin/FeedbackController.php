<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicDetail;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\ClassTimeTableTwo;
use App\Models\Feedback;
use App\Models\Feedback_questions;
use App\Models\FeedbackSchedule;
use App\Models\FeedbackTypeModel;
use App\Models\GeneralFeedbackModel;
use App\Models\OverAllFeedbacksModel;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectRegistration;
use App\Models\ToolsCourse;
use App\Models\ToolsDegreeType;
use App\Models\ToolsDepartment;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Requests;
use Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Base62\Base62;

class FeedbackController extends Controller
{
    public function configureIndex(Request $request)
    {
        if ($request->ajax()) {
            $query = Feedback::with('users')->get();
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'configure_feedback_show';
                $editGate = 'configure_feedback_edit';
                $deleteGate = 'configure_feedback_delete';
                $crudRoutePart = 'configure-feedback';
                $viewFunct = 'viewfeedback';
                $editFunct = 'editfeedback';
                $deleteFunct = 'deletefeedback';

                return view(
                    'partials.ajaxTableActions',
                    compact(
                        'viewGate',
                        'editGate',
                        'deleteGate',
                        'crudRoutePart',
                        'viewFunct',
                        'editFunct',
                        'deleteFunct',
                        'row'
                    )
                );
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('updated_at', function ($row) {
                $explode = explode(' ', $row->updated_at);
                return $explode[0];
            });
            $table->editColumn('createdBy', function ($row) {
                return $row->users ? $row->users->name : '';
            });

            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }
        return view('admin.feedbackConfigure.index');
    }
    public function configureStore(Request $request)
    {
        // dd($request);
        if ($request->id == '') {
            if (($request->name != '' && $request->rating != '') || $request->question != '') {
                $encode = null;
                if (!empty($request->question)) {
                    $encode = json_encode($request->question);
                }

                $create = Feedback::create([
                    'name' => $request->name,
                    'rating' => $request->rate,
                    'question' => $encode,
                    'created_by' => auth()->user()->id,
                ]);

                return response()->json(['status' => true, 'data' => 'FeedBack Created Successfully.']);

            } else {
                return response()->json(['status' => false, 'data' => 'Required datas not found.']);
            }
        } else {
            if (($request->name != '' && $request->rating != '') || $request->question != '') {
                $encode = null;
                if (!empty($request->question)) {
                    $encode = json_encode($request->question);
                }

                $create = Feedback::where('id', $request->id)->update([
                    'name' => $request->name,
                    // 'feedback_type' => $request->type,
                    'rating' => $request->rate,
                    'question' => $encode,
                    'created_by' => auth()->user()->id,
                ]);

                return response()->json(['status' => true, 'data' => 'FeedBack Updated Successfully.']);

            } else {
                return response()->json(['status' => false, 'data' => 'Required datas not found.']);
            }
        }
    }

    public function configureView(Request $request)
    {
        if (isset($request->id)) {
            $data = Feedback::where(['id' => $request->id])->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function configureEdit(Request $request)
    {
        if (isset($request->id)) {
            $data = Feedback::where(['id' => $request->id])->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function configureDestroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = Feedback::where(['id' => $request->id])->delete();
            return response()->json(['status' => 'success', 'data' => 'FeedBack Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }


    public function scheduleIndex(Request $request)
    {
        if ($request->ajax()) {
            $query = FeedbackSchedule::with('feedback')->get();
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'schedule_feedback_show';
                $editGate = 'schedule_feedback_edit';
                $deleteGate = 'schedule_feedback_delete';
                $crudRoutePart = 'schedule-feedback';
                $viewFunct = 'viewfeedback';
                $editFunct = 'editfeedback';
                $deleteFunct = 'deletefeedback';

                return view(
                    'partials.ajaxTableActions',
                    compact(
                        'viewGate',
                        'editGate',
                        'deleteGate',
                        'crudRoutePart',
                        'viewFunct',
                        'editFunct',
                        'deleteFunct',
                        'row'
                    )
                );
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->feedback ? $row->feedback->name : '';
            });
            $table->editColumn('type', function ($row) {
                return $row->feedback_participant ? $row->feedback_participant : '';
            });
            $table->editColumn('expiry', function ($row) {
                return $row->expiry_date ? $row->expiry_date : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status;
            });
            $table->editColumn('createdBy', function ($row) {
                return $row->created_by ? $row->created_by : '';
            });
            $table->rawColumns(['actions', 'placeholder', 'status']);
            return $table->make(true);
        }

        $batch = Batch::pluck('name', 'id');
        $feedback = Feedback::select('name', 'id', 'feedback_type')->get();
        $sem = Semester::pluck('semester', 'id');
        $degree = ToolsDegreeType::pluck('name', 'id');
        $dept = ToolsDepartment::pluck('name', 'id');
        $course = ToolsCourse::pluck('short_form', 'id');
        $ay = AcademicYear::pluck('name', 'id');
        $sec = Section::pluck('section', 'id')->unique();
        $feed_type = FeedbackTypeModel::select('feedback_type', 'feedback_participant')->get();
        return view('admin.feedbackSchedule.index', compact('feed_type', 'dept', 'batch', 'ay', 'sem', 'course', 'feedback', 'degree', 'sec'));
    }
    public function scheduleStore(Request $request)
    {
        // dd($request);
        if ($request->id == '') {
            if ($request->participant != '') {
                if (!empty($request->course)) {
                    $check_course = ToolsCourse::where('id', $request->course)->exists();
                    if (!$check_course) {
                        return response()->json(['status' => false, 'data' => 'Course not found.']);
                    }
                }

                $training = null;
                if ($request->type == 'training feedback') {
                    $training = [
                        'type_training' => $request->type_training,
                        'title_training' => $request->title_training,
                        'from_time' => $request->from_time,
                        'to_time' => $request->to_time,
                        'person_training' => $request->person_training
                    ];
                    $training = json_encode($training);
                }

                if ($request->participant == 'external') {
                    $domain = url('/');
                    $token = Str::random(32);
                    $encode_token = base64_encode($domain . '/feedback/' . $token);
                    $create = FeedbackSchedule::create([
                        'feedback_id' => $request->name,
                        'feedback_participant' => $request->participant,
                        'feedback_type' => $request->type,
                        'expiry_date' => $request->expiry,
                        'start_date' => $request->start,
                        'status' => '0',
                        'token_link' => $encode_token,
                        'created_by' => auth()->user()->name,
                    ]);
                } else {
                    $create = FeedbackSchedule::create([
                        'feedback_id' => $request->name,
                        'feedback_participant' => $request->participant,
                        'feedback_type' => $request->type,
                        'training' => $training,
                        'expiry_date' => $request->expiry,
                        'start_date' => $request->start,
                        'degree_id' => $request->degree,
                        //'department_id' => $dept_encode,
                        'academic_id' => $request->ay,
                        'batch_id' => $request->batch,
                        'course_id' => $request->course,
                        'semester' => $request->sem,
                        'section' => $request->sec,
                        'status' => '0',
                        'created_by' => auth()->user()->name,
                    ]);

                }
                return response()->json(['status' => true, 'data' => 'FeedBack Created Successfully.']);

            } else {
                return response()->json(['status' => false, 'data' => 'Required datas not found.']);
            }
        } else {
            if ($request->participant != '') {
                if (!empty($request->course)) {
                    $check_course = ToolsCourse::where('id', $request->course)->exists();
                    if (!$check_course) {
                        return response()->json(['status' => false, 'data' => 'Course not found.']);
                    }
                }

                $training = null;
                if ($request->type == 'training feedback') {
                    $training = [
                        'type_training' => $request->type_training,
                        'title_training' => $request->title_training,
                        'from_time' => $request->from_time,
                        'to_time' => $request->to_time,
                        'person_training' => $request->person_training
                    ];
                    $training = json_encode($training);
                }

                $create = FeedbackSchedule::where('id', $request->id)->update([
                    'feedback_id' => $request->name,
                    'feedback_participant' => $request->participant,
                    'feedback_type' => $request->type,
                    'training' => $training,
                    'expiry_date' => $request->expiry,
                    'start_date' => $request->start,
                    'degree_id' => $request->degree,
                    'academic_id' => $request->ay,
                    'batch_id' => $request->batch,
                    'course_id' => $request->course,
                    'semester' => $request->sem,
                    'section' => $request->sec,
                    'status' => '0',
                    'created_by' => auth()->user()->name,
                ]);

                return response()->json(['status' => true, 'data' => 'FeedbackSchedule Updated Successfully.']);

            } else {
                return response()->json(['status' => false, 'data' => 'Required datas not found.']);
            }
        }
    }

    public function scheduleView(Request $request)
    {
        if (isset($request->id)) {
            $data = FeedbackSchedule::where(['id' => $request->id])->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function scheduleEdit(Request $request)
    {
        if (isset($request->id)) {
            $data = FeedbackSchedule::where(['id' => $request->id])->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function scheduleDestroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = FeedbackSchedule::where(['id' => $request->id])->delete();
            return response()->json(['status' => 'success', 'data' => 'FeedbackSchedule Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function change_status(Request $request)
    {
        // dd($request);
        if (isset($request->id) && isset($request->status)) {
            $update = FeedbackSchedule::where(['id' => $request->id])->update(['status' => $request->status]);
            // dd($update);
            return response()->json(['status' => true, 'data' => 'Feedback Schedule\'s Status Modified']);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function fetchCourse(Request $request)
    {
        if ($request->id) {
            if ($request->id != 'All') {
                $get = ToolsCourse::where('degree_type_id', $request->id)->pluck('short_form', 'id');
            } else {
                $get = ToolsCourse::pluck('short_form', 'id');
            }
            if ($get) {
                return response()->json(['status' => true, 'data' => $get]);
            } else {
                return response()->json(['status' => false, 'data' => 'Course Not Found']);
            }
        }
    }
    public function fetchSection(Request $request)
    {
        // dd($request);
        if ($request->id) {
            if ($request->id != 'All') {
                $get = Section::where('course_id', $request->id)->pluck('section', 'id');
            } else {
                $get = Section::pluck('section', 'id');
            }
            if ($get) {
                return response()->json(['status' => true, 'data' => $get]);
            } else {
                return response()->json(['status' => false, 'data' => 'Section Not Found']);
            }
        }
    }

    public function feedbackForm(Request $request)
    {
        $domain = url('/');
        $encode_token = base64_encode($domain . '/feedback/' . $request->token);
        $data = FeedbackSchedule::with('feedback')
            ->where('token_link', $encode_token)
            ->where('expiry_date', '>=', date('Y-m-d'))
            ->where('status', 1)
            ->first();

        $course = ToolsCourse::where('id', json_decode($data->course_id))->select('short_form', 'id')->first();
        // dd($course);
        if ($data) {
            if ($data->token_link) {
                // $dept = ToolsDepartment::pluck('name', 'id');
                $rating = $data->feedback->rating;

                return view('admin.feedback.external', compact('data', 'rating', 'course'));
            }
        } else {
            $data = 'The link has expired or is Invalid.';
            return view('admin.feedback.expiry', compact('data'));
        }
    }

    public function feedbackStore(Request $request)
    {
        if (!empty($request->feed_id) && !empty($request->feedback_id) && !empty($request->name)) {
            $rules = [
                'email' => 'required|email',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return back()->with(['errors' => $validator->errors()], 422);
            }

            $check = FeedbackSchedule::with('feedback')
                ->where('id', $request->feed_id)
                ->where('course_id', $request->course)
                ->where('expiry_date', '>=', date('Y-m-d'))
                ->first();
            // dd($check);
            if (!$check) {
                $data = 'You Submited Feedback form has expired or is Invalid.';
                return view('admin.feedback.expiry', compact('data'));
            } else {

                $questions = json_decode($check->feedback->question);
                $overall_rating = $check->feedback->rating;
                $feedback_participant = $check->feedback_participant;
                $verify = OverAllFeedbacksModel::where([
                    'feedback_id' => $check->feedback->id,
                    'feed_schedule_id' => $check->id,
                    'feedback_participant' => $feedback_participant,
                    // //'department_id' => $request->dept
                ])->exists();
                if ($verify) {
                    $email_exists = OverAllFeedbacksModel::where([
                        'feedback_id' => $check->feedback->id,
                        'feed_schedule_id' => $check->id,
                        'feedback_participant' => $feedback_participant,
                        // //'department_id' => $request->dept
                    ])->whereJsonContains('emails', $request->email)->exists();
                    if ($email_exists) {
                        $data = 'You have Already Submitted the Form.';
                        return view('admin.feedback.success', compact(['data']));
                    } else {
                        $update = OverAllFeedbacksModel::where([
                            'feedback_id' => $check->feedback->id,
                            'feed_schedule_id' => $check->id,
                            'feedback_participant' => $feedback_participant,
                            // //'department_id' => $request->dept
                        ])->get();

                        foreach ($update as $key => $value) {

                            $decode_email = json_decode($value->emails, true) ?? [];
                            $decode_name = json_decode($value->users, true) ?? [];
                            $decode_rate = json_decode($value->ratings, true) ?? [];
                            $r = 'ques' . ($key + 1);

                            array_push($decode_email, $request->email);
                            array_push($decode_name, $request->name);
                            array_push($decode_rate, $request->$r);

                            $value->emails = json_encode($decode_email);
                            $value->users = json_encode($decode_name);
                            $value->ratings = json_encode($decode_rate);
                            $value->course_id = $check->course_id;

                            $value->save();
                        }

                        $data = 'Feedback Submitted Successfully.';
                        return view('admin.feedback.success', compact(['data']));
                    }

                } else {
                    foreach ($questions as $key => $value) {
                        $decode_rate = json_encode([$request->ques . ($key + 1)]);
                        $decode_name = json_encode([$request->name]);
                        $decode_email = json_encode([$request->email]);
                        $create = OverAllFeedbacksModel::create([
                            'feedback_id' => $check->feedback->id,
                            'feed_schedule_id' => $check->id,
                            'feedback_participant' => $feedback_participant,
                            'question_name' => $value,
                            // //'department_id' => $request->dept,
                            'overall_rating' => $check->feedback->rating,
                            'ratings' => $decode_rate,
                            'users' => $decode_name,
                            'emails' => $decode_email,
                            'course_id' => $check->course_id,
                        ]);
                    }
                    $data = 'Feedback Submitted Successfully.';
                    return view('admin.feedback.success', compact(['data']));
                }
                // dd($create);
            }
        } else {
            return back()->with(['errors' => 'Enter All Fields.'], 422);
        }
    }

    public function studentIndex(Request $request)
    {
        $user_id = auth()->user()->id;
        $student = Student::with('enroll_master')->where(['user_name_id' => $user_id])->first();
        $enroll_id = $student->enroll_master->id;
        $enroll_batch_id = $student->enroll_master->batch_id;
        $enroll_academic_id = $student->enroll_master->academic_id;
        $enroll_course_id = $student->enroll_master->course_id;
        $enroll_semester_id = $student->enroll_master->semester_id;
        $enroll_section = $student->enroll_master->section;

        $today = Carbon::today()->toDateString();
        // dd($student->enroll_master->course_id);
        // Query the database
        $feedback = FeedbackSchedule::with('feedback', 'overall_feedbacks')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('expiry_date', '>=', $today)
            ->where('feedback_participant', 'student')
            ->where('status', '1')
            ->where(function ($query) use ($user_id) {
                $query->where(function ($q) use ($user_id) {
                    $q->whereHas('overall_feedbacks', function ($subQuery) use ($user_id) {
                        $subQuery->whereJsonDoesntContain('users', $user_id);
                    });
                })->orWhereDoesntHave('overall_feedbacks');
            })
            ->get();
        // dd($feedback, $user_id);
        $subject = [];
        $training = [];
        $is_valid = false;
        if ($enroll_id != '' && $feedback != null) {
            foreach ($feedback as $id => $item) {
                if ($item->feedback_type == 'course feedback' || $item->feedback_type == 'training feedback') {
                    $decode_course_id = json_decode($item->course_id);
                    $section = $item->section;
                    $academic_id = $item->academic_id;
                    $semester_id = $item->semester;
                    $batch_id = $item->batch_id;
                    if ($decode_course_id[0] != 'All') {
                        $sec_valid = ($enroll_section == $section || $section == 'All') ? true : false;
                        $sem_valid = ($enroll_semester_id == $semester_id || $semester_id == 'All') ? true : false;
                        $c_valid = in_array($enroll_course_id, $decode_course_id) ? true : false;
                        $a_valid = ($enroll_academic_id == $academic_id || $academic_id == 'All') ? true : false;
                        $b_valid = ($enroll_batch_id == $batch_id || $batch_id == 'All') ? true : false;
                        if ($sec_valid == true && $sem_valid == true && $c_valid == true && $a_valid == true && $b_valid == true) {
                            $is_valid = true;
                        } else {
                            $is_valid = false;
                        }
                    } elseif ($decode_course_id[0] == 'All') {
                        $is_valid = true;
                    } else {
                        $is_valid = false;
                    }

                    if ($is_valid == true && $item->feedback_type == 'course feedback') {
                        $get_subjects = SubjectRegistration::where(['user_name_id' => $user_id, 'enroll_master' => $enroll_id])->select('subject_id')->get();
                        foreach ($get_subjects as $key => $value) {
                            $check = ClassTimeTableTwo::with('staffs', 'subjects')->where(['class_name' => $enroll_id, 'subject' => $value->subject_id])->select('staff', 'subject')->first();
                            if ($check) {
                                $overall_feed = OverAllFeedbacksModel::where(['staff_id' => $check->staff, 'feed_schedule_id' => $item->id])->whereJsonContains('users', (string) $user_id)->exists();
                                // dd($overall_feed, $check->staff);
                                if (!$overall_feed) {
                                    if ($check->subjects && $check->staffs) {
                                        $subject[$value->subject_id] = [
                                            'name' => $check->subjects->name,
                                            'code' => $check->subjects->subject_code,
                                            'staff' => $check->staffs->name,
                                            'staff_id' => $check->staff,
                                            'feedback_name' => $item->feedback->name,
                                            'feedback_id' => $item->id,
                                            'feed_id' => $item->feedback_id,
                                            'expiry_date' => $item->expiry_date,
                                        ];
                                    }
                                }
                            }
                        }

                    } elseif ($is_valid == true && $item->feedback_type == 'training feedback') {
                        $overall_feed = OverAllFeedbacksModel::where(['feedback_type' => 'training feedback', 'feed_schedule_id' => $item->id])->whereJsonContains('users', (string) $user_id)->exists();
                        // dd($overall_feed, $user_id);
                        if (!$overall_feed) {
                            $decode = json_decode($item->training);
                            // dd($decode);
                            $from_time = $decode->from_time;
                            $to_time = $decode->to_time;

                            $dateTime1 = new DateTime($from_time);
                            $dateTime2 = new DateTime($to_time);

                            $interval = $dateTime1->diff($dateTime2);
                            $training[] = [
                                'title' => $decode->title_training,
                                'duration' => $interval->format('%h hours %i minutes'),
                                'person' => $decode->person_training,
                                'staff_id' => null,
                                'feedback_name' => $item->feedback->name,
                                'feedback_id' => $item->id,
                                'feed_id' => $item->feedback_id,
                                'expiry_date' => $item->expiry_date,

                            ];
                        }
                    }
                }
            }

            // dd($training);
            // dd($subject, $training);
            return view('admin.feedback.studentIndex', compact('subject', 'training'));

        } else {

        }
    }

    public function studentFeedSurvey(Request $request)
    {
        // dd($request);
        if ($request->feedback_id != '' && $request->datas != '') {
            $schedule = FeedbackSchedule::with('feedback')->where('expiry_date', '>=', date('Y-m-d'))->find($request->feedback_id);
            $question = [];
            if ($schedule) {
                foreach (json_decode($schedule->feedback->question) as $key => $value) {
                    $question[] = $value;
                }
                $datas = $request->datas;
                $rating = $schedule->feedback->rating;
                $question = json_encode($question);
                // dd($question);
                return view('admin.feedback.student', compact('datas', 'question', 'rating'));
            } else {
                $data = 'The link has expired or is Invalid.';
                return view('admin.feedback.expiry', compact('data'));
            }
        }
    }

    public function studentFeedStore(Request $request)
    {
        // dd($request);
        if (!empty($request->user_id) && !empty($request->feedback_id) && !empty($request->name)) {
            $rules = [
                'name' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return view('admin.feedback.student')->with(['errors' => $validator->errors(), 'datas' => $request->datas, 'question' => $request->question], 422);
            }

            $check = FeedbackSchedule::with('feedback')
                ->where('id', $request->feedback_id)
                ->where('expiry_date', '>=', date('Y-m-d'))
                ->first();
            if (!$check) {
                $data = 'You Submited Feedback form has expired or is Invalid.';
                return view('admin.feedback.expiry', compact('data'));
            } else {
                $questions = json_decode($check->feedback->question);
                $overall_rating = $check->feedback->rating;
                $feedback_participant = $check->feedback_participant;
                $feedback_type = $check->feedback_type;
                $verify = OverAllFeedbacksModel::where([
                    'feedback_id' => $check->feedback->id,
                    'feed_schedule_id' => $check->id,
                    'feedback_participant' => $feedback_participant,
                    'feedback_type' => $feedback_type,
                    'staff_id' => $request->staff_id
                ])->exists();
                if ($verify) {
                    $email_exists = OverAllFeedbacksModel::where([
                        'feedback_id' => $check->feedback->id,
                        'feed_schedule_id' => $check->id,
                        'feedback_participant' => $feedback_participant,
                        'feedback_type' => $feedback_type,
                        'staff_id' => $request->staff_id
                    ])->whereJsonContains('users', $request->user_id)->exists();
                    if ($email_exists) {
                        $data = 'You have Already Submitted the Form.';
                        return view('admin.feedback.success', compact(['data']));
                    } else {
                        $update = OverAllFeedbacksModel::where([
                            'feedback_id' => $check->feedback->id,
                            'feed_schedule_id' => $check->id,
                            'feedback_participant' => $feedback_participant,
                            'feedback_type' => $feedback_type,
                            'staff_id' => $request->staff_id
                        ])->get();

                        foreach ($update as $key => $value) {
                            $decode_email = json_decode($value->emails, true) ?? [];
                            $decode_name = json_decode($value->users, true) ?? [];
                            $decode_rate = json_decode($value->ratings, true) ?? [];
                            $ques = 'ques' . ($key + 1);
                            $decode_rate[$request->user_id] = $request->$ques;
                            array_push($decode_name, $request->user_id);
                            // dd($decode_rate);

                            $value->users = json_encode($decode_name);
                            $value->ratings = json_encode($decode_rate);

                            $value->save();
                        }

                        $data = 'Feedback Submitted Successfully.';
                        return view('admin.feedback.success', compact(['data']));
                    }
                } else {
                    foreach ($questions as $key => $value) {
                        $decode_rate = json_encode([$request->user_id => $request->ques . ($key + 1)]);
                        $decode_id = json_encode([$request->user_id]);
                        $create = OverAllFeedbacksModel::create([
                            'feedback_id' => $check->feedback->id,
                            'feed_schedule_id' => $check->id,
                            'feedback_participant' => strtolower($feedback_participant),
                            'feedback_type' => $feedback_type,
                            'staff_id' => $request->staff_id ?? null,
                            'question_name' => $value,
                            'overall_rating' => $check->feedback->rating,
                            'ratings' => $decode_rate,
                            'users' => $decode_id,
                            // 'emails' => $decode_email,
                        ]);
                    }
                    $data = 'Feedback Submitted Successfully.';
                    return view('admin.feedback.success', compact(['data']));
                }
                // dd($create);
            }
        }
    }

    public function staffIndex(Request $request)
    {
        $user_id = auth()->user()->id;
        $dept = auth()->user()->dept;
        $dept = ToolsDepartment::where('name', $dept)->first();
        $course = ToolsCourse::where('department_id', $dept->id)->value('id');
        $today = Carbon::today()->toDateString();

        $data = FeedbackSchedule::with('feedback', 'overall_feedbacks')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('expiry_date', '>=', $today)
            ->where('feedback_participant', 'faculty')
            ->where('feedback_type', 'faculty feedback')
            ->where('course_id', $course)
            ->where('status', '1')
            ->where(function ($query) use ($user_id) {
                $query->where(function ($q) use ($user_id) {
                    $q->whereHas('overall_feedbacks', function ($subQuery) use ($user_id) {
                        $subQuery->whereJsonDoesntContain('users', $user_id);
                    });
                })->orWhereDoesntHave('overall_feedbacks');
            })
            ->get();
        return view('admin.feedback.staffIndex', compact('data'));
    }

    public function staffFeedSurvey(Request $request)
    {
        // dd($request);
        if ($request->feedback_id != '' && $request->datas != '') {
            $schedule = FeedbackSchedule::where('expiry_date', '>=', date('Y-m-d'))->find($request->feedback_id);
            $question = [];
            if ($schedule) {
                foreach (json_decode($schedule->feedback->question) as $key => $value) {
                    $question[] = $value;
                }
                $datas = $request->datas;
                $rating = $schedule->feedback->rating;

                return view('admin.feedback.staff', compact('datas', 'question', 'rating'));
            } else {
                $data = 'The link has expired or is Invalid.';
                return view('admin.feedback.expiry', compact('data'));
            }
        }

    }

    public function staffFeedStore(Request $request)
    {
        // dd($request);
        if (!empty($request->feedback_id) && !empty($request->name)) {
            $rules = [
                'name' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return view('admin.feedback.staff')->with(['errors' => $validator->errors(), 'datas' => $request->datas], 422);
            }
            $dept = ToolsDepartment::where('name', auth()->user()->dept)->first();
            $course = ToolsDepartment::where('department_id', $dept->id)->value('id');
            // dd($request, $dept);

            $check = FeedbackSchedule::with('feedback')
                ->where('id', $request->feed_id)
                ->where('expiry_date', '>=', date('Y-m-d'))
                ->where('course_id', $course)
                ->where('status', 1)
                ->first();
            // dd($check, $request);
            if (!$check) {
                $data = 'You Submited Feedback form has expired or is Invalid.';
                return view('admin.feedback.expiry', compact('data'));
            } else {
                $questions = json_decode($check->feedback->question);
                $overall_rating = $check->feedback->rating;
                $feedback_participant = $check->feedback_participant;
                $feedback_type = $check->feedback_type;
                $verify = OverAllFeedbacksModel::where([
                    'feedback_id' => $check->feedback->id,
                    'feed_schedule_id' => $check->id,
                    'feedback_participant' => $feedback_participant,
                    'feedback_type' => $feedback_type,
                    'course_id' => $course,
                ])->exists();
                // dd($feedback_type, $feedback_participant);
                if ($verify) {
                    $email_exists = OverAllFeedbacksModel::where([
                        'feedback_id' => $check->feedback->id,
                        'feed_schedule_id' => $check->id,
                        'feedback_participant' => $feedback_participant,
                        'feedback_type' => $feedback_type,
                        'course_id' => $course,
                    ])->whereJsonContains('users', auth()->user()->id)->exists();
                    if ($email_exists) {
                        $data = 'You have Already Submitted the Form.';
                        return view('admin.feedback.success', compact(['data']));
                    } else {
                        $update = OverAllFeedbacksModel::where([
                            'feedback_id' => $check->feedback->id,
                            'feed_schedule_id' => $check->id,
                            'feedback_participant' => $feedback_participant,
                            'feedback_type' => $feedback_type,
                            'course_id' => $dept->id,
                        ])->get();
                        // dd($update);
                        foreach ($update as $key => $value) {
                            $decode_email = json_decode($value->emails, true) ?? [];
                            $decode_name = json_decode($value->users, true) ?? [];
                            $decode_rate = json_decode($value->ratings, true) ?? [];
                            $ques = 'ques' . ($key + 1);
                            $decode_rate[$request->user_id] = $request->$ques;
                            array_push($decode_name, $request->user_id);
                            // dd($decode_rate);

                            $value->users = json_encode($decode_name);
                            $value->ratings = json_encode($decode_rate);

                            $value->save();
                        }

                        $data = 'Feedback Submitted Successfully.';
                        return view('admin.feedback.success', compact(['data']));
                    }
                } else {
                    foreach ($questions as $key => $value) {
                        $decode_rate = json_encode([auth()->user()->id => $request->ques . ($key + 1)]);
                        $decode_id = json_encode([auth()->user()->id]);
                        // $decode_email = null;
                        $create = OverAllFeedbacksModel::create([
                            'feedback_id' => $check->feedback->id,
                            'feed_schedule_id' => $check->id,
                            'feedback_participant' => $feedback_participant,
                            'feedback_type' => $feedback_type,
                            'question_name' => $value,
                            'overall_rating' => $check->feedback->rating,
                            'course_id' => $course,
                            'ratings' => $decode_rate,
                            'users' => $decode_id,
                            // 'emails' => $decode_email,
                        ]);
                    }
                    $data = 'Feedback Submitted Successfully.';
                    return view('admin.feedback.success', compact(['data']));
                }
                // dd($create);
            }
        }
    }
}