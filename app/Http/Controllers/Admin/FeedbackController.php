<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicDetail;
use App\Models\AcademicYear;
use App\Models\Feedback;
use App\Models\Feedback_questions;
use App\Models\FeedbackSchedule;
use App\Models\GeneralFeedbackModel;
use App\Models\Section;
use App\Models\Semester;
use App\Models\ToolsCourse;
use App\Models\ToolsDegreeType;
use App\Models\ToolsDepartment;
use Carbon\Carbon;
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
                    'rating' => $request->rating,
                    'question' => $encode,
                    'created_by' => auth()->user()->id,
                ]);

                if (!empty($request->question)) {
                    foreach ($request->question as $key => $value) {
                        $data = Feedback_questions::create([
                            'feedback_id' => $create->id,
                            'question' => $value,
                        ]);
                    }
                }


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
                    'rating' => $request->rating,
                    'question' => $encode,
                    'created_by' => auth()->user()->id,
                ]);

                if ($encode) {
                    $check = Feedback_questions::where(['feedback_id' => $request->id])->delete();
                    foreach ($request->question as $key => $value) {
                        $data = Feedback_questions::create([
                            'feedback_id' => $request->id,
                            'question' => $value,
                        ]);
                    }
                }

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
            $delete = Feedback_questions::where(['feedback_id' => $request->id])->delete();
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
                return $row->feedback_type ? $row->feedback_type : '';
            });
            $table->editColumn('expiry', function ($row) {
                return $row->expiry_date ? $row->expiry_date : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? $row->status : '';
            });
            $table->editColumn('createdBy', function ($row) {
                return $row->created_by ? $row->created_by : '';
            });
            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }

        $feedback = Feedback::pluck('name', 'id');
        $sem = Semester::pluck('semester', 'id');
        $degree = ToolsDegreeType::pluck('name', 'id');
        $course = ToolsCourse::pluck('name', 'id');
        $ay = AcademicYear::pluck('name', 'id');
        $sec = Section::pluck('section', 'id')->unique();

        return view('admin.feedbackSchedule.index', compact('ay', 'sem', 'course', 'feedback', 'degree', 'sec'));
    }
    public function scheduleStore(Request $request)
    {
        // dd($request);
        if ($request->id == '') {
            if ($request != '') {
                $encode = null;
                if (!empty($request->course)) {
                    foreach ($request->course as $id => $value) {
                        if ($value != 'All') {
                            $check_course = ToolsCourse::where('id', $value)->exists();
                            if (!$check_course) {
                                return response()->json(['status' => false, 'data' => 'Course not found.']);
                            }
                        }
                    }
                    $encode = json_encode($request->course, true);
                }
                if ($request->type != 'General') {

                    $create = FeedbackSchedule::create([
                        'feedback_id' => $request->name,
                        'feedback_type' => $request->type,
                        'expiry_date' => $request->expiry,
                        'degree_id' => $request->degree,
                        'academic_id' => $request->ay,
                        'course_id' => $encode,
                        'semester' => $request->sem,
                        'section' => $request->sec,
                        'status' => $request->status,
                        'created_by' => auth()->user()->name,
                    ]);
                } else {
                    $domain = url('/');
                    $token = Str::random(32);
                    $encode_token = base64_encode($domain . '/feedback/' . $token);
                    $create = FeedbackSchedule::create([
                        'feedback_id' => $request->name,
                        'feedback_type' => $request->type,
                        'expiry_date' => $request->expiry,
                        'degree_id' => $request->degree ?? null,
                        'academic_id' => $request->ay ?? null,
                        'course_id' => $encode ?? null,
                        'semester' => $request->sem ?? null,
                        'section' => $request->sec ?? null,
                        'status' => $request->status ?? null,
                        'token_link' => $encode_token,
                        'created_by' => auth()->user()->name,
                    ]);

                }
                return response()->json(['status' => true, 'data' => 'FeedBack Created Successfully.']);

            } else {
                return response()->json(['status' => false, 'data' => 'Required datas not found.']);
            }
        } else {
            if ($request) {
                $encode = null;
                foreach ($request->course as $id => $value) {
                    if ($value != 'All') {
                        $check_course = ToolsCourse::where('id', $value)->exists();
                        if (!$check_course) {
                            return response()->json(['status' => false, 'data' => 'Course not found.']);
                        }
                    }
                }
                $encode = json_encode($request->course, true);
                $create = FeedbackSchedule::where('id', $request->id)->update([
                    'feedback_id' => $request->name,
                    'feedback_type' => $request->type,
                    'expiry_date' => $request->expiry,
                    'degree_id' => $request->degree,
                    'academic_id' => $request->ay,
                    'course_id' => $encode,
                    'semester' => $request->sem,
                    'section' => $request->sec,
                    'status' => $request->status,
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

    public function feedbackForm(Request $request)
    {
        $domain = url('/');
        $encode_token = base64_encode($domain . '/feedback/' . $request->token);
        $data = FeedbackSchedule::with('feedback')
            ->where('token_link', $encode_token)
            ->where('expiry_date', '>=', date('Y-m-d'))
            ->first();
        if ($data) {
            if (!empty($data->token_link) && !empty($data->degree_id) && !empty($data->academic_id) && !empty($data->course_id)) {
                return view('admin.feedback.internal', compact('data'));
            } elseif ($data->token_link) {
                return view('admin.feedback.general', compact('data'));
            }
        } else {
            $data = 'The link has expired or is Invalid.';
            return view('admin.feedback.expiry', compact('data'));
        }
    }

    public function feedbackStore(Request $request)
    {
        // dd($request);
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
                ->where('expiry_date', '>=', date('Y-m-d'))
                ->first();
            if (!$check) {
                $data = 'You Submited Feedback form has expired or is Invalid.';
                return view('admin.feedback.expiry', compact('data'));
            } else {

                $questions = json_decode($check->feedback->question);
                $overall_rating = $check->feedback->rating;
                $feedback_type = $check->feedback_type;
                $verify = GeneralFeedbackModel::where([
                    'feedback_id' => $check->feedback->id,
                    'feed_schedule_id' => $check->id,
                    'feedback_type' => $feedback_type,
                ])->exists();
                if ($verify) {
                    $email_exists = GeneralFeedbackModel::where([
                        'feedback_id' => $check->feedback->id,
                        'feed_schedule_id' => $check->id,
                        'feedback_type' => $feedback_type,
                    ])->whereJsonContains('emails', $request->email)->exists();
                    if ($email_exists) {
                        $data = 'You have Already Submitted the Form.';
                        return view('admin.feedback.success', compact(['data']));
                    } else {
                        $update = GeneralFeedbackModel::where([
                            'feedback_id' => $check->feedback->id,
                            'feed_schedule_id' => $check->id,
                            'feedback_type' => $feedback_type
                        ])->get();

                        foreach ($update as $key => $value) {
                            // Decode JSON columns into PHP arrays
                            $decode_email = json_decode($value->emails, true) ?? [];
                            $decode_name = json_decode($value->users, true) ?? [];
                            $decode_rate = json_decode($value->ratings, true) ?? [];
                            $r = 'ques' . ($key + 1);
                            // Add new values to arrays
                            array_push($decode_email, $request->email);
                            array_push($decode_name, $request->name);
                            array_push($decode_rate, $request->$r);
                            // Encode arrays back to JSON
                            $value->emails = json_encode($decode_email);
                            $value->users = json_encode($decode_name);
                            $value->ratings = json_encode($decode_rate);

                            // Save updated record
                            $value->save();
                        }

                        $data = 'Feedback Submitted Successfully.';
                        return view('admin.feedback.success', compact(['data']));
                    }
                    // $decode_email = json_decode($email_exists->emails);
                    // dd($email_exists);
                } else {
                    foreach ($questions as $key => $value) {
                        $decode_rate = json_encode([$request->ques . ($key + 1)]);
                        $decode_name = json_encode([$request->name]);
                        $decode_email = json_encode([$request->email]);
                        $create = GeneralFeedbackModel::create([
                            'feedback_id' => $check->feedback->id,
                            'feed_schedule_id' => $check->id,
                            'feedback_type' => $feedback_type,
                            'question_name' => $value,
                            'overall_rating' => $check->feedback->rating,
                            'ratings' => $decode_rate,
                            'users' => $decode_name,
                            'emails' => $decode_email,
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
