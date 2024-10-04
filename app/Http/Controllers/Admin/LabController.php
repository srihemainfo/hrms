<?php

namespace App\Http\Controllers\Admin;

ini_set('memory_limit', '256M');
ini_set('max_execution_time', 600);
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AddLab;
use App\Models\Batch;
use App\Models\ExamRegistration;
use App\Models\PracticalMark;
use App\Models\Subject;
use App\Models\SubjectType;
use App\Models\TeachingStaff;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use App\Models\Year;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class LabController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('tool_lab_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = AddLab::with(['department'])->select(sprintf('%s.*', (new AddLab)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'show_lab_access';
                $editGate = 'edit_lab_access';
                $deleteGate = 'delete_lab_access';
                $crudRoutePart = 'tool-lab';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                )
                );
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->addColumn('lab_name', function ($row) {
                return $row->lab_name ? $row->lab_name : '';
            });
            $table->addColumn('lab_incharge', function ($row) {
                return $row->lab_incharge ? $row->lab_incharge : '';
            });
            $table->editColumn('department', function ($row) {
                return $row->department ? $row->department->name : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.addLab.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = ToolsDepartment::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $teaching_staffs = TeachingStaff::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.addLab.create', compact('departments', 'teaching_staffs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request) {
            // dd($request);
            $store = new AddLab;
            $store->dept = $request->dept;
            $store->lab_name = $request->lab_name;
            // $store->lab_incharge = '';
            $store->save();
        }
        return view('admin.addLab.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort_if(Gate::denies('show_lab_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lab = AddLab::where(['id' => $id])->first();

        $lab->load('department');

        // dd($lab);

        return view('admin.addLab.show', compact('lab'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lab = AddLab::where(['id' => $id])->first();

        $departments = ToolsDepartment::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $teaching_staffs = TeachingStaff::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $lab->load('department');

        // dd($lab);

        return view('admin.addLab.edit', compact('lab', 'departments', 'teaching_staffs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if ($request) {
            $update = AddLab::where(['id' => $request->id])->update([
                'dept' => $request->dept,
                'lab_name' => $request->lab_name,
            ]);
        }
        return view('admin.addLab.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = AddLab::find($id);

        $delete->delete();

        return back();

    }

    public function practicalIndex(Request $request)
    {
        $courses = ToolsCourse::pluck('short_form', 'id');
        $batches = Batch::pluck('name', 'id');
        $ays = AcademicYear::pluck('name', 'id');
        $years = Year::pluck('year', 'id');
        return view('admin.practicalMark.index', compact('courses', 'batches', 'ays', 'years'));
    }

    public function getSubjects(Request $request)
    {
        if (isset($request->batch) && isset($request->ay) && isset($request->course) && isset($request->exam_month) && isset($request->exam_year) && isset($request->semester) && isset($request->exam_type)) {
            $getData = ExamRegistration::with('subject:id,subject_code,name,subject_type_id')->where(['batch' => $request->batch, 'academic_year' => $request->ay, 'course' => $request->course, 'semester' => $request->semester, 'exam_type' => $request->exam_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year])->groupBy('subject_id', 'subject_name')->select('subject_id', 'subject_name')->get();
            $getSubTypes = SubjectType::whereIn('name', ['LAB ORIENTED THEORY', 'LABORATORY', 'PROJECT'])->select('id')->get();
            $subTypes = [];
            if (count($getSubTypes) > 0) {
                foreach ($getSubTypes as $data) {
                    $subTypes[] = $data->id;
                }
            } else {
                return response()->json(['status' => false, 'data' => 'Subject Types Not Found']);
            }
            $getSubjects = [];
            if (count($getData) > 0) {
                foreach ($getData as $data) {
                    if ($data->subject != null && in_array($data->subject->subject_type_id, $subTypes)) {
                        $getSubjects[] = $data;
                    }
                }
            }
            return response()->json(['status' => true, 'data' => $getSubjects]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function getStudents(Request $request)
    {
        if (isset($request->batch) && isset($request->ay) && isset($request->course) && isset($request->exam_month) && isset($request->exam_year) && isset($request->semester) && isset($request->exam_type) && isset($request->subject)) {
            $getData = PracticalMark::with('student:user_name_id,name,register_no')->where(['batch' => $request->batch, 'ay' => $request->ay, 'course' => $request->course, 'exam_type' => $request->exam_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'semester' => $request->semester, 'subject' => $request->subject])->select('user_name_id', 'mark', 'mark_in_word', 'action')->get();
            $exist = true;
            if (count($getData) <= 0) {
                // $getData = ExamRegistration::where(['batch' => $request->batch, 'academic_year' => $request->ay, 'course' => $request->course, 'semester' => $request->semester, 'exam_type' => $request->exam_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'subject_id' => $request->subject])->groupBy('user_name_id')->select('user_name_id')->get();
                $getData = DB::table('exam_registration')
                    ->leftJoin('students', 'exam_registration.user_name_id', '=', 'students.user_name_id')
                    ->where('exam_registration.batch', $request->batch)
                    ->where('exam_registration.academic_year', $request->ay)
                    ->where('exam_registration.course', $request->course)
                    ->where('exam_registration.semester', $request->semester)
                    ->where('exam_registration.exam_type', $request->exam_type)
                    ->where('exam_registration.exam_month', $request->exam_month)
                    ->where('exam_registration.exam_year', $request->exam_year)
                    ->where('exam_registration.subject_id', $request->subject)
                    ->whereNull('students.deleted_at')
                    ->whereNull('exam_registration.deleted_at')
                    ->select('exam_registration.user_name_id', 'students.name', 'students.register_no')
                    ->orderBy('students.register_no', 'ASC')
                    ->get();
                $exist = false;
            }
            return response()->json(['status' => true, 'data' => $getData, 'exist' => $exist]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function storeStudents(Request $request)
    {
        if (isset($request->batch) && isset($request->ay) && isset($request->course) && isset($request->exam_month) && isset($request->exam_year) && isset($request->semester) && isset($request->exam_type) && isset($request->subject) && isset($request->action) && isset($request->data)) {
            $subjectSem = ExamRegistration::where(['batch' => $request->batch, 'academic_year' => $request->ay, 'course' => $request->course, 'semester' => $request->semester, 'exam_type' => $request->exam_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'subject_id' => $request->subject])->value('subject_sem');
            if ($request->action == 0) {
                $getStu = PracticalMark::where(['batch' => $request->batch, 'ay' => $request->ay, 'course' => $request->course, 'exam_type' => $request->exam_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'semester' => $request->semester, 'subject' => $request->subject])->select('user_name_id')->get();
                $students = [];
                if (count($getStu) > 0) {
                    foreach ($getStu as $student) {
                        $students[] = $student->user_name_id;
                    }
                }
                foreach ($request->data as $i => $data) {
                    if ($data[0] != '') {
                        if (in_array($data[0], $students)) {
                            PracticalMark::where(['batch' => $request->batch, 'ay' => $request->ay, 'course' => $request->course, 'exam_type' => $request->exam_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'semester' => $request->semester, 'subject' => $request->subject, 'user_name_id' => $data[0]])->update(['mark' => $data[1], 'mark_in_word' => $data[2]]);
                        } else {
                            PracticalMark::create([
                                'batch' => $request->batch,
                                'ay' => $request->ay,
                                'course' => $request->course,
                                'exam_type' => $request->exam_type,
                                'exam_month' => $request->exam_month,
                                'exam_year' => $request->exam_year,
                                'semester' => $request->semester,
                                'subject' => $request->subject,
                                'subject_sem' => $subjectSem,
                                'user_name_id' => $data[0],
                                'mark' => $data[1],
                                'mark_in_word' => $data[2],
                            ]);
                        }
                    }
                }
                return response()->json(['status' => true, 'data' => 'Mark Saved Successfully']);
            } else {
                $getStu = PracticalMark::where(['batch' => $request->batch, 'ay' => $request->ay, 'course' => $request->course, 'exam_type' => $request->exam_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'semester' => $request->semester, 'subject' => $request->subject])->select('user_name_id')->get();
                $students = [];
                if (count($getStu) > 0) {
                    foreach ($getStu as $student) {
                        $students[] = $student->user_name_id;
                    }
                }
                foreach ($request->data as $i => $data) {
                    if ($data[0] != '') {
                        if (in_array($data[0], $students)) {
                            PracticalMark::where(['batch' => $request->batch, 'ay' => $request->ay, 'course' => $request->course, 'exam_type' => $request->exam_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'semester' => $request->semester, 'subject' => $request->subject, 'user_name_id' => $data[0]])->update(['mark' => $data[1], 'mark_in_word' => $data[2], 'action' => 1]);
                        } else {
                            PracticalMark::create([
                                'batch' => $request->batch,
                                'ay' => $request->ay,
                                'course' => $request->course,
                                'exam_type' => $request->exam_type,
                                'exam_month' => $request->exam_month,
                                'exam_year' => $request->exam_year,
                                'semester' => $request->semester,
                                'subject' => $request->subject,
                                'subject_sem' => $subjectSem,
                                'user_name_id' => $data[0],
                                'mark' => $data[1],
                                'mark_in_word' => $data[2],
                                'action' => 1,
                            ]);
                        }
                    }
                }
                return response()->json(['status' => true, 'data' => 'Mark Submitted Successfully']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function printingData(Request $request)
    {
        if (isset($request->batch) && isset($request->ay) && isset($request->course) && isset($request->exam_month) && isset($request->exam_year) && isset($request->semester) && isset($request->exam_type) && isset($request->subject)) {
            if (Session::has('practicalMark')) {
                Session::forget('practicalMark');
            }
            $subject = Subject::find($request->subject);
            $course = ToolsCourse::find($request->course);
            $getData = PracticalMark::with('student:user_name_id,name,register_no')->where(['batch' => $request->batch, 'ay' => $request->ay, 'course' => $request->course, 'exam_type' => $request->exam_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'semester' => $request->semester, 'subject' => $request->subject])->select('user_name_id', 'mark', 'mark_in_word', 'subject_sem')->get();
            Session::put('practicalMark', ['data' => $getData, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'exam_type' => $request->exam_type, 'subject' => $subject, 'course' => $course, 'semester' => $request->semester]);
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function preview(Request $request)
    {
        if (Session::has('practicalMark')) {
            $detail = Session::get('practicalMark');
        } else {
            $detail = [];
        }

        return view('admin.practicalMark.preview', compact('detail'));
    }
    public function print(Request $request)
    {
        if (Session::has('practicalMark')) {
            $detail = Session::get('practicalMark');
        } else {
            $detail = [];
        }
        $pdf = PDF::loadView('admin.practicalMark.print', ['detail' => $detail]);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('PracticalMark.pdf');
    }

    public function practicalMasterIndex(Request $request)
    {
        $courses = ToolsCourse::pluck('short_form', 'id');
        $batches = Batch::pluck('name', 'id');
        $ays = AcademicYear::pluck('name', 'id');
        $years = Year::pluck('year', 'id');
        if ($request->ajax()) {
            $query = PracticalMark::where(['action' => 1])->with('subjects:id,name,subject_code', 'courses:id,short_form', 'ays:id,name')->groupBy('batch', 'ay', 'course', 'semester', 'exam_type', 'exam_month', 'exam_year', 'subject', 'subject_sem')->select('batch', 'ay', 'course', 'semester', 'exam_type', 'exam_month', 'exam_year', 'subject', 'subject_sem')->get();
            $table = Datatables::of($query);

            $table->editColumn('ay', function ($row) {
                return $row->ays ? $row->ays->name : '';
            });
            $table->editColumn('month', function ($row) {
                return $row->exam_month . ' ' . $row->exam_year;
            });
            $table->editColumn('course', function ($row) {
                return $row->courses ? $row->courses->short_form : '';
            });
            $table->editColumn('semester', function ($row) {
                return $row->semester;
            });
            $table->editColumn('subject', function ($row) {
                return $row->subjects ? $row->subjects->name . ' (' . $row->subjects->subject_code . ')' : '';
            });
            $table->editColumn('subject_sem', function ($row) {
                return $row->subject_sem;
            });
            $table->editColumn('exam_type', function ($row) {
                return $row->exam_type;
            });
            $table->editColumn('total_students', function ($row) {
                $total = PracticalMark::where(['action' => 1, 'batch' => $row->batch, 'ay' => $row->ay, 'course' => $row->course, 'semester' => $row->semester, 'exam_type' => $row->exam_type, 'exam_month' => $row->exam_month, 'exam_year' => $row->exam_year, 'subject' => $row->subject, 'subject_sem' => $row->subject_sem])->select('user_name_id')->count();
                return $total;
            });
            $table->editColumn('total_present', function ($row) {
                $present = PracticalMark::where(['action' => 1, 'batch' => $row->batch, 'ay' => $row->ay, 'course' => $row->course, 'semester' => $row->semester, 'exam_type' => $row->exam_type, 'exam_month' => $row->exam_month, 'exam_year' => $row->exam_year, 'subject' => $row->subject, 'subject_sem' => $row->subject_sem])->where('mark', '!=', -1)->select('user_name_id')->count();
                return $present;
            });
            $table->editColumn('total_absent', function ($row) {
                $absent = PracticalMark::where(['action' => 1, 'batch' => $row->batch, 'ay' => $row->ay, 'course' => $row->course, 'semester' => $row->semester, 'exam_type' => $row->exam_type, 'exam_month' => $row->exam_month, 'exam_year' => $row->exam_year, 'subject' => $row->subject, 'subject_sem' => $row->subject_sem])->where('mark', -1)->select('user_name_id')->count();
                return $absent;
            });
            $table->editColumn('submitted_date', function ($row) {
                $theDate = PracticalMark::where(['action' => 1, 'batch' => $row->batch, 'ay' => $row->ay, 'course' => $row->course, 'semester' => $row->semester, 'exam_type' => $row->exam_type, 'exam_month' => $row->exam_month, 'exam_year' => $row->exam_year, 'subject' => $row->subject, 'subject_sem' => $row->subject_sem])->first()->updated_at;
                $submittedDate = Carbon::parse($theDate)->format('Y-m-d');
                return $submittedDate;
            });
            $table->editColumn('actions', function ($row) {
                return $row;
            });

            return $table->make(true);
        }
        return view('admin.practicalMark.masterIndex', compact('courses', 'batches', 'ays', 'years'));
    }

    public function getData(Request $request)
    {
        if (isset($request->batch) && isset($request->ay) && isset($request->course) && isset($request->exam_month) && isset($request->exam_year) && isset($request->semester) && isset($request->exam_type)) {

            $query = PracticalMark::where(['batch' => $request->batch, 'ay' => $request->ay, 'course' => $request->course, 'exam_type' => $request->exam_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'semester' => $request->semester, 'action' => 1])->with('subjects:id,name,subject_code', 'courses:id,short_form', 'ays:id,name')->groupBy('batch', 'ay', 'course', 'semester', 'exam_type', 'exam_month', 'exam_year', 'subject', 'subject_sem')->select('batch', 'ay', 'course', 'semester', 'exam_type', 'exam_month', 'exam_year', 'subject', 'subject_sem')->get();
            $data = [];

            if (count($query) > 0) {
                foreach ($query as $detail) {
                    $temp = [];
                    $temp['batch_id'] = $request->batch;
                    $temp['ay_id'] = $request->ay;
                    $temp['course_id'] = $request->course;
                    $temp['exam_month'] = $request->exam_month;
                    $temp['exam_year'] = $request->exam_year;
                    $temp['subject_id'] = $detail->subject;
                    $temp['ay'] = $detail->ays ? $detail->ays->name : '';
                    $temp['month'] = $detail->exam_month . ' ' . $detail->exam_year;
                    $temp['course'] = $detail->courses ? $detail->courses->short_form : '';
                    $temp['semester'] = $detail->semester;
                    $temp['subject'] = $detail->subjects ? $detail->subjects->name . ' (' . $detail->subjects->subject_code . ')' : '';
                    $temp['subject_sem'] = $detail->subject_sem;
                    $temp['exam_type'] = $detail->exam_type;
                    $temp['total_students'] = PracticalMark::where(['action' => 1, 'batch' => $detail->batch, 'ay' => $detail->ay, 'course' => $detail->course, 'semester' => $detail->semester, 'exam_type' => $detail->exam_type, 'exam_month' => $detail->exam_month, 'exam_year' => $detail->exam_year, 'subject' => $detail->subject, 'subject_sem' => $detail->subject_sem])->select('user_name_id')->count();
                    $temp['total_present'] = PracticalMark::where(['action' => 1, 'batch' => $detail->batch, 'ay' => $detail->ay, 'course' => $detail->course, 'semester' => $detail->semester, 'exam_type' => $detail->exam_type, 'exam_month' => $detail->exam_month, 'exam_year' => $detail->exam_year, 'subject' => $detail->subject, 'subject_sem' => $detail->subject_sem])->where('mark', '!=', -1)->select('user_name_id')->count();
                    $temp['total_absent'] = PracticalMark::where(['action' => 1, 'batch' => $detail->batch, 'ay' => $detail->ay, 'course' => $detail->course, 'semester' => $detail->semester, 'exam_type' => $detail->exam_type, 'exam_month' => $detail->exam_month, 'exam_year' => $detail->exam_year, 'subject' => $detail->subject, 'subject_sem' => $detail->subject_sem])->where('mark', -1)->select('user_name_id')->count();
                    $theDate = PracticalMark::where(['action' => 1, 'batch' => $detail->batch, 'ay' => $detail->ay, 'course' => $detail->course, 'semester' => $detail->semester, 'exam_type' => $detail->exam_type, 'exam_month' => $detail->exam_month, 'exam_year' => $detail->exam_year, 'subject' => $detail->subject, 'subject_sem' => $detail->subject_sem])->first()->updated_at;
                    $temp['submitted_date'] = Carbon::parse($theDate)->format('Y-m-d');
                    array_push($data, $temp);
                }
            }
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function downloadExcel(Request $request)
    {
        $status = false;
        $data = 'Required Details Not Found';
        $subject = null;
        $course = null;
        $ay = null;
        $exam_type = null;
        $exam_month = null;
        $exam_year = null;
        if (isset($request->batch) && isset($request->ay) && isset($request->course) && isset($request->exam_month) && isset($request->exam_year) && isset($request->semester) && isset($request->exam_type) && isset($request->subject) && isset($request->subject_sem)) {

            $subject = Subject::find($request->subject);
            $course = ToolsCourse::find($request->course);
            $ay = AcademicYear::find($request->ay);
            $exam_type = $request->exam_type;
            $exam_month = $request->exam_month;
            $exam_year = $request->exam_year;
            if ($subject != '' && $course != '' && $ay != '') {
                $data = PracticalMark::with('student:user_name_id,name,register_no')->where(['batch' => $request->batch, 'ay' => $request->ay, 'course' => $request->course, 'exam_type' => $request->exam_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'semester' => $request->semester, 'subject' => $request->subject])->select('user_name_id', 'mark', 'mark_in_word', 'subject_sem')->get();
                $status = true;
            }
        }

        return view('admin.practicalMark.masterExcel', compact('status', 'data', 'subject', 'course', 'ay', 'exam_year', 'exam_month', 'exam_type'));

    }
    public function downloadPdf(Request $request)
    {
        if (isset($request->batch) && isset($request->ay) && isset($request->course) && isset($request->exam_month) && isset($request->exam_year) && isset($request->semester) && isset($request->exam_type) && isset($request->subject)) {

            $subject = Subject::find($request->subject);
            $course = ToolsCourse::find($request->course);
            $getData = PracticalMark::with('student:user_name_id,name,register_no')->where(['batch' => $request->batch, 'ay' => $request->ay, 'course' => $request->course, 'exam_type' => $request->exam_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'semester' => $request->semester, 'subject' => $request->subject])->select('user_name_id', 'mark', 'mark_in_word', 'subject_sem')->get();
            $detail = ['data' => $getData, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'exam_type' => $request->exam_type, 'subject' => $subject, 'course' => $course, 'semester' => $request->semester];

            $pdf = PDF::loadView('admin.practicalMark.print', ['detail' => $detail]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('PracticalMark.pdf');
        } else {
            return 'Required Details Not Found';
        }
    }
    public function masterEdit(Request $request)
    {
        if (isset($request->batch) && isset($request->ay) && isset($request->course) && isset($request->exam_month) && isset($request->exam_year) && isset($request->semester) && isset($request->exam_type) && isset($request->subject)) {

            $batch = Batch::find($request->batch);
            $ay = AcademicYear::find($request->ay);
            $subject = Subject::find($request->subject);
            $course = ToolsCourse::find($request->course);
            if ($batch == null || $ay == null || $subject == null || $course == null) {
                return 'Required Details Not Found';
            }
            $data = PracticalMark::with('student:user_name_id,name,register_no')->where(['batch' => $request->batch, 'ay' => $request->ay, 'course' => $request->course, 'exam_type' => $request->exam_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'semester' => $request->semester, 'subject' => $request->subject])->select('user_name_id', 'mark', 'mark_in_word', 'subject_sem')->get();
            $details = ['exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'exam_type' => $request->exam_type, 'subject' => $subject, 'course' => $course, 'semester' => $request->semester, 'batch' => $batch, 'ay' => $ay];
            return view('admin.practicalMark.masterEdit', compact('details', 'data'));
        } else {
            return 'Required Details Not Found';
        }
    }

    public function updateStudents(Request $request)
    {
        abort_if(Gate::denies('practical_mark_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if (isset($request->batch) && isset($request->ay) && isset($request->course) && isset($request->exam_month) && isset($request->exam_year) && isset($request->semester) && isset($request->exam_type) && isset($request->subject) && isset($request->data)) {
            $subjectSem = ExamRegistration::where(['batch' => $request->batch, 'academic_year' => $request->ay, 'course' => $request->course, 'semester' => $request->semester, 'exam_type' => $request->exam_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'subject_id' => $request->subject])->value('subject_sem');

            $getStu = PracticalMark::where(['batch' => $request->batch, 'ay' => $request->ay, 'course' => $request->course, 'exam_type' => $request->exam_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'semester' => $request->semester, 'subject' => $request->subject])->select('user_name_id')->get();
            $students = [];
            if (count($getStu) > 0) {
                foreach ($getStu as $student) {
                    $students[] = $student->user_name_id;
                }
            }
            foreach ($request->data as $i => $data) {
                if ($data[0] != '') {
                    if (in_array($data[0], $students)) {
                        PracticalMark::where(['batch' => $request->batch, 'ay' => $request->ay, 'course' => $request->course, 'exam_type' => $request->exam_type, 'exam_month' => $request->exam_month, 'exam_year' => $request->exam_year, 'semester' => $request->semester, 'subject' => $request->subject, 'user_name_id' => $data[0]])->update(['mark' => $data[1], 'mark_in_word' => $data[2]]);
                    }
                }
            }
            return response()->json(['status' => true, 'data' => 'Mark Updated Successfully']);

        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }
}
