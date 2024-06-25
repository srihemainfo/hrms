<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NonTeachingStaff;
use App\Models\PromotionDetails;
use App\Models\Role;
use App\Models\TeachingStaff;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PromotionDetailsController extends Controller
{

    public function staff_index(Request $request)
    {

        if (isset($request->accept)) {

            PromotionDetails::where('id', $request->id)->update(['status' => 1]);
        }

        if (isset($request->reject)) {

            PromotionDetails::where('id', $request->id)->update(['status' => 2]);
        }

        $titlesToExclude = ['Admin', 'User', 'Sub-Admin', 'student', 'Hr', 'Principal','HOD','Librarian','Admission'];

        // Fetch all roles except for titles to exclude
        $designation = Role::whereNotIn('title', $titlesToExclude)
            ->pluck('title', 'id')
            ->prepend(trans('global.pleaseSelect'), '');
        if (!$request->updater) {

            $query = PromotionDetails::where(['user_name_id' => $request->user_name_id])->get();

            $teaching_staff = TeachingStaff::where(['user_name_id' => $request->user_name_id])->first();

            if (empty($teaching_staff)) {
                $teaching_staff = NonTeachingStaff::where(['user_name_id' => $request->user_name_id])->first();

            }
// dd($query);

            if ($query->count() <= 0) {
                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->current_designation = $teaching_staff->Designation;
                $query->promoted_designation = '';
                $query->designation = $designation;
                $query->promotion_date = '';
                $query->add = 'Add';

                $staff = $query;
                $staff_edit = $query;
                $list = [];

            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                $staff = $query[0];

                for ($i = 0; $i < count($query); $i++) {
                    $query[$i]->designation = $designation;
                }

                $list = $query;

                $staff_edit = new PromotionDetails;
                $staff_edit->add = 'Add';
                $staff_edit->id = '';
                $staff_edit->current_designation = $teaching_staff->Designation;
                $staff_edit->promoted_designation = '';
                $staff_edit->designation = $designation;
                $staff_edit->promotion_date = '';

            }

        } else {

            // dd($request);

            $query_one = PromotionDetails::where(['user_name_id' => $request->user_name_id])->get();
            $query_two = PromotionDetails::where(['id' => $request->id])->get();

            if (!$query_two->count() <= 0) {

                $query_one[0]['user_name_id'] = $request->user_name_id;

                $query_one[0]['name'] = $request->name;

                $query_two[0]['add'] = 'Update';

                $staff = $query_one[0];

                for ($i = 0; $i < count($query_one); $i++) {
                    $query_one[$i]->designation = $designation;
                }
                for ($i = 0; $i < count($query_two); $i++) {
                    $query_two[$i]->designation = $designation;
                }

                $list = $query_one;
                // dd($list);
                $staff_edit = $query_two[0];
            } else {
                dd('Error');
            }
        }
        $roles = Role::pluck('title', 'id');
        // dd($roles);
        $check = 'promotion_details';
        $check_staff_1 = TeachingStaff::where(['user_name_id' => $request->user_name_id])->get();

        if (count($check_staff_1) > 0) {
            return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit', 'roles'));
        } else {
            $check_staff_2 = NonTeachingStaff::where(['user_name_id' => $request->user_name_id])->get();

            if (count($check_staff_2) > 0) {
                return view('admin.StaffProfile(non_tech).staff', compact('staff', 'check', 'list', 'staff_edit', 'roles'));
            }
        }
    }

    public function staff_update(Request $request)
    {
        // dd($request);
        $promoted_role = Role::where(['id' => $request->promoted_designation])->first();
        $current_role = Role::where(['id' => $request->current_designation])->first();
        if (!empty($promoted_role)) {
            // dd($role);
            $user = User::where(['id' => $request->user_name_id])->first();
            if (!$request->id == 0 || $request->id != '') {

                $phd_check = PromotionDetails::where(['user_name_id' => $request->user_name_id, 'id' => $request->id])->update([
                    'current_designation' => $current_role->id,
                    'promoted_designation' => $promoted_role->id,
                    'promotion_date' => $request->promotion_date,
                    'status' => 1,
                ]);

                $teach_staff = TeachingStaff::where(['user_name_id' => $request->user_name_id])->get();
            } else {
                $phd_check = false;
            }

            if ($phd_check) {

                if (count($teach_staff) > 0) {
                    $update_teach_staff = TeachingStaff::where(['user_name_id' => $request->user_name_id])->update([
                        'Designation' => $promoted_role->title,
                    ]);
                }

                $non_teach_staff = NonTeachingStaff::where(['user_name_id' => $request->user_name_id])->get();

                if (count($non_teach_staff) > 0) {
                    $update_teach_staff = NonTeachingStaff::where(['user_name_id' => $request->user_name_id])->update([
                        'Designation' => $promoted_role->title,
                    ]);
                }

                if($user){
                    $user->roles()->sync($request->input('roles', $promoted_role->id));
                }

                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

            } else {

                $phd = new PromotionDetails;

                $phd->current_designation = $current_role->id;
                $phd->promoted_designation = $promoted_role->id;
                $phd->promotion_date = $request->promotion_date;
                $phd->status = 1;
                $phd->user_name_id = $request->user_name_id;
                $phd->save();

                if ($phd) {
                    $teach_staff = TeachingStaff::where(['user_name_id' => $request->user_name_id])->get();

                    if (count($teach_staff) > 0) {
                        $update_teach_staff = TeachingStaff::where(['user_name_id' => $request->user_name_id])->update([
                            'Designation' => $promoted_role->title,
                        ]);
                    }

                    $non_teach_staff = NonTeachingStaff::where(['user_name_id' => $request->user_name_id])->get();

                    if (count($non_teach_staff) > 0) {
                        $update_teach_staff = NonTeachingStaff::where(['user_name_id' => $request->user_name_id])->update([
                            'Designation' => $promoted_role->title,
                        ]);
                    }

                    if($user){
                        $user->roles()->sync($request->input('roles', $promoted_role->id));
                    }


                    $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                    // dd($staff);
                } else {
                    dd('Error');
                }
            }

        } else {
            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
        }
        return redirect()->route('admin.promotion-details.staff_index', $staff);
    }

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
        // dd($id);
        $find = PromotionDetails::find($id);
        $find->delete();
        return back();
    }
}
