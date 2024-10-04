<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AttendanceRecord;
use App\Models\ClassTimeTableTwo;
use App\Models\CourseEnrollMaster;
use App\Models\LessonPlans;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\TeachingStaff;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;

class SyllabusCompletionDeptWiseController extends Controller
{
    public function index()
    {
        $course = '';

        $userDpt = '';
        $departments = ToolsDepartment::pluck('name', 'id');
        $courses = ToolsCourse::pluck('short_form', 'id');
        $semester = Semester::pluck('semester', 'id');
        $AcademicYear = AcademicYear::pluck('name', 'id');
        $Section = Section::pluck('section', 'id')->unique();

        $getAys = AcademicYear::pluck('name', 'id');

        if (auth()->user()->roles[0]->id == 14) {
            $userDpt = auth()->user()->dept;
            $departments = ToolsDepartment::where('name', $userDpt)->first();
            if ($departments != '' && $departments->id != 5) {
                $courses = ToolsCourse::where('department_id', $departments->id)->pluck('short_form', 'id');
            }
        }
        return view('admin.SyllabusCompletionDeptWise.index', compact('departments', 'courses', 'semester', 'AcademicYear', 'Section', 'getAys'));
    }

    public function search(Request $request)
    {

        $array = [];

        $type_id = auth()->user()->roles[0]->type_id;
        $currentClasses = Session::get('currentClasses');
        if ($type_id == 1 || $type_id == 3) {

            $timeTable = ClassTimeTableTwo::whereIn('class_name', $currentClasses)->where(['staff' => auth()->user()->id, 'status' => 1])
                ->groupBy(['subject', 'class_name', 'staff'])
                ->select('subject', 'staff', 'class_name', DB::raw('COUNT(*) as count'))  
                ->get();

            if ($timeTable) {
                $processedTimeTable = [];

                foreach ($timeTable as $timetable) {
                    $CourseEnrollMaster = CourseEnrollMaster::find($timetable->class_name);
                    if ($CourseEnrollMaster) {
                        $year = $CourseEnrollMaster->enroll_master_number;
                        $yearSplit = explode('/', $year);

                        $Year = '';
                        $section = '';
                        $Semester = '';

                        if (isset($yearSplit) && $yearSplit) {
                            switch ($yearSplit[3]) {
                                case 1:
                                case 2:
                                    $Year = 1;
                                    break;
                                case 3:
                                case 4:
                                    $Year = 2;
                                    break;
                                case 5:
                                case 6:
                                    $Year = 3;
                                    break;
                                case 7:
                                case 8:
                                    $Year = 4;
                                    break;
                                default:
                                    $Year = '';
                                    break;
                            }

                            $section = $yearSplit[4];
                            $Semester = $yearSplit[3];
                            if ($timetable->subject == 'Library') {
                                continue;
                            }

                            if (is_numeric($timetable->subject)) {
                                try {
                                    $Subject = Subject::find($timetable->subject);
                                } catch (ModelNotFoundException $e) {
                                    $Subject = null;
                                }
                                try {
                                    $teachingStaff = TeachingStaff::where('user_name_id', $timetable->staff)->first();
                                } catch (ModelNotFoundException $e) {
                                    $teachingStaff = null;
                                }

                                if ($Subject) {
                                    $subjectname = $Subject->name;
                                    $subjectcode = $Subject->subject_code;
                                    $subjectId = $Subject->id;
                                    $staff = $teachingStaff != null ? $teachingStaff->name ?? '' : '';
                                    $staffCode = $teachingStaff != null ? $teachingStaff->StaffCode ?? '' : '';

                                    $LessonPlans = LessonPlans::where([
                                        'status' => '1',
                                        'class' => $CourseEnrollMaster->id,
                                        'subject' => $Subject->id,
                                    ])->select('unit_no', 'topic_no')->get();

                                    $LessonPlansCount = count($LessonPlans);

                                    $attendanceRecord = AttendanceRecord::where([
                                        'subject' => $Subject->id,
                                        'enroll_master' => $CourseEnrollMaster->id,
                                    ])->where('status', '!=', '0')->whereRaw("unit REGEXP '^[0-9]+$'")->groupby('unit', 'topic', 'actual_date')->select('unit', 'topic', 'actual_date')->get();

                                    $attendanceRecordForOthers = AttendanceRecord::where([
                                        'subject' => $Subject->id,
                                        'enroll_master' => $CourseEnrollMaster->id,
                                    ])->where('status', '!=', '0')->whereRaw("unit REGEXP '^[a-zA-Z]+$'")->groupby('unit', 'topic', 'actual_date')->select('unit', 'topic', 'actual_date')->get();

                                    $attended = count($attendanceRecord);
                                    $attendedOthers = count($attendanceRecordForOthers);

                                } else {
                                    $subjectname = '';
                                    $subjectcode = '';
                                    $staff = '';
                                    $LessonPlansCount = 0;
                                    $attended = 0;
                                    $subjectId = '';
                                    $staffCode = '';

                                }

                            } else {
                                $subjectname = '';
                                $subjectcode = '';
                                $staff = '';
                                $LessonPlansCount = '';
                                $attended = 0;
                                $subjectId = '';
                                $staffCode = '';

                            }

                            $processedTimeTableEntry = new \stdClass();
                            $processedTimeTableEntry->subjectName = $subjectname;
                            $processedTimeTableEntry->subjectCode = $subjectcode;
                            $processedTimeTableEntry->year = $Year;
                            $processedTimeTableEntry->section = $section;
                            $processedTimeTableEntry->Semester = $Semester;
                            $processedTimeTableEntry->staffName = $staff;
                            $processedTimeTableEntry->staffCode = $staffCode;
                            $processedTimeTableEntry->proposedPeriods = $LessonPlansCount;
                            if ($LessonPlansCount > 0) {
                                $percentag = ($attended / $LessonPlansCount) * 100;
                                $percentage = round($percentag, 2) . '%';
                            } else {
                                $percentage = 0;
                            }
                            $processedTimeTableEntry->handledPeriods = $attended;
                            $processedTimeTableEntry->percentage = $percentage;
                            $processedTimeTableEntry->others = $attendedOthers;
                            $button = '<a href="' . route('admin.staffFilter.view', ['enroll' => base64_encode($CourseEnrollMaster->id . '-' . $timetable->staff . '-' . $subjectId)]) . '" class="btn btn-xs btn-primary" target="_blank" >View</a><a href="' . route('admin.staffFilter.pdf', ['enroll' => base64_encode($CourseEnrollMaster->id . '-' . $timetable->staff . '-' . $subjectId)]) . '" class="btn btn-xs btn-success" target="_blank" >Download PDF</a>';
                            $processedTimeTableEntry->button = $button;

                            $processedTimeTable[] = $processedTimeTableEntry;
                        }
                    }

                }

                return response()->json(['data' => $processedTimeTable]);

            }

        }
        if ($request->academicYear != null && $request->course != null) {

            $course = ToolsCourse::where('short_form', $request->course)->select('name')->first();
            if ($course != '') {
                $course_name = $course->name;
            } else {
                $course_name = null;
            }

            $array = '/' . $course_name . '/' . $request->academicYear;

            $array .= isset($request->semester) && $request->semester != null ? '/' . $request->semester : '';

            $array .= isset($request->section) && $request->section != null
            ? '/' . $request->section
            : '';
            // dd($array);
            $CourseEnrollMasters = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%{$array}%")->get();

            $subjectIds = $CourseEnrollMasters->pluck('id')->toArray();
            $timeTable = ClassTimeTableTwo::whereIn('class_name', $subjectIds)
                ->select('subject', 'class_name', 'staff')
                ->groupBy('subject', 'class_name', 'staff')
                ->get();

            $processedCourseEnrollMasters = [];
            $processedTimeTable = [];

            if ($timeTable->isNotEmpty()) {
                foreach ($CourseEnrollMasters as $CourseEnrollMaster) {
                    $year = $CourseEnrollMaster->enroll_master_number;
                    $yearSplit = explode('/', $year);

                    $Year = '';
                    $section = '';
                    $Semester = '';

                    if (isset($yearSplit) && $yearSplit) {
                        switch ($yearSplit[3]) {
                            case 1:
                            case 2:
                                $Year = 1;
                                break;
                            case 3:
                            case 4:
                                $Year = 2;
                                break;
                            case 5:
                            case 6:
                                $Year = 3;
                                break;
                            case 7:
                            case 8:
                                $Year = 4;
                                break;
                            default:
                                $Year = '';
                                break;
                        }

                        $section = $yearSplit[4];
                        $Semester = $yearSplit[3];

                        $relatedTimeTables = $timeTable->where('class_name', $CourseEnrollMaster->id);

                        foreach ($relatedTimeTables as $timeTables) {
                            // dd($timeTables);
                            if ($timeTables->subject == 'Library') {
                                continue;
                            }
                            if (is_numeric($timeTables->subject)) {
                                try {
                                    $Subject = Subject::find($timeTables->subject);
                                } catch (ModelNotFoundException $e) {
                                    $Subject = null;
                                }
                                try {
                                    $teachingStaff = TeachingStaff::where('user_name_id', $timeTables->staff)->first();
                                } catch (ModelNotFoundException $e) {
                                    $teachingStaff = null;
                                }

                                if ($Subject) {
                                    $subjectname = $Subject->name;
                                    $subjectcode = $Subject->subject_code;
                                    $subjectId = $Subject->id;
                                    $staff = $teachingStaff != null ? $teachingStaff->name ?? '' : '';
                                    $staffCode = $teachingStaff != null ? $teachingStaff->StaffCode ?? '' : '';

                                    $LessonPlans = LessonPlans::where([
                                        'status' => '1',
                                        'class' => $timeTables->class_name,
                                        'subject' => $timeTables->subject,
                                    ])->select('unit_no', 'topic_no')->get();

                                    $LessonPlansCount = count($LessonPlans);

                                    $attendanceRecord = AttendanceRecord::where([
                                        'subject' => $timeTables->subject,
                                        'enroll_master' => $timeTables->class_name,
                                    ])->where('status', '!=', '0')->whereRaw("unit REGEXP '^[0-9]+$'")->groupby('unit', 'topic', 'actual_date')->select('unit', 'topic', 'actual_date')->get();
                                    $realAttendanceRecord = AttendanceRecord::where([
                                        'subject' => $timeTables->subject,
                                        'enroll_master' => $timeTables->class_name,
                                    ])->where('status', '!=', '0')->whereRaw("unit REGEXP '^[0-9]+$'")->select('unit', 'topic')->distinct()->get();
                                    $attendanceRecordForOthers = AttendanceRecord::where([
                                        'subject' => $timeTables->subject,
                                        'enroll_master' => $timeTables->class_name,
                                    ])->where('status', '!=', '0')->whereRaw("unit REGEXP '^[a-zA-Z]+$'")->groupby('unit', 'topic', 'actual_date')->select('unit', 'topic', 'actual_date')->get();

                                    $attended = count($attendanceRecord);
                                    $realAttended = count($realAttendanceRecord);
                                    $attendedOthers = count($attendanceRecordForOthers);

                                } else {
                                    $subjectname = '';
                                    $subjectcode = '';
                                    $staff = '';
                                    $LessonPlansCount = 0;
                                    $attended = 0;
                                    $realAttended = 0;
                                    $subjectId = '';
                                    $staffCode = '';

                                }

                            } else {
                                $subjectname = '';
                                $subjectcode = '';
                                $staff = '';
                                $LessonPlansCount = 0;
                                $attended = 0;
                                $realAttended = 0;
                                $subjectId = '';
                                $staffCode = '';

                            }

                            $processedTimeTableEntry = new \stdClass();
                            $processedTimeTableEntry->subjectName = $subjectname;
                            $processedTimeTableEntry->subjectCode = $subjectcode;
                            $processedTimeTableEntry->year = $Year;
                            $processedTimeTableEntry->section = $section;
                            $processedTimeTableEntry->Semester = $Semester;
                            $processedTimeTableEntry->staffName = $staff;
                            $processedTimeTableEntry->staffCode = $staffCode;
                            $processedTimeTableEntry->proposedPeriods = $LessonPlansCount;
                            if ($LessonPlansCount > 0) {
                                $percentag = ($realAttended / $LessonPlansCount) * 100;
                                $percentage = round($percentag, 2) . '%';
                            } else {
                                $percentage = 0;
                            }
                            $processedTimeTableEntry->handledPeriods = $attended;
                            $processedTimeTableEntry->percentage = $percentage;
                            $processedTimeTableEntry->others = $attendedOthers;
                            $button = '<a href="' . route('admin.staffFilter.view', ['enroll' => base64_encode($CourseEnrollMaster->id . '-' . $timeTables->staff . '-' . $subjectId)]) . '" class="btn-xs btn-primary p-1" target="_blank">View</a><div style="padding-top:2px;"></div>';
                            $button .= '<a href="' . route('admin.staffFilter.pdf', ['enroll' => base64_encode($CourseEnrollMaster->id . '-' . $timeTables->staff . '-' . $subjectId)]) . '" class="btn-xs btn-success p-1" target="_blank">Download</a>';
                            $processedTimeTableEntry->button = $button;

                            $processedTimeTable[] = $processedTimeTableEntry;
                        }
                    }

                }
            }

            return response()->json(['data' => $processedTimeTable]);
        }

        return response()->json(['data' => '']);
    }

