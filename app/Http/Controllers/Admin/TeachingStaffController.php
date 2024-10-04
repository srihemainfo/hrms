<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTeachingStaffRequest;
use App\Http\Requests\StoreTeachingStaffRequest;
use App\Http\Requests\UpdateTeachingStaffRequest;
use App\Models\Address;
use App\Models\Award;
use App\Models\BankAccountDetail;
use App\Models\BloodGroup;
use App\Models\Community;
use App\Models\Document;
use App\Models\EducationalDetail;
use App\Models\EducationType;
use App\Models\EntranceExam;
use App\Models\EventOrganized;
use App\Models\EventParticipation;
use App\Models\Events;
use App\Models\Examstaff;
use App\Models\ExperienceDetail;
use App\Models\HrmRequestLeaf;
use App\Models\IndustrialExperience;
use App\Models\IndustrialTraining;
use App\Models\Intern;
use App\Models\Iv;
use App\Models\LeaveType;
use App\Models\MediumofStudied;
use App\Models\MotherTongue;
use App\Models\MsBiometric;
use App\Models\NonTeachingStaff;
use App\Models\OnlineCourse;
use App\Models\Patent;
use App\Models\PermissionRequest;
use App\Models\PersonalDetail;
use App\Models\PhdDetail;
use App\Models\PromotionDetails;
use App\Models\PublicationDetail;
use App\Models\Religion;
use App\Models\Role;
use App\Models\Sabotical;
use App\Models\ShiftModel;
use App\Models\Sponser;
use App\Models\Staffs;
use App\Models\StaffSalary;
use App\Models\Sttp;
use App\Models\TeachingStaff;
use App\Models\TeachingType;
use App\Models\ToolsDepartment;
use App\Models\User;
use App\Models\Workshop;
use Carbon\Carbon;
use DateTime;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TeachingStaffController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
<<<<<<< HEAD
        // abort_if(Gate::denies('teaching_staff_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // $a = MsBiometric::get();
        // $b = "SELECT * FROM AttendanceLogs";
        // $a = DB::connection('sqlsrv')->getPdo();
        // dd($a);
=======

