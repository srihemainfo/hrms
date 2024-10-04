<?php

namespace App\Console\Commands;

use App\Models\ApiBiometricModel;
use App\Models\PermissionRequest;
use App\Models\StaffBiometric;
use App\Models\UserAlert;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Console\Command;

class ApiBiometric extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apiBiometric:getdata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Api Staff BioMetric Data';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currect_date = date('Y-m-d');

        $get_biometric = ApiBiometricModel::where('created_at', 'LIKE', $currect_date . '%')->get();
        // dd($get_biometric);
        foreach ($get_biometric as $key => $biometric) {
            $decoded = json_decode($biometric->response, true);
            // dd($decoded['data']);
            if (array_key_exists('data', $decoded)) {
                $rows = count($decoded['data']);
                // dd($rows);
                $balance_row = $rows;
                foreach ($decoded['data'] as $key => $datas) {
                    // dd($datas, $key);
                    $user_id = DB::table('users')
                        ->leftJoin('teaching_staffs', 'teaching_staffs.user_name_id', 'users.id')
                        ->leftJoin('non_teaching_staffs', 'non_teaching_staffs.user_name_id', 'users.id')
                        ->where('teaching_staffs.BiometricID', $key)
                        ->orwhere('non_teaching_staffs.BiometricID', $key)
                        ->select('users.id')
                        ->first();
                    if ($user_id != null) {
                        foreach ($datas as $data) {
                            $time = explode(' ', $data);

                            $staff_bio = StaffBiometric::where(['user_name_id' => $user_id->id, 'date' => $time[0]])->first();
                            // dd($staff_bio);
                            if ($data != null && $data != null) {
                                // dd($data['in']);
                                if ($user_id != null) {
                                    // $in_time = '00:00:00 00:00:00';
                                    // $out_time = '00:00:00';

                                    if (isset($data)) {
                                        // dd($data);
                                        $time = explode(' ', $data);
                                        // dd($time);
                                        // dd($data['in'][0]);
                                        $in_time = ($staff_bio->in_time == '00:00:00' || $staff_bio->in_time == null) ? $time[1] : ($staff_bio->in_time <= $time[1] ? $staff_bio->in_time : $time[1]);
                                        $out_time = ($staff_bio->out_time == '00:00:00' || $staff_bio->out_time == null) ? $time[1] : ($staff_bio->in_time <= $time[1] ? $time[1] : $staff_bio->out_time);
                                    }
                                    // if (isset($data['out'])) {
                                    //     $data['out'] = explode(' ', $data['out']);
                                    //     $out_time = $staff_bio->out_time == '00:00:00' != '' ? $data : ($staff_bio->in_time <= $data ? $staff_bio->in_time : $data);
                                    //     $out_time = $data['out'] != '' ? $data['out'][1] : '00:00:00';
                                    // }
                                    // dd($out_time, $in_time);
                                    $permission = '';
                                    $details = '';
                                    if ($in_time != '00:00:00' && $out_time != '00:00:00') {
                                        $in = strtotime($in_time);
                                        $out = strtotime($out_time);

                                        $duration_seconds = $out - $in;

                                        $total_hours = gmdate('H:i:s', $duration_seconds);

                                        if (strtotime($in_time) > strtotime('08:00:00') && strtotime($in_time) <= strtotime('08:15:00')) {
                                            if ($details == '') {
                                                $details .= 'Late';
                                            } else {
                                                $details .= ',Late';
                                            }
                                        } else if (strtotime($in_time) > strtotime('08:15:00')) {
                                            if ($details == '') {
                                                $details .= 'Too Late';
                                            } else {
                                                $details .= ',Too Late';
                                            }

                                        }
                                        $status = 'Present';
                                    } else {
                                        $total_hours = '00:00:00';
                                        $status = 'Absent';
                                    }
                                    $day_punches = !empty($staff_bio->day_punches) ? $staff_bio->day_punches : '';
                                    $day_punches .= $time[1] . ', ';
                                    // $explode = explode(' ', $time[1]);
                                    // $times = strtotime($time[1]);
                                    // dd(gmdate('H:i:s', $times));
                                    // dd($out_time, $in_time, $time[1],  $day_punches);
                                    // dd($day_punches);


                                    $given_date = $time[0];
                                    // dd($given_date);
                                    $formattedDate = null;

                                    $formats = [
                                        'd-m-y',
                                        'd-m-Y',
                                        'd/m/y',
                                        'd/m/Y',
                                        'Y-m-d'
                                    ];

                                    foreach ($formats as $i => $format) {
                                        try {
                                            $the_date = Carbon::createFromFormat($format, $given_date);

                                            $dateOnly = $the_date->format('Y-m-d');
                                            if ($dateOnly != '') {
                                                $formattedDate = $dateOnly;
                                                break;
                                            }
                                        } catch (Exception $e) {
                                        }
                                    }


                                    if ($formattedDate != null) {
                                        $staff_biometric = StaffBiometric::where(['date' => $formattedDate, 'user_name_id' => $user_id->id])->select('id', 'details', 'update_status', 'shift', 'status', 'in_time', 'out_time', 'total_hours', 'day_punches', 'updated_at', 'permission', 'import')->first();
                                        // dd($staff_biometric);
                                        if ($staff_biometric != '') {
                                            if ($staff_biometric->import != 1) {
                                                // if (strpos($staff_biometric->details, 'Sunday') === false && $staff_biometric->details != 'Holiday') {
                                                if ($staff_biometric->details != 'Sunday' && $staff_biometric->details != 'Sunday,Admin OD' && $staff_biometric->details != 'Sunday,Exam OD' && $staff_biometric->details != 'Sunday,Training OD' && $staff_biometric->details != 'Admin OD,Sunday' && $staff_biometric->details != 'Exam OD,Sunday' && $staff_biometric->details != 'Training OD,Sunday' && $staff_biometric->details != 'Holiday' && $staff_biometric->details != 'Holiday,Admin OD' && $staff_biometric->details != 'Holiday,Exam OD' && $staff_biometric->details != 'Holiday,Training OD' && $staff_biometric->details != 'Admin OD,Holiday' && $staff_biometric->details != 'Exam OD,Holiday' && $staff_biometric->details != 'Training OD,Holiday') {
                                                    $get = PermissionRequest::where(['user_name_id' => $user_id->id, 'date' => $formattedDate, 'Permission' => 'On Duty', 'status' => 2])->select('from_time', 'to_time')->first();
                                                    if ($get == '' && $staff_biometric->permission != 'OD Permission') {
                                                        if ($staff_biometric->permission == 'FN Permission') {
                                                            $details = '';
                                                        } else if ($staff_biometric->permission != 'AN Permission') {

                                                            if ($staff_biometric->shift == 1) {
                                                                if ($out_time != '00:00:00') {
                                                                    if (strtotime($out_time) < strtotime('16:00:00')) {
                                                                        if ($details == '') {
                                                                            if ($staff_biometric->details != null && strpos($staff_biometric->details, 'After Noon') === false) {
                                                                                $status = 'Absent';
                                                                                $details = 'Early Out';
                                                                            } else if ($staff_biometric->details == null) {
                                                                                $status = 'Absent';
                                                                                $details = 'Early Out';
                                                                            }
                                                                        } else {
                                                                            if ($staff_biometric->details != null && strpos($staff_biometric->details, 'After Noon') === false) {
                                                                                $status = 'Absent';
                                                                                $details .= ',Early Out';
                                                                            } else if ($staff_biometric->details == null) {
                                                                                $status = 'Absent';
                                                                                $details .= ',Early Out';
                                                                            }
                                                                        }

                                                                    }
                                                                }
                                                            } else if ($staff_biometric->shift == 2) {
                                                                if ($out_time != '00:00:00') {
                                                                    if (strtotime($out_time) < strtotime('17:00:00')) {
                                                                        if ($details == '') {
                                                                            if ($staff_biometric->details != null && strpos($staff_biometric->details, 'After Noon') === false) {
                                                                                $status = 'Absent';
                                                                                $details = 'Early Out';
                                                                            } else if ($staff_biometric->details == null) {
                                                                                $status = 'Absent';
                                                                                $details = 'Early Out';
                                                                            }
                                                                        } else {
                                                                            if ($staff_biometric->details != null && strpos($staff_biometric->details, 'After Noon') === false) {
                                                                                $status = 'Absent';
                                                                                $details .= ',Early Out';
                                                                            } else if ($staff_biometric->details == null) {
                                                                                $status = 'Absent';
                                                                                $details .= ',Early Out';
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        } else {
                                                            if ($staff_biometric->details != null && strpos($staff_biometric->details, 'Fore Noon') !== false) {
                                                                $details = '';
                                                            }
                                                        }
                                                    } else {
                                                        if (($details == 'Late' || $details == 'Too Late') && (strtotime($in_time) >= strtotime($get->from_time) && strtotime($in_time) <= strtotime($get->to_time))) {
                                                            $details = '';
                                                        } else if (strtotime($out_time) <= strtotime($get->to_time)) {
                                                            $details = '';
                                                        }
                                                    }
                                                    if ($staff_biometric->details != null) {
                                                        if ($details != '') {
                                                            $tempDetail = $staff_biometric->details . ',' . $details;
                                                        } else {
                                                            $tempDetail = $staff_biometric->details;
                                                        }
                                                    } else {
                                                        $tempDetail = $details != '' ? $details : null;
                                                    }
                                                } else {
                                                    $tempDetail = $staff_biometric->details;
                                                }
                                                // dd($tempDetail);
                                                $staff_biometric->in_time = $in_time;
                                                $staff_biometric->out_time = $out_time;
                                                $staff_biometric->total_hours = $total_hours;
                                                $staff_biometric->status = $status;
                                                $staff_biometric->details = $tempDetail;
                                                $staff_biometric->day_punches = $day_punches;
                                                // $staff_biometric->import = 1;
                                                $staff_biometric->updated_at = Carbon::now();
                                                $staff_biometric->save();
                                            }
                                            $balance_row--;
                                            $details = '';
                                        }
                                        $inserted_rows = $rows - $balance_row;
                                        $formattedDate = null;
                                    }
                                }
                            }
                        }
                    }

                }
            }
        }
        \Log::info($inserted_rows . "Row Updated Staff Biometric table");


    }
}