    public function view($enroll)
    {
        $totalProposed = null;
        $totalConducted = null;
        $realTotalConducted = 0;
        $totalPercentage = null;
        $getOthers = [];
        $proposedArraY = [];
        $decodeadData = base64_decode($enroll);

        $array = explode('-', $decodeadData);
        // dd($array);
        $containsEmpty = false;
        foreach ($array as $element) {
            if (empty($element)) {
                $containsEmpty = true;
                break;
            }
        }

        if ($containsEmpty || count($array) < 3) {
            return view('auth.close');
        } else {
            $enrollId = $array[0];
            $staffId = $array[1];
            $subjectId = $array[2];
            //    dd($enrollId,$staffId,$subjectId);
            $getOthers = AttendanceRecord::where(['subject' => $subjectId, 'enroll_master' => $enrollId])->where('status', '!=', '0')->whereRaw("unit REGEXP '^[a-zA-Z]+$'")->groupby('unit', 'topic', 'actual_date')->select('unit', 'topic', 'actual_date')->get();
            $lessonplane = LessonPlans::select('class', 'subject', 'unit_no', 'unit')
                ->where([
                    'class' => $enrollId,
                    'subject' => $subjectId,
                    'status' => '1',
                ])
                ->groupBy('class', 'subject', 'unit_no', 'unit')->get();
            // dd($lessonplane);
            $lessonPlanes = [];
            // $topics = [];

            if ($lessonplane) {
                foreach ($lessonplane as $lessonPlan) {
                    $topicLesson = LessonPlans::where([
                        // 'user_name_id' => $staffId,
                        'class' => $lessonPlan->class,
                        'subject' => $lessonPlan->subject,
                        'status' => '1',
                        'unit_no' => $lessonPlan->unit_no,
                    ])->get();
                    // dd($topicLesson);

                    $topics = [];

                    if ($topicLesson) {
                        $topicCount = 0;
                        $unitCount = count($topicLesson);
                        // $totalProposed = $unitCount;
                        $totalProposed += $unitCount;

                        $topics = [];

                        foreach ($topicLesson as $topical) {
                            $attendanceRecord = AttendanceRecord::where([
                                'subject' => $subjectId,
                                'enroll_master' => $enrollId,
                                'unit' => $topical->unit_no,
                                'topic' => $topical->topic_no,
                            ])->where('status', '!=', '0')->select('actual_date', 'unit', 'topic')->get();
                            // dd($attendanceRecord);
                            $dateArray = [];
                            if (count($attendanceRecord) > 0) {
                                foreach ($attendanceRecord as $record) {
                                    if (!in_array($record->actual_date, $dateArray)) {
                                        array_push($dateArray, $record->actual_date);
                                        $topicCount++;
                                    }
                                    if (!in_array([$record->unit, $record->topic], $proposedArraY)) {
                                        array_push($proposedArraY, [$record->unit, $record->topic]);
                                        $realTotalConducted++;
                                    }
                                }

                            }
                            // dd($dateArray);
                            $date = '';
                            if (count($dateArray) > 0) {
                                foreach ($dateArray as $data) {
                                    $date .= $data . ',';
                                }
                            }
                            $topic = new \stdClass();
                            $topic->proposed_date = $topical->proposed_date ?? '';
                            $topic->topic = $topical->topic ?? '';
                            $topic->text_book = $topical->text_book ?? '';
                            $topic->delivery_method = $topical->delivery_method ?? '';
                            $topic->attendedperiod = substr($date, 0, -1);
                            $topic->unitPeriods = $unitCount;
                            $topic->conducted = $topicCount;
                            $topics[] = $topic;
                        }
                        $totalConducted += $topicCount;
                    }

                    $lessonPlan->lesTopic = $topics;
                }
                // dd($lessonplane);
            }

            $enrollName = CourseEnrollMaster::find($enrollId);
            if ($enrollName) {
                $enrollArray = $enrollName->enroll_master_number;
                $newArray = explode('/', $enrollArray);
                $ToolsCourse = ToolsCourse::where('name', $newArray[1])->first();
                if ($ToolsCourse) {
                    $departmentID = $ToolsCourse->department_id;

                    $ToolsDepartment = ToolsDepartment::find($departmentID);
                    if ($ToolsDepartment) {
                        $department = $ToolsDepartment->name;
                    } else {
                        $department = '';

                    }
                } else {

                    $department = '';
                }
                switch ($newArray[3]) {
                    case 1:
                    case 2:
                        $Year = 1;
                        break;
                    case 3:
                    case 4:
                        $Year = 2;
                        break;
                    case 5:
                    case 6:
                        $Year = 3;
                        break;
                    case 7:
                    case 8:
                        $Year = 4;
                        break;
                    default:
                        $Year = '';
                        break;
                }

                $accademicYear = $newArray[2];
                $course = $newArray[1];
                $sem = $newArray[3];
                $section = $newArray[4];
            } else {
                $accademicYear = '';
                $course = '';
                $department = '';
                $Year = '';
                $sem = '';
                $section = '';
            }
            $TeachingStaff = TeachingStaff::where('user_name_id', $staffId)->first();
            $subjectName = Subject::find($subjectId);
            if ($subjectName) {
                $subName = ($subjectName->name ?? '') . ' -(' . ($subjectName->subject_code ?? '') . ')';
            } else {
                $subName = '';
            }
            if ($TeachingStaff) {
                $name = ($TeachingStaff->name ?? '') . ' - (' . ($TeachingStaff->StaffCode ?? '') . ')';
            } else {
                $name = '';
            }
            $newObj = new \stdClass();
            $newObj->accademicYear = $accademicYear;
            $newObj->course = $course;
            $newObj->department = $department;
            $newObj->Year = $Year;
            $newObj->sem = $sem;
            $newObj->section = $section;
            $newObj->name = $name;
            $newObj->subName = $subName;

            if ($realTotalConducted != 0) {
                $totalPercentage = round(($realTotalConducted / $totalProposed) * 100, 2) . '%';
            }

            return view('admin.SyllabusCompletionDeptWise.views', compact('newObj', 'lessonplane', 'totalProposed', 'totalConducted', 'totalPercentage', 'getOthers'));

        }
    }

