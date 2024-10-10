<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HrmRequestLeaf;
use App\Models\LeaveType;
use App\Models\NonTeachingStaff;
use App\Models\Staffs;
use App\Models\TeachingStaff;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class StaffLeaveReportController extends Controller
{
    public function index(Request $request)
    {

        abort_if(Gate::denies('staff_leave_report_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request) {
            $leave_type = LeaveType::pluck('name', 'id');
            // $departments = ToolsDepartment::pluck('name', 'id');
        }

        return view('admin.Reports.leavereport', compact('leave_type'));

    }

    public function index_rep(Request $request)
    {

        abort_if(Gate::denies('staff_leave_report_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (!empty($request['data'])) {

            $validatedData = [
                'leave_type' => $request['data']['leave_type'],
                'start_date' => $request['data']['start_date'],
                'end_date' => $request['data']['end_date'],
                // 'department' => $request['data']['department'],
            ];
            $leave_rep = HrmRequestLeaf::select('user_id', DB::raw('SUM(total_days) as total_days'), 'leave_type')
                ->join('staffs', 'hrm_request_leaves.user_id', '=', 'staffs.user_name_id')
                ->where('hrm_request_leaves.status', 'Approved')
                ->when(!empty($validatedData['start_date']) && !empty($validatedData['end_date']), function ($query) use ($validatedData) {
                    return $query->whereBetween('from_date', [$validatedData['start_date'], $validatedData['end_date']]);
                })
            // ->when(!empty($validatedData['department']), function ($query) use ($validatedData) {
            //     return $query->where('staffs.Dept', $validatedData['department']);
            // })
                ->when(!empty($validatedData['leave_type']), function ($query) use ($validatedData) {
                    return $query->where('hrm_request_leaves.leave_type', $validatedData['leave_type']);
                })
                ->groupBy('user_id', 'leave_type')
                ->get();

            $finally = [];

            $mergedData = collect($leave_rep)->groupBy('user_id')->each(function ($users) use (&$finally) {

                if ($users->count() > 0) {

                    $teaching_Staff = Staffs::where(['user_name_id' => $users[0]['user_id']])->first();
                    if ($teaching_Staff != '') {
                        $Staff = $teaching_Staff;
                    }

                    $user = new HrmRequestLeaf();
                    $user->name = $Staff->name;
                    $user->Designation = $Staff->designation_id;
                    // $user->Dept = $Staff->Dept;
                    $user->StaffCode = $Staff->employee_id;
                    // $user->casual_leave = $teaching_Staff->casual_leave;
                    $user->casual_leave_taken = '';
                    $user->sick_leave = '';
                    $user->exam_od = '';
                    $user->training_od = '';
                    $user->compensation = '';
                    $user->half_day_leave = '';
                    // $user->assigning_staff = '';

                    foreach ($users as $leave) {
                        if ($leave['leave_type'] == 1) {
                            $user->casual_leave_taken = $leave['total_days'];
                        }
                        if ($leave['leave_type'] == 2) {
                            $user->sick_leave = $leave['total_days'];
                        }
                        if ($leave['leave_type'] == 3) {
                            $user->exam_od = $leave['total_days'];
                        }
                        if ($leave['leave_type'] == 4) {
                            $user->training_od = $leave['total_days'];
                        }
                        if ($leave['leave_type'] == 5) {
                            $user->compensation = $leave['total_days'];
                        }
                        if ($leave['leave_type'] == 6) {
                            $user->half_day_leave = $leave['total_days'];
                        }
                        // $assigningStaff = TeachingStaff::where('user_name_id', $leave['assigning_staff'])->first();
                        // $user->assigning_staff = $assigningStaff ? $assigningStaff->name : null;
                        // dd($leave);

                    }

                    $finally[] = $user;

                }
            });

            // dd($finally);
            return response()->json(['data' => $finally]);

        }
        // elseif (empty($request['data']['leave_type']) && empty($request['data']['department'])) {

        //     $leave_type = $request->data['leave_type'];
        //     $month = $request->data['month'];
        //     $year = $request->data['year'];

        //     $leave_rep = HrmRequestLeaf::select('user_id', DB::raw('SUM(total_days) as total_days'))
        //         ->where(['status' => 'Approved', 'leave_type' => $leave_type])
        //         ->whereRaw('YEAR(from_date) = ?', [$year])
        //         ->whereRaw('MONTH(from_date) = ?', [$month])
        //         ->groupBy('user_id')
        //         ->get();

        //     $finally = [];

        //     $mergedData = collect($leave_rep)->groupBy('user_id')->each(function ($users) use (&$finally, $leave_type) {

        //         if ($users->count() > 0) {

        //             $teaching_Staff = TeachingStaff::where(['user_name_id' => $users[0]['user_id']])->select(['name', 'Designation', 'Dept', 'casual_leave'])->first();

        //             $user = new HrmRequestLeaf();
        //             $user->name = $teaching_Staff->name;
        //             $user->Designation = $teaching_Staff->Designation;
        //             $user->Dept = $teaching_Staff->Dept;
        //             // $user->casual_leave = $teaching_Staff->casual_leave;
        //             $user->casual_leave_taken = '';
        //             $user->admin_od = '';
        //             $user->exam_od = '';
        //             $user->training_od = '';
        //             $user->compensation = '';

        //             foreach ($users as $leave) {
        //                 if ($leave_type == 1) {
        //                     $user->casual_leave_taken = $leave['total_days'];
        //                 }
        //                 if ($leave_type == 2) {
        //                     $user->admin_od = $leave['total_days'];
        //                 }
        //                 if ($leave_type == 3) {
        //                     $user->exam_od = $leave['total_days'];
        //                 }
        //                 if ($leave_type == 4) {
        //                     $user->training_od = $leave['total_days'];
        //                 }
        //                 if ($leave_type == 5) {
        //                     $user->compensation = $leave['total_days'];
        //                 }
        //             }

        //             $finally[] = $user;
        //         }
        //     });

        //     return response()->json(['data' => $finally]);

        // } else {
        //     if ($request->data['leave_type'] != '') {

        //         $leave_type = $request->data['leave_type'];

        //         $leave_rep = HrmRequestLeaf::select('user_id', DB::raw('SUM(total_days) as total_days'))
        //             ->where(['status' => 'Approved', 'leave_type' => $leave_type])
        //             ->groupBy('user_id')
        //             ->get();

        //         $finally = [];

        //         $mergedData = collect($leave_rep)->groupBy('user_id')->each(function ($users) use (&$finally, $leave_type) {

        //             if ($users->count() > 0) {

        //                 $teaching_Staff = TeachingStaff::where(['user_name_id' => $users[0]['user_id']])->select(['name', 'Designation', 'Dept', 'casual_leave'])->first();

        //                 $user = new HrmRequestLeaf();
        //                 $user->name = $teaching_Staff->name;
        //                 $user->Designation = $teaching_Staff->Designation;
        //                 $user->Dept = $teaching_Staff->Dept;
        //                 // $user->casual_leave = $teaching_Staff->casual_leave;
        //                 $user->casual_leave_taken = '';
        //                 $user->admin_od = '';
        //                 $user->exam_od = '';
        //                 $user->training_od = '';
        //                 $user->compensation = '';

        //                 foreach ($users as $leave) {
        //                     if ($leave_type == 1) {
        //                         $user->casual_leave_taken = $leave['total_days'];
        //                     }
        //                     if ($leave_type == 2) {
        //                         $user->admin_od = $leave['total_days'];
        //                     }
        //                     if ($leave_type == 3) {
        //                         $user->exam_od = $leave['total_days'];
        //                     }
        //                     if ($leave_type == 4) {
        //                         $user->training_od = $leave['total_days'];
        //                     }
        //                     if ($leave_type == 5) {
        //                         $user->compensation = $leave['total_days'];
        //                     }
        //                 }

        //                 $finally[] = $user;
        //             }
        //         });

        //         return response()->json(['data' => $finally]);

        //     } else {
        //         if ($request->data['month'] != '' && $request->data['year'] != '') {

        //             $month = $request->data['month'];
        //             $year = $request->data['year'];

        //             $leave_rep = HrmRequestLeaf::select(['user_id', 'leave_type', DB::raw('SUM(total_days) as total_days')])
        //                 ->where(['status' => 'Approved'])
        //                 ->whereRaw('YEAR(from_date) = ?', [$year])
        //                 ->whereRaw('MONTH(from_date) = ?', [$month])
        //                 ->groupBy(['user_id', 'leave_type'])
        //                 ->get();

        //             $finally = [];

        //             $mergedData = collect($leave_rep)->groupBy('user_id')->each(function ($users) use (&$finally) {

        //                 if ($users->count() > 0) {

        //                     $teaching_Staff = TeachingStaff::where(['user_name_id' => $users[0]['user_id']])->select(['name', 'Designation', 'Dept', 'casual_leave'])->first();

        //                     $user = new HrmRequestLeaf();
        //                     $user->name = $teaching_Staff->name;
        //                     $user->Designation = $teaching_Staff->Designation;
        //                     $user->Dept = $teaching_Staff->Dept;
        //                     // $user->casual_leave = $teaching_Staff->casual_leave;
        //                     $user->casual_leave_taken = '';
        //                     $user->admin_od = '';
        //                     $user->exam_od = '';
        //                     $user->training_od = '';
        //                     $user->compensation = '';

        //                     foreach ($users as $leave) {
        //                         switch ($leave['leave_type']) {
        //                             case 1:
        //                                 $user->casual_leave_taken = $leave['total_days'];
        //                                 break;
        //                             case 2:
        //                                 $user->admin_od = $leave['total_days'];
        //                                 break;
        //                             case 3:
        //                                 $user->exam_od = $leave['total_days'];
        //                                 break;
        //                             case 4:
        //                                 $user->training_od = $leave['total_days'];
        //                                 break;
        //                             case 5:
        //                                 $user->compensation = $leave['total_days'];
        //                                 break;
        //                             default:
        //                                 // Handle unknown leave_type value, if needed
        //                                 break;
        //                         }
        //                     }

        //                     // dd($users);
        //                     $finally[] = $user;
        //                 }
        //             });

        //             return response()->json(['data' => $finally]);

        //         }
        //     }
        // }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

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
}
