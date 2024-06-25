<?php

namespace App\Http\Controllers\Admin;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LeavesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function create_cl()
    {

        $get_staff = DB::table('role_user')->whereNotIn('role_id', [1, 2, 11, 12])->get();
        if ($get_staff->count() > 0) {
            foreach ($get_staff as $staff) {

                $check_staff = Leave::where(['user_id' => $staff->user_id])->get();

                if ($check_staff->count() > 0) {

                    $cl = $check_staff[0]->casual_leave;

                    $store_staff = Leave::where(['user_id' => $staff->user_id, 'role_id' => $staff->role_id])->update([
                        'casual_leave' => $cl + 1,
                    ]);

                } else {
                    $add_cl = new Leave;
                    $add_cl->user_id = $staff->user_id;
                    $add_cl->role_id = $staff->role_id;
                    $add_cl->casual_leave = 1;
                    $add_cl->save();
                }

            }
        }
    }
}
