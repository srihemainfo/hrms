<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\Iv;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Batch;
use App\Models\Intern;
use App\Models\Patent;
use App\Models\Address;
use App\Models\Section;
use App\Models\Seminar;
use App\Models\Student;
use App\Models\Document;
use App\Models\Religion;
use App\Models\Semester;
use App\Models\Community;
use App\Models\BloodGroup;
use App\Models\ToolsCourse;
use App\Models\AcademicYear;
use App\Models\MotherTongue;
use App\Models\ParentDetail;
use Illuminate\Http\Request;
use App\Models\AddConference;
use App\Models\EducationType;
use App\Models\AcademicDetail;
use App\Models\PersonalDetail;
use App\Models\RemovedStudent;
use App\Models\AttendenceTable;
use App\Models\MediumofStudied;
use App\Models\ToolsDepartment;
use Yajra\DataTables\DataTables;
use App\Models\EducationalDetail;
use App\Models\CourseEnrollMaster;
use App\Models\IndustrialTraining;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\professional_activities;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyStudentRequest;

class StudentController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('student_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $userId = auth()->user()->id;
            $user = User::find($userId);

            if ($user) {
                $assignedRole = $user->roles->first();

                if ($assignedRole) {
                    $roleTitle = $assignedRole->id;

                    if ($roleTitle == 14) {
                        $userdept = user::where('id', $userId)->first();
                        // dd($userdept->dept);
                        if ($userdept) {
                            $department = ToolsDepartment::where('name', $userdept->dept)->first();
                            // dd($department->id);

                            if ($department) {
                                if ($department->id != 5) {
                                    $courses = ToolsCourse::where('department_id', $department->id)->get();
                                    if ($courses->isNotEmpty()) {
                                        $query = Student::with(['enroll_master'])
                                            ->whereIn('admitted_course', $courses->pluck('name'))
                                            ->get();
                                        if ($query->isEmpty()) {
                                            session()->flash('error', 'There are no students in this course.');
                                        }
                                    } else {
                                        session()->flash('error', 'No courses found for the department.');
                                    }
                                } else {
                                    $courses = ToolsCourse::select('name')->get();
                                    $theEnrolls = [];
                                    if (count($courses) > 0) {
                                        foreach ($courses as $course) {
                                            $make_enroll_1 = '%/' . $course->name . '/%/1/%';
                                            $make_enroll_2 = '%/' . $course->name . '/%/2/%';
                                            $getEnroll_1 = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%{$make_enroll_1}")->select('id')->get();
                                            $getEnroll_2 = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%{$make_enroll_2}")->select('id')->get();
                                            if (count($getEnroll_1) > 0) {
                                                foreach ($getEnroll_1 as $enroll) {
                                                    array_push($theEnrolls, $enroll->id);
                                                }
                                            }
                                            if (count($getEnroll_2) > 0) {
                                                foreach ($getEnroll_2 as $enroll) {
                                                    array_push($theEnrolls, $enroll->id);
                                                }
                                            }
                                        }
                                    }
                                    if (count($theEnrolls) > 0) {
                                        $query = Student::with(['enroll_master'])->whereIn('enroll_master_id', $theEnrolls)->get();
                                        if ($query->isEmpty()) {
                                            session()->flash('error', 'There are no students in this course.');
                                        }
                                    } else {
                                        session()->flash('error', 'There are no students in this course.');
                                    }
                                }
                            } else {
                                session()->flash('error', 'Department is not found.');
                            }
                        } else {
                            session()->flash('error', 'User department not found.');
                        }
                    } else {
                        $query = Student::with(['enroll_master'])->get();
                    }
                }
            }

            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $row->id = $row->user_name_id;
                $viewGate = 'student_show';
                $editGate = 'student_edit';
                $deleteGate = 'student_delete';
                $crudRoutePart = 'students';

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

            $table->editColumn('name', function ($row) {
                return $row->name ?? '';
            });

            $table->editColumn('register_no', function ($row) {
                return $row->register_no ?? '';
            });

            $table->addColumn('Course', function ($row) {
                $enrollMasterNumber = optional($row->enroll_master)->enroll_master_number;
                return $enrollMasterNumber ? explode('/', $enrollMasterNumber)[1] : '';
            });

            $table->addColumn('semester', function ($row) {
                $enrollMasterNumber = optional($row->enroll_master)->enroll_master_number;
                return $enrollMasterNumber ? explode('/', $enrollMasterNumber)[3] : '';
            });

            $table->addColumn('AccademicYear', function ($row) {
                $enrollMasterNumber = optional($row->enroll_master)->enroll_master_number;
                return $enrollMasterNumber ? explode('/', $enrollMasterNumber)[2] : '';
            });

            $table->addColumn('Section', function ($row) {
                $enrollMasterNumber = optional($row->enroll_master)->enroll_master_number;
                return $enrollMasterNumber ? explode('/', $enrollMasterNumber)[4] : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'enroll_master']);
            return $table->make(true);
        }

        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $section = Section::pluck('section', 'id')->unique();
        $academicYear = AcademicYear::pluck('name', 'id');
        // $Batch=Batch::pluck('name','id');
        // Flash message for non-Ajax requests
        return view('admin.students.index', compact('courses', 'semester', 'section', 'academicYear'))->with('error', session('error'));
    }
    public function search(Request $request)
    {
        abort_if(Gate::denies('student_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $string = $request->course . '/' . $request->academicYear . '/' . $request->semester . '/' . $request->section;
            $id = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%{$string}")->first();
            // dd($id);

            if ($id) {
                $enrollid = $id->id;
            } else {
                $enrollid = '';
            }
            $userId = auth()->user()->id;
            $user = User::find($userId);

            if ($user) {
                $assignedRole = $user->roles->first();

                if ($assignedRole) {
                    $roleTitle = $assignedRole->id;

                    if ($roleTitle == 14) {
                        $userdept = user::where('id', $userId)->first();
                        // dd($userdept->dept);
                        if ($userdept) {
                            $department = ToolsDepartment::where('name', $userdept->dept)->first();
                            // dd($department->id);

                            if ($department) {
                                $courses = ToolsCourse::where('department_id', $department->id)->get();
                                if ($courses->isNotEmpty()) {
                                    if ($enrollid != '') {
                                        $query = Student::with(['enroll_master'])
                                            ->whereIn('admitted_course', $courses->pluck('name'))
                                            ->get();
                                    } else {
                                        return response()->json(['message' => 'Enroll ID is empty.'], 400);

                                    }
                                    if ($query->isEmpty()) {
                                        session()->flash('error', 'There are no students in this course.');
                                    }
                                } else {
                                    session()->flash('error', 'No courses found for the department.');
                                }
                            } else {
                                session()->flash('error', 'Department is not found.');
                            }
                        } else {
                            session()->flash('error', 'User department not found.');
                        }
                    } else {
                        if ($enrollid != '') {
                            $query = Student::with(['enroll_master'])->where('enroll_master_id', $enrollid)->get();
                        } else {
                            return response()->json(['message' => 'Enroll ID is empty.'], 400);
                        }
                    }
                }
            }

            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $row->id = $row->user_name_id;
                $viewGate = 'student_show';
                $editGate = 'student_edit';
                $deleteGate = 'student_delete';
                $crudRoutePart = 'students';

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

            $table->editColumn('name', function ($row) {
                return $row->name ?? '';
            });

            $table->editColumn('register_no', function ($row) {
                return $row->register_no ?? '';
            });

            $table->addColumn('Course', function ($row) {
                $enrollMasterNumber = optional($row->enroll_master)->enroll_master_number;
                return $enrollMasterNumber ? explode('/', $enrollMasterNumber)[1] : '';
            });

            $table->addColumn('semester', function ($row) {
                $enrollMasterNumber = optional($row->enroll_master)->enroll_master_number;
                return $enrollMasterNumber ? explode('/', $enrollMasterNumber)[3] : '';
            });

            $table->addColumn('AccademicYear', function ($row) {
                $enrollMasterNumber = optional($row->enroll_master)->enroll_master_number;
                return $enrollMasterNumber ? explode('/', $enrollMasterNumber)[2] : '';
            });

            $table->addColumn('Section', function ($row) {
                $enrollMasterNumber = optional($row->enroll_master)->enroll_master_number;
                return $enrollMasterNumber ? explode('/', $enrollMasterNumber)[4] : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'enroll_master']);
            return $table->make(true);
        }

        $courses = ToolsCourse::pluck('name', 'id');
        $semester = Semester::pluck('semester', 'id');
        $section = Section::pluck('section', 'id')->unique();
        $academicYear = AcademicYear::pluck('name', 'id');
        // Flash message for non-Ajax requests
        return view('admin.students.index', compact('courses', 'semester', 'section', 'academicYear'))->with('error', session('error'));
    }

    public function create()
    {
        abort_if(Gate::denies('student_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $enroll_masters = CourseEnrollMaster::pluck('enroll_master_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.students.create', compact('enroll_masters'));
    }

    public function store(StoreStudentRequest $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:120',
            'email' => 'required|email|unique:users',
            'phone' => ['required', 'digits:10'],
            'register_no' => 'required',
            'roll_no' => 'required',
        ]);

        if ($validator->fails()) {
            // dd($validator->errors());
            return back()->with(['errors' => $validator->errors()], 422);
        }

        $check_reg_no = User::where(['register_no' => $request->register_no])->get();

        if (count($check_reg_no) > 0) {
            return back()->withErrors(['register_no' => 'The Register Number Already Exist']);
        }
        $firstname = $request->input('name');
        $name = strtoupper($firstname);
        // dd($request);
        $user = new User;
        $user->name = $name;
        $user->email = $request->input('email');
        $user->register_no = $request->input('register_no');
        $user->password = bcrypt($request->input('phone'));
        $user->save();

        $admin = Role::select('id')->where('title', 'Student')->first();

        if (!$admin) {
            return response()->json(['error' => 'Role not found'], 400);
        }

        $role_id = $admin->id;
        $user->roles()->sync($request->input('roles', $role_id));

        if ($request->enroll_master_id == '') {
            $enroll = null;
        } else {
            $enroll = $request->enroll_master_id;
        }
        // $student = Student::create($request->all());
        $studentcreate = new Student;
        $studentcreate->name = $name;
        $studentcreate->student_email_id = $request->input('email');
        $studentcreate->student_phone_no = $request->input('phone');
        $studentcreate->register_no = $request->input('register_no');
        $studentcreate->roll_no = $request->input('roll_no');
        $studentcreate->enroll_master_id = $enroll;
        $studentcreate->user_name_id = $user->id;
        $studentcreate->save();

        $personalDetails = new PersonalDetail();
        $personalDetails->name = $name;
        $personalDetails->mobile_number = $request->input('phone');
        $personalDetails->email = $request->input('email');
        $personalDetails->user_name_id = $user->id;
        $personalDetails->save();

        $academic_details = new AcademicDetail();
        $academic_details->user_name_id = $user->id;
        $academic_details->register_number = $request->input('register_no');
        $academic_details->roll_no = $request->input('roll_no');
        $academic_details->save();

        return redirect()->route('admin.students.index');
    }

    public function edit($request)
    {

        if ($request) {

            $query = Student::where(['user_name_id' => $request])->get();
            $document = Document::where(['nameofuser_id' => $request, 'fileName' => 'Profile'])->get();

        }

        $enroll_masters = CourseEnrollMaster::pluck('enroll_master_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        if ($query->count() <= 0) {
            $query->user_name_id = $request;

            if ($document->count() <= 0) {
                $query->filePath = '';
            } else {
                $query->filePath = $document[0]->filePath;
            }

            $student = $query;
        } else {

            if ($document->count() <= 0) {
                $query[0]->filePath = '';
            } else {
                $query[0]->filePath = $document[0]->filePath;
            }

            $student = $query[0];

        }
        // dd($student);
        $check = 'entry';

        return view('admin.StudentProfile.student', compact('student', 'check'));
    }

    public function first_edit($request)
    {

        if ($request) {

            $query = Student::where(['user_name_id' => $request])->get();
            $document = Document::where(['nameofuser_id' => $request, 'fileName' => 'Profile'])->get();

        }

        $enroll_masters = CourseEnrollMaster::pluck('enroll_master_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        if ($query->count() <= 0) {
            $query->user_name_id = $req;

            if ($document->count() <= 0) {
                $query->filePath = '';
            } else {
                $query->filePath = $document[0]->filePath;
            }

            $student = $query;
        } else {

            if ($document->count() <= 0) {
                $query[0]->filePath = '';
            } else {
                $query[0]->filePath = $document[0]->filePath;
            }

            $student = $query[0];

        }
        // dd($student);
        $check = 'entry';

        return view('admin.StudentProfile.student', compact('student', 'check'));
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $student->update($request->all());

        return redirect()->route('admin.students.index');
    }

    public function show($request)
    {
        abort_if(Gate::denies('student_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request);
        if (is_numeric($request)) {
            $student = Student::where('user_name_id', $request)->first();
        } else if (filter_var($request, FILTER_SANITIZE_NUMBER_INT)) {

            $explode = explode('(', $request);
            $register_no = trim(substr($explode[1], 0, -1));

            $student = Student::where('register_no', $register_no)->first();
            $name = $request;

        } else {
            $user_name = substr($request, 0, -1);
            $name = $user_name;
            // dd($request);
            $student = Student::where('name', $user_name)->first();
        }

        if ($student == null || $student == '') {
            return back()->withErrors('Student Not Found');
        } else {

            $user_name_id = $student->user_name_id;

            $document = Document::where(['nameofuser_id' => $user_name_id, 'fileName' => 'Profile'])->get();

            if ($document->count() <= 0) {
                $student->filePath = '';
            } else {
                $student->filePath = $document[0]->filePath;
            }

            $personal = PersonalDetail::where(['user_name_id' => $user_name_id])->get();

            $blood_groups = BloodGroup::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

            $mother_tongues = MotherTongue::pluck('mother_tongue', 'id')->prepend(trans('global.pleaseSelect'), '');

            $religions = Religion::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

            $communities = Community::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

            $attendance = AttendenceTable::where(['student' => $user_name_id])->select('date', 'attendance')->groupby('date', 'attendance')->get();
            //    dd($attendance);
            $absents = 0;
            if (count($attendance) > 0) {
                $totalAttendance = count($attendance);
                foreach ($attendance as $att) {
                    if ($att->attendance == 'Absent') {
                        $absents++;
                    }
                }
            } else {
                $totalAttendance = 0;
            }
            // dd($absents,$totalAttendance);
            if ($totalAttendance > 0 && $absents > 0) {
                $theAttPercentage = round(($absents / $totalAttendance) * 100);
            } else if ($totalAttendance > 0 && $absents <= 0) {
                $theAttPercentage = 100;
            } else if ($totalAttendance <= 0) {
                $theAttPercentage = 0;
            }

            $enroll_master_numbers = CourseEnrollMaster::pluck('enroll_master_number', 'id')->prepend(trans('global.pleaseSelect'), '');

            if ($personal->count() <= 0) {

                $personal->id = '';
                $personal->age = '';
                $personal->dob = '';
                $personal->email = '';
                $personal->mobile_number = '';
                $personal->aadhar_number = '';
                $personal->state = '';
                $personal->country = '';
                $personal->blood_group_id = $blood_groups;
                $personal->blood_group = [];
                $personal->mother_tongue_id = $mother_tongues;
                $personal->mother_tongue = [];
                $personal->community_id = $communities;
                $personal->community = [];
                $personal->religion_id = $religions;
                $personal->religion = [];
                $personal->student_id = '';
                $personal->gender = '';
                $personal->user_name_id = $user_name_id;
                $personal->name = $student->name;

                $detail = $personal;

            } else {
                $personal[0]->blood_group = $blood_groups;
                $personal[0]->mother_tongue = $mother_tongues;
                $personal[0]->community = $communities;
                $personal[0]->religion = $religions;

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

            $address_details = Address::where(['name_id' => $user_name_id])->get();

            if ($address_details->count() <= 0) {
                $address_list = [];
            } else {
                $address_list = $address_details;
            }
            $admitted_courses = ToolsCourse::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
            $academic_details = AcademicDetail::with(['enroll_master_number','scholarDetail'])->where(['user_name_id' => $user_name_id])->get();

            if ($academic_details->count() <= 0) {
                $academic_list = [];
            } else {
                $academic_details[0]->enroll_master_numbers = $enroll_master_numbers;
                $academic_details[0]->admitted_courses = $admitted_courses;
                $academic_list = $academic_details[0];
            }
            $parent_detail = ParentDetail::where(['user_name_id' => $user_name_id])->get();

            if ($parent_detail->count() <= 0) {
                $parent = [];
            } else {
                $parent = $parent_detail[0];
            }

            $conference_details = AddConference::where(['user_name_id' => $user_name_id])->get();

            if ($conference_details->count() <= 0) {
                $conference_list = [];
            } else {
                $conference_list = $conference_details;
            }

            $industrial_training = IndustrialTraining::where(['name_id' => $user_name_id])->get();

            if ($industrial_training->count() <= 0) {

                $industrial_training_list = [];
            } else {
                $industrial_training_list = $industrial_training;
            }

            $intern_details = Intern::where(['name_id' => $user_name_id])->get();
            if ($intern_details->count() <= 0) {

                $intern_details_list = [];
            } else {
                $intern_details_list = $intern_details;
            }

            $iv_details = Iv::where(['name_id' => $user_name_id])->get();
            if ($iv_details->count() <= 0) {

                $iv_details_list = [];
            } else {
                $iv_details_list = $iv_details;
            }

            $document = Document::where(['nameofuser_id' => $user_name_id])->get();
            if ($document->count() <= 0) {

                $document_list = [];
            } else {
                $document_list = $document;
            }

            $seminar_details = Seminar::where(['user_name_id' => $user_name_id])->get();

            if ($seminar_details->count() <= 0) {

                $seminar_details_list = [];
            } else {
                $seminar_details_list = $seminar_details;
            }

            $patent_details = Patent::where(['name_id' => $user_name_id])->get();
            if ($patent_details->count() <= 0) {

                $patent_details_list = [];
            } else {
                $patent_details_list = $patent_details;
            }
            $professional_activities = professional_activities::where(['user_name_id' => $user_name_id])->get();
            if ($professional_activities->count() <= 0) {

                $professional_activities_list = [];
            } else {
                $professional_activities_list = $professional_activities;
            }

            $student_leave_apply = DB::table('student_leave_apply')
                ->where('user_name_id', $student->user_name_id)
                ->whereNull('deleted_at')
                ->get();

            if ($student_leave_apply->isEmpty()) {
                $student_leave_apply_list = [];
            } else {
                $student_leave_apply_list = $student_leave_apply->toArray();
            }

            // // dd($academic_list);

            if (isset($academic_list->enroll_master_number_id) && $academic_list->enroll_master_number_id != '') {
                $array_1 = CourseEnrollMaster::find($academic_list->enroll_master_number_id);
                if ($array_1) {
                    $newArray = explode('/', $array_1->enroll_master_number);

                    $academic_list->coursE = $newArray[1];
                    $academic_list->Batch = $newArray[0];
                    $academic_list->accademicYear = $newArray[2];
                    $academic_list->sem = $newArray[3];
                    $academic_list->section = $newArray[4];
                } else {
                    $academic_list->coursE = '';
                    $academic_list->Batch = '';
                    $academic_list->accademicYear = '';
                    $academic_list->sem = '';
                    $academic_list->section = '';
                }

            } else {
                $academic_list->coursE = '';
                $academic_list->Batch = '';
                $academic_list->accademicYear = '';
                $academic_list->sem = '';
                $academic_list->section = '';
            }

            if (is_numeric($request)) {

                return view('admin.students.studentshow', compact('education_types', 'patent_details_list', 'seminar_details_list', 'document_list', 'iv_details_list', 'intern_details_list', 'industrial_training_list', 'student', 'detail', 'education_list', 'address_list', 'academic_list', 'parent', 'conference_list', 'professional_activities_list', 'student_leave_apply_list', 'theAttPercentage'));

            } else {

                return view('admin.edges.student', compact('name', 'education_types', 'patent_details_list', 'seminar_details_list', 'document_list', 'iv_details_list', 'intern_details_list', 'industrial_training_list', 'student', 'detail', 'education_list', 'address_list', 'academic_list', 'parent', 'conference_list', 'professional_activities_list', 'student_leave_apply_list', 'theAttPercentage'));
            }
        }
    }

    public function destroy(Request $request)
    {
        abort_if(Gate::denies('student_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request->id,$request->reason);
        $find_student = Student::where(['user_name_id' => $request->id])->first();

        $student = Student::find($find_student->id);

        $user = User::find($request);

        if ($student != '' && $user != '') {

            $removeStudent = RemovedStudent::create([
                'user_name_id' => $request->id,
                'name' => $student->name,
                'register_no' => $student->register_no,
                'enroll_master' => $student->enroll_master_id,
                'reason' => $request->reason,
                'deleted_by' => auth()->user()->id,
            ]);

            $student->delete();

            $update = User::where(['id' => $request->id])->update([
                'access' => 1,
                'block_reason' => $request->reason,
            ]);
            return response()->json(['status' => true]);
        } else {
            return back();
        }

    }

    public function massDestroy(MassDestroyStudentRequest $request)
    {
        $students = Student::find(request('ids'));

        foreach ($students as $student) {
            $student->delete();
            $user = User::find($student->user_name_id);
            $user->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function circle(Request $request)
    {
        // $getStudent = Student::where(['enroll_master_id' => null])->get();
        // foreach ($getStudent as $stu) {
        //     // $updateStu = Student::where(['user_name_id' => $stu->user_name_id])->update([
        //     //     'enroll_master_id' => 571,

        //     // ]);
        //     // $updateStudent = AcademicDetail::where(['user_name_id' => $stu->user_name_id])->update([
        //     //     'enroll_master_number_id' => 571,

        //     // ]);
        // }
        return back();
    }

    public function removedStudents(Request $request)
    {
        abort_if(Gate::denies('student_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {

            $query = RemovedStudent::with(['enroll'])->get();

            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');

            $table->editColumn('name', function ($row) {
                return $row->name ?? '';
            });

            $table->editColumn('register_no', function ($row) {
                return $row->register_no ?? '';
            });

            $table->addColumn('Course', function ($row) {
                $enrollMasterNumber = optional($row->enroll)->enroll_master_number;
                return $enrollMasterNumber ? explode('/', $enrollMasterNumber)[1] : '';
            });

            $table->addColumn('semester', function ($row) {
                $enrollMasterNumber = optional($row->enroll)->enroll_master_number;
                return $enrollMasterNumber ? explode('/', $enrollMasterNumber)[3] : '';
            });

            $table->addColumn('AcademicYear', function ($row) {
                $enrollMasterNumber = optional($row->enroll)->enroll_master_number;
                return $enrollMasterNumber ? explode('/', $enrollMasterNumber)[2] : '';
            });

            $table->addColumn('Section', function ($row) {
                $enrollMasterNumber = optional($row->enroll)->enroll_master_number;
                return $enrollMasterNumber ? explode('/', $enrollMasterNumber)[4] : '';
            });

            $table->editColumn('reason', function ($row) {
                return $row->reason ?? '';
            });

            $table->editColumn('date', function ($row) {
                $getDate = null;
                if ($row->created_at != null) {
                    $getDate = Carbon::parse($row->created_at)->format('d-m-Y');
                }
                return $getDate;
            });

            $table->rawColumns(['placeholder', 'enroll_master']);
            return $table->make(true);
        }

        return view('admin.removedStudent.index');
    }

    public function imageIndex(Request $request)
    {
        return view('admin.students.imageUpload');
    }

    public function imageUpload(Request $request)
    {
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg'];

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $uploadedCount = 0;
            foreach ($images as $image) {
                if (in_array($image->getClientMimeType(), $allowedMimeTypes)) {
                    if ($image->getClientOriginalName() != '' && $image->getClientOriginalName() != null) {
                        $explode = explode('.', $image->getClientOriginalName());
                        if (count($explode) > 1) {
                            $getStudent = Student::where(['register_no' => $explode[0]])->select('user_name_id')->first();
                            if ($getStudent != '') {
                                $destinationPath = public_path('studentImages');
                                $image->move($destinationPath, $image->getClientOriginalName());
                                $path = 'studentImages/' . $image->getClientOriginalName();

                                $document = Document::where('fileName', 'Profile')
                                    ->where('nameofuser_id', $getStudent->user_name_id)
                                    ->first();

                                if ($document != '') {
                                    $filePath = public_path($document->filePath);

                                    $update = Document::where(['fileName' => 'Profile', 'nameofuser_id' => $getStudent->user_name_id])->update([
                                        'filePath' => $path,
                                        'status' => '0',
                                    ]);
                                    if ($update) {
                                        $uploadedCount++;
                                    }
                                    $unwanted_1 = null;
                                    $unwanted_2 = null;
                                    if ($explode[1] == 'jpeg') {
                                        $unwanted_1 = 'studentImages/' . $explode[0] . '.jpg';
                                        $unwanted_2 = 'studentImages/' . $explode[0] . '.png';
                                    } else if ($explode[1] == 'jpg') {
                                        $unwanted_1 = 'studentImages/' . $explode[0] . '.png';
                                        $unwanted_2 = 'studentImages/' . $explode[0] . '.jpeg';
                                    } else if ($explode[1] == 'png') {
                                        $unwanted_1 = 'studentImages/' . $explode[0] . '.jpg';
                                        $unwanted_2 = 'studentImages/' . $explode[0] . '.jpeg';
                                    }
                                    // // Delete the old file from the disk
                                    if ($unwanted_1 != null) {
                                        if (file_exists($unwanted_1)) {
                                            unlink($unwanted_1);
                                        }
                                    }
                                    if ($unwanted_2 != null) {
                                        if (file_exists($unwanted_2)) {
                                            unlink($unwanted_2);
                                        }
                                    }
                                } else {
                                    // If the document does not exist, create a new one
                                    $document = Document::create([
                                        'fileName' => 'Profile',
                                        'filePath' => $path,
                                        'nameofuser_id' => $getStudent->user_name_id,
                                        'status' => '0',
                                    ]);
                                    if ($document) {
                                        $uploadedCount++;
                                    }

                                }
                            }
                        }
                    }

                }
            }
            $data = $uploadedCount . ' Images Uploaded Successfully';
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Images Not Found']);
        }

    }
}
