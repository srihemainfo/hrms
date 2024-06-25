<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveImplement;
use App\Models\NonTeachingStaff;
use App\Models\StaffBiometric;
use App\Models\TeachingStaff;
use App\Models\TeachingType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LeaveImplementController extends Controller
{
    public function index(Request $request)
    {
        // $getData = LeaveImplement::select('id', 'date', 'staff_type', 'leave_type', 'noon', 'reason')->orderBy('id', 'DESC')->get();
        $type = TeachingType::whereNot('id', 6)->pluck('name', 'id');

        if ($request->ajax()) {
            $query = LeaveImplement::query()->select(sprintf('%s.*', (new LeaveImplement)->table));
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            // $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                return $row;
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('date', function ($row) {
                return $row->date ? $row->date : '';
            });
            $table->editColumn('staff_type', function ($row) {
                if ($row->staff_type == 'tech,non-tech') {
                    return $row->staff_type ? "Teaching Staff & Non Teaching Staff" : '';
                } elseif ($row->staff_type == 'non-tech,tech') {
                    return $row->staff_type ? "Teaching Staff & Non Teaching Staff" : '';
                } elseif ($row->staff_type == 'tech') {
                    return $row->staff_type ? "Teaching Staff" : '';
                } elseif ($row->staff_type == 'non-tech') {
                    return $row->staff_type ? "Non Teaching Staff" : '';
                } else {
                    return $row->staff_type ? $row->staff_type : '';
                }
            });
            $table->editColumn('leave_type', function ($row) {
                return $row->leave_type ? $row->leave_type : '';
            });
            $table->editColumn('half_day', function ($row) {
                return $row->noon ? $row->noon : '';
            });
            $table->editColumn('remark', function ($row) {
                return $row->reason ? $row->reason : '';
            });

            $table->rawColumns(['placeholder']);

            return $table->make(true);
        }
        return view('admin.leaveImplement.index', compact('type'));
    }

    public function store(Request $request)
    {

        if (isset($request->date) && isset($request->staff_type) && isset($request->leave_type)) {
            $date = $request->date;
            $staff_type = $request->staff_type;
            $leave_type = $request->leave_type;
            $day_type = $request->day_type;
            $reason = $request->reason;
            $staffs = '';
            foreach ($staff_type as $staff) {
                $s = TeachingType::where('id', $staff)->select('name')->first();
                $staffs .= $s->name . ',';
            }
            $gotStaff = substr($staffs, 0, -1);
            $gotStaff = str_replace(',', ' & ', $gotStaff);

            if (in_array('1', $staff_type) || in_array('3', $staff_type) || in_array(1, $staff_type) || in_array(3, $staff_type)) {
                $getTechStaff = TeachingStaff::whereIn('role_type', $staff_type)->select('user_name_id')->get();
            } else {
                $getTechStaff = [];
            }

            if (in_array('2', $staff_type) || in_array('4', $staff_type) || in_array('5', $staff_type) || in_array(2, $staff_type) || in_array(4, $staff_type) || in_array(5, $staff_type)) {
                $getNonTechStaff = NonTeachingStaff::whereIn('role_type', $staff_type)->select('user_name_id')->get();
            } else {
                $getNonTechStaff = [];
            }
            if ($day_type != null) {
                $details = $day_type . ' Holiday';
            } else {
                $details = 'Holiday';
            }
            if (count($getTechStaff) > 0) {
                foreach ($getTechStaff as $staff) {
                    $biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $staff->user_name_id])->select('id', 'details')->first();
                    if ($biometric != '') {
                        $theDetail = $details;
                        if ($biometric->details != null) {
                            if ($details != 'Holiday') {
                                $explode = explode(',', $biometric->details);
                                if ($day_type == 'After Noon') {
                                    if (in_array('Early Out', $explode)) {
                                        $theIndex = array_search('Early Out', $explode);
                                        unset($explode[$theIndex]);
                                    }
                                } else if ($day_type == 'Fore Noon') {
                                    if (in_array('Late', $explode)) {
                                        $theIndex = array_search('Late', $explode);
                                        unset($explode[$theIndex]);
                                    } else if (in_array('Too Late', $explode)) {
                                        $theIndex = array_search('Too Late', $explode);
                                        unset($explode[$theIndex]);
                                    }
                                }
                                $implode = implode(',', $explode);
                                if ($implode != '') {
                                    $theDetail = $implode . ',' . $details;
                                }
                            } else {
                                $theDetail = 'Holiday';
                            }
                        }
                        StaffBiometric::where(['id' => $biometric->id])->update([
                            'details' => $theDetail,
                            'update_status' => 1,
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }
            }
            if (count($getNonTechStaff) > 0) {
                foreach ($getNonTechStaff as $staff){
                    $biometric = StaffBiometric::where(['date' => $date, 'user_name_id' => $staff->user_name_id])->select('id', 'details')->first();
                    if ($biometric != '') {
                        $theDetail = $details;
                        if ($biometric->details != null) {
                            if ($details != 'Holiday') {
                                $explode = explode(',', $biometric->details);
                                if ($day_type == 'After Noon') {
                                    if (in_array('Early Out', $explode)) {
                                        $theIndex = array_search('Early Out', $explode);
                                        unset($explode[$theIndex]);
                                    }
                                } else if ($day_type == 'Fore Noon') {
                                    if (in_array('Late', $explode)) {
                                        $theIndex = array_search('Late', $explode);
                                        unset($explode[$theIndex]);
                                    } else if (in_array('Too Late', $explode)) {
                                        $theIndex = array_search('Too Late', $explode);
                                        unset($explode[$theIndex]);
                                    }
                                }

                                $implode = implode(',', $explode);
                                if ($implode != '') {
                                    $theDetail = $implode . ',' . $details;
                                }
                            } else {
                                $theDetail = 'Holiday';
                            }
                        }
                        StaffBiometric::where(['id' => $biometric->id])->update([
                            'details' => $theDetail,
                            'update_status' => 1,
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }
            }
            $insert = LeaveImplement::create([
                'date' => $date,
                'staff_type' => $gotStaff,
                'leave_type' => $leave_type,
                'noon' => $day_type != null ? $day_type : null,
                'reason' => $reason,
            ]);
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }
    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $leave = LeaveImplement::where('id', $request->id)->select('id', 'date', 'staff_type', 'leave_type', 'noon')->first();
            if ($leave != '') {
                $staff_type = explode(' & ', $leave->staff_type);
                $staff_id = [];
                foreach ($staff_type as $staffs) {
                    $s = TeachingType::where('name', $staffs)->select('id')->first();
                    $staff_id[] = $s->id;
                }

                if (in_array('1', $staff_id) || in_array('3', $staff_id) || in_array(1, $staff_id) || in_array(3, $staff_id)) {
                    $getTechStaff = TeachingStaff::whereIn('role_type', $staff_id)->select('user_name_id')->get();
                } else {
                    $getTechStaff = [];
                }

                if (in_array('2', $staff_id) || in_array('4', $staff_id) || in_array('5', $staff_id) || in_array(2, $staff_id) || in_array(4, $staff_id) || in_array(5, $staff_id)) {
                    $getNonTechStaff = NonTeachingStaff::whereIn('role_type', $staff_id)->select('user_name_id')->get();
                } else {
                    $getNonTechStaff = [];
                }
                if ($leave->noon != null) {
                    $theDetail = $leave->noon . ' ' . $leave->leave_type;
                } else {
                    $theDetail = $leave->leave_type;
                }

                if (count($getTechStaff) > 0) {
                    foreach ($getTechStaff as $staff) {
                        $biometric = StaffBiometric::where(['date' => $leave->date, 'user_name_id' => $staff->user_name_id])->select('id', 'details', 'update_status', 'updated_at')->first();
                        if ($biometric != '') {

                            if ($biometric->details == $theDetail) {
                                $details = null;
                            } else {
                                if (strpos($staff_biometric->details, ',') !== false) {
                                    $explode = explode(',', $staff_biometric->details);
                                    if (in_array('Holiday', $explode) && $theDetail == 'Holiday') {
                                        $theIndex = array_search('Holiday', $explode);
                                        unset($explode[$theIndex]);
                                    } else if (in_array('Fore Noon Holiday', $explode) && $theDetail == 'Fore Noon Holiday') {
                                        $theIndex = array_search('Fore Noon Holiday', $explode);
                                        unset($explode[$theIndex]);
                                    } else if (in_array('After Noon Holiday', $explode) && $theDetail == 'After Noon Holiday') {
                                        $theIndex = array_search('After Noon Holiday', $explode);
                                        unset($explode[$theIndex]);
                                    }
                                    $implode = implode(',', $explode);
                                    $details = $implode;
                                } else {
                                    $details = null;
                                }
                            }
                            $biometric->details = $details;
                            $biometric->update_status = null;
                            $biometric->updated_at = Carbon::now();
                            $biometric->save();
                        };
                    }
                }

                if (count($getNonTechStaff) > 0) {
                    foreach ($getNonTechStaff as $staff) {
                        $biometric = StaffBiometric::where(['date' => $leave->date, 'user_name_id' => $staff->user_name_id])->select('id', 'details', 'update_status', 'updated_at')->first();
                        if ($biometric != '') {

                            if ($biometric->details == $theDetail) {
                                $details = null;
                            } else {
                                if (strpos($staff_biometric->details, ',') !== false) {
                                    $explode = explode(',', $staff_biometric->details);
                                    if (in_array('Holiday', $explode) && $theDetail == 'Holiday') {
                                        $theIndex = array_search('Holiday', $explode);
                                        unset($explode[$theIndex]);
                                    } else if (in_array('Fore Noon Holiday', $explode) && $theDetail == 'Fore Noon Holiday') {
                                        $theIndex = array_search('Fore Noon Holiday', $explode);
                                        unset($explode[$theIndex]);
                                    } else if (in_array('After Noon Holiday', $explode) && $theDetail == 'After Noon Holiday') {
                                        $theIndex = array_search('After Noon Holiday', $explode);
                                        unset($explode[$theIndex]);
                                    }
                                    $implode = implode(',', $explode);
                                    $details = $implode;
                                } else {
                                    $details = null;
                                }
                            }
                            $biometric->details = $details;
                            $biometric->update_status = null;
                            $biometric->updated_at = Carbon::now();
                            $biometric->save();
                        };
                    }
                }

                $deleteLeave = LeaveImplement::where('id', $request->id)->delete();
                return response()->json(['status' => true]);
            } else {
                return response()->json(['status' => false]);
            }
        } else {
            return response()->json(['status' => false]);
        }
    }
}
