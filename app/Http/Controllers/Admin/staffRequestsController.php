<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseEnrollMaster;
use App\Models\StaffAlteration;
use App\Models\TeachingStaff;
use App\Models\ToolsCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class staffRequestsController extends Controller
{
    public function index(Request $request)
    {


        if (isset($request->status2) && isset($request->status)) {
            if ($request->status2 == 'Sent') {
                $dbColumn = 'from_id';
            } elseif ($request->status2 == 'Received') {
                $dbColumn = 'to_id';
            }

            switch ($request->status) {
                case 'Pending':
                    $status = 'Pending';
                    $statusMaintained = '0';
                    break;
                case 'Approved':
                    $status = 'Approved';
                    $statusMaintained = '1';
                    break;
                case 'Rejected':
                    $status = 'Rejected';
                    $statusMaintained = '2';
                    break;
                default:
                    break;
            }

            $staffAlter = StaffAlteration::where(['status' => $statusMaintained, $dbColumn => auth()->user()->id])->get();

            // dd($staffAlter);
            if ($staffAlter->count() > 0) {
                foreach ($staffAlter as $checkings) {
                    $checkings->classID = $checkings->classname;
                    $checkings->class_period = $checkings->period;
                    $checkings->to_id = $checkings->to_id;
                    $checkings->timeDeff = $checkings->created_at ? $checkings->created_at->diffForHumans() : '';

                    $staffName = TeachingStaff::where('user_name_id', $checkings->from_id)->first();
                    if ($checkings->from_id == auth()->user()->id) {
                        $staffName = TeachingStaff::where('user_name_id', $checkings->to_id)->first();

                    }
                    if ($staffName) {
                        $checkings->name = $staffName->name;
                        $checkings->staff_code = $staffName->StaffCode;

                    }

                    $enroll = CourseEnrollMaster::find($checkings->classname);
                    if ($enroll) {
                        $className = explode('/', $enroll->enroll_master_number);
                        if (isset($className[1])) {
                            $get_short_form = ToolsCourse::where('name', $className[1])->value('short_form');
                            if ($get_short_form) {
                                $className[1] = $get_short_form;
                            }
                            $checkings->classname = $className[1] . ' / ' . $className[3] . ' / ' . $className[4];
                        }
                    }

                    //     $checkings->approveButton = "<button class='btn btn-xs btn-primary' type='submit' name='approve' value='$checkings->id'>Approve</button>";
                    //     $checkings->rejectButton = "<button class='btn btn-xs btn-danger' type='submit' name='reject' value='$checkings->id'>Reject</button>";
                }
                $checking = $staffAlter;

            } else {
                $checking = [];

            }
            $status2 = $request->status2;
            $status = $request->status;
        } else {
            $checking = [];
            $status = 'Pending';
            $status2 = 'Sent';
        }
        return view('admin.staffAlteration.index', compact('checking', 'status', 'status2'));
    }
}
