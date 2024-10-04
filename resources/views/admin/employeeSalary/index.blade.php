@extends('layouts.admin')
@section('content')
    {{-- {{ dd($salary) }} --}}
    <div id="loader_container">
        <div class="loader" id="loader" style="display:none;">
            <div class="spinner-border text-primary"></div>
        </div>
        <div class="card">
            <div class="card-header">
                Employee Salary
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.employee-salary.search') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row gutters">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label class="required" for="staff_code">Staff Name</label>
                                <select class="form-control select2" name="staff_code" id="staff_code" required>
                                    <option value="">Select Staff</option>
                                    @foreach ($staff as $id => $key)
                                        <option value="{{ $id }}">{{ $key . ' (' . $id . ')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label class="required" for="month">Month</label>
                                <select class="form-control select2" name="month" id="month" required>
                                    <option value="">Select Month</option>
                                    <option value="01">January</option>
                                    <option value="02">February</option>
                                    <option value="03">March</option>
                                    <option value="04">April</option>
                                    <option value="05">May</option>
                                    <option value="06">June</option>
                                    <option value="07">July</option>
                                    <option value="08">August</option>
                                    <option value="09">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label class="required" for="year">Year</label>
                                <select class="form-control select2" name="year" id="year" required>
                                    @foreach ($year as $y)
                                        <option value="{{ $y->year }}">{{ $y->year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="form-group" style="padding-top: 30px;">
                                <button type="submit" id="submit" name="submit" class="enroll_generate_bn">Get
                                    Report</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @if ($attend_rep != '')
            <div class="card">
                <div class="card-header">
                    <div style="display:flex;justify-content:space-between;">
                        <div>
                            <button class="manual_bn d-block">STAFF : {{ $attend_rep[0]->employee_name }}</button>
                        </div>
                        <div style="display:flex;justify-content:space-between;">
                            <button class="manual_bn d-block"> Shift : {{ $attend_rep[0]->shift }}
                            </button>
                            <span style="display:none;" id="shift_bn">{{ $attend_rep[0]->shift }}</span>
                            @php
                                $count = count($attend_rep);
                            @endphp
                            <button class="ml-2 manual_bn d-block"> Month :
                                {{ \Carbon\Carbon::parse($attend_rep[$count - 1]->date)->format('F') }}</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @php

                        $late = 0;
                        $permission_shift_1 = 0;
                        $permission_shift_2 = 0;

                    @endphp
                    <table class="list_table" style='width:100%;' id="list_table">
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Day Punches</th>
                            <th>In Time</th>
                            <th>Out Time</th>
                            <th>Total Hours</th>
                            <th>Permission</th>
                            <th>Status</th>
                            <th>Details</th>
                            <th>Updated By</th>
                        </tr>
                        {{-- {{ dd($attend_rep[28]) }} --}}
                        {{-- Loop through the dates in $day_array --}}
                        @foreach ($attend_rep as $day)
                            {{-- {{  dd($day); }} --}}
                            {{-- Check if there is an attendance record for this date in $attend_rep --}}
                            @php
                                $user_name_id = $attend_rep[0]->user_name_id;
                            @endphp

                            {{-- @if ($attendance = $attend_rep->where('date', $day[0])->first()) --}}
                            @if ($attendance = $day)
                                {{-- {{ dd($attendance) }} --}}
                                @php

                                    if (strpos($attendance->details, 'Late') !== false && strpos($attendance->details, 'Too Late') === false) {
                                        $late++;
                                    }

                                    if ($attendance->shift == '1') {
                                        if (
                                            $attendance->permission == 'FN Permission' &&
                                            $attendance->permission == 'AN Permission'
                                        ) {
                                            $permission_shift_1 += 2;
                                        } elseif ($attendance->permission == 'FN Permission') {
                                            $permission_shift_1++;
                                        } elseif ($attendance->permission == 'AN Permission') {
                                            $permission_shift_1++;
                                        } else {
                                        }
                                    } elseif ($attendance->shift == '2') {
                                        if (
                                            $attendance->permission == 'FN Permission' &&
                                            $attendance->permission == 'AN Permission'
                                        ) {
                                            $permission_shift_2 += 2;
                                        } elseif ($attendance->permission == 'FN Permission') {
                                            $permission_shift_2++;
                                        } elseif ($attendance->permission == 'AN Permission') {
                                            $permission_shift_2++;
                                        } else {
                                        }
                                    }

                                @endphp
                                {{-- Create a row with attendance data --}}
                                @if ($attendance['day'] == 'Sunday')
                                    @if ($attendance->total_hours == '')
                                        <tr style="background-color: rgb(253, 198, 198)">

                                            <td class="date">{{ $attendance['date'] }}</td>
                                            <td>{{ $attendance['day'] }}</td>
                                            <td>{{ $attendance['day_punches'] }}</td>
                                            <td>
                                                <input type="hidden" name="user_name_id" value="{{ $user_name_id }}">
                                                <input type="hidden" class="form-control in_time" name="in_time"
                                                    value="">
                                            </td>
                                            <td>
                                                <input type="hidden" class="form-control out_time" name="out_time"
                                                    value="">
                                            </td>
                                            <td class="total_hours">{{ $attendance['total_hours'] }}</td>
                                            <td class="permission">
                                                {{ isset($attendance->permission) ? ($attendance->permission != '' ? $attendance->permission : '') : '' }}
                                            </td>
                                            <td>
                                                <select name="status" class="status_attend">
                                                    <option value="Present"
                                                        {{ isset($attendance->status) ? ($attendance->status == 'Present' ? 'selected' : '') : '' }}>
                                                        Present</option>
                                                    <option value="Absent"
                                                        {{ is_null($attendance->status) || $attendance->status == 'Absent' ? 'selected' : '' }}>
                                                        Absent</option>

                                                </select>
                                            </td>
                                            <td class="details">
                                                {{ $attendance->details != '' ? $attendance->details : 'Holiday' }}
                                            </td>
                                            <td class="up_status">
                                                {{ isset($attendance->updated_by) ? ($attendance->updated_by != '' ? $attendance->updated_by : '') : '' }}
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td class="date">{{ $attendance['date'] }}</td>
                                            <td>{{ $attendance['day'] }}</td>
                                            <td>{{ $attendance['day_punches'] }}</td>
                                            <td>
                                                <input type="hidden" name="user_name_id" value="{{ $user_name_id }}">
                                                <input class="form-control table_inp in_time " type="text" name="in_time"
                                                    value="{{ $attendance->in_time }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control table_inp out_time "
                                                    name="out_time" value="{{ $attendance->out_time }}">
                                            </td>
                                            <td class="total_hours">{{ $attendance['total_hours'] }}</td>
                                            <td class="permission">
                                                {{ isset($attendance->permission) ? ($attendance->permission != '' ? $attendance->permission : '') : '' }}
                                            </td>
                                            <td>
                                                <select name="status" class="status_attend">
                                                    <option value="Present"
                                                        {{ $attendance->status == 'Present' ? 'selected' : '' }}>
                                                        Present</option>

                                                    <option value="Absent"
                                                        {{ is_null($attendance->status) || $attendance->status == 'Absent' ? 'selected' : '' }}>
                                                        Absent</option>

                                                </select>
                                            </td>
                                            @if (strpos($attendance->details, 'Late') !== false || strpos($attendance->details, 'Early Out') !== false)
                                                <td class="details" style="color:red;">
                                                    {{ $attendance->details }}
                                                </td>
                                            @else
                                                <td class="details">
                                                    {{ $attendance->details != '' ? $attendance->details : '' }}
                                                </td>
                                            @endif

                                            <td class="up_status">
                                                {{ isset($attendance->updated_by) ? ($attendance->updated_by != '' ? $attendance->updated_by : '') : '' }}
                                            </td>
                                        </tr>
                                    @endif
                                @elseif (
                                    (($attendance->total_hours == '' || $attendance->total_hours == '00:00:00') &&
                                        strpos($attendance->details, 'Holiday') === false &&
                                        strpos($attendance->details, 'Winter Vacation') === false &&
                                        strpos($attendance->details, 'Summer Vacation') === false &&
                                        strpos($attendance->details, 'Exam OD') === false &&
                                        strpos($attendance->details, 'Admin OD') === false &&
                                        strpos($attendance->details, 'Training OD') === false &&
                                        strpos($attendance->details, 'Compensation Leave') === false &&
                                        strpos($attendance->details, '(CL Provided)') === false))
                                    <tr style="background-color: rgb(172, 252, 255)">
                                        <td class="date">{{ $attendance['date'] }}</td>
                                        <td>{{ $attendance['day'] }}</td>
                                        <td>{{ $attendance['day_punches'] }}</td>
                                        <td>
                                            <input type="hidden" name="user_name_id" value="{{ $user_name_id }}">
                                            <input class="form-control table_inp in_time " type="text" name="in_time"
                                                value="{{ $attendance->in_time }}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control table_inp out_time "
                                                name="out_time" value="{{ $attendance->out_time }}">
                                        </td>
                                        <td class="total_hours">{{ $attendance['total_hours'] }}</td>
                                        <td class="permission">
                                            {{ isset($attendance->permission) ? ($attendance->permission != '' ? $attendance->permission : '') : '' }}
                                        </td>
                                        <td>
                                            <select name="status" class="status_attend">
                                                <option value="Present"
                                                    {{ $attendance->status == 'Present' ? 'selected' : '' }}>
                                                    Present</option>

                                                <option value="Absent"
                                                    {{ is_null($attendance->status) || $attendance->status == 'Absent' ? 'selected' : '' }}>
                                                    Absent</option>

                                            </select>
                                        </td>
                                        @if (strpos($attendance->details, 'Late') !== false || strpos($attendance->details, 'Early Out') !== false)
                                            <td class="details" style="color:red;">
                                                {{ $attendance->details }}
                                            </td>
                                        @else
                                            <td class="details">
                                                {{ $attendance->details != '' ? $attendance->details : '' }}
                                            </td>
                                        @endif

                                        <td class="up_status">
                                            {{ isset($attendance->updated_by) ? ($attendance->updated_by != '' ? $attendance->updated_by : '') : '' }}
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="date">{{ $attendance['date'] }}</td>
                                        <td>{{ $attendance['day'] }}</td>
                                        <td>{{ $attendance['day_punches'] }}</td>
                                        <td>
                                            <input type="hidden" name="user_name_id" value="{{ $user_name_id }}">
                                            <input class="form-control table_inp in_time " type="text" name="in_time"
                                                value="{{ $attendance->in_time }}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control table_inp out_time "
                                                name="out_time" value="{{ $attendance->out_time }}">
                                        </td>
                                        <td class="total_hours">{{ $attendance['total_hours'] }}</td>
                                        <td class="permission">
                                            {{ isset($attendance->permission) ? ($attendance->permission != '' ? $attendance->permission : '') : '' }}
                                        </td>

                                        <td>
                                            <select name="status" class="status_attend">
                                                <option value="Present"
                                                    {{ isset($attendance->status) ? ($attendance->status == 'Present' ? 'selected' : '') : '' }}>
                                                    Present</option>
                                                <option value="Absent"
                                                    {{ is_null($attendance->status) || $attendance->status == 'Absent' ? 'selected' : '' }}>
                                                    Absent</option>

                                            </select>
                                        </td>
                                        @if (strpos($attendance->details, 'Late') !== false || strpos($attendance->details, 'Early Out') !== false)
                                            <td class="details" style="color:red;">
                                                {{ $attendance->details }}
                                            </td>
                                        @else
                                            <td class="details">
                                                {{ $attendance->details != '' ? $attendance->details : '' }}
                                            </td>
                                        @endif

                                        <td class="up_status">
                                            {{ isset($attendance->updated_by) ? ($attendance->updated_by != '' ? $attendance->updated_by : '') : '' }}
                                        </td>
                                    </tr>
                                @endif
                            @endif
                        @endforeach
                    </table>
                    <div style="margin:20px 0px 0px auto;text-align:right;">
                        <button type="submit" id="saveBtn" name="submit"
                            class="enroll_generate_bn">Update</button></button>
                    </div>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div style="display:flex;justify-content:space-between;">
                        <div>
                            <h5 class="mb-2 text-primary">Salary Details</h5>
                        </div>
                        <div style="display:flex;justify-content:space-between;">
                            <button class="manual_bn d-block"> Available CL : {{ $salary->casual_leave }}
                            </button>
                        </div>
                    </div>
                </div>
                @php
                    if ($late > 3) {
                        $m_total_paid_days = count($day_array) - ($leave + $half_day_leave + $too_late + 0.5);
                    } else {
                        $m_total_paid_days = count($day_array) - ($leave + $half_day_leave + $too_late);
                    }

                    $m_total_working_days = count($day_array);

                    // Basic Pay Calculation
                    if (isset($salary->basicPay) && !empty($salary->basicPay && !is_nan($salary->basicPay))) {
                        $m_per_day_basic_pay = $salary->basicPay / $m_total_working_days;

                        $m_half_day_basic_pay = $m_per_day_basic_pay / 2;

                        if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                            $basic_pay_permis_deduct = ($m_per_day_basic_pay / 7) * ($permission_shift_1 - 2);
                        } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                            $basic_pay_permis_deduct = ($m_per_day_basic_pay / 9) * ($permission_shift_2 - 2);
                        } else {
                            $basic_pay_permis_deduct = 0;
                        }

                        if ($late > 3) {
                            $deduct_basic_pay = $late - 3;

                            $late_deduct_basic_pay = $m_half_day_basic_pay * $deduct_basic_pay;
                        } else {
                            $late_deduct_basic_pay = 0;
                        }

                        if ($too_late > 0) {
                            $too_late_deduct_basic_pay = $m_half_day_basic_pay * $too_late;
                        } else {
                            $too_late_deduct_basic_pay = 0;
                        }

                        $m_basic_pay = round(
                            $salary->basicPay * ($m_total_paid_days / $m_total_working_days) -
                                ($basic_pay_permis_deduct + $late_deduct_basic_pay + $too_late_deduct_basic_pay),
                            2,
                        );
                        $m_basic_pay_loss = $salary->basicPay - $m_basic_pay;
                    } else {
                        $m_basic_pay = 0;
                        $m_basic_pay_loss = 0;
                    }

                    // AGP Calculation
                    if (isset($salary->agp) && !empty($salary->agp && !is_nan($salary->agp))) {
                        $m_per_day_agp = $salary->agp / $m_total_working_days;

                        $m_half_day_agp = $m_per_day_agp / 2;

                        if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                            $agp_permis_deduct = ($m_per_day_agp / 7) * ($permission_shift_1 - 2);
                        } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                            $agp_permis_deduct = ($m_per_day_agp / 9) * ($permission_shift_2 - 2);
                        } else {
                            $agp_permis_deduct = 0;
                        }

                        if ($late > 3) {
                            $deduct_agp = $late - 3;

                            $late_deduct_agp = $m_half_day_agp * $deduct_agp;
                        } else {
                            $late_deduct_agp = 0;
                        }
                        if ($too_late > 0) {
                            $too_late_deduct_agp = $m_half_day_agp * $too_late;
                        } else {
                            $too_late_deduct_agp = 0;
                        }

                        $m_agp = round(
                            $salary->agp * ($m_total_paid_days / $m_total_working_days) -
                                ($agp_permis_deduct + $late_deduct_agp + $too_late_deduct_agp),
                            2,
                        );
                        $m_agp_loss = $salary->agp - $m_agp;
                    } else {
                        $m_agp = 0;
                        $m_agp_loss = 0;
                    }

                    // DA Calculation
                    $m_da = round(($m_basic_pay + $m_agp) * 0.55, 2);
                    $m_da_loss = $salary->da - $m_da;
                    // $m_da = (14594+5613)*0.55;

                    // HRA Calculation
                    if ($salary->hra == '' || $salary->hra == null) {
                        $salary_hra = 0;
                    } else {
                        $salary_hra = $salary->hra;
                    }
                    $m_hra = round(($m_agp + $m_da) * ($salary_hra / 100), 2);

                    $m_hra_loss = $salary->hra_amount - $m_hra;

                    // dd($m_hra,$m_hra_loss);
                    // $m_hra = (1000+2300)*10 / 100;
                    // dd($m_agp,$m_da,$salary_hra,$m_hra);
                    // dd($m_da_loss);
                    // SpecialFee Calculation
                    if (isset($salary->specialFee) && !empty($salary->specialFee && !is_nan($salary->specialFee))) {
                        $m_per_day_specialFee = $salary->specialFee / $m_total_working_days;

                        $m_half_day_specialFee = $m_per_day_specialFee / 2;

                        if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                            $specialFee_permis_deduct = ($m_per_day_specialFee / 7) * ($permission_shift_1 - 2);
                        } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                            $specialFee_permis_deduct = ($m_per_day_specialFee / 9) * ($permission_shift_2 - 2);
                        } else {
                            $specialFee_permis_deduct = 0;
                        }

                        if ($late > 3) {
                            $deduct_specialFee = $late - 3;

                            $late_deduct_specialFee = $m_half_day_specialFee * $deduct_specialFee;
                        } else {
                            $late_deduct_specialFee = 0;
                        }

                        if ($too_late > 0) {
                            $too_late_deduct_specialFee = $m_half_day_specialFee * $too_late;
                        } else {
                            $too_late_deduct_specialFee = 0;
                        }

                        $m_specialFee = round(
                            $salary->specialFee * ($m_total_paid_days / $m_total_working_days) -
                                ($specialFee_permis_deduct + $late_deduct_specialFee + $too_late_deduct_specialFee),
                            2,
                        );
                        $m_specialFee_loss = $salary->specialFee - $m_specialFee;
                    } else {
                        $m_specialFee = 0;
                        $m_specialFee_loss = 0;
                    }

                    // Phd Allowance Calculation
                    if (
                        isset($salary->phdAllowance) &&
                        !empty($salary->phdAllowance && !is_nan($salary->phdAllowance))
                    ) {
                        $m_per_day_phdAllowance = $salary->phdAllowance / $m_total_working_days;

                        $m_half_day_phdAllowance = $m_per_day_phdAllowance / 2;
                        // dd($m_total_paid_days,$m_total_working_days);
                        if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                            $phdAllowance_permis_deduct = ($m_per_day_phdAllowance / 7) * ($permission_shift_1 - 2);
                        } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                            $phdAllowance_permis_deduct = ($m_per_day_phdAllowance / 9) * ($permission_shift_2 - 2);
                        } else {
                            $phdAllowance_permis_deduct = 0;
                        }

                        if ($late > 3) {
                            $deduct_phdAllowance = $late - 3;

                            $late_deduct_phdAllowance = $m_half_day_phdAllowance * $deduct_phdAllowance;
                        } else {
                            $late_deduct_phdAllowance = 0;
                        }

                        if ($too_late > 0) {
                            $too_late_deduct_phdAllowance = $m_half_day_phdAllowance * $too_late;
                        } else {
                            $too_late_deduct_phdAllowance = 0;
                        }

                        $m_phdAllowance = round(
                            $salary->phdAllowance * ($m_total_paid_days / $m_total_working_days) -
                                ($phdAllowance_permis_deduct +
                                    $late_deduct_phdAllowance +
                                    $too_late_deduct_phdAllowance),
                            2,
                        );
                        $m_phdAllowance_loss = $salary->phdAllowance - $m_phdAllowance;
                    } else {
                        $m_phdAllowance = 0;
                        $m_phdAllowance_loss = 0;
                    }

                    // Other Allowance Calculation
                    if (
                        isset($salary->otherAllowence) &&
                        !empty($salary->otherAllowence && !is_nan($salary->otherAllowence))
                    ) {
                        $m_per_day_otherAllowence = $salary->otherAllowence / $m_total_working_days;

                        $m_half_day_otherAllowence = $m_per_day_otherAllowence / 2;
                        // dd($m_total_paid_days,$m_total_working_days);
                        if ($permission_shift_1 != 0 && $permission_shift_1 > 2) {
                            $otherAllowence_permis_deduct = ($m_per_day_otherAllowence / 7) * ($permission_shift_1 - 2);
                        } elseif ($permission_shift_2 != 0 && $permission_shift_2 > 2) {
                            $otherAllowence_permis_deduct = ($m_per_day_otherAllowence / 9) * ($permission_shift_2 - 2);
                        } else {
                            $otherAllowence_permis_deduct = 0;
                        }

                        if ($late > 3) {
                            $deduct_otherAllowence = $late - 3;

                            $late_deduct_otherAllowence = $m_half_day_otherAllowence * $deduct_otherAllowence;
                        } else {
                            $late_deduct_otherAllowence = 0;
                        }

                        if ($too_late > 0) {
                            $too_late_deduct_otherAllowence = $m_half_day_otherAllowence * $too_late;
                        } else {
                            $too_late_deduct_otherAllowence = 0;
                        }

                        $m_otherAllowence = round(
                            $salary->otherAllowence * ($m_total_paid_days / $m_total_working_days) -
                                ($otherAllowence_permis_deduct + $too_late_deduct_otherAllowence),
                            2,
                        );
                        $m_otherAllowence_loss = $salary->otherAllowence - $m_otherAllowence;
                    } else {
                        $m_otherAllowence = 0;
                        $m_otherAllowence_loss = 0;
                    }
                    // dd($m_agp, $m_da, $m_hra);

                    $deduction = round(
                        $m_basic_pay_loss +
                            $m_agp_loss +
                            $m_da_loss +
                            $m_hra_loss +
                            $m_specialFee_loss +
                            $m_phdAllowance_loss +
                            $m_otherAllowence_loss,
                        2,
                    );
                    $gross_salary = round(
                        $m_basic_pay + $m_agp + $m_da + $m_hra + $m_specialFee + $m_phdAllowance + $m_otherAllowence,
                        2,
                    );
                    $net_salary = round($gross_salary - $deduction, 2);
                    if ($net_salary <= 0) {
                        $net_salary = 0;
                    }

                @endphp
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.salary-statement.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card add_color">
                            <div class="card-body">
                                <div class="row gutters">
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Staff Name : </label>
                                            <input type="text" class="pay_input" name="name"
                                                value="{{ isset($salary->name) ? ($salary->name != '' ? $salary->name : '') : '' }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Designation : </label>
                                            <input type="text" class="pay_input" name="designation"
                                                value="{{ isset($salary->Designation) ? ($salary->Designation != '' ? $salary->Designation : '') : '' }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Department : </label>
                                            <input type="text" class="pay_input" name="department"
                                                value="{{ isset($salary->Dept) ? ($salary->Dept != '' ? $salary->Dept : '') : '' }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Bank Name : </label>
                                            <input type="hidden" name="updatedby" value="{{ auth()->user()->name }}">
                                            <input type="text" class="pay_input" name="bankname"
                                                value="{{ $bank != '' ? (isset($bank->bank_name) ? ($bank->bank_name != '' ? $bank->bank_name : '') : '') : '' }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>DOJ:</label>
                                            <input type="text" class="pay_input" name="doj"
                                                value="{{ $doj != '' ? (isset($doj->DOJ) ? ($doj->DOJ != '' ? date('d-m-Y', strtotime($doj->DOJ)) : '') : '') : '' }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Month : </label>
                                            <input type="text" class="pay_input"
                                                name="month"value="{{ \Carbon\Carbon::parse($day_array[15][0])->format('F') }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Year : </label>
                                            <input type="text" class="pay_input"
                                                name="year"value="{{ \Carbon\Carbon::parse($day_array[15][0])->format('Y') }}"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card add_color">
                            <div class="card-body">
                                <div class="row gutters">
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Total Days : </label>
                                            <input type="text" class="pay_input"
                                                name="total_days"value="{{ count($day_array) }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Total Working Days : </label>

                                            <input type="text" class="pay_input"
                                                name=""value="{{ count($day_array) }}" readonly>
                                        </div>
                                    </div>
                                    {{-- <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label >Weekend : </label>

                                    </div>
                                </div> --}}
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Total Late : </label>
                                            <input type="text" class="pay_input"
                                                name="late"value="{{ $late }}" readonly>
                                            <input type="hidden" class="pay_input"
                                                name=""value="{{ $late }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Total Leave : </label>
                                            @php
                                                if ($late > 3) {
                                                    $late_lop = 0.5;
                                                } else {
                                                    $late_lop = 0;
                                                }
                                                if ($leave + $half_day_leave + $too_late + $late_lop > 0) {
                                                    $total_leave_days =
                                                        $leave + $half_day_leave + $too_late + $late_lop;
                                                } else {
                                                    $total_leave_days = 0;
                                                }
                                                // dd($total_leave_days);
                                            @endphp
                                            <input type="text" class="pay_input"
                                                name="leave"value="{{ $total_leave_days }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Total Paid Days : </label>
                                            @php

                                                $paid_days =
                                                    count($day_array) -
                                                    ($leave + $half_day_leave + $too_late + $late_lop);
                                            @endphp
                                            <input type="text" class="pay_input"
                                                name="paid_days"value="{{ $paid_days }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Total Permission : </label>
                                            <input type="text" class="pay_input"
                                                name="permission"value="{{ $permission_shift_1 != 0 ? $permission_shift_1 : ($permission_shift_2 != 0 ? $permission_shift_2 : 0) }}"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card add_color">
                            <div class="card-body">
                                <div class="row gutters">
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Actual Gross Salary : </label>
                                            <input type="number" class="pay_input" name="gross_salary"
                                                value="{{ isset($salary->TotalSalary) ? ($salary->TotalSalary != '' ? $salary->TotalSalary : 0) : 0 }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Basic Pay : </label>
                                            <input type="number" class="pay_input" name="basicpay"
                                                value="{{ $m_basic_pay }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>AGP : </label>
                                            <input type="number" class="pay_input" name="agp"
                                                value="{{ $m_agp }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>DA : </label>
                                            <input type="number" class="pay_input" name="da"
                                                value="{{ $m_da }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>HRA : </label>
                                            <input type="number" class="pay_input" name="hra"
                                                value="{{ $m_hra }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Special Pay : </label>
                                            <input type="number" class="pay_input" name="specialpay"
                                                value="{{ $m_specialFee }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>PHD Allowance : </label>
                                            <input type="number" class="pay_input" name="phdallowance"
                                                value="{{ $m_phdAllowance }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Other Allowance : </label>
                                            <input type="number" class="pay_input" name="otherAllowence"
                                                value="{{ $m_otherAllowence }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Gross Salary : </label>
                                            <input type="number" class="pay_input" name="earnings"
                                                value="{{ $gross_salary }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card add_color">
                            <div class="card-body">
                                <div class="row gutters">
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>EPF</label>
                                            <input type="number" class="form-control deduct" name="epf"
                                                value="">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>ESI</label>
                                            <input type="number" class="form-control deduct" name="esi"
                                                value="">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>IT</label>
                                            <input type="number" class="form-control deduct" name="it"
                                                value="">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>PT</label>
                                            <input type="number" class="form-control deduct" name="pt"
                                                value="">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Salary In Advance</label>
                                            <input type="number" class="form-control deduct" name="salaryadvance"
                                                value="">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Other Deductions</label>
                                            <input type="number" class="form-control deduct" name="otherdeduction"
                                                value="">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Total Deductions</label>
                                            <input type="number" class="form-control" id="total_deduct"
                                                name="totaldeductions" value="" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Loss Of Pay</label>
                                            <input type="number" class="form-control" name="lop"
                                                value="{{ $deduction }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Net Salary</label>
                                            <input type="hidden" name="user_name_id" value="{{ $user_name_id }}">
                                            <input type="number" class="form-control" id="netpay" name="netpay"
                                                value="{{ $net_salary }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="text-right">

                                    <button type="submit" id="submit" name="submit"
                                        class="btn btn-outline-primary">Generate
                                        Salary Statement</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-2 text-primary text-center mt-2"> No Data Available</h5>
                </div>
            </div>
        @endif
    </div>
