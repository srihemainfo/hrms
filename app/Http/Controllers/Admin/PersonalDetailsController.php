<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyPersonalDetailRequest;
use App\Http\Requests\StorePersonalDetailRequest;
use App\Http\Requests\UpdatePersonalDetailRequest;
use App\Models\BloodGroup;
use App\Models\Community;
use App\Models\Document;
use App\Models\MotherTongue;
use App\Models\Nationality;
use App\Models\PersonalDetail;
use App\Models\Religion;
use App\Models\Staffs;
use App\Models\State;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PersonalDetailsController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        // abort_if(Gate::denies('personal_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = PersonalDetail::with(['user_name', 'blood_group', 'mother_tongue', 'religion', 'community'])->select(sprintf('%s.*', (new PersonalDetail)->table));
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'personal_detail_show';
                $editGate = 'personal_detail_edit';
                $deleteGate = 'personal_detail_delete';
                $crudRoutePart = 'personal-details';

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
                return $row->id ? $row->id : null;
            });
            $table->addColumn('user_name_name', function ($row) {
                return $row->user_name ? $row->user_name->name : null;
            });

            $table->editColumn('age', function ($row) {
                return $row->age ? $row->age : null;
            });

            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : null;
            });
            $table->editColumn('mobile_number', function ($row) {
                return $row->mobile_number ? $row->mobile_number : null;
            });
            $table->editColumn('aadhar_number', function ($row) {
                return $row->aadhar_number ? $row->aadhar_number : null;
            });
            $table->addColumn('blood_group_name', function ($row) {
                return $row->blood_group ? $row->blood_group->name : null;
            });

            $table->addColumn('mother_tongue_mother_tongue', function ($row) {
                return $row->mother_tongue ? $row->mother_tongue->mother_tongue : null;
            });

            $table->addColumn('religion_name', function ($row) {
                return $row->religion ? $row->religion->name : null;
            });

            $table->addColumn('community_name', function ($row) {
                return $row->community ? $row->community->name : null;
            });

            $table->editColumn('state', function ($row) {
                return $row->state ? $row->state : null;
            });
            $table->editColumn('country', function ($row) {
                return $row->country ? $row->country : null;
            });

            $table->rawColumns(['actions', 'placeholder', 'user_name', 'blood_group', 'mother_tongue', 'religion', 'community']);

            return $table->make(true);
        }

        return view('admin.personalDetails.index');
    }
    public function editperonal(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $department = $request->input('department');
        $phone = $request->input('phone');
        $workingas = $request->input('workingas');

        $affectedRows = DB::table('personal_details')
            ->where('id', $id)
            ->update([
                'name' => $name,
                'Dept' => $department,
                'ContactNo' => $phone,
                'Designation' => $workingas,

            ]);
        return response()->json(['success' => true]);
    }

    public function stu_index(Request $request)
    {
        // abort_if(Gate::denies('personal_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request) {

            $query = PersonalDetail::with(['user_name', 'blood_group', 'mother_tongue', 'religion', 'community'])->where(['user_name_id' => $request->user_name_id])->get();
            $document = Document::where(['nameofuser_id' => $request->user_name_id, 'fileName' => 'Profile'])->get();

        }

        $blood_groups = BloodGroup::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mother_tongues = MotherTongue::pluck('mother_tongue', 'id')->prepend(trans('global.pleaseSelect'), '');

        $religions = Religion::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $communities = Community::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        if ($query->count() <= 0) {

            $query->id = null;
            $query->age = null;
            $query->dob = null;
            $query->email = null;
            $query->mobile_number = null;
            $query->aadhar_number = null;
            $query->state = null;
            $query->country = null;
            $query->caste = null;
            $query->blood_group_id = $blood_groups;
            $query->blood_group = null;
            $query->mother_tongue_id = $mother_tongues;
            $query->mother_tongue = null;
            $query->community_id = $communities;
            $query->community = null;
            $query->religion_id = $religions;
            $query->religion = null;
            $query->name = $request->name;
            $query->student_id = null;
            $query->later_entry = null;
            $query->day_scholar_hosteler = null;
            $query->gender = null;
            $query->whatsapp_no = null;
            $query->annual_income = null;
            $query->first_graduate = null;
            $query->different_abled_person = '0';
            $query->user_name_id = $request->user_name_id;
            $query->add = 'Add';

            if ($document->count() <= 0) {
                $query->filePath = null;
            } else {
                $query->filePath = $document[0]->filePath;
            }

            $student = $query;

        } else {

            $query[0]->id = $request->user_name_id;
            $query[0]->name = $request->name;
            $query[0]->blood_group = $blood_groups;
            $query[0]->mother_tongue = $mother_tongues;
            $query[0]->community = $communities;
            $query[0]->religion = $religions;
            $query[0]->add = "Update";

            if ($document->count() <= 0) {
                $query[0]->filePath = null;
            } else {
                $query[0]->filePath = $document[0]->filePath;
            }

            $student = $query[0];

            $student->load('user_name', 'blood_group', 'mother_tongue', 'religion', 'community');

        }
        $check = 'personal_details';

        return view('admin.StudentProfile.student', compact('student', 'check'));
    }

    public function stu_update(UpdatePersonalDetailRequest $request, PersonalDetail $personalDetail)
    {
        // dd($request);
        if (isset($request->filePath)) {
            $request->validate([
                'filePath' => 'required|image|mimes:jpg,JPG,jpeg,png,PNG,JPEG|max:2048',
            ]);

            $file = $request->file('filePath');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $destinationPath = public_path('uploads'); // Set the destination path

// Move the uploaded file to the destination manually
            $file->move($destinationPath, $fileName);

// Set the storage path for further use if needed
            $path = 'uploads/' . $fileName;

// Find the document with the same file name and user ID
            $document = Document::where('fileName', $request->fileName)
                ->where('nameofuser_id', $request->user_name_id)
                ->first();

// If the document exists, update it and delete the old file
            if ($document) {
                $filePath = public_path($document->filePath);

                $document->filePath = $path;
                $document->fileName = $request->fileName;
                $document->status = '0';
                $document->save();

                // Delete the old file from the disk
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
            } else {
                // If the document does not exist, create a new one
                $document = new Document([
                    'fileName' => $request->fileName,
                    'filePath' => $path,
                    'nameofuser_id' => $request->user_name_id,
                    'status' => '0',
                ]);
                $document->save();

            }

        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($request->user_name_id),
            ],
            'mobile_number' => 'required|string|max:15',

        ]);

        if ($validator->fails()) {
            // Validation failed
            return back()->with(['errors' => $validator->errors()], 422);
        }
        $personal = $personalDetail->where('user_name_id', $request->user_name_id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'aadhar_number' => $request->aadhar_number,
            'dob' => $request->dob,
            'age' => $request->age,
            'caste' => $request->caste,
            'different_abled_person' => $request->different_abled_person,
            'gender' => $request->gender,
            'blood_group_id' => $request->blood_group_id,
            'mother_tongue_id' => $request->mother_tongue_id,
            'religion_id' => $request->religion_id,
            'community_id' => $request->community_id,
            'state' => $request->state,
            'country' => $request->country,
            'whatsapp_no' => $request->whatsapp_no,
            'annual_income' => $request->annual_income,
        ]);

        $stu_update = Student::where('user_name_id', $request->user_name_id)->update([
            'name' => $request->name,
            'student_phone_no' => $request->mobile_number,
            'student_email_id' => $request->email,
        ]);
        $user_update = User::where(['id' => $request->user_name_id])->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($personal) {

            $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $personalDetail = PersonalDetail::create([
                'name' => $request->name,
                'email' => $request->email,
                'user_name_id' => $request->user_name_id,
                'mobile_number' => $request->mobile_number,
                'aadhar_number' => $request->aadhar_number,
                'dob' => $request->dob,
                'age' => $request->age,
                'caste' => $request->caste,
                'different_abled_person' => $request->different_abled_person,
                'gender' => $request->gender,
                'blood_group_id' => $request->blood_group_id,
                'mother_tongue_id' => $request->mother_tongue_id,
                'religion_id' => $request->religion_id,
                'community_id' => $request->community_id,
                'state' => $request->state,
                'country' => $request->country,
                'whatsapp_no' => $request->whatsapp_no,
                'annual_income' => $request->annual_income,
            ]);

            if ($personalDetail) {
                $student = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
            } else {
                dd('Error');
            }

        }

        return redirect()->route('admin.personal-details.stu_index', $student);
    }

    public function staff_index(Request $request)
    {
        // dd($request->user_name_id);
        // abort_if(Gate::denies('personal_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request);
        if ($request) {

            $query = PersonalDetail::with(['user_name', 'blood_group', 'mother_tongue', 'religion', 'community'])->where(['user_name_id' => $request->user_name_id])->get();
            // $document = Document::where(['nameofuser_id' => $request->user_name_id, 'fileName' => 'Profile'])->get();
        }

        $blood_groups = BloodGroup::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mother_tongues = MotherTongue::pluck('mother_tongue', 'id')->prepend(trans('global.pleaseSelect'), '');

        $religions = Religion::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $communities = Community::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $states = State::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $nationalities = Nationality::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        // $departments=ToolsDepartment::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        if ($query->count() <= 0) {

            $query->id = null;
            $query->age = null;
            $query->dob = null;
            $query->email = null;
            $query->mobile_number = null;
            $query->aadhar_number = null;
            $query->state = null;
            $query->nationality = null;
            $query->employee_id = null;
            $query->phone_number = null;
            $query->country = null;
            $query->gender = null;
            $query->state_id = $states;
            $query->nationality_id = $nationalities;
            $query->state = null;
            $query->nationality = null;
            $query->user_name_id = null;
            $query->blood_group_id = $blood_groups;
            $query->blood_group = null;
            $query->mother_tongue_id = $mother_tongues;
            $query->mother_tongue = null;
            $query->community_id = $communities;
            $query->community = null;
            $query->religion_id = $religions;
            $query->religion = null;
            $query->father_name = null;
            $query->last_name = null;
            $query->spouse_name = null;
            $query->StaffCode = null;
            $query->emergency_contact_no = null;
            $query->known_languages = null;
            $query->marital_status = null;
            $query->pan_number = null;
            $query->PassportNo = null;
            $query->total_experience = null;
            $query->COECode = null;
            // $query->department_id = $departments;
            $query->department = null;

            // $query->DOJ = null;
            // $query->DOR = null;
            // $query->au_card_no = null;
            $query->name = $request->name;
            $query->first_name = null;
            $query->user_name_id = $request->user_name_id;
            $query->add = 'Add';

            // if ($document->count() <= 0) {
            //     $query->filePath = null;
            // } else {
            //     $query->filePath = $document[0]->filePath;
            // }

            $staff = $query;

        } else {

            $known_languages = unserialize($query[0]->known_languages);

            $query[0]->first_name = $query[0]->name;
            $query[0]->id = $request->user_name_id;
            $query[0]->name = $request->name;
            $query[0]->blood_group = $blood_groups;
            // $query[0]->departmentss = $departments;
            $query[0]->mother_tongue = $mother_tongues;
            $query[0]->community = $communities;
            $query[0]->religion = $religions;
            $query[0]->state = $states;
            $query[0]->nationality = $nationalities;
            // $query[0]->known_languages = $known_languages;
            // $query[0]->BiometricID = $teaching_staff->BiometricID;
            // $query[0]->AICTE = $teaching_staff->AICTE;
            // $query[0]->PanNo = $teaching_staff->PanNo;
            // $query[0]->PassportNo = $teaching_staff->PassportNo;
            // $query[0]->au_card_no = $teaching_staff->au_card_no;
            // $query[0]->COECode = $teaching_staff->COECode;
            // $query[0]->DOJ = $teaching_staff->DOJ;
            // $query[0]->DOR = $teaching_staff->DOR;
            $query[0]->add = "Update";

            // if ($document->count() <= 0) {
            //     $query[0]->filePath = null;
            // } else {
            //     $query[0]->filePath = $document[0]->filePath;
            // }

            $staff = $query[0];
            $staff->load('user_name', 'blood_group', 'mother_tongue', 'religion', 'community', 'state', 'nationality');

        }
        $check = 'personal_details';

        $check_staff_1 = Staffs::where(['user_name_id' => $request->user_name_id])->get();

        if (count($check_staff_1) > 0) {
            // dd($staff);

            return view('admin.StaffProfile.staff', compact('staff', 'check'));
        } else {
            // $check_staff_2 = NonTeachingStaff::where(['user_name_id' => $request->user_name_id])->get();

            // if (count($check_staff_2) > 0) {
            //     return view('admin.StaffProfile(non_tech).staff', compact('staff', 'check'));
            // }
        }

    }

    public function staff_update(Request $request, PersonalDetail $personalDetail)
    {
        // dd($request);
        if (isset($request->filePath)) {

            $request->validate([
                'filePath' => 'required|image|mimes:jpg,JPG,jpeg,png,PNG,JPEG|max:2048',
            ]);

            $file = $request->file('filePath');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $destinationPath = public_path('uploads'); // Set the destination path

            $file->move($destinationPath, $fileName);

            $path = 'uploads/' . $fileName;

            $document = Document::where('fileName', $request->fileName)
                ->where('nameofuser_id', $request->user_name_id)
                ->first();

            if ($document) {
                $filePath = public_path($document->filePath);

                $document->filePath = $path;
                $document->fileName = $request->fileName;
                $document->status = '0';
                $document->save();

                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
            } else {

                $document = new Document([
                    'fileName' => $request->fileName,
                    'filePath' => $path,
                    'nameofuser_id' => $request->user_name_id,
                    'status' => '0',
                ]);
                $document->save();

            }

        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|no_special_characters',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($request->user_name_id),
            ],
            'employee_id' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {

            return back()->with(['errors' => $validator->errors()], 422);
        }

        $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        $personal = $personalDetail->where('user_name_id', $request->user_name_id)->update([
            'name' => strtoupper($request->name),
            'email' => $request->email,
            'employee_id' => $request->employee_id,
            'phone_number' => $request->phone_number,
            'aadhar_number' => $request->aadhar_number,
            'pan_number' => $request->pan_number,
            'emergency_contact_no' => $request->emergency_contact_no,
            'marital_status' => $request->marital_status,
            'father_name' => $request->father_name,
            'spouse_name' => $request->spouse_name,
            'dob' => $request->dob,
            'age' => $request->age,
            'gender' => $request->gender,
            'blood_group_id' => $request->blood_group_id,
            'mother_tongue_id' => $request->mother_tongue_id,
            'community_id' => $request->community_id,
            'religion_id' => $request->religion_id,
            'state_id' => $request->state_id,
            'nationality_id' => $request->nationality_id,
            'total_experience' => $request->total_experience,
        ]);

        if ($personal) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {


            $roles_number = Staffs::where('user_name_id' , $request->user_name_id)->first();

            $personalDetail = PersonalDetail::create([
                'name' => strtoupper($request->name),
                'email' => $request->email,
                'BiometricID' => $request->biometric,
                'employee_id' => $request->employee_id,
                'phone_number' => $request->phone_number,
                'aadhar_number' => $request->aadhar_number,
                'pan_number' => $request->pan_number,
                'emergency_contact_no' => $request->emergency_contact_no,
                'marital_status' => $request->marital_status,
                'father_name' => $request->father_name,
                'spouse_name' => $request->spouse_name,
                'dob' => $request->dob,
                'age' => $request->age,
                'gender' => $request->gender,
                'blood_group_id' => $request->blood_group_id,
                'mother_tongue_id' => $request->mother_tongue_id,
                'community_id' => $request->community_id,
                'religion_id' => $request->religion_id,
                'state_id' => $request->state_id,
                'nationality_id' => $request->nationality_id,
                'total_experience' => $request->total_experience,
                'user_name_id' => $request->user_name_id,
                'role_id' =>$roles_number->role_id,
                'designation_id' => $roles_number->designation_id,

            ]);

            if ($personalDetail) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
            } else {
                dd('Error');
            }

        }

        $teach_staff_update = Staffs::where('user_name_id', $request->user_name_id)->update([
            'name' => strtoupper($request->name . ' ' . $request->last_name),
            'phone_number' => $request->phone_number,
            'employee_id' => $request->employee_id,
            'email' => $request->email,
            'gender' => $request->gender,
        ]);


        $user = User::where('id', $request->user_name_id)->update([
            'name' => strtoupper($request->name . ' ' . $request->last_name),
            'email' => $request->email,
            'employee_id' => $request->employee_id,
        ]);
        return redirect()->route('admin.personal-details.staff_index', $staff);
    }

    public function create()
    {

        // abort_if(Gate::denies('personal_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $blood_groups = BloodGroup::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mother_tongues = MotherTongue::pluck('mother_tongue', 'id')->prepend(trans('global.pleaseSelect'), '');

        $religions = Religion::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $communities = Community::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.personalDetails.create', compact('blood_groups', 'communities', 'mother_tongues', 'religions', 'user_names'));
    }

    public function store(StorePersonalDetailRequest $request)
    {

        $personalDetail = PersonalDetail::create($request->all());
        // dd($personalDetail);
        return redirect()->route('admin.personal-details.index');
    }

    public function edit(PersonalDetail $personalDetail)
    {
        // abort_if(Gate::denies('personal_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_names = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $blood_groups = BloodGroup::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mother_tongues = MotherTongue::pluck('mother_tongue', 'id')->prepend(trans('global.pleaseSelect'), '');

        $religions = Religion::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $communities = Community::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $personalDetail->load('user_name', 'blood_group', 'mother_tongue', 'religion', 'community');
        // dd($personalDetail);
        return view('admin.personalDetails.edit', compact('blood_groups', 'communities', 'mother_tongues', 'personalDetail', 'religions', 'user_names'));
    }

    public function update(UpdatePersonalDetailRequest $request, PersonalDetail $personalDetail)
    {

        $personalDetail->update($request->all());
        // dd($personalDetail);
        return redirect()->route('admin.personal-details.index');
    }

    public function show(PersonalDetail $personalDetail)
    {
        // abort_if(Gate::denies('personal_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $personalDetail->load('user_name', 'blood_group', 'mother_tongue', 'religion', 'community');

        return view('admin.personalDetails.show', compact('personalDetail'));
    }

    public function destroy(PersonalDetail $personalDetail)
    {
        // abort_if(Gate::denies('personal_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $personalDetail->delete();

        return back();
    }

    public function massDestroy(MassDestroyPersonalDetailRequest $request)
    {
        $personalDetails = PersonalDetail::find(request('ids'));

        foreach ($personalDetails as $personalDetail) {
            $personalDetail->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
