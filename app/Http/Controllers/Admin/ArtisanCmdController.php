<?php

namespace App\Http\Controllers\Admin;

use App\Models\ApiBiometricModel;

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

        return back();
    }

    public function ApiBiometric()
    {
        Artisan::call('apiBiometric:getdata');

        \Log::info("Api biometric data updation");

        return back();

    }

}
