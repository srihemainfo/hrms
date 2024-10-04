<?php

namespace App\Console\Commands;

use App\Models\HrmRequestLeaf;
use App\Models\NonTeachingStaff;
use App\Models\TeachingStaff;
use App\Models\StaffBiometric;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FutureLeaveCalculation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'futureleavecalculation:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the leave requests,which is has future days, and deduct cl for those days when its come in present';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        $theFromDate = $year . '-' . $month . '-' . '25';
        $theToDate = $year . '-' . $month . '-' . '26';

        $checkLeaveReq = HrmRequestLeaf::where('from_date', '<=', $theFromDate)->where('to_date', '>=', $theToDate)->where(['leave_type' => 1, 'status' => 'Approved'])->select('user_id', 'to_date')->get();

        foreach ($checkLeaveReq as $leave) {
            $leaveToDate = Carbon::createFromFormat('Y-m-d', $leave->to_date);
            $theLeaveDay = $leaveToDate->format('d');
            $daysCount = (int) $theLeaveDay - 25; // instead of (($theLeaveDay - 26) + 1),we used 25
            $theCl = 0;
            $subtracted_cl = 0;
            $get_staff = TeachingStaff::where(['user_name_id' => $leave->user_id])->select('casual_leave', 'subtracted_cl')->first();
            if ($get_staff == '') {
                $get_staff = NonTeachingStaff::where(['user_name_id' => $leave->user_id])->select('casual_leave', 'subtracted_cl')->first();
                if ($get_staff != '') {
                    $theCl = $get_staff->casual_leave;
                    $subtracted_cl = $get_staff->subtracted_cl;
                }
            } else {
                $theCl = $get_staff->casual_leave;
                $subtracted_cl = $get_staff->subtracted_cl;
            }

            if ($daysCount >= 3) {
                if ($theCl > 0) {
                    $deduct_cl = $theCl - 3;
                    if ($deduct_cl > 0) {
                        $cl = $deduct_cl;
                        $subsCl = $subtracted_cl + 3;
                        $j = 0;
                        for ($i = 26; $i <= (int) $theLeaveDay; $i++) {
                            if ($j < 3) {
                                $staff_biometric = StaffBiometric::where(['date' => $year . '-' . $month . '-' . $i, 'user_name_id' => $leave->user_id, 'update_status' => null])->update([
                                    'status' => 'Absent',
                                    'details' => 'Casual Leave (CL Provided)',
                                    'update_status' => 1,
                                ]);
                            } else {
                                $staff_biometric = StaffBiometric::where(['date' => $year . '-' . $month . '-' . $i, 'user_name_id' => $leave->user_id, 'update_status' => null])->update([
                                    'status' => 'Absent',
                                    'details' => 'Casual Leave',
                                    'update_status' => 1,
                                ]);
                            }
                            $j++;
                        }

                        $updateStaff = TeachingStaff::where(['user_name_id' => $leave->user_id])->update([
                            'casual_leave' => $cl,
                            'past_casual_leave' => $cl,
                            'subtracted_cl' => $subsCl,
                        ]);

                        $updateNonStaff = NonTeachingStaff::where(['user_name_id' => $leave->user_id])->update([
                            'casual_leave' => $cl,
                            'past_casual_leave' => $cl,
                            'subtracted_cl' => $subsCl,
                        ]);

                    } elseif ($deduct_cl == 0) {
                        $cl = 0;
                        $subsCl = $subtracted_cl + $theCl;
                        for ($i = 26; $i <= (int) $theLeaveDay; $i++) {
                            $staff_biometric = StaffBiometric::where(['date' => $year . '-' . $month . '-' . $i, 'user_name_id' => $leave->user_id, 'update_status' => null])->update([
                                'status' => 'Absent',
                                'details' => 'Casual Leave (CL Provided)',
                                'update_status' => 1,
                            ]);
                        }

                        $updateStaff = TeachingStaff::where(['user_name_id' => $leave->user_id])->update([
                            'casual_leave' => $cl,
                            'past_casual_leave' => $cl,
                            'subtracted_cl' => $subsCl,
                        ]);

                        $updateNonStaff = NonTeachingStaff::where(['user_name_id' => $leave->user_id])->update([
                            'casual_leave' => $cl,
                            'past_casual_leave' => $cl,
                            'subtracted_cl' => $subsCl,
                        ]);

                    } elseif ($deduct_cl < 0) {
                        $cl = 0;
                        $subsCl = $subtracted_cl + $theCl;
                        $j = 0;
                        for ($i = 26; $i <= (int) $theLeaveDay; $i++) {
                            if ($j < $theCl) {
                                $staff_biometric = StaffBiometric::where(['date' => $year . '-' . $month . '-' . $i, 'user_name_id' => $leave->user_id, 'update_status' => null])->update([
                                    'status' => 'Absent',
                                    'details' => 'Casual Leave (CL Provided)',
                                    'update_status' => 1,
                                ]);
                            } else {
                                $staff_biometric = StaffBiometric::where(['date' => $year . '-' . $month . '-' . $i, 'user_name_id' => $leave->user_id, 'update_status' => null])->update([
                                    'status' => 'Absent',
                                    'details' => 'Casual Leave',
                                    'update_status' => 1,
                                ]);
                            }
                            $j++;
                        }
                        $updateStaff = TeachingStaff::where(['user_name_id' => $leave->user_id])->update([
                            'casual_leave' => $cl,
                            'past_casual_leave' => $cl,
                            'subtracted_cl' => $subsCl,
                        ]);

                        $updateNonStaff = NonTeachingStaff::where(['user_name_id' => $leave->user_id])->update([
                            'casual_leave' => $cl,
                            'past_casual_leave' => $cl,
                            'subtracted_cl' => $subsCl,
                        ]);
                    }
                } else {
                    for ($i = 26; $i <= (int) $theLeaveDay; $i++) {
                        $staff_biometric = StaffBiometric::where(['date' => $year . '-' . $month . '-' . $i, 'user_name_id' => $leave->user_id, 'update_status' => null])->update([
                            'status' => 'Absent',
                            'details' => 'Casual Leave',
                            'update_status' => 1,
                        ]);
                    }
                }
            } else {
                if ($theCl > 0) {
                    $deduct_cl = $theCl - $daysCount;
                    if ($deduct_cl > 0) {
                        $cl = $deduct_cl;
                        $subsCl = $subtracted_cl + $daysCount;
                        for ($i = 26; $i <= (int) $theLeaveDay; $i++) {
                            $staff_biometric = StaffBiometric::where(['date' => $year . '-' . $month . '-' . $i, 'user_name_id' => $leave->user_id, 'update_status' => null])->update([
                                'status' => 'Absent',
                                'details' => 'Casual Leave (CL Provided)',
                                'update_status' => 1,
                            ]);
                        }

                        $updateStaff = TeachingStaff::where(['user_name_id' => $leave->user_id])->update([
                            'casual_leave' => $cl,
                            'past_casual_leave' => $cl,
                            'subtracted_cl' => $subsCl,
                        ]);

                        $updateNonStaff = NonTeachingStaff::where(['user_name_id' => $leave->user_id])->update([
                            'casual_leave' => $cl,
                            'past_casual_leave' => $cl,
                            'subtracted_cl' => $subsCl,
                        ]);

                    } elseif ($deduct_cl == 0) {
                        $cl = $deduct_cl;
                        $subsCl = $subtracted_cl + $theCl;
                        for ($i = 26; $i <= (int) $theLeaveDay; $i++) {
                            $staff_biometric = StaffBiometric::where(['date' => $year . '-' . $month . '-' . $i, 'user_name_id' => $leave->user_id, 'update_status' => null])->update([
                                'status' => 'Absent',
                                'details' => 'Casual Leave (CL Provided)',
                                'update_status' => 1,
                            ]);
                        }

                        $updateStaff = TeachingStaff::where(['user_name_id' => $leave->user_id])->update([
                            'casual_leave' => $cl,
                            'past_casual_leave' => $cl,
                            'subtracted_cl' => $subsCl,
                        ]);

                        $updateNonStaff = NonTeachingStaff::where(['user_name_id' => $leave->user_id])->update([
                            'casual_leave' => $cl,
                            'past_casual_leave' => $cl,
                            'subtracted_cl' => $subsCl,
                        ]);

                    } elseif ($deduct_cl < 0) {
                        $cl = 0;
                        $subsCl = $subtracted_cl + $theCl;
                        $j = 0;
                        for ($i = 26; $i <= (int) $theLeaveDay; $i++) {
                            if ($j < $theCl) {
                                $staff_biometric = StaffBiometric::where(['date' => $year . '-' . $month . '-' . $i, 'user_name_id' => $leave->user_id, 'update_status' => null])->update([
                                    'status' => 'Absent',
                                    'details' => 'Casual Leave (CL Provided)',
                                    'update_status' => 1,
                                ]);
                            } else {
                                $staff_biometric = StaffBiometric::where(['date' => $year . '-' . $month . '-' . $i, 'user_name_id' => $leave->user_id, 'update_status' => null])->update([
                                    'status' => 'Absent',
                                    'details' => 'Casual Leave',
                                    'update_status' => 1,
                                ]);
                            }
                            $j++;
                        }
                        $updateStaff = TeachingStaff::where(['user_name_id' => $leave->user_id])->update([
                            'casual_leave' => $cl,
                            'past_casual_leave' => $cl,
                            'subtracted_cl' => $subsCl,
                        ]);

                        $updateNonStaff = NonTeachingStaff::where(['user_name_id' => $leave->user_id])->update([
                            'casual_leave' => $cl,
                            'past_casual_leave' => $cl,
                            'subtracted_cl' => $subsCl,
                        ]);
                    }
                } else {
                    for ($i = 26; $i <= (int) $theLeaveDay; $i++) {
                        $staff_biometric = StaffBiometric::where(['date' => $year . '-' . $month . '-' . $i, 'user_name_id' => $leave->user_id, 'update_status' => null])->update([
                            'status' => 'Absent',
                            'details' => 'Casual Leave',
                            'update_status' => 1,
                        ]);
                    }
                }
            }
        }

        \Log::info("All Future Casual Leave Calculations Done.");
    }
}
