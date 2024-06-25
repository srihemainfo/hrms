<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\Iv;
use App\Models\Role;
use App\Models\Sttp;
use App\Models\User;
use App\Models\Award;
use App\Models\Events;
use App\Models\Intern;
use App\Models\Patent;
use App\Models\Address;
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
use App\Models\MotherTongue;
use App\Models\OnlineCourse;
use Illuminate\Http\Request;
use App\Models\EducationType;
use App\Models\EventOrganized;
use App\Models\HrmRequestLeaf;
use App\Models\PersonalDetail;
use App\Models\MediumofStudied;
use App\Models\ToolsDepartment;
use App\Models\ExperienceDetail;
use App\Models\NonTeachingStaff;
use App\Models\PromotionDetails;
use App\Models\BankAccountDetail;
use App\Models\EducationalDetail;
use App\Models\PermissionRequest;
use App\Models\PublicationDetail;
use App\Models\EventParticipation;
use App\Models\IndustrialTraining;
use App\Http\Controllers\Controller;
use App\Models\IndustrialExperience;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;

use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\StoreNonTeachingStaffRequest;
use App\Http\Requests\UpdateNonTeachingStaffRequest;
use App\Http\Requests\MassDestroyNonTeachingStaffRequest;
use Illuminate\Support\Facades\DB;

