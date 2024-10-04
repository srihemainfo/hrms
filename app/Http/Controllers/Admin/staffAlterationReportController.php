<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseEnrollMaster;
use App\Models\StaffAlteration;
use App\Models\TeachingStaff;
use App\Models\ToolsCourse;
use Illuminate\Http\Request;

class staffAlterationReportController extends Controller
{
    public function index(Request $request)
    {
        $status = '';

        $role_id = auth()->user()->roles[0]->id;
        if ($role_id == 14) {
            $dept = auth()->user()->dept;
        } else {
            $dept = null;
        }

        if (isset($request->status)) {
            switch ($request->status) {
                case 'Pending':
                    $status = 'Pending';
                    $staffAlter = StaffAlteration::where('status', '0')->select([
                        'classname',
                        'period',
                        'from_id',
                        'to_id',
                        'created_at',
                        'from_date',
                        'to_date',
                    ])->get();
                    break;
                case 'Approved':
                    $status = 'Approved';
                    $staffAlter = StaffAlteration::where('status', '1')->select([
                        'classname',
                        'period',
                        'from_id',
                        'to_id',
                        'created_at',
                        'from_date',
                        'to_date',
                    ])->get();
                    break;
                case 'Rejected':
                    $status = 'Rejected';
                    $staffAlter = StaffAlteration::where('status', '2')->select([
                        'classname',
                        'period',
                        'from_id',
                        'to_id',
                        'created_at',
                        'from_date',
                        'to_date',
                    ])->get();
                    break;
                default:
                    $staffAlter = StaffAlteration::where('status', '0')->select([
                        'classname',
                        'period',
                        'from_id',
                        'to_id',
                        'created_at',
                        'from_date',
                        'to_date',
                    ])->get();
                    break;
            }
        } else {
            $staffAlter = StaffAlteration::where('status', '0')->select([
                'classname',
                'period',
                'from_id',
                'to_id',
                'created_at',
                'from_date',
                'to_date',
            ])->get();
            $status = 'Pending';
        }


        $staffAlter = $staffAlter->toArray();
        if (count($staffAlter) > 0) {
            foreach ($staffAlter as $i => $checkings) {
                $staffAlter[$i]['classID'] = $checkings['classname'];

                if ($dept != null) {
                    $fromestaffName = TeachingStaff::where(['user_name_id' => $checkings['from_id'], 'Dept' => $dept])->first();
                } else {
                    $fromestaffName = TeachingStaff::where('user_name_id', $checkings['from_id'])->first();
                }

                $tostaffName = TeachingStaff::where('user_name_id', $checkings['to_id'])->first();
                if ($fromestaffName != '' && $fromestaffName != null) {

                    $staffAlter[$i]['fromestaffName'] = $fromestaffName->name;
                    $staffAlter[$i]['fromr_staff_code'] = $fromestaffName->StaffCode;

                    if ($tostaffName) {
                        $staffAlter[$i]['tostaffName'] = $tostaffName->name;
                        $staffAlter[$i]['to_staff_code'] = $tostaffName->StaffCode;
                    }

                    $enroll = CourseEnrollMaster::find($checkings['classname']);
                    if ($enroll) {
                        $className = explode('/', $enroll->enroll_master_number);
                        if (isset($className[1])) {
                            $get_short_form = ToolsCourse::where('name', $className[1])->value('short_form');
                            if ($get_short_form) {
                                $className[1] = $get_short_form;
                            }
                            $staffAlter[$i]['classname'] = $className[1] . ' / ' . $className[3] . ' / ' . $className[4];
                        }
                    }
                    $period = match ($checkings['period']) {
                        '1' => 'First Period',
                        '2' => 'Second Period',
                        '3' => 'Third Period',
                        '4' => 'Fourth Period',
                        '5' => 'Fifth Period',
                        '6' => 'Sixth Period',
                        '7' => 'Seventh Period',
                        default => 'Unknown Period',
                    };
                    $staffAlter[$i]['class_period'] = $period;
                    if($checkings['from_date'] != '' && $checkings['to_date'] != ''){
                        $theDate = '(' .$checkings['from_date'] . ')' . '-' . '(' .$checkings['to_date']. ')';
                    }else{
                        $theDate = '';
                    }
                    $staffAlter[$i]['leaveDate'] = $theDate;

                } else {
                    unset($staffAlter[$i]);
                }
            }
            $staffAlter = array_values($staffAlter);
            $checking = $staffAlter;

        } else {
            $checking = [];

        }

        return view('admin.staffAlteration.staffalterationReport', compact('checking', 'status'));
    }
}
