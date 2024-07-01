<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use DB;
use Exception;
use Illuminate\Http\Request;

class ErpSettingController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.erpSetting.index');
    }
    public function store(Request $request)
    {
        $permission = ['degree_alter_access', 'shift_alter_access'];
        $get_ids = Permission::whereIn('title', $permission)->pluck('id')->toArray();
        // dd($request);

        try {
            if ($request->college == 'ARTS') {
                $check = DB::table('permission_role')->where(['role_id' => 1, 'permission_id' => $get_ids[0]])->exists();
                if (!$check) {
                    $degree_role = DB::table('permission_role')->insert(['role_id' => 1, 'permission_id' => $get_ids[0]]);
                }

            } elseif ($request->college == 'ENGINEERING') {
                $check = DB::table('permission_role')->where(['permission_id' => $get_ids[0]])->exists();
                if ($check) {
                    $degree_role = DB::table('permission_role')->where('permission_id', $get_ids[0])->delete();
                }

            }

            if (in_array('UG', $request->degree) && in_array('PG', $request->degree)) {
                $check = DB::table('permission_role')->where(['role_id' => 1, 'permission_id' => $get_ids[1]])->exists();
                if (!$check) {
                    $degree_role = DB::table('permission_role')->insert(['role_id' => 1, 'permission_id' => $get_ids[1]]);
                }

            } elseif (in_array('UG', $request->degree) || in_array('PG', $request->degree)) {
                $check = DB::table('permission_role')->where(['permission_id' => $get_ids[1]])->exists();
                if ($check) {
                    $degree_role = DB::table('permission_role')->where('permission_id', $get_ids[1])->delete();
                }
            }


            return response()->json(['status' => true, 'data' => 'ERP Altered based on Shift & Degree.']);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'data' => $e->getMessage()]);
        }

    }
}