    public function pdf($enroll)
    {
        $totalProposed = null;
        $totalConducted = null;
        $totalPercentage = null;
        $getOthers = [];
        $decodeadData = base64_decode($enroll);

        $array = explode('-', $decodeadData);
        // dd($array);
        $containsEmpty = false;
        foreach ($array as $element) {
            if (empty($element)) {
                $containsEmpty = true;
                break;
            }
        }

        if ($containsEmpty || count($array) < 3) {
            return view('auth.close');
        } else {
            $enrollId = $array[0];
            $staffId = $array[1];
            $subjectId = $array[2];
            //    dd($enrollId,$staffId,$subjectId);
            $getOthers = AttendanceRecord::where(['subject' => $subjectId, 'enroll_master' => $enrollId])->where('status', '!=', '0')->whereRaw("unit REGEXP '^[a-zA-Z]+$'")->groupby('unit', 'topic', 'actual_date')->select('unit', 'topic', 'actual_date')->get();
            $lessonplan = LessonPlans::select('class', 'subject', 'unit_no', 'unit')
                ->where([
                    'class' => $enrollId,
                    'subject' => $subjectId,
                    'status' => '1',
                ])
                ->groupBy('class', 'subject', 'unit_no', 'unit')->get();

            $lessonPlans = [];
            // $topics = [];

            if ($lessonplan) {
                foreach ($lessonplan as $lessonPlan) {
                    $topicLesson = LessonPlans::where([
                        // 'user_name_id' => $staffId,
                        'class' => $lessonPlan->class,
                        'subject' => $lessonPlan->subject,
                        'status' => '1',
                        'unit_no' => $lessonPlan->unit_no,
                    ])->get();
                    // dd($topicLesson);

                    $topics = [];

                    if ($topicLesson) {
                        $topicCount = 0;
                        $unitCount = count($topicLesson);
                        // $totalProposed = $unitCount;
                        $totalProposed += $unitCount;

                        $topics = [];

                        foreach ($topicLesson as $topical) {
                            $attendanceRecord = AttendanceRecord::where([
                                'subject' => $subjectId,
                                'enroll_master' => $enrollId,
                                'unit' => $topical->unit_no,
                                'topic' => $topical->topic_no,
                            ])->where('status', '!=', '0')->select('actual_date')->get();
                            // dd($attendanceRecord);
                            $dateArray = [];
                            if (count($attendanceRecord) > 0) {
                                foreach ($attendanceRecord as $record) {
                                    if (!in_array($record->actual_date, $dateArray)) {
                                        array_push($dateArray, $record->actual_date);
                                        $topicCount++;
                                    }
                                }

                            }
                            // dd($dateArray);
                            $date = '';
                            if (count($dateArray) > 0) {
                                foreach ($dateArray as $data) {
                                    $date .= $data . ',';
                                }
                            }
                            $topic = new \stdClass();
                            $topic->proposed_date = $topical->proposed_date ?? '';
                            $topic->topic = $topical->topic ?? '';
                            $topic->text_book = $topical->text_book ?? '';
                            $topic->delivery_method = $topical->delivery_method ?? '';
                            $topic->attendedperiod = substr($date, 0, -1);
                            $topic->unitPeriods = $unitCount;
                            $topic->conducted = $topicCount;
                            $topics[] = $topic;
                        }

                        $totalConducted += $topicCount;
                    }

                    $lessonPlan->lesTopic = $topics;
                }

            }

            $enrollName = CourseEnrollMaster::find($enrollId);
            if ($enrollName) {
                $enrollArray = $enrollName->enroll_master_number;
                $newArray = explode('/', $enrollArray);
                $ToolsCourse = ToolsCourse::where('name', $newArray[1])->first();
                if ($ToolsCourse) {
                    $departmentID = $ToolsCourse->department_id;

                    $ToolsDepartment = ToolsDepartment::find($departmentID);
                    if ($ToolsDepartment) {
                        $department = $ToolsDepartment->name;
                    } else {
                        $department = '';

                    }
                } else {

                    $department = '';
                }
                switch ($newArray[3]) {
                    case 1:
                    case 2:
                        $Year = 1;
                        break;
                    case 3:
                    case 4:
                        $Year = 2;
                        break;
                    case 5:
                    case 6:
                        $Year = 3;
                        break;
                    case 7:
                    case 8:
                        $Year = 4;
                        break;
                    default:
                        $Year = '';
                        break;
                }

                $accademicYear = $newArray[2];
                $course = $newArray[1];
                $sem = $newArray[3];
                $section = $newArray[4];
            } else {
                $accademicYear = '';
                $course = '';
                $department = '';
                $Year = '';
                $sem = '';
                $section = '';
            }
            $TeachingStaff = TeachingStaff::where('user_name_id', $staffId)->first();
            $subjectName = Subject::find($subjectId);
            if ($subjectName) {
                $subName = ($subjectName->name ?? '') . ' -(' . ($subjectName->subject_code ?? '') . ')';
            } else {
                $subName = '';
            }
            if ($TeachingStaff) {
                $name = ($TeachingStaff->name ?? '') . ' - (' . ($TeachingStaff->StaffCode ?? '') . ')';
            } else {
                $name = '';
            }
            $newObj = new \stdClass();
            $newObj->accademicYear = $accademicYear;
            $newObj->course = $course;
            $newObj->department = $department;
            $newObj->Year = $Year;
            $newObj->sem = $sem;
            $newObj->section = $section;
            $newObj->name = $name;
            $newObj->subName = $subName;

            if ($totalConducted != 0) {
                $totalPercentage = round(($totalConducted / $totalProposed) * 100, 2) . '%';
            }

            $final_data = ['newObj' => $newObj, 'lessonplan' => $lessonplan, 'totalProposed' => $totalProposed, 'totalConducted' => $totalConducted, 'totalPercentage' => $totalPercentage, 'getOthers' => $getOthers];

            $pdf = PDF::loadView('admin.SyllabusCompletionDeptWise.completionPDF', $final_data);

            // $pdf->setPaper('A4', 'landscape');

            return $pdf->stream('SyllabusCompletionReport.pdf');

        }
    }

