<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AssignmentAttendances;
use App\Models\AssignmentData;
use App\Models\AssignmentModel;
use App\Models\ClassRoom;
use App\Models\ClassTimeTableTwo;
use App\Models\CollegeBlock;
use App\Models\CourseEnrollMaster;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentPromotionHistory;
use App\Models\Subject;
use App\Models\SubjectAllotment;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use PDF;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AssignmentExamController extends Controller
{
    public function index(Request $request)
    {

        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $AcademicYear = AcademicYear::pluck('name', 'id');
        $examNames = AssignmentModel::select('exam_name')->distinct()->get();

        if ($request->ajax()) {
            $exam = [];
            $courses = ToolsCourse::pluck('name', 'id');
            $semester = Semester::pluck('semester', 'id');
            $AcademicYear = AcademicYear::pluck('name', 'id');
            $examNames = AssignmentModel::select('id', 'exam_name', 'course_id', 'academic_year', 'section', 'semester', 'year', 'subject', 'due_date')->distinct()->get();
            foreach ($examNames as $examName) {

                $course = ToolsCourse::where('id', $examName->course_id)->select('name')->first();
                $AcademicYears = AcademicYear::where('id', $examName->academic_year)->select('name')->first();
                $string = '/' . $course->name . '/' . $AcademicYears->name . '/' . $examName->semester . '/' . $examName->section;
                $stu = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%' . $string)->first();
                if ($stu != null) {
                    $students = Student::where('enroll_master_id', $stu->id)->count();
                    if ($students <= 0) {
                        $students = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $stu->id)->count();
                    }
                    if ($students > 0) {
                        $exam[] = $examName;
                    } else {
                        AssignmentModel::where('id', $examName->id)->update(['deleted_at' => now()]);
                    }
                }
            }
            $table = DataTables::of($exam);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'exam_shedule_view';
                $editGate = 'examTimetable_edit';
                $deleteGate = 'examTimetable_delete';
                $crudRoutePart = 'assignment';

                return view(
                    'partials.datatablesActions',
                    compact(
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
            $table->editColumn('exam_name', function ($row) {
                return $row->exam_name ? $row->exam_name : '';
            });
            $table->editColumn('academic_year', function ($row) {
                if ($row->academic_year != '') {
                    $ay = AcademicYear::find($row->academic_year);
                    if ($ay) {
                        $academicYear = $ay->name;
                    } else {
                        $academicYear = '';
                    }
                }
                return $row->academic_year ? $academicYear : '';
            });
            $table->editColumn('course_id', function ($row) {
                if ($row->course_id != '') {
                    $course = ToolsCourse::find($row->course_id);
                    if ($course) {
                        $outputcourse = $course->short_form;
                    } else {
                        $outputcourse = '';
                    }
                }
                return $row->course_id ? $outputcourse : '';
            });
            $table->editColumn('course', function ($row) {

                return $row->course_id ? $row->course_id : '';
            });
            $table->editColumn('semester', function ($row) {
                if ($row->semester != '') {
                    $sem = Semester::find($row->semester);
                    if ($sem) {
                        $semesterData = $sem->semester;
                    } else {
                        $semesterData = '';
                    }
                }
                return $row->semester ? $semesterData : '';
            });
            $table->editColumn('year', function ($row) {
                if ($row->year) {

                    $result = app(ExamTimetableCreationController::class)->toRoman($row->year);
                }
                return $row->year ? $result : '';
            });

            $table->editColumn('due_date', function ($row) {
                $due_date = date('d-m-Y', strtotime($row->due_date ? $row->due_date : ''));
                return $due_date;
            });

            $table->editColumn('section', function ($row) {

                return $row->section ? $row->section : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        return view('admin.assignmentSchedule.index', compact('AcademicYear', 'courses', 'semester'));
    }
    public function create()
    {
        $departments = ToolsDepartment::pluck('name', 'id');
        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $Subjects = Subject::orderBy('created_at', 'desc')->get();
        $blocks = CollegeBlock::pluck('name', 'id');
        $classrooms = ClassRoom::pluck('name', 'id');
        $AcademicYear = AcademicYear::pluck('name', 'id');
        // $Section = Section::select('id', 'section')->groupBy('id', 'section')->get();
        return view('admin.assignmentSchedule.create', compact('courses', 'departments', 'semester', 'Subjects', 'blocks', 'classrooms', 'AcademicYear'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'academic_year' => 'required',
            'semester_type' => 'required',
            'course_id' => 'required',
            'year' => 'required',
            'semester' => 'required',
            'due_date' => 'required',
            'subject' => 'required',
            'sections' => 'required|array',
        ]);
        $academicYearId = $request->academic_year;
        $semester_type = $request->semester_type;
        $course_id = $request->course_id;
        $year = $request->year;
        $semester = $request->semester;
        $due_date = $request->due_date;
        $year = $request->year;
        $sections = $request->input('sections');
        $subjects = json_decode($request->input('subject'), true);
        if (!$subjects || !$sections) {
            return response()->json(['message' => 'Invalid or incomplete data.'], 400);
        }
        try {
            foreach ($sections as $section) {
                $existingAssignment = AssignmentModel::where(['academic_year' => $academicYearId, 'semester_type' => $semester_type, 'course_id' => $course_id, 'year' => $year, 'semester' => $semester, 'section' => $section])->select('id')->first();
                if (!$existingAssignment) {

                    $Academicyear = AcademicYear::where('id', $academicYearId)->select('name')->first()->name;
                    $course_short_from = ToolsCourse::where('id', $course_id)->select('short_form')->first()->short_form;
                    $serializedSubject = '';
                    // $serializedCo_mark = '';

                    foreach ($subjects as $index => $subjectData) {
                        if (!empty($subjectData)) {
                            $serializedSubject = serialize($subjects);
                            // $serializedCo_mark = serialize($co_mark);
                        } else {
                            $serializedSubject = '';
                            // $serializedCo_mark = '';
                        }
                    }

                    DB::transaction(function () use ($request, $serializedSubject, $course_id, $due_date, $semester_type, $section, $subjects, $academicYearId, $Academicyear, $course_short_from, $semester, $year) {
                        // Create a new AssignmentModel record
                        $newAssignmentModel = AssignmentModel::create([
                            'course_id' => $course_id,
                            'semester' => $semester,
                            'subject' => $serializedSubject,
                            'exam_name' => $Academicyear . '/' . $course_short_from . '/0' . $semester,

                            'due_date' => $due_date,
                            'academic_year' => $academicYearId,
                            'semester_type' => $semester_type,
                            'year' => $year,
                            'section' => $section,
                        ]);

                        $newModel_id = $newAssignmentModel->id;
                        $newModelcourse_id = $newAssignmentModel->course_id;
                        $newModelAcademicYear_id = $newAssignmentModel->academic_year;
                        $newModelExamName_id = $newAssignmentModel->exam_name;
                        $newModelSemester_id = $newAssignmentModel->semester;
                        $newModelYear_id = $newAssignmentModel->year;
                        $newModelSection = $newAssignmentModel->section;

                        // Create related Examattendance records
                        foreach ($subjects as $subjectData) {
                            foreach ($subjectData as $subjectId => $subject) {

                                $Exam_attedance_id = AssignmentAttendances::create([
                                    'assignment_id' => $newModel_id,
                                    'course' => $newModelcourse_id,
                                    'subject' => $subjectId,
                                    'academic_year' => $newModelAcademicYear_id,
                                    'exam_name' => $newModelExamName_id,
                                    'semester' => $newModelSemester_id,
                                    'year' => $newModelYear_id,
                                    'section' => $newModelSection,
                                    'assignment_mark' => 50,
                                ]);
                                $lastinsertedId = $Exam_attedance_id->id;

                                $getLastinsertedId = $lastinsertedId;

                                $courseName = ToolsCourse::where('id', $course_id)->select('name')->first()->name;
                                $academicYearName = AcademicYear::where('id', $academicYearId)->select('name')->first()->name;
                                $semesterName = explode('0', $request->semester)[0];

                                $string = '/' . $courseName . '/' . $academicYearName . '/' . $semesterName . '/' . $newModelSection;
                                $enrollment_id = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%' . $string)->select('id')->first()->id;

                                $studentList = DB::table('subject_registration')->leftJoin('students', 'subject_registration.user_name_id', '=', 'students.user_name_id')->where('subject_registration.enroll_master', $enrollment_id)->where('subject_registration.subject_id', $subjectId)->whereNull('students.deleted_at')->whereNull('subject_registration.deleted_at')
                                    ->select('students.register_no', 'students.user_name_id', 'students.name')
                                    ->orderBy('students.register_no', 'asc')
                                    ->get();
                                if (count($studentList) <= 0) {
                                    $studentList = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $enrollment_id)->orderBy('students.register_no', 'asc')->select('students.register_no', 'students.user_name_id', 'students.name')->get();
                                }
                                if (count($studentList) > 0) {

                                    foreach ($studentList as $student) {

                                        $studentId = $student->user_name_id;
                                        $entered = AssignmentData::create([
                                            'date' => now(),
                                            'subject' => $subjectId,
                                            'enter_by' => auth()->user()->id,
                                            'class_id' => $enrollment_id,
                                            'student_id' => $studentId,
                                            'assignment_name_id' => $getLastinsertedId,
                                        ]);
                                    }
                                } else {
                                    return response()->json(['message' => 'Invalid or incomplete data.'], 400);
                                }
                            }
                        }
                    });
                }
            }
            Session::flash('message', 'Assignment timetable successfully created.');
            return response()->json(['error' => false]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error creating Lab Exam timetable: ' . $e->getMessage());

            Session::flash('message_error', 'Error occurred while creating Assignment Exam timetable. Changes rolled back.');
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function view($id)
    {

        $assignmentModel = AssignmentModel::find($id);

        if ($assignmentModel) {
            $course = ToolsCourse::where('id', $assignmentModel->course_id)->select('name')->first();
            $assignmentModel->course_id = $course->name;
            $AcademicYear = AcademicYear::where('id', $assignmentModel->academic_year)->select('name')->first();
            $assignmentModel->academic_year = $AcademicYear->name;
            $subjectData = unserialize($assignmentModel->subject);
            $subjectsNew = [];
            if ($assignmentModel->year == 01) {
                $assignmentModel->year = "I";
            } else if ($assignmentModel->year == 02) {
                $assignmentModel->year = "II";
            } else if ($assignmentModel->year == 03) {
                $assignmentModel->year = "III";
            } else if ($assignmentModel->year == 04) {
                $assignmentModel->year = "IV";
            }
            if ($subjectData !== false && is_array($subjectData)) {
                foreach ($subjectData as $match) {
                    foreach ($match as $data => $matches) {

                        $subject = Subject::find($data);

                        if ($subject) {
                            $value = $matches;
                            $subjectsNew[] = [
                                'name' => $subject->name,
                                'date' => $value,
                                'code' => $subject->subject_code,
                                'id' => $subject->id,
                            ];
                        }
                    }
                }
            }

            $assignmentModel->newsubject = $subjectsNew;
        }
        return view('admin.assignmentSchedule.view', compact('assignmentModel'));
    }
    public function search(Request $request)
    {

        $exam_name = $request->input('exam_name');
        $academic_year = $request->input('academicYear_id');
        $semester_type = $request->input('semester_type');

        $lab_table_details = AssignmentModel::where(['academic_year' => $academic_year, 'semester_type' => $semester_type])->select('id', 'course_id', 'year', 'semester', 'section', 'semester_type')->get();
        $lab_table_id = $lab_table_details->pluck('id');

        $course_id = $lab_table_details->pluck('course_id');
        $count = count($lab_table_id);
        if ($count > 0) {

            $semester = $lab_table_details->pluck('semester');
            //$course_get
            $course_get = $lab_table_details->groupBy('course_id')->map(function ($course_id) {
                $data = $course_id->pluck('course_id');
                $data[0] = ToolsCourse::where('id', $data[0])->select('name')->first()->name;
                return $data[0] ? $data[0] : null;
            });

            $year_get = $lab_table_details->groupBy('year')->map(function ($year) {
                $data = $year->pluck('year');
                if ($data[0] == '01') {
                    $data[0] = 'I';
                } elseif ($data[0] == 02) {
                    $data[0] = 'II';
                } elseif ($data[0] == 03) {
                    $data[0] = 'III';
                } elseif ($data[0] == 04) {
                    $data[0] = 'IV';
                }
                return $data[0] ? $data[0] : null;
            });

            $semester_get = $lab_table_details->groupBy('semester')->map(function ($semester) {
                $data = $semester->pluck('semester');
                return $data[0] ? $data[0] : null;
            });

            $sections_get = $lab_table_details->groupBy('section')->map(function ($section) {
                $data = $section->pluck('section');
                return $data[0] ? $data[0] : null;
            });
        } else {
            $course_get = [];
            $year_get = [];
            $semester_get = [];
            $sections_get = [];
        }

        $academic_year = $request->input('academicYear_id');

        $semester_type = $request->input('semester_type');

        $query = AssignmentModel::query();

        if ($academic_year) {
            $query->Where('academic_year', $academic_year);
        }

        if ($semester_type) {
            $query->Where('semester_type', $semester_type);
        }

        // $response = $query->get();
        $query = $query->get();
        $table = Datatables::of($query);
        $table->addColumn('placeholder', '&nbsp;');
        $table->addColumn('actions', '&nbsp;');

        $table->editColumn('actions', function ($row) {
            $viewGate = 'exam_shedule_view';
            $editGate = 'examTimetable_edit';
            $deleteGate = 'examTimetable_delete';
            $crudRoutePart = 'assignment';

            return view(
                'partials.datatablesActions',
                compact(
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
        $table->editColumn('exam_name', function ($row) {
            return $row->exam_name ? $row->exam_name : '';
        });
        $table->editColumn('academic_year', function ($row) {
            if ($row->academic_year != '') {
                $ay = AcademicYear::find($row->academic_year);
                if ($ay) {
                    $academic_year = $ay->name;
                } else {
                    $academic_year = '';
                }
            }
            return $row->academic_year ? $academic_year : '';
        });
        $table->editColumn('course_id', function ($row) {
            if ($row->course_id != '') {
                $course = ToolsCourse::find($row->course_id);
                if ($course) {
                    $outputcourse = $course->short_form;
                } else {
                    $outputcourse = '';
                }
            }
            return $row->course_id ? $outputcourse : '';
        });
        $table->editColumn('course', function ($row) {

            return $row->course_id ? $row->course_id : '';
        });
        $table->editColumn('semester', function ($row) {
            if ($row->semester != '') {
                $sem = Semester::find($row->semester);
                if ($sem) {
                    $semesterData = $sem->semester;
                } else {
                    $semesterData = '';
                }
            }
            return $row->semester ? $semesterData : '';
        });
        $table->editColumn('year', function ($row) {
            if ($row->year) {

                $result = app(ExamTimetableCreationController::class)->toRoman($row->year);
            }
            return $row->year ? $result : '';
        });

        $table->editColumn('section', function ($row) {

            return $row->section ? $row->section : '';
        });
        $table->editColumn('due_date', function ($row) {
            if ($row->due_date) {
                $result = date('d-m-Y', strtotime($row->due_date));
            }
            return $row->due_date ? $result : '';
        });

        $table->rawColumns(['actions', 'placeholder']);
        $table->make(true);

        // return $table->make(true);
        return response()->json(['data' => $table, 'course' => $course_get, 'semester' => $semester_get, 'year' => $year_get, 'sections' => $sections_get]);

        // }
        // return view('admin.labSchedule.index', compact('courses', 'semester', 'AcademicYear', 'examNames', 'MarkType'));
    }

    public function edit($id)
    {
        $verify_or_pulish_check = AssignmentAttendances::where('assignment_id', $id)->whereIn('status', [1, 2, 3])->select('id')->get();
        $count = count($verify_or_pulish_check);
        if ($count <= 0) {
            $departments = ToolsDepartment::pluck('name', 'id');
            $courses = ToolsCourse::pluck('name', 'id');
            $semester = Semester::pluck('semester', 'id');
            $Subjects = Subject::get();
            $blocks = CollegeBlock::pluck('name', 'id');
            $classrooms = ClassRoom::pluck('name', 'id');
            $AcademicYear = AcademicYear::pluck('name', 'id');
            // $MarkType=Tools_Labmark_Title::pluck('name', 'id');

            $assignmentSchedule = AssignmentModel::find($id);
            $semester_id = $assignmentSchedule->semester;
            $course_id = $assignmentSchedule->course_id;
            $academic_year_id = $assignmentSchedule->academic_year;
            $semester_type = $assignmentSchedule->semester_type;
            $section = $assignmentSchedule->section;
            $get_subjects = [];
            // $get_subjects = SubjectAllotment::where(['semester' => $semesters, 'course' => $coures, 'academic_year' => $academic_years, 'semester_type' => $semester_types])->get();

            $subjects = [];
            $got_subject = [];
            $subjectsNew = [];
            if ($assignmentSchedule) {

                $subjectData = unserialize($assignmentSchedule->subject);
                $present_subject_ids = [];
                if ($subjectData !== false && is_array($subjectData)) {
                    $si = 0;
                    foreach ($subjectData as $match) {
                        foreach ($match as $data => $matches) {
                            $got_subject = Subject::where('id', $data)
                                ->select('id', 'name', 'subject_code')
                                ->first();
                            array_push($present_subject_ids, $data);
                            array_push($subjectsNew, $got_subject);
                            $subjectsNew[$si]['date'] = $matches;
                        }
                        $si++;
                    }
                }

                $assignmentSchedule->newsubject = $subjectsNew;
            }

            return view('admin.assignmentSchedule.edit', compact('assignmentSchedule', 'courses', 'departments', 'semester', 'Subjects', 'blocks', 'classrooms', 'AcademicYear', 'present_subject_ids'));
        } else {
            return to_route('admin.assignment.index')->with('message_error', 'Already Assignment  Mark Enter or Verify or Published So can\'t Edit.');
        }
    }

    public function update(Request $request, AssignmentModel $AssignmentModel, $id)
    {
        $verify_or_pulish_check = AssignmentAttendances::where('assignment_id', $id)->whereIn('status', [1, 2, 3])->select('id')->get();

        $count = count($verify_or_pulish_check);
        if ($count <= 0) {
            $request->validate([
                'academic_year' => 'required',
                'semester_type' => 'required',
                'examName' => 'required',
                'year' => 'required',
                'course_id' => 'required',
                'semester' => 'required',
                'subject' => 'required',
                'sections' => 'required',
            ]);

            $academicYearId = $request->academic_year;
            $semester_type = $request->semester_type;
            $course_id = $request->course_id;
            $year = $request->year;
            $semester = $request->semester;
            $due_date = $request->due_date;
            $year = $request->year;
            $user_id = auth()->user()->id;

            $AcademicyearName = AcademicYear::where('id', $academicYearId)->select('name')->first()->name;
            $course_short_from = ToolsCourse::where('id', $course_id)->select('short_form')->first()->short_form;

            $subjects = json_decode($request->input('subject'), true);
            $section = $request->input('sections');

            if (!$subjects || !$section) {
                return response()->json(['message' => 'Invalid or incomplete data.'], 400);
            }

            $coData = $request->input('hidden');
            $serializeSubject = serialize($subjects);

            $assignmentModel = AssignmentModel::find($id);

            if (!$assignmentModel) {
                return redirect()->route('admin.assignment.index')->with('error', 'Exam timetable not found.');
            }

            $assignmentModel->update([
                'subject' => $serializeSubject,
                'due_date' => $due_date,
                'semester_type' => $semester_type,
                'exam_name' => $AcademicyearName . '/' . $course_short_from . '/0' . $semester,
                'course_id' => $course_id,
                'semester' => $semester,
                'academic_year' => $academicYearId,
                'year' => $year,

            ]);

            $attData = AssignmentAttendances::where('assignment_id', $assignmentModel->id)->get();
            $subjectTotal = [];

            foreach ($subjects as $subjectData) {
                foreach ($subjectData as $ids => $subject) {
                    array_push($subjectTotal, $ids);
                }
            }

            if ($attData) {
                foreach ($attData as $attDatas) {

                    AssignmentData::where('assignment_name_id', $attDatas->id)->delete();
                    $attDatas->delete();

                }
            }

            foreach ($subjects as $subjectData) {
                foreach ($subjectData as $ids => $subject) {
                    $Exam_attedance_id = AssignmentAttendances::updateOrCreate(
                        [
                            'assignment_id' => $assignmentModel->id,
                            'subject' => $ids,
                        ],
                        [
                            'course' => $request->input('course_id'),
                            'date' => $subject,
                            'academic_year' => $request->input('academic_year'),
                            'exam_name' => $request->input('examName'),
                            'semester' => $request->input('semester'),
                            'year' => $request->input('year'),
                            'section' => $request->input('sections'),
                            'update_by' => $user_id,
                            'semester' => $semester,
                        ]
                    );

                    $lastinsertedId = $Exam_attedance_id->id;

                    $courseName = ToolsCourse::where('id', $course_id)->select('name')->first()->name;
                    $academicYearName = AcademicYear::where('id', $academicYearId)->select('name')->first();
                    $semesterName = explode('0', $request->semester)[0];

                    $string = '/' . $courseName . '/' . $AcademicyearName . '/' . $semesterName . '/' . $section;
                    $enrollementId = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%' . $string)->select('id')->first()->id;
                    $studentList = DB::table('subject_registration')->leftJoin('students', 'subject_registration.user_name_id', '=', 'students.user_name_id')->where('subject_registration.enroll_master', $enrollementId)->where('subject_registration.subject_id', $subjectId)->whereNull('students.deleted_at')->whereNull('subject_registration.deleted_at')
                        ->select('students.register_no', 'students.user_name_id', 'students.name')
                        ->orderBy('students.register_no', 'asc')
                        ->get();
                    if (count($studentList) <= 0) {
                        $studentList = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $enrollementId)->orderBy('students.register_no', 'asc')->select('students.register_no', 'students.user_name_id', 'students.name')->get();
                    }
                    if (count($studentList) > 0) {

                        foreach ($studentList as $id) {

                            // Create a new ExamattendanceData record
                            $entered = AssignmentData::create([
                                'date' => now(),
                                'subject' => $ids,
                                'enter_by' => auth()->user()->id,
                                'class_id' => $enrollementId,
                                'student_id' => $id->user_name_id,
                                'assignment_name_id' => $lastinsertedId,

                            ]);
                        }
                    } else {
                        return response()->json(['message' => 'Invalid or incomplete data.'], 400);
                    }
                }
            }

            Session::flash('message', 'Lab Exam timetable successfully edited.');
            return response(null, Response::HTTP_NO_CONTENT);
        } else {
            Session::flash('message_error', 'Exam  Already Mark Enter or  Verify or Published successfully.');
            return response(null, Response::HTTP_NO_CONTENT);
        }
    }

    public function destroy($assignmentId)
    {
        $existing_record_publish_or_not = AssignmentAttendances::where('assignment_id', $assignmentId)->whereIn('status', [1, 2, 3])->select('id')->get();
        $count = count($existing_record_publish_or_not);
        $delete = AssignmentModel::find($assignmentId);

        if ($delete) {
            if ($count == 0) {
                $datas = AssignmentAttendances::where('assignment_id', $assignmentId)->get();

                foreach ($datas as $data) {
                    AssignmentData::where('assignment_name_id', $data->id)->delete();
                    $data->delete();
                }

                $delete->delete();
            } else {
                return redirect()->route('admin.assignment.index')->with('message_error', 'Exam  Already Mark Enter or  Verify or Published so Can\'t be Deleted .');
            }

            return redirect()->route('admin.assignment.index')->with('message', 'Exam timetable successfully deleted.');
        } else {
            return redirect()->route('admin.assignment.index')->with('message_error', 'Exam timetable not found.');
        }
    }

    public function find(Request $request)
    {
        $role_id = auth()->user()->roles->first()->id;

        $academic_year = $request->input('academicYear_id');
        $course = $request->input('course_id');
        $semester = $request->input('semester_id');
        $year = $request->input('year');
        $section = $request->input('section');
        $exam_name = $request->input('exam_name');

        // Start with a base query
        $query = AssignmentAttendances::select('id', 'exam_name', 'course', 'subject', 'mark_enter_by', 'academic_year', 'semester', 'section', 'year')->with([
            'courseEnrollMaster:id,name',
            'academicYear:id,name',
            'semester:id,semester',
            'teachingStaff:id,StaffCode,name',
            'nonTeachingStaff:id,StaffCode,name',
            'user:id,name',
            'subject:id,subject_code,name',
        ]);

        // Check each input value and add a where clause if it's present
        if ($academic_year) {
            $query->where('academic_year', $academic_year);
        }
        if ($course) {
            $query->where('course', $course);
        }
        if ($semester) {
            $query->where('semester', $semester);
        }
        if ($year) {
            $query->where('year', $year);
        }
        if ($section) {
            $query->where('section', $section);
        }
        if ($exam_name) {
            $query->where('exam_name', $exam_name);
        }

        // Initialize an empty array to store Examattendance records
        $exameAtt = [];

        $query->chunk(50, function ($exameAttChunk) use ($role_id, &$exameAtt) {
            foreach ($exameAttChunk as $record) {

                // Initialize totalstudent to 0
                // $record->course_id
                $record->totalstudent = 0;

                // Build the search string for CourseEnrollMaster
                $courseName = optional($record->courseEnrollMaster)->name;
                $academicYearName = optional($record->academicYear)->name;
                $semesterName = optional($record->semester)->semester;
                $string = '/' . $courseName . '/' . $academicYearName . '/' . $semesterName . '/' . $record->section;

                // Find the CourseEnrollMaster record
                $stu = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%' . $string)->select('id')->first();

                // If a CourseEnrollMaster record is found, count students
                if ($stu != null) {
                    $students = Student::where('enroll_master_id', $stu->id)->count();
                    if ($students <= 0) {
                        $students = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $stu->id)->count();
                    }
                    if ($students > 0) {
                        $record->totalstudent = $students;
                    } else {
                        $record->totalstudent = null;
                    }
                    $class_ = app(CatExamAttendanceSummaryController::class);
                    $className = $class_->getClassName($stu->id);
                    $record->class = $className;
                }

                if ($record->totalstudent !== null) {
                    // Determine attenteredBY value
                    $attenteredBY = optional($record->teachingStaff)->StaffCode ?: (optional($record->nonTeachingStaff)->StaffCode ?: (optional($record->user)->name ?: ''));
                    $record->attenteredBY = $attenteredBY;

                    // Build action buttons based on att_entered and user's role
                    $buttons = '';
                    if ($record->att_entered == 'Yes') {
                        $buttons .= '<a class="btn btn-xs btn-primary" href="' . route('admin.lab_exam_attendance.viewattendance', [$stu->id, $record->id]) . '" target="_blank">View</a>';
                    } else {
                        $buttons .= '<a class="btn btn-xs btn-info" href="' . route('admin.lab_exam_attendance.attendanceEnter', [$stu->id, $record->id]) . '" target="_blank">Enter</a>';
                    }

                    if (($role_id == 40 || $role_id == 1) && $record->att_entered == 'Yes') {
                        $buttons .= ' <a class="btn btn-xs btn-danger" href="' . route('admin.lab_exam_attendance.editattendance', [$stu->id, $record->id]) . '" target="_blank">Edit</a>';
                    }

                    // Assign the action buttons and course name to the record
                    $record->course_id = $record->course;

                    $record->actions = $buttons;
                    $record->course = $courseName;
                    $record->date_entered = $record->date_entered ? date('d-m-Y', strtotime($record->date_entered)) : '-';

                    // If subject is not empty, format subject and subject code
                    if ($record->subject != '') {
                        $subject = Subject::where(['id' => $record->subject])->select('subject_code', 'name')->first();
                        if ($subject) {
                            $record->subject = $subject->name ?? '';
                            $record->subject_code = $subject->subject_code ?? '';
                        } else {
                            $record->subject = '';
                        }
                    }

                    if ($record->exam_name != '' && $record->year != '' && $record->semester != '') {
                        $record->exam_name = $record->exam_name . '/' . $this->toRoman($record->year) . '/0' . $record->semester;
                    }

                    // Add the record to the $exameAtt array
                    $exameAtt[] = $record;
                }
            }
        });

        return response()->json(['data' => $exameAtt]);
    }

    public function staff(Request $request)
    {
        $user_name_id = auth()->user()->id;
        $subjects = [];
        $response = [];
        $buttons = '';
        $get_course = '';

        $timetable = ClassTimeTableTwo::where(['staff' => $user_name_id, 'status' => 1])->groupBy('class_name', 'subject')->select('class_name', 'subject')->get();

        if ($timetable) {
            foreach ($timetable as $timetables) {
                $get_enroll = CourseEnrollMaster::where(['id' => $timetables->class_name])->first();

                if ($get_enroll) {
                    if ($get_enroll != '') {
                        $newArray = explode('/', $get_enroll->enroll_master_number);
                        $get_course = ToolsCourse::where(['name' => $newArray[1]])->first();
                        if ($get_course) {
                            $classname = $get_course->short_form . '/' . $newArray[3] . '/' . $newArray[4];
                        }
                        // $class_name=$newArray[1].'/'.$newArray[3].'/'.$newArray[4];

                    } else {
                        $classname = '';
                    }

                    $examAtt = AssignmentData::where(['class_id' => $timetables->class_name, 'subject' => $timetables->subject])->groupBy(['class_id', 'subject', 'assignment_name_id'])->select(['class_id', 'subject', 'assignment_name_id'])->get();

                    if ($examAtt) {
                        foreach ($examAtt as $attendanceData) {

                            // $examAtt->exam_name;
                            $responsesss = AssignmentAttendances::where([
                                'id' => $attendanceData->assignment_name_id,
                                'mark_entry' => '1',
                            ])->first();

                            if ($responsesss) {
                                $due_dates = AssignmentModel::where('id', $responsesss->assignment_id)->select('due_date', 'exam_name', 'section')->first();
                                $due_date = $due_dates->due_date;
                                $exam_name = $due_dates->exam_name;
                                $section = $due_dates->section;
                                $attendanceData->classname = $classname;
                                $responsesss->classname = $classname;
                                $buttonss = '';
                                $markstatus = '';
                                $academic_year = $due_dates->academic_year;
                                $semester = $due_dates->semester;
                                if ($semester == 1 || $semester == 3 || $semester == 5 || $semester == 7) {
                                    $semesterType = 'ODD';
                                } else {
                                    $semesterType = 'EVEN';
                                }

                                if ($responsesss->mark_enter_by == null) {
                                    $buttonss = '<a class="btn btn-xs btn-info" href="' . route('admin.assignment_Exam_Mark.markEnter', [$timetables->class_name, $responsesss->id]) . '" target="_blank">Enter</a>';
                                    $markstatus = 'Not Submitted';
                                } else {
                                    $buttonss = '<a class="btn btn-xs btn-primary" href="' . route('admin.assignment_Exam_Mark.markview', [$timetables->class_name, $responsesss->id]) . '" target="_blank">View</a>';
                                    $markstatus = 'Not Submitted';
                                }
                                if ($responsesss->status == 3 && $responsesss->mark_enter_by != null) {

                                    $edit_buttons = '<a class="btn btn-xs btn-primary" href="' . route('admin.assignment_Exam_Mark.editMark', [$timetables->class_name, $responsesss->id]) . '" target="_blank">Edit</a>';
                                    $markstatus = 'Not Final Submitted';

                                } else if ($responsesss->status == 4 || $responsesss->status == 1) {
                                    $markstatus = 'Submitted';
                                    $edit_buttons = '';

                                } else {
                                    $edit_buttons = '';
                                }
                                $attendanceData->button = $buttonss;
                                $attendanceData->edit_buttons = $edit_buttons;
                                $attendanceData->exam_name = $responsesss->exam_name;
                                $attendanceData->subject = $responsesss->subject;
                                $attendanceData->markstatus = $markstatus;
                                $attendanceData->due_date = $due_date ? date('d-m-Y', strtotime($due_date)) : '';

                                // $responsesss->button = $buttonss;
                                // $responsesss->markstatus = $markstatus;
                                // $response[] = $responsesss;
                                $response[$academic_year . '|' . $semesterType][] = $attendanceData;
                            }
                        }
                    }

                }
            }
        }
        // Now, $response should contain only valid data
        $subjects = Subject::get();
        return view('admin.assignmentExamMark.staff', compact('subjects', 'response'));
    }

    public function indexStaff()
    {

        $user_name_id = auth()->user()->id;
        $subjects = [];
        $response = [];
        $buttons = '';
        $buttonss = '';
        $getAys = AcademicYear::pluck('name', 'id');
        $currentClasses = Session::get('currentClasses');

        $timetable = ClassTimeTableTwo::whereIn('class_name', $currentClasses)->where(['staff' => $user_name_id, 'status' => 1])->groupBy('class_name', 'subject')->select('class_name', 'subject')->get();

        if ($timetable) {
            foreach ($timetable as $timetables) {

                $got_subjectDetails = Subject::where(['id' => $timetables->subject])->select('name')->first();

                $AcademicYear = '';
                $publishSubject = '';
                $get_enroll = CourseEnrollMaster::where(['id' => $timetables->class_name])->first();

                if ($get_enroll) {

                    if ($got_subjectDetails) {
                        $got_subjectName = $got_subjectDetails->name;
                        $got_subjectCode = $got_subjectDetails->subject_code;
                    } else {
                        $got_subjectName = '';
                        $got_subjectCode = '';
                    }

                    if ($get_enroll != '') {
                        $newArray = explode('/', $get_enroll->enroll_master_number);
                        $get_course = ToolsCourse::where(['name' => $newArray[1]])->first();
                        if ($get_course) {
                            $classname = $get_course->short_form . '/' . $newArray[3] . '/' . $newArray[4];
                            $courseId = $get_course->id;
                        } else {
                            $courseId = '';
                        }

                        $AcademicYearId = AcademicYear::where('name', $newArray[2])->select('id')->first()->id;
                        $publishSubject = AssignmentAttendances::where('status', 2)->where(['course' => $courseId, 'academic_year' => $AcademicYearId, 'semester' => $newArray[3], 'section' => $newArray[4], 'subject' => $timetables->subject])->select('id')->first();
                        $class_name = $newArray[1] . '/' . $newArray[3] . '/' . $newArray[4];

                    } else {
                        $classname = '';
                    }

                    if ($publishSubject) {
                        $publishSubject->classname = $classname;
                        $publishSubject->subject_name = $got_subjectName . ' (' . $got_subjectCode . ')';
                        $buttonss = '<a class="btn btn-xs btn-primary" href="' . route('admin.assignment_Exam_Mark_Result.resultview', [$timetables->class_name, $timetables->subject]) . '" target="_blank">View</a>';
                        $publishSubject->button = $buttonss;
                        $response[] = $publishSubject;
                    }
                }
            }
        }
        return view('admin.assignmentResult.index', compact('response', 'getAys'));
    }

    public function resultview($classId, $subjectId, $pdf = '')
    {

        $CourseEnrollMaster = CourseEnrollMaster::find($classId);
        $class_name = ClassRoom::where('name', $classId)->select('short_form')->first();
        $class_Name_short_form = $class_name->short_form;

        if ($CourseEnrollMaster) {
            $enrollName = $CourseEnrollMaster->enroll_master_number;
            $enrollArray = explode('/', $enrollName);
            $department_name = explode('.', $enrollArray[1]);
            $department_name = ltrim(end($department_name));

            if ($enrollArray) {
                $getCourse = ToolsCourse::where('name', $enrollArray[1])->first();
                if ($getCourse) {
                    $department = $getCourse->department_id;
                    $courseId = $getCourse->id;
                } else {
                    $department = '';
                    $courseId = '';
                }
                $semester = Semester::where('semester', $enrollArray[3])->first();
                if ($semester) {
                    $semId = $semester->id;
                } else {
                    $semId = '';
                }

                $getAcademicYear = AcademicYear::where('name', $enrollArray[2])->first();
                if ($getAcademicYear) {
                    $accId = $getAcademicYear->id;
                } else {
                    $accId = '';
                }
                $getSection = Section::with('course')->where('section', $enrollArray[4])->where('course_id', $courseId)->first();
                if ($getSection) {
                    $secId = $getSection->id;
                    $Section = $getSection->section;
                } else {
                    $secId = '';
                }
            }

            $newdata = [];
            $co_array = [];

            $examNames = AssignmentModel::where(['course_id' => $courseId, 'academic_year' => $accId, 'semester' => $semId, 'section' => $Section])->get();

            if (count($examNames) > 0) {
                foreach ($examNames as $examName) {

                    $exameAtt = AssignmentAttendances::where(['status' => 2, 'course' => $courseId, 'subject' => $subjectId, 'academic_year' => $accId, 'semester' => $semId, 'section' => $Section])->select('id', 'exam_name', 'subject', 'assignment_id', 'subject', 'status')->first();

                    if ($exameAtt) {

                        $subject_details = Subject::find($subjectId);
                        if ($subject_details) {
                            $examSubject = $subject_details->name . '(' . $subject_details->subject_code . ')';
                        }

                        $status = $exameAtt->status;
                        $examName = $exameAtt->exam_name;
                        $className = CourseEnrollMaster::find($classId);
                        if ($className != '') {
                            $newArray = explode('/', $className->enroll_master_number);
                            $get_course = ToolsCourse::where(['name' => $newArray[1]])->first();
                            if ($get_course) {
                                $classname = $get_course->short_form . '/' . $newArray[3] . '/' . $newArray[4];
                            }
                        } else {
                            $classname = '';
                        }

                        $exameData = AssignmentData::where([
                            'class_id' => $classId,
                            'assignment_name_id' => $exameAtt->id,
                        ])->get();
                        $total_students = $exameData->count();

                        $classId = '';
                        $subjectId = '';
                        $examId = '';
                        if ($exameData) {
                            $assignmentMarks = 50;
                            foreach ($exameData as $exameDatas) {
                                $exameDatas->student_id;
                                $classId = $exameDatas->class_id;
                                $subjectId = $exameDatas->subject;
                                $examId = $exameDatas->assignment_name_id;
                                $student = Student::where('user_name_id', $exameDatas->student_id)->first();
                                if ($student != '') {
                                    $exameDatas->studentName = $student->name;
                                    $exameDatas->studentReg = $student->register_no;
                                }
                            }
                        }

                    } else {
                        $exameData = [];

                    }
                }
            } else {
                $exameData = [];
            }
        } else {
            $exameData = [];
        }

        if ($pdf != '') {

            $pdf = PDF::loadView('admin.labResult.Exam-result-StaffWise-reportPDF', ['examMarks' => $exameData])->setOption('margin-top', 0)->setOption('margin-bottom', 0)->setOption('margin-left', 0)->setOption('margin-right', 0);
            $pdf->setPaper('A4', 'portrait');

            return $pdf->stream('Exam-result-StaffWise-report.pdf');
        } else {

            if (count($exameData) == 0) {
                $examName = '';
                $examSubject = '';
                return view('admin.assignmentResult.view', compact('exameData', 'examName', 'examSubject'));
            } else {
                return view('admin.assignmentResult.view', compact('examId', 'total_students', 'subjectId', 'classId', 'exameData', 'assignmentMarks', 'examSubject', 'classname', 'examName', 'status'));
                // return view('admin.assignmentExamMark.markview', compact('examId', 'total_students', 'subjectId', 'classId', 'exameData', 'assignmentMarks',  'examSubject', 'classname', 'examName', 'status', 'examCellCo'));

            }
        }
    }

    public function assignment_subject_get(Request $request)
    {
        $course_id = $request->course_id;
        $academic_year_id = $request->academic_year;
        $semester_id = $request->semester;
        $assignment_id = $request->id;

        if ($course_id != '' || $semester_id != '' || $academic_year_id != '') {

            $created_count = AssignmentModel::where(['course_id' => $course_id, 'semester' => $semester_id, 'academic_year' => $academic_year_id])->select('section')->get();
            if (count($created_count) == 0) {
                $get_sections = [];
                $check_input = is_numeric($request->course_id);
                $course = ToolsCourse::where('id', $request->course_id)->select('name')->first();
                $AcademicYears = AcademicYear::where('id', $academic_year_id)->select('name')->first();
                $string = '/' . $course->name . '/' . $AcademicYears->name . '/' . $semester_id . '/';
                $stu = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%' . $string . '%')->get();
                if (count($stu) > 0) {

                    foreach ($stu as $stu) {
                        $parts = explode('/', $stu->enroll_master_number)[4];
                        $students = Student::where('enroll_master_id', $stu->id)->count();
                        if ($students <= 0) {
                            $students = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $stu->id)->count();
                        }
                        if ($students > 0) {

                            if ($check_input) {
                                $stu->enroll_master_number;
                                $get_section = Section::where(['course_id' => $request->course_id, 'section' => $parts])->select('id', 'section')->get();
                                $get_sections[] = $get_section;
                            } else {

                                $get_course = ToolsCourse::where(['name' => $request->course_id, 'section' => $parts])->first();
                                if ($get_course != '') {
                                    $get_section = Section::where(['course_id' => $get_course->id])->get();
                                    $get_sections[] = $get_section;
                                }
                            }
                        }
                    }
                }

                $get_subjects = SubjectAllotment::where(['semester' => $semester_id, 'course' => $request->course_id, 'academic_year' => $academic_year_id, 'semester_type' => $request->semester_type])->get();
                $subjects = [];
                $got_subject = [];
                if (count($get_subjects) > 0) {
                    foreach ($get_subjects as $subject) {

                        $got_subject = Subject::where('id', $subject->subject_id)
                            ->whereIn('subject_type_id', [1, 2, 7, 8, 13, 14])
                            ->first();

                        if ($got_subject != '') {
                            array_push($subjects, $got_subject);
                        }
                    }
                }
                if (count($get_sections) > 0 && count($subjects) > 0) {
                    return response()->json(['subjects' => $subjects, 'get_section' => $get_sections]);
                } else if (count($get_sections) > 0) {
                    return response()->json(['subjects' => [], 'get_section' => $get_sections]);
                } else if (count($get_sections) > 0 && count($subjects) > 0) {
                    return response()->json(['subjects' => $subjects, 'get_section' => $get_sections, 'examName' => []]);
                } else {
                    return response()->json(['status' => 'fail', 'subjects' => [], 'get_section' => [], 'examName' => []]);
                }
            } else {
                $get_section = Section::where(['course_id' => $request->course_id])->select('section')->get();
                $gotSections = [];
                $allSections = [];
                $availableSections = [];
                foreach ($created_count as $data) {
                    $gotSections[] = $data->section;
                }
                foreach ($get_section as $theSec) {
                    $allSections[] = $theSec->section;
                }
                foreach ($allSections as $section) {
                    if (!in_array($section, $gotSections)) {
                        $availableSections[] = $section;
                    }
                }
                if (count($availableSections) > 0) {
                    $get_sections = [];
                    $check_input = is_numeric($request->course_id);
                    $course = ToolsCourse::where('id', $request->course_id)->select('name')->first();
                    $AcademicYears = AcademicYear::where('id', $academic_year_id)->select('name')->first();
                    foreach ($availableSections as $theSection) {
                        $string = '/' . $course->name . '/' . $AcademicYears->name . '/' . $semester_id . '/' . $theSection;
                        $stu = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%' . $string . '%')->get();
                        if (count($stu) > 0) {

                            foreach ($stu as $stu) {
                                $students = Student::where('enroll_master_id', $stu->id)->count();
                                if ($students <= 0) {
                                    $students = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $stu->id)->count();
                                }
                                if ($students > 0) {

                                    if ($check_input) {
                                        $stu->enroll_master_number;
                                        $get_section = Section::where(['course_id' => $request->course_id, 'section' => $theSection])->select('id', 'section')->get();
                                        $get_sections[] = $get_section;
                                    } else {

                                        $get_course = ToolsCourse::where(['name' => $request->course_id, 'section' => $theSection])->first();
                                        if ($get_course != '') {
                                            $get_section = Section::where(['course_id' => $get_course->id])->get();
                                            $get_sections[] = $get_section;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $get_subjects = SubjectAllotment::where(['semester' => $semester_id, 'course' => $request->course_id, 'academic_year' => $academic_year_id, 'semester_type' => $request->semester_type])->get();
                    $subjects = [];
                    $got_subject = [];
                    if (count($get_subjects) > 0) {
                        foreach ($get_subjects as $subject) {

                            $got_subject = Subject::where('id', $subject->subject_id)
                                ->whereIn('subject_type_id', [1, 2, 7, 8, 13, 14])
                                ->first();

                            if ($got_subject != '') {
                                array_push($subjects, $got_subject);
                            }
                        }
                    }
                    if (count($get_sections) > 0 && count($subjects) > 0) {
                        return response()->json(['subjects' => $subjects, 'get_section' => $get_sections]);
                    } else if (count($get_sections) > 0) {
                        return response()->json(['subjects' => [], 'get_section' => $get_sections]);
                    } else if (count($get_sections) > 0 && count($subjects) > 0) {
                        return response()->json(['subjects' => $subjects, 'get_section' => $get_sections, 'examName' => []]);
                    } else {
                        return response()->json(['status' => 'fail', 'subjects' => [], 'get_section' => [], 'examName' => []]);
                    }
                } else {
                    return response()->json(['status' => 'already done']);
                }
            }
        } else {
            return response()->json(['status' => 'fail', 'subjects' => [], 'get_section' => [], 'examName' => []]);
        }
    }

    public function assignment_subject_get_edit(Request $request)
    {

        $course_id = $request->course_id;
        $academic_year_id = $request->academic_year;
        $semester_id = $request->semester;
        $assignment_id = $request->id;
        $section = $request->section;

        $existingAssignment = AssignmentModel::where(['course_id' => $course_id, 'semester' => $semester_id, 'academic_year' => $academic_year_id, 'section' => $section])->where('id', '!=', $assignment_id)->get();

        if (count($existingAssignment) > 0) {

            return response()->json(['status' => 'Already Done']);
        } else {

            $course = ToolsCourse::where('id', $course_id)->select('name')->first();
            $AcademicYears = AcademicYear::where('id', $academic_year_id)->select('name')->first();
            $string = '/' . $course->name . '/' . $AcademicYears->name . '/' . $request->semester . '/';
            $stu = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%' . $string . '%')->get();

            if (count($stu) > 0) {

                $get_sections = [];
                $i = 0;
                foreach ($stu as $sec) {
                    $parts = explode('/', $sec->enroll_master_number)[4];
                    $students = Student::where('enroll_master_id', $sec->id)->count();
                    if ($students <= 0) {
                        $students = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $sec->id)->count();
                    }
                    if ($students > 0) {
                        $sec->enroll_master_number;
                        $get_section = Section::where(['course_id' => $course_id, 'section' => $parts])->select('id', 'section')->get();
                        $get_sections[$i] = $get_section;
                    }
                    $i++;
                }
            }
            $get_subjects = [];
            $get_subjects = SubjectAllotment::where(['semester' => $request->semester, 'course' => $course_id, 'academic_year' => $request->academic_year, 'semester_type' => $request->semester_type])->get();

            $got_subject = [];
            if (count($get_subjects) > 0) {
                $subjects = [];
                $got_subject = [];
                $subjectsNew = [];
                $status = '';
                if ($request->old_academic_year == $request->academic_year && $request->old_year == $request->year && $request->old_course_id == $course_id && $request->old_semester == $request->semester && $request->old_section == $request->section && $request->old_year == $request->year) {
                    $subject_get = AssignmentModel::where(['academic_year' => $request->academic_year, 'course_id' => $course_id, 'semester' => $request->semester, 'semester_type' => $request->semester_type, 'section' => $request->section])->where(['id' => $request->id])->select('subject', 'due_date')->first();
                }
                if (isset($subject_get) && $subject_get != '' && !isset($condition)) {

                    $subjectData = unserialize($subject_get->subject);
                    $present_subject_ids = [];
                    if ($subjectData !== false && is_array($subjectData)) {
                        // $si = 0;
                        foreach ($subjectData as $match) {
                            foreach ($match as $data => $matches) {
                                $got_subject = Subject::where('id', $data)
                                    ->select('id', 'name', 'subject_code')
                                    ->first();
                                $got_subject->date = $matches;
                                $got_subject->due_date = $subject_get->due_date;
                                array_push($subjects, $got_subject);
                            }
                            // $si++;
                        }
                    }

                    // $Exam_name->newsubject = $subjectsNew;
                } else {
                    if (count($get_subjects) == 0) {
                        $subjects = [];
                    } else {

                        foreach ($get_subjects as $subject) {

                            $got_subject = Subject::where('id', $subject->subject_id)
                                ->whereIn('subject_type_id', [1, 2, 7, 8, 13, 14])
                                ->first();
                            if ($got_subject != '') {
                                $got_subject->date = '';
                                array_push($subjects, $got_subject);
                            }
                        }
                    }
                }
            } else {
                $subjects = [];
            }

            // $subjects = $subjects->toArray();
            if ($get_sections != '' && $subjects != '') {
                return response()->json(['subjects' => $subjects, 'get_section' => $get_sections]);
            } else if ($subjects != '') {

                return response()->json(['subjects' => $subjects]);
            } else if ($get_sections != '') {
                return response()->json(['get_section' => $get_sections]);
            } else {

                return response()->json(['status' => 'fail']);
            }
        }
    }

    public function getPastRecords(Request $request)
    {
        if (isset($request->past_ay) && isset($request->past_semester)) {
            $array = [];
            // $request->past_ay = '2023-2024';
            $enroll = '%/%/' . $request->past_ay . '/' . $request->past_semester . '/%';

            $getClass = CourseEnrollMaster::where('enroll_master_number', "LIKE", $enroll)->select('id', 'enroll_master_number')->get();

            $theClass = [];

            if (count($getClass) > 0) {
                foreach ($getClass as $enrolledClass) {
                    array_push($theClass, $enrolledClass->id);
                }
            }

            $type_id = auth()->user()->roles[0]->type_id;

            $user_name_id = auth()->user()->id;
            $subjects = [];
            $response = [];
            $buttons = '';
            $buttonss = '';

            $timetable = ClassTimeTableTwo::whereIn('class_name', $theClass)->where(['staff' => $user_name_id, 'status' => 1])->groupBy('class_name', 'subject')->select('class_name', 'subject')->get();
            if ($timetable) {
                foreach ($timetable as $timetables) {

                    $got_subjectDetails = Subject::where(['id' => $timetables->subject])->select('name', 'subject_code')->first();

                    $AcademicYear = '';
                    $publishSubject = '';
                    $get_enroll = CourseEnrollMaster::where(['id' => $timetables->class_name])->first();

                    if ($get_enroll) {

                        if ($got_subjectDetails) {
                            $got_subjectName = $got_subjectDetails->name;
                            $got_subjectCode = $got_subjectDetails->subject_code;
                        } else {
                            $got_subjectName = '';
                            $got_subjectCode = '';
                        }

                        if ($get_enroll != '') {
                            $newArray = explode('/', $get_enroll->enroll_master_number);
                            $get_course = ToolsCourse::where(['name' => $newArray[1]])->first();
                            if ($get_course) {
                                $classname = $get_course->short_form . '/' . $newArray[3] . '/' . $newArray[4];
                                $courseId = $get_course->id;
                            } else {
                                $courseId = '';
                            }

                            $AcademicYearId = AcademicYear::where('name', $newArray[2])->select('id')->first()->id;
                            $publishSubject = AssignmentAttendances::where('status', 2)->where(['course' => $courseId, 'academic_year' => $AcademicYearId, 'semester' => $newArray[3], 'section' => $newArray[4], 'subject' => $timetables->subject])->select('id')->first();
                            $class_name = $newArray[1] . '/' . $newArray[3] . '/' . $newArray[4];

                        } else {
                            $classname = '';
                        }

                        if ($publishSubject) {
                            $publishSubject->classname = $classname;
                            $publishSubject->subject_name = $got_subjectName . ' (' . $got_subjectCode . ')';
                            $buttonss = '<a class="btn btn-xs btn-primary" href="' . route('admin.assignment_Exam_Mark_Result.resultview', [$timetables->class_name, $timetables->subject]) . '" target="_blank">View</a>';
                            $publishSubject->button = $buttonss;
                            $response[] = $publishSubject;
                        }
                    }
                }

                return response()->json(['status' => true, 'data' => $response]);
            } else {
                return response()->json(['status' => false, 'data' => 'Classes Not Found']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }
}
