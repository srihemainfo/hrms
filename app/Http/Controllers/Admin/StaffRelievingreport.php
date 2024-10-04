<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\TeachingStaff;
use App\Models\PersonalDetail;
use App\Models\ToolsDepartment;
use App\Models\NonTeachingStaff;
use App\Http\Controllers\Controller;
// staffRelievingreport



class StaffRelievingreport extends Controller
{
    public function index(){
            $datas=[];
            $department=ToolsDepartment::pluck('name','id');
        return view('admin.staffRelievingreport.index',compact('datas','department'));
    }

    public function search(Request $request){
        // dd($request);
        if ($request) {
            $department = $request->input('department');
            // $from_date = $request->input('From_date');
            // $to_date = $request->input('to_date');
            $status =$request->status;
            $data = PersonalDetail::where('employment_status', $status);

            // if (!empty($from_date) && !empty($to_date)) {
            //     $data->whereBetween('DOR', [$from_date, $to_date]);
            // }

            if ($department != 'ADMIN' && $department != 'CIVIL') {
                $data->whereIn('user_name_id', function ($query) use ($department) {
                    $query->select('user_name_id')
                        ->from('teaching_staffs')
                        ->where('Dept', $department);
                });
            }else if(!empty($department)){
                $data->whereIn('user_name_id', function ($query) use ($department) {
                    $query->select('user_name_id')
                        ->from('non_teaching_staffs')
                        ->where('Dept', $department);
                });
            }

            $data = $data->select('user_name_id','name','StaffCode','DOJ','DOR')->get();
           

            foreach ($data as $datas) {
                $user_id = $datas->user_name_id;
                $datas->DOJ = $datas->DOJ ? date('d-m-Y',strtotime($datas->DOJ)): '';
                $datas->DOR = $datas->DOR ? date('d-m-Y',strtotime($datas->DOR)): '';
               
                $datas->user_id_staff_code = $datas->name.'('.$datas->StaffCode.')';
                $staff = TeachingStaff::where('user_name_id', $user_id)->first();
                if ($staff != '') {
                    $datas->department = $staff->Dept;
                }else{
                    $non_tech_staff = NonTeachingStaff::where('user_name_id', $user_id)->first();
                    if($non_tech_staff != ''){
                        $datas->department = $non_tech_staff->Dept;
                    }else{
                        $datas->department = null;
                    }
                }
            }

            // dd($data);

            return response()->json(['datas' => $data]);
        }


    }
}