    public function getPastRecords(Request $request)
    {

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

        $timeTable = ClassTimeTableTwo::whereIn('class_name', $theClass)->where(['staff' => auth()->user()->id, 'status' => 1])
            ->groupBy(['subject', 'class_name', 'staff'])
            ->select('subject', 'staff', 'class_name', DB::raw('COUNT(*) as count'))
            ->get();

        if ($timeTable) {
            $processedTimeTable = [];

            foreach ($timeTable as $timetable) {
                $CourseEnrollMaster = CourseEnrollMaster::find($timetable->class_name);
                if ($CourseEnrollMaster) {
                    $year = $CourseEnrollMaster->enroll_master_number;
                    $yearSplit = explode('/', $year);

                    $Year = '';
                    $section = '';
                    $Semester = '';

                    if (isset($yearSplit) && $yearSplit) {
                        switch ($yearSplit[3]) {
                            case 1:
                            case 2:
                                $Year = 1;
                                break;
                            case 3:
                            case 4:
                                $Year = 2;
                                break;
                            case 5:
                            case 6:
                                $Year = 3;
                                break;
                            case 7:
                            case 8:
                                $Year = 4;
                                break;
                            default:
                                $Year = '';
                                break;
                        }

                        $section = $yearSplit[4];
                        $Semester = $yearSplit[3];
                        if ($timetable->subject == 'Library') {
                            continue;
                        }

                        if (is_numeric($timetable->subject)) {
                            try {
                                $Subject = Subject::find($timetable->subject);
                            } catch (ModelNotFoundException $e) {
                                $Subject = null;
                            }
                            try {
                                $teachingStaff = TeachingStaff::where('user_name_id', $timetable->staff)->first();
                            } catch (ModelNotFoundException $e) {
                                $teachingStaff = null;
                            }

                            if ($Subject) {
                                $subjectname = $Subject->name;
                                $subjectcode = $Subject->subject_code;
                                $subjectId = $Subject->id;
                                $staff = $teachingStaff != null ? $teachingStaff->name ?? '' : '';
                                $staffCode = $teachingStaff != null ? $teachingStaff->StaffCode ?? '' : '';

                                $LessonPlans = LessonPlans::where([
                                    'status' => '1',
                                    'class' => $CourseEnrollMaster->id,
                                    'subject' => $Subject->id,
                                ])->select('unit_no', 'topic_no')->get();

                                $LessonPlansCount = count($LessonPlans);

                                $attendanceRecord = AttendanceRecord::where([
                                    'subject' => $Subject->id,
                                    'enroll_master' => $CourseEnrollMaster->id,
                                ])->where('status', '!=', '0')->whereRaw("unit REGEXP '^[0-9]+$'")->groupby('unit', 'topic', 'actual_date')->select('unit', 'topic', 'actual_date')->get();

                                $attendanceRecordForOthers = AttendanceRecord::where([
                                    'subject' => $Subject->id,
                                    'enroll_master' => $CourseEnrollMaster->id,
                                ])->where('status', '!=', '0')->whereRaw("unit REGEXP '^[a-zA-Z]+$'")->groupby('unit', 'topic', 'actual_date')->select('unit', 'topic', 'actual_date')->get();

                                $attended = count($attendanceRecord);
                                $attendedOthers = count($attendanceRecordForOthers);

                            } else {
                                $subjectname = '';
                                $subjectcode = '';
                                $staff = '';
                                $LessonPlansCount = 0;
                                $attended = 0;
                                $subjectId = '';
                                $staffCode = '';

                            }

                        } else {
                            $subjectname = '';
                            $subjectcode = '';
                            $staff = '';
                            $LessonPlansCount = '';
                            $attended = 0;
                            $subjectId = '';
                            $staffCode = '';

                        }

                        $processedTimeTableEntry = new \stdClass();
                        $processedTimeTableEntry->subjectName = $subjectname;
                        $processedTimeTableEntry->subjectCode = $subjectcode;
                        $processedTimeTableEntry->year = $Year;
                        $processedTimeTableEntry->section = $section;
                        $processedTimeTableEntry->Semester = $Semester;
                        $processedTimeTableEntry->staffName = $staff;
                        $processedTimeTableEntry->staffCode = $staffCode;
                        $processedTimeTableEntry->proposedPeriods = $LessonPlansCount;
                        if ($LessonPlansCount > 0) {
                            $percentag = ($attended / $LessonPlansCount) * 100;
                            $percentage = round($percentag, 2) . '%';
                        } else {
                            $percentage = 0;
                        }
                        $processedTimeTableEntry->handledPeriods = $attended;
                        $processedTimeTableEntry->percentage = $percentage;
                        $processedTimeTableEntry->others = $attendedOthers;
                        $button = '<a href="' . route('admin.staffFilter.view', ['enroll' => base64_encode($CourseEnrollMaster->id . '-' . $timetable->staff . '-' . $subjectId)]) . '" class="btn btn-xs btn-primary" target="_blank" >View</a><a href="' . route('admin.staffFilter.pdf', ['enroll' => base64_encode($CourseEnrollMaster->id . '-' . $timetable->staff . '-' . $subjectId)]) . '" class="btn btn-xs btn-success" target="_blank" >Download PDF</a>';
                        $processedTimeTableEntry->button = $button;

                        $processedTimeTable[] = $processedTimeTableEntry;
                    }
                }

            }

            return response()->json(['data' => $processedTimeTable]);

        }
        return response()->json(['data' => '']);
    }

}