class NonTeachingStaffController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('non_teaching_staff_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $status = PersonalDetail::where('employment_status','Active')->orWhereNull('employment_status')->whereNotNull('StaffCode')
        //     ->orderBy('user_name_id','asc')
        //     ->select('user_name_id')
        //     ->get();
        //     $userNameIds = $status->pluck('user_name_id');
        //     $staff_details = NonTeachingStaff::whereIn('user_name_id', $userNameIds)->select('id','name','Dept','StaffCode','Designation','past_leave_access','user_name_id')->get();
        //     dd($staff_details);


        if ($request->ajax()) {

            // $status = PersonalDetail::where('employment_status', 'Active')->orWhereNull('employment_status')->whereNotNull('StaffCode')
            //     ->orderBy('user_name_id', 'asc')
            //     ->select('user_name_id')
            //     ->get();
            // $userNameIds = $status->pluck('user_name_id');
            // $staff_details = NonTeachingStaff::whereIn('user_name_id', $userNameIds)->select('id', 'name', 'Dept', 'last_name', 'StaffCode', 'phone', 'email', 'Designation', 'past_leave_access', 'user_name_id')->get();
            // $query = NonTeachingStaff::with(['working_as'])->select(sprintf('%s.*', (new NonTeachingStaff)->table));

            $query = DB::table('non_teaching_staffs')
                ->whereNull('non_teaching_staffs.deleted_at')
                ->leftJoin('role_user', 'role_user.user_id', '=', 'non_teaching_staffs.user_name_id')
                ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
                ->leftJoin('personal_details', 'personal_details.user_name_id', '=', 'non_teaching_staffs.user_name_id')
                ->where(function ($query) {
                    $query->where('personal_details.employment_status', 'Active')
                        ->orWhereNull('personal_details.employment_status');
                })
                ->whereNotNull('personal_details.StaffCode')
                ->orderBy('personal_details.user_name_id', 'asc')
                ->leftJoin('teaching_types', 'teaching_types.id', '=', 'roles.type_id')
                ->select('non_teaching_staffs.user_name_id', 'non_teaching_staffs.name', 'non_teaching_staffs.email', 'non_teaching_staffs.past_leave_access', 'non_teaching_staffs.StaffCode', 'non_teaching_staffs.phone', 'non_teaching_staffs.Designation', 'roles.title', 'teaching_types.name as teach_type')
                ->get();

            // dd($query);

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');



            $table->editColumn('actions', function ($row) {
                $row->id = $row->user_name_id;
                $viewGate = 'non_teaching_staff_show';
                $editGate = 'non_teaching_staff_edit';
                $deleteGate = 'non_teaching_staff_delete';
                $crudRoutePart = 'non-teaching-staffs';

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

            $table->editColumn('user_name_id', function ($row) {
                return $row->user_name_id ? $row->user_name_id : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->addColumn('StaffCode', function ($row) {
                return $row->StaffCode ? $row->StaffCode : '';
            });

            $table->addColumn('Designation', function ($row) {
                return $row->Designation ? $row->Designation : '';
            });
            $table->addColumn('teach_type', function ($row) {
                return $row->teach_type ? $row->teach_type : '';
            });
            $table->addColumn('phone', function ($row) {
                return $row->phone ? $row->phone : '';
            });
            $table->addColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            $table->editColumn('past_leave_access', function ($row) {
                $accessGate2 = 'Past_Leave_Permission_Access';
                return view('partials.controlBtn', compact('accessGate2', 'row'));
            });
            $table->editColumn('active_status', function ($row) {
                $status = PersonalDetail::where('user_name_id', $row->user_name_id)->select('employment_status')->first();


                $employmentStatus = $status->employment_status ? $status->employment_status : Null;
                if ($employmentStatus != '') {
                    if ($employmentStatus == 'Active') {
                        return 'Active';
                    } else {
                        return 'Inactive';
                    }
                } else {
                    return '';
                }
            });
            //'working_as'

            $table->rawColumns(['actions', 'placeholder', 'past_leave_access']);

            return $table->make(true);
        }

        return view('admin.nonTeachingStaffs.index');
    }

    public function create()
    {
        abort_if(Gate::denies('non_teaching_staff_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $working_as = Role::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');
        $department = ToolsDepartment::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        // Array of titles to exclude
        $working_as = Role::whereIn('type_id', [2, 4, 5])->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');


        return view('admin.nonTeachingStaffs.create', compact('working_as', 'department'));
    }

    public function store(StoreNonTeachingStaffRequest $request)
    {
        // $nonTeachingStaff = NonTeachingStaff::create($request->all());
        $this->validate($request, [
            'name' => 'required|max:120|no_special_characters',
            'email' => 'required|custom_email|email|unique:users',
            'phone' => ['required', 'digits:10'],
            'StaffCode' => 'required|string|unique:users,employID',
            'Dept' => 'required',
            'Designation' => 'required',
            'doj' => 'required',
            'last_name' => 'required|max:120|no_special_characters',
        ]);
        // dd($request->input('Designation'));

        $firstname = $request->input('name');
        $firstname = strtoupper($firstname);
        $lastname = $request->input('last_name');
        $lastname = strtoupper($lastname);

        $fullname = $firstname . ' ' . $lastname;
        $fullname = strtoupper($fullname);

        // dd($fullname);
        $user = new User();
        $user->name = $fullname; // Set the name from request
        $user->email = $request->input('email'); // Set the email
        $user->dept = $request->Dept;
        $user->employID = $request->input('StaffCode');
        $user->password = bcrypt($request->input('phone'));
        $user->save();

        $role_type_id = 0;
        $Designation = '';
        if ($request->has('Designation')) {

            $admin = Role::select('id', 'type_id', 'title')->where('id', $request->input('Designation'))->first();

            $role_type_id = $admin->type_id;
            $Designation = $admin->title;
            $user->roles()->sync($request->input('roles', $admin->id));
        }
        $casual_leave = 0;
        $personal_permission = 0;
        if ($request->doj != null || $request->doj != '') {
            $yearMonth = substr($request->doj, 0, 7);
            $explode = explode('-', $request->doj);

            $year = (int) $explode[0];
            $month = (int) $explode[1];
            $day = (int) $explode[2];

            $casual_leave = 0;

            if ($yearMonth == date('Y-m') && $day == 1) {

                $casual_leave = 1;
            } elseif (($year == (int) date('Y') || $year == (int) date('Y') - 1) && (($year == (int) date('Y') - 1 && $month == (int) date('m', strtotime('last month'))) || ($year == (int) date('Y') && $month < (int) date('m'))) && $day >= 26) {

                $casual_leave = 1;
            }

            $personal_permission = 0;
            if ($yearMonth == date('Y-m') && ($day > 1 && $day <= 15)) {
                $personal_permission = 1;
            } elseif (
                ($year == (int) date('Y') || $year == (int) date('Y') - 1) &&
                (($year == (int) date('Y') - 1 && $month == (int) date('m', strtotime('last month'))) || ($year == (int) date('Y') && $month < (int) date('m'))) &&
                $day >= 26
            ) {
                $personal_permission = 2;
            }
        }

        // dd($admin->id);
        $nonTeachingStaff = new NonTeachingStaff();
        $nonTeachingStaff->name = $fullname;
        $nonTeachingStaff->last_name = $lastname;
        $nonTeachingStaff->StaffCode = $request->input('StaffCode');
        $nonTeachingStaff->Designation = $Designation;
        $nonTeachingStaff->Dept = $request->input('Dept');
        $nonTeachingStaff->role_type = $role_type_id;
        $nonTeachingStaff->casual_leave = $casual_leave;
        $nonTeachingStaff->personal_permission = $personal_permission;
        $nonTeachingStaff->phone = $request->input('phone');
        $nonTeachingStaff->email = $request->input('email');
        $nonTeachingStaff->user_name_id = $user->id;
        $nonTeachingStaff->save();

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

        return redirect()->route('admin.non-teaching-staffs.index');
    }

    public function edit($request)
    {
        // abort_if(Gate::denies('non_teaching_staff_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $working_as = Role::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        // $nonTeachingStaff->load('working_as');
        if ($request) {


            $query = NonTeachingStaff::where(['user_name_id' => $request])->get();
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


        $check = "entry1";
        // dd($request);

        return view('admin.StaffProfile(non_tech).staff', compact('check', 'staff'));
    }

    public function update(UpdateNonTeachingStaffRequest $request, NonTeachingStaff $nonTeachingStaff)
    {
        $nonTeachingStaff->update($request->all());

        return redirect()->route('admin.non-teaching-staffs.index');
    }

    public function show($request)
    {
        if (is_numeric($request)) {
            // dd($request);
            $staff = NonTeachingStaff::where('user_name_id', $request)->first();
            $name = '';
        } else if (filter_var($request, FILTER_SANITIZE_NUMBER_INT)) {
            // dd($request);
            $staff = NonTeachingStaff::where('StaffCode', $request)->first();
            $name = $request;
        } else {
            $staff = NonTeachingStaff::where('name', $request)->first();
            $name = $request;
        }

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

        $leave_details = HrmRequestLeaf::where(['user_id' => $user_name_id, 'status' => 1])->get();

        if ($leave_details->count() <= 0) {

            $leave_list = [];
        } else {
            for ($i = 0; $i < count($leave_details); $i++) {

                $leave_details[$i]->leave_types = $leave_types;
            }

            $leave_list = $leave_details;
        }

        $exam_types = Examstaff::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $exam_details = EntranceExam::where(['name_id' => $user_name_id, 'status' => 1])->get();

        if ($exam_details->count() <= 0) {
            $exam_details->exam_types = $exam_types;
            $exam_list = [];
        } else {

            for ($i = 0; $i < count($exam_details); $i++) {

                $exam_details[$i]->exam_types = $exam_types;
            }
            $exam_list = $exam_details;
        }

        $document = Document::where(['nameofuser_id' => $user_name_id, 'status' => 1])->get();
        if ($document->count() <= 0) {

            $document_list = [];
        } else {
            $document_list = $document;
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

        $first_entry = 'data';


        return view('admin.nonTeachingStaffs.staffshow', compact('name', 'staff', 'detail', 'education_types', 'education_list', 'experience_list', 'address_list', 'bank_list', 'salary_list', 'leave_list', 'document_list', 'permissionrequest_list', 'promotiondetails_list'));
    }

    public function destroy($request)
    {
        // dd($request);
        abort_if(Gate::denies('non_teaching_staff_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $non_teaching_staff = NonTeachingStaff::where('user_name_id', $request)->delete();
        $personal = PersonalDetail::where('user_name_id', $request)->delete();
        $user = User::find($request)->delete();
        return back();
    }

    public function massDestroy(MassDestroyNonTeachingStaffRequest $request)
    {

        $nonTeachingStaffs = NonTeachingStaff::find(request('ids'));

        foreach ($nonTeachingStaffs as $nonTeachingStaff) {
            $nonTeachingStaff->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function past_leave_apply_Non_Teaching_access(Request $request)
    {

        if (isset($request->id) && $request->id != '' && isset($request->status) && $request->status != '') {
            $update_control = NonTeachingStaff::where(['user_name_id' => $request->id])->update([
                'past_leave_access' => (int) $request->status,
            ]);
            if ($request->status == '1') {
                $status = 'Enable';
                $update_control = NonTeachingStaff::where(['user_name_id' => $request->id])->select('past_leave_access')->first();
                $id = $update_control->past_leave_access;
            } else {
                $status = 'Disable';
                $update_control = NonTeachingStaff::where(['user_name_id' => $request->id])->select('past_leave_access')->first();
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

    public function Past_Leave_Non_Teaching_Access__check(Request $request)
    {
        if (isset($request->data)) {


            $staff = auth()->user()->id;
            $update_control = NonTeachingStaff::where(['user_name_id' => $staff])->select('past_leave_access')->first();
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
}
