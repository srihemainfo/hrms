<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Designation;
use App\Models\NonTeachingStaff;
use App\Models\PersonalDetail;
use App\Models\Role;
use App\Models\Staffs;
use App\Models\Student;
use App\Models\TeachingStaff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        // abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            // $query = User::with(['roles'])->where('id', '!=', 2973)->select(sprintf('%s.*', (new User)->table));

            $query = DB::table('users')
                ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
                ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
            // ->leftJoin('teaching_types', 'teaching_types.id', '=', 'roles.type_id')
                ->select('users.id', 'users.name', 'users.email', 'roles.title', 'users.created_at')
                ->where('users.id', '!=', 2973)
                ->WhereNull('users.deleted_at')
                ->get();

            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) {
                $viewGate = 'user_show';
                $editGate = 'user_edit';
                $deleteGate = 'user_delete';
                $crudRoutePart = 'users';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            // $table->editColumn('staff_code', function ($row) {
            //     if ($row->employID != null) {
            //         return $row->employID;
            //     } else if ($row->register_no != null) {
            //         return $row->register_no;
            //     } else {
            //         return '';
            //     }
            // });
            // $table->editColumn('role_type', function ($row) {

            //     return $row->teach_type ? $row->teach_type : '';
            // });
            $table->editColumn('roles', function ($row) {
                return $row->title ? sprintf('<span class="label label-info label-many">%s</span>', $row->title) : '';
            });
            $table->editColumn('created', function ($row) {

                return $row->created_at ? $row->created_at : '';
            });
            $table->rawColumns(['actions', 'placeholder', 'roles']);
            return $table->make(true);
        }

        return view('admin.users.index');
    }

    public function create()
    {
        // abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');
        $working_as = Role::pluck('title', 'id');
        $designations = Designation::pluck('name', 'id');

        return view('admin.users.create', compact('roles', 'working_as', 'designations'));
    }

    public function store(Request $request)
    {
        if (isset($request->role)) {
            $user = new User();
            $staffs = new Staffs();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->phone_number);
            $user->save();


            $user->roles()->sync([$request->role]);


            if ($user->id != '') {

                $staffs->user_id = $user->id;
                $staffs->name = $request->name;
                $staffs->email = $request->email;
                $staffs->phone_number = $request->phone_number;
                $staffs->gender = $request->gender;
                $staffs->designation_id = $request->designation;
                $staffs->role_id = $request->role;
                $staffs->status = '';
                $staffs->employee_id = '';
                $staffs->biometric = '';

                $staffs->save();

                return response()->json(['status' => true, 'data' => 'User created successfully']);
            } else {
                return response()->json(['status' => false, 'data' => 'User creation failed']);
            }
        }


        // if (isset($request->role_type)) {
        //     $user = new User();
        //     $personalDetails = new PersonalDetail();

        //     if ($request->role_type == 1 || $request->role_type == 3 || $request->role_type == 2 || $request->role_type == 4 || $request->role_type == 5) {

        //         $casual_leave = 0;
        //         $personal_permission = 0;
        //         if ($request->doj != null || $request->doj != '') {
        //             $yearMonth = substr($request->doj, 0, 7);
        //             $explode = explode('-', $request->doj);

        //             $year = (int) $explode[0];
        //             $month = (int) $explode[1];
        //             $day = (int) $explode[2];

        //             $casual_leave = 0;

        //             if ($yearMonth == date('Y-m') && $day == 1) {
        //                 $casual_leave = 1;
        //             } elseif (($year == (int) date('Y') || $year == (int) date('Y') - 1) && (($year == (int) date('Y') - 1 && $month == (int) date('m', strtotime('last month'))) || ($year == (int) date('Y') && $month < (int) date('m'))) && $day >= 26) {
        //                 $casual_leave = 1;
        //             }

        //             $personal_permission = 0;
        //             if ($yearMonth == date('Y-m') && ($day > 1 && $day <= 15)) {
        //                 $personal_permission = 1;
        //             } elseif (
        //                 ($year == (int) date('Y') || $year == (int) date('Y') - 1) &&
        //                 (($year == (int) date('Y') - 1 && $month == (int) date('m', strtotime('last month'))) || ($year == (int) date('Y') && $month < (int) date('m'))) &&
        //                 $day >= 26
        //             ) {
        //                 $personal_permission = 2;
        //             }
        //         }

        //         $user->name = $request->firstname . ' ' . $request->last_name;
        //         $user->dept = $request->Dept;
        //         $user->employID = $request->StaffCode;
        //         $user->email = $request->email;
        //         $user->password = bcrypt($request->phone);
        //         $user->save();
        //         $user->roles()->sync([$request->role]);

        //         if ($request->role_type == 1 || $request->role_type == 3) {

        //             $staffCreate = new TeachingStaff();
        //             $staffCreate->name = $request->firstname . ' ' . $request->last_name;
        //             $staffCreate->last_name = $request->last_name;
        //             $staffCreate->StaffCode = $request->StaffCode;
        //             $staffCreate->Designation = $request->Designation;
        //             $staffCreate->Dept = $request->Dept;
        //             $staffCreate->shift_id = $request->shift;
        //             $staffCreate->casual_leave = $casual_leave;
        //             $staffCreate->personal_permission = $personal_permission;
        //             $staffCreate->EmailIDOffical = $request->email;
        //             $staffCreate->ContactNo = $request->phone;
        //             $staffCreate->role_type = $request->role_type;
        //             $staffCreate->user_name_id = $user->id;
        //             $staffCreate->save();

        //             $personalDetails->name = $request->firstname;
        //             $personalDetails->last_name = $request->last_name;
        //             $personalDetails->StaffCode = $request->StaffCode;
        //             $personalDetails->email = $request->email;
        //             $personalDetails->mobile_number = $request->phone;
        //             $personalDetails->user_name_id = $user->id;
        //             $personalDetails->save();

        //             $experience_details = new ExperienceDetail();
        //             $experience_details->user_name_id = $user->id;
        //             $experience_details->doj = $request->doj;
        //             $experience_details->save();

        //             if ($user->id != '' && $staffCreate->id != '' && $personalDetails->id != '') {
        //                 return response()->json(['status' => true, 'data' => 'User created successfully']);
        //             } else {
        //                 return response()->json(['status' => false, 'data' => 'User creation failed']);
        //             }
        //         } elseif ($request->role_type == 2 || $request->role_type == 4 || $request->role_type == 5) {

        //             $nonTeachingStaff = new NonTeachingStaff();
        //             $nonTeachingStaff->name = $request->firstname . ' ' . $request->last_name;
        //             $nonTeachingStaff->last_name = $request->last_name;
        //             $nonTeachingStaff->StaffCode = $request->StaffCode;
        //             $nonTeachingStaff->Designation = $request->Designation;
        //             $nonTeachingStaff->Dept = $request->Dept;
        //             $nonTeachingStaff->casual_leave = $casual_leave;
        //             $nonTeachingStaff->personal_permission = $personal_permission;
        //             $nonTeachingStaff->phone = $request->phone;
        //             $nonTeachingStaff->email = $request->email;
        //             $nonTeachingStaff->role_type = $request->role_type;
        //             $nonTeachingStaff->user_name_id = $user->id;
        //             $nonTeachingStaff->save();

        //             $personalDetails->name = $request->firstname;
        //             $personalDetails->last_name = $request->last_name;
        //             $personalDetails->StaffCode = $request->StaffCode;
        //             $personalDetails->email = $request->email;
        //             $personalDetails->mobile_number = $request->phone;
        //             $personalDetails->user_name_id = $user->id;
        //             $personalDetails->save();

        //             $experience_details = new ExperienceDetail();
        //             $experience_details->user_name_id = $user->id;
        //             $experience_details->doj = $request->doj;
        //             $experience_details->save();

        //             if ($user->id != '' && $nonTeachingStaff->id != '' && $personalDetails->id != '') {
        //                 return response()->json(['status' => true, 'data' => 'User created successfully']);
        //             } else {
        //                 return response()->json(['status' => false, 'data' => 'User creation failed']);
        //             }
        //         }
        //     } else {
        //         if ($request->role_type == 6 && $request->role == 11) {
        //             $user->name = $request->name;
        //             $user->register_no = $request->register_no;
        //             $user->enroll_master_id = $request->enroll_master_id;
        //             $user->email = $request->email;
        //             $user->save();
        //             $user->roles()->sync([$request->role]);

        //             $student = new Student();
        //             $student->name = $request->name;
        //             $student->student_email_id = $request->email;
        //             $student->student_phone_no = $request->phone;
        //             $student->register_no = $request->register_no;
        //             $student->shift_id = $request->shift;
        //             $student->roll_no = $request->rollNumber;
        //             $student->enroll_master_id = $request->enroll_master_id;
        //             $student->user_name_id = $user->id;
        //             $student->save();

        //             $academinDetails = new AcademicDetail();
        //             $academinDetails->roll_no = $request->rollNumber;
        //             $academinDetails->register_number = $request->register_no;
        //             $academinDetails->user_name_id = $user->id;
        //             $academinDetails->save();

        //             $personalDetails->name = $user->name;
        //             $personalDetails->email = $user->email;
        //             $personalDetails->mobile_number = $request->phone;
        //             $personalDetails->user_name_id = $user->id;
        //             $personalDetails->save();

        //             if ($user->id != '' && $academinDetails->id != '' && $personalDetails->id != '' && $student->id != '') {
        //                 return response()->json(['status' => true, 'data' => 'User created successfully']);
        //             } else {
        //                 return response()->json(['status' => false, 'data' => 'User creation failed']);
        //             }
        //         } else {
        //             $user->name = $request->fname;
        //             $user->password = $request->password;
        //             $user->email = $request->email;
        //             $user->save();
        //             $user->roles()->sync([$request->role]);

        //             if ($user->id != '') {
        //                 return response()->json(['status' => true, 'data' => 'User created successfully']);
        //             } else {
        //                 return response()->json(['status' => false, 'data' => 'User creation failed']);
        //             }
        //         }
        //     }
        // }
    }

    public function edit(User $user)
    {
        // abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roles = Role::pluck('title', 'id');
        $user->load('roles');
        // $role_type = TeachingType::pluck('name', 'id')->prepend('Select Type', '');
        return view('admin.users.edit', compact('role_type', 'user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user, )
    {

        $roleId = $request->input('roles');
        $role = Role::find($roleId);

        $designation = $role->title;

        if ($request->role_type == 1 || $request->role_type == 3) {
            $teach = TeachingStaff::where('user_name_id', $user->id)->get();
            if (count($teach) <= 0) {

                $nonTeach = NonTeachingStaff::where('user_name_id', $user->id)->get();

                if (count($nonTeach) > 0) {
                    $teachingData = $nonTeach->toArray();

                    unset($teachingData['id']);
                    unset($teachingData['created_at']);
                    unset($teachingData['updated_at']);

                    $teaching = TeachingStaff::create($teachingData);

                    // dd($teaching);
                    NonTeachingStaff::where('user_name_id', $user->id)->update([
                        'deleted_at' => Carbon::now(),
                    ]);

                    TeachingStaff::where('user_name_id', $user->id)->update([
                        'Designation' => $designation,
                        'EmailIDOffical' => $request->input('email'),
                        'role_type' => $request->role_type,
                    ]);
                }
            } else {
                TeachingStaff::where('user_name_id', $user->id)->update([
                    'Designation' => $designation,
                    'EmailIDOffical' => $request->input('email'),
                    'role_type' => $request->role_type,
                    // 'shift_id' => $request->shift,
                ]);
            }
        } elseif ($request->role_type == 2 || $request->role_type == 4 || $request->role_type == 5) {

            $nonteach = NonTeachingStaff::where('user_name_id', $user->id)->get();
            if (count($nonteach) <= 0) {

                $teach = TeachingStaff::where('user_name_id', $user->id)->get();

                if (count($teach) > 0) {
                    $nonteachingData = $teach->toArray();

                    unset($nonteachingData['id']);
                    unset($nonteachingData['created_at']);
                    unset($nonteachingData['updated_at']);

                    $teaching = NonTeachingStaff::create($nonteachingData);

                    // dd($teaching);
                    TeachingStaff::where('user_name_id', $user->id)->update([
                        'deleted_at' => Carbon::now(),
                    ]);

                    NonTeachingStaff::where('user_name_id', $user->id)->update([
                        'Designation' => $designation,
                        'email' => $request->input('email'),
                        'role_type' => $request->role_type,
                        'updated_at' => Carbon::now(),
                    ]);
                }
            } else {
                NonTeachingStaff::where('user_name_id', $user->id)->update([
                    'Designation' => $designation,
                    'email' => $request->input('email'),
                    'role_type' => $request->role_type,
                    'updated_at' => Carbon::now(),

                ]);
            }
        }
        if ($role->id == 11) {
            Student::where('user_name_id', $user->id)->update([
                'student_email_id' => $request->input('email'),
                // 'shift_id' => $request->input('shift'),
                'updated_at' => Carbon::now(),
            ]);
        }
        $update_personal = PersonalDetail::where(['user_name_id' => $user->id])->update([
            'email' => $request->input('email'),
            'updated_at' => Carbon::now(),

        ]);

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $validator = Validator::make($request->all(), [
            'password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if (!empty($password)) {
            $user->update([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
                'updated_at' => Carbon::now(),

            ]);
            if ($role->id == 11) {
                Student::where('user_name_id', $user->id)->update([
                    'name' => $name,
                    'student_email_id' => $email,
                    'updated_at' => Carbon::now(),
                ]);
            }
        } else {
            $user->update([
                'name' => $name,
                'email' => $email,
                'updated_at' => Carbon::now(),
            ]);
            if ($role->id == 11) {
                Student::where('user_name_id', $user->id)->update([
                    'name' => $name,
                    'student_email_id' => $email,
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $get_role = Role::select('title', 'id')
            ->where('id', $request->roles)
            ->latest()
            ->first();

        $user->roles()->sync([$roleId]);

        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        // abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles', 'userUserAlerts');

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        // abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($user->id);
        $user->delete();

        $teaching_staff = TeachingStaff::where('user_name_id', $user->id)->first();
        if ($teaching_staff != null) {
            $teaching_staff->delete();
        }
        $non_teaching_staff = NonTeachingStaff::where('user_name_id', $user->id)->first();

        if ($non_teaching_staff != null) {
            $non_teaching_staff->delete();
        }

        $personal = PersonalDetail::where('user_name_id', $user->id)->first();
        if ($personal != null) {
            $personal->delete();
        }

        $student = Student::where('user_name_id', $user->id)->first();
        if ($student != null) {
            $student->delete();
        }

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        $users = User::find(request('ids'));

        foreach ($users as $user) {
            $user->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    // public function block(Request $request)
    // {

    //     $role_type = TeachingType::pluck('name', 'id');

    //     return view('admin.users.block', compact('role_type'));
    // }
    public function fetchRoles(Request $request)
    {
        $roles = Role::where(['type_id' => $request->role_type])->select('id', 'title')->get();
        return response()->json(['roles' => $roles]);
    }
    public function fetchUsers(Request $request)
    {
        $users = [];
        $user = DB::table('role_user')->where(['role_id' => $request->role_user])->select('user_id')->get();
        // $userId =$user->toArray();
        foreach ($user as $u) {

            $getUser = User::where('id', $u->user_id)->select('name', 'id')->get();

            foreach ($getUser as $us) {
                array_push($users, ['id' => $us->id, 'name' => $us->name]);
            }
        }

        return response()->json([$users]);
    }
    public function unblock(Request $request)
    {
        $users = User::pluck('name', 'id');
        $got_users = User::get();
        $roles = Role::select('title', 'id', 'type_id')->get();
        $got_roles = [];
        foreach ($roles as $role_id => $role) {
            // dd($users);
            $got_roles[$role->id] = [];
            $get_user = DB::table('role_user')->where(['role_id' => $role->id])->get();
            foreach ($get_user as $user) {
                foreach ($got_users as $data) {
                    // dd($data);
                    if ($user->user_id == $data->id) {
                        if ($role->id == 11) {
                            array_push($got_roles[$role->id], [$data->id => $data->name . ' ( ' . $data->register_no . ' ) ']);
                        } elseif ($role->type_id != 6) {
                            array_push($got_roles[$role->id], [$data->id => $data->name . ' ( ' . $data->employID . ' ) ']);
                        } else {
                            array_push($got_roles[$role->id], [$data->id => $data->name]);
                        }
                    }
                }
            }
            // dd($get_user);
        }
        // dd($got_roles);
        return view('admin.users.unblock', compact('users', 'roles', 'got_roles'));
    }

    public function block_user(Request $request)
    {
        // dd($request);
        if ($request->user != '') {
            $user = User::where(['id' => $request->user])->update([
                'access' => 1,
                'block_reason' => $request->block_reason,
            ]);
        }
        return redirect()->route('admin.users.index')->with('error', 'User Blocked Successfully...');
    }

    public function unblock_user(Request $request)
    {
        // dd($request);
        if ($request->user != '') {
            $user = User::where(['id' => $request->user])->update([
                'access' => 0,
            ]);
        }
        return redirect()->route('admin.users.index')->with('message', 'User Unblocked Successfully...');
    }

    public function block_list()
    {
        $user = User::where(['access' => 1])->get();

        return view('admin.users.blockList', compact('user'));
    }
    public function fetch_role(Request $request)
    {
        // dd($request->type);
        $role = Role::where('type_id', $request->type)->pluck('title', 'id');
        return response()->json($role);
    }
}
