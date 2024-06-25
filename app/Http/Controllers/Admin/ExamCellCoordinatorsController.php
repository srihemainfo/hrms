<?php

namespace App\Http\Controllers\Admin;

use App\Models\Iv;
use App\Models\Role;
use App\Models\Sttp;
use App\Models\Award;
use App\Models\Events;
use App\Models\Intern;
use App\Models\Patent;
use App\Models\Address;
use App\Models\Seminar;
use App\Models\Sponser;
use App\Models\Document;
use App\Models\Religion;
use App\Models\Workshop;
use App\Models\Community;
use App\Models\Examstaff;
use App\Models\LeaveType;
use App\Models\PhdDetail;
use App\Models\Sabotical;
use App\Models\BloodGroup;
use App\Models\StaffSalary;
use App\Models\EntranceExam;
use App\Models\GuestLecture;
use App\Models\MotherTongue;
use App\Models\OnlineCourse;
use Illuminate\Http\Request;
use App\Models\AddConference;
use App\Models\EducationType;
use App\Models\TeachingStaff;
use App\Models\EventOrganized;
use App\Models\HrmRequestLeaf;
use App\Models\PersonalDetail;
use App\Models\MediumofStudied;
use App\Models\ExperienceDetail;
use App\Models\NonTeachingStaff;
use App\Models\PromotionDetails;
use App\Models\BankAccountDetail;
use App\Models\EducationalDetail;
use App\Models\PermissionRequest;
use App\Models\PublicationDetail;
use App\Models\EventParticipation;
use App\Models\IndustrialTraining;
use App\Models\ExamCellCoordinator;
use App\Http\Controllers\Controller;
use App\Models\IndustrialExperience;

class ExamCellCoordinatorsController extends Controller
{
    public function index(){
        $datas=ExamCellCoordinator::get();
        foreach($datas as $data){
            $Staff=TeachingStaff::select(['name','user_name_id','StaffCode','Dept','Designation'])->where('user_name_id',$data->user_name_id)->first();
            $staff_type='TeachingStaff';

                if(!$Staff){
                    $Staff=NonTeachingStaff::select(['name','user_name_id','StaffCode','Dept','Designation'])->where('user_name_id',$data->user_name_id)->first();
            $staff_type='Non-TeachingStaff';

                }
                $data->name=$Staff->name;
                $data->StaffCode=$Staff->StaffCode;
                $data->Dept=$Staff->Dept;
                $data->Designation=$Staff->Designation;
                $data->staffType=$staff_type;
                // $data->button='<a  class="btn btn-info btn-sm"href="#" role="button">View</a>';
                // $data->button2='<a  class="btn btn-danger btn-sm" href="{{ route('admin.') }}" role="button">Remove</a>';

        }
                // dd($datas);

        return view('admin.examcellCoordinators.index',compact('datas'));
    }

    public function create(){

        $table1Data = TeachingStaff::select(['name','user_name_id','StaffCode'])->get();
$table2Data = NonTeachingStaff::select(['name','user_name_id','StaffCode'])->where('Dept','!=','CIVIL')->get();

$combinedData = $table1Data->concat($table2Data);


// dd($combinedData);
        return view('admin.examcellCoordinators.create',compact('combinedData'));
    }

    public function store(Request $request){
        // dd($request->staffName);
        if($request->staffName > 0){
            foreach($request->staffName as $data){
                $existingRecord = ExamCellCoordinator::where('user_name_id', $data)
                    ->where('status', 0)
                    ->first();

                if (!$existingRecord) {
                    ExamCellCoordinator::create([
                        'user_name_id' => $data,
                        'exam_type' => 1,
                        'status' => 0,
                    ]);
                }
            }

        }
        return redirect()->route('admin.exam_cell_coordinators.index');

    }

    public function remove($id){
        // dd($id);
        $delete = ExamCellCoordinator::find($id);

        if ($delete) {
            $delete->delete();
            // return redirect()->route('admin.Exam-time-table.index')->with('success', 'Exam timetable successfully deleted.');
        }
        return redirect()->route('admin.exam_cell_coordinators.index');

    }