>>>>>>> 6563285674506c09c4794a263e688088e7e74606
        if ($request->ajax()) {

            $query = DB::table('teaching_staffs')
                ->whereNull('teaching_staffs.deleted_at')
                ->leftJoin('role_user', 'role_user.user_id', '=', 'teaching_staffs.user_name_id')
                ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
                // ->leftJoin('shift', 'shift.id', '=', 'teaching_staffs.shift_id')
                // ->leftJoin('personal_details', 'personal_details.user_name_id', '=', 'teaching_staffs.user_name_id')
                // ->where(function ($query) {
                //     $query->where('personal_details.employment_status', 'Active')
                //         ->orWhereNull('personal_details.employment_status');
                // })
                // ->whereNotNull('personal_details.StaffCode')
                // ->orderBy('personal_details.user_name_id', 'asc')
                // ->leftJoin('teaching_types', 'teaching_types.id', '=', 'roles.type_id')
                ->select('teaching_staffs.user_name_id', 'teaching_staffs.name', 'teaching_staffs.past_leave_access',  'teaching_staffs.Designation', 'teaching_staffs.Dept', 'roles.title')
                ->get();



            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');



            $table->editColumn('actions', function ($row) {
                $row->id = $row->user_name_id;

                $viewGate = 'teaching_staff_show';
                $editGate = 'teaching_staff_edit';
                $deleteGate = 'teaching_staff_delete';
                $crudRoutePart = 'teaching-staffs';

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
                return $row->user_name_id ? $row->user_name_id : '';
            });

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            // $table->editColumn('StaffCode', function ($row) {
            //     return $row->StaffCode ? $row->StaffCode : '';
            // });
            $table->editColumn('Dept', function ($row) {
                return $row->Dept ? $row->Dept : '';
            });
            $table->editColumn('Designation', function ($row) {
                return $row->Designation ? $row->Designation : '';
            });
            // $table->editColumn('teach_type', function ($row) {
            //     return $row->teach_type ? $row->teach_type : '';
            // });
            // $table->editColumn('shift', function ($row) {
            //     return $row->shift_name ? $row->shift_name : '';
            // });

            $table->editColumn('past_leave_access', function ($row) {
                $accessGate2 = 'Past_Leave_Permission_Access';
                return view('partials.controlBtn', compact('accessGate2', 'row'));
            });

            // $table->editColumn('active_status', function ($row) {
            //     $status = PersonalDetail::where('user_name_id', $row->user_name_id)->select('employment_status')->first();

            //     $employmentStatus = $status->employment_status ? $status->employment_status : null;
            //     if ($employmentStatus != '') {
            //         if ($employmentStatus == 'Active') {
            //             return 'Active';
            //         } else {
            //             return 'Inactive';
            //         }
            //     } else {
            //         return '';
            //     }
            // });

            $table->rawColumns(['actions', 'placeholder', 'past_leave_access']);
            return $table->make(true);

        }

        return view('admin.teachingStaffs.index');
    }

    public function create()
    {
        // abort_if(Gate::denies('teaching_staff_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $department = ToolsDepartment::whereNotIn('name', ['ADMIN', 'CIVIL'])->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $teaching_type = TeachingType::whereIn('id', [1, 2])->pluck('name', 'id');
        $shift = ShiftModel::pluck('Name', 'id');

        $working_as = Role::whereIn('type_id', [1, 3])->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.teachingStaffs.create', compact('shift','department', 'working_as', 'teaching_type'));
    }

    public function store(StoreTeachingStaffRequest $request)
    {
        // dd($request->Dept);
        $this->validate($request, [
            'name' => 'required|max:120|no_special_characters',
            'email' => 'required|email|unique:users|custom_email',
            'phone' => ['required', 'digits:10'],
            'StaffCode' => 'required|string|unique:users,employID',
            'Dept' => 'required',
            'Designation' => 'required',
            'doj' => 'required',
            'shift' => 'nullable',
            'last_name' => 'required|no_special_characters',
        ]);

        $firstname = $request->input('name');
        $firstname = strtoupper($firstname);
        $lastname = $request->input('last_name');
        $lastname = strtoupper($lastname);

        $fullname = $firstname . ' ' . $lastname;
        $fullname = strtoupper($fullname);

        $user = new User();
        $user->name = $fullname;
        $user->dept = $request->Dept;
        $user->employID = $request->StaffCode;
        $user->email = $request->email;
        $user->password = bcrypt($request->phone);
        $user->save();
        // $user->roles()->sync([$request->role]);

        $role_type_id = 0;
        $Designation = '';
        if ($request->has('Designation')) {

            $admin = Role::select('id', 'type_id', 'title')->where('id', $request->input('Designation'))->first();

            $role_type_id = $admin->type_id;
            $Designation = $admin->title;
            $user->roles()->sync($request->input('roles', $admin->id));
        }
        $casual_leave =  0;
        $personal_permission = 0;
        if($request->doj != null || $request->doj != ''){
            $yearMonth = substr($request->doj, 0, 7);
            $explode = explode('-', $request->doj);

            $year = (int)$explode[0];
            $month = (int)$explode[1];
            $day = (int)$explode[2];

            // $last_month = date('Y-m-26', strtotime('last month'));
            // $last_month_26 = date('Y-m-26', strtotime($last_month));
            // dd( $last_month);

            // $date = substr($request->doj, 8, 9);
            $casual_leave =  0;

            if ($yearMonth == date('Y-m') && $day  == 1) {

                $casual_leave =  1;
            } elseif (($year == (int)date('Y') || $year == (int)date('Y') - 1) && (($year == (int)date('Y') - 1 && $month == (int)date('m', strtotime('last month'))) || ($year == (int)date('Y') && $month < (int)date('m'))) && $day >= 26) {

                $casual_leave =  1;
            }

            $personal_permission = 0;
            if ($yearMonth == date('Y-m') && ($day  > 1 && $day <= 15)) {
                $personal_permission =  1;
            } elseif (($year == (int)date('Y') || $year == (int)date('Y') - 1) &&
                (($year == (int)date('Y') - 1 && $month == (int)date('m', strtotime('last month'))) || ($year == (int)date('Y') && $month < (int)date('m'))) &&
                $day >= 26
            ) {
                $personal_permission =  2;
            }
        }


        // dd($casual_leave);


        $staffCreate = new TeachingStaff;
        $staffCreate->name = $fullname;
        $staffCreate->last_name = $lastname;
        $staffCreate->StaffCode = $request->input('StaffCode');
        $staffCreate->Designation = $Designation;
        $staffCreate->Dept = $request->input('Dept');
        $staffCreate->role_type =  $role_type_id;
        $staffCreate->shift_id =  $request->input('shift');
        $staffCreate->casual_leave =  $casual_leave;
        $staffCreate->personal_permission = $personal_permission;
        $staffCreate->ContactNo = $request->input('phone');
        $staffCreate->EmailIDOffical = $request->input('email');
        $staffCreate->user_name_id = $user->id;
        $staffCreate->save();


        $personalDetails = new PersonalDetail();
        $personalDetails->name = $firstname;
        $personalDetails->last_name = $lastname;
        $personalDetails->email = $request->input('email');
        $personalDetails->StaffCode = $request->input('StaffCode');
        $personalDetails->mobile_number = $request->input('phone');
        $personalDetails->user_name_id = $user->id;
        $personalDetails->save();

        $experience_details = new ExperienceDetail();
        $experience_details->user_name_id = $user->id;
        $experience_details->doj = $request->doj;
        $experience_details->save();





        // $teachingStaff = TeachingStaff::create($request->all());
        // if ($request->doj == date('Y-m') . '-01') {

        //     $staffCreate = new TeachingStaff;
        //     $staffCreate->name = $fullname;
        //     $staffCreate->last_name = $lastname;
        //     $staffCreate->StaffCode = $request->input('StaffCode');
        //     $staffCreate->Designation = $Designation;
        //     $staffCreate->Dept = $request->input('Dept');
        //     $staffCreate->role_type =  $role_type_id;
        //     $staffCreate->casual_leave =  1;
        //     $staffCreate->ContactNo = $request->input('phone');
        //     $staffCreate->EmailIDOffical = $request->input('email');
        //     $staffCreate->user_name_id = $user->id;
        //     $staffCreate->save();


        //     $personalDetails = new PersonalDetail();
        //     $personalDetails->name = $firstname;
        //     $personalDetails->last_name = $lastname;
        //     $personalDetails->email = $request->input('email');
        //     $personalDetails->StaffCode = $request->input('StaffCode');
        //     $personalDetails->mobile_number = $request->input('phone');
        //     $personalDetails->user_name_id = $user->id;
        //     $personalDetails->save();
        // }


        return redirect()->route('admin.teaching-staffs.index');
    }

    public function clvalue($data)
    {
    }

    public function edit($request)
    {

        // abort_if(Gate::denies('teaching_staff_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request) {

            // $decodedParam = base64_decode($one, true);

            // if ($decodedParam === true) {
            //     dd($request);
            //     $req= base64_decode($request);
            // }else{
            //     dd($request);
            //     $req = $request;
            // }

            $query = TeachingStaff::where(['user_name_id' => $request])->get();
            $document = Document::where(['nameofuser_id' => $request, 'fileName' => 'Profile'])->get();
            $query_one = PersonalDetail::where(['user_name_id' => $request])->get();
        }

        if ($query->count() <= 0) {
            $query->user_name_id = $request;

            if ($document->count() <= 0) {
                $query->filePath = '';
            } else {
                $query->filePath = $document[0]->filePath;
            }

            if ($query_one->count() > 0) {
                $query->Gender = $query_one[0]->gender;
            }

            $staff = $query;
        } else {

            if ($document->count() <= 0) {
                $query[0]->filePath = '';
            } else {
                $query[0]->filePath = $document[0]->filePath;
            }

            if ($query_one->count() > 0) {
                $query[0]->Gender = $query_one[0]->gender;
            }

            $staff = $query[0];
        }

        $check = "entry";

        // dd($staff);

        return view('admin.StaffProfile.staff', compact('check', 'staff'));
    }

    public function update(UpdateTeachingStaffRequest $request, TeachingStaff $teachingStaff)
    {

        $teachingStaff->update($request->all());

        return redirect()->route('admin.teaching-staffs.index');
    }

    public function show($request)
    {
        // abort_if(Gate::denies('teaching_staff_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        //  dd($request);
        if (is_numeric($request)) {
            $staff = TeachingStaff::where('user_name_id', $request)->first();
            $name = '';
            $who = 'tech';
        } else if (filter_var($request, FILTER_SANITIZE_NUMBER_INT)) {
            $strpos = strpos($request, '(');
            // dd($strpos);
            if ($strpos < 5) {
                return back()->withErrors('Given Data Not Valid');
            }
            $explode = explode('(', $request);
            $staff_code = trim(substr($explode[1], 0, -1));

            $staff = TeachingStaff::where('StaffCode', $staff_code)->first();
            $who = 'tech';

            if (empty($staff)) {
                $staff = NonTeachingStaff::where('StaffCode', $staff_code)->first();
                $who = 'non_tech';
            }
            $name = $request;
        } else {
            $staff = '';
        }

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

    public function destroy($request)
    {
        // abort_if(Gate::denies('student_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $teaching_staff = TeachingStaff::where('user_name_id', $request)->delete();
        $personal = PersonalDetail::where('user_name_id', $request)->delete();
        $user = User::find($request)->delete();
        return back();
    }

    public function massDestroy(MassDestroyTeachingStaffRequest $request)
    {
        // dd($request);
        $teachingStaffs = TeachingStaff::find(request('ids'));

        foreach ($teachingStaffs as $teachingStaff) {
            $teachingStaff->delete();
            $user = User::find($teachingStaff->user_name_id);
            $user->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function past_leave_apply_access(Request $request)
    {

        if (isset($request->id) && $request->id != '' && isset($request->status) && $request->status != '') {
            $update_control = TeachingStaff::where(['user_name_id' => $request->id])->update([
                'past_leave_access' => (int) $request->status,
            ]);
            if ($request->status == '1') {
                $status = 'Enable';
                $update_control = TeachingStaff::where(['user_name_id' => $request->id])->select('past_leave_access')->first();
                $id = $update_control->past_leave_access;
            } else {
                $status = 'Disable';
                $update_control = TeachingStaff::where(['user_name_id' => $request->id])->select('past_leave_access')->first();
                $id = $update_control->past_leave_access;
            }
            if ($update_control) {
                return response()->json(['status' => true, 'id' => $id, 'data' => $status . 'd']);
            } else {
                return response()->json(['status' => false, 'id' => $id, 'data' => $status . ' Process Failed']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Technical Error']);
        }
    }

    public function Past_Leave_Access_check(Request $request)
    {
        if (isset($request->data)) {

            $staff = auth()->user()->id;
            $update_control = Staffs::where(['user_name_id' => $staff])->select('past_leave_access')->first();
            if ($update_control['past_leave_access'] == 1) {

                $given_Dates = $request->date;
                $given_Date = new \DateTime($given_Dates);
                $given_Date->setTime(0, 0, 0);
                $currentDate = new \DateTime();
                $currentDate->setTime(0, 0, 0);
                $startDateAllowed = new \DateTime('first day of previous month');
                $startDateAllowed->setDate($startDateAllowed->format('Y'), $startDateAllowed->format('m'), 26);
                $startDateAllowed->setTime(0, 0, 0);
                $endDateAllowed = new \DateTime('last day of this month');
                $endDateAllowed->setDate($endDateAllowed->format('Y'), $endDateAllowed->format('m'), 25);
                $endDateAllowed->setTime(0, 0, 0);
                $endDateAllowed2 = new \DateTime('last day of this month');
                $endDateAllowed2->setDate($endDateAllowed->format('Y'), $endDateAllowed->format('m'), 26);
                $endDateAllowed2->setTime(0, 0, 0);

                if ($currentDate > $given_Date) {

                    if ($startDateAllowed <= $given_Date) {

                        return response()->json(['status' => true]);
                    } else {
                        return response()->json(['status' => false]);
                    }
                } else if ($currentDate > $endDateAllowed) {
                    if ($endDateAllowed < $given_Date) {
                        return response()->json(['status' => true]);
                    } else {
                        return response()->json(['status' => false]);
                    }
                } else {
                    return response()->json(['status' => true]);
                }
            } else {
                return response()->json(['status' => false]);
            }
        }
    }

    public function rdStaffIndex(Request $request)
    {

        if ($request->ajax()) {
            $query = TeachingStaff::where('rd_staff', '1')->select('name', 'StaffCode', 'Designation', 'user_name_id', 'rd_staff')->get();

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->editColumn('StaffCode', function ($row) {
                return $row->StaffCode ? $row->StaffCode : '';
            });
            $table->editColumn('Designation', function ($row) {
                return $row->Designation ? $row->Designation : '';
            });
            $table->editColumn('remove', function ($row) {
                return $row->user_name_id ? $row->user_name_id : '';
            });

            $table->rawColumns(['placeholder']);
            return $table->make(true);
        }
        $staff = TeachingStaff::where('rd_staff', '0')->select('name', 'StaffCode', 'user_name_id')->get();
        return view('admin.teachingStaffs.rdindex', compact('staff'));
    }

    public function rdStaffStore(Request $request)
    {
        if (isset($request->staff) && $request->staff != null) {
            $update = TeachingStaff::where(['user_name_id' => $request->staff])->update([
                'rd_staff' => '1',
            ]);
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false, 'data' => 'Staff Not Found']);
        }
    }

    public function rdStaffRemove(Request $request)
    {
        if (isset($request->staff) && $request->staff != null) {
            $update = TeachingStaff::where(['user_name_id' => $request->staff])->update([
                'rd_staff' => '0',
            ]);
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false, 'data' => 'Staff Not Found']);
        }
    }
}
