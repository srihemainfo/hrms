<?php

namespace App\Http\Controllers\Admin;

use App\Models\ApiBiometricModel;
use App\Models\NonTeachingStaff;
use App\Models\PersonalDetail;
use App\Models\Student;
use App\Models\SubjectRegistration;
use App\Models\TeachingStaff;
use App\Models\User;

session()->start();
use \Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\HrmRequestLeaf;
use App\Models\StaffBiometric;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

ini_set('max_execution_time', 3600);
class ArtisanCmdController extends Controller
{
    public function ViewCache()
    {
        Artisan::call('view:cache');

        \Log::info("View Cached");

        return back();
    }

    public function ViewClear()
    {

        Artisan::call('view:clear');

        \Log::info("View Cleared");

        return back();
    }

    public function RouteCache()
    {

        Artisan::call('route:cache');

        \Log::info("Route Cached");

        return back();
    }

    public function RouteClear()
    {

        Artisan::call('route:clear');

        \Log::info("route Cleared");

        return back();
    }

    public function CacheClear()
    {

        Artisan::call('cache:clear');

        \Log::info("Cache Cleared");

        return back();
    }

    public function CacheForget($key)
    {
        Artisan::call('cache:forget', ['key' => $key]);

        \Log::info("Cache Forgeted");

        return back();
    }

    public function ConfigCache()
    {
        Artisan::call('config:cache');

        \Log::info("Config Cached");

        return back();
    }

    public function ConfigClear()
    {

        Artisan::call('config:clear');

        \Log::info("Config Cleared");

        return back();
    }

    public function ScheduleClearCache()
    {
        Artisan::call('schedule:clear-cache');

        \Log::info("Schedule Cache Cleared");

        return back();
    }

    public function StorageLink()
    {
        Artisan::call('storage:link');

        \Log::info("Storage Linked");

        return back();

    }

    public function circle()
    {
        // $roles = DB::table('permission_role')->get();

        // foreach ($roles as $role) {
        //     $check = DB::table('permission_role')
        //         ->where('role_id', $role->role_id)
        //         ->where('permission_id', 820)
        //         ->first();
        //     // dd(!$check);
        //     if (!$check) {
        //         // Permission 820 is not assigned to this role, so insert it
        //         DB::table('permission_role')->insert([
        //             'role_id' => $role->role_id,
        //             'permission_id' => 820,
        //         ]);
        //     }
        // }

        // $user = DB::table('academic_details')
        //     ->whereNotNull('register_number')
        //     ->update(['register_number' => DB::raw('SUBSTRING(register_number, 5)')]);


        // $users = User::whereNotNull('employID')->select('id')->get();
        // dd($users[0]->id);
        // $i=0;
        // foreach ($users as $key => $user) {
        //     $str = 'Staff' . $key + 1;
        // dd($str);

        //     $u = User::where('id', $user->id)->update([
        //         'name' => $str
        //     ]);

        //     $stu = TeachingStaff::where('user_name_id', $user->id)->update([
        //         'name' => $str
        //     ]);

        //     $stu = NonTeachingStaff::where('user_name_id', $user->id)->update([
        //         'name' => $str
        //     ]);

        //     $personal = PersonalDetail::where('user_name_id', $user->id)->update([
        //         'name' => $str
        //     ]);

        // }

        // $get_data = Student::select('roll_no', 'user_name_id')->get();
        // foreach ($get_data as $data) {
        //     $check = User::where('id', $data->user_name_id)->update([
        //         'roll_no' => $data->roll_no
        //     ]);

        //     $check = SubjectRegistration::where('user_name_id', $data->user_name_id)->update([
        //         'roll_no' => $data->roll_no
        //     ]);
        // }

        // $user = User::whereNotNull('email')->get();
        // foreach ($user as $u) {
        //     $email = explode('@', $u->email);
        //     $addon = $email[0] . '@shi.edu.in';
        //     $u->email = $addon;
        //     $u->save();
        //     // dd($u);
        //     $student = Student::where('user_name_id', $u->id)->update([
        //         'student_email_id' => $addon

        //     ]);

        //     $personal = PersonalDetail::where('user_name_id', $u->id)->update([
        //         'email' => $addon

        //     ]);
        // }


        return back();
    }

    public function ApiBiometric()
    {
        Artisan::call('apiBiometric:getdata');

        \Log::info("Api biometric data updation");

        return back();

    }

}
