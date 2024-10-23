<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Models\Address;
use App\Models\BankAccountDetail;
use App\Models\BloodGroup;
use App\Models\Community;
use App\Models\Designation;
use App\Models\Document;
use App\Models\EducationalDetail;
use App\Models\EducationType;
use Illuminate\Support\Facades\Gate;
use App\Models\ExperienceDetail;
use App\Models\MediumofStudied;
use App\Models\MotherTongue;
use App\Models\Nationality;
use App\Models\PersonalDetail;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Religion;
use App\Models\Role;
use App\Models\Staffs;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StaffsController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('staff_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DB::table('staffs')
                ->whereNull('staffs.deleted_at')
                ->leftJoin('roles', 'roles.id', '=', 'staffs.role_id')
                ->leftJoin('designation', 'designation.id', '=', 'staffs.designation_id')
                ->leftJoin('personal_details', 'personal_details.user_name_id', '=', 'staffs.user_name_id')
                ->where(function ($query) {
                    $query->where('personal_details.employment_status', 'Active')
                        ->orWhereNull('personal_details.employment_status');
                })
                ->orderBy('personal_details.user_name_id', 'asc')
                ->select('staffs.id', 'staffs.email', 'staffs.phone_number', 'staffs.status', 'staffs.edit_access', 'staffs.name', 'staffs.user_name_id', 'staffs.employee_id', 'roles.title as roled', 'designation.name as des')
                ->get();

            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $row->id = $row->user_name_id;

                $viewGate = 'staff_show';
                $editGate = 'staff_edit';
                $deleteGate = 'staff_delete';
                $crudRoutePart = 'staffs';

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
            $table->editColumn('employee_id', function ($row) {
                return $row->employee_id ? $row->employee_id : '';
            });

            $table->editColumn('title', function ($row) {
                return $row->roled ? $row->roled : '';
            });

            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });

            $table->editColumn('phone_number', function ($row) {
                return $row->phone_number ? $row->phone_number : '';
            });

            $table->editColumn('designation', function ($row) {
                return $row->des ? $row->des : '';
            });

            $table->editColumn('edit_access', function ($row) {
                return $row->edit_access ? $row->edit_access : '';
            });

            $table->editColumn('active_status', function ($row) {
                $status = PersonalDetail::where('user_name_id', $row->user_name_id)->select('employment_status')->first();

                $employmentStatus = $status ? $status->employment_status : null;
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

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $role = Role::pluck('title', 'id');

        return view('admin.staffs.index', compact('role'));
    }

    public function edit_access(Request $request)
    {
        // dd($request->id);
        $staffs = Staffs::where('user_name_id', $request->id)->update([
            'edit_access' => $request->edit_access,
        ]);

        return response()->json(['status' => 'success', 'data' => 'Edit Request Updated Successfully']);
    }

    public function destroy($request)
    {


        abort_if(Gate::denies('staff_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $teaching_staff = Staffs::where('user_name_id', $request)->delete();
        $personal = PersonalDetail::where('user_name_id', $request)->delete();
        $user = User::find($request)->delete();
        return back();
    }

    public function show($request)
    {
        abort_if(Gate::denies('staff_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        //  dd($request);
        if (is_numeric($request)) {
            $staff = Staffs::where('user_name_id', $request)->first();
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

            $name = $request;
        } else {
            $staff = '';
        }

        if ($staff == null || $staff == '') {
            return back()->withErrors('Staff Not Found');
        } else {

            $user_name_id = $staff->user_name_id;
            // $document = Document::where(['nameofuser_id' => $user_name_id, 'fileName' => 'Profile'])->get();

            // if ($document->count() <= 0) {
            //     $staff->filePath = '';
            // } else {
            //     $staff->filePath = $document[0]->filePath;
            // }

            $personal = PersonalDetail::where(['user_name_id' => $user_name_id])->get();
            $blood_groups = BloodGroup::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
            $roles = Role::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');
            $designations = Designation::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
            $religions = Religion::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
            $states = State::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
            $nationalities = Nationality::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
            $communities = Community::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
            $mother_tongues = MotherTongue::pluck('mother_tongue', 'id')->prepend(trans('global.pleaseSelect'), '');

            if ($personal->count() <= 0) {
                $personal->id = '';
                $personal->age = '';
                $personal->dob = '';
                $personal->email = '';
                $personal->phone_number = '';
                $personal->aadhar_number = '';
                $personal->state = '';
                $personal->state_id = '';
                $personal->country = '';
                $personal->employee_id = '';
                $personal->user_name_id = '';
                $personal->blood_group_id = '';
                $personal->blood_group = '';
                $personal->designation_id = '';
                $personal->mother_tongue_id = '';
                $personal->mother_tongue = '';
                $personal->community_id = '';
                $personal->community = '';
                $personal->marital_status = '';
                $personal->religion_id = '';
                $personal->nationality_id = '';
                $personal->religion = '';
                $personal->role_id = '';
                $personal->father_name = '';
                $personal->last_name = '';
                $personal->spouse_name = '';
                $personal->employee_id = '';
                $personal->BiometricID = '';
                $personal->AICTE = '';
                $personal->pan_number = '';
                $personal->PassportNo = '';
                $personal->emergency_contact_no = '';
                $personal->known_languages = '';
                $personal->au_card_no = '';
                $personal->first_name = '';
                $personal->name = '';
                $personal->total_experience = '';
                $personal->employment_type = '';
                $personal->employment_status = '';
                $personal->rit_club_incharge = '';
                $personal->future_tech_membership = '';
                $personal->future_tech_membership_type = '';
                $personal->known_languages = '';
                $personal->gender = '';
                $staff->gender = $personal->gender;
                $detail = $personal;
            } else {

                $known_languages = unserialize($personal[0]->known_languages);

                $personal[0]->name = $personal[0]->name;
                $personal[0]->blood_group = $blood_groups;
                $personal[0]->role = $roles;
                $personal[0]->designation = $designations;
                $personal[0]->nationality = $nationalities;
                $personal[0]->state = $states;
                $personal[0]->mother_tongue = $mother_tongues;
                $personal[0]->community = $communities;
                $personal[0]->religion = $religions;
                // $personal[0]->known_languages = $known_languages;
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

            // $phd_details = PhdDetail::where(['user_name_id' => $user_name_id, 'status' => 1])->get();

            // if ($phd_details->count() <= 0) {
            //     $phd_list = [];
            // } else {
            //     $phd_list = $phd_details;
            // }

            $experience_details = ExperienceDetail::where(['user_name_id' => $user_name_id])->get();

            if ($experience_details->count() <= 0) {
                $experience_list = [];
            } else {
                $experience_list = $experience_details;
            }

            // $address_details = Address::where(['name_id' => $user_name_id, 'status' => 1])->get();

            // if ($address_details->count() <= 0) {
            //     $address_list = [];
            // } else {
            //     $address_list = $address_details;
            // }

            // $bank_details = BankAccountDetail::where(['user_name_id' => $user_name_id, 'status' => 1])->get();
            // if ($bank_details->count() <= 0) {
            //     $bank_list = [];
            // } else {
            //     $bank_list = $bank_details;
            // }

            // $salary_details = StaffSalary::where(['user_name_id' => $user_name_id])->get();
            // if ($salary_details->count() <= 0) {
            //     $salary_list = [];
            // } else {
            //     $salary_list = $salary_details;
            // }

            // $iv_details = Iv::where(['name_id' => $user_name_id, 'status' => 1])->get();
            // if ($iv_details->count() <= 0) {

            //     $iv_details_list = [];
            // } else {
            //     $iv_details_list = $iv_details;
            // }

            // $online_course = OnlineCourse::where(['user_name_id' => $user_name_id, 'status' => 1])->get();
            // if ($online_course->count() <= 0) {

            //     $online_course_list = [];
            // } else {
            //     $online_course_list = $online_course;
            // }

            $document = Document::where(['nameofuser_id' => $user_name_id, 'status' => 1])->get();
            if ($document->count() <= 0) {

                $document_list = [];
            } else {
                $document_list = $document;
            }

            // // $seminar_details = Seminar::where(['user_name_id' => $user_name_id])->get();

            // // if ($seminar_details->count() <= 0) {

            // $seminar_details_list = [];
            // // } else {
            // //     $seminar_details_list = $seminar_details;
            // // }

            // $sabotical_details = Sabotical::where(['name_id' => $user_name_id, 'status' => 1])->get();

            // if ($sabotical_details->count() <= 0) {

            //     $sabotical_details_list = [];
            // } else {
            //     $sabotical_details_list = $sabotical_details;
            // }

            // $sponser_details = Sponser::where(['user_name_id' => $user_name_id, 'status' => 1])->get();

            // if ($sponser_details->count() <= 0) {

            //     $sponser_details_list = [];
            // } else {
            //     $sponser_details_list = $sponser_details;
            // }

            // $sttp_details = Sttp::where(['name_id' => $user_name_id, 'status' => 1])->get();
            // if ($sttp_details->count() <= 0) {

            //     $sttp_details_list = [];
            // } else {
            //     $sttp_details_list = $sttp_details;
            // }

            // $workshop_details = Workshop::where(['user_name_id' => $user_name_id])->get();
            // if ($workshop_details->count() <= 0) {

            //     $workshop_details_list = [];
            // } else {
            //     $workshop_details_list = $workshop_details;
            // }
            // $patent_details = Patent::where(['name_id' => $user_name_id, 'status' => 1])->get();
            // if ($patent_details->count() <= 0) {

            //     $patent_details_list = [];
            // } else {
            //     $patent_details_list = $patent_details;
            // }

            // $award_details = Award::where(['user_name_id' => $user_name_id, 'status' => 1])->get();
            // if ($award_details->count() <= 0) {

            //     $award_details_list = [];
            // } else {
            //     $award_details_list = $award_details;
            // }

            // $event = Events::pluck('event', 'id')->prepend(trans('global.pleaseSelect'), '');
            // $event_organized_details = EventOrganized::where(['user_name_id' => $user_name_id, 'status' => 1])->get();

            // if ($event_organized_details->count() <= 0) {

            //     $event_organized_details_list = [];
            // } else {

            //     for ($i = 0; $i < count($event_organized_details); $i++) {

            //         $event_organized_details[$i]->event = $event;
            //     }

            //     $event_organized_details_list = $event_organized_details;
            // }

            // $event_participation_details = EventParticipation::where(['user_name_id' => $user_name_id, 'status' => 1])->get();

            // if ($event_participation_details->count() <= 0) {

            //     $event_participation_details_list = [];
            // } else {

            //     for ($i = 0; $i < count($event_participation_details); $i++) {

            //         $event_participation_details[$i]->event = $event;
            //     }

            //     $event_participation_details_list = $event_participation_details;
            // }

            // $publication_details = PublicationDetail::where(['user_name_id' => $user_name_id, 'status' => 1])->get();
            // if ($publication_details->count() <= 0) {

            //     $publication_details_list = [];
            // } else {
            //     $publication_details_list = $publication_details;
            // }
            // $permissionrequest = PermissionRequest::where(['user_name_id' => $user_name_id, 'status' => 1])->get();
            // if ($permissionrequest->count() <= 0) {

            //     $permissionrequest_list = [];
            // } else {
            //     $permissionrequest_list = $permissionrequest;
            // }

            // $promotiondetails = PromotionDetails::where(['user_name_id' => $user_name_id, 'status' => 1])->get();

            // if ($promotiondetails->count() <= 0) {

            //     $promotiondetails_list = [];
            // } else {
            //     $promotiondetails_list = $promotiondetails;
            // }
            // $roles = Role::pluck('title', 'id');
            // $first_entry = 'data';

            $userId = auth()->user()->id;


            $canEdit = DB::table('staffs')
                ->where('user_name_id', $userId)
                ->value('edit_access');
                if($canEdit == 1)
                {
                    return redirect()->route('admin.staffs.Profile-edit', ['id' => $userId]);
                }
            if (is_numeric($request)) {
                return view('admin.staffs.staffshow', compact('staff', 'detail', 'experience_list', 'education_list', 'document_list', 'canEdit'));
            } else {
                if ($who == 'tech') {
                    return view('admin.edges.staff', compact('first_entry', 'name', 'staff', 'detail', 'experience_list', 'education_list'));
                } elseif ($who == 'non_tech') {
                    return view('admin.edges.staff', compact('first_entry', 'name', 'staff', 'detail', 'experience_list', 'education_list'));
                }
            }
        }
    }

    public function edit($request)
    {

        abort_if(Gate::denies('staff_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request) {

            // $decodedParam = base64_decode($one, true);

            // if ($decodedParam === true) {
            //     dd($request);
            //     $req= base64_decode($request);
            // }else{
            //     dd($request);
            //     $req = $request;
            // }
            $roles = Role::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');
            $designations = Designation::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

            $query = Staffs::where(['user_name_id' => $request])->get();
            // $document = Document::where(['nameofuser_id' => $request, 'fileName' => 'Profile'])->get();
            $query_one = PersonalDetail::where(['user_name_id' => $request])->get();
        }

        if ($query->count() <= 0) {
            $query->user_name_id = $request;

            // if ($document->count() <= 0) {
            //     $query->filePath = '';
            // } else {
            //     $query->filePath = $document[0]->filePath;
            // }

            if ($query_one->count() > 0) {
                $query->Gender = $query_one[0]->gender;
            }

            $staff = $query;
        } else {

            // if ($document->count() <= 0) {
            //     $query[0]->filePath = '';
            // } else {
            //     $query[0]->filePath = $document[0]->filePath;
            // }

            if ($query_one->count() > 0) {
                $query[0]->Gender = $query_one[0]->gender;
            }

            $staff = $query[0];
        }

        $check = "entry";

        $userId = auth()->user()->id;
        $canEdit = DB::table('staffs')
            ->where('user_name_id', $userId)
            ->value('edit_access');

        // dd($staff);

        return view('admin.StaffProfile.staff', compact('check', 'staff', 'designations', 'roles','canEdit'));
    }

    public function update(Request $request, Staffs $staffs)
    {

        $staffs->update($request->all());

        return redirect()->route('admin.staffs.index');
    }

}