@endsection
@section('scripts')
    <script>
        let select_elmt = document.getElementsByClassName('status_attend');
        let up_status = document.getElementsByClassName('up_status');
        let permission = document.getElementsByClassName('permission');
        let in_time = document.getElementsByClassName('in_time');
        let out_time = document.getElementsByClassName('out_time');
        let shift = document.getElementById('shift_bn');
        let deduct = document.getElementsByClassName('deduct');
        let total_deduct = document.getElementById('total_deduct');
        let netpay = document.getElementById('netpay');

        let month_selector = document.getElementById('month');

        let loader = document.getElementById('loader');

        window.onload = function() {

            month_selector.value = '';

            for (let i = 0; i < select_elmt.length; i++) {

                if (select_elmt[i].value == 'Absent') {

                    select_elmt[i].style.backgroundColor = 'rgb(252, 45, 45)';

                }
            }

            $("#staff_code").select2();
            $("#month").select2();
            $("#year").select2();

        }
        let netpay_amount = netpay.value;
        for (let k = 0; k < deduct.length; k++) {
            deduct[k].addEventListener("change", function() {
                total_deductions = 0;
                for (let j = 0; j < deduct.length; j++) {
                    let a = parseInt(deduct[j].value) || 0;
                    total_deductions += a;
                }
                total_deduct.value = total_deductions;
                netpay.value = netpay_amount - total_deduct.value;
            });
        }

        for (let i = 0; i < select_elmt.length; i++) {

            select_elmt[i].onchange = function() {

                up_status[i].innerHTML = "{{ auth()->user()->name }}";

                if (select_elmt[i].value == 'Absent') {

                    select_elmt[i].style.backgroundColor = 'rgb(252, 45, 45)';

                } else {
                    select_elmt[i].style.backgroundColor = 'rgb(112, 253, 112)';
                }
            }

            in_time[i].onchange = function() {

                up_status[i].innerHTML = "{{ auth()->user()->name }}";
            }

            out_time[i].onchange = function() {

                up_status[i].innerHTML = "{{ auth()->user()->name }}";
            }
        }

        $(document).ready(function() {


            // Add a click event listener to the "Save Data" button
            $('#saveBtn').click(function() {

                loader.style.display = '';

                // Create an empty array to hold the data
                var data = [];
                // Loop through each row in the table
                $('#list_table tr').each(function(index, row) {
                    // Create an object to hold the data for this row
                    var rowData = {};

                    rowData['date'] = $(row).find('.date').text();
                    rowData['up_status'] = $(row).find('.up_status').text();
                    rowData['shift'] = shift.innerHTML;


                    $(row).find('select[name="status"]').each(function(index, select) {
                        rowData['status'] = $(select).val();
                    });

                    // Loop through each input field in the row
                    $(row).find('input').each(function(index, input) {
                        // Add the data from the input field to the object

                        rowData[$(input).attr('name')] = $(input).val();

                    });

                    // Add the object to the data array
                    data.push(rowData);
                });

                // Send the data to the server using AJAX
                $.ajax({
                    url: '{{ route('admin.staff-biometrics.updater') }}',
                    type: 'POST',
                    data: {
                        'data': data
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data.status == 'true') {
                            loader.style.display = "none";
                            alert('updated');
                            location.reload();
                        }


                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log(xhr.responseText);
                    }
                });
                // console.log(data);
            });
        });
    </script>
@endsection