    public function show($request)
    {
        if (is_numeric($request)) {

            $staff = TeachingStaff::where('user_name_id', $request)->first();
            if(!$staff){
            $staff = NonTeachingStaff::where('user_name_id', $request)->first();
            }

            $name = '';
            $who = 'tech';

        }else{
            $staff = '';
        }
        // dd($staff);

        if ($staff == null || $staff == '') {
            return back()->withErrors('Staff Not Found');
        } else {

            $user_name_id = $staff->user_name_id;
            $document = Document::where(['nameofuser_id' => $user_name_id, 'fileName' => 'Profile'])->get();

            if ($document->count() <= 0) {
                $staff->filePath = '';
            } else {
                $staff->filePath = $document[0]->filePath;
            }

            $personal = PersonalDetail::where(['user_name_id' => $user_name_id])->get();

            $blood_groups = BloodGroup::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

            $mother_tongues = MotherTongue::pluck('mother_tongue', 'id')->prepend(trans('global.pleaseSelect'), '');

            $religions = Religion::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

            $communities = Community::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

            if ($personal->count() <= 0) {
                $personal->id = '';
                $personal->age = '';
                $personal->dob = '';
                $personal->email = '';
                $personal->mobile_number = '';
                $personal->aadhar_number = '';
                $personal->state = '';
                $personal->country = '';
                $personal->user_name_id = '';
                $personal->blood_group_id = '';
                $personal->blood_group = '';
                $personal->mother_tongue_id = '';
                $personal->mother_tongue = '';
                $personal->community_id = '';
                $personal->community = '';
                $personal->religion_id = '';
                $personal->religion = '';
                $personal->father_name = '';
                $personal->last_name = '';
                $personal->spouse_name = '';
                $personal->StaffCode = '';
                $personal->BiometricID = '';
                $personal->AICTE = '';
                $personal->PanNo = '';
                $personal->PassportNo = '';
                $personal->COECode = '';
                $personal->emergency_contact_no = '';
                $personal->known_languages = '';
                $personal->au_card_no = '';
                $personal->first_name = '';
                $personal->total_experience = '';
                $personal->employment_type = '';
                $personal->employment_status = '';
                $personal->rit_club_incharge = '';
                $personal->future_tech_membership = '';
                $personal->future_tech_membership_type = '';
                $personal->known_languages = '';
                $personal->emergency_contact_no = '';
                $personal->gender = '';
                $staff->gender = $personal->gender;
                $detail = $personal;

            } else {

                $known_languages = unserialize($personal[0]->known_languages);

                $personal[0]->first_name = $personal[0]->name;
                $personal[0]->blood_group = $blood_groups;
                $personal[0]->mother_tongue = $mother_tongues;
                $personal[0]->community = $communities;
                $personal[0]->religion = $religions;
                $personal[0]->known_languages = $known_languages;
                // $personal[0]->BiometricID = $staff->BiometricID;
                // $personal[0]->gender = $staff->gender;
                $personal[0]->known_languages = $known_languages;
                $staff->gender = $personal[0]->gender;

                $detail = $personal[0];

            }

            $education_types = EducationType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

            $medium = MediumofStudied::pluck('medium', 'id')->prepend(trans('global.pleaseSelect'), '');

            $education_details = EducationalDetail::with(['education_type', 'medium'])->where(['user_name_id' => $user_name_id])->get();

            if ($education_details->count() <= 0) {

                $education_details->education_types = $education_types;

                $education_details->medium = $medium;

                $education_list = [];

            } else {

                for ($i = 0; $i < count($education_details); $i++) {
                    $education_details[$i]->education_types = $education_types;

                    $education_details[$i]->medium = $medium;

                }

                $education_list = $education_details;

            }

            $phd_details = PhdDetail::where(['user_name_id' => $user_name_id, 'status' => 1])->get();

            if ($phd_details->count() <= 0) {
                $phd_list = [];
            } else {
                $phd_list = $phd_details;
            }

            $experience_details = ExperienceDetail::where(['user_name_id' => $user_name_id])->get();

            if ($experience_details->count() <= 0) {
                $experience_list = [];
            } else {
                $experience_list = $experience_details;
            }

            $address_details = Address::where(['name_id' => $user_name_id, 'status' => 1])->get();

            if ($address_details->count() <= 0) {
                $address_list = [];
            } else {
                $address_list = $address_details;
            }

            $bank_details = BankAccountDetail::where(['user_name_id' => $user_name_id, 'status' => 1])->get();
            if ($bank_details->count() <= 0) {
                $bank_list = [];
            } else {
                $bank_list = $bank_details;
            }

            $salary_details = StaffSalary::where(['user_name_id' => $user_name_id])->get();
            if ($salary_details->count() <= 0) {
                $salary_list = [];
            } else {
                $salary_list = $salary_details;
            }

            $leave_types = LeaveType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

            $leave_details = HrmRequestLeaf::where(['user_id' => $user_name_id, 'status' => 'Approved'])->get();

            if ($leave_details->count() <= 0) {

                $leave_list = [];
            } else {
                for ($i = 0; $i < count($leave_details); $i++) {

                    $leave_details[$i]->leave_types = $leave_types;

                }

                $leave_list = $leave_details;
            }

            // $conference_details = AddConference::where(['user_name_id' => $user_name_id])->get();

            // if ($conference_details->count() <= 0) {
            $conference_list = [];
            // } else {
            //     $conference_list = $conference_details;
            // }

            $exam_types = Examstaff::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

            $exam_details = EntranceExam::where(['name_id' => $user_name_id, 'status' => 1])->get();

            if ($exam_details->count() <= 0) {
                $exam_details->exam_types = $exam_types;
                $exam_list = [];
            } else {
                // $exam_details[0]['exam_types'] = $exam_types;
                for ($i = 0; $i < count($exam_details); $i++) {

                    $exam_details[$i]->exam_types = $exam_types;

                }
                $exam_list = $exam_details;

            }

            // $guest_lecture = GuestLecture::where(['user_name_id' => $user_name_id])->get();

            // if ($guest_lecture->count() <= 0) {
            $guest_lecture_list = [];
            // } else {
            //     $guest_lecture_list = $guest_lecture;
            // }

            $industrial_training = IndustrialTraining::where(['name_id' => $user_name_id, 'status' => 1])->get();

            if ($industrial_training->count() <= 0) {

                $industrial_training_list = [];
            } else {
                $industrial_training_list = $industrial_training;
            }

            $intern_details = Intern::where(['name_id' => $user_name_id, 'status' => 1])->get();
            if ($intern_details->count() <= 0) {

                $intern_details_list = [];
            } else {
                $intern_details_list = $intern_details;
            }

            $indus_exp_details = IndustrialExperience::where(['user_name_id' => $user_name_id, 'status' => 1])->get();
            if ($indus_exp_details->count() <= 0) {

                $indus_exp_list = [];
            } else {
                $indus_exp_list = $indus_exp_details;
            }

            $iv_details = Iv::where(['name_id' => $user_name_id, 'status' => 1])->get();
            if ($iv_details->count() <= 0) {

                $iv_details_list = [];
            } else {
                $iv_details_list = $iv_details;
            }

            $online_course = OnlineCourse::where(['user_name_id' => $user_name_id, 'status' => 1])->get();
            if ($online_course->count() <= 0) {

                $online_course_list = [];
            } else {
                $online_course_list = $online_course;
            }

            $document = Document::where(['nameofuser_id' => $user_name_id, 'status' => 1])->get();
            if ($document->count() <= 0) {

                $document_list = [];
            } else {
                $document_list = $document;
            }

            // $seminar_details = Seminar::where(['user_name_id' => $user_name_id])->get();

            // if ($seminar_details->count() <= 0) {

            $seminar_details_list = [];
            // } else {
            //     $seminar_details_list = $seminar_details;
            // }

            $sabotical_details = Sabotical::where(['name_id' => $user_name_id, 'status' => 1])->get();

            if ($sabotical_details->count() <= 0) {

                $sabotical_details_list = [];
            } else {
                $sabotical_details_list = $sabotical_details;
            }

            $sponser_details = Sponser::where(['user_name_id' => $user_name_id, 'status' => 1])->get();

            if ($sponser_details->count() <= 0) {

                $sponser_details_list = [];
            } else {
                $sponser_details_list = $sponser_details;
            }

            $sttp_details = Sttp::where(['name_id' => $user_name_id, 'status' => 1])->get();
            if ($sttp_details->count() <= 0) {

                $sttp_details_list = [];
            } else {
                $sttp_details_list = $sttp_details;
            }

            $workshop_details = Workshop::where(['user_name_id' => $user_name_id])->get();
            if ($workshop_details->count() <= 0) {

                $workshop_details_list = [];
            } else {
                $workshop_details_list = $workshop_details;
            }
            $patent_details = Patent::where(['name_id' => $user_name_id, 'status' => 1])->get();
            if ($patent_details->count() <= 0) {

                $patent_details_list = [];
            } else {
                $patent_details_list = $patent_details;
            }

            $award_details = Award::where(['user_name_id' => $user_name_id, 'status' => 1])->get();
            if ($award_details->count() <= 0) {

                $award_details_list = [];
            } else {
                $award_details_list = $award_details;
            }

            $event = Events::pluck('event', 'id')->prepend(trans('global.pleaseSelect'), '');
            $event_organized_details = EventOrganized::where(['user_name_id' => $user_name_id, 'status' => 1])->get();

            if ($event_organized_details->count() <= 0) {

                $event_organized_details_list = [];

            } else {

                for ($i = 0; $i < count($event_organized_details); $i++) {

                    $event_organized_details[$i]->event = $event;

                }

                $event_organized_details_list = $event_organized_details;
            }

            $event_participation_details = EventParticipation::where(['user_name_id' => $user_name_id, 'status' => 1])->get();

            if ($event_participation_details->count() <= 0) {

                $event_participation_details_list = [];

            } else {

                for ($i = 0; $i < count($event_participation_details); $i++) {

                    $event_participation_details[$i]->event = $event;

                }

                $event_participation_details_list = $event_participation_details;
            }

            $publication_details = PublicationDetail::where(['user_name_id' => $user_name_id, 'status' => 1])->get();
            if ($publication_details->count() <= 0) {

                $publication_details_list = [];
            } else {
                $publication_details_list = $publication_details;
            }
            $permissionrequest = PermissionRequest::where(['user_name_id' => $user_name_id, 'status' => 1])->get();
            if ($permissionrequest->count() <= 0) {

                $permissionrequest_list = [];
            } else {
                $permissionrequest_list = $permissionrequest;
            }

            $promotiondetails = PromotionDetails::where(['user_name_id' => $user_name_id, 'status' => 1])->get();
            // dd($promotiondetails);
            if ($promotiondetails->count() <= 0) {

                $promotiondetails_list = [];
            } else {
                $promotiondetails_list = $promotiondetails;
            }
            $roles = Role::pluck('title', 'id');
            $first_entry = 'data';

            if (is_numeric($request)) {
                return view('admin.teachingStaffs.staffshow', compact('staff', 'detail', 'phd_list', 'education_types', 'education_list', 'experience_list', 'address_list', 'bank_list', 'salary_list', 'leave_list', 'conference_list', 'exam_list', 'guest_lecture_list', 'industrial_training_list', 'intern_details_list', 'indus_exp_list', 'iv_details_list', 'online_course_list', 'document_list', 'seminar_details_list', 'sabotical_details_list', 'sponser_details_list', 'sttp_details_list', 'workshop_details_list', 'patent_details_list', 'award_details_list', 'event_organized_details_list', 'event_participation_details_list', 'publication_details_list', 'permissionrequest_list', 'promotiondetails_list', 'roles'));
            } else {
                // dd($who);
                if ($who == 'tech') {
                    return view('admin.edges.staff', compact('first_entry', 'name', 'staff', 'detail', 'phd_list', 'education_types', 'education_list', 'experience_list', 'address_list', 'bank_list', 'salary_list', 'leave_list', 'conference_list', 'exam_list', 'guest_lecture_list', 'industrial_training_list', 'intern_details_list', 'indus_exp_list', 'iv_details_list', 'online_course_list', 'document_list', 'seminar_details_list', 'sabotical_details_list', 'sponser_details_list', 'sttp_details_list', 'workshop_details_list', 'patent_details_list', 'award_details_list', 'event_organized_details_list', 'event_participation_details_list', 'publication_details_list', 'permissionrequest_list', 'promotiondetails_list', 'roles'));
                } elseif ($who == 'non_tech') {
                    return view('admin.edges.staff', compact('first_entry', 'name', 'staff', 'detail', 'phd_list', 'education_types', 'education_list', 'experience_list', 'address_list', 'bank_list', 'salary_list', 'leave_list', 'conference_list', 'exam_list', 'guest_lecture_list', 'industrial_training_list', 'intern_details_list', 'indus_exp_list', 'iv_details_list', 'online_course_list', 'document_list', 'seminar_details_list', 'sabotical_details_list', 'sponser_details_list', 'sttp_details_list', 'workshop_details_list', 'patent_details_list', 'award_details_list', 'event_organized_details_list', 'event_participation_details_list', 'publication_details_list', 'permissionrequest_list', 'promotiondetails_list', 'roles'));
                }
            }
        }
    }
}