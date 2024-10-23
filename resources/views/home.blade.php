@php
    $role_id = auth()->user()->roles[0]->id;
    if ($role_id == 1 || $role_id == 5) {
        $key = 'layouts.admin';
    } else {
        $key = 'layouts.staffs';
    }
@endphp
@extends($key)
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />
    @php
        use Carbon\Carbon;
        $userId = auth()->user()->id;
        $user = \App\Models\User::find($userId);
        if ($user) {
            $assignedRole = $user->roles->first();
            $userName = $user->name;
            $userId = $user->id;
            if ($assignedRole) {
                $roleTitle = $assignedRole->id;
            } else {
                $roleTitle = 0;
            }

            $staff = \App\Models\Staffs::where('user_name_id', $userId)->first();
            $casualLeave = $staff ? $staff->casual_leave : '0';
            $sickLeave = $staff ? $staff->sick_leave : '0';
            $permissions = $staff ? $staff->personal_permission : '0';
            $currect_year = Carbon::now()->year;
            $currect_month = Carbon::now()->format('F');
            $ot = \App\Models\salarystatement::where('user_name_id', $userId)
                ->where('month', $currect_month)
                ->where('year', $currect_year)
                ->first();

            $ot_hours = $ot ? $ot->ot : '0';
        }
    @endphp
    @if ($roleTitle == 1)
        <div class="row">
            <div class="col-lg-12">
                <p id="welcome">Welcome Admin!</p>
                <p id="dashboard">Dashboard</p>
            </div>
        </div>
        <div class="row card_row">
            <div class="col-lg-3 col-md-6 mt-lg-0 mt-2">
                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="icon-container">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="col-6 text-right">
                            <p id="employee_count" class="counts">{{ $staffsCount }}</p>
                            <p id="employee"><b class="text-primary staff-box">SHI Staffs</b></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mt-lg-0 mt-2">
                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="icon-container">
                                <i class="fas fa-cubes"></i>
                            </div>
                        </div>
                        <div class="col-6 text-right">
                            <p id="project_count" class="counts">{{ $projectCount }}</p>
                            <p id="projects"><b class="text-warning staff-box">Projects</b></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mt-lg-0 mt-2">
                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="icon-container">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="col-6 text-right">
                            <p id="present_count" class="counts">{{ $staff_present }}</p>
                            <p id="projects"><b class="text-success staff-box">Present</b></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mt-lg-0 mt-2">
                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="icon-container">
                                <i class="fas fa-user-slash"></i>
                            </div>
                        </div>
                        <div class="col-6 text-right">
                            <p id="absent_count" class="counts">{{ $staff_absent }}</p>
                            <p id="projects"><b class="text-danger staff-box">Absent</b></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row card_row-2 mt-4">
            <div class="col-lg-4 col-md-6 mt-lg-0 mt-2">
                <div class="card card-2">
                    <div class="row">
                        <div class="col-12">
                            <div class="box-1">
                                <h4 class="text-center"><b>Calender</b></h4>
                            </div>
                            <div class="card-body">
                                <div class="calendar-header">
                                    <button class="btn btn-sm btn-primary" style="background-color:#7a40d2; border:none;"
                                        id="prevMonth">Prev</button>
                                    <div id="monthYear"></div>
                                    <button class="btn btn-sm btn-primary" style="background-color:#7a40d2; border:none;"
                                        id="nextMonth">Next</button>
                                </div>
                                <div class="calendar-days-header">
                                    <!-- Weekday Names -->
                                    <div class="calendar-day-name">Sun</div>
                                    <div class="calendar-day-name">Mon</div>
                                    <div class="calendar-day-name">Tue</div>
                                    <div class="calendar-day-name">Wed</div>
                                    <div class="calendar-day-name">Thu</div>
                                    <div class="calendar-day-name">Fri</div>
                                    <div class="calendar-day-name">Sat</div>
                                </div>
                                <div class="calendar" id="calendarDays"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mt-lg-0 mt-2">
                <div class="card card-2 "style="overflow-y: auto; overflow-x: hidden; white-space: nowrap;">
                    <div class="row">
                        <div class="col-12">
                            <div class="box-1">
                                <h4 class="text-center"><b>Request</b></h4>
                            </div>
                            <div class="card-body">
                                @if ($alertData->isEmpty())
                                    <div class="card"
                                        style="height: 35px; border-radius: 20px; display: flex; align-items: center; justify-content: center;">
                                        <p style="margin: 0;">No requests</p>
                                    </div>
                                @else
                                    @foreach ($alertData as $alert)
                                        <div class="card"
                                            style="height: 35px; border-radius: 20px; margin-bottom: 10px; display: flex; align-items: center; justify-content: center;">
                                            <p style="margin: 0;">
                                                <a href="{{ $alert['link'] }}" target="_blank"
                                                    style="text-decoration: none; color: inherit;">{{ $alert['text'] }}
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i
                                                        class="fas fa-arrow-right"></i></a>

                                            </p>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <style>
            .select2 {
                width: 100% !important;
            }

            #welcome {
                margin-top: -10px;
                font-size: 28px;
                font-weight: bold;
            }

            #dashboard {
                margin-top: -10px;
                color: gray;
                font-size: 20px;
            }

            .card {
                height: 110px;
                border-bottom: 5px solid #007bff;
            }

            .icon-container {
                background-color: #d3d8df;
                border-radius: 50%;
                width: 60px;
                height: 60px;
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 20px 0 0 20px;
            }

            .icon-container i {
                color: black;
                font-size: 26px;
            }

            .counts {
                margin-right: 35px;
                font-size: 26px;
                font-weight: bold;
                margin-top: 20px;
            }

            .card_row>.col-lg-3:nth-child(1)>.card {
                border-bottom: 3px solid #71a5f4;
            }

            .card_row .col-lg-3:nth-child(2) .card {
                border-bottom: 3px solid rgb(242, 242, 136);
            }

            .card_row .col-lg-3:nth-child(3) .card {
                border-bottom: 3px solid rgb(120, 232, 120);
            }

            .card_row .col-lg-3:nth-child(4) .card {
                border-bottom: 3px solid rgb(244, 6, 6);
            }

            .logo-line-height {
                line-height: 0.9;
            }

            .card_row .col-lg-3:nth-child(1) .icon-container {
                background-color: #71a5f4;
            }

            .card_row .col-lg-3:nth-child(2) .icon-container {
                background-color: rgb(242, 242, 136);
            }

            .card_row .col-lg-3:nth-child(3) .icon-container {
                background-color: rgb(120, 232, 120);
            }

            .card_row .col-lg-3:nth-child(4) .icon-container {
                background-color: rgb(240, 44, 44);
            }

            .card-2 {
                height: 370px;
            }

            .box-1 {
                border-bottom: 1px solid #d3c4c4;
                padding-top: 8px;
            }

            .box-1-insidebox {
                padding-top: 8px;
            }

            hr {
                margin: 9px 0 !important;
            }

            .margin {
                margin-top: 7px;
                flex-wrap: nowrap !important;
            }

            .leave-line,
            .request-line {
                font-size: 15px;
                font-weight: bold;
                border: none !important;
            }

            .leave-line.active,
            .request-line.active {
                color: orange !important;
            }

            #myTab {
                border: none;
            }

            .box-1-insidebox-2 {
                overflow-y: scroll;
                width: 100%;
                height: 220px;
            }

            .box-1-insidebox-2::-webkit-scrollbar {
                display: none;
            }

            .name {
                border: 1px solid grey !important;
            }

            .border-outline {
                line-height: 2;
                list-style-type: none;
                padding: 0;
                margin: 0;
            }

            .border-outline li {
                border: 1px solid red;
                border-radius: 5px;
                margin-top: 10px;
            }

            .rounded-icon {
                display: inline-block;
                width: 30px;
                height: 30px;
                line-height: 30px;
                border-radius: 50%;
                background-color: #f00;
                color: white;
                text-align: center;
                font-weight: bold;
                font-family: Arial, sans-serif;
                margin-left: 11px;
            }

            .icon-arrow {
                margin-right: 8px !important;
            }

            .leave-request {
                border: 1px solid grey;
                border-radius: 10px;
            }

            .leave-request-insideline {
                font-size: 11px;
                margin-left: 7px;
            }

            .calendar {
                display: flex;
                flex-wrap: wrap;
                justify-content: start;
            }

            .calendar-day {
                width: calc(100% / 7);
                /* padding: 5px; */
                line-height: 2.2rem;
                text-align: center;
                margin-bottom: 2px;
                box-sizing: border-box;
            }



            .calendar-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 5px;
                font-weight: bold;
            }

            .calendar-days-header {
                display: flex;
                justify-content: space-between;
                font-weight: bold;
                padding: 10px 0;
            }

            .calendar-day-name {
                width: calc(100% / 7);
                text-align: center;
                font-size: 14px;
            }

            .highlighted {
                background-color: #ffcc00;
                color: white;
                border-radius: 50%;
            }


            @media screen and (max-width:576px) {
                .box-1-insidebox-2 {
                    overflow-y: scroll;
                    width: 100%;
                    height: 230px;
                }
            }

            @media screen and (max-width:3556px) {
                .box-1-insidebox-2 {
                    overflow-y: scroll;
                    width: 100%;
                    height: 220px;
                }
            }

            @media screen and (min-witdh:990px) and (max-width:1030) {
                .staff-box {
                    font-size: 13px !important;
                }
            }

            .calendar-day.disabled {
                color: #ccc;
                pointer-events: none;
            }

            .calendar-day.current {
                background-color: rgb(243, 187, 82);
                color: white;
                /* border-radius: 70%; */
                /* border: 2px solid #ff9800;  */
            }

            .calendar-day.highlighted {
                background-color: rgb(10, 184, 242);
                color: white;
                border-radius: 50%;
            }

            /* .calendar-day:hover {
                                                                                                                                                                                background-color: lightgray;
                                                                                                                                                                            } */

            .staff-box {
                margin-right: 15px;
            }
        </style>
    @elseif($roleTitle == 5)
        <div class="row">
            <div class="col-lg-12">
                <p id="welcome">Welcome HR!</p>
                <p id="dashboard">Dashboard</p>
            </div>
        </div>
        <div class="row card_row">
            <div class="col-lg-3 col-md-6 mt-lg-0 mt-2">
                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="icon-container">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="col-6 text-right">
                            <p id="employee_count" class="counts">{{ $staffsCount }}</p>
                            <p id="employee"><b class="text-primary staff-box">SHI Staffs</b></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mt-lg-0 mt-2">
                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="icon-container">
                                <i class="fas fa-cubes"></i>
                            </div>
                        </div>
                        <div class="col-6 text-right">
                            <p id="project_count" class="counts">{{ $projectCount }}</p>
                            <p id="projects"><b class="text-warning staff-box">Projects</b></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mt-lg-0 mt-2">
                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="icon-container">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="col-6 text-right">
                            <p id="present_count" class="counts">{{ $staff_present }}</p>
                            <p id="projects"><b class="text-success staff-box">Present</b></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mt-lg-0 mt-2">
                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="icon-container">
                                <i class="fas fa-user-slash"></i>
                            </div>
                        </div>
                        <div class="col-6 text-right">
                            <p id="absent_count" class="counts">{{ $staff_absent }}</p>
                            <p id="projects"><b class="text-danger staff-box">Absent</b></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row card_row-2 mt-4">
            <div class="col-lg-4 col-md-6 mt-lg-0 mt-2">
                <div class="card card-2">
                    <div class="row">
                        <div class="col-12">
                            <div class="box-1">
                                <h4 class="text-center"><b>Calender</b></h4>
                            </div>
                            <div class="card-body">
                                <div class="calendar-header">
                                    <button class="btn btn-sm btn-primary" style="background-color:#7a40d2; border:none;"
                                        id="prevMonth">Prev</button>
                                    <div id="monthYear"></div>
                                    <button class="btn btn-sm btn-primary" style="background-color:#7a40d2; border:none;"
                                        id="nextMonth">Next</button>
                                </div>
                                <div class="calendar-days-header">
                                    <!-- Weekday Names -->
                                    <div class="calendar-day-name">Sun</div>
                                    <div class="calendar-day-name">Mon</div>
                                    <div class="calendar-day-name">Tue</div>
                                    <div class="calendar-day-name">Wed</div>
                                    <div class="calendar-day-name">Thu</div>
                                    <div class="calendar-day-name">Fri</div>
                                    <div class="calendar-day-name">Sat</div>
                                </div>
                                <div class="calendar" id="calendarDays"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mt-lg-0 mt-2">
                <div class="card card-2 "style="overflow-y: auto; overflow-x: hidden; white-space: nowrap;">
                    <div class="row">
                        <div class="col-12">
                            <div class="box-1">
                                <h4 class="text-center"><b>Request</b></h4>
                            </div>
                            <div class="card-body">
                                @if ($alertData->isEmpty())
                                    <div class="card"
                                        style="height: 35px; border-radius: 20px; display: flex; align-items: center; justify-content: center;">
                                        <p style="margin: 0;">No requests</p>
                                    </div>
                                @else
                                    @foreach ($alertData as $alert)
                                        <div class="card"
                                            style="height: 35px; border-radius: 20px; margin-bottom: 10px; display: flex; align-items: center; justify-content: center;">
                                            <p style="margin: 0;">
                                                <a href="{{ $alert['link'] }}" target="_blank"
                                                    style="text-decoration: none; color: inherit;">{{ $alert['text'] }}
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i
                                                        class="fas fa-arrow-right"></i></a>

                                            </p>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <style>
            .select2 {
                width: 100% !important;
            }

            #welcome {
                margin-top: -10px;
                font-size: 28px;
                font-weight: bold;
            }

            #dashboard {
                margin-top: -10px;
                color: gray;
                font-size: 20px;
            }

            .card {
                height: 110px;
                border-bottom: 5px solid #007bff;
            }

            .icon-container {
                background-color: #d3d8df;
                border-radius: 50%;
                width: 60px;
                height: 60px;
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 20px 0 0 20px;
            }

            .icon-container i {
                color: black;
                font-size: 26px;
            }

            .counts {
                margin-right: 35px;
                font-size: 26px;
                font-weight: bold;
                margin-top: 20px;
            }

            .card_row>.col-lg-3:nth-child(1)>.card {
                border-bottom: 3px solid #71a5f4;
            }

            .card_row .col-lg-3:nth-child(2) .card {
                border-bottom: 3px solid rgb(242, 242, 136);
            }

            .card_row .col-lg-3:nth-child(3) .card {
                border-bottom: 3px solid rgb(120, 232, 120);
            }

            .card_row .col-lg-3:nth-child(4) .card {
                border-bottom: 3px solid rgb(244, 6, 6);
            }

            .logo-line-height {
                line-height: 0.9;
            }

            .card_row .col-lg-3:nth-child(1) .icon-container {
                background-color: #71a5f4;
            }

            .card_row .col-lg-3:nth-child(2) .icon-container {
                background-color: rgb(242, 242, 136);
            }

            .card_row .col-lg-3:nth-child(3) .icon-container {
                background-color: rgb(120, 232, 120);
            }

            .card_row .col-lg-3:nth-child(4) .icon-container {
                background-color: rgb(240, 44, 44);
            }

            .card-2 {
                height: 370px;
            }

            .box-1 {
                border-bottom: 1px solid #d3c4c4;
                padding-top: 8px;
            }

            .box-1-insidebox {
                padding-top: 8px;
            }

            hr {
                margin: 9px 0 !important;
            }

            .margin {
                margin-top: 7px;
                flex-wrap: nowrap !important;
            }

            .leave-line,
            .request-line {
                font-size: 15px;
                font-weight: bold;
                border: none !important;
            }

            .leave-line.active,
            .request-line.active {
                color: orange !important;
            }

            #myTab {
                border: none;
            }

            .box-1-insidebox-2 {
                overflow-y: scroll;
                width: 100%;
                height: 220px;
            }

            .box-1-insidebox-2::-webkit-scrollbar {
                display: none;
            }

            .name {
                border: 1px solid grey !important;
            }

            .border-outline {
                line-height: 2;
                list-style-type: none;
                padding: 0;
                margin: 0;
            }

            .border-outline li {
                border: 1px solid red;
                border-radius: 5px;
                margin-top: 10px;
            }

            .rounded-icon {
                display: inline-block;
                width: 30px;
                height: 30px;
                line-height: 30px;
                border-radius: 50%;
                background-color: #f00;
                color: white;
                text-align: center;
                font-weight: bold;
                font-family: Arial, sans-serif;
                margin-left: 11px;
            }

            .icon-arrow {
                margin-right: 8px !important;
            }

            .leave-request {
                border: 1px solid grey;
                border-radius: 10px;
            }

            .leave-request-insideline {
                font-size: 11px;
                margin-left: 7px;
            }

            .calendar {
                display: flex;
                flex-wrap: wrap;
                justify-content: start;
            }

            .calendar-day {
                width: calc(100% / 7);
                /* padding: 5px; */
                line-height: 2.2rem;
                text-align: center;
                margin-bottom: 2px;
                box-sizing: border-box;
            }



            .calendar-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 5px;
                font-weight: bold;
            }

            .calendar-days-header {
                display: flex;
                justify-content: space-between;
                font-weight: bold;
                padding: 10px 0;
            }

            .calendar-day-name {
                width: calc(100% / 7);
                text-align: center;
                font-size: 14px;
            }

            .highlighted {
                background-color: #ffcc00;
                color: white;
                border-radius: 50%;
            }


            @media screen and (max-width:576px) {
                .box-1-insidebox-2 {
                    overflow-y: scroll;
                    width: 100%;
                    height: 230px;
                }
            }

            @media screen and (max-width:3556px) {
                .box-1-insidebox-2 {
                    overflow-y: scroll;
                    width: 100%;
                    height: 220px;
                }
            }

            @media screen and (min-witdh:990px) and (max-width:1030) {
                .staff-box {
                    font-size: 13px !important;
                }
            }

            .calendar-day.disabled {
                color: #ccc;
                pointer-events: none;
            }

            .calendar-day.current {
                background-color: rgb(243, 187, 82);
                color: white;
                /* border-radius: 70%; */
                /* border: 2px solid #ff9800;  */
            }

            .calendar-day.highlighted {
                background-color: rgb(10, 184, 242);
                color: white;
                border-radius: 50%;
            }

            .staff-box {
                margin-right: 15px;
            }
        </style>
    @elseif($roleTitle == 2)
        <div class="row">
            <div class="col-lg-12">
                <p id="welcome">Welcome &nbsp;&nbsp;{{ $userName }}</p>
                <p id="dashboard">Dashboard</p>
            </div>
        </div>
        <div class="row card_row">
            <div class="col-lg-3 col-md-6 mt-lg-0 mt-2">
                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="icon-container">
                                <i class="far fa-calendar-alt"></i>
                            </div>
                        </div>
                        <div class="col-6 text-right">
                            <p id="casual_leave_count" class="counts">{{ $casualLeave }}</p>
                            <p id="casual_leave"><b class="text-success staff-box">Casual Leave</b></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mt-lg-0 mt-2">
                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="icon-container">
                                <i class="far fa-hospital"></i>
                            </div>
                        </div>
                        <div class="col-6 text-right">
                            <p id="sick_leave_count" class="counts">{{ $sickLeave }}</p>
                            <p id="sick_leave"><b class="text-primary staff-box">Sick Leave</b></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mt-lg-0 mt-2">
                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="icon-container">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                        <div class="col-6 text-right">
                            <p id="permission_count" class="counts">{{ $permissions }}</p>
                            <p id="permission"><b class="text-warning staff-box">Permissions</b></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mt-lg-0 mt-2">
                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="icon-container">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="col-6 text-right">
                            <p id="permission_count" class="counts">{{ $ot_hours }}</p>
                            <p id="permission"><b class="text-danger staff-box">Total OT Hours</b></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row card_row-2 mt-3">

            <div class="col-lg-4 col-md-6 mt-lg-0 mt-2">
                <div class="card card-2">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0 text-center"><strong>Announcement</strong></h5>
                        </div>
                        <div class="card-body">
                            <i class="fas fa-bullhorn fa-3x text-warning mt-1"></i>
                            <p class="card-text mt-4"><strong>Dear Employees</strong></p>
                            <p class="card-text">
                                Please note that the new leave policy will be effective starting next month. Make sure to
                                submit your leave requests on time. For any inquiries, contact HR.
                            </p>
                            <small class="text-muted">Posted on: October 23, 2024</small>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-lg-4 col-md-6 mt-lg-0 mt-2">
                <div class="card card-2">
                    <div class="row">
                        <div class="col-12">
                            <div class="box-1">
                                <h4 class="text-center"><b>Calender</b></h4>
                            </div>
                            <div class="card-body">
                                <div class="calendar-header">
                                    <button class="btn btn-sm btn-primary" style="background-color:#7a40d2; border:none;"
                                        id="prevMonth">Prev</button>
                                    <div id="monthYear"></div>
                                    <button class="btn btn-sm btn-primary" style="background-color:#7a40d2; border:none;"
                                        id="nextMonth">Next</button>
                                </div>
                                <div class="calendar-days-header">
                                    <!-- Weekday Names -->
                                    <div class="calendar-day-name">Sun</div>
                                    <div class="calendar-day-name">Mon</div>
                                    <div class="calendar-day-name">Tue</div>
                                    <div class="calendar-day-name">Wed</div>
                                    <div class="calendar-day-name">Thu</div>
                                    <div class="calendar-day-name">Fri</div>
                                    <div class="calendar-day-name">Sat</div>
                                </div>
                                <div class="calendar" id="calendarDays"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




            <div class="col-lg-4 col-md-6 mt-lg-0 mt-2">
                <div class="card card-2">
                    <div class="col-12">
                        <div class="box-1">
                            <h4 class="text-center"><b>Request</b></h4>
                        </div>
                        <div class="card-body">
                            <div class="card"
                                style="height: 35px; border-radius: 20px; display: flex; align-items: center; justify-content: center;">
                                <p style="margin: 0;">No requests</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


        </div>


        <style>
            .box-1 {
                border-bottom: 1px solid #d3c4c4;
                padding-top: 8px;
            }

            .box-1-insidebox {
                padding-top: 8px;
            }

            .box-1-insidebox-2 {
                overflow-y: scroll;
                width: 100%;
                height: 220px;
            }

            .box-1-insidebox-2::-webkit-scrollbar {
                display: none;
            }

            #welcome {
                margin-top: -10px;
                font-size: 28px;
                font-weight: bold;
            }

            #dashboard {
                margin-top: -10px;
                color: gray;
                font-size: 20px;
            }

            .staff-box {
                margin-right: 15px;
            }

            .card {
                height: 110px;
                /* border-bottom: 5px solid #007bff; */
            }

            .icon-container {
                background-color: #d3d8df;
                border-radius: 50%;
                width: 60px;
                height: 60px;
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 20px 0 0 20px;
            }

            .icon-container i {
                color: black;
                font-size: 26px;
            }

            .counts {
                margin-right: 35px;
                font-size: 26px;
                font-weight: bold;
                margin-top: 20px;
            }

            .card_row>.col-lg-3:nth-child(1)>.card {
                border-bottom: 3px solid #28a745;
            }

            .card_row .col-lg-3:nth-child(2) .card {
                border-bottom: 3px solid #007bff;
            }

            .card_row .col-lg-3:nth-child(3) .card {
                border-bottom: 3px solid #ffc107;
            }

            .card_row .col-lg-3:nth-child(4) .card {
                border-bottom: 3px solid rgb(244, 6, 6);
            }

            .logo-line-height {
                line-height: 0.9;
            }

            .card_row .col-lg-3:nth-child(1) .icon-container {
                background-color: #28a745;
            }

            .card_row .col-lg-3:nth-child(2) .icon-container {
                background-color: #007bff;
            }

            .card_row .col-lg-3:nth-child(3) .icon-container {
                background-color: #ffc107;
            }

            .card_row .col-lg-3:nth-child(4) .icon-container {
                background-color: rgb(240, 44, 44);
            }

            .card-2 {
                height: 320px;
                border-bottom: 5px solid #007bff;
            }

            @media screen and (min-witdh:990px) and (max-width:1030) {
                .staff-box {
                    font-size: 13px !important;
                }
            }

            .calendar-day.disabled {
                color: #ccc;
                pointer-events: none;
            }

            .calendar-day.current {
                background-color: rgb(243, 187, 82);
                color: white;
            }

            .calendar {
                display: flex;
                flex-wrap: wrap;
                justify-content: start;
            }

            .calendar-day {
                width: calc(100% / 7);
                /* padding: 5px; */
                line-height: 2.2rem;
                text-align: center;
                margin-bottom: 2px;
                box-sizing: border-box;
            }



            .calendar-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 5px;
                font-weight: bold;
            }

            .calendar-days-header {
                display: flex;
                justify-content: space-between;
                font-weight: bold;
                padding: 10px 0;
            }

            .calendar-day-name {
                width: calc(100% / 7);
                text-align: center;
                font-size: 14px;
            }

            .highlighted {
                background-color: rgb(10, 184, 242);
                color: white;
                /* border-radius: 50%; */
            }
        </style>
    @endif


