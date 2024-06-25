<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ClassRoom;
use App\Models\CollegeBlock;
use App\Models\Course;
use App\Models\CourseEnrollMaster;
use App\Models\Examattendance;
use App\Models\ExamattendanceData;
use App\Models\ExamTimetableCreation;
use App\Models\NonTeachingStaff;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentPromotionHistory;
use App\Models\Subject;
use App\Models\SubjectAllotment;
use App\Models\TeachingStaff;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class ExamTimetableCreationController extends Controller
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
    public function toRoman($number)
    {
        $map = [
            1000 => 'M',
            900 => 'CM',
            500 => 'D',
            400 => 'CD',
            100 => 'C',
            90 => 'XC',
            50 => 'L',
            40 => 'XL',
            10 => 'X',
            9 => 'IX',
            5 => 'V',
            4 => 'IV',
            1 => 'I',
        ];

        $roman = '';
        foreach ($map as $value => $symbol) {
            while ($number >= $value) {
                $roman .= $symbol;
                $number -= $value;
            }
        }
        return $roman;
    }
    public function index(Request $request)
    {
        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $AcademicYear = AcademicYear::pluck('name', 'id');
        $examNames = ExamTimetableCreation::select('exam_name')->distinct()->get();

        if ($request->ajax()) {
            $exam = [];
            $courses = ToolsCourse::pluck('name', 'id');
            $semester = Semester::pluck('semester', 'id');
            $AcademicYear = AcademicYear::pluck('name', 'id');
            $getAys = AcademicYear::where(['status' => 1])->select('id')->get();
            $Ays = [];
            if (count($getAys) > 0) {
                foreach ($getAys as $ay) {
                    array_push($Ays, $ay->id);
                }
            }
            $getSem = Semester::where(['status' => 1])->select('semester')->get();
            $Sems = [];
            if (count($getSem) > 0) {
                foreach ($getSem as $sem) {
                    array_push($Sems, $sem->semester);
                }
            }

            $examNames = ExamTimetableCreation::whereIn('accademicYear', $Ays)
                ->whereIn('semester', $Sems)
                ->select('id', 'exam_name', 'course', 'accademicYear', 'sections', 'semester', 'year', 'date')
                ->distinct()
                ->get();
            foreach ($examNames as $examName) {

                $course = ToolsCourse::where('id', $examName->course)->select('name')->first();
                $AcademicYears = AcademicYear::where('id', $examName->accademicYear)->select('name')->first();
                $string = '/' . $course->name . '/' . $AcademicYears->name . '/' . $examName->semester . '/' . $examName->sections;
                $stu = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%' . $string)->first();
                if ($stu != null) {
                    $students = Student::where('enroll_master_id', $stu->id)->count();
                    if ($students <= 0) {
                        $students = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $stu->id)->count();
                    }
                    if ($students > 0) {
                        $exam[] = $examName;
                    } else {
                        ExamTimetableCreation::where('id', $examName->id)->update(['deleted_at' => now()]);
                    }
                }
            }
            $table = Datatables::of($exam);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'exam_shedule_view';
                $editGate = 'examTimetable_edit';
                $deleteGate = 'examTimetable_delete';
                $crudRoutePart = 'examTimetable';

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
                // dd($row);
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
            $table->editColumn('course', function ($row) {
                if ($row->course != '') {
                    $course = ToolsCourse::find($row->course);
                    if ($course) {
                        $outputcourse = $course->short_form;
                    } else {
                        $outputcourse = '';
                    }
                }
                return $row->course ? $outputcourse : '';
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

                    $result = $this->toRoman($row->year);
                }
                return $row->year ? $result : '';
            });

            $table->editColumn('date', function ($row) {
                return $row->date ? $row->date : '';
            });
            $table->editColumn('start_time', function ($row) {
                if ($row->date) {
                    if ($this->is_serialized($row->date)) {
                        $dummy = unserialize($row->date);

                        // Check if $dummy is an array or object
                        if (is_array($dummy) || is_object($dummy)) {
                            $dates = [];

                            foreach ($dummy as $dummy1) {
                                foreach ($dummy1 as $dummy2) {
                                    array_push($dates, $dummy2);
                                }
                            }

                            $timestamps = array_map(function ($dateString) {
                                return strtotime($dateString);
                            }, $dates);

                            // Find the first date (minimum timestamp)
                            $firstDateTimestamp = min($timestamps);
                            $firstDate = date('Y-m-d', $firstDateTimestamp);

                            return $firstDate;
                        } else {
                            // Handle invalid or unserialized data
                            return 'Invalid Data';
                        }
                    }
                }

                return '';
            });

            $table->editColumn('end_time', function ($row) {
                if ($row->date) {
                    if ($this->is_serialized($row->date)) {
                        $dummy = unserialize($row->date);

                        // Check if $dummy is an array or object
                        if (is_array($dummy) || is_object($dummy)) {
                            $dates = [];

                            foreach ($dummy as $dummy1) {
                                foreach ($dummy1 as $dummy2) {
                                    array_push($dates, $dummy2);
                                }
                            }

                            $timestamps = array_map(function ($dateString) {
                                return strtotime($dateString);
                            }, $dates);

                            // Find the last date (maximum timestamp)
                            $lastDateTimestamp = max($timestamps);
                            $lastDate = date('Y-m-d', $lastDateTimestamp);

                            return $lastDate;
                        } else {
                            // Handle invalid or unserialized data
                            return 'Invalid Data';
                        }
                    }
                }

                return '';
            });

            $table->editColumn('subject', function ($row) {

                if ($row->subject != '') {
                    $subject = Subject::find($row->subject);
                    if ($subject) {
                        $subjectData = $subject->name . '(' . $subject->subject_code . ')';
                    } else {
                        $subjectData = '';
                    }
                }
                return $row->subject ? $subjectData : '';
            });
            $table->editColumn('sections', function ($row) {
                // if ($row->sections) {
                //     if ($this->is_serialized($row->sections)) {
                //         return unserialize($row->sections);
                //     } else {
                //         // Handle invalid or unserialized data
                //         return 'Invalid Data';
                //     }
                // }
                return $row->sections ? $row->sections : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.Exam_timetable.index', compact('courses', 'semester', 'AcademicYear', 'examNames'));
    }

    public function create()
    {
        $departments = ToolsDepartment::pluck('name', 'id');
        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $Subjects = Subject::get();
        $blocks = CollegeBlock::pluck('name', 'id');
        $classrooms = ClassRoom::pluck('name', 'id');
        $AcademicYear = AcademicYear::pluck('name', 'id');
        // $Section = Section::select('id', 'section')->groupBy('id', 'section')->get();
        return view('admin.Exam_timetable.create', compact('courses', 'departments', 'semester', 'Subjects', 'blocks', 'classrooms', 'AcademicYear'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'accademicYear' => 'required',
            'semesterType' => 'required',
            // 'exameType' => 'required',
            'examName' => 'required',
            'year' => 'required',
            'course_id' => 'required',
            'semester' => 'required',
            'modeofExam' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'co_mark' => 'required',
            'subject' => 'required',
            'sections' => 'required|array',
        ]);

        $co_mark = json_decode($request->input('co_mark'), true);
        $subjects = json_decode($request->input('subject'), true);
        $sections = $request->input('sections');

        if (!$co_mark || !$subjects || !$sections) {
            // Handle the case when 'co_mark', 'subject', or 'sections' are missing or invalid
            return response()->json(['message' => 'Invalid or incomplete data.'], 400);
        }

        $serializedSubject = '';
        $serializedCo_mark = '';

        foreach ($sections as $section) {
            foreach ($subjects as $index => $subjectData) {
                if (!empty($subjectData)) {
                    $serializedSubject = serialize($subjects);
                    $serializedCo_mark = serialize($co_mark);
                } else {
                    $serializedSubject = '';
                    $serializedCo_mark = '';
                }
            }

            DB::transaction(function () use ($request, $co_mark, $subjects, $serializedSubject, $serializedCo_mark, $section) {
                // Create a new ExamTimetableCreation record
                $newExamTimetable = ExamTimetableCreation::create([
                    'course' => $request->input('course_id'),
                    'semester' => $request->input('semester'),
                    'subject' => $serializedSubject,
                    'exam_name' => $request->input('examName') . '/' . $this->toRoman($request->input('year')) . '/0' . $request->input('semester'),
                    'date' => $serializedSubject,
                    'start_time' => $request->input('start_time'),
                    'end_time' => $request->input('end_time'),
                    'accademicYear' => $request->input('accademicYear'),
                    'semesterType' => $request->input('semesterType'),
                    'year' => $request->input('year'),
                    'sections' => $section,
                    'modeofExam' => $request->input('modeofExam'),
                    'co' => $serializedCo_mark,
                ]);

                // Create related Examattendance records
                foreach ($subjects as $subjectData) {
                    foreach ($subjectData as $ids => $subject) {
                        Examattendance::create([
                            'exame_id' => $newExamTimetable->id,
                            'course' => $newExamTimetable->course,
                            'date' => $subject,
                            'subject' => $ids,
                            'acyear' => $newExamTimetable->accademicYear,
                            'examename' => $newExamTimetable->exam_name,
                            'sem' => $newExamTimetable->semester,
                            'year' => $newExamTimetable->year,
                            'section' => $newExamTimetable->sections,
                            // 'co' => array_key_last($co_mark),
                            'co_mark' => $serializedCo_mark,
                        ]);
                    }
                }
            });
        }

        return response()->json(['message' => 'Exam timetable(s) successfully processed.']);
    }

    public function show(ExamTimetableCreation $examTimetableCreation)
    {

        $departments = ToolsDepartment::pluck('name', 'id');
        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $AcademicYear = AcademicYear::pluck('name', 'id');

        return view('admin.Exam_timetable.show', compact('departments', 'courses', 'semester', 'AcademicYear'));
    }

    public function edit($id)
    {
        // dd($id);

        $departments = ToolsDepartment::pluck('name', 'id');
        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $Subjects = Subject::get();
        $blocks = CollegeBlock::pluck('name', 'id');
        $classrooms = ClassRoom::pluck('name', 'id');
        $AcademicYear = AcademicYear::pluck('name', 'id');

        $examTimetable = ExamTimetableCreation::find($id);
        $semesters = $examTimetable->semester;
        $coures = $examTimetable->course;
        $accademicYears = $examTimetable->accademicYear;
        $semesterTypes = $examTimetable->semesterType;
        $sections = $examTimetable->sections;

        $get_subjects = [];
        $get_subjects = SubjectAllotment::where(['semester' => $semesters, 'course' => $coures, 'academic_year' => $accademicYears, 'semester_type' => $semesterTypes])->get();

        $subjects = [];
        $got_subject = [];
        if (count($get_subjects) > 0) {
            foreach ($get_subjects as $subject) {
                $got_subject = Subject::where(['id' => $subject->subject_id])->first();
                if ($got_subject != '') {
                    array_push($subjects, $got_subject);
                }
            }
            // return response()->json(['subjects' => $subjects]);
        }

        $subject_id = [];
        foreach ($subjects as $subject) {

            $subject_ids = $subject->id;
            array_push($subject_id, $subject_ids);
        }

        $subjectsNew = [];
        foreach ($subject_id as $subject_ids) {

            $subject = Subject::find($subject_ids);
            $subjectsNews = [
                'name' => $subject->name, // Store subject name
                'date' => null, // Store date
                'code' => $subject->subject_code,
                'id' => $subject->id,
            ];
            array_push($subjectsNew, $subjectsNews);
        }

        if ($examTimetable) {

            $subjectData = unserialize($examTimetable->subject);

            $present_subject_ids = [];

            if ($subjectData !== false && is_array($subjectData)) {

                foreach ($subjectData as $match) {

                    foreach ($match as $data => $matches) {

                        array_push($present_subject_ids, $data);
                        for ($i = 0; $i < count($subjectsNew); $i++) {

                            if ($subjectsNew[$i]['id'] == $data) {
                                $subjectsNew[$i]['date'] = $matches;
                            }
                        }
                    }
                }
            }
            // dd($subjectsNew);

            $examTimetable->newsubject = $subjectsNew;

            $jsonString = unserialize($examTimetable->co);

            if ($jsonString !== false && is_array($jsonString)) {
                $examTimetable->co_1 = $jsonString['CO-1'] ?? null;
                $examTimetable->co_2 = $jsonString['CO-2'] ?? null;
                $examTimetable->co_3 = $jsonString['CO-3'] ?? null;
                $examTimetable->co_4 = $jsonString['CO-4'] ?? null;
                $examTimetable->co_5 = $jsonString['CO-5'] ?? null;
            }
        }

        return view('admin.Exam_timetable.edit', compact('examTimetable', 'courses', 'departments', 'semester', 'Subjects', 'blocks', 'classrooms', 'AcademicYear', 'present_subject_ids'));
    }
    public function view($id)
    {
        // dd($id);
        $departments = ToolsDepartment::pluck('name', 'id');
        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $Subjects = Subject::get();
        $blocks = CollegeBlock::pluck('name', 'id');
        $classrooms = ClassRoom::pluck('name', 'id');
        $AcademicYear = AcademicYear::pluck('name', 'id');

        $examTimetable = ExamTimetableCreation::find($id);

        if ($examTimetable) {
            // Extract subject data
            $subjectData = unserialize($examTimetable->subject);
            $subjectsNew = [];
            // dd($subjectData);
            if ($subjectData !== false && is_array($subjectData)) {
                foreach ($subjectData as $match) {
                    foreach ($match as $data => $matches) {

                        // dd($data);
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

            // if (preg_match_all('/s:(\d+):"([^"]+)";/', $subjectData, $matches, PREG_SET_ORDER)) {

            // }

            $examTimetable->newsubject = $subjectsNew;

            $jsonString = unserialize($examTimetable->co);

            if ($jsonString !== false && is_array($jsonString)) {
                $examTimetable->co_1 = $jsonString['CO-1'] ?? null;
                $examTimetable->co_2 = $jsonString['CO-2'] ?? null;
                $examTimetable->co_3 = $jsonString['CO-3'] ?? null;
                $examTimetable->co_4 = $jsonString['CO-4'] ?? null;
                $examTimetable->co_5 = $jsonString['CO-5'] ?? null;
            }
        }

        return view('admin.Exam_timetable.view', compact('examTimetable', 'courses', 'departments', 'semester', 'Subjects', 'blocks', 'classrooms', 'AcademicYear'));
        // return view('admin.Exam_timetable.view', compact('examTimetable', 'courses', 'departments', 'semester', 'Subjects', 'blocks', 'classrooms','AcademicYear'));
    }

    public function update(Request $request, ExamTimetableCreation $examTimetableCreation, $id)
    {
        $request->validate([
            'accademicYear' => 'required',
            'semesterType' => 'required',
            'examName' => 'required',
            'year' => 'required',
            'course_id' => 'required',
            'semester' => 'required',
            'modeofExam' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'co_mark' => 'required',
            'subject' => 'required',
            'sections' => 'required',
        ]);

        $co_mark = json_decode($request->input('co_mark'), true);
        $subjects = json_decode($request->input('subject'), true);
        $sections = $request->input('sections');

        if (!$co_mark || !$subjects || !$sections) {
            return response()->json(['message' => 'Invalid or incomplete data.'], 400);
        }

        $coData = $request->input('hidden');
        $comarks = serialize($co_mark);
        $subjectsss = serialize($subjects);

        $examTimetableCreation = ExamTimetableCreation::find($id);

        if (!$examTimetableCreation) {
            return redirect()->route('admin.Exam-time-table.index')->with('error', 'Exam timetable not found.');
        }

        $examTimetableCreation->update([
            'subject' => $subjectsss,
            'exam_name' => $request->input('examName'),
            'date' => $subjectsss,
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'semesterType' => $request->input('semesterType'),
            'modeofExam' => $request->input('modeofExam'),
            'co' => $comarks,
        ]);

        $attData = Examattendance::where('exame_id', $examTimetableCreation->id)->get();
        $subjectTotal = [];

        foreach ($subjects as $subjectData) {
            foreach ($subjectData as $ids => $subject) {
                array_push($subjectTotal, $ids);
            }
        }

        if ($attData) {
            foreach ($attData as $attDatas) {
                if (!in_array($attDatas->subject, $subjectTotal)) {
                    $attDatas->delete();
                }
            }
        }

        // foreach ($subjects as $subjectData) {
        //     foreach ($subjectData as $ids => $subject) {
        //         $existingSubject = Examattendance::where('exame_id', $examTimetableCreation->id)
        //             ->where('subject', $ids)
        //             ->first();

        //         if (!$existingSubject) {
        //             Examattendance::create([
        //                 'exame_id' => $examTimetableCreation->id,

        //                 'subject' => $ids,

        //                 'co' => array_key_last($co_mark),
        //                 'co_mark' => end($co_mark),
        //             ]);
        //         }
        //     }
        // }
        foreach ($subjects as $subjectData) {
            foreach ($subjectData as $ids => $subject) {
                Examattendance::updateOrCreate(
                    [
                        'exame_id' => $examTimetableCreation->id,
                        'subject' => $ids,
                    ],
                    [
                        'course' => $request->input('course_id'),

                        'date' => $subject,
                        'acyear' => $request->input('accademicYear'),
                        'examename' => $request->input('examName'),
                        'sem' => $request->input('semester'),
                        'year' => $request->input('year'),

                        'section' => $request->input('sections'),
                        'co' => array_key_last($co_mark),
                        'co_mark' => end($co_mark),
                    ]
                );
            }
        }

        $data = 'Exam timetable successfully edited.';
        return redirect()->route('admin.Exam-time-table.index')->with('success', $data);
    }

    public function destroy($examTimetableCreation)
    {
        $delete = ExamTimetableCreation::find($examTimetableCreation);

        if ($delete) {
            $datas = Examattendance::where('exame_id', $examTimetableCreation)->get();
            if ($datas) {
                foreach ($datas as $data) {
                    $ExamattendanceData = ExamattendanceData::where('examename', $data->id)->get();
                    if ($ExamattendanceData) {
                        foreach ($ExamattendanceData as $dataaa) {
                            $dataaa->delete();
                        }
                    }
                    $data->delete();
                }
            }

            $delete->delete();
            return redirect()->route('admin.Exam-time-table.index')->with('success', 'Exam timetable successfully deleted.');
        } else {
            return redirect()->route('admin.Exam-time-table.index')->with('error', 'Exam timetable not found.');
        }
    }

    public function Check(Request $request)
    {
        $response = '';
        $jsonData = '';

        if ($request->sem != '' && $request->course != '' && $request->accademicYear != '') {
            if (!is_null($request->boxValues)) {
                $boxValues = str_split($request->boxValues);
                $response = ExamTimetableCreation::where([
                    'semester' => $request->sem,
                    'course' => $request->course,
                    'accademicYear' => $request->accademicYear,
                ])->whereIn('sections',$boxValues)->get()->unique('co');
            } else {
                $response = ExamTimetableCreation::where([
                    'semester' => $request->sem,
                    'course' => $request->course,
                    'accademicYear' => $request->accademicYear,
                ])->get()->unique('co');
            }
            // dd($response);
            $storage = [];

            if ($response->isNotEmpty()) {
                foreach ($response as $responses) {
                    // Check if $responses->co is not empty before unserializing
                    if ($responses->co) {
                        $unserializedData = unserialize($responses->co);
                        // Merge the unserialized data into $storage
                        $storage = array_merge($storage, $unserializedData);
                    }
                }
                // Convert $storage to JSON if it's not empty
                if (!empty($storage)) {
                    $jsonData = json_encode($storage);

                    $firstRecord = $response->first();
                    $firstRecord->serializeCo = $jsonData;
                    $response = $firstRecord;
                }
            } else {
                $response = 'No matching records found';
            }
        } else {
            $response = 'Fill in the data';
        }
        // dd($firstRecord);
        return response()->json(['data' => $response]);
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
        $examNames = ExamTimetableCreation::select('exam_name')->distinct()->get();

        // Initialize an empty array to store Examattendance records
        $exameAtt = [];
        $store = [];
        $currentClasses = Session::get('currentClasses');
        // Retrieve Examattendance records with eager loading and chunking
        Examattendance::with([
            'courseEnrollMaster',
            'academicYear',
            'semester',
            'teachingStaff',
            'nonTeachingStaff',
            'user',
            'subject',
        ])->chunk(50, function ($exameAttChunk) use ($role_id, &$exameAtt, $currentClasses) {
            foreach ($exameAttChunk as $record) {
                // Initialize totalstudent to 0
                $record->totalstudent = 0;

                // Build the search string for CourseEnrollMaster
                $courseName = optional($record->courseEnrollMaster)->name;
                $courseShortForm = optional($record->courseEnrollMaster)->short_form;
                $academicYearName = optional($record->academicYear)->name;
                $semesterName = optional($record->semester)->semester;
                $string = '/' . $courseName . '/' . $academicYearName . '/' . $semesterName . '/' . $record->section;

                // Find the CourseEnrollMaster record
                $stu = CourseEnrollMaster::whereIn('id', $currentClasses)->where('enroll_master_number', 'LIKE', '%' . $string)->select('id')->first();

                // If a CourseEnrollMaster record is found, count students
                if ($stu != '') {
                    $students = Student::where('enroll_master_id', $stu->id)->count();
                    if ($students <= 0) {
                        $students = StudentPromotionHistory::join('students', 'students.user_name_id', '=', 'student_promotion_history.user_name_id')->where('student_promotion_history.enroll_master_id', $stu->id)->count();
                    }
                    if ($students > 0) {
                        $record->totalstudent = $students;
                    } else {
                        $record->totalstudent = null;
                    }

                    $record->class = $courseShortForm . '/' . $semesterName . '/' . $record->section;

                    if ($record->totalstudent != null) {
                        // Determine attenteredBY value
                        $attenteredBY = optional($record->teachingStaff)->StaffCode ?: (optional($record->nonTeachingStaff)->StaffCode ?: (optional($record->user)->name ?: ''));
                        $record->attenteredBY = $attenteredBY;

                        // Build action buttons based on att_entered and user's role
                        $buttons = '';
                        if ($record->att_entered == 'Yes') {
                            $buttons .= '<a class="btn btn-xs btn-primary" href="' . route('admin.exam_attendance.viewattendance', [$stu->id, $record->id]) . '" target="_blank">View</a>';
                        } else {
                            // dd($record->att_entered);

                            $buttons .= '<a class="btn btn-xs btn-info" href="' . route('admin.exam_attendance.attendanceEnter', [$stu->id, $record->id]) . '" target="_blank">Enter</a>';
                        }

                        if (($role_id == 40 || $role_id == 1) && $record->att_entered == 'Yes') {
                            $buttons .= ' <a class="btn btn-xs btn-danger" href="' . route('admin.exam_attendance.editattendance', [$stu->id, $record->id]) . '" target="_blank">Edit</a>';
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
            }
        });
        // dd($exameAtt);

        return view('admin.Exam_attendance.index', compact('courses', 'semester', 'Subjects', 'AcademicYear', 'examNames', 'exameAtt'));
    }

    // function  lab_subject_get_edit(Request $request)
    // {

    //     $academicYear_id = $request->academicYear_id;
    //     $course_id = $request->course_id;
    //     $semester_id = $request->semester_id;
    //     $section = $request->section;

    //     if ($request->course_id != '' || $request->semester != '' || $request->accademicYear != '') {

    //         // $get_section = [];
    //         // $check_input = is_numeric($request->course_id);
    //         // $check_input = is_numeric($request->course_id);
    //         $course = ToolsCourse::where('id', $request->course_id)->select('name')->first();
    //         $AcademicYears = AcademicYear::where('id', $request->accademicYear)->select('name')->first();
    //         $string = '/' . $course->name . '/' . $AcademicYears->name . '/' . $request->semester . '/';
    //         $stu = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%' . $string . '%')->get();
    //         if (count($stu) > 0) {

    //             $get_sections = [];
    //             $i = 0;
    //             foreach ($stu as $sec) {
    //                 $parts = explode('/', $sec->enroll_master_number)[4];
    //                 $students = Student::where('enroll_master_id', $sec->id)->count();
    //                 if ($students > 0) {
    //                     $sec->enroll_master_number;
    //                     $get_section = Section::where(['course_id' => $request->course_id, 'section' => $parts])->select('id', 'section')->get();
    //                     $get_sections[$i] = $get_section;

    //                 }
    //                 $i++;
    //             }
    //         }
    //         $get_subjects = [];
    //         $get_subjects = SubjectAllotment::where(['semester' => $request->semester, 'course' => $request->course_id, 'academic_year' => $request->accademicYear, 'semester_type' => $request->semesterType])->get();

    //         $got_subject = [];
    //         if (count($get_subjects) > 0) {
    //             $subjects = [];
    //             $got_subject = [];
    //             $subjectsNew = [];
    //             $status = '';

    //             if (isset($request->labExam)) {

    //                 $count = Tools_Labmark_Title::count();
    //                 if (!isset($request->select_section)) {
    //                     $markTypes =  LabFirstmodel::where(['accademicYear' => $request->accademicYear, 'course_id' => $request->course_id, 'semester' => $request->semester, 'semesterType' => $request->semesterType])->select('MarkType', 'subject')->get();
    //                     $subject_get =[];

    //                 } else {
    //                     if($request->old_accademicYear == $request->accademicYear && $request->old_year == $request->year && $request->old_course_id == $request->course_id && $request->old_semester == $request->semester && $request->old_section == $request->section && $request->old_year == $request->year ){
    //                     $markTypes =  LabFirstmodel::where(['accademicYear' => $request->accademicYear, 'course_id' => $request->course_id, 'semester' => $request->semester, 'semesterType' => $request->semesterType, 'section' => $request->section,'year'=> $request->year])->where('id', '!=', $request->id)->select('id', 'MarkType', 'subject','due_date')->get();
    //                     $subject_get =  LabFirstmodel::where(['accademicYear' => $request->accademicYear, 'course_id' => $request->course_id, 'semester' => $request->semester, 'semesterType' => $request->semesterType, 'section' => $request->section])->where(['id'=>$request->id,'MarkType'=>$request->markType])->select('subject','due_date')->first();
    //                     // $due_date = $subject_get->due_date ?? '';
    //                     }else{
    //                     $markTypes =  LabFirstmodel::where(['accademicYear' => $request->accademicYear, 'course_id' => $request->course_id, 'semester' => $request->semester, 'semesterType' => $request->semesterType, 'section' => $request->section,'year'=> $request -> year,'markType'=>$request->markType])->select('id', 'MarkType', 'subject','due_date')->first();
    //                     if( $markTypes != ''){
    //                         $condition = true;
    //                         $subject_get= '';
    //                         // $due_date='';
    //                     }else{
    //                     $subject_get = '';
    //                     $markTypes = [];
    //                     // $due_date='';

    //                     }

    //                     }
    //                 }

    //                 if ($count > count($markTypes) && count($markTypes) > 0 && !isset($condition)) {

    //                     $Exam_name  = Tools_Labmark_Title::whereNotIn('name', $markTypes)->select('name')->get();
    //                     // $Exam_name['date'] = $due_date;
    //                 } else if ($count != count($markTypes) && count($markTypes) == 0 && !isset($condition)) {
    //                     $Exam_name  = Tools_Labmark_Title::whereNotIn('name', $markTypes)->select('name')->get();
    //                     // $Exam_name['date'] = $due_date;
    //                 } else if(isset($condition)){
    //                     $Exam_name = [];
    //                     $status = 'Already Done';
    //                 }else{
    //                     $Exam_name = [];
    //                 }

    //                 if ($subject_get != '' && !isset($condition)) {

    //                     $subjectData = unserialize($subject_get->subject);
    //                     $present_subject_ids = [];
    //                     if ($subjectData !== false && is_array($subjectData)) {
    //                         $si = 0;
    //                         foreach ($subjectData as $match) {
    //                             foreach ($match as $data => $matches) {
    //                                 $got_subject = Subject::where('id', $data)
    //                                     ->select('id', 'name', 'subject_code')
    //                                     ->first();
    //                                 // array_push($present_subject_ids, $data);
    //                                 array_push($subjects, $got_subject);
    //                                 array_push($subjectsNew, $got_subject);
    //                                 $subjectsNew[$si]['date'] =  $matches;
    //                             }
    //                             $si++;
    //                         }
    //                     }

    //                     $Exam_name->newsubject = $subjectsNew;
    //                 } else {

    //                     $subject_get =  LabFirstmodel::where(['accademicYear' => $request->accademicYear, 'course_id' => $request->course_id, 'semester' => $request->semester, 'semesterType' => $request->semesterType, 'section' => $request->section])->where(['section'=>$request->section,'MarkType'=>$request->markType])->select('subject')->first();
    //                     foreach ($get_subjects as $subject) {

    //                         $got_subject = Subject::where('id', $subject->subject_id)
    //                             ->whereNotIn('subject_type_id', [1, 7, 13])
    //                             ->first();
    //                         if ($got_subject != '') {
    //                             $got_subject->date = '';
    //                             array_push($subjects, $got_subject);
    //                         }
    //                     }
    //                     // $Exam_name->newsubject = $subjectsNew;
    //                 }

    //             } else {
    //                 foreach ($get_subjects as $subject) {
    //                     $got_subject = Subject::where('id', $subject->subject_id)
    //                         ->whereNotIn('subject_type_id', [3, 9, 15])
    //                         ->first();

    //                     if ($got_subject != '') {
    //                         array_push($subjects, $got_subject);
    //                     }
    //                 }
    //             }

    //             // return response()->json(['subjects' => $subjects]);
    //         }

    //         if ($academicYear_id  != '' && $course_id != '' &&   $semester_id != '' && $section != '') {

    //             $exam_name = ExamTimetableCreation::where(['accademicYear' => $academicYear_id, 'course' => $course_id, 'semester' => $semester_id, 'sections' => $section])->select('exam_name')->get();
    //             if (count($exam_name) > 0) {

    //                 return response()->json(['exam_name' => $exam_name, 'status' => 'success']);
    //             } else {
    //                 return response()->json(['status' => 'exam_Name']);
    //             }
    //         }

    //         // $subjects = $subjects->toArray();
    //         if ($get_sections != '' && $subjects != '' && isset($Exam_name) && isset($status)) {
    //             return response()->json(['subjects' => $subjects, 'get_section' => $get_sections, 'examName' => $Exam_name, 'exam_status'=>$status]);
    //         } elseif ($get_sections != '' && $subjects != '') {
    //             return response()->json(['subjects' => $subjects, 'get_section' => $get_sections]);
    //         } else if ($subjects != '') {

    //             return response()->json(['subjects' => $subjects]);
    //         } else if ($get_sections != '') {
    //             return response()->json(['get_section' => $get_sections]);
    //         } else {

    //             return response()->json(['status' => 'fail']);
    //         }
    //     } else {
    //         return response()->json(['status' => 'fail']);
    //     }
    // }

    public function subject_get2(Request $request)
    {

        $academicYear_id = $request->academicYear_id;
        $course_id = $request->course_id;
        $semester_id = $request->semester_id;
        $section = $request->section;

        if ($request->course_id != '' || $request->semester != '' || $request->accademicYear != '') {

            $get_section = [];
            $check_input = is_numeric($request->course_id);

            // $course = ToolsCourse::where('id', $request->course_id)->select('name')->first();
            // $AcademicYears = AcademicYear::where('id', $request->accademicYear)->select('name')->first();
            // $string = '/' . $course-> name. '/' . $AcademicYears->name . '/' . $request->semester . '/';
            // $stu = CourseEnrollMaster::where('enroll_master_number', 'LIKE', '%' . $string)->first();
            // if ($stu != null) {
            //     $students = Student::where('enroll_master_id', $stu->id)->count();
            //     if ($students > 0) {

            //         if ($check_input) {
            //             $get_section = Section::where(['course_id' => $request->course_id])->select('id', 'section')->get();
            //         } else {

            //             $get_course = ToolsCourse::where(['name' => $request->course_id])->first();
            //             if ($get_course != '') {
            //                 $get_section = Section::where(['course_id' => $get_course->id])->get();
            //             }
            //         }
            //     }

            // }

            if ($check_input) {
                $get_section = Section::where(['course_id' => $request->course_id])->select('id', 'section')->get();
            } else {

                $get_course = ToolsCourse::where(['name' => $request->course_id])->first();
                if ($get_course != '') {
                    $get_section = Section::where(['course_id' => $get_course->id])->get();
                }
            }

            $get_subjects = [];
            $get_subjects = SubjectAllotment::where(['semester' => $request->semester, 'course' => $request->course_id, 'academic_year' => $request->accademicYear, 'semester_type' => $request->semesterType])->get();

            $subjects = [];
            $got_subject = [];
            if (count($get_subjects) > 0) {
                foreach ($get_subjects as $subject) {
                    $got_subject = Subject::where(['id' => $subject->subject_id])->first();
                    if ($got_subject != '') {
                        array_push($subjects, $got_subject);
                    }
                }
                // return response()->json(['subjects' => $subjects]);
            }

            if ($academicYear_id != '' && $course_id != '' && $semester_id != '' && $section != '') {

                $exam_name = ExamTimetableCreation::where(['accademicYear' => $academicYear_id, 'course' => $course_id, 'semester' => $semester_id, 'sections' => $section])->select('exam_name')->get();
                if (count($exam_name) > 0) {

                    return response()->json(['exam_name' => $exam_name, 'status' => 'success']);
                } else {
                    return response()->json(['status' => 'exam_Name']);
                }
            }

            if ($get_section != '' && $subjects != '') {
                return response()->json(['subjects' => $subjects, 'get_section' => $get_section]);
            } else if ($subjects != '') {

                return response()->json(['subjects' => $subjects]);
            } else if ($get_section != '') {
                return response()->json(['get_section' => $get_section]);
            } else {

                return response()->json(['status' => 'fail']);
            }
        } else {
            return response()->json(['status' => 'fail']);
        }
    }

    public function attendanceEnter($request, $id)
    {
        // dd($request,$id);
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
            $subjectData = Examattendance::find($id);
            // dd($id);
            if ($subjectData) {
                $date = $subjectData->date;

                $got_subject = Subject::find($subjectData->subject);
                if ($got_subject) {
                    $subject_data = $got_subject->name . '(' . $got_subject->subject_code . ')';
                }
                $studentList = DB::table('subject_registration')->whereNull('students.deleted_at')->leftJoin('students', 'subject_registration.user_name_id', '=', 'students.user_name_id')->where('subject_registration.enroll_master', $request)->where('subject_registration.subject_id', $subjectData->subject)->whereNull('subject_registration.deleted_at')
                    ->select('students.register_no', 'students.user_name_id', 'students.name',
                        'students.enroll_master_id')
                    ->orderBy('students.register_no', 'asc')
                    ->get();
            }
            $studentList->exameid = $id;
        } else {
            $studentList = null;
            $studentList->exameid = '';
        }
        return view('admin.Exam_attendance.enter', compact('studentList', 'class_name', 'subject_data', 'date'));
    }

    public function attendencestore(Request $request)
    {
        // dd($request);
        if ($request) {
            $attendenceData = $request->attendance ?? '';
            $enroll_id = $request->enroll_id ?? '';
            $exameid = $request->exameid ?? '';
            $exam = Examattendance::find($exameid);

            // if ($exam) {
            //     if ($attendenceData) {
            //         $present = 0;
            //         $absent = 0;
            //         $entered = null;

            //         DB::beginTransaction();

            //         try {
            //             foreach ($attendenceData as $id => $attendanceData) {
            //                 // dd($id, $attendanceData);
            //                 if ($attendanceData == "Present") {
            //                     $present++;
            //                 }
            //                 if ($attendanceData == "Absent") {
            //                     $absent++;
            //                 }

            //                 // Create a new ExamattendanceData record
            //                 $entered = ExamattendanceData::create([
            //                     'date' => now(),
            //                     'subject' => $exam->subject,
            //                     'enteredby' => auth()->user()->id,
            //                     'class_id' => $enroll_id,
            //                     'attendance' => $attendanceData,
            //                     'student_id' => $id,
            //                     'examename' => $exameid,
            //                     'exame_date' => $exam->date,
            //                 ]);
            //             }

            //             $exam->total_present = $present;
            //             $exam->total_abscent = $absent;
            //             $exam->att_entered = $entered ? 'Yes' : 'No';

            //             DB::commit();
            //         } catch (\Exception $e) {
            //             DB::rollback();
            //         }

            //         $exam->date_entered = $entered ? $entered->date : null;
            //         $exam->entered_by = $entered ? $entered->enteredby : null;
            //         $exam->save();
            //     }
            // }
            if ($exam && $attendenceData) {
                $present = 0;
                $absent = 0;
                $entered = null;

                foreach ($attendenceData as $id => $attendanceData) {
                    if ($attendanceData == "Present") {
                        $present++;
                    }
                    if ($attendanceData == "Absent") {
                        $absent++;
                    }

                    // Create a new ExamattendanceData record
                    $check = ExamattendanceData::where(['class_id' => $enroll_id, 'examename' => $exameid, 'student_id' => $id, 'subject' => $exam->subject, 'exame_date' => $exam->date])->count();
                    if ($check <= 0) {
                        $entered = ExamattendanceData::create([
                            'date' => now(),
                            'subject' => $exam->subject,
                            'enteredby' => auth()->user()->id,
                            'class_id' => $enroll_id,
                            'attendance' => $attendanceData,
                            'student_id' => $id,
                            'examename' => $exameid,
                            'exame_date' => $exam->date,
                        ]);
                    }
                }

                if ($entered) {
                    $exam->total_present = $present;
                    $exam->total_abscent = $absent;
                    $exam->att_entered = 'Yes';

                    $exam->date_entered = $entered->date;
                    $exam->entered_by = $entered->enteredby;
                    $exam->save();
                }
            }
        }
        return redirect()->route('admin.Exam-Attendance.attendance');
    }

    public function viewattendance($request, $id)
    {
        $studentList = null;
        $class_name = '';
        $subject_data = '';
        $date = '';
        $examename_id = $id;
        if (isset($request, $id)) {
            $studentList = ExamattendanceData::where(['class_id' => $request, 'examename' => $id])->get();
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
            $subjectData = Examattendance::find($id);
            if ($subjectData) {
                $date = $subjectData->date;

                $got_subject = Subject::find($subjectData->subject);
                if ($got_subject) {
                    $subject_data = $got_subject->name . '(' . $got_subject->subject_code . ')';
                }
            }
            if ($studentList) {
                foreach ($studentList as $studentLists) {
                    // dd($studentLists);
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
        return view('admin.Exam_attendance.view', compact('studentList', 'class_name', 'subject_data', 'date', 'summary'));
    }

    public function editattendance($request, $id)
    {
        // dd($request,$id);
        $class_name = '';
        $subject_data = '';
        if (is_numeric($request) && $id != '') {
            $studentList = ExamattendanceData::where(['class_id' => $request, 'examename' => $id])->get();
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
            $subjectData = Examattendance::find($id);
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

        // dd($studentList);
        return view('admin.Exam_attendance.edit', compact('studentList', 'class_name', 'subject_data', 'date'));
    }

    public function attendenceUpdate(Request $request)
    {
        $exameid = null;
        $enroll_id = null;
        if ($request) {
            $attendenceData = $request->attendance ?? '';
            $enroll_id = $request->enroll_id ?? '';
            $exameid = $request->exameid ?? '';
            $exam = Examattendance::find($exameid);
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
                        //   dd($exameid);

                        // Create a new ExamattendanceData record
                        $entered = ExamattendanceData::where(['class_id' => $enroll_id, 'examename' => $exameid, 'student_id' => $id])->update([
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

        return redirect()->route('admin.exam_attendance.editattendance', [$enroll_id, $exameid]);
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
        $query = Examattendance::select(
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
                        $buttons .= '<a class="btn btn-xs btn-primary" href="' . route('admin.exam_attendance.viewattendance', [$stu->id, $record->id]) . '" target="_blank">View</a>';
                    } else {
                        // dd($record->att_entered);
                        $buttons .= '<a class="btn btn-xs btn-info" href="' . route('admin.exam_attendance.attendanceEnter', [$stu->id, $record->id]) . '" target="_blank">Enter</a>';
                    }

                    if (($role_id == 40 || $role_id == 1) && $record->att_entered == 'Yes') {
                        $buttons .= ' <a class="btn btn-xs btn-danger" href="' . route('admin.exam_attendance.editattendance', [$stu->id, $record->id]) . '" target="_blank">Edit</a>';
                    }

                    // Assign the action buttons and course name to the record
                    // dd($record->att_entered);
                    $record->actions = $buttons;
                    $record->course = $courseName;

                    // If subject is not empty, format subject and subject code
                    if ($record->subject != '') {
                        $subject = Subject::where(['id' => $record->subject])->select('subject_code', 'name')->first();
                        if ($subject) {
                            $record->subject = $subject->name ?? '';
                            $record->subject_code = $subject->subject_code ?? '';
                            // dd($subject->name);
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

    public function search(Request $request)
    {

        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $AcademicYear = AcademicYear::pluck('name', 'id');
        $examNames = ExamTimetableCreation::select('exam_name')->distinct()->get();

        if ($request->ajax()) {

            $accademicYear = $request->input('academicYear_id');
            $course = $request->input('course_id');
            $semester = $request->input('semester_id');
            $year = $request->input('year');
            $section = $request->input('section');
            $examename = $request->input('examename');

            // Start with a base query
            $query = ExamTimetableCreation::query();

            // Check each input value and add a where clause if it's present
            if ($accademicYear) {
                $query->Where('accademicYear', $accademicYear);
            }
            if ($course) {
                $query->Where('course', $course);
            }
            if ($semester) {
                $query->Where('semester', $semester);
            }
            if ($year) {
                $query->Where('year', $year);
            }
            if ($section) {
                $query->Where('sections', $section);
            }
            if ($examename) {
                $query->Where('exam_name', $examename);
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
                $crudRoutePart = 'examTimetable';

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
                // dd($row);
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
            $table->editColumn('course', function ($row) {
                if ($row->course != '') {
                    $course = ToolsCourse::find($row->course);
                    if ($course) {
                        $outputcourse = $course->short_form;
                    } else {
                        $outputcourse = '';
                    }
                }
                return $row->course ? $outputcourse : '';
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

                    $result = $this->toRoman($row->year);
                }
                return $row->year ? $result : '';
            });

            $table->editColumn('date', function ($row) {
                return $row->date ? $row->date : '';
            });
            $table->editColumn('start_time', function ($row) {
                if ($row->date) {
                    if ($this->is_serialized($row->date)) {
                        $dummy = unserialize($row->date);
                        // dd($dummy);for
                        $dates = [];
                        foreach ($dummy as $dummy1) {
                            foreach ($dummy1 as $dummy2) {
                                array_push($dates, $dummy2);
                            }
                        }
                        $timestamps = array_map(function ($dateString) {
                            return strtotime($dateString);
                        }, $dates);

                        // Find the first date (minimum timestamp)
                        $firstDateTimestamp = min($timestamps);
                        $firstDate = date('Y-m-d', $firstDateTimestamp);

                        // Find the last date (maximum timestamp)
                        // $lastDateTimestamp = max($timestamps);
                        // $lastDate = date('Y-m-d', $lastDateTimestamp);
                        // dd($row->date);
                    } else {
                        // Handle invalid or unserialized data
                        return 'Invalid Data';
                    }
                }
                return $row->date ? $firstDate ? $firstDate : '' : '';
            });
            $table->editColumn('end_time', function ($row) {
                if ($row->date) {
                    if ($this->is_serialized($row->date)) {
                        $dummy = unserialize($row->date);
                        // dd($dummy);for
                        $dates = [];
                        foreach ($dummy as $dummy1) {
                            foreach ($dummy1 as $dummy2) {
                                array_push($dates, $dummy2);
                            }
                        }
                        $timestamps = array_map(function ($dateString) {
                            return strtotime($dateString);
                        }, $dates);

                        // Find the first date (minimum timestamp)
                        // $firstDateTimestamp = min($timestamps);
                        // $firstDate = date('Y-m-d', $firstDateTimestamp);

                        // Find the last date (maximum timestamp)
                        $lastDateTimestamp = max($timestamps);
                        $lastDate = date('Y-m-d', $lastDateTimestamp);
                        // dd($row->date);
                    } else {
                        // Handle invalid or unserialized data
                        return 'Invalid Data';
                    }
                }
                return $row->date ? $lastDate ? $lastDate : '' : '';
            });
            $table->editColumn('subject', function ($row) {

                if ($row->subject != '') {
                    $subject = Subject::find($row->subject);
                    if ($subject) {
                        $subjectData = $subject->name . '(' . $subject->subject_code . ')';
                    } else {
                        $subjectData = '';
                    }
                }
                return $row->subject ? $subjectData : '';
            });
            $table->editColumn('sections', function ($row) {
                // if ($row->sections) {
                //     if ($this->is_serialized($row->sections)) {
                //         return unserialize($row->sections);
                //     } else {
                //         // Handle invalid or unserialized data
                //         return 'Invalid Data';
                //     }
                // }
                return $row->sections ? $row->sections : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        return view('admin.Exam_timetable.index', compact('courses', 'semester', 'AcademicYear', 'examNames'));
    }

    public function ViewAttendancePdf($id)
    {
        if (isset($id) && $id != '') {

            $studentList = null;
            $class_name = '';
            $subject_data = '';
            $date = '';
            $examename_id = $id;
            if (isset($request, $id)) {
                $studentList = ExamattendanceData::where(['examename' => $id])->get();
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
                $subjectData = Examattendance::find($id);
                if ($subjectData) {
                    $date = $subjectData->date;

                    $got_subject = Subject::find($subjectData->subject);
                    if ($got_subject) {
                        $subject_data = $got_subject->name . '(' . $got_subject->subject_code . ')';
                    }
                }
                if ($studentList) {
                    foreach ($studentList as $studentLists) {
                        // dd($studentLists);
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
            // $summary[]=$request;
            $summary[] = $examename_id;

            // $pdf = PDF::loadView('admin.Exam_attendance.AttendancePDF', compact('studentList', 'class_name', 'subject_data', 'date','summary'));
            $pdf = PDF::loadView('admin.Exam_attendance.AttendancePDF', ['studentList' => $studentList, 'class_name' => $class_name, 'subject_data' => $subject_data, 'date' => $summary]);

            // $pdf->setPaper('A4', 'landscape');

            return $pdf->stream('Attendance.pdf');
        }
    }

    public function subject_get(Request $request)
    {

        $academicYear_id = $request->academicYear_id;
        $course_id = $request->course_id;
        $semester_id = $request->semester_id;
        $section = $request->section;

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

            $get_subjects = [];
            $get_subjects = SubjectAllotment::where(['semester' => $request->semester, 'course' => $request->course_id, 'academic_year' => $request->accademicYear, 'semester_type' => $request->semesterType])->get();

            $subjects = [];
            $got_subject = [];
            if (count($get_subjects) > 0) {
                foreach ($get_subjects as $subject) {

                    $got_subject = Subject::where('id', $subject->subject_id)
                        ->whereNotIn('subject_type_id', [3, 9, 15])
                        ->first();

                    if ($got_subject != '') {
                        array_push($subjects, $got_subject);
                    }

                }
            }

            if ($academicYear_id != '' && $course_id != '' && $semester_id != '' && $section != '') {

                $exam_name = ExamTimetableCreation::where(['accademicYear' => $academicYear_id, 'course' => $course_id, 'semester' => $semester_id, 'sections' => $section])->select('exam_name')->get();
                if (count($exam_name) > 0) {

                    return response()->json(['exam_name' => $exam_name, 'status' => 'success']);
                } else {
                    return response()->json(['status' => 'exam_Name']);
                }
            }

            if ($get_sections != '' && $subjects != '') {
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
}
