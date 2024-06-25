<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\PhdDetail;
use Illuminate\Http\Request;
use App\Models\TeachingStaff;
use App\Models\NonTeachingStaff;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class phdDetailController extends Controller
{

    public function staff_index(Request $request)
    {

        abort_if(Gate::denies('phd_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (!$request->updater) {

            $query = PhdDetail::where(['user_name_id' => $request->user_name_id])->get();
// dd($query);
            if ($query->count() <= 0) {

                $query->user_name_id = $request->user_name_id;
                $query->name = $request->name;
                $query->id = '';
                $query->institute_name = '';
                $query->university_name = '';
                $query->thesis_title = '';
                $query->research_area = '';
                $query->supervisor_name = '';
                $query->supervisor_details = '';
                $query->status = '';
                $query->registration_year = '';
                $query->viva_date = '';
                $query->total_years = '';
                $query->mode = '';
                $query->add = 'Add';

                $staff = $query;
                $staff_edit = $query;
                $list = [];

            } else {

                $query[0]['user_name_id'] = $request->user_name_id;

                $query[0]['name'] = $request->name;

                $staff = $query[0];

                $list = $query;

                $staff_edit = new PhdDetail;
                $staff_edit->add = 'Add';
                $staff_edit->id = '';
                $staff_edit->institute_name = '';
                $staff_edit->university_name = '';
                $staff_edit->thesis_title = '';
                $staff_edit->research_area = '';
                $staff_edit->supervisor_name = '';
                $staff_edit->supervisor_details = '';
                $staff_edit->status = '';
                $staff_edit->registration_year = '';
                $staff_edit->viva_date = '';
                $staff_edit->total_years = '';
                $staff_edit->mode = '';

            }

        } else {

            // dd($request);

            $query_one = PhdDetail::where(['user_name_id' => $request->user_name_id])->get();
            $query_two = PhdDetail::where(['id' => $request->id])->get();

            if (!$query_two->count() <= 0) {

                $query_one[0]['user_name_id'] = $request->user_name_id;

                $query_one[0]['name'] = $request->name;

                $query_two[0]['add'] = 'Update';

                $staff = $query_one[0];

                $list = $query_one;
                // dd($list);
                $staff_edit = $query_two[0];
            } else {
                dd('Error');
            }
        }

        $check = 'phd_details';
        $check_staff_1 = TeachingStaff::where(['user_name_id' => $request->user_name_id])->get();

        if (count($check_staff_1) > 0) {
            return view('admin.StaffProfile.staff', compact('staff', 'check', 'list', 'staff_edit'));
        } else {
            $check_staff_2 = NonTeachingStaff::where(['user_name_id' => $request->user_name_id])->get();

            if (count($check_staff_2) > 0) {
                return view('admin.StaffProfile(non_tech).staff', compact('staff', 'check', 'list', 'staff_edit'));
            }
        }
    }

    public function staff_update(Request $request)
    {
        abort_if(Gate::denies('phd_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $request->validate([
            'registration_year' => 'required|date_format:Y-m',
        ]);

        $registration_year = $request->input('registration_year');
        $formattedDate = date('Y-m-d', strtotime($registration_year));

        if (!$request->id == 0 || $request->id != '') {

            $phd_check = PhdDetail::where(['user_name_id' => $request->user_name_id, 'id' => $request->id])->update([
                'institute_name' => $request->institute_name,
                'university_name' => $request->university_name,
                'thesis_title' => $request->thesis_title,
                'research_area' => $request->research_area,
                'supervisor_name' => $request->supervisor_name,
                'supervisor_details' => $request->supervisor_details,
                'status' => $request->status,
                'registration_year' => $formattedDate,
                'viva_date' => $request->viva_date,
                'total_years' => $request->total_years,
                'mode' => $request->mode,
            ]);

        } else {
            $phd_check = false;
        }

        if ($phd_check) {

            $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];

        } else {

            $phd = new PhdDetail;

            $phd->institute_name = $request->institute_name;
            $phd->university_name = $request->university_name;
            $phd->thesis_title = $request->thesis_title;
            $phd->research_area = $request->research_area;
            $phd->supervisor_name = $request->supervisor_name;
            $phd->supervisor_details = $request->supervisor_details;
            $phd->status = $request->status;
            $phd->registration_year = $formattedDate;
            $phd->viva_date = $request->viva_date;
            $phd->total_years = $request->total_years;
            $phd->mode = $request->mode;
            $phd->user_name_id = $request->user_name_id;
            $phd->save();

            if ($phd) {
                $staff = ['user_name_id' => $request->user_name_id, 'name' => $request->name];
                // dd($staff);
            } else {
                dd('Error');
            }
        }

        return redirect()->route('admin.phd-details.staff_index', $staff);
    }

    public function destroy($request)
    {
        abort_if(Gate::denies('phd_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $detail = PhdDetail::find($request);

        $detail->delete();

        return back();
    }
}
