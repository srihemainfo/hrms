<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePersonalDetailRequest;
use App\Models\NonTeachingStaff;
use App\Models\PersonalDetail;
use App\Models\TeachingStaff;
use App\Models\StaffOldCurrentStatus;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmploymentDetailsController extends Controller
{
    public function staff_index(Request $request)
    {
        abort_if(Gate::denies('employment_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request) {

            $query = PersonalDetail::where(['user_name_id' => $request->user_name_id])->get();
        }

        if ($query->count() <= 0) {

            $query->id = '';
            $query->BiometricID = '';
            $query->AICTE = '';
            $query->DOJ = '';
            $query->DOR = '';
            $query->au_card_no = '';
            $query->employment_type = '';
            $query->employment_status = '';
            $query->rit_club_incharge = '';
            $query->future_tech_membership = '';
            $query->future_tech_membership_type = '';
            $query->name = $request->name;
            $query->user_name_id = $request->user_name_id;
            $query->add = 'Add';

            $staff = $query;
        } else {

            $query[0]->id = $request->user_name_id;
            $query[0]->name = $request->name;
            $query[0]->add = "Update";

            $staff = $query[0];
        }
        $check = 'employee_details';
        $check_staff_1 = TeachingStaff::where(['user_name_id' => $request->user_name_id])->get();

        if (count($check_staff_1) > 0) {
            return view('admin.StaffProfile.staff', compact('staff', 'check'));
        } else {
            $check_staff_2 = NonTeachingStaff::where(['user_name_id' => $request->user_name_id])->get();

            if (count($check_staff_2) > 0) {
                return view('admin.StaffProfile(non_tech).staff', compact('staff', 'check'));
            }
        }
    }

    public function staff_update(UpdatePersonalDetailRequest $request, PersonalDetail $personalDetail)
    {

        $personal = $personalDetail->where('user_name_id', $request->user_name_id)->update([
            'BiometricID' => $request->BiometricID,
            'AICTE' => $request->AICTE,
            'DOJ' => $request->DOJ,
            'DOR' => $request->DOR,
            'au_card_no' => $request->au_card_no,
            'employment_type' => $request->employment_type,
            'employment_status' => $request->employment_status,
            'rit_club_incharge' => $request->rit_club_incharge,
            'future_tech_membership' => $request->future_tech_membership,
            'future_tech_membership_type' => $request->future_tech_membership_type,
        ]);

        if ($personal) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
        } else {

            $personalDetail = PersonalDetail::create([
                'name' => $request->name,
                'BiometricID' => $request->BiometricID,
                'AICTE' => $request->AICTE,
                'DOJ' => $request->DOJ,
                'DOR' => $request->DOR,
                'au_card_no' => $request->au_card_no,
                'employment_type' => $request->employment_type,
                'employment_status' => $request->employment_status,
                'rit_club_incharge' => $request->rit_club_incharge,
                'future_tech_membership' => $request->future_tech_membership,
                'future_tech_membership_type' => $request->future_tech_membership_type,
                'user_name_id' => $request->user_name_id,
            ]);

            $teach_staff_update = TeachingStaff::where('user_name_id', $request->user_name_id)->update([
                'BiometricID' => $request->BiometricID,
                'AICTE' => $request->AICTE,
                'DOJ' => $request->DOJ,
                'DOR' => $request->DOR,
                'au_card_no' => $request->au_card_no,
            ]);

            if ($personalDetail) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
            } else {
            }
        }

        $status_check = StaffOldCurrentStatus::where('user_name_id', $request->user_name_id)->get()->last();
        $tech_or_nontech = NonTeachingStaff::where('user_name_id', $request->user_name_id)->select('user_name_id','Dept','Designation')->first();
        if($tech_or_nontech != '' ){
                $staff_status = 'NonTeaching';
                $Dept = $tech_or_nontech->Dept;
                $Designation = $tech_or_nontech->Designation;
        }else{
        $tech_or_nontech = TeachingStaff::where('user_name_id', $request->user_name_id)->select('user_name_id','Dept','Designation')->first();
                
            $staff_status = 'Teaching';
            $Dept = $tech_or_nontech->Dept;
            $Designation = $tech_or_nontech->Designation;
        }

        if ($status_check != '') {
            if ($status_check->status  != $request->employment_status) {
                
                $date1 = strtotime($status_check->start_time);
                $date2 = strtotime(now());
                $diff = $date2 - $date1;
                $days = floor($diff / (60 * 60 * 24));
                $status_update = $status_check->update(['end_time' => now(), 'total_days' => $days,'current_status'=>$request->employment_status]);

                // create_new Status
                $status_create = StaffOldCurrentStatus::create([
                    'staff_name' => $request->name,
                    'user_name_id' => $request->user_name_id,
                    'status' => $request->employment_status,
                    'teach_or_nonteach' => $staff_status,
                    'Dept' =>  $Dept ?  $Dept : NULL,
                    'Designation' =>  $Designation ?  $Designation : NULL,
                    'start_time' => now(),
                    'updated_by' => auth()->user()->id,
                ]);
            }
        } else {
            $status_create = StaffOldCurrentStatus::create([
                'staff_name' => $request->name,
                'user_name_id' => $request->user_name_id,
                'status' => $request->employment_status,
                'current_status' => $request->employment_status,
                'teach_or_nonteach' => $staff_status,
                'Dept' =>  $Dept ?  $Dept : NULL,
                'Designation' =>  $Designation ?  $Designation : NULL,
                'start_time' => now(),
                'end_time' => now(),
                'updated_by' => auth()->user()->id,
            ]);
        }


        return redirect()->route('admin.employment-details.staff_index', $staff);
    }
}