@endsection
@section('scripts')
    @parent
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
    <script src="your-js-file.js"></script>
    <script>
        // Calender JS Start
        const currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();
        let selectedDate = null; // To track the selected date

        function renderCalendar(month, year) {
            const daysContainer = $('#calendarDays');
            const monthYearDisplay = $('#monthYear');
            daysContainer.empty();

            const monthNames = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];
            const lastDay = new Date(year, month + 1, 0).getDate(); // Last day of the month
            const firstDay = new Date(year, month, 1).getDay(); // First day of the month

            monthYearDisplay.text(monthNames[month] + " " + year);

            // Fill in empty slots for the days before the first day of the month
            for (let i = 0; i < firstDay; i++) {
                daysContainer.append('<div class="calendar-day"></div>');
            }

            // Add days of the month
            for (let day = 1; day <= lastDay; day++) {
                const dayElement = $('<div class="calendar-day">' + day + '</div>');

                // Check if it's the current day
                const isCurrentDate = (day === currentDate.getDate() && month === currentDate.getMonth() && year ===
                    currentDate.getFullYear());

                if (isCurrentDate) {
                    dayElement.addClass('current'); // Add 'current' class to highlight today
                }

                // Add click event to highlight the clicked day
                dayElement.click(function() {
                    if (selectedDate) {
                        selectedDate.removeClass(
                            'highlighted'); // Remove highlight class from previously selected date
                    }

                    // Set the currently selected date and highlight it
                    selectedDate = dayElement;
                    selectedDate.addClass('highlighted'); // Add highlight class to the clicked date
                });

                daysContainer.append(dayElement);
            }
        }

        // Initialize calendar
        renderCalendar(currentMonth, currentYear);

        // Previous month button click
        $('#prevMonth').click(function() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar(currentMonth, currentYear);
        });

        // Next month button click
        $('#nextMonth').click(function() {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar(currentMonth, currentYear);
        });
    </script>
@endsection
