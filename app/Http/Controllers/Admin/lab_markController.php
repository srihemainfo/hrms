<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\ClassRoom;
use App\Models\ToolsCourse;
use App\Models\AcademicYear;
use App\Models\CollegeBlock;
use Illuminate\Http\Request;
use App\Models\LabFirstmodel;
use App\Models\ToolsDepartment;
use App\Models\SubjectAllotment;
use App\Models\ClassTimeTableTwo;
use App\Models\LabExamAttendance;
use App\Models\CourseEnrollMaster;
use Illuminate\Support\Facades\DB;
use App\Models\Tools_Labmark_Title;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\LabExamAttendanceData;
use App\Models\StudentPromotionHistory;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;

class lab_markController extends Controller
{
    public function index(Request $request)
    {

        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $AcademicYear = AcademicYear::pluck('name', 'id');
        $examNames = LabFirstmodel::select('exam_name')->distinct()->get();
        $MarkType = Tools_Labmark_Title::pluck('name', 'id');

        if ($request->ajax()) {
            $exam = [];
            $courses = ToolsCourse::pluck('name', 'id');
            $semester = Semester::pluck('semester', 'id');
            $AcademicYear = AcademicYear::pluck('name', 'id');
            $examNames = LabFirstmodel::select('id', 'exam_name', 'course_id', 'accademicYear', 'section', 'semester', 'year', 'subject', 'due_date')->distinct()->get();
            foreach ($examNames as $examName) {

                $course = ToolsCourse::where('id', $examName->course_id)->select('name')->first();
                $AcademicYears = AcademicYear::where('id', $examName->accademicYear)->select('name')->first();
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
                        LabFirstmodel::where('id', $examName->id)->update(['deleted_at' => now()]);
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
                $crudRoutePart = 'lab-mark';

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
            $table->editColumn('accademicYear', function ($row) {
                if ($row->accademicYear != '') {
                    $ay = AcademicYear::find($row->accademicYear);
                    if ($ay) {
                        $academicYear = $ay->name;
                    } else {
                        $academicYear = '';
                    }
                }
                return $row->accademicYear ? $academicYear : '';
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
            // $table->editColumn('start_time', function ($row) {
            //     if ($row->subject) {
            //         if (app(ExamTimetableCreationController::class)->is_serialized($row->subject)) {
            //             $dummy = unserialize($row->subject);

            //             // Check if $dummy is an array or object
            //             if (is_array($dummy) || is_object($dummy)) {
            //                 $dates = [];

            //                 foreach ($dummy as $dummy1) {
            //                     foreach ($dummy1 as $dummy2) {
            //                         array_push($dates, $dummy2);
            //                     }
            //                 }

            //                 $timestamps = array_map(function ($dateString) {
            //                     return strtotime($dateString);
            //                 }, $dates);

            //                 // Find the first date (minimum timestamp)
            //                 $firstDateTimestamp = min($timestamps);
            //                 $firstDate = date('Y-m-d', $firstDateTimestamp);

            //                 return $firstDate;
            //             } else {
            //                 // Handle invalid or unserialized data
            //                 return 'Invalid Data';
            //             }
            //         }
            //     }

            //     return '';
            // });

            // $table->editColumn('end_time', function ($row) {
            //     if ($row->subject) {
            //         if (app(ExamTimetableCreationController::class)->is_serialized($row->subject)) {
            //             $dummy = unserialize($row->subject);

            //             // Check if $dummy is an array or object
            //             if (is_array($dummy) || is_object($dummy)) {
            //                 $dates = [];

            //                 foreach ($dummy as $dummy1) {
            //                     foreach ($dummy1 as $dummy2) {
            //                         array_push($dates, $dummy2);
            //                     }
            //                 }

            //                 $timestamps = array_map(function ($dateString) {
            //                     return strtotime($dateString);
            //                 }, $dates);

            //                 // Find the last date (maximum timestamp)
            //                 $lastDateTimestamp = max($timestamps);
            //                 $lastDate = date('Y-m-d', $lastDateTimestamp);

            //                 return $lastDate;
            //             } else {
            //                 // Handle invalid or unserialized data
            //                 return 'Invalid Data';
            //             }
            //         }
            //     }

            //     return '';
            // });
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
        return view('admin.labSchedule.index', compact('AcademicYear', 'MarkType', 'courses', 'semester'));
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
        $MarkType = Tools_Labmark_Title::pluck('name', 'id');
        // $Section = Section::select('id', 'section')->groupBy('id', 'section')->get();
        return view('admin.labSchedule.create', compact('courses', 'departments', 'semester', 'Subjects', 'blocks', 'classrooms', 'AcademicYear', 'MarkType'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'accademicYear' => 'required',
            'semesterType' => 'required',
            'course_id' => 'required',
            'year' => 'required',
            'semester' => 'required',
            'due_date' => 'required',
            'MarkType' => 'required',
            'subject' => 'required',
            'sections' => 'required|array',
        ]);
        $accademicYear = $request->accademicYear;
        $semesterType = $request->semesterType;
        $course_id = $request->course_id;
        $year = $request->year;
        $semester = $request->semester;
        $MarkType = $request->MarkType;

        $existingLabExam = LabFirstmodel::where(['accademicYear' => $accademicYear, 'semesterType' => $semesterType, 'course_id' => $course_id, 'year' => $year, 'semester' => $semester, 'MarkType' => $MarkType])->select('id')->first();
        if (!$existingLabExam) {
            try {
                $subjects = json_decode($request->input('subject'), true);
                $sections = $request->input('sections');
                $Academicyear = AcademicYear::where('id', $request->input('accademicYear'))->select('name')->first();

                if (!$subjects || !$sections) {
                    return response()->json(['message' => 'Invalid or incomplete data.'], 400);
                }

                $serializedSubject = '';
                // $serializedCo_mark = '';

                foreach ($sections as $section) {

                    foreach ($subjects as $index => $subjectData) {
                        if (!empty($subjectData)) {
                            $serializedSubject = serialize($subjects);
                            // $serializedCo_mark = serialize($co_mark);
                        } else {
                            $serializedSubject = '';
                            // $serializedCo_mark = '';
                        }
                    }

                    DB::transaction(function () use ($request, $serializedSubject, $section, $subjects, $Academicyear) {
                        // Create a new LabFirstmodel record
                        $newExamTimetable = LabFirstmodel::create([
                            'course_id' => $request->input('course_id'),
                            'semester' => $request->input('semester'),
                            'subject' => $serializedSubject,
                            'exam_name' => $request->input('MarkType') . '/' . $Academicyear->name . '/0' . $request->input('semester'),
                            'due_date' => $request->input('due_date'),
                            'accademicYear' => $request->input('accademicYear'),
                            'semesterType' => $request->input('semesterType'),
                            'year' => $request->input('year'),
                            'section' => $section,
                            'MarkType' => $request->input('MarkType'),
                        ]);

                        // Create related Examattendance records
                        foreach ($subjects as $subjectData) {
                            foreach ($subjectData as $ids => $subject) {

                                $Exam_attedance_id = LabExamAttendance::create([
                                    'lab_exam_id' => $newExamTimetable->id,
                                    'course' => $newExamTimetable->course_id,
                                    'subject' => $ids,
                                    'acyear' => $newExamTimetable->accademicYear,
                                    'examename' => $newExamTimetable->exam_name,
                                    'sem' => $newExamTimetable->semester,
                                    'year' => $newExamTimetable->year,
                                    'section' => $newExamTimetable->section,
                                    'cycle_exam_mark' => 100,
                                    'pass_count' => 0,
                                    'fail_count' => 0,
                                ]);
                                $lastinsertedId = $Exam_attedance_id->id;
                                $lastinsertedId = $lastinsertedId;

                                $courseName = ToolsCourse::where('id', $request->course_id)->select('name')->first();
                                $academicYearName = AcademicYear::where('id', $request->accademicYear)->select('name')->first();
                                $semesterName = explode('0', $request->semester)[0];

                                $string = '/' . $courseName->name . '/' . $academicYearName->name . '/' . $semesterName . '/' . $newExamTimetable->section;
                                $stu = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%' . $string)->select('id')->first();
                                $studentList = Student::where('enroll_master_id', $stu->id)
                                    ->orderBy('register_no', 'asc')->select('user_name_id')
                                    ->get();
                                    if (count($studentList) <= 0) {
                                        $studentList = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $stu->id)->orderBy('students.register_no', 'asc')->select('students.user_name_id')->get();
                                    }
                                if (count($studentList) > 0) {

                                    foreach ($studentList as $id) {

                                        // Create a new ExamattendanceData record
                                        $entered = LabExamAttendanceData::create([
                                            'date' => now(),
                                            'subject' => $ids,
                                            'enteredby' => auth()->user()->id,
                                            'class_id' => $stu->id,
                                            'student_id' => $id->user_name_id,
                                            'lab_exam_name' => $lastinsertedId,
                                        ]);

                                    }
                                } else {
                                    return response()->json(['message' => 'Invalid or incomplete data.'], 400);
                                }
                            }
                        }
                    });
                }
                // Session::flash('message', 'Lab Exam timetable successfully Created.');
                // return response(null, Response::HTTP_NO_CONTENT);
                Session::flash('message', 'Lab Exam timetable successfully created.');
                return response(null, Response::HTTP_NO_CONTENT);
            } catch (\Exception $e) {
                DB::rollBack();

                // Log the error for debugging
                Log::error('Error creating Lab Exam timetable: ' . $e->getMessage());

                Session::flash('message_error', 'Error occurred while creating Lab Exam timetable. Changes rolled back.');
                return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            Session::flash('message_error', 'Lab Exam timetable Already  Created.');
            return response(null, Response::HTTP_NO_CONTENT);
        }
        // return response()->json(['message' => 'Exam timetable(s) successfully processed.']);
    }

    public function view($id)
    {

        $labMarkschedule = LabFirstmodel::find($id);

        if ($labMarkschedule) {
            $course = ToolsCourse::where('id', $labMarkschedule->course_id)->select('name')->first();
            $labMarkschedule->course_id = $course->name;
            $AcademicYear = AcademicYear::where('id', $labMarkschedule->accademicYear)->select('name')->first();
            $labMarkschedule->accademicYear = $AcademicYear->name;
            $exam_name = $labMarkschedule->MarkType;
            $subjectData = unserialize($labMarkschedule->subject);
            $subjectsNew = [];
            if ($labMarkschedule->year == 01) {
                $labMarkschedule->year = "First Year";
            } else if ($labMarkschedule->year == 02) {
                $labMarkschedule->year = "Second Year";
            } else if ($labMarkschedule->year == 03) {
                $labMarkschedule->year = "Third Year";
            } else if ($labMarkschedule->year == 04) {
                $labMarkschedule->year = "Fourth Year";
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

            $labMarkschedule->newsubject = $subjectsNew;
        }
        return view('admin.labSchedule.view', compact('labMarkschedule'));
    }
    public function search(Request $request)
    {

        // $courses = ToolsCourse::pluck('name', 'id');
        // $semester = Semester::pluck('semester', 'id');
        // $AcademicYear = AcademicYear::pluck('name', 'id');
        // $examNames = LabFirstmodel::select('exam_name')->distinct()->get();
        // $MarkType = Tools_Labmark_Title::pluck('name', 'id');

        // if ($request->ajax()) {

        $examename = $request->input('examename');
        $accademicYear = $request->input('academicYear_id');
        $semesterType = $request->input('SemesterType');

        $accademicYear = $request->input('academicYear_id');

        $SemesterType = $request->input('SemesterType');
        $MarkType = $request->input('MarkType');

        $lab_table_details = LabFirstmodel::where(['MarkType' => $MarkType, 'accademicYear' => $accademicYear, 'semesterType' => $semesterType])->select('id', 'course_id', 'year', 'semester', 'section', 'semesterType')->get();
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
            // $lab_attendance_check = LabExamAttendance::whereIn('lab_exam_id',$lab_table)->get();
            // $count = count($lab_attendance_check);
        } else {
            $course_get = [];
            $year_get = [];
            $semester_get = [];
            $sections_get = [];
        }

        $accademicYear = $request->input('academicYear_id');
        // $course = $request->input('course_id');
        // $semester = $request->input('semester_id');
        // $year = $request->input('year');
        $SemesterType = $request->input('SemesterType');
        $MarkType = $request->input('MarkType');

        // Start with a base query
        $query = LabFirstmodel::query();

        // Check each input value and add a where clause if it's present
        if ($accademicYear) {
            $query->Where('accademicYear', $accademicYear);
        }
        // if ($course) {
        //     $query->Where('course', $course);
        // }
        // if ($semester) {
        //     $query->Where('semester', $semester);
        // }
        // if ($year) {
        //     $query->Where('year', $year);
        // }
        if ($SemesterType) {
            $query->Where('semesterType', $SemesterType);
        }
        if ($MarkType) {
            $query->Where('MarkType', $MarkType);
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
            $crudRoutePart = 'lab-mark';

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
        $table->editColumn('accademicYear', function ($row) {
            if ($row->accademicYear != '') {
                $ay = AcademicYear::find($row->accademicYear);
                if ($ay) {
                    $academicYear = $ay->name;
                } else {
                    $academicYear = '';
                }
            }
            return $row->accademicYear ? $academicYear : '';
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
        $getcount = LabExamAttendance::where('lab_exam_id', $id)->select('id')->count();

        if ($getcount > 0) {
            $verify_or_pulish_check = LabExamAttendance::where('lab_exam_id', $id)->whereIn('status', [1, 2, 3])->select('id')->count();
            if ($verify_or_pulish_check > 0) {
                $actionMaded = true;
            } else {
                $actionMaded = false;
            }
            $departments = ToolsDepartment::pluck('name', 'id');
            $courses = ToolsCourse::pluck('name', 'id');
            $semester = Semester::pluck('semester', 'id');
            $Subjects = Subject::get();
            $blocks = CollegeBlock::pluck('name', 'id');
            $classrooms = ClassRoom::pluck('name', 'id');
            $AcademicYear = AcademicYear::pluck('name', 'id');
            // $MarkType=Tools_Labmark_Title::pluck('name', 'id');

            $labSchedule = LabFirstmodel::find($id);
            $semesters = $labSchedule->semester;
            $coures = $labSchedule->course_id;
            $accademicYears = $labSchedule->accademicYear;
            $semesterTypes = $labSchedule->semesterType;
            $sections = $labSchedule->section;
            $cycle_mark_check = LabFirstmodel::where(['accademicYear' => $accademicYears, 'course_id' => $coures, 'semester' => $semesters, 'semesterType' => $semesterTypes, 'section' => $sections])->where('id', '!=', $id)->select('MarkType')->get();
            $MarkType = Tools_Labmark_Title::whereNotIn('name', $cycle_mark_check)->select('name')->get();

            $get_subjects = [];

            $subjects = [];
            $got_subject = [];
            $subjectsNew = [];
            if ($labSchedule) {

                $subjectData = unserialize($labSchedule->subject);
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

                $labSchedule->newsubject = $subjectsNew;
            }

            $labExamTimetable = $labSchedule;

            return view('admin.labSchedule.edit', compact('MarkType', 'labExamTimetable', 'courses', 'departments', 'semester', 'Subjects', 'blocks', 'classrooms', 'AcademicYear', 'present_subject_ids', 'actionMaded'));
        } else {
            // *** return to_route('admin.lab_mark.index')->with('message_error', 'Exam Already Mark Enter or Verify or Published So can\'t Edit.');
            return to_route('admin.lab_mark.index')->with('message_error', 'Exam Not Found.');

        }
    }

    public function update(Request $request, LabFirstmodel $LabFirstmodel, $id)
    {
        // dd($id);
        // $verify_or_pulish_check = LabExamAttendance::where('lab_exam_id', $id)->whereIn('status', [1, 2, 3])->select('id')->get();
        // $count = count($verify_or_pulish_check);

        $request->validate([
            'accademicYear' => 'required',
            'semesterType' => 'required',
            'examName' => 'required',
            'year' => 'required',
            'course_id' => 'required',
            'semester' => 'required',
            'subject' => 'required',
            'sections' => 'required',
            'MarkType' => 'required',
        ]);

        $Academicyear = AcademicYear::where('id', $request->input('accademicYear'))->select('name')->first();

        $subjects = json_decode($request->input('subject'), true);
        $sections = $request->input('sections');

        if (!$subjects || !$sections) {
            return response()->json(['message' => 'Invalid or incomplete data.'], 400);
        }

        $coData = $request->input('hidden');
        $subjectsss = serialize($subjects);

        $examTimetableCreation = LabFirstmodel::find($id);

        if (!$examTimetableCreation) {
            return redirect()->route('admin.Exam-time-table.index')->with('error', 'Exam Timetable Not Found.');
        }

        $examTimetableCreation->update([
            'subject' => $subjectsss,
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'semesterType' => $request->input('semesterType'),
            'exam_name' => $request->input('MarkType') . '/' . $Academicyear->name . '/0' . $request->input('semester'),
            'MarkType' => $request->input('MarkType'),

        ]);

        $attData = LabExamAttendance::where('lab_exam_id', $examTimetableCreation->id)->get();
        $subjectTotal = [];

        foreach ($subjects as $subjectData) {
            foreach ($subjectData as $ids => $subject) {
                array_push($subjectTotal, $ids);
            }
        }

        if ($attData) {
            foreach ($attData as $attDatas) {
                if (!in_array($attDatas->subject, $subjectTotal)) {
                    LabExamAttendanceData::where('lab_exam_name', $attDatas->id)->delete();
                    $attDatas->delete();
                }
            }
        }

        $courseName = ToolsCourse::where('id', $request->course_id)->select('name')->first();
        $academicYearName = AcademicYear::where('id', $request->accademicYear)->select('name')->first();
        $semesterName = explode('0', $request->semester)[0];

        $string = '/' . $courseName->name . '/' . $academicYearName->name . '/' . $semesterName . '/' . $request->sections;
        $stu = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%' . $string)->select('id')->first();
        $studentList = Student::where('enroll_master_id', $stu->id)
            ->orderBy('register_no', 'asc')->select('user_name_id')
            ->get();
            if (count($studentList) <= 0) {
                $studentList = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $stu->id)->orderBy('students.register_no', 'asc')->select('students.user_name_id')->get();
            }
        foreach ($subjects as $subjectData) {
            foreach ($subjectData as $ids => $subject) {
                $checkLabExamAtt = LabExamAttendance::where(['lab_exam_id' => $examTimetableCreation->id, 'subject' => $ids])->select('id')->first();
                if ($checkLabExamAtt != '') {
                    $Exam_attedance_id = LabExamAttendance::where(['id' => $checkLabExamAtt->id])->update([
                        'course' => $request->input('course_id'),
                        'acyear' => $request->input('accademicYear'),
                        'examename' => $request->input('examName'),
                        'sem' => $request->input('semester'),
                        'year' => $request->input('year'),
                        'section' => $request->input('sections'),
                        'updateby' => auth()->user()->id,
                    ]);
                    $lastinsertedId = $checkLabExamAtt->id;
                } else {
                    $Exam_attedance_id = LabExamAttendance::create([
                        'lab_exam_id' => $examTimetableCreation->id,
                        'subject' => $ids,
                        'course' => $request->input('course_id'),
                        'acyear' => $request->input('accademicYear'),
                        'examename' => $request->input('examName'),
                        'sem' => $request->input('semester'),
                        'year' => $request->input('year'),
                        'section' => $request->input('sections'),
                        'cycle_exam_mark' => 100,
                        'pass_count' => 0,
                        'fail_count' => 0,
                        'pass_percentage' => 0,
                        'fail_percentage' => 0,
                        'updateby' => auth()->user()->id,
                    ]);
                    $checkLabExamAtt = LabExamAttendance::where(['lab_exam_id' => $examTimetableCreation->id, 'subject' => $ids])->select('id')->first();
                    $lastinsertedId = $checkLabExamAtt->id;
                }

                if (count($studentList) > 0) {

                    foreach ($studentList as $id) {

                        $checkStudent = LabExamAttendanceData::where(['subject' => $ids, 'lab_exam_name' => $lastinsertedId, 'student_id' => $id->user_name_id, 'class_id' => $stu->id])->select('id')->first();
                        if ($checkStudent != '') {
                            $update = LabExamAttendanceData::where(['id' => $checkStudent->id])->update([
                                'enteredby' => auth()->user()->id,
                                'date' => now(),
                            ]);
                        } else {
                            $store = LabExamAttendanceData::create([
                                'subject' => $ids,
                                'lab_exam_name' => $lastinsertedId,
                                'enteredby' => auth()->user()->id,
                                'date' => now(),
                                'class_id' => $stu->id,
                                'student_id' => $id->user_name_id,
                            ]);
                        }
                    }
                } else {
                    return response()->json(['message' => 'Invalid or incomplete data.'], 400);
                }
            }
        }

        Session::flash('message', 'Lab Exam Timetable Successfully Edited.');
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function destroy($ExamTimetableCreation)
    {
        $existing_record_publish_or_not = LabExamAttendance::where('lab_exam_id', $ExamTimetableCreation)->whereIn('status', [1, 2, 3])->select('id')->get();
        $count = count($existing_record_publish_or_not);
        $labModelId = LabFirstmodel::where('id', $ExamTimetableCreation)->select('id')->first();

        if ($labModelId) {
            if ($count == 0) {

                $attendance_remove = LabExamAttendance::where('lab_exam_id', $labModelId->id)->select('id')->get();

                foreach ($attendance_remove as $attendanceId) {

                    $studentAttedanceDelete = LabExamAttendanceData::where('lab_exam_name', $attendanceId->id)->delete();
                    $StudentSujectDelete = LabExamAttendance::where('id', $attendanceId->id)->delete();

                }

                $labModelId->delete();
            } else {
                return redirect()->route('admin.lab_mark.index')->with('message_error', 'Exam  Already Mark Enter or  Verify or Published so Can\'t be Deleted .');

            }

            return redirect()->route('admin.lab_mark.index')->with('message', 'Exam timetable successfully deleted.');
        } else {
            return redirect()->route('admin.lab_mark.index')->with('message_error', 'Exam timetable not found.');
        }
    }

    public function attendance(Request $request)
    {
        // Retrieve the authenticated user's role ID
        $role_id = auth()->user()->roles->first()->id;

        // Retrieve necessary data
        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $Subjects = Subject::get();
        $AcademicYear = AcademicYear::pluck('name', 'id');
        $examNames = LabFirstmodel::select('exam_name')->distinct()->get();

        // Initialize an empty array to store Examattendance records
        $exameAtt = [];
        $store = [];

        // Retrieve Examattendance records with eager loading and chunking
        LabExamAttendance::with([
            'courseEnrollMaster',
            'academicYear',
            'semester',
            'teachingStaff',
            'nonTeachingStaff',
            'user',
            'subject',
        ])->chunk(50, function ($exameAttChunk) use ($role_id, &$exameAtt) {
            foreach ($exameAttChunk as $record) {
                // Initialize totalstudent to 0
                $record->totalstudent = 0;

                // Build the search string for CourseEnrollMaster
                $courseName = optional($record->courseEnrollMaster)->name;
                $academicYearName = optional($record->academicYear)->name;
                $semesterName = optional($record->semester)->semester;
                $string = '/' . $courseName . '/' . $academicYearName . '/' . $semesterName . '/' . $record->section;

                // Find the CourseEnrollMaster record
                $stu = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%' . $string)->first();

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
                    $record->actions = $buttons;
                    $record->course = $courseName;

                    // If subject is not empty, format subject and subject code
                    if ($record->subject != '') {
                        $subject = Subject::find($record->subject);
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

        return view('admin.lab_Exam_attendance.index', compact('courses', 'semester', 'Subjects', 'AcademicYear', 'examNames', 'exameAtt'));
    }

    public function attendanceEnter($request, $id)
    {

        $class_name = '';
        $subject_data = '';
        $date = '';
        $studentList = [];
        if (is_numeric($request) && $id != '') {

            $className = CourseEnrollMaster::find($request);
            $classname = $className != null ? $className->enroll_master_number : '';
            if ($classname != '') {
                $newArray = explode('/', $className->enroll_master_number);
                $get_course = ToolsCourse::where(['name' => $newArray[1]])->first();
                if ($get_course) {
                    $class_name = $get_course->short_form . '/' . $newArray[3] . '/' . $newArray[4];
                }
            } else {
                $class_name = '';
            }
            $subjectData = LabExamAttendance::find($id);
            if ($subjectData) {
                $date = $subjectData->date;

                $got_subject = Subject::find($subjectData->subject);
                if ($got_subject) {
                    $subject_data = $got_subject->name . '(' . $got_subject->subject_code . ')';
                }

                $studentList = DB::table('subject_registration')->leftJoin('students', 'subject_registration.user_name_id', '=', 'students.user_name_id')->where('subject_registration.enroll_master', $request)->where('subject_registration.subject_id', $subjectData->subject)
                    ->select('students.register_no', 'students.user_name_id', 'students.name','students.enroll_master_id')
                    ->orderBy('students.register_no', 'asc')
                    ->get();
            }

            $studentList->exameid = $id;
        } else {
            $studentList = null;
            $studentList->exameid = '';
        }

        return view('admin.lab_Exam_attendance.enter', compact('studentList', 'class_name', 'subject_data', 'date'));
    }

    public function attendencestore(Request $request)
    {
        if ($request) {
            $attendenceData = $request->attendance ?? '';
            $enroll_id = $request->enroll_id ?? '';
            $exameid = $request->exameid ?? '';
            $exam = LabExamAttendance::find($exameid);

            if ($exam && $attendenceData) {
                $present = 0;
                $absent = 0;

                foreach ($attendenceData as $id => $attendanceData) {
                    // Check if a record with the same criteria already exists
                    $existingRecord = LabExamAttendanceData::where([
                        'class_id' => $enroll_id,
                        'student_id' => $id,
                        'lab_exam_name' => $exameid,
                    ])->first();

                    if (!$existingRecord) {
                        // Create a new ExamattendanceData record
                        $entered = LabExamAttendanceData::create([
                            'date' => now(),
                            'subject' => $exam->subject,
                            'enteredby' => auth()->user()->id,
                            'class_id' => $enroll_id,
                            'attendance' => $attendanceData,
                            'student_id' => $id,
                            'lab_exam_name' => $exameid,
                            'exame_date' => $exam->date,
                        ]);
                    } else {
                        // Update the existing record with the new data
                        $existingRecord->attendance = $attendanceData;
                        $existingRecord->enteredby = auth()->user()->id;
                        $existingRecord->save();
                    }

                    if ($attendanceData == "Present") {
                        $present++;
                    } elseif ($attendanceData == "Absent") {
                        $absent++;
                    }
                }

                if (isset($entered)) {
                    $exam->total_present = $present;
                    $exam->total_abscent = $absent;
                    $exam->att_entered = 'Yes';

                    $exam->date_entered = $entered->date;
                    $exam->entered_by = $entered->enteredby;
                    $exam->save();
                }
            }
        }
        return redirect()->route('admin.lab_Exam_Attendance.attendance')->with('message', 'Attendance Entered successfully');
    }

    public function attendenceUpdate(Request $request)
    {
        $exameid = null;
        $enroll_id = null;
        if ($request) {
            $attendenceData = $request->attendance ?? '';
            $enroll_id = $request->enroll_id ?? '';
            $exameid = $request->exameid ?? '';
            $exam = LabExamAttendance::find($exameid);
            if ($exam) {
                if ($attendenceData) {
                    $present = 0;
                    $absent = 0;

                    foreach ($attendenceData as $id => $attendanceData) {
                        if ($attendanceData == "Present") {
                            $present++;
                        }
                        if ($attendanceData == "Absent") {
                            $absent++;
                        }

                        // Create a new ExamattendanceData record
                        $entered = LabExamAttendanceData::where(['class_id' => $enroll_id, 'lab_exam_name' => $exameid, 'student_id' => $id])->update([
                            'attendance' => $attendanceData,
                            'edited_by' => auth()->user()->id,
                        ]);
                    }
                    $exam->total_present = $present;
                    $exam->total_abscent = $absent;
                    $exam->save();
                }
            }
        }

        return redirect()->route('admin.lab_exam_attendance.editattendance', [$enroll_id, $exameid])->with('message', 'Attendance Updated successfully');
    }

    public function editattendance($request, $id)
    {
        $class_name = '';
        $subject_data = '';
        if (is_numeric($request) && $id != '') {
            $studentList = LabExamAttendanceData::where(['class_id' => $request, 'lab_exam_name' => $id])->get();
            $className = CourseEnrollMaster::find($request);
            $classname = $className != null ? $className->enroll_master_number : '';
            if ($classname != '') {
                $newArray = explode('/', $className->enroll_master_number);
                $get_course = ToolsCourse::where(['name' => $newArray[1]])->first();
                if ($get_course) {
                    $class_name = $get_course->short_form . '/' . $newArray[3] . '/' . $newArray[4];
                }
                // $class_name=$newArray[1].'/'.$newArray[3].'/'.$newArray[4];

            } else {
                $class_name = '';
            }
            $subjectData = LabExamAttendance::find($id);
            if ($subjectData) {
                $date = $subjectData->date;

                $got_subject = Subject::find($subjectData->subject);
                if ($got_subject) {
                    $subject_data = $got_subject->name . '(' . $got_subject->subject_code . ')';
                }
            }
            if ($studentList) {
                foreach ($studentList as $studentLists) {
                    $studentData = Student::where('user_name_id', $studentLists->student_id)->first();
                    if ($studentData != '') {
                        $studentLists->name = $studentData->name ?? '';
                        $studentLists->register_no = $studentData->register_no ?? '';
                        $studentLists->user_name_id = $studentData->user_name_id ?? '';
                        $studentLists->enroll_master_id = $studentData->enroll_master_id ?? '';
                    }
                }
                $studentList->exameid = $id;
            }
        } else {
            $studentList = null;
            $studentList->exameid = '';
        }

        return view('admin.lab_Exam_attendance.edit', compact('studentList', 'class_name', 'subject_data', 'date'));
    }

    public function viewattendance($request, $id)
    {
        $studentList = null;
        $class_name = '';
        $subject_data = '';
        $date = '';
        $examename_id = $id;
        if (isset($request, $id)) {
            $studentList = LabExamAttendanceData::where(['class_id' => $request, 'lab_exam_name' => $id])->get();
            $className = CourseEnrollMaster::find($request);
            $classname = $className != null ? $className->enroll_master_number : '';
            if ($classname != '') {
                $newArray = explode('/', $className->enroll_master_number);
                $get_course = ToolsCourse::where(['name' => $newArray[1]])->first();
                if ($get_course) {
                    $class_name = $get_course->short_form . '/' . $newArray[3] . '/' . $newArray[4];
                }
                // $class_name=$newArray[1].'/'.$newArray[3].'/'.$newArray[4];

            } else {
                $class_name = '';
            }
            $subjectData = LabExamAttendance::find($id);
            if ($subjectData) {
                $date = $subjectData->date;

                $got_subject = Subject::find($subjectData->subject);
                if ($got_subject) {
                    $subject_data = $got_subject->name . '(' . $got_subject->subject_code . ')';
                }
            }
            if ($studentList) {
                foreach ($studentList as $studentLists) {
                    $studentData = Student::where('user_name_id', $studentLists->student_id)->first();
                    if ($studentData != '') {
                        $studentLists->name = $studentData->name ?? '';
                        $studentLists->register_no = $studentData->register_no ?? '';
                    }
                }
            }
        }
        $present = 0;
        $absent = 0;
        $summary = [];
        foreach ($studentList as $id => $student_count) {
            $status = $student_count->attendance;
            if ($status == 'Present') {
                $present++;
            } elseif ($status == 'Absent') {
                $absent++;
            }
        }
        $total_student = 0;
        $total_student += $present + $absent;
        $summary[] = $total_student;
        $summary[] = $present;
        $summary[] = $absent;
        $summary[] = $request;
        $summary[] = $examename_id;
        return view('admin.lab_Exam_attendance.view', compact('studentList', 'class_name', 'subject_data', 'date', 'summary'));
    }

    public function find(Request $request)
    {
        $role_id = auth()->user()->roles->first()->id;

        $accademicYear = $request->input('academicYear_id');
        $course = $request->input('course_id');
        $semester = $request->input('semester_id');
        $year = $request->input('year');
        $section = $request->input('section');
        $examename = $request->input('examename');

        // Start with a base query
        $query = LabExamAttendance::select(
            'id',
            'examename',
            'course',
            'date',
            'subject',
            'total_present',
            'total_abscent',
            'date_entered',
            'entered_by',
            'acyear',
            'sem',
            'section',
            'year',
            'att_entered'
        )->with([
            'courseEnrollMaster:id,name', // Select the 'id' and 'name' columns
            'academicYear:id,name', // Select the 'id' and 'name' columns
            'semester:id,semester', // Select the 'id' and 'semester' columns
            'teachingStaff:id,StaffCode,name', // Select the 'id', 'StaffCode', and 'name' columns
            'nonTeachingStaff:id,StaffCode,name', // Select the 'id', 'StaffCode', and 'name' columns
            'user:id,name', // Select the 'id' and 'name' columns
            'subject:id,subject_code,name', // Select the 'id', 'subject_code', and 'name' columns
        ]);

        // Check each input value and add a where clause if it's present
        if ($accademicYear) {
            $query->where('acyear', $accademicYear);
        }
        if ($course) {
            $query->where('course', $course);
        }
        if ($semester) {
            $query->where('sem', $semester);
        }
        if ($year) {
            $query->where('year', $year);
        }
        if ($section) {
            $query->where('section', $section);
        }
        if ($examename) {
            $query->where('examename', $examename);
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
                    $examAtt = LabExamAttendanceData::where(['class_id' => $timetables->class_name, 'subject' => $timetables->subject])->groupBy(['class_id', 'subject', 'lab_exam_name'])->select(['class_id', 'subject', 'lab_exam_name'])->get();

                    if ($examAtt) {
                        foreach ($examAtt as $attendanceData) {

                            // $examAtt->examename;
                            $responsesss = LabExamAttendance::where([
                                'id' => $attendanceData->lab_exam_name,
                                'mark_entry' => '1',
                            ])->first();

                            if ($responsesss) {
                                $due_dates = LabFirstmodel::where('id', $responsesss->lab_exam_id)->select('due_date', 'exam_name', 'section')->first();
                                $exam_name = $due_dates->exam_name;
                                $due_date = $due_dates->due_date;
                                $section = $due_dates->section;
                                $attendanceData->classname = $classname;

                                $responsesss->classname = $classname;

                                if ($responsesss->mark_entereby == null) {
                                    $buttonss = '<a class="btn btn-xs btn-info" href="' . route('admin.lab_Exam_Mark.markEnter', [$timetables->class_name, $responsesss->id]) . '" target="_blank">Enter</a>';
                                    $markstatus = 'Not Submitted';
                                } else {
                                    $buttonss = '<a class="btn btn-xs btn-primary" href="' . route('admin.lab_Exam_Mark.markview', [$timetables->class_name, $responsesss->id]) . '" target="_blank">View</a>';
                                    $markstatus = 'Submitted';
                                }
                                if ($responsesss->status == 3 && $responsesss->mark_entereby != null) {

                                    $edit_buttons = '<a class="btn btn-xs btn-primary" href="' . route('admin.lab_Exam_Mark.editMark', [$timetables->class_name, $responsesss->id]) . '" target="_blank">Edit</a>';
                                } else {
                                    $edit_buttons = '';
                                }
                                $attendanceData->button = $buttonss;
                                $attendanceData->edit_buttons = $edit_buttons;
                                $attendanceData->examename = $responsesss->examename;
                                $attendanceData->subject = $responsesss->subject;
                                $attendanceData->markstatus = $markstatus;

                                $attendanceData = $attendanceData->toArray();

                                if (!array_key_exists($exam_name, $response)) {
                                    $response[$exam_name] = [];
                                    $response[$exam_name][$due_date . '|' . $section][] = $attendanceData;

                                }else{
                                    if (array_key_exists($due_date . '|' . $section, $response[$exam_name])) {

                                        $response[$exam_name][$due_date . '|' . $section][] = $attendanceData;
                                    } else {

                                        $response[$exam_name][$due_date . '|' . $section][] = $attendanceData;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $subjects = Subject::get();
        return view('admin.labExamMark.staff', compact('subjects', 'response'));
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

                $got_subject = Subject::where('id', $timetables->subject)
                    ->whereNotIn('subject_type_id', [1, 7, 13])->select('id', 'name')
                    ->first();

                if ($got_subject != null) {
                    $get_enroll = CourseEnrollMaster::where(['id' => $timetables->class_name])->first();
                    if ($get_enroll) {
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
                            $publishSubject = LabExamAttendance::where('status', 2)->where(['course' => $courseId, 'acyear' => $AcademicYearId, 'sem' => $newArray[3], 'section' => $newArray[4], 'subject' => $got_subject->id])->select('id')->first();
                            $class_name = $newArray[1] . '/' . $newArray[3] . '/' . $newArray[4];
                            // $class_name=$newArray[1].'/'.$newArray[3].'/'.$newArray[4];

                        } else {
                            $classname = '';
                        }
                        // $publish_check = LabExamAttendance::where('status', 2)

                        if ($publishSubject) {
                            $publishSubject->classname = $classname;
                            $publishSubject->subject_name = $got_subject->name;
                            $buttonss = '<a class="btn btn-xs btn-primary" href="' . route('admin.lab_Exam-Mark-Result.resultview', [$timetables->class_name, $timetables->subject]) . '" target="_blank">View</a>';
                            $publishSubject->button = $buttonss;
                            $response[] = $publishSubject;
                        }
                    }
                }
            }
        }
        // $subjects = Subject::get();
        return view('admin.labResult.index', compact('response', 'getAys'));
    }

    // public function resultview($classId, $subjectId, $pdf = '')
    // {

    //     if (isset($classId, $subjectId)) {
    //         $exam_data = [];
    //         $pass_count =0;
    //         $fail_count =0;
    //         $student = Student::where('enroll_master_id', $classId)->select('name', 'register_no', 'user_name_id')->get();
    //         $count = count($student);
    //         if ($student) {
    //             $si = 0;
    //             foreach ($student as $students) {

    //                 $get_enroll = CourseEnrollMaster::find($classId);

    //                 if ($get_enroll) {
    //                     if ($get_enroll != '') {
    //                         $newArray = explode('/', $get_enroll->enroll_master_number);
    //                         $get_course = ToolsCourse::where(['name' => $newArray[1]])->first();
    //                         if ($get_course) {
    //                             $students->classname = $get_course->short_form . '/' . $newArray[3] . '/' . $newArray[4];
    //                             $exam_data['classname']=$students->classname;
    //                         }
    //                         // $class_name=$newArray[1].'/'.$newArray[3].'/'.$newArray[4];

    //                     }
    //                 }
    //                 $exameData = LabExamAttendanceData::where([
    //                     'class_id' => $classId,
    //                     'subject' => $subjectId,
    //                     'student_id' => $students->user_name_id,
    //                 ])->get();

    //                 if ($exameData) {
    //                     $i =0;
    //                     foreach ($exameData as $id => $exameDatas) {

    //                         $exametable2 = LabExamAttendance::find($exameDatas->lab_exam_name);

    //                         if ($exametable2 && $exametable2->status == '2') {

    //                             $a = Subject::find($subjectId);
    //                             $attendenceData = LabExamAttendanceData::where('lab_exam_name', $exametable2->id)->get();
    //                             // ;
    //                             if ($a) {
    //                                 $students->subjectName = $a->name . '(' . $a->subject_code . ')';
    //                             $exam_data['subjectName']=$students->subjectName;

    //                             }

    //                             $co_mark = 100;

    //                             if ($exameDatas->cycle_mark != null) {
    //                             // $exam_data['co_mark']=$students->co_mark;
    //                             $exam_data ['student_name'][$students->name.'|'.$students->register_no] ['exam_name'][]= $exametable2->examename;

    //                             if($exameDatas->attendance =='Absent'){
    //                                 $exameDatas->cycle_mark = $exameDatas->attendance;
    //                                 $fail_count++;
    //                             }else if($exameDatas->cycle_mark < $co_mark / 2 ){
    //                                 $fail_count++;
    //                             }else{
    //                                 $pass_count++;
    //                             }
    //                             $exam_data['student_name'][$students->name.'|'.$students->register_no] ['cycle_mark'][]= $exameDatas->cycle_mark;
    //                             $exam_data['student_name'][$students->name.'|'.$students->register_no] ['lab_mark'][]= $co_mark ;
    //                             // $exam_data['student_name'][$students->name.'|'.$students->register_no]['pass_count'][]= $exametable2->pass_count;
    //                             // $exam_data['student_name'][$students->name.'|'.$students->register_no]['fail_count'][$i]= $exametable2->fail_count;
    //                             // $exam_data['student_name'][$students->name.'|'.$students->register_no]['pass_percentage'][$i]= $exametable2->pass_percentage;
    //                             // $exam_data['student_name'][$students->name.'|'.$students->register_no]['fail_percentage'][$i]= $exametable2->fail_percentage;
    //                             $exam_data[$exametable2->examename]['passpercentage']= $pass_count ;
    //                             $exam_data[$exametable2->examename]['failcount']= $fail_count ;

    //                                 // $exam_data[$exametable2->examename][$students->name.'|'.$students->register_no]['cycle_mark'] = $exameDatas->cycle_mark ?? 0;
    //                                 // $exam_data[$exametable2->examename][$students->name.'|'.$students->register_no]['co_mark'] = $exameDatas->co_mark ?? 0;
    //                                 $students->co_1 = $exameDatas->cycle_mark;
    //                                 $students->co_1Name = $exametable2->examename;
    //                                 $students->co_1Mark = $co_mark ?? 1;
    //                                 // $students->co_1Absent = 0;
    //                                 // $students->co_1Present = 0;
    //                                 $students->attendance = $exameDatas->attendance;
    //                                 // foreach ($attendenceData as $attendenceDatas) {

    //                                 //     if ($attendenceDatas->attendance == 'Absent') {
    //                                 //         $students->co_1Absent++;
    //                                 //     } else {
    //                                 //         $students->co_1Present++;
    //                                 //     }
    //                                 //         // $exam_data ['student_name'][$students->name.'|'.$students->register_no] ['cycle_mark'.$i]= $exameDatas->cycle_mark;

    //                                 //         // $exam_data[$exametable2->examename][]= $students->co_1Absent?? 0 ;
    //                                 //         // $exam_data[$exametable2->examename][]= $students->co_1Present?? 0;

    //                                 // }
    //                                 // $exam_data['co_1Absent'][0]=$students->co_1Absent;
    //                                 // $exam_data['co_1Present'][0]=$students->co_1Present;

    //                             }

    //                         }
    //                         $i=0;
    //                     }
    //                 }
    //                 $si++;
    //             }
    //         }
    //     }

    //     $count = 0;
    //     for ($i = 1; $i <= 5; $i++) {
    //         if (isset($student[0]->{'co_' . $i})) {
    //             $count++;
    //         }
    //     }
    //     if ($pdf != '') {

    //         $pdf = PDF::loadView('admin.labResult.Exam-result-StaffWise-reportPDF', ['student' => $student, 'count' => $count])->setOption('margin-top', 0)->setOption('margin-bottom', 0)->setOption('margin-left', 0)->setOption('margin-right', 0);;
    //         $pdf->setPaper('A4', 'portrait');
    //         return $pdf->stream('Exam-result-StaffWise-report.pdf');
    //     } else {

    //         return view('admin.labResult.view', compact('student', 'classId', 'subjectId', 'count'));
    //     }

    // }

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

            // $examNames = LabFirstmodel::where(['course_id' => $courseId, 'accademicYear' => $accId, 'semester' => $semId, 'section' => $Section])->whereNotNull('deleted_at')->get();
            $examNames = DB::table('lab_first_table')->where([
                'course_id' => $courseId, 'accademicYear' => $accId, 'semester' => $semId, 'section' => $Section
            ])->get();
            // dd($examNames);
            if (count($examNames) > 0) {
                foreach ($examNames as $examName) {

                    $exameAtt = LabExamAttendance::where(['status' => 2, 'course' => $courseId, 'subject' => $subjectId, 'acyear' => $accId, 'sem' => $semId, 'section' => $Section])->select('id', 'examename', 'lab_exam_id', 'subject', 'status', 'cycle_exam_mark', 'pass_count', 'fail_count', 'pass_percentage', 'fail_percentage', 'total_present', 'total_abscent')->get();
                    if (count($exameAtt) > 0) {
                        $values = [];
                        if (count($exameAtt) > 0) {
                            $g_total = 0;
                            $co = 1;
                            $si = 0;
                            $studentTotals = [];
                            $newdata[$si]['co_total'] = 0;
                            // $newdata[$si]['total'] =0;
                            foreach ($exameAtt as $exameatt => $examename) {
                                $exam_name = $examename->examename ? explode('/', $examename->examename)[0] : '';
                                $total = 0;
                                $total += $examename->pass_count + $examename->fail_count;
                                $subject_id = $examename->subject;
                                $Subject = Subject::find($subject_id);
                                $subject_name = $Subject->name;
                                $subject_code = $Subject->subject_code;
                                $newdata[$si]['subject_name'] = $subject_name;
                                $newdata[$si]['subject_code'] = $subject_code;
                                $newdata[$si]['class_name'] = $class_Name_short_form;
                                $newdata[$si]['department_name'] = $department_name;
                                $newdata[$si]['exam_title']["Exam_name$co"] = $exam_name;
                                $newdata[$si]['co_val']["Exam_name$co"] = $examename->cycle_exam_mark;
                                $newdata[$si]['co_total'] += $examename->cycle_exam_mark;
                                $newdata[$si]['pass'][$co] = $examename->pass_count;
                                $newdata[$si]['fail'][$co] = $examename->fail_count;
                                $newdata[$si]['pass_percentage'][$co] = $examename->pass_percentage;
                                $newdata[$si]['fail_percentage'][$co] = $examename->fail_percentage;
                                $newdata[$si]['total'][$co] = $total;
                                // $newdata[$si]['total_student'][$co] = $examename->total_abscent + $examename->total_present;

                                $examMark = LabExamAttendanceData::where(['class_id' => $CourseEnrollMaster->id, 'lab_exam_name' => $examename->id, 'subject' => $Subject->id])->get();
                                $exam_name = explode('/', $examename->examename)[0];
                                foreach ($examMark as $id => $value) {

                                    $studentID = $value->student_id;
                                    $student_data = Student::where('user_name_id', $studentID)->select('name', 'register_no')->first();
                                    $studentTotals[$studentID]['name'] = $student_data->name;
                                    $studentTotals[$studentID]['register_no'] = $student_data->register_no;

                                    if (!isset($studentTotals[$studentID])) {
                                        $studentTotals[$studentID]['status'] = [];
                                        $studentTotals[$studentID]['total'] = [];
                                        $studentTotals[$studentID]['name'] = [];
                                        $studentTotals[$studentID]['register_no'] = [];
                                    }

                                    if ($value->cycle_mark != null) {

                                        $studentTotals[$studentID]['status'][$co] = $value->cycle_mark;
                                        $studentTotals[$studentID]['total'][$co] = $value->cycle_mark;
                                    }
                                }
                                $co++;
                            }

                            $newdata[0]['student_details'] = $studentTotals;
                            $examMarks = $newdata;
                        }
                    } else {
                        $examMarks = [];
                        // $studentTotals = [];
                    }
                }
            } else {
                $examMarks = [];
            }
        } else {
            $examMarks = [];
        }

        if ($pdf != '') {

            $pdf = PDF::loadView('admin.labResult.Exam-result-StaffWise-reportPDF', ['examMarks' => $examMarks])->setOption('margin-top', 0)->setOption('margin-bottom', 0)->setOption('margin-left', 0)->setOption('margin-right', 0);
            $pdf->setPaper('A4', 'portrait');

            return $pdf->stream('Exam-result-StaffWise-report.pdf');
        } else {

            return view('admin.labResult.view', compact('examMarks', 'classId', 'subjectId'));
        }
    }

    public function lab_subject_get(Request $request)
    {

        if ($request->course_id != '' || $request->semester != '' || $request->accademicYear != '') {

            $get_sections = [];
            $check_input = is_numeric($request->course_id);
            $course = ToolsCourse::where('id', $request->course_id)->select('name')->first();
            $AcademicYears = AcademicYear::where('id', $request->accademicYear)->select('name')->first();
            $string = '/' . $course->name . '/' . $AcademicYears->name . '/' . $request->semester . '/';
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

            $count = Tools_Labmark_Title::count();
            $markTypes = LabFirstmodel::where(['accademicYear' => $request->accademicYear, 'course_id' => $request->course_id, 'semester' => $request->semester, 'semesterType' => $request->semesterType, 'year' => $request->year])->select('MarkType')->distinct()->get();
            if ($count > count($markTypes) && count($markTypes) > 0) {

                $Exam_name = Tools_Labmark_Title::whereNotIn('name', $markTypes)->select('name')->get();
            } else if ($count != count($markTypes) && count($markTypes) == 0) {
                $Exam_name = Tools_Labmark_Title::whereNotIn('name', $markTypes)->select('name')->get();
            } else {
                $Exam_name = [];
            }

            $get_subjects = SubjectAllotment::where(['semester' => $request->semester, 'course' => $request->course_id, 'academic_year' => $request->accademicYear, 'semester_type' => $request->semesterType])->get();
            $subjects = [];
            $got_subject = [];
            if (count($get_subjects) > 0) {
                foreach ($get_subjects as $subject) {
                    if (isset($request->labExam)) {
                        $got_subject = Subject::where('id', $subject->subject_id)
                            ->whereNotIn('subject_type_id', [1, 7, 13])
                            ->first();

                        if ($got_subject != '') {
                            array_push($subjects, $got_subject);
                        }
                    }
                }
            }
            if (count($get_sections) > 0 && count($subjects) > 0 && count($Exam_name) > 0) {
                return response()->json(['subjects' => $subjects, 'get_section' => $get_sections, 'examName' => $Exam_name]);
            } else if (count($get_sections) > 0 && count($Exam_name) > 0) {
                return response()->json(['subjects' => [], 'get_section' => $get_sections, 'examName' => $Exam_name]);
            } else if (count($get_sections) > 0 && count($subjects) > 0) {
                return response()->json(['subjects' => $subjects, 'get_section' => $get_sections, 'examName' => []]);
            } else {
                return response()->json(['status' => 'fail', 'subjects' => [], 'get_section' => [], 'examName' => []]);
            }
        } else {
            return response()->json(['status' => 'fail', 'subjects' => [], 'get_section' => [], 'examName' => []]);
        }
    }

    public function lab_subject_get_edit(Request $request)
    {

        if ($request->course_id != '' || $request->semester != '' || $request->accademicYear != '') {

            // $get_section = [];
            // $check_input = is_numeric($request->course_id);
            // $check_input = is_numeric($request->course_id);
            $course = ToolsCourse::where('id', $request->course_id)->select('name')->first();
            $AcademicYears = AcademicYear::where('id', $request->accademicYear)->select('name')->first();
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
                        $get_section = Section::where(['course_id' => $request->course_id, 'section' => $parts])->select('id', 'section')->get();
                        $get_sections[$i] = $get_section;
                    }
                    $i++;
                }
            }
            $get_subjects = [];
            $get_subjects = SubjectAllotment::where(['semester' => $request->semester, 'course' => $request->course_id, 'academic_year' => $request->accademicYear, 'semester_type' => $request->semesterType])->get();

            $got_subject = [];
            if (count($get_subjects) > 0) {
                $subjects = [];
                $got_subject = [];
                $subjectsNew = [];
                $status = '';
                $count = Tools_Labmark_Title::count();
                if ($request->old_accademicYear == $request->accademicYear && $request->old_year == $request->year && $request->old_course_id == $request->course_id && $request->old_semester == $request->semester && $request->old_section == $request->section && $request->old_year == $request->year) {
                    $markTypes = LabFirstmodel::where(['accademicYear' => $request->accademicYear, 'course_id' => $request->course_id, 'semester' => $request->semester, 'semesterType' => $request->semesterType, 'section' => $request->section, 'year' => $request->year])->where('id', '!=', $request->id)->select('MarkType')->get();
                    $subject_get = LabFirstmodel::where(['accademicYear' => $request->accademicYear, 'course_id' => $request->course_id, 'semester' => $request->semester, 'semesterType' => $request->semesterType, 'section' => $request->section])->where(['id' => $request->id, 'MarkType' => $request->markType])->select('subject', 'due_date')->first();
                    // $due_date = $subject_get->due_date ?? '';
                } else {
                    $markTypes = LabFirstmodel::where(['accademicYear' => $request->accademicYear, 'course_id' => $request->course_id, 'semester' => $request->semester, 'semesterType' => $request->semesterType, 'section' => $request->section, 'year' => $request->year])->select('MarkType')->get();
                }

                if ($count > count($markTypes) && count($markTypes) > 0) {

                    $Exam_name = Tools_Labmark_Title::whereNotIn('name', $markTypes)->select('name')->get();
                    // $Exam_name['date'] = $due_date;
                } else if ($count != count($markTypes) && count($markTypes) == 0) {
                    $Exam_name = Tools_Labmark_Title::whereNotIn('name', $markTypes)->select('name')->get();
                    // $Exam_name['date'] = $due_date;
                } else {
                    $Exam_name = [];
                    $status = 'Already Done';
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
                                // array_push($present_subject_ids, $data);
                                $got_subject->date = $matches;
                                $got_subject->due_date = $subject_get->due_date;
                                array_push($subjects, $got_subject);
                                // $subjectsNew[$si]['date'] =  $matches;
                                // $subjectsNew[$si]['due_date'] =  $subject_get->due_date;
                                // array_push($subjectsNew, $got_subject);
                            }
                            // $si++;
                        }
                    }

                    // $Exam_name->newsubject = $subjectsNew;
                } else {

                    $subject_get = LabFirstmodel::where(['accademicYear' => $request->accademicYear, 'course_id' => $request->course_id, 'semester' => $request->semester, 'semesterType' => $request->semesterType, 'section' => $request->section])->where(['section' => $request->section, 'MarkType' => $request->markType])->select('subject')->first();
                    foreach ($get_subjects as $subject) {

                        $got_subject = Subject::where('id', $subject->subject_id)
                            ->whereNotIn('subject_type_id', [1, 7, 13])
                            ->first();
                        if ($got_subject != '') {
                            $got_subject->date = '';
                            array_push($subjects, $got_subject);
                        }
                    }
                    // $Exam_name->newsubject = $subjectsNew;
                }
            }

            // $subjects = $subjects->toArray();
            if ($get_sections != '' && $subjects != '' && isset($Exam_name) && isset($status)) {
                return response()->json(['subjects' => $subjects, 'get_section' => $get_sections, 'examName' => $Exam_name, 'exam_status' => $status]);
            } elseif ($get_sections != '' && $subjects != '') {
                return response()->json(['subjects' => $subjects, 'get_section' => $get_sections]);
            } else if ($subjects != '') {

                return response()->json(['subjects' => $subjects]);
            } else if ($get_sections != '') {
                return response()->json(['get_section' => $get_sections]);
            } else {

                return response()->json(['status' => 'fail']);
            }
        } else {
            return response()->json(['status' => 'fail']);
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

                    $got_subject = Subject::where('id', $timetables->subject)
                        ->whereNotIn('subject_type_id', [1, 7, 13])->select('id', 'name', 'subject_code')
                        ->first();

                    if ($got_subject != null) {
                        $get_enroll = CourseEnrollMaster::where(['id' => $timetables->class_name])->first();
                        if ($get_enroll) {
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
                                $publishSubject = LabExamAttendance::where('status', 2)->where(['course' => $courseId, 'acyear' => $AcademicYearId, 'sem' => $newArray[3], 'section' => $newArray[4], 'subject' => $got_subject->id])->select('id')->first();
                                $class_name = $newArray[1] . '/' . $newArray[3] . '/' . $newArray[4];
                                // $class_name=$newArray[1].'/'.$newArray[3].'/'.$newArray[4];

                            } else {
                                $classname = '';
                            }
                            // $publish_check = LabExamAttendance::where('status', 2)

                            if ($publishSubject) {
                                $publishSubject->classname = $classname;
                                $publishSubject->subject_name = $got_subject->name . ' (' . $got_subject->subject_code . ')';
                                $buttonss = '<a class="btn btn-xs btn-primary" href="' . route('admin.lab_Exam-Mark-Result.resultview', [$timetables->class_name, $timetables->subject]) . '" target="_blank">View</a>';
                                $publishSubject->button = $buttonss;
                                $response[] = $publishSubject;
                            }
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
