<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\SystemCalendarController;
use App\Models\ClassTimeTableTwo;
use App\Models\CollegeCalender;
use App\Models\CourseEnrollMaster;
use App\Models\Document;
use App\Models\HrmRequestLeaf;
use App\Models\NonTeachingStaff;

session()->start();
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeachingStaff;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\DB;

class HomeController extends SystemCalendarController
{
    public function index()
    {
        $user_id = auth()->user()->id;
        $get_staff = DB::table('role_user')->where(['user_id' => $user_id])->first();
        $roleTypeId = null;
        if (auth()->user()->roles->isNotEmpty()) {
            $roleTypeId = auth()->user()->roles[0]->type_id ?? null;
        }
        $user = isset($get_staff->user_id) ? $get_staff->user_id : '';
        $role = isset($get_staff->role_id) ? $get_staff->role_id : '';

        $document = Document::where(['nameofuser_id' => $user_id, 'fileName' => 'Profile'])->first();
        if (!is_null($document)) {
            $profile = $document->filePath;
        } else {
            $profile = null;
        }
        // if ($role != '') {
        if ($roleTypeId == 1 || $roleTypeId == 3) {
            $events = parent::calendar();

            session(['profile' => $profile]);
            $personal_details = PersonalDetail::where(['user_name_id' => $user_id])->first();
            $teaching_staffs = TeachingStaff::where(['user_name_id' => $user_id])->first();
            $eventparticipation = EventParticipation::where(['user_name_id' => $user_id, 'status' => 1])->count();
            $Awards = Award::where(['user_name_id' => $user_id, 'status' => 1])->count();
            $Patent = Patent::where(['name_id' => $user_id, 'status' => 1])->count();
            $OnlineCourse = OnlineCourse::where(['user_name_id' => $user_id, 'status' => 1])->count();
            $Iv = Iv::where(['name_id' => $user_id, 'status' => 1])->count();
            $IndustrialExperience = IndustrialExperience::where(['user_name_id' => $user_id, 'status' => 1])->count();
            $Intern = Intern::where(['name_id' => $user_id, 'status' => 1])->count();
            $IndustrialTraining = IndustrialTraining::where(['name_id' => $user_id, 'status' => 1])->count();
            $EventOrganized = EventOrganized::where(['user_name_id' => $user_id, 'status' => 1])->count();
            $Journal = PublicationDetail::where(['user_name_id' => $user_id, 'status' => 1, 'publication_type' => 'Journal'])->count();
            $Conference = PublicationDetail::where(['user_name_id' => $user_id, 'status' => 1, 'publication_type' => 'Conference'])->count();
            $Text_Book = PublicationDetail::where(['user_name_id' => $user_id, 'status' => 1, 'publication_type' => 'Text_Book'])->count();
            $Book_Chapter = PublicationDetail::where(['user_name_id' => $user_id, 'status' => 1, 'publication_type' => 'Book_Chapter'])->count();

            $PhdDetail = PhdDetail::where(['user_name_id' => $user_id, 'status' => 1])->count();
            $Address = Address::where(['name_id' => $user_id, 'status' => 1])->first();
            // dd($Address->room_no_and_street );

            $document = Document::where(['nameofuser_id' => $user_id, 'fileName' => 'Profile'])->get();

            $date_of_birth = $personal_details ? $personal_details->dob ? $personal_details->dob : null : null;
            if ($date_of_birth != null) {
                $birth_date = new DateTime($date_of_birth);
                $today = new DateTime();
                $age = $today->diff($birth_date)->y;
                $personal_details->age = $age;
            } else {
                $personal_details->age = null;
            }

            return view('staff_home', compact('events', 'personal_details', 'teaching_staffs', 'eventparticipation', 'Awards', 'Patent', 'OnlineCourse', 'Iv', 'IndustrialExperience', 'Intern', 'IndustrialTraining', 'EventOrganized', 'Journal', 'Conference', 'Text_Book', 'Book_Chapter', 'PhdDetail', 'Address', 'document'));
        } elseif ($roleTypeId == 2 || $roleTypeId == 4 || $roleTypeId == 5) {
            return view('nt_staff_home');
        } else {
            if ($role == 13) {
                $teachingStaffs = TeachingStaff::count('id');
                $nonTeachingStaffs = NonTeachingStaff::count('id');
                $userCounts = User::count('id');
                return view('hr_home', compact('teachingStaffs', 'nonTeachingStaffs', 'userCounts'));
            } elseif ($role == 11) {
                $user_id = auth()->user()->id;
                $currentDate = date('Y-m-d');
                $calender = CollegeCalender::where('from_date', '<=', $currentDate)
                    ->where('to_date', '>=', $currentDate)
                    ->first();

                if ($calender != '') {
                    // dd($calender);
                    $events = DB::table('college_calenders_preview')
                        ->select('date', 'dayorder')
                        ->where('date', '>=', $calender->from_date)
                        ->where('date', '<=', $calender->to_date)
                        ->get();
                } else {
                    $events = [];
                }

                // dd($events);
                $dateTime = new DateTime($currentDate);

                $month = $dateTime->format('m');
                $year = $dateTime->format('Y');

                $date = new DateTime("$year-$month-01");
                $numDays = $date->format('t');
                $firstDayOfWeek = $date->format('w');
                $weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

                //Student Time Table
                $student = Student::where(['user_name_id' => $user_id])->first();
                // dd();
                if ($student != '') {
                    $enrollid = $student->enroll_master_id;

                    if ($enrollid != null) {
                        $enroll_name = CourseEnrollMaster::where(['id' => $enrollid])->first();
                        $enroll_masterId = $enroll_name->enroll_master_number;
                        $currentDay = date('l');

                        if ($enroll_name) {
                            $timeTable = ClassTimeTableTwo::where(['class_name' => $enroll_masterId, 'status' => 1])->get();
                            foreach ($timeTable as $timetable) {

                                if (!empty($timetable->subject)) {
                                    $subjects = Subject::where(['id' => $timetable->subject])->first();
                                    if ($subjects != '') {
                                        $timetable->subject = $subjects->name;
                                    } else {
                                        $timetable->subject = null;
                                    }

                                }

                            }
                        }
                    } else {
                        $currentDay = null;
                        $timeTable = null;
                    }

                } else {
                    $enrollid = null;
                    $currentDay = null;
                    $timeTable = null;
                }

                return view('student_home', compact('month', 'year', 'numDays', 'firstDayOfWeek', 'weekdays', 'events', 'profile', 'timeTable', 'currentDay'));
            } else {
                $teachingStaffs = TeachingStaff::count('id');
                // $nonTeachingStaffs = NonTeachingStaff::count('id');
                $userCounts = User::count('id');
                $currentDate = date('Y-m-d');
                $calender = CollegeCalender::where('from_date', '<=', $currentDate)
                    ->where('to_date', '>=', $currentDate)
                    ->first();
                if (!empty($calender->from_date)) {

                    $events = DB::table('college_calenders_preview')
                        ->select('date', 'dayorder')
                        ->where('date', '>=', $calender->from_date)
                        ->where('date', '<=', $calender->to_date)
                        ->get();
                    // dd($events);
                    $dateTime = new DateTime($currentDate);
                    $month = $dateTime->format('m');
                    $year = $dateTime->format('Y');

                    $date = new DateTime("$year-$month-01");
                    $numDays = $date->format('t');
                    $firstDayOfWeek = $date->format('w');
                    $weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

                    $staff_leaves = HrmRequestLeaf::where('leave_type', 1)
                        ->where('status', 'Pending')
                        ->when($role == 14, function ($query) {
                            return $query->where('level', '0');
                        })
                        ->count();

                    $staff_od = HrmRequestLeaf::where('leave_type', [2, 3, 4])->where('status', 'Pending')->when($role == 14, function ($query) {
                        return $query->where('level', '0');
                    })->count();
                    $check = 'nil';
                } else {
                    $check = 'empty';
                    $month = '';
                    $year = '';
                    $numDays = '';
                    $firstDayOfWeek = '';
                    $weekdays = '';
                    $events = [];
                    $staff_leaves = 0;
                    $staff_od = 0;
                }

                return view('home', compact('month', 'year', 'numDays', 'firstDayOfWeek', 'weekdays', 'events', 'staff_leaves', 'staff_od', 'check', 'teachingStaffs',  'userCounts'));
            }
        }

    }

}
