<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NonTeachingStaff;
use App\Models\PersonalDetail;
use App\Models\Staffs;
use Illuminate\Http\Request;

class EmploymentDetailsController extends Controller
{
    public function staff_index(Request $request)
    {
        // abort_if(Gate::denies('employment_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        $check_staff_1 = Staffs::where(['user_name_id' => $request->user_name_id])->get();

        if (count($check_staff_1) > 0) {
            return view('admin.StaffProfile.staff', compact('staff', 'check'));
        } else {
            $check_staff_2 = NonTeachingStaff::where(['user_name_id' => $request->user_name_id])->get();

            if (count($check_staff_2) > 0) {
                return view('admin.StaffProfile(non_tech).staff', compact('staff', 'check'));
            }
        }
    }

    public function staff_update(Request $request, PersonalDetail $personalDetail)
    {

        $personal = $personalDetail->where('user_name_id', $request->user_name_id)->update([
            'BiometricID' => $request->BiometricID,
            // 'AICTE' => $request->AICTE,
            'DOJ' => $request->DOJ,
            'DOR' => $request->DOR,
            // 'au_card_no' => $request->au_card_no,
            // 'employment_type' => $request->employment_type,
            'employment_status' => $request->employment_status,
            // 'rit_club_incharge' => $request->rit_club_incharge,
            // 'future_tech_membership' => $request->future_tech_membership,
            // 'future_tech_membership_type' => $request->future_tech_membership_type,
        ]);

        $teach_staff_update = Staffs::where('user_name_id', $request->user_name_id)->update([
            'biometric' => $request->BiometricID,
            'DOJ' => $request->DOJ,
            'status' => $request->employment_status,
            'DOR' => $request->DOR,
        ]);

        if ($personal) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
        } else {

            $personalDetail = PersonalDetail::create([
                'name' => $request->name,
                'BiometricID' => $request->BiometricID,
                // 'AICTE' => $request->AICTE,
                'DOJ' => $request->DOJ,
                'DOR' => $request->DOR,
                // 'au_card_no' => $request->au_card_no,
                // 'employment_type' => $request->employment_type,
                'employment_status' => $request->employment_status,
                // 'rit_club_incharge' => $request->rit_club_incharge,
                // 'future_tech_membership' => $request->future_tech_membership,
                // 'future_tech_membership_type' => $request->future_tech_membership_type,
                'user_name_id' => $request->user_name_id,
            ]);

            $teach_staff_update = Staffs::where('user_name_id', $request->user_name_id)->update([
                'biometric' => $request->BiometricID,
                'DOJ' => $request->DOJ,
                'status' => $request->employment_status,
                'DOR' => $request->DOR,
            ]);

            if ($personalDetail) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
            } else {
            }
        }

        return redirect()->route('admin.employment-details.staff_index', $staff);
    }
}
